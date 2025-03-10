<?php

namespace App\Http\Livewire\ActivitiesReports;

use App\Models\Activity;
use App\Models\ChatReportsActivities;
use App\Models\ErrorLog;
use App\Models\Log;
use App\Models\Project;
use App\Models\Report;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MyActivities extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $listeners = ['modeUpdated', 'seeProjectsUpdated', 'taskMoved', 'showError']; // Escuchar el evento

    protected $paginationTheme = 'tailwind';
    // ENVIADAS
    public $mode;
    public $seeProjects;
    // FILTROS
    public $search, $allUsers, $allProjects;
    public $selectedDelegate = '', $selectedStates = '', $selectedProjects = '';
    public $allUsersFiltered = [], $allProjectsFiltered = [];
    public $filtered = false; // cambio de dirección de flechas
    public $visiblePanels = []; // Asociativa para controlar los paneles de opciones por ID de reporte
    public $perPage = '20';
    // variables para la consulta
    public $filterPriotiry = false, $filterState = false, $filterExpected = true;
    public $filteredPriority = '', $filteredState = '', $priorityCase = '', $filteredExpected = 'asc';
    // KANVAN
    public $tareasSinFecha = []; // Tareas sin fecha
    public $fechaActual; // Fecha actual
    public $fechasFuturas = []; // Arreglo para almacenar las fechas futuras
    public $tareasAtrasadas = []; // Tareas con expected_date anterior a hoy
    public $tareasActuales = []; // Tareas con expected_date hoy
    public $tareasProximas = []; // Tareas con expected_date en las fechas futuras
    public $tareasActualesFuturas = []; // Tareas con expected_date hoy o en las fechas futuras
    public $tareasMasDeUnMes = []; // Tareas con expected_date después de las fechas futuras
    public $tareasAgrupadasPorFecha = []; // Tareas agrupadas por fecha
    public $mesesAdicionales = 1; // Número de meses adicionales a mostrar
    public $ultimaFechaTarea; // Última fecha de las tareas
    public $priorityOrder = [
        'Alto' => 1,
        'Medio' => 2,
        'Bajo' => 3,
    ]; // Definir la prioridad como propiedad
    public $meses = [
        'ene.' => 'Jan',
        'feb.' => 'Feb',
        'mar.' => 'Mar',
        'abr.' => 'Apr',
        'may.' => 'May',
        'jun.' => 'Jun',
        'jul.' => 'Jul',
        'ago.' => 'Aug',
        'sep.' => 'Sep',
        'oct.' => 'Oct',
        'nov.' => 'Nov',
        'dic.' => 'Dec'
    ]; // Mapeo de nombres de meses en español a inglés
    public $projectPriorities; // Definir la prioridad de proyectos como propiedad
    // MODAL SHOW
    public $show = false;
    public $taskShow = '';
    public $taskShowType = '';
    // MODAL EVIDENCE
    public $reportEvidence;
    public $evidenceActRep = false;
    // GRAFICA EFFORT POINTS
    public $starMonth, $endMonth;
    // EXPECTED_DATE
    public $expected_day;

    public function updatedSelectedStates()
    {
        // Resetear la página a 1 cuando se cambian los filtros
        $this->resetPage();
    }

    // Método para manejar el evento
    public function modeUpdated($value)
    {
        $this->mode = $value; // Actualizar el modo
    }

    public function seeProjectsUpdated($value)
    {
        // Actualizar la propiedad seeProjects en el componente hijo
        $this->seeProjects = $value;
        // Forzar la actualización del componente para aplicar los cambios
        $this->render();
    }

    public function mount()
    {
        // Obtener la fecha actual y formatearla
        $this->fechaActual = Carbon::now()->isoFormat('D MMM YYYY'); // Formato: 26-Dic-2024

        // Generar un arreglo con los próximos 4 días
        for ($i = 1; $i <= 4; $i++) {
            $fecha = Carbon::now()->addDays($i);
            $this->fechasFuturas[] = [
                'fecha' => $fecha->isoFormat('D MMM YYYY'), // Fecha en formato 26-Dic-2024
                'dia_semana' => $fecha->isoFormat('dddd'), // Nombre del día de la semana
            ];
        }
    }

    public function render()
    {
        // Filtro de consulta
        $userLogin = Auth::user();
        $user_id = $userLogin->id;
        // DELEGATE
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
        // TODOS LOS DELEGADOS
        $this->allUsersFiltered = [];
        foreach ($this->allUsers as $user) {
            $this->allUsersFiltered[] = [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        // FILTRO PROYECTOS
        $this->allProjects = Project::orderBy('name', 'asc')->get();
        $this->allProjectsFiltered = [];
        foreach ($this->allProjects as $project) {
            $this->allProjectsFiltered[] = [
                'id' => $project->id,
                'name' => $project->name,
            ];
        }
        if ($this->selectedDelegate) {
            // Reiniciar la paginación cuando se cambia el delegado
            $this->resetPage();
        }
        // Obtener los reports del usuario
        $reports = User::select(
            'users.id as user',
            'users.name as user_name',
            'reports.*'
        )
            ->leftJoin('reports', 'users.id', '=', 'reports.delegate_id')
            ->where('reports.delegate_id', $user_id)
            ->where('reports.title', 'like', '%' . $this->search . '%')
            ->when(!empty($this->selectedStates), function ($query) {
                // Filtrar por los estados seleccionados en los checkboxes
                $query->where('reports.state', $this->selectedStates);
            }, function ($query) {
                // Excluir "Resuelto" si no se seleccionan estados
                $query->where('reports.state', '!=', 'Resuelto');
            })
            ->when(!empty($this->selectedProjects), function ($query) {
                // Filtrar por los estados seleccionados en los checkboxes
                $query->where('reports.project_id', $this->selectedProjects);
            })
            ->when($this->selectedDelegate, function ($query) {
                $query->where('user_id', $this->selectedDelegate);
            })
            ->get();

        // Obtener las activities del usuario
        $activities = User::select(
            'users.id as user',
            'users.name as user_name',
            'activities.*'
        )
            ->leftJoin('activities', 'users.id', '=', 'activities.delegate_id') // JOIN con actividades
            ->leftJoin('sprints', 'activities.sprint_id', '=', 'sprints.id') // JOIN con sprints
            ->leftJoin('backlogs', 'sprints.backlog_id', '=', 'backlogs.id') // JOIN con backlogs
            ->leftJoin('projects', 'backlogs.project_id', '=', 'projects.id') // JOIN con proyectos
            ->where('activities.delegate_id', $user_id)
            ->where('activities.title', 'like', '%' . $this->search . '%')
            ->when(!empty($this->selectedStates), function ($query) {
                // Filtrar por los estados seleccionados en los checkboxes
                $query->where('activities.state', $this->selectedStates);
            }, function ($query) {
                // Excluir "Resuelto" si no se seleccionan estados
                $query->where('activities.state', '!=', 'Resuelto');
            })
            ->when(!empty($this->selectedProjects), function ($query) {
                $query->where('backlogs.project_id', $this->selectedProjects); // Filtrar por proyectos seleccionados
            })
            ->when($this->selectedDelegate, function ($query) {
                $query->where('user_id', $this->selectedDelegate);
            })
            ->get();
        // Combinar los resultados en una colección
        $tasks = $activities->merge($reports);
        // Ordenar la colección combinada por prioridad si es necesario
        if ($this->filterPriotiry) {
            $tasks = $tasks->sortBy(function ($task) {
                // Definir el orden de prioridad
                $priorityOrder = [
                    'Alto' => 1,
                    'Medio' => 2,
                    'Bajo' => 3,
                ];
                return $priorityOrder[$task->priority] ?? 4; // Valor por defecto si no coincide
            }, SORT_REGULAR, $this->filteredPriority === 'desc');
        }
        // Ordenar la colección combinada por estado si es necesario
        if ($this->filterState) {
            $tasks = $tasks->sortBy(function ($task) {
                // Definir el orden de prioridad
                $statusOrder = [
                    'Abierto' => 1,
                    'Proceso' => 2,
                    'Conflicto' => 3,
                    'Resuelto' => 4,
                ];
                return $statusOrder[$task->state] ?? 5; // Valor por defecto si no coincide
            }, SORT_REGULAR, $this->filteredState === 'desc');
        }
        // Ordenar la colección combinada
        if ($this->filterExpected) {
            $tasks = $tasks->sortBy(function ($task) {
                return $task->expected_date;
            }, SORT_REGULAR, $this->filteredExpected === 'desc');
        }
        // Paginación manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->perPage;
        $currentItems = $tasks->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedTask = new LengthAwarePaginator($currentItems, $tasks->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        // ADD ATRIBUTES
        foreach ($tasks as $task) {
            // ACTIONS
            $task->filteredActions = $this->getFilteredActions($task->state);
            // DELEGATE
            $task->usersFiltered = $this->allUsers->reject(function ($user) use ($task) {
                return $user->id === $task->delegate_id || $user->id === Auth::id();
            })->values();
            // PROGRESS
            if ($task->progress && $task->updated_at) {
                $task->progress = Carbon::parse($task->progress);
                $progress = Carbon::parse($task->progress);
                $updated_at = Carbon::parse($task->updated_at);
                $diff = $progress->diff($updated_at);

                $units = [
                    'año' => $diff->y,
                    'mes' => $diff->m,
                    'semana' => floor($diff->days / 7),
                    'dia' => $diff->d % 7, // Días restantes después de calcular las semanas
                    'hora' => $diff->h,
                    'minuto' => $diff->i,
                    'segundo' => $diff->s,
                ];

                $timeDifference = '';
                foreach ($units as $unit => $value) {
                    if ($value > 0) {
                        $timeDifference = $value . ' ' . $unit . ($value > 1 ? 's' : '');
                        break;
                    }
                }

                $task->timeDifference = $timeDifference;
            } else {
                $task->timeDifference = null;
            }
            // NAME USUARIO CREO
            $user_created = User::where('id', $task->user_id)->first();
            if ($user_created) {
                $task->created_name = $user_created->name;
                $task->created_id = $user_created->id;
            } else {
                $task->created_name = 'Usuario eliminado';
                $task->created_id = null;
            }
            // NAME USUARIO
            $user_delegate = User::where('id', $user_id)->first();
            $task->delegate_id = $user_delegate->id;
            $task->delegate_name = $user_delegate->name;
            // CHAT ACTIVITY
            if ($task->sprint_id) {
                $messages = ChatReportsActivities::where('activity_id', $task->id)->orderBy('created_at', 'asc')->get();
                $lastMessageNoView = ChatReportsActivities::where('activity_id', $task->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('receiver_id', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();
                // Datos del proyecto
                $sprint = Sprint::where('id', $task->sprint_id)->first(); // Obtener solo un modelo, no una colección
                if ($sprint) {
                    if ($sprint->backlog) {
                        if ($sprint->backlog->project) {
                            // Acceder al proyecto asociado al backlog
                            $task->project_name = $sprint->backlog->project->name;
                            $task->project_priority = 'K' . $sprint->backlog->project->priority;
                            $task->project_id = $sprint->backlog->project->id;
                            $task->sprint_state = $sprint->state;
                            $task->project_activity = true;
                            // FECHA DE ENTREGA
                            $this->expected_day[$task->id] = ($task->expected_date) ? Carbon::parse($task->expected_date)->format('Y-m-d') : '';
                        } else {
                            // Manejar caso donde no hay backlog asociado
                            $task->project_name = 'Proyecto no disponible';
                            $task->project_activity = false;
                        }
                    } else {
                        // Manejar caso donde no hay backlog asociado
                        $task->project_name = 'Backlog no disponible';
                        $task->project_activity = false;
                    }
                } else {
                    // Manejar caso donde no se encuentra el sprint
                    $task->project_name = 'Sprint no encontrado';
                    $task->project_activity = false;
                }
            } else {
                $messages = ChatReportsActivities::where('report_id', $task->id)->orderBy('created_at', 'asc')->get();
                $lastMessageNoView = ChatReportsActivities::where('report_id', $task->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('receiver_id', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();
                // Datos del proyecto
                $project = Project::where('id', $task->project_id)->first(); // Obtener solo un modelo, no una colección
                if ($project) {
                    // Acceder al proyecto a
                    $task->project_name = $project->name;
                    $task->project_priority = 'K' . $project->priority;
                    $task->project_id = $project->id;
                    $task->project_report = true;
                    // FECHA DE ENTREGA
                    if ($task->updated_expected_date == false) {
                        $this->expected_day[$task->id] = ''; // Deja el input vacío si no se ha actualizado la fecha
                    } else {
                        $this->expected_day[$task->id] = Carbon::parse($task->expected_date)->format('Y-m-d');
                    }
                } else {
                    // Manejar caso donde no se encuentra el proyecto
                    $task->project_name = 'Proyecto no encontrado';
                    $task->project_report = false;
                }
            }
            // Verificar si la colección tiene al menos un mensaje
            if ($messages) {
                if ($lastMessageNoView) {
                    $task->user_chat = $lastMessageNoView->user_id;
                    $task->receiver_chat = $lastMessageNoView->receiver_id;

                    $receiver = User::find($lastMessageNoView->receiver_id);

                    if ($receiver->type_user == 3) {
                        $task->client = true;
                    } else {
                        $task->client = false;
                    }
                } else {
                    $lastMessage = $messages->last();
                    if ($lastMessage) {
                        if ($lastMessage->user_id == Auth::id()) {
                            $task->user_id = true;
                        } else {
                            if ($lastMessage->receiver) {
                                if ($lastMessage->receiver->type_user == 3) {
                                    $task->client = true;
                                } else {
                                    $task->client = false;
                                }
                            } else {
                                $task->client = false;
                            }
                            // VER MENSAJES EXCLUSIVOS DE CLIENTE PARA ADMINISTRADORES
                            if ($lastMessage->transmitter && Auth::user()->type_user == 1) {
                                if ($lastMessage->transmitter->type_user == 3) {
                                    $task->client = true;
                                } else {
                                    $task->client = false;
                                }
                            } else {
                                $task->client = false;
                            }
                            $task->user_id = false;
                        }
                    }
                }
            }
            $task->messages_count = $messages->where('look', false)->count();
        }

        if ($this->mode) {
            // Crear un arreglo de prioridades desde K1 hasta K10
            $this->projectPriorities = collect(range(1, 10))->map(fn($i) => "K$i");

            // Emitir un evento para inicializar los botones de desplazamiento
            $this->dispatchBrowserEvent('initializeScrollButtons');
            $this->dispatchBrowserEvent('initializeSortableJS');

            // Obtener la última tarea con expected_date más lejana
            $this->ultimaFechaTarea = $tasks->isNotEmpty()
                ? Carbon::parse($tasks->last()->expected_date)->format('Y-m') // Formato Año-Mes
                : null;

            // Filtrar y ordenar tareas
            $this->tareasSinFecha = $tasks->filter(fn($task) => is_null($task->expected_date)) // Tareas sin fecha
                ->sortBy(fn($task) => [$this->priorityOrder[$task->priority] ?? 4, $this->parseProjectPriority($task)])->toArray();

            $this->tareasAtrasadas = $tasks->filter(
                fn($task) =>
                !empty($task->expected_date) && Carbon::parse($task->expected_date)->lt(Carbon::today()) // Tareas atrasadas
            )->sortBy(fn($task) => [$this->priorityOrder[$task->priority] ?? 4, $this->parseProjectPriority($task)])->toArray();

            $this->tareasActualesFuturas = $tasks->filter(
                fn($task) =>
                !empty($task->expected_date) && Carbon::parse($task->expected_date)->gte(Carbon::today()) // Tareas actuales o futuras
            )->sortBy(fn($task) => [$this->priorityOrder[$task->priority] ?? 4, $this->parseProjectPriority($task)])->toArray();

            // Aplicar agrupación por proyecto si seeProjects es true
            if ($this->seeProjects) {
                $this->tareasSinFecha = $this->agruparYOrdenarPorProyecto($this->tareasSinFecha);
                $this->tareasAtrasadas = $this->agruparYOrdenarPorProyecto($this->tareasAtrasadas);
                $this->tareasActualesFuturas = $this->agruparYOrdenarPorFechaYProyecto($this->tareasActualesFuturas);
            }

            $this->actualizarTareas(); // Actualizar las tareas antes de renderizar
        }

        return view('livewire.activities-reports.my-activities', [
            'tasks' => $paginatedTask,
        ]);
    }
    // ACTIONS
    public function updateDelegate($id, $delegate, $type)
    {
        try {
            if ($type == 'report') {
                $report = Report::find($id);
                if ($report) {
                    $report->delegate_id = $delegate;
                    $report->delegated_date = Carbon::now();
                    $report->progress = null;
                    $report->look = false;
                    $report->state = 'Abierto';
                    $report->save();

                    Log::create([
                        'user_id' => Auth::id(),
                        'report_id' => $id,
                        'view' => 'livewire/activities-reports/my-activities',
                        'action' => 'Cambio de delegado',
                        'message' => 'Delegado actualizado',
                        'details' => 'Delegado: ' . $report->delegate_id,
                    ]);

                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Delegado actualizado',
                    ]);
                }
            } else {
                $activity = Activity::find($id);
                if ($activity) {
                    $activity->delegate_id = $delegate;
                    $activity->delegated_date = Carbon::now();
                    $activity->progress = null;
                    $activity->look = false;
                    $activity->state = 'Abierto';
                    $activity->save();

                    Log::create([
                        'user_id' => Auth::id(),
                        'activity_id' => $id,
                        'view' => 'livewire/activities-reports/my-activities',
                        'action' => 'Cambio de delegado',
                        'message' => 'Delegado actualizado',
                        'details' => 'Delegado: ' . $activity->delegate_id,
                    ]);

                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Delegado actualizado',
                    ]);
                }
            }
        } catch (\Exception $e) {
            if ($type == 'report') {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'report_id' => $id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Cambio de delegado',
                    'message' => 'Error en actualizar delegado',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            } else {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'activity_id' => $id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Cambio de delegado',
                    'message' => 'Error en actualizar delegado',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            }

            // Manejar el error y mostrar un mensaje al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function updateState($id, $project_id, $state)
    {
        try {
            if ($project_id == null) {
                $activity = Activity::find($id);

                if ($activity) {
                    if ($state == 'Proceso' || $state == 'Conflicto') {
                        if ($activity->progress == null && $activity->look == false && $activity->state == 'Abierto') {
                            $activity->progress = Carbon::now();
                            $activity->look = true;
                        }
                        if ($activity->progress != null && $activity->look == true && $activity->state == 'Abierto') {
                            $activity->progress = Carbon::now();
                            $activity->look = false;
                        }

                        $activity->state = $state;
                        $activity->save();

                        Log::create([
                            'user_id' => Auth::id(),
                            'activity_id' => $activity->id,
                            'view' => 'livewire/activities-reports/my-activities',
                            'action' => 'Cambio de estado',
                            'message' => 'Estado actualizado',
                            'details' => 'Estado: ' . $activity->state,
                        ]);

                        // Actualizacion de grafica
                        $this->effortPoints();
                        // Emitir un evento de navegador
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'success',
                            'title' => 'Estado actualizado',
                        ]);
                    }

                    if ($state == 'Resuelto') {
                        $activity->expected_date = ($activity->expected_date) ? $activity->expected_date : Carbon::now();
                        $activity->end_date = Carbon::now();
                        $activity->state = $state;
                        $activity->save();

                        Log::create([
                            'user_id' => Auth::id(),
                            'activity_id' => $activity->id,
                            'view' => 'livewire/activities-reports/my-activities',
                            'action' => 'Cambio de estado',
                            'message' => 'Estado actualizado',
                            'details' => 'Estado: ' . $activity->state,
                        ]);
                        // Actualizacion de grafica
                        $this->effortPoints();
                        // Emitir un evento para notificar al componente padre
                        $this->emitUp('activityUpdated');
                        // Emitir un evento de navegador
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'success',
                            'title' => 'Estado actualizado',
                        ]);
                    }
                }
            } else {
                $report = Report::find($id);

                if ($report) {
                    if ($state == 'Proceso' || $state == 'Conflicto') {
                        if ($report->progress == null && $report->look == false && $report->state == 'Abierto') {
                            $report->progress = Carbon::now();
                            $report->look = true;
                        }
                        if ($report->progress != null && $report->look == true && $report->state == 'Abierto') {
                            $report->progress = Carbon::now();
                            $report->look = false;
                        }

                        $report->state = $state;
                        $report->save();

                        Log::create([
                            'user_id' => Auth::id(),
                            'report_id' => $report->id,
                            'view' => 'livewire/activities-reports/my-activities',
                            'action' => 'Cambio de estado',
                            'message' => 'Estado actualizado',
                            'details' => 'Estado: ' . $report->state,
                        ]);

                        // Actualizacion de grafica
                        $this->effortPoints();
                        // Emitir un evento de navegador
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'success',
                            'title' => 'Estado actualizado',
                        ]);
                    }

                    if ($state == 'Resuelto') {
                        if ($report->evidence == true) {
                            $this->evidenceActRep = true;
                            $this->reportEvidence = $report;
                        } else {
                            $report->expected_date = ($report->expected_date) ? $report->expected_date : Carbon::now();
                            $report->state = $state;
                            $report->updated_expected_date = true;
                            $report->end_date = Carbon::now();
                            $report->repeat = true;
                            $report->save();

                            Log::create([
                                'user_id' => Auth::id(),
                                'report_id' => $report->id,
                                'view' => 'livewire/activities-reports/my-activities',
                                'action' => 'Cambio de estado',
                                'message' => 'Estado actualizado',
                                'details' => 'Estado: ' . $report->state,
                            ]);
                            // Actualizacion de grafica
                            $this->effortPoints();
                            // Emitir un evento de navegador
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Estado actualizado',
                            ]);
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            if ($project_id == null) {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'activity_id' => $id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Cambio de estado',
                    'message' => 'Error en actualizar estado',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            } else {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'report_id' => $id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Cambio de estado',
                    'message' => 'Error en actualizar estado',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            }

            // Manejar el error y mostrar un mensaje al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function taskMoved($data)
    {
        $taskId = $data['taskId'];
        $nuevaFecha = $data['nuevaFecha']; // Nueva fecha
        $type = $data['type']; // 'activity' o 'report'

        try {
            if ($type == 'report') {
                $task = Report::find($taskId);
            } else {
                $task = Activity::find($taskId);
            }

            if ($task) {
                if ($type == 'report') {
                    if ($task->updated_expected_date == false) {
                        $task->updated_expected_date = true;
                    }
                }
                if ($nuevaFecha == '') {
                    $task->expected_date = null;
                } else {
                    $task->expected_date = Carbon::parse($nuevaFecha)->format('Y-m-d');
                }
                $task->save();

                if ($type == 'report') {
                    Log::create([
                        'user_id' => Auth::id(),
                        'report_id' => $task->id,
                        'view' => 'livewire/activities-reports/my-activities',
                        'action' => 'Drag-and-Drop Kanvan',
                        'message' => 'Fecha de entrega actualizada',
                        'details' => 'Fecha: ' . $task->expected_date,
                    ]);
                } else {
                    Log::create([
                        'user_id' => Auth::id(),
                        'activity_id' => $task->id,
                        'view' => 'livewire/activities-reports/my-activities',
                        'action' => 'Drag-and-Drop Kanvan',
                        'message' => 'Fecha de entrega actualizada',
                        'details' => 'Fecha: ' . $task->expected_date,
                    ]);
                }

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Fecha actualizada correctamente',
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Tarea no encontrada',
                ]);
            }
        } catch (\Exception $e) {
            // Registrar el error en la base de datos
            if ($type == 'report') {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'report_id' => $task->id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Drag-and-Drop Kanvan',
                    'message' => 'Error en actualizar la fecha de entrega',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            } else {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'activity_id' => $task->id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Drag-and-Drop Kanvan',
                    'message' => 'Error en actualizar la fecha de entrega',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            }

            // Notificar error al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function updateExpectedDay($id, $type, $day)
    {
        try {
            if ($type == 'report') {
                $task = Report::find($id);
            } else {
                $task = Activity::find($id);
            }
            if ($task) {
                if ($type == 'report') {
                    if ($task->updated_expected_date == false) {
                        $task->updated_expected_date = true;
                    }
                }

                $task->expected_date = Carbon::parse($day)->format('Y-m-d');
                $task->save();

                if ($type == 'report') {
                    Log::create([
                        'user_id' => Auth::id(),
                        'report_id' => $task->id,
                        'view' => 'livewire/activities-reports/my-activities',
                        'action' => 'Actualizar fecha de entrega',
                        'message' => 'Fecha de entrega actualizada',
                        'details' => 'Fecha: ' . $task->expected_date,
                    ]);
                } else {
                    Log::create([
                        'user_id' => Auth::id(),
                        'activity_id' => $task->id,
                        'view' => 'livewire/activities-reports/my-activities',
                        'action' => 'Actualizar fecha de entrega',
                        'message' => 'Fecha de entrega actualizada',
                        'details' => 'Fecha: ' . $task->expected_date,
                    ]);
                }

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Fecha actualizada correctamente',
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Reporte no encontrado',
                ]);
            }
        } catch (\Exception $e) {
            if ($type == 'report') {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'report_id' => $id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Actualizar fecha de entrega',
                    'message' => 'Error en actualizar fecha de entrega',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            } else {
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'activity_id' => $id,
                    'view' => 'livewire/activities-reports/my-activities',
                    'action' => 'Actualizar fecha de entrega',
                    'message' => 'Error en actualizar fecha de entrega',
                    'details' => $e->getMessage(), // Mensaje de la excepción
                ]);
            }

            // Manejar el error y mostrar un mensaje al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function finishEvidence($project_id, $report_id)
    {
        $this->redirectRoute('projects.reports.index', ['project' => $project_id, 'reports' => $report_id, 'highlight' => $report_id]);
    }
    // MODAL
    public function show($id, $type)
    {
        if ($this->show == true) {
            $this->show = false;
            $this->taskShow = null;
        } else {
            $this->taskShow = ($type == 'report') ? Report::find($id) : Activity::find($id);
            $this->taskShowType = ($type == 'report') ? 'report' : 'activity';
            if ($this->taskShow) {
                $this->show = true;
            } else {
                $this->show = false;

                // Maneja un caso en el que no se encuentra el reporte (opcional)
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'La tarea no existe',
                ]);
            }
        }
    }
    // FILTER
    public function filterDown($type)
    {
        $this->filtered = false; // Cambio de flechas
        // Reiniciar todos los filtros
        $this->filterPriotiry = false;
        $this->filterState = false;
        $this->filterExpected = false;

        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filteredPriority = 'asc'; // Orden ascendente
        }

        if ($type == 'state') {
            $this->filterState = true;
            $this->filteredState = 'asc'; // Orden ascendente
        }

        if ($type == 'expected_date') {
            $this->filterExpected = true;
            $this->filteredExpected = 'asc'; // Orden ascendente
        }
    }

    public function filterUp($type)
    {
        $this->filtered = true; // Cambio de flechas
        // Reiniciar todos los filtros
        $this->filterPriotiry = false;
        $this->filterState = false;
        $this->filterExpected = false;

        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filteredPriority = 'desc'; // Orden descendente
        }

        if ($type == 'state') {
            $this->filterState = true;
            $this->filteredState = 'desc'; // Orden descendente
        }

        if ($type == 'expected_date') {
            $this->filterExpected = true;
            $this->filteredExpected = 'desc'; // Orden descendente
        }
    }
    //ERRORS
    public function showError($data)
    {
        $message = $data['message'];

        // Mostrar un mensaje de error al usuario
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'warning',
            'title' => $message,
        ]);
    }

    // PROTECTED
    protected function getFilteredActions($currentState)
    {
        $actions = ['Abierto', 'Proceso', 'Resuelto', 'Conflicto'];

        if ($currentState == 'Abierto') {
            return ['Proceso', 'Conflicto'];
        }

        if ($currentState == 'Conflicto') {
            return ['Resuelto'];
        }

        if ($currentState == 'Resuelto') {
            return [];
        }

        if ($currentState == 'Proceso') {
            return array_filter($actions, function ($action) {
                return !in_array($action, ['Abierto', 'Proceso']);
            });
        }

        // En cualquier otro caso, elimina el estado actual del arreglo
        return array_filter($actions, function ($action) use ($currentState) {
            return $action != $currentState;
        });
    }

    protected function effortPoints()
    {
        // FECHAS
        $this->starMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); // Primer día del mes actual
        $this->endMonth = Carbon::now()->endOfMonth()->format('Y-m-d'); // Último día del mes actual
        // Subconsulta de Reports por mes incluyendo puntos resueltos y los demás estados
        $reportsMonthly = Report::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto', 'Resuelto') THEN points ELSE 0 END) as total_points_reports"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_reports")
        )
            ->where('delegate_id', Auth::id())
            ->where(function ($query) {
                $query->whereBetween('expected_date', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('progress', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('end_date', [$this->starMonth, $this->endMonth]);
            })
            ->groupBy('delegate_id');
        // Subconsulta de Activities por mes incluyendo puntos resueltos y los demás estados
        $activitiesMonthly = Activity::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto', 'Resuelto') THEN points ELSE 0 END) as total_points_activities"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_activities")
        )
            ->where('delegate_id', Auth::id())
            ->where(function ($query) {
                $query->whereBetween('expected_date', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('progress', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('end_date', [$this->starMonth, $this->endMonth]);
            })
            ->groupBy('delegate_id');
        // Subconsulta de Reports por semana
        $reportsWeekly = Report::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state = 'Abierto' THEN points ELSE 0 END) as report_abierto"),
            DB::raw("SUM(CASE WHEN state = 'Proceso' THEN points ELSE 0 END) as report_proceso"),
            DB::raw("SUM(CASE WHEN state = 'Conflicto' THEN points ELSE 0 END) as report_conflicto"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as report_resuelto")
        )
            ->where('delegate_id', Auth::id())
            ->where(function ($query) {
                $query->whereBetween('expected_date', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('progress', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('end_date', [$this->starMonth, $this->endMonth]);
            })
            ->groupBy('delegate_id');
        // Subconsulta de Activities por semana
        $activitiesWeekly = Activity::select(
            'delegate_id',
            DB::raw("SUM(CASE WHEN state = 'Abierto' THEN points ELSE 0 END) as activity_abierto"),
            DB::raw("SUM(CASE WHEN state = 'Proceso' THEN points ELSE 0 END) as activity_proceso"),
            DB::raw("SUM(CASE WHEN state = 'Conflicto' THEN points ELSE 0 END) as activity_conflicto"),
            DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as activity_resuelto")
        )
            ->where('delegate_id', Auth::id())
            ->where(function ($query) {
                $query->whereBetween('expected_date', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('progress', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('end_date', [$this->starMonth, $this->endMonth]);
            })
            ->groupBy('delegate_id');
        // Consulta principal unificada
        $points = User::select(
            'users.id',
            'users.name',
            'users.effort_points',
            'reports_weekly.report_abierto',
            'reports_weekly.report_proceso',
            'reports_weekly.report_conflicto',
            'reports_weekly.report_resuelto',
            'activities_weekly.activity_abierto',
            'activities_weekly.activity_proceso',
            'activities_weekly.activity_conflicto',
            'activities_weekly.activity_resuelto',

            'reports_monthly.total_points_reports',
            'reports_monthly.total_resuelto_reports',
            'activities_monthly.total_points_activities',
            'activities_monthly.total_resuelto_activities'
        )
            ->leftJoinSub($reportsWeekly, 'reports_weekly', 'users.id', '=', 'reports_weekly.delegate_id')
            ->leftJoinSub($activitiesWeekly, 'activities_weekly', 'users.id', '=', 'activities_weekly.delegate_id')
            ->leftJoinSub($reportsMonthly, 'reports_monthly', 'users.id', '=', 'reports_monthly.delegate_id')
            ->leftJoinSub($activitiesMonthly, 'activities_monthly', 'users.id', '=', 'activities_monthly.delegate_id')
            ->where('users.id', Auth::id())
            ->get();

        foreach ($points as $key => $point) {
            // Puntos por terminar
            $points_finish = $point->total_points_reports + $point->total_points_activities;
            // Puntos por asignar
            $points_assigned = $point->effort_points - $points_finish;
            if ($points_assigned < 0) {
                // Crear el nuevo atributo extrapoints y establecerlo como el valor positivo del número negativo
                $point->extrapoints = abs($points_assigned);
                // Asignar el valor de points_assigned al objeto point
                $point->points_assigned = 0;
            } else {
                // Si points_assigned no es negativo, asegúrate de que extrapoints esté vacío o nulo
                $point->extrapoints = 0;
                // Asignar el valor de points_assigned al objeto point
                $point->points_assigned = $points_assigned;
            }
            // Avance porcentaje usando la suma de los puntos resueltos del mes
            $total_resuelto_monthly = $point->total_resuelto_reports + $point->total_resuelto_activities;
            // Evitar división por cero
            if ($point->effort_points != 0) {
                $advance = $total_resuelto_monthly / $point->effort_points;
            } else {
                $advance = 0; // O cualquier valor que tenga sentido en tu contexto
            }
            // Obtener la primera palabra del nombre
            $first_name = explode(' ', trim($point->name))[0];
            // Formatear el nombre con el avance
            $point->name_with_advance = $first_name . ' (' . number_format($advance * 100, 2) . '%)';
            // Asignar effort_points directamente
            $point->total_effort_points = $point->effort_points;
        }

        $series = [
            [
                'name' => 'Resuelto',
                'data' => $points->map(function ($point) {
                    return $point->report_resuelto + $point->activity_resuelto;
                })->toArray(),
            ],
            [
                'name' => 'Proceso',
                'data' => $points->map(function ($point) {
                    return $point->report_proceso + $point->activity_proceso;
                })->toArray(),
            ],
            [
                'name' => 'Conflicto',
                'data' => $points->map(function ($point) {
                    return $point->report_conflicto + $point->activity_conflicto;
                })->toArray(),
            ],
            [
                'name' => 'Abierto',
                'data' => $points->map(function ($point) {
                    return $point->report_abierto + $point->activity_abierto;
                })->toArray(),
            ],
            [
                'name' => 'Asignar',
                'data' => $points->map(function ($point) {
                    return $point->points_assigned;
                })->toArray(),
            ],
            [
                'name' => 'Extras',
                'data' => $points
                    ->map(function ($point) {
                        return $point->extrapoints;
                    })
                    ->toArray(),
            ],
        ];
        // Preparar los datos para el gráfico
        $categories = $points->map(function ($point) {
            return $point->name_with_advance;
        })->toArray();

        $totalEffortPoints = $points->map(function ($point) {
            return $point->total_effort_points;
        })->toArray();
        // Emitir los datos al componente padre
        $this->emitUp('refreshChart', $categories, $series, $totalEffortPoints);
    }
    // KANVAN
    protected function agruparYOrdenarPorFechaYProyecto($tareas)
    {
        // Agrupar tareas por fecha
        $tareasPorFecha = [];

        foreach ($tareas as $task) {
            $fecha = Carbon::parse($task['expected_date'])->isoFormat('D MMM YYYY');

            // Si no existe la fecha, se inicializa
            if (!isset($tareasPorFecha[$fecha])) {
                $tareasPorFecha[$fecha] = [];
            }

            // Agregar la tarea a la fecha correspondiente
            $tareasPorFecha[$fecha][] = $task;
        }

        // Aplicar agrupación por proyecto si seeProjects es true
        if ($this->seeProjects) {
            foreach ($tareasPorFecha as $fecha => $tareas) {
                $tareasPorFecha[$fecha] = $this->agruparYOrdenarPorProyecto($tareas);
            }
        }

        return $tareasPorFecha;
    }
    protected function agruparYOrdenarPorProyecto($tareas)
    {
        $tareasAgrupadas = [];

        foreach ($tareas as $task) {
            $priority = $task['project_priority'];

            // Si no existe la prioridad, se inicializa
            if (!isset($tareasAgrupadas[$priority])) {
                $tareasAgrupadas[$priority] = [
                    'project_name' => $task['project_name'], // Incluir el nombre del proyecto
                    'tasks' => [], // Incluir la tarea completa
                ];
            }

            // Agregar la tarea a la prioridad correspondiente, incluyendo el nombre del proyecto
            $tareasAgrupadas[$priority]['tasks'][] = $task; // Incluir la tarea completa
        }

        // Ordenar el arreglo por prioridad (de menor a mayor)
        uksort($tareasAgrupadas, function ($a, $b) {
            // Extraer el número de la prioridad (por ejemplo, "K2" -> 2)
            $numA = intval(substr($a, 1)); // Elimina la "K" y convierte a entero
            $numB = intval(substr($b, 1)); // Elimina la "K" y convierte a entero

            // Comparar los números
            return $numA - $numB;
        });

        return $tareasAgrupadas;
    }

    public function parseProjectPriority($task)
    {
        // Función para extraer el número de project_priority
        return (int) str_replace('K', '', $task->project_priority ?? 'K0'); // Usa 99 si no tiene prioridad
    }

    public function cargarMasMeses()
    {
        // Obtener la última fecha de tarea desde la propiedad
        $ultimaTarea = $this->ultimaFechaTarea;
        // Calcular el nuevo rango después de agregar más meses
        $nuevoMes = Carbon::now()->addDays(5)->addMonths($this->mesesAdicionales)->format('Y-m');

        // Si ya no hay más tareas después del último mes, mostrar un mensaje y no incrementar más
        if ($ultimaTarea && $nuevoMes > $ultimaTarea) {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'info',
                'title' => 'Has llegado al último mes con tareas registradas',
            ]);
            return; // Detener la ejecución para no incrementar más meses
        }

        // Si todavía hay más tareas en el futuro, seguir cargando más meses
        $this->mesesAdicionales += 1;
        $this->actualizarTareas(); // Actualizar la lista de tareas
    }

    // Método para actualizar las tareas
    // Método para actualizar las tareas
    public function actualizarTareas()
    {
        // Obtener la fecha actual
        $now = Carbon::now();
        $today = now()->isoFormat('D MMM YYYY'); // Obtener la fecha de hoy en el mismo formato de las claves
        // Rango de fechas (del día siguiente hasta 4 días después de hoy)
        $startOfRangeFourDays = $now->copy();
        $endOfRangeFourDays = $now->copy()->addDays(4);
        // Definir el rango de fechas
        $startOfRange = $now->copy()->addDays(4); // 5 días después de hoy (el día 5 es el inicio)
        $endOfRange = $startOfRange->copy()->addMonths($this->mesesAdicionales); // N meses después del día 5
        // Extraer la propiedad para usar dentro del closure
        $meses = $this->meses;
        $priorityOrder = $this->priorityOrder;
        $tareasActualesFuturas = $this->tareasActualesFuturas;

        if ($this->seeProjects) { // Filtro por proyecto
            // Filtrar solo las tareas de hoy
            $this->tareasActuales = $this->tareasActualesFuturas[$today] ?? [];

            // Si $this->seeProjects es true, mostrar solo un mes a la vez
            $tareasActualesFuturas = collect($this->tareasActualesFuturas);

            // Filtrar las tareas dentro del rango
            $this->tareasProximas = $tareasActualesFuturas
            ->filter(function ($tasks, $dateKey) use ($startOfRangeFourDays, $endOfRangeFourDays, $meses) {
                // Convertir `dateKey` al formato correcto usando Carbon
                foreach ($meses as $esp => $eng) {
                    $dateKey = str_replace($esp, $eng, $dateKey);
                }
                $dateKeyFormatted = Carbon::createFromFormat('d M Y', $dateKey);

                return $dateKeyFormatted->between($startOfRangeFourDays, $endOfRangeFourDays);
            })
            ->toArray();

            // Fechas despues de un mes
            $tareasFiltradas = $tareasActualesFuturas
                ->filter(function ($tasks, $dateKey) use ($startOfRange, $endOfRange, $meses) {
                    foreach ($meses as $esp => $eng) {
                        $dateKey = str_replace($esp, $eng, $dateKey);
                    }

                    $taskDate = Carbon::createFromFormat('d M Y', $dateKey);
                    return $taskDate->between($startOfRange, $endOfRange);
                })
                ->sortBy(function ($tasks, $dateKey) use ($meses) {
                    foreach ($meses as $esp => $eng) {
                        $dateKey = str_replace($esp, $eng, $dateKey);
                    }
                    return Carbon::createFromFormat('d M Y', $dateKey)->timestamp;
                })
                ->map(function ($tasks) use ($priorityOrder) {
                    return collect($tasks)->map(function ($taskGroup) use ($priorityOrder) {
                        // Validar si $taskGroup tiene la clave 'tasks'
                        if (!isset($taskGroup['tasks']) || !is_array($taskGroup['tasks'])) {
                            return $taskGroup; // Retornar sin modificar si no es un array válido
                        }

                        // Ordenar las tareas dentro de 'tasks'
                        $taskGroup['tasks'] = collect($taskGroup['tasks'])->sortBy(function ($task) use ($priorityOrder) {
                            return [
                                $priorityOrder[$task['priority']] ?? 4, // Ordenar por prioridad de la tarea
                                $task['project_priority'] ?? 'K99' // Ordenar por prioridad del proyecto
                            ];
                        })->values();

                        return $taskGroup;
                    });
                })
                ->toArray();

            // Obtener el primer mes dentro del rango
            $this->tareasAgrupadasPorFecha = $tareasFiltradas; // Mostrar solo el primer mes

        } else {
            // Convertir el array a una Colección
            $tareasActualesFuturas = collect($this->tareasActualesFuturas);

            // 🔹 Combinar y filtrar las tareas actuales o futuras dentro del rango
            $this->tareasMasDeUnMes = $tareasActualesFuturas
                ->filter(fn($task) => Carbon::parse($task['expected_date'])->between($startOfRange, $endOfRange))
                ->sortBy(fn($task) => [
                    Carbon::parse($task['expected_date'])->timestamp, // Ordenar por fecha (más cercana primero)
                    $this->priorityOrder[$task['priority']] ?? 4, // Ordenar por prioridad (según tu configuración)
                    $this->parseProjectPriority($task) // Ordenar por prioridad del proyecto (K1, K2, etc.)
                ])->values(); // 🔥 Asegura que Livewire maneje correctamente los índices

            // Si $this->seeProjects es false, agrupar por fecha específica (formato: 'D MMM YYYY')
            $this->tareasAgrupadasPorFecha = $this->tareasMasDeUnMes
                ->groupBy(fn($task) => Carbon::parse($task['expected_date'])->isoFormat('D MMM YYYY')) // Agrupar por fecha formateada
                ->map(fn($tasks) => $tasks->sortBy(fn($task) => [
                    $this->priorityOrder[$task['priority']] ?? 4,
                    $this->parseProjectPriority($task)
                ])->values()) // 🔥 Asegura que los grupos también mantengan índices correctos
                ->toArray();
        }
    }
}
