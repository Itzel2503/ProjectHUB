<?php

namespace App\Http\Livewire\ActivitiesReports;

use App\Models\Activity;
use App\Models\ChatReportsActivities;
use App\Models\Project;
use App\Models\Report;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;

class Created extends Component
{
    // FILTROS
    public $search, $allUsers;
    public $selectedDelegate = '';
    public $allUsersFiltered = [], $selectedStates = [];
    public $filtered = false; // cambio de dirección de flechas
    public $isOptionsVisible = false; // Controla la visibilidad del panel de opciones
    public $visiblePanels = []; // Asociativa para controlar los paneles de opciones por ID de reporte
    public $perPage = '20';
    // variables para la consulta
    public $filterPriotiry = false, $filterState = false;
    public $filteredPriority = '', $filteredState = '', $priorityCase = '', $filteredStateArrow = '', $filteredExpected = 'desc', $orderByType = 'expected_date';
    // MODAL SHOW
    public $show = false;
    public $taskShow = '';
    public $taskShowType = '';
    // MODAL EVIDENCE
    public $reportEvidence;
    public $evidenceActRep = false;

    public function render()
    {
        // Filtro de consulta
        $userLogin = Auth::user();
        $user_id = $userLogin->id;
        // DELEGATE
        $this->allUsers = User::where('type_user', '!=', 3)->where('id', '!=', Auth::id())->orderBy('name', 'asc')->get();
        // Obtener los reports del usuario
        $reports = User::select(
            'users.id as user',
            'users.name as user_name',
            'reports.*'
        )
            ->leftJoin('reports', 'users.id', '=', 'reports.user_id')
            ->where('reports.user_id', $user_id) 
            ->where('reports.title', 'like', '%' . $this->search . '%')
            ->when($this->filterPriotiry, function ($query) {
                $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
            })
            ->when($this->filterState, function ($query) {
                $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
            })
            ->when(!empty($this->selectedStates), function ($query) {
                // Filtrar por los estados seleccionados en los checkboxes
                $query->whereIn('reports.state', $this->selectedStates);
            })
            ->when($this->selectedDelegate, function ($query) {
                $query->where('delegate_id', $this->selectedDelegate);
            })
            ->orderBy($this->orderByType, $this->filteredExpected)
            ->get();
        // Obtener las activities del usuario
        $activities = User::select(
            'users.id as user',
            'users.name as user_name',
            'activities.*'
        )
            ->leftJoin('activities', 'users.id', '=', 'activities.user_id')
            ->where('activities.user_id', $user_id) 
            ->where('activities.title', 'like', '%' . $this->search . '%')
            ->when($this->filterPriotiry, function ($query) {
                $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
            })
            ->when($this->filterState, function ($query) {
                $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
            })
            ->when(!empty($this->selectedStates), function ($query) {
                // Filtrar por los estados seleccionados en los checkboxes
                $query->whereIn('activities.state', $this->selectedStates);
            })
            ->when($this->selectedDelegate, function ($query) {
                $query->where('delegate_id', $this->selectedDelegate);
            })
            ->orderBy($this->orderByType, $this->filteredExpected)
            ->get();
        // Combinar los resultados manualmente
        $tasks = new \Illuminate\Database\Eloquent\Collection;

        foreach ($activities as $activity) {
            $tasks->push($activity);
        }

        foreach ($reports as $report) {
            $tasks->push($report);
        }
        // Ordenar la colección combinada
        $tasks = $tasks->sortBy(function ($task) {
            return $task->expected_date;
        }, SORT_REGULAR, $this->filteredExpected === 'desc');
        // Paginación manual
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = $this->perPage;
        $currentItems = $tasks->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedTask = new LengthAwarePaginator($currentItems, $tasks->count(), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        // TODOS LOS DELEGADOS
        $this->allUsersFiltered = [];
        foreach ($this->allUsers as $user) {
            $this->allUsersFiltered[] = [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        // ADD ATRIBUTES
        foreach ($tasks as $task) {
            // ACTIONS
            $task->filteredActions = $this->getFilteredActions($task->state);
            // DELEGATE
            $task->usersFiltered = $this->allUsers
                ->reject(function ($user) use ($task) {
                    return $user->id === $task->delegate_id;
                })
                ->values();
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
            } else {
                $task->created_name = 'Usuario eliminado';
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
                            $task->project_name = $sprint->backlog->project->name . ' (Actividad)';
                            $task->project_id = $sprint->backlog->project->id;
                            $task->project_activity = true;
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
                    $task->project_name = $project->name . ' (Reporte)';
                    $task->project_id = $project->id;
                    $task->project_report = true;
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
        
        return view('livewire.activities-reports.created', [
            'tasks' => $paginatedTask,
        ]);
    }
    // ACTIONS
    public function updateDelegate($id, $delegate, $tpe)
    {
        if ($tpe == 'report') {
            $report = Report::find($id);
            if ($report) {
                $report->delegate_id = $delegate;
                $report->delegated_date = Carbon::now();
                $report->progress = null;
                $report->look = false;
                $report->state = 'Abierto';
                $report->save();

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

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Delegado actualizado',
                ]);
            }
        }
    }

    public function updateState($id, $project_id, $state)
    {
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
                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Estado actualizado',
                    ]);
                }

                if ($state == 'Resuelto') {
                    $activity->end_date = Carbon::now();
                    $activity->state = $state;
                    $activity->save();
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
                        $report->state = $state;
                        $report->updated_expected_date = true;
                        $report->end_date = Carbon::now();
                        $report->repeat = true;
                        $report->save();

                        // Emitir un evento de navegador
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'success',
                            'title' => 'Estado actualizado',
                        ]);
                    }
                }
            }
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
            $this->taskShow = ($type == 'report') ? Report::find($id) : Activity::find($id) ;
            $this->taskShowType = ($type == 'report') ? 'report' : 'activity' ;
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
        
        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filterState = false;
            $this->filteredPriority = 'asc';
            $this->priorityCase = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }

        if ($type == 'state') {
            $this->filterPriotiry = false;
            $this->filterState = true;
            $this->filteredState = 'asc';
            $this->priorityCase = "CASE WHEN state = 'Abierto' THEN 1 WHEN state = 'Proceso' THEN 2 WHEN state = 'Conflicto' THEN 3 WHEN state = 'Resuelto' THEN 4 ELSE 5 END";
        }

        if ($type == 'delegate') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->orderByType = 'name';
            $this->filteredExpected = 'asc';
        }

        if ($type == 'expected_date') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->orderByType = 'expected_date';
            $this->filteredExpected = 'asc';
        }
    }

    public function filterUp($type)
    {
        $this->filtered = true; // Cambio de flechas
        
        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filterState = false;
            $this->filteredPriority = 'asc';
            $this->priorityCase = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        
        if ($type == 'state') {
            $this->filterPriotiry = false;
            $this->filterState = true;
            $this->filteredState = 'asc';
            $this->priorityCase = "CASE WHEN state = 'Resuelto' THEN 1 WHEN state = 'Conflicto' THEN 2 WHEN state = 'Proceso' THEN 3 WHEN state = 'Abierto' THEN 4 ELSE 5 END";
        }

        if ($type == 'delegate') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->orderByType = 'name';
            $this->filteredExpected = 'desc';
        }

        if ($type == 'expected_date') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->orderByType = 'expected_date';
            $this->filteredExpected = 'desc';
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
}
