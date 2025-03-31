<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Models\Activity;
use App\Models\ActivityRecurrent;
use App\Models\Backlog;
use App\Models\ChatReportsActivities;
use App\Models\ErrorLog;
use App\Models\Log;
use App\Models\Project;
use App\Models\Report;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TableActivities extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'sprintUpdated', 'goToPageWithActivity', 'delete', 'deleteRecurrent', 'openCreateActivityModal' => 'openCreateModal'];
    // ENVIADAS
    public $idsprint, $project, $percentagetesolved;
    // BACKLOG
    public $backlog;
    // SPRINT
    public $sprint, $percentageResolved;
    // FILTROS
    public $search, $allUsers, $allProjects;
    public $selectedDelegate = '', $selectedStates = '', $selectedProjects = '';
    public $allUsersFiltered = [], $allProjectsFiltered = [];
    public $filtered = false; // cambio de dirección de flechas
    public $visiblePanels = []; // Asociativa para controlar los paneles de opciones por ID de reporte
    public $perPage = '20';
    // variables para la consulta
    public $filterPriotiry = false, $filterState = false;
    public $filteredPriority = '', $filteredState = '', $priorityCase = '', $filteredStateArrow = '', $filteredExpected = 'desc', $orderByType = 'expected_date';
    // MODAL CREATE
    public $createEdit = false;
    // MODAL EDIT
    public $editActivity = false;
    public $activityEdit = '';
    // MODAL SHOW
    public $showActivity = false;
    public $activityShow;
    // GRAFICA EFFORT POINTS
    public $starMonth, $endMonth;
    // EXPECTED_DATE
    public $expected_day = [];
    public $errorMessages = []; // Para almacenar mensajes de error dinámicos

    // Resetear paginación cuando se actualiza el campo de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // Método para abrir el modal de creación
    public function openCreateModal()
    {
        $this->createEdit = true; // Abrir el modal
    }

    public function mount()
    {
        if (request()->has('highlight')) {
            $this->goToPageWithActivity(request('highlight'));
        }
    }

    public function render()
    {
        // Verifica si se ha seleccionado un proyecto y un sprint
        if ($this->project != null && $this->idsprint != null) {
            $this->backlog = Backlog::where('project_id', $this->project->id)->first(); // Backlog
            $this->sprint = Sprint::find($this->idsprint); // Sprint
        }
        // Filtro de consulta
        $user = Auth::user();
        $user_id = $user->id;
        // DELEGATE
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
        // FILTRO PROYECTOS
        if ($this->project == null) {
            // PROYECTOS
            $this->allProjects = Project::orderBy('name', 'asc')->get();
            $this->allProjectsFiltered = [];
            foreach ($this->allProjects as $project) {
                $this->allProjectsFiltered[] = [
                    'id' => $project->id,
                    'name' => $project->name,
                ];
            }
        }
        // ACTIVITIES
        if (Auth::user()->type_user == 1) {
            if ($this->project == null && $this->idsprint == null) {
                $activities = Activity::query()
                    ->join('sprints', 'activities.sprint_id', '=', 'sprints.id')
                    ->join('backlogs', 'sprints.backlog_id', '=', 'backlogs.id')
                    ->join('projects', 'backlogs.project_id', '=', 'projects.id')
                    ->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                    ->select('activities.*', 'delegates.name')
                    ->when($this->search, function ($query) {
                        $query->where('activities.title', 'like', '%' . $this->search . '%');
                    })
                    ->when(empty($this->selectedStates), function ($query) {
                        $query->where('activities.state', '!=', 'Resuelto');
                    })
                    ->when(!empty($this->selectedProjects), function ($query) {
                        $query->where('backlogs.project_id', $this->selectedProjects);
                    })
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when($this->selectedStates, function ($query) {
                        $query->where('activities.state', $this->selectedStates);
                    })
                    ->when($this->filterPriotiry, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                    })
                    ->when($this->filterState, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                    })
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            } else {
                $activities = Activity::where('sprint_id', $this->idsprint)
                    ->where(function ($query) {
                        $query
                            ->where('title', 'like', '%' . $this->search . '%');
                    })
                    ->when($this->filterPriotiry, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                    })
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when(!empty($this->selectedStates), function ($query) {
                        $query->where('state', $this->selectedStates);
                    })
                    ->when($this->filterState, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                    })
                    ->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                    ->select('activities.*', 'delegates.name')
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            }
        } else {
            if ($this->project == null && $this->idsprint == null) {
                $activities = Activity::query()
                    ->join('sprints', 'activities.sprint_id', '=', 'sprints.id')
                    ->join('backlogs', 'sprints.backlog_id', '=', 'backlogs.id')
                    ->join('projects', 'backlogs.project_id', '=', 'projects.id')
                    ->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                    ->select('activities.*', 'delegates.name')
                    ->when($this->search, function ($query) {
                        $query->where('activities.title', 'like', '%' . $this->search . '%');
                    })
                    ->when(empty($this->selectedStates), function ($query) {
                        $query->where('activities.state', '!=', 'Resuelto');
                    })
                    ->when(!empty($this->selectedProjects), function ($query) {
                        $query->where('backlogs.project_id', $this->selectedProjects);
                    })
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when($this->selectedStates, function ($query) {
                        $query->where('activities.state', $this->selectedStates);
                    })
                    ->when($this->filterPriotiry, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                    })
                    ->when($this->filterState, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                    })
                    ->where(function ($query) use ($user_id) {
                        $query->where('delegate_id', $user_id);
                    })
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            } else {
                $activities = Activity::where('sprint_id', $this->idsprint)
                    ->where(function ($query) {
                        $query
                            ->where('title', 'like', '%' . $this->search . '%');
                    })
                    ->when($this->filterPriotiry, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                    })
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when(!empty($this->selectedStates), function ($query) {
                        $query->where('state', $this->selectedStates);
                    })
                    ->when($this->filterState, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                    })
                    ->where(function ($query) use ($user_id) {
                        $query->where('delegate_id', $user_id);
                    })
                    ->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                    ->select('activities.*', 'delegates.name')
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            }
        }
        // TODOS LOS DELEGADOS
        $this->allUsersFiltered = [];
        foreach ($this->allUsers as $user) {
            $this->allUsersFiltered[] = [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        // ADD ATRIBUTES
        foreach ($activities as $activity) {
            // FECHA DE ENTREGA
            $this->expected_day[$activity->id] = ($activity->expected_date) ? Carbon::parse($activity->expected_date)->format('Y-m-d') : '';
            // ACTIONS
            $activity->filteredActions = $this->getFilteredActions($activity->state);
            // DELEGATE
            $activity->usersFiltered = $this->allUsers
                ->reject(function ($user) use ($activity) {
                    return $user->id === $activity->delegate_id;
                })
                ->values();
            // PROGRESS
            if ($activity->progress && $activity->updated_at) {
                $progress = Carbon::parse($activity->progress);
                $updated_at = Carbon::parse($activity->updated_at);
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

                $activity->timeDifference = $timeDifference;
            } else {
                $activity->timeDifference = null;
            }
            // CHAT
            $messages = ChatReportsActivities::where('activity_id', $activity->id)->orderBy('created_at', 'asc')->get();
            $lastMessageNoView = ChatReportsActivities::where('activity_id', $activity->id)
                ->where('user_id', '!=', Auth::id())
                ->where('receiver_id', Auth::id())
                ->where('look', false)
                ->latest()
                ->first();
            // Verificar si la colección tiene al menos un mensaje
            if ($messages) {
                if ($lastMessageNoView) {
                    $activity->user_chat = $lastMessageNoView->user_id;
                    $activity->receiver_chat = $lastMessageNoView->receiver_id;

                    $receiver = User::find($lastMessageNoView->receiver_id);

                    if ($receiver->type_user == 3) {
                        $activity->client = true;
                    } else {
                        $activity->client = false;
                    }
                } else {
                    $lastMessage = $messages->last();

                    if ($lastMessage) {
                        if ($lastMessage->user_id == Auth::id()) {
                            $activity->user_id = true;
                        } else {
                            if ($lastMessage->receiver) {
                                if ($lastMessage->receiver->type_user == 3) {
                                    $activity->client = true;
                                } else {
                                    $activity->client = false;
                                }
                            } else {
                                $activity->client = false;
                            }
                            // VER MENSAJES EXCLUSIVOS DE CLIENTE PARA ADMINISTRADORES
                            if ($lastMessage->transmitter && Auth::user()->type_user == 1) {
                                if ($lastMessage->transmitter->type_user == 3) {
                                    $activity->client = true;
                                } else {
                                    $activity->client = false;
                                }
                            } else {
                                $activity->client = false;
                            }
                            $activity->user_id = false;
                        }
                    }
                }
            }
            $activity->messages_count = $messages->where('look', false)->count();
        }

        // COUNT ACTIVITIES
        $totalActivities = Activity::where('sprint_id', $this->idsprint)->count(); // Contar el número total de actividades del sprint
        $allActivities = Activity::where('sprint_id', $this->idsprint)->get(); // Seleccionar todas las actividades del sprint
        $sprint = Sprint::find($this->idsprint);
        if ($totalActivities) {
            if ($sprint->state == 'Curso' || $sprint->state == 'Cerrado') {
                $resolvedActivities = $allActivities->where('state', 'Resuelto')->count(); // Contar el número de actividades resueltas
                if ($totalActivities > 0) {
                    $this->percentageResolved = ($resolvedActivities / $totalActivities) * 100; // Calcular el porcentaje de actividades resueltas sobre el total de actividades
                    $this->percentageResolved = round($this->percentageResolved, 2); // Redondear el porcentaje a dos decimales
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
        return view('livewire.projects.activities.table-activities', [
            'activities' => $activities,
        ]);
    }
    // ACTIONS
    public function delete($id)
    {
        try {
            $activity = Activity::find($id);

            if ($activity) {
                if ($activity->activity_repeat != null) {
                    $activities = Activity::where('activity_repeat', $activity->activity_repeat)->get();
                    // Ya no existen actividades recurrentes
                    $activitiesRecurrents = ActivityRecurrent::where('activity_repeat', $activity->activity_repeat)->first();

                    if ($activities->count() === 1) {
                        if ($activitiesRecurrents) {
                            $activitiesRecurrents->delete();
                        }

                        if ($activity->image) {
                            $contentPath = 'activities/' . $activity->image;
                            $fullPath = public_path($contentPath);

                            if (File::exists($fullPath)) {
                                File::delete($fullPath);
                            }
                        }
                        $activity->delete();
                        // } else {
                        //     $lastDateActivity = Carbon::parse($activity->expected_date)->startOfDay();
                        //     $lastDateRecurrent = Carbon::parse($activitiesRecurrents->last_date)->startOfDay();

                        //     if ($lastDateActivity == $lastDateRecurrent) {
                        //         if ($activity->image) {
                        //             $contentPath = 'activities/' . $activity->image;
                        //             $fullPath = public_path($contentPath);

                        //             if (File::exists($fullPath)) {
                        //                 File::delete($fullPath);
                        //             }
                        //         }
                        //         // Eliminar la actividad
                        //         $activity->delete();

                        //         // Actualizar la colección después de eliminar
                        //         $activities = $activities->fresh();
                        //         $activitiesRecurrents->last_date = $activities->last()->expected_date;
                        //         $activitiesRecurrents->save();
                        //     } else {
                        //         if ($activity->image) {
                        //             $contentPath = 'activities/' . $activity->image;
                        //             $fullPath = public_path($contentPath);

                        //             if (File::exists($fullPath)) {
                        //                 File::delete($fullPath);
                        //             }
                        //         }
                        //         $activity->delete();
                        //     }
                    } else {
                        if ($activity->image) {
                            $contentPath = 'activities/' . $activity->image;
                            $fullPath = public_path($contentPath);

                            if (File::exists($fullPath)) {
                                File::delete($fullPath);
                            }
                        }
                        $activity->delete();

                        $lastActivityRepeat = Activity::where('activity_repeat', $activity->activity_repeat)->orderBy('expected_date', 'desc')->latest()->first();

                        $activitiesRecurrents->last_date = Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d');

                        if ($activitiesRecurrents->end_date != null) {
                            $activitiesRecurrents->end_date = Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d');
                        }

                        $activitiesRecurrents->save();
                    }
                } else {
                    if ($activity->image) {
                        $contentPath = 'activities/' . $activity->image;
                        $fullPath = public_path($contentPath);

                        if (File::exists($fullPath)) {
                            File::delete($fullPath);
                        }
                    }
                    $activity->delete();
                }

                Log::create([
                    'user_id' => Auth::id(),
                    'project_id' => ($this->project != null) ? $this->project->id : null,
                    'activity_id' => $id,
                    'view' => 'livewire/projects/activities/table-activities',
                    'action' => 'Eliminar actividad',
                    'message' => 'Actividad eliminada',
                ]);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Actividad eliminada',
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Actividad no encontrada',
                ]);
            }
        } catch (\Exception $e) {
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project != null) ? $this->project->id : null,
                'activity_id' => $id,
                'view' => 'livewire/projects/activities/table-activities',
                'action' => 'Eliminar actividad',
                'message' => 'Error en eliminar actividad',
                'details' => $e->getMessage(), // Mensaje de la excepción
            ]);

            // Mostrar mensaje de error al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function deleteRecurrent($id)
    {
        try {
            $activity = Activity::find($id);
            if ($activity) {
                $activitiesAbierto = Activity::where('activity_repeat', $activity->activity_repeat)->where('state', 'Abierto')->get();
                $activitiesNoAbierto = Activity::where('activity_repeat', $activity->activity_repeat)->where('state', '!=',  'Abierto')->orderBy('expected_date', 'asc')->get();
                $activitiesRecurrents = ActivityRecurrent::where('activity_repeat', $activity->activity_repeat)->first();
                
                if ($activitiesNoAbierto->count() <= 1) {
                    if ($activitiesRecurrents) {
                        $activitiesRecurrents->delete();
                    }
                } else {
                    if ($activitiesRecurrents && $activitiesRecurrents->end_date != null) {
                        $activitiesRecurrents->last_date = $activitiesNoAbierto->last()->expected_date;
                        $activitiesRecurrents->end_date = $activitiesNoAbierto->last()->expected_date;
                        $activitiesRecurrents->save();
                    }
                }

                // Recorrer y eliminar cada actividad principal
                foreach ($activitiesAbierto as $activity) {
                    if ($activity->image) {
                        $contentPath = 'activities/' . $activity->image;
                        $fullPath = public_path($contentPath);

                        if (File::exists($fullPath)) {
                            File::delete($fullPath);
                        }
                    }
                    $activity->delete();
                }

                // Registrar en logs
                Log::create([
                    'user_id' => Auth::id(),
                    'project_id' => ($this->project != null) ? $this->project->id : null,
                    'activity_id' => $id,
                    'view' => 'livewire/projects/activities/table-activities',
                    'action' => 'Eliminar actividades recurrentes',
                    'message' => 'Actividades eliminadas',
                ]);

                // Mostrar mensaje de éxito
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Actividades eliminadas',
                ]);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Actividad no encontrada',
                ]);
            }
        } catch (\Exception $e) {
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project != null) ? $this->project->id : null,
                'activity_id' => $id,
                'view' => 'livewire/projects/activities/table-activities',
                'action' => 'Eliminar actividades recurrentes',
                'message' => 'Error en eliminar actividades',
                'details' => $e->getMessage(),
            ]);

            // Mostrar mensaje de error
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function updateDelegate($id, $delegate)
    {
        try {
            // Oculta todos los paneles
            $this->visiblePanels = [];

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
                    'project_id' => ($this->project != null) ? $this->project->id : null,
                    'activity_id' => $id,
                    'view' => 'livewire/projects/activities/table-activities',
                    'action' => 'Cambio de delegado',
                    'message' => 'Delegado actualizado',
                    'details' => 'Delegado: ' . $activity->delegate_id,
                ]);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Delegado actualizado',
                ]);
            }
        } catch (\Exception $e) {
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project != null) ? $this->project->id : null,
                'activity_id' => $id,
                'view' => 'livewire/projects/activities/table-activities',
                'action' => 'Cambio de delegado',
                'message' => 'Error en actualizar delegado',
                'details' => $e->getMessage(), // Mensaje de la excepción
            ]);

            // Manejar el error y mostrar un mensaje al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function updateState($id, $state)
    {
        try {
            // Oculta todos los paneles
            $this->visiblePanels = [];

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
                        'project_id' => ($this->project != null) ? $this->project->id : null,
                        'activity_id' => $activity->id,
                        'view' => 'livewire/projects/activities/table-activities',
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
                        'project_id' => ($this->project != null) ? $this->project->id : null,
                        'activity_id' => $activity->id,
                        'view' => 'livewire/projects/activities/table-activities',
                        'action' => 'Cambio de estado',
                        'message' => 'Estado actualizado',
                        'details' => 'Estado: ' . $activity->state,
                    ]);

                    // Emitir un evento para notificar al componente padre
                    $this->emitUp('activityUpdated');
                    // Actualizacion de grafica
                    $this->effortPoints();
                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Estado actualizado',
                    ]);
                }
            }
        } catch (\Exception $e) {
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project != null) ? $this->project->id : null,
                'activity_id' => $id,
                'view' => 'livewire/projects/activities/table-activities',
                'action' => 'Cambio de estado',
                'message' => 'Error en actualizar estado',
                'details' => $e->getMessage(), // Mensaje de la excepción
            ]);

            // Manejar el error y mostrar un mensaje al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }

    public function updateExpectedDay($id, $day)
    {
        try {
            $activity = Activity::find($id);

            if ($activity) {
                // Convertir la fecha ingresada a un objeto Carbon
                $newDate = Carbon::parse($day)->format('Y-m-d');
                $today = Carbon::now()->format('Y-m-d');

                if ($newDate < $today) {
                    $this->errorMessages[$id] = 'No puedes seleccionar una fecha anterior a hoy.';
                    $this->expected_day[$id] = $activity->expected_date
                        ? Carbon::parse($activity->expected_date)->format('Y-m-d')
                        : '';

                    return;
                }

                // Limpiar mensaje de error si la fecha es válida
                unset($this->errorMessages[$id]);

                // Actualizar la fecha en la base de datos
                $activity->expected_date = Carbon::parse($day)->format('Y-m-d');
                $activity->save();

                $repeat = ($activity->activity_repeat != null) ? true : false;

                if ($repeat) {
                    $ActivityRepeat = Activity::where('activity_repeat', $activity->activity_repeat)->get();
                    $activitiesRecurrents = ActivityRecurrent::where('activity_repeat', $activity->activity_repeat)->first();

                    if ($ActivityRepeat->count() == 1) {
                        switch ($activitiesRecurrents->frequency) {
                            case 'Dairy':
                                if ($activitiesRecurrents->end_date != null) {
                                    $activitiesRecurrents->end_date = Carbon::parse($day);
                                    $activitiesRecurrents->last_date = Carbon::parse($day);
                                } else {
                                    $activitiesRecurrents->last_date = Carbon::parse($day)->addDay();
                                }
                                $activitiesRecurrents->save();
                                break;
                            case 'Weekly':
                                if ($activitiesRecurrents->end_date != null) {
                                    $activitiesRecurrents->end_date = Carbon::parse($day);
                                    $activitiesRecurrents->last_date = Carbon::parse($day);
                                } else {
                                    $activitiesRecurrents->last_date = Carbon::parse($day)->addWeek();
                                }
                                $activitiesRecurrents->save();
                                break;
                            case 'Monthly':
                                if ($activitiesRecurrents->end_date != null) {
                                    $activitiesRecurrents->end_date = Carbon::parse($day);
                                    $activitiesRecurrents->last_date = Carbon::parse($day);
                                } else {
                                    $activitiesRecurrents->last_date = Carbon::parse($day)->addMonth();
                                }
                                $activitiesRecurrents->save();
                                break;
                            case 'Yearly':
                                if ($activitiesRecurrents->end_date != null) {
                                    $activitiesRecurrents->end_date = Carbon::parse($day);
                                    $activitiesRecurrents->last_date = Carbon::parse($day);
                                } else {
                                    $activitiesRecurrents->last_date = Carbon::parse($day)->addYear();
                                }
                                $activitiesRecurrents->save();
                                break;
                            default:
                                break;
                        }
                    } else {
                        $lastActivityRepeat = Activity::where('activity_repeat', $activity->activity_repeat)->orderBy('expected_date', 'desc')->latest()->first();

                        $activitiesRecurrents->last_date = Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d');

                        if ($activitiesRecurrents->end_date != null) {
                            $activitiesRecurrents->end_date = Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d');
                        }

                        $activitiesRecurrents->save();
                    }
                }

                Log::create([
                    'user_id' => Auth::id(),
                    'project_id' => ($this->project != null) ? $this->project->id : null,
                    'activity_id' => $activity->id,
                    'view' => 'livewire/projects/activities/table-activities',
                    'action' => 'Actualizar fecha de entrega',
                    'message' => 'Fecha de entrega actualizada',
                    'details' => 'Estado: ' . $activity->expected_date,
                ]);

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
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => ($this->project != null) ? $this->project->id : null,
                'activity_id' => $id,
                'view' => 'livewire/projects/activities/table-activities',
                'action' => 'Actualizar fecha de entrega',
                'message' => 'Error en actualizar fecha de entrega',
                'details' => $e->getMessage(), // Mensaje de la excepción
            ]);

            // Manejar el error y mostrar un mensaje al usuario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtelo de nuevo.',
            ]);
        }
    }
    // MODAL
    public function create($idProject)
    {
        // Oculta todos los paneles
        $this->visiblePanels = [];

        if ($idProject != 0) {
            $this->createEdit = !$this->createEdit;
        }
    }

    public function showActivity($id)
    {
        // Oculta todos los paneles
        $this->visiblePanels = [];

        if ($this->showActivity == true) {
            $this->showActivity = false;
            $this->activityShow = null;
        } else {
            $this->activityShow = Activity::find($id);
            if ($this->activityShow) {
                $this->showActivity = true;
            } else {
                $this->showActivity = false;
                // Maneja un caso en el que no se encuentra el reporte (opcional)
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'La actividad no existe',
                ]);
            }
        }
    }

    public function editActivity($id)
    {
        // Oculta todos los paneles
        $this->visiblePanels = [];

        if ($this->createEdit == true) {
            $this->createEdit = false;
            $this->activityEdit = null;
        } else {
            $this->activityEdit = Activity::find($id);
            if ($this->activityEdit) {
                $this->createEdit = true;
            } else {
                $this->createEdit = false;
                // Maneja un caso en el que no se encuentra el actividad (opcional)
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El reporte no existe',
                ]);
            }
        }
    }
    // EXTRAS
    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }

    public function sprintUpdated($sprintId)
    {
        if ($this->idsprint == $sprintId) {
            $this->sprint = Sprint::find($sprintId);
        }
    }
    // FILTER
    public function togglePanel($activityId)
    {
        // Si el panel ya está visible, lo cerramos
        if (isset($this->visiblePanels[$activityId]) && $this->visiblePanels[$activityId]) {
            unset($this->visiblePanels[$activityId]);
        } else {
            // Cerrar todos los demás paneles
            $this->visiblePanels = [];

            // Abrir el panel seleccionado
            $this->visiblePanels[$activityId] = true;
        }
    }

    public function filterDown($type)
    {
        $this->filtered = false; // Cambio de flechas
        // Oculta todos los paneles
        $this->visiblePanels = [];
        // Reiniciar todos los filtros
        $this->filterPriotiry = false;
        $this->filterState = false;

        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filteredPriority = 'asc';
            $this->priorityCase = "CASE WHEN activities.priority = 'Bajo' THEN 1 WHEN activities.priority = 'Medio' THEN 2 WHEN activities.priority = 'Alto' THEN 3 ELSE 4 END";
        }

        if ($type == 'state') {
            $this->filterState = true;
            $this->filteredState = 'asc';
            $this->priorityCase = "CASE WHEN activities.state = 'Abierto' THEN 1 WHEN activities.state = 'Proceso' THEN 2 WHEN activities.state = 'Conflicto' THEN 3 WHEN activities.state = 'Resuelto' THEN 4 ELSE 5 END";
        }

        if ($type == 'delegate') {
            $this->orderByType = 'name';
            $this->filteredExpected = 'asc';
        }

        if ($type == 'expected_date') {
            $this->orderByType = 'expected_date';
            $this->filteredExpected = 'asc';
        }
    }

    public function filterUp($type)
    {
        $this->filtered = true; // Cambio de flechas
        // Oculta todos los paneles
        $this->visiblePanels = [];
        // Reiniciar todos los filtros
        $this->filterPriotiry = false;
        $this->filterState = false;

        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filteredPriority = 'asc';
            $this->priorityCase = "CASE WHEN activities.priority = 'Alto' THEN 1 WHEN activities.priority = 'Medio' THEN 2 WHEN activities.priority = 'Bajo' THEN 3 ELSE 4 END";
        }

        if ($type == 'state') {
            $this->filterState = true;
            $this->filteredState = 'asc';
            $this->priorityCase = "CASE WHEN activities.state = 'Resuelto' THEN 1 WHEN activities.state = 'Conflicto' THEN 2 WHEN activities.state = 'Proceso' THEN 3 WHEN activities.state = 'Abierto' THEN 4 ELSE 5 END";
        }

        if ($type == 'delegate') {
            $this->orderByType = 'name';
            $this->filteredExpected = 'desc';
        }

        if ($type == 'expected_date') {
            $this->orderByType = 'expected_date';
            $this->filteredExpected = 'desc';
        }
    }

    public function goToPageWithActivity($activityId)
    {
        $activity = Activity::find($activityId);
        $user_id = Auth::user()->id;

        if ($activity) {
            // Replicar la misma lógica de consulta que en render()
            $query = Activity::where('sprint_id', $activity->sprint_id)
                ->when($this->filterPriotiry, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when(!empty($this->selectedStates), function ($query) {
                    $query->where('state', $this->selectedStates);
                })
                ->when($this->filterState, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                });

            // Aplicar filtro por tipo de usuario
            if (Auth::user()->type_user != 1) {
                $query->where('delegate_id', $user_id);
            }

            // Continuar con la consulta base
            $query->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                ->select('activities.*', 'delegates.name')
                ->orderBy($this->orderByType, $this->filteredExpected);

            // Buscar el índice considerando todos los filtros
            $index = $query->pluck('activities.id')->search($activityId);

            if ($index !== false) {
                $page = floor($index / $this->perPage) + 1;
    
                // Forzar el cambio de página en Livewire
                $this->setPage($page);
    
                // Emitir evento para que el frontend haga scroll después de la actualización
                $this->emit('activityHighlighted', $activityId);
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Actividad no está visible',
                ]);
            }
        }
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
}
