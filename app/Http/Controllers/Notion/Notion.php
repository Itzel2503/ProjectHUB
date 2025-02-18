<?php

namespace App\Http\Controllers\Notion;

use App\Http\Controllers\Controller;
use App\Models\ChatNotion;
use App\Models\Notion as ModelsNotion;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Notion extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (Auth::check()) {
            $allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
            $projects = Project::orderBy('name', 'asc')->get();
            $userId = Auth::id();

            $notas = ModelsNotion::where('user_id', $userId)
                ->orWhereHas('delegate', function ($query) use ($userId) {
                    $query->where('users.id', $userId);
                })
                ->with(['user', 'project', 'chats']) // Cargar relaciones completas, incluyendo chats
                ->get()
                ->map(function ($nota) {
                    // Agregar atributos personalizados
                    $nota->user_name = $nota->user->name ?? 'Sin usuario';
                    $nota->project_name = $nota->project->name ?? 'Sin proyecto';
                    $nota->project_id = $nota->project->id ?? null;

                    // Agregar los delegados de la tabla pivote
                    $nota->delegates = $nota->delegate->map(function ($delegate) {
                        return [
                            'id' => $delegate->id,
                            'name' => $delegate->name,
                        ];
                    });

                    // Verificar si al menos un chat tiene look en false
                    $nota->has_unseen_chats = $nota->chats->contains('look', false);

                    return $nota;
                });
            return view('notion.notion', compact('projects', 'allUsers', 'notas'));
        } else {
            return redirect('/login');
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Combina la fecha y la hora
        $starTime = ($request->starTime) ? $request->starTime : '00:00';
        $endTime = ($request->endTime) ? $request->endTime : '00:00';

        $dateStartTime = $request->dateFirst . ' ' . $starTime; // Si hay hora, combínala con la fecha
        $dateEndTime = $request->dateSecond . ' ' . $endTime; // Si hay hora, combínala con la fecha

        $notion = new ModelsNotion();
        $notion->project_id = ($request->project_id == 0) ? null : $request->project_id;
        $notion->user_id = Auth::id();
        $notion->note_repeat = null;
        $notion->icon = $request->icon;
        $notion->color = ($request->color) ? $request->color : '#2e4c5f';
        $notion->title = $request->title;
        $notion->start_date = $dateStartTime;
        $notion->end_date = $dateEndTime;

        if ($request->priority1) {
            $notion->priority = 'Alto';
        } else if ($request->priority2) {
            $notion->priority = 'Medio';
        } else if ($request->priority3) {
            $notion->priority = 'Bajo';
        }

        $notion->status = 'Abierto';
        $notion->repeat = ($request->repeat != null) ? $request->repeat : 'Once';

        // Crear repeticiones según el filtro seleccionado
        switch ($request->repeat) {
            case 'Dairy':
                $this->createDailyRepetitions($notion);
                break;
            case 'Weeks':
                $this->createWeeklyRepetitions($notion);
                break;
            case 'Months':
                $this->createMonthlyRepetitions($notion);
                break;
            case 'Years':
                $this->createYearlyRepetitions($notion);
                break;
            default:
                // No se repite (opción "Once")
                $notion->save();

                // Sincronizar usuarios relacionados en la tabla pivote
                if ($request->has('delegate_id')) {
                    $notion->delegate()->sync($request->delegate_id); // Guarda los IDs en la tabla pivote
                }
        }

        return redirect()->route('calendar.index')->with('success', 'Nota creada correctamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($request->updateDates) {
            // Validar los datos del formulario
            $request->validate([
                'start' => 'required|date',
                'end' => 'nullable|date',
                'updateAllEvents' => 'nullable|boolean', // Asegúrate de validar la nueva variable
            ]);

            // Buscar la nota por su ID
            $notion = ModelsNotion::findOrFail($id);

            // Formatear la fecha de inicio al formato deseado (Y-m-d H:i:s)
            $startDate = Carbon::parse($request->start)->format('Y-m-d H:i:s');
            $endDate = Carbon::parse($request->end)->format('Y-m-d H:i:s');

            // Si el evento es de todo el día (allDay), ajustar las horas a 00:00:00
            if ($request->allDay) {
                // Ajustar la hora de "start" a 00:00:00
                $startDate = Carbon::parse($startDate)->startOfDay()->format('Y-m-d H:i:s');

                // Ajustar la hora de "end" a 00:00:00 y restar un día
                if ($endDate) {
                    $endDate = Carbon::parse($endDate)->startOfDay()->subDay()->format('Y-m-d H:i:s');
                }
            }

            // Actualizar las fechas
            $notion->start_date = $startDate;
            $notion->end_date = $endDate;

            // Si updateAllEvents es true, actualizar todos los eventos relacionados
            if ($request->updateAllEvents && $request->repeat != 'Once') {
                // Obtener todos los eventos relacionados con note_repeat
                $relatedNotions = ModelsNotion::where('note_repeat', $notion->note_repeat)->get();

                // Recorrer cada evento relacionado
                foreach ($relatedNotions as $relatedNotion) {
                    // Eliminar los registros de la tabla pivote (notion_users) para este evento
                    DB::table('notion_users')->where('notion_id', $relatedNotion->id)->delete();

                    // Obtener todos los chats asociados al evento relacionado
                    $chatNotion = ChatNotion::where('notion_id', $relatedNotion->id)->get();

                    // Recorrer cada chat para eliminar archivos y mensajes
                    foreach ($chatNotion as $chat) {
                        // Si el chat tiene contenido (archivos), eliminarlos
                        if ($chat->content) {
                            $contentPath = 'notion/' . $chat->content; // Ruta relativa del archivo
                            $fullPath = public_path($contentPath); // Ruta absoluta del archivo

                            // Verificar si el archivo existe y eliminarlo
                            if (File::exists($fullPath)) {
                                File::delete($fullPath);
                            }
                        }

                        // Eliminar el chat
                        $chat->delete();
                    }

                    // Eliminar el evento relacionado
                    $relatedNotion->delete();
                }

                // Crear nuevos eventos según la frecuencia seleccionada
                switch ($notion->repeat) {
                    case 'Dairy':
                        $this->createDailyRepetitions($notion);
                        break;
                    case 'Weeks':
                        $this->createWeeklyRepetitions($notion);
                        break;
                    case 'Months':
                        $this->createMonthlyRepetitions($notion);
                        break;
                    case 'Years':
                        $this->createYearlyRepetitions($notion);
                        break;
                    default:
                        // No se repite (opción "Once")
                        $notion->save();

                        break;
                }
            } else {
                // Guardar los cambios
                $notion->save();
            }
        } else {
            try {
                // Validar los datos del formulario (opcional)
                $request->validate([
                    'title' => 'required|string|max:255',
                    'dateFirst' => 'required|date',
                    'dateSecond' => 'required|date',
                    'editAllEvents' => 'nullable|boolean', // Asegúrate de validar la nueva variable
                ]);
                // Aquí puedes continuar con tu lógica después de la validación exitosa
            } catch (\Illuminate\Validation\ValidationException $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al actualizar la nota: ' . $e->getMessage(),
                ], 500);
                throw $e;
            }

            // Buscar la nota por su ID
            $notion = ModelsNotion::findOrFail($id);

            // Combina la fecha y la hora
            $starTime = ($request->starTime) ? $request->starTime : '00:00';
            $endTime = ($request->endTime) ? $request->endTime : '00:00';
            $dateStartTime = $request->dateFirst . ' ' . $starTime;
            $dateEndTime = $request->dateSecond . ' ' . $endTime;

            // Actualizar los campos
            $notion->project_id = ($request->project_id == 0) ? null : $request->project_id;
            $notion->color = ($request->color) ? $request->color : '#2e4c5f';
            $notion->title = $request->title;
            $notion->start_date = $dateStartTime;
            $notion->end_date = $dateEndTime;

            if ($request->priority1) {
                $notion->priority = 'Alto';
            } else if ($request->priority2) {
                $notion->priority = 'Medio';
            } else if ($request->priority3) {
                $notion->priority = 'Bajo';
            }

            $notion->repeat = $request->repeat;

            // Si el evento tiene eventos relacionados
            if ($request->noRepeat == false) {
                // Eleccion de otro repeat del que ya esta guardado
                if ($request->changeRepetition == true) {
                    // Eliminar todos los eventos relacionados por note_repeat
                    if ($request->repeat != 'Once') {
                        $relatedNotions = ModelsNotion::where('note_repeat', $notion->note_repeat)->get();
                    } else {
                        $relatedNotions = ModelsNotion::where('note_repeat', $notion->note_repeat)
                            ->where('id', '!=', $notion->id) // Excluir el registro actual
                            ->get();
                    }

                    // Recorrer cada evento relacionado
                    foreach ($relatedNotions as $relatedNotion) {
                        // Eliminar los registros de la tabla pivote (notion_users) para este evento
                        DB::table('notion_users')->where('notion_id', $relatedNotion->id)->delete();

                        // Obtener todos los chats asociados al evento relacionado
                        $chatNotion = ChatNotion::where('notion_id', $relatedNotion->id)->get();

                        // Recorrer cada chat para eliminar archivos y mensajes
                        foreach ($chatNotion as $chat) {
                            // Si el chat tiene contenido (archivos), eliminarlos
                            if ($chat->content) {
                                $contentPath = 'notion/' . $chat->content; // Ruta relativa del archivo
                                $fullPath = public_path($contentPath); // Ruta absoluta del archivo

                                // Verificar si el archivo existe y eliminarlo
                                if (File::exists($fullPath)) {
                                    File::delete($fullPath);
                                }
                            }

                            // Eliminar el chat
                            $chat->delete();
                        }

                        // Eliminar el evento relacionado
                        $relatedNotion->delete();
                    }

                    // Crear nuevos eventos según la frecuencia seleccionada
                    switch ($request->repeat) {
                        case 'Dairy':
                            $this->createDailyRepetitions($notion);
                            break;
                        case 'Weeks':
                            $this->createWeeklyRepetitions($notion);
                            break;
                        case 'Months':
                            $this->createMonthlyRepetitions($notion);
                            break;
                        case 'Years':
                            $this->createYearlyRepetitions($notion);
                            break;
                        default:
                            // No se repite (opción "Once")
                            $notion->save();

                            // Sincronizar usuarios relacionados en la tabla pivote
                            if ($request->has('delegate_id')) {
                                $notion->delegate()->sync($request->delegate_id);
                            }
                            break;
                    }
                } else {
                    // Editar todos los eventos o solo el seleccionado
                    if ($request->editAllEvents == true) {
                        // Eliminar todos los eventos relacionados por note_repeat
                        $relatedNotions = ModelsNotion::where('note_repeat', $notion->note_repeat)->get();

                        // Recorrer cada evento relacionado
                        foreach ($relatedNotions as $relatedNotion) {
                            // Eliminar los registros de la tabla pivote (notion_users) para este evento
                            DB::table('notion_users')->where('notion_id', $relatedNotion->id)->delete();

                            // Obtener todos los chats asociados al evento relacionado
                            $chatNotion = ChatNotion::where('notion_id', $relatedNotion->id)->get();

                            // Recorrer cada chat para eliminar archivos y mensajes
                            foreach ($chatNotion as $chat) {
                                // Si el chat tiene contenido (archivos), eliminarlos
                                if ($chat->content) {
                                    $contentPath = 'notion/' . $chat->content; // Ruta relativa del archivo
                                    $fullPath = public_path($contentPath); // Ruta absoluta del archivo

                                    // Verificar si el archivo existe y eliminarlo
                                    if (File::exists($fullPath)) {
                                        File::delete($fullPath);
                                    }
                                }

                                // Eliminar el chat
                                $chat->delete();
                            }

                            // Eliminar el evento relacionado
                            $relatedNotion->delete();
                        }

                        // Crear nuevos eventos según la frecuencia seleccionada
                        switch ($request->repeat) {
                            case 'Dairy':
                                $this->createDailyRepetitions($notion);
                                break;
                            case 'Weeks':
                                $this->createWeeklyRepetitions($notion);
                                break;
                            case 'Months':
                                $this->createMonthlyRepetitions($notion);
                                break;
                            case 'Years':
                                $this->createYearlyRepetitions($notion);
                                break;
                            default:
                                // No se repite (opción "Once")
                                $notion->save();

                                // Sincronizar usuarios relacionados en la tabla pivote
                                if ($request->has('delegate_id')) {
                                    $notion->delegate()->sync($request->delegate_id);
                                }
                                break;
                        }
                    } else {
                        // No se repite (opción "Once")
                        $notion->save();

                        // Sincronizar usuarios relacionados en la tabla pivote
                        if ($request->has('delegate_id')) {
                            $notion->delegate()->sync($request->delegate_id);
                        }
                    }
                }
            } else {
                // Crear nuevos eventos según la frecuencia seleccionada
                switch ($request->repeat) {
                    case 'Dairy':
                        $this->createDailyRepetitions($notion);

                        // Eliminar todos los eventos relacionados por note_repeat
                        ModelsNotion::where('id', $id)->delete();

                        break;
                    case 'Weeks':
                        $this->createWeeklyRepetitions($notion);

                        // Eliminar todos los eventos relacionados por note_repeat
                        ModelsNotion::where('id', $id)->delete();

                        break;
                    case 'Months':
                        $this->createMonthlyRepetitions($notion);

                        // Eliminar todos los eventos relacionados por note_repeat
                        ModelsNotion::where('id', $id)->delete();

                        break;
                    case 'Years':
                        $this->createYearlyRepetitions($notion);

                        // Eliminar todos los eventos relacionados por note_repeat
                        ModelsNotion::where('id', $id)->delete();

                        break;
                    default:
                        // No se repite (opción "Once")
                        $notion->save();

                        // Sincronizar usuarios relacionados en la tabla pivote
                        if ($request->has('delegate_id')) {
                            $notion->delegate()->sync($request->delegate_id);
                        }
                        break;
                }
            }
        }

        // Retornar una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Evento actualizado correctamente',
            'data' => $notion,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'deleteAllEvents' => 'nullable|boolean', // Asegúrate de validar la nueva variable
        ]);

        // Buscar la nota por su ID
        $notion = ModelsNotion::findOrFail($id);

        // Si deleteAllEvents es true, eliminar todos los eventos relacionados
        if ($request->deleteAllEvents && $notion->note_repeat) {
            // Obtener todos los eventos relacionados con note_repeat
            $relatedNotions = ModelsNotion::where('note_repeat', $notion->note_repeat)->get();

            // Recorrer cada evento relacionado
            foreach ($relatedNotions as $relatedNotion) {
                // Eliminar los registros de la tabla pivote (notion_users) para este evento
                DB::table('notion_users')->where('notion_id', $relatedNotion->id)->delete();

                // Obtener todos los chats asociados al evento relacionado
                $chatNotion = ChatNotion::where('notion_id', $relatedNotion->id)->get();

                // Recorrer cada chat para eliminar archivos y mensajes
                foreach ($chatNotion as $chat) {
                    // Si el chat tiene contenido (archivos), eliminarlos
                    if ($chat->content) {
                        $contentPath = 'notion/' . $chat->content; // Ruta relativa del archivo
                        $fullPath = public_path($contentPath); // Ruta absoluta del archivo

                        // Verificar si el archivo existe y eliminarlo
                        if (File::exists($fullPath)) {
                            File::delete($fullPath);
                        }
                    }

                    // Eliminar el chat
                    $chat->delete();
                }

                // Eliminar el evento relacionado
                $relatedNotion->delete();
            }
        } else {
            // Eliminar los registros de la tabla pivote (notion_users) para este evento
            DB::table('notion_users')->where('notion_id', $notion->id)->delete();

            // Obtener todos los chats asociados a la nota
            $chatNotion = ChatNotion::where('notion_id', $id)->get();
            // Recorrer cada chat para eliminar archivos y mensajes
            foreach ($chatNotion as $chat) {
                // Si el chat tiene contenido (archivos), eliminarlos
                if ($chat->content) {
                    $contentPath = 'notion/' . $chat->content; // Ruta relativa del archivo
                    $fullPath = public_path($contentPath); // Ruta absoluta del archivo

                    // Verificar si el archivo existe y eliminarlo
                    if (File::exists($fullPath)) {
                        File::delete($fullPath);
                    }
                }

                // Eliminar el chat
                $chat->delete();
            }
            // Eliminar la nota
            $notion->delete();
        }

        // Retornar una respuesta JSON
        return response()->json([
            'success' => true,
            'message' => 'Evento eliminado correctamente',
        ]);
    }

    private function createDailyRepetitions($notion)
    {
        $startDate = Carbon::parse($notion->start_date);
        $endDate = Carbon::parse($notion->end_date);

        // Obtener el último número de repetición
        $notionLast = ModelsNotion::where('note_repeat', '!=', null)->orderBy('note_repeat', 'desc')->first();
        $noteRepeat = ($notionLast) ? $notionLast->note_repeat + 1 : 1;

        // Preparar los datos para la inserción masiva
        $events = [];
        for ($i = 0; $i < 365; $i++) {
            $events[] = [
                'project_id' => $notion->project_id,
                'user_id' => $notion->user_id,
                'note_repeat' => $noteRepeat,
                'color' => $notion->color,
                'icon' => $notion->icon,
                'title' => $notion->title,
                'priority' => $notion->priority,
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                'status' => 'Abierto',
                'repeat' => $notion->repeat,
            ];

            // Avanzar un día
            $startDate->addDay();
            $endDate->addDay();
        }

        // Insertar todos los eventos de una vez
        ModelsNotion::insert($events);

        // Sincronizar usuarios relacionados en la tabla pivote (si es necesario)
        if (request()->has('delegate_id')) {
            $delegateIds = request()->input('delegate_id');
            $newNotions = ModelsNotion::where('note_repeat', $noteRepeat)->get();
            foreach ($newNotions as $newNotion) {
                $newNotion->delegate()->sync($delegateIds);
            }
        }
    }

    private function createWeeklyRepetitions($notion)
    {
        $startDate = Carbon::parse($notion->start_date);
        $endDate = Carbon::parse($notion->end_date);

        // Obtener el último número de repetición
        $notionLast = ModelsNotion::all()->where('note_repeat', '!=', null)->last();
        $noteRepeat = ($notionLast) ? $notionLast->note_repeat + 1 : 1;

        // Preparar los datos para la inserción masiva
        $events = [];
        for ($i = 0; $i < 52; $i++) {
            $events[] = [
                'project_id' => $notion->project_id,
                'user_id' => $notion->user_id,
                'note_repeat' => $noteRepeat,
                'color' => $notion->color,
                'icon' => $notion->icon,
                'title' => $notion->title,
                'priority' => $notion->priority,
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                'status' => 'Abierto',
                'repeat' => $notion->repeat,
            ];

            // Avanzar una semana
            $startDate->modify('+1 week');
            $endDate->modify('+1 week');
        }

        // Insertar todos los eventos de una vez
        ModelsNotion::insert($events);

        // Sincronizar usuarios relacionados en la tabla pivote (si es necesario)
        if (request()->has('delegate_id')) {
            $delegateIds = request()->input('delegate_id');
            $newNotions = ModelsNotion::where('note_repeat', $noteRepeat)->get();
            foreach ($newNotions as $newNotion) {
                $newNotion->delegate()->sync($delegateIds);
            }
        }
    }

    private function createMonthlyRepetitions($notion)
    {
        $startDate = Carbon::parse($notion->start_date);
        $endDate = Carbon::parse($notion->end_date);

        // Obtener el último número de repetición
        $notionLast = ModelsNotion::all()->where('note_repeat', '!=', null)->last();
        $noteRepeat = ($notionLast) ? $notionLast->note_repeat + 1 : 1;

        // Preparar los datos para la inserción masiva
        $events = [];
        for ($i = 0; $i < 12; $i++) {
            $events[] = [
                'project_id' => $notion->project_id,
                'user_id' => $notion->user_id,
                'note_repeat' => $noteRepeat,
                'color' => $notion->color,
                'icon' => $notion->icon,
                'title' => $notion->title,
                'priority' => $notion->priority,
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                'status' => 'Abierto',
                'repeat' => $notion->repeat,
            ];

            // Avanzar un mes
            $startDate->modify('+1 month');
            $endDate->modify('+1 month');
        }

        // Insertar todos los eventos de una vez
        ModelsNotion::insert($events);

        // Sincronizar usuarios relacionados en la tabla pivote (si es necesario)
        if (request()->has('delegate_id')) {
            $delegateIds = request()->input('delegate_id');
            $newNotions = ModelsNotion::where('note_repeat', $noteRepeat)->get();
            foreach ($newNotions as $newNotion) {
                $newNotion->delegate()->sync($delegateIds);
            }
        }
    }

    private function createYearlyRepetitions($notion)
    {
        $startDate = Carbon::parse($notion->start_date);
        $endDate = Carbon::parse($notion->end_date);

        // Obtener el último número de repetición
        $notionLast = ModelsNotion::all()->where('note_repeat', '!=', null)->last();
        $noteRepeat = ($notionLast) ? $notionLast->note_repeat + 1 : 1;

        // Preparar los datos para la inserción masiva
        $events = [];
        for ($i = 0; $i < 10; $i++) {
            $events[] = [
                'project_id' => $notion->project_id,
                'user_id' => $notion->user_id,
                'note_repeat' => $noteRepeat,
                'color' => $notion->color,
                'icon' => $notion->icon,
                'title' => $notion->title,
                'priority' => $notion->priority,
                'start_date' => $startDate->format('Y-m-d H:i:s'),
                'end_date' => $endDate->format('Y-m-d H:i:s'),
                'status' => 'Abierto',
                'repeat' => $notion->repeat,
            ];

            // Avanzar un año
            $startDate->modify('+1 year');
            $endDate->modify('+1 year');
        }

        // Insertar todos los eventos de una vez
        ModelsNotion::insert($events);

        // Sincronizar usuarios relacionados en la tabla pivote (si es necesario)
        if (request()->has('delegate_id')) {
            $delegateIds = request()->input('delegate_id');
            $newNotions = ModelsNotion::where('note_repeat', $noteRepeat)->get();
            foreach ($newNotions as $newNotion) {
                $newNotion->delegate()->sync($delegateIds);
            }
        }
    }
}
