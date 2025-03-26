<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Models\Activity;
use App\Models\ActivityRecurrent;
use App\Models\Backlog;
use App\Models\ErrorLog;
use App\Models\Log;
use App\Models\Sprint;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class BacklogSprints extends Component
{
    public $listeners = ['activityUpdated' => 'recalculatePercentageResolved', 'destroySprint',  'sprintCreated' => 'sprintsUpdated'];
    protected $paginationTheme = 'tailwind';
    // ENVIADAS
    public $project, $backlog;
    // SPRINT
    public $sprints, $firstSprint, $selectSprint;
    public $changeSprint = false;
    public $filteredState = [];
    // MODAL BACKLOG
    public $showBacklog;
    // MODAL SPRINT
    public $newSprint = false, $updateSprint = false;
    public $sprintEdit;
    // inputs
    public $name_sprint, $start_date, $end_date;
    public $sprintState, $startDate, $endDate, $idSprint, $percentageResolved;

    public function render()
    {
        // Obtener los sprints y ordenarlos por el número de sprint
        $backlog = $this->backlog;
        // Verificar si el archivo existe en la base de datos
        $filesBacklog = [];
        if ($backlog && $backlog->files) {
            foreach ($backlog->files as $key => $file) {
                // Verificar si el archivo existe en la carpeta
                $filePath = public_path('backlogs/' . $file->route);
                if (file_exists($filePath)) {
                    $filesBacklog[] = $file->route;
                } else {
                    $filesBacklog[] = false;
                }
            }
        }
        $this->backlog->filesBacklog = $filesBacklog;
        if (!$this->sprints) {
            $this->sprints = $this->backlog->sprints->sortBy('number');
        }

        if ($this->sprints->isNotEmpty()) {
            // PRIMERO SPRINT
            if (!$this->changeSprint) {
                // Buscar el primer sprint con estado 'Curso'
                $courseSprint = $this->sprints->firstWhere('state', 'Curso');
                // Si no se encontró ningún sprint con estado 'Curso', buscar el primer sprint con estado 'Curso'
                if (!$courseSprint) {
                    $pendingSprint = $this->sprints->firstWhere('state', 'Pendiente');
                    // Si se encontró un sprint con estado 'Pendiente', asignarlo a $this->firstSprint
                    if ($pendingSprint) {
                        $this->firstSprint = $pendingSprint;
                        // Obtenemos las fechas relevantes
                        $startDate = Carbon::parse($pendingSprint->start_date)->startOfDay();
                        $currentDate = Carbon::now()->startOfDay();
                        // Verificamos si la fecha de inicio del sprint es igual o mayor a hoy
                        if ($startDate->lte($currentDate)) {
                            // Solo cambiar a "Curso" si estamos en o después del 13 de enero
                            if ($currentDate->greaterThanOrEqualTo(Carbon::create($startDate))) {
                                $sprint = Sprint::find($pendingSprint->id);
                                $sprint->state = 'Curso';
                                $sprint->save();
                                // Emitir evento al componente hijo
                                $this->emit('sprintUpdated', $pendingSprint->id);
                            }
                        }
                    } else {
                        // Si no se encontró ningún sprint con estado 'Curso' o 'Pendiente', asignar el primer sprint de la colección
                        $this->firstSprint = $this->sprints->first();
                    }
                } else {
                    // Si se encontró un sprint con estado 'Curso', asignarlo a $this->firstSprint
                    $this->firstSprint = $courseSprint;
                }

                $this->selectSprint = $this->firstSprint->id;
                $this->filteredState = $this->getFilteredStates($this->firstSprint->state);
            }
            // SELECT SPRINT
            $this->updateSprintData($this->selectSprint);
            $this->filteredState = $this->getFilteredStates($this->sprintState);
            $this->firstSprint = Sprint::find($this->selectSprint);
            // Condicion para que solo un Sprint se muestre en Curso
            // if ($this->firstSprint) {
            //     $sprintDesc = $this->sprints;
            //     // Encuentra el sprint anterior en la secuencia
            //     $previousSprint = $sprintDesc->sortByDesc('number')->first(function ($sprint) {
            //         return $sprint->number < $this->firstSprint->number;
            //     });

            //     if ($previousSprint) {
            //         if ($previousSprint->state == 'Curso') {
            //             $this->filteredState = [];
            //         } else {
            //             if ($this->firstSprint->state == 'Curso') {
            //                 $this->filteredState = [];
            //             } elseif ($this->firstSprint->state == 'Pendiente') {
            //                 $this->filteredState = ['Curso'];
            //             } else {
            //                 $this->filteredState = [];
            //             }
            //         }
            //     } else {
            //         if ($this->firstSprint->state == 'Curso') {
            //             $this->filteredState = [];
            //         } elseif ($this->firstSprint->state == 'Pendiente') {
            //             $this->filteredState = ['Curso'];
            //         } else {
            //             $this->filteredState = [];
            //         }
            //     }
            // }
        } else {
            $this->selectSprint = null;
        }
        // COUNT ACTIVITIES
        $totalActivities = Activity::where('sprint_id', $this->selectSprint)->count(); // Contar el número total de actividades del sprint
        $allActivities = Activity::where('sprint_id', $this->selectSprint)->get(); // Seleccionar todas las actividades del sprint
        $sprint = Sprint::find($this->selectSprint);
        if ($totalActivities && Auth::user()->type_user == 1) {
            if ($sprint->state == 'Curso' || $sprint->state == 'Cerrado') {
                $resolvedActivities = $allActivities->where('state', 'Resuelto')->count(); // Contar el número de actividades resueltas
                if ($totalActivities > 0) {
                    $this->percentageResolved = ($resolvedActivities / $totalActivities) * 100; // Calcular el porcentaje de actividades resueltas sobre el total de actividades
                    $this->percentageResolved = round($this->percentageResolved, 2); // Redondear el porcentaje a dos decimales
                    // Verificar si todas las actividades están en estado "Resuelto"
                    $allResolved = $allActivities->every(function ($activity) {
                        return $activity->state === 'Resuelto';
                    });
                    // Si todas las actividades están en estado "Resuelto", actualizar el estado del sprint a "Cerrado"
                    if ($allResolved && Auth::user()->type_user == 1) {
                        $sprint->state = 'Cerrado';
                        $sprint->save();

                        $this->firstSprint = $sprint;
                        $this->sprints = $this->backlog->sprints;
                    }
                } else {
                    // Manejo alternativo si $totalActivities es igual a cero
                    $this->percentageResolved = 0;
                }
            } else {
                // Manejo alternativo state es diferente
                $this->percentageResolved = 0;
            }
        } else {
            // Manejo alternativo si $totalActivities no existe
            $this->percentageResolved = 0;
        }

        return view('livewire.projects.activities.backlog-sprints', [
            'sprints' => $this->sprints,
        ]);
    }
    // ACTIONS
    public function createSprint()
    {
        try {
            $this->validate(
                [
                    'name_sprint' => 'required|max:255',
                    'start_date' => 'required|date|max:255',
                    'end_date' => 'required|date|max:255',
                ],
                [
                    'name_sprint.required' => 'El nombre del sprint es obligatorio.',
                    'name_sprint.max' => 'El nombre del sprint no puede tener más de 255 caracteres.',
                    'start_date.required' => 'La fecha de inicio del sprint es obligatoria.',
                    'start_date.date' => 'La fecha de inicio del sprint debe ser una fecha válida.',
                    'start_date.max' => 'La fecha de inicio del sprint no puede tener más de 255 caracteres.',
                    'end_date.required' => 'La fecha de finalización del sprint es obligatoria.',
                    'end_date.date' => 'La fecha de finalización del sprint debe ser una fecha válida.',
                    'end_date.max' => 'La fecha de finalización del sprint no puede tener más de 255 caracteres.',
                ],
            );

            $sprint = new Sprint();

            $sprints = Sprint::where('backlog_id', $this->backlog->id)
                ->orderBy('number', 'desc')
                ->get();

            if ($sprints->isEmpty()) {
                $sprint->number = 1;
            } else {
                $lastSprintNumber = $sprints->first()->number;
                $sprint->number = $lastSprintNumber + 1;
            }

            $sprint->name = $this->name_sprint;
            $sprint->state = 'Pendiente';
            $sprint->start_date = $this->start_date;
            $sprint->end_date = $this->end_date;
            $sprint->backlog_id = $this->backlog->id;
            $sprint->save();

            // Emitir evento para actualizar la vista
            $this->emit('sprintCreated');
            // Actualiza los sprints en la vista
            $this->sprintsUpdated();
            $this->newSprint();
            // Guardar el error en la base de datos
            Log::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Crear Sprint',
                'message' => 'Sprint creado correctamente',
                'details' => 'Sprint:' . $sprint->id,
            ]);
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Sprint creado',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Crear Sprint',
                'message' => 'Faltan campos o campos incorrectos',
                'details' => $e->getMessage(),
            ]);
            // Manejar errores de validación
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
        } catch (\Exception $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Crear Sprint',
                'message' => 'Ocurrió un error al crear el sprint',
                'details' => $e->getMessage(),
            ]);
            // Manejar cualquier otra excepción
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error inesperado',
                'text' => 'Ocurrió un error al crear el sprint. Por favor, inténtalo de nuevo.',
            ]);
        }
    }

    public function updateSprint($id)
    {
        try {
            $this->validate(
                [
                    'name_sprint' => 'required|max:255',
                ],
                [
                    'name_sprint.required' => 'El nombre del sprint es obligatorio.',
                    'name_sprint.max' => 'El nombre del sprint no puede tener más de 255 caracteres.',
                ],
            );

            $sprint = Sprint::find($id);
            $sprint->name = $this->name_sprint ?? $sprint->name;
            $sprint->state = 'Pendiente';
            $sprint->start_date = $this->start_date ?? $sprint->start_date;
            $sprint->end_date = $this->end_date ?? $sprint->end_date;
            $sprint->save();

            $this->emit('sprintsUpdated');
            $this->newSprint();

            // Guardar el error en la base de datos
            Log::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Actualizar Sprint',
                'message' => 'Sprint actualizado correctamente',
                'details' => 'Sprint:' . $sprint->id,
            ]);
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Sprint actualizado',
            ]);
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Actualizar Sprint',
                'message' => 'Faltan campos o campos incorrectos',
                'details' => $e->getMessage(),
            ]);
            // Manejar errores de validación
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
        } catch (\Exception $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Actualizar Sprint',
                'message' => 'Ocurrió un error al actualizar el sprint',
                'details' => $e->getMessage(),
            ]);
            // Manejar cualquier otra excepción
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error inesperado',
                'text' => 'Ocurrió un error al actualizar el sprint. Por favor, inténtalo de nuevo.',
            ]);
        }
    }

    public function updateStateSprint($id, $state)
    {
        try {
            $sprint = Sprint::find($id);
            $allSprints = Sprint::all()->where('backlog_id', $this->backlog->id);

            if ($sprint) {
                // Encuentra el sprint anterior en la secuencia
                $previousSprint = $allSprints->sortByDesc('number')->first(function ($number) {
                    return $number->number < $this->firstSprint->number;
                });

                if ($this->firstSprint->number == 1) {
                    $sprint->state = $state;
                    $sprint->save();
                    $this->emit('sprintsUpdated');
                    $this->emit('sprintUpdated', $id);

                    // Guardar el error en la base de datos
                    Log::create([
                        'user_id' => Auth::id(),
                        'project_id' => ($this->project) ? $this->project->id : null,
                        'view' => 'livewire/projects/activities/backlog-sprints',
                        'action' => 'Actualizar estado Sprint',
                        'message' => 'Estado del sprint actualizado correctamente',
                        'details' => 'Sprint:' + $sprint->id,
                    ]);

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Estado de sprint actualizado',
                    ]);
                } elseif ($previousSprint) {
                    $sprint->state = $state;
                    $sprint->save();
                    $this->emit('sprintsUpdated');
                    $this->emit('sprintUpdated', $id);

                    // Guardar el error en la base de datos
                    Log::create([
                        'user_id' => Auth::id(),
                        'project_id' => ($this->project) ? $this->project->id : null,
                        'view' => 'livewire/projects/activities/backlog-sprints',
                        'action' => 'Actualizar estado Sprint',
                        'message' => 'Estado del sprint actualizado correctamente',
                        'details' => 'Sprint:' . $sprint->id,
                    ]);

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Estado de sprint actualizado',
                    ]);
                }
            } else {
                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Sprint no encontrado',
                ]);
            }
        } catch (\Exception $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Actualizar estado Sprint',
                'message' => 'Ocurrió un error al actualizar el estado del sprint',
                'details' => $e->getMessage(),
            ]);
            // Manejar cualquier otra excepción
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error inesperado',
                'text' => 'Ocurrió un error al crear el sprint. Por favor, inténtalo de nuevo.',
            ]);
        }
    }

    public function destroySprint($id)
    {
        try {
            $sprintDelete = Sprint::find($id);

            if ($sprintDelete) {
                $sprintNumberToDelete = $sprintDelete->number;
                $sprints = Sprint::all()->where('backlog_id', $this->backlog->id);
                // Eliminar el sprint
                $sprintDelete->delete();
                // Reenumerar los números de los sprints restantes
                foreach ($sprints as $sprint) {
                    if ($sprint->number > $sprintNumberToDelete) {
                        $sprint->number -= 1;
                        $sprint->save();
                    }
                }

                $activities = Activity::where('sprint_id', $id)->get();
                $totalActivities = $activities->count();
                
                foreach ($activities as $activity) {
                    $activitiesRecurrents = ActivityRecurrent::where('activity_repeat', $activity->activity_repeat)->first();
                    ($activitiesRecurrents) ? $activitiesRecurrents->delete() : '';
                    
                    if ($activity->image) {
                        $contentPath = 'activities/' . $activity->image;
                        $fullPath = public_path($contentPath);
        
                        if (File::exists($fullPath)) {
                            File::delete($fullPath);
                        }
                    }
                    $activity->delete();
                }

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => $totalActivities . 'actividad/es eliminada/s',
                ]);

                // Guardar el error en la base de datos
                Log::create([
                    'user_id' => Auth::id(),
                    'project_id' => ($this->project) ? $this->project->id : null,
                    'view' => 'livewire/projects/activities/backlog-sprints',
                    'action' => 'Eliminar Sprint',
                    'message' => 'Sprint eliminado',
                    'details' => 'Actividades eliminadas: ' . $totalActivities,
                ]);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Sprint eliminado',
                ]);
                return redirect()->to('/projects/' . $this->project->id . '/activities');
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Sprint no encontrado',
                ]);
            }
        } catch (\Exception $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project) ? $this->project->id : null,
                'view' => 'livewire/projects/activities/backlog-sprints',
                'action' => 'Eliminar Sprint',
                'message' => 'Ocurrió un error al eliminar el sprint',
                'details' => $e->getMessage(),
            ]);
            // Manejar cualquier otra excepción
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error inesperado',
                'text' => 'Ocurrió un error al crear el sprint. Por favor, inténtalo de nuevo.',
            ]);
        }
    }
    // MODAL
    public function showBacklog()
    {
        if ($this->showBacklog == true) {
            $this->showBacklog = false;
        } else {
            $this->showBacklog = true;
        }
    }

    public function newSprint()
    {
        // Lógica para alternar la ventana de creación
        $this->updateSprint = false;
        $this->newSprint = !$this->newSprint;
        // Limpia los inputs y errores
        $this->clearInputs();
        $this->resetErrorBag();
    }

    public function editSprint($id)
    {
        $this->updateSprint = true;

        ($this->newSprint == true) ? $this->newSprint = false :  $this->newSprint = true;

        $this->sprintEdit = Sprint::find($id);
        $this->name_sprint = $this->sprintEdit->name;

        $fecha_start = Carbon::parse($this->sprintEdit->start_date);
        $this->start_date = $fecha_start->toDateString();
        $fecha_end = Carbon::parse($this->sprintEdit->end_date);
        $this->end_date = $fecha_end->toDateString();
    }
    // ACCIONES EXTRAS
    public function clearInputs()
    {
        $this->name_sprint = '';
        $this->start_date = '';
        $this->end_date = '';
    }

    public function sprintsUpdated()
    {
        $this->sprints = Backlog::find($this->backlog->id)->sprints->sortBy('number');
    }

    public function selectSprint($id)
    {
        $this->changeSprint = true;
        $this->updateSprintData($id);
        $this->selectSprint = $id;
        $this->emit('sprintsUpdated');
    }

    public function recalculatePercentageResolved()
    {
        $this->calculatePercentageResolved();
    }
    // PROTECTED
    protected function updateSprintData($id)
    {
        $sprint = Sprint::find($id);

        if ($sprint) {
            $this->startDate = $sprint->start_date;
            $this->endDate = $sprint->end_date;
            $this->sprintState = $sprint->state;
            $this->idSprint = $sprint->id;

            // Obtenemos las fechas relevantes
            if ($sprint->state == 'Pendiente') {
                $startDate = Carbon::parse($sprint->start_date)->startOfDay();
                $currentDate = Carbon::now()->startOfDay();
                // Verificamos si la fecha de inicio del sprint es igual o mayor a hoy
                if ($startDate->lte($currentDate)) {
                    // Solo cambiar a "Curso" si estamos en o después del 13 de enero
                    if ($currentDate->greaterThanOrEqualTo(Carbon::create($startDate))) {
                        $sprint->state = 'Curso';
                        $sprint->save();

                        // Guardar el error en la base de datos
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => ($this->project) ? $this->project->id : null,
                            'view' => 'livewire/projects/activities/backlog-sprints',
                            'action' => 'Actualizar estado Sprint',
                            'message' => 'Estado del sprint actualizado correctamente',
                            'details' => 'Sprint:' . $sprint->id,
                        ]);

                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'success',
                            'title' => 'Sprint iniciado',
                            'text' => 'El sprint ha comenzado, ya que la fecha actual coincide o es posterior a la fecha de inicio programada.',
                        ]);
                    }
                }
            }
        }
    }

    protected function getFilteredStates($currentState)
    {
        $actions = ['Pendiente', 'Curso', 'Cerrado'];

        if ($currentState == 'Pendiente') {
            return ['Curso'];
        }

        if ($currentState == 'Curso' || $currentState == 'Cerrado') {
            return [];
        }

        // En cualquier otro caso, elimina el estado actual del arreglo
        return array_filter($actions, function ($action) use ($currentState) {
            return $action != $currentState;
        });
    }

    protected function calculatePercentageResolved()
    {
        $totalActivities = Activity::where('sprint_id', $this->selectSprint)->count();
        $allActivities = Activity::where('sprint_id', $this->selectSprint)->get();
        $sprint = Sprint::find($this->selectSprint);

        if ($totalActivities && Auth::user()->type_user == 1) {
            $resolvedActivities = $allActivities->where('state', 'Resuelto')->count();

            if ($totalActivities > 0) {
                $this->percentageResolved = round(($resolvedActivities / $totalActivities) * 100, 2);

                $allResolved = $allActivities->every(function ($activity) {
                    return $activity->state === 'Resuelto';
                });

                if ($allResolved && $sprint->state !== 'Cerrado') {
                    $sprint->state = 'Cerrado';
                    $sprint->save();
                    $this->firstSprint = $sprint;
                    $this->sprints = $this->backlog->sprints;
                }
            } else {
                $this->percentageResolved = 0;
            }
        } else {
            $this->percentageResolved = 0;
        }
    }
}
