<?php

namespace App\Http\Livewire\ActivitiesReports;

use App\Models\Activity;
use App\Models\ChatReportsActivities;
use App\Models\Evidence;
use App\Models\Project;
use App\Models\Report;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

use function Ramsey\Uuid\v1;

class ActivitiesReports extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['messageSentReport' => 'loadMessagesReport', 'messageSentActivity' => 'loadMessagesActivity'];
    // PESTAÑA
    public $activeTab = 'actividades';
    // Generales
    public $allUsers;
    public $usersFiltered = [],
        $allUsersFiltered = [];
    public $selectedDelegate = '';
    public $perPage = '50';
    // FILTRO PUNTOS
    public $startDate, $endDate, $starMonth, $endMonth;
    // FILTRO ESTADOS
    public $selectedStates = [];
    // ------------------------------ ACTIVITY ------------------------------
    // modal activity
    public $modalShowActivity = false;
    public $showActivity = false,
        $showChatActivity = false;
    public $activityShow, $messagesActivity, $messageActivity;
    // table, action's activities
    public $searchActivity, $firstSprint;
    public $filteredActivity = false, $filterActivity = false;
    public $filteredPriorityActivity = '', $priorityCaseActivity = '', $expected_dateActivity = 'asc';
    // ------------------------------ REPORT ------------------------------
    // modal show report
    public $modalShowReport = false;
    public $showReport = false,
        $showChatReport = false,
        $showEvidence = false;
    public $reportShow;
    public $messagesReport, $messageReport;
    // modal evidence report
    public $modalEvidence = false;
    public $evidenceShow;
    // modal edit report
    public $modalEditReport = false;
    public $showEditReport = false;
    public $reportEdit, $clientDukke;
    // modal activity points
    public $changePoints = false;
    public $points, $point_know, $point_many, $point_effort;
    // inputs
    public $tittle, $type, $file, $comment, $evidenceEdit, $expected_date, $priority1, $priority2, $priority3, $evidence, $message;
    // table, action's reports
    public $searchReport, $reportEvidenceReport;
    public $searchDukke;
    public $filteredReport = false, $filterReport = false;
    public $filteredDukke = false, $filterDukke = false;
    public $filteredPriorityReport = '', $priorityCaseReport = '', $expected_dateReport = 'asc', $filteredPriorityDukke = '', $priorityCaseDukke = '', $expected_dateDukke = 'asc';
    // ------------------------------ TASK ADMIN ------------------------------
    public $allUsersTask;
    public $searchTask;
    public $expected_dateTask = 'asc';
    public $filteredTask = false;
    // ------------------------------ CREATED ADMIN ------------------------------
    public $allUsersCreated;
    public $searchCreated;
    public $expected_dateCreated = 'asc';
    public $filteredCreated = false;

    public function mount()
    {
        $user = Auth::user();

        if ($user && $user->type_user == 1) {
            $this->activeTab = 'task';
        } else {
            $this->activeTab = 'actividades';
        }
    }

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');
        // DELEGATE
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
        $this->allUsersTask = User::where('type_user', '!=', 3)->where('id', '!=', Auth::id())->orderBy('name', 'asc')->get();
        $this->allUsersCreated = User::where('type_user', '!=', 3)->where('id', '!=', Auth::id())->orderBy('name', 'asc')->get();
        // Filtro de consulta
        $userLogin = Auth::user();
        $user_id = $userLogin->id;
        if (!empty($this->selectedStates)) {
            $this->resetPage();
        }
        // ACTIVITIES
        if (Auth::user()->type_user == 1) {
            $activities = Activity::where(function ($query) {
                $query
                    ->where('title', 'like', '%' . $this->searchActivity . '%');
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterActivity, function ($query) {
                    $query->orderByRaw($this->priorityCaseActivity . ' ' . $this->filteredPriorityActivity);
                })
                ->when($this->selectedStates, function ($query) {
                    // Filtrar por los estados seleccionados en los checkboxes
                    return $query->whereIn('activities.state', $this->selectedStates);
                })
                ->orderBy('expected_date', $this->expected_dateActivity)
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);

            $reports = Report::where(function ($query) {
                $query
                    ->where('title', 'like', '%' . $this->searchReport . '%');
                if (strtolower($this->searchReport) === 'reincidencia' || strtolower($this->searchReport) === 'Reincidencia') {
                    $query->orWhereNotNull('count');
                }
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterReport, function ($query) {
                    $query->orderByRaw($this->priorityCaseReport . ' ' . $this->filteredPriorityReport);
                })
                ->when($this->selectedStates, function ($query) {
                    // Filtrar por los estados seleccionados en los checkboxes
                    return $query->whereIn('reports.state', $this->selectedStates);
                })
                ->orderBy('expected_date', $this->expected_dateReport)
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);

            if (Auth::user()->area_id == 4) {
                $reportsDukke = Report::where('project_id', 5)
                    ->whereHas('user', function ($query) {
                        $query->where('type_user', 3);
                    })
                    ->where(function ($query) {
                        $query
                            ->where('title', 'like', '%' . $this->searchDukke . '%');
                        if (strtolower($this->searchDukke) === 'reincidencia' || strtolower($this->searchDukke) === 'Reincidencia') {
                            $query->orWhereNotNull('count');
                        }
                    })
                    ->where(function ($query) use ($user_id) {
                        $query
                            ->where('delegate_id', $user_id)
                            // O incluir registros donde user_id es igual a user_id y video es true
                            ->orWhere(function ($subQuery) use ($user_id) {
                                $subQuery->where('user_id', $user_id)->where('video', true);
                            });
                    })
                    ->when($this->filterDukke, function ($query) {
                        $query->orderByRaw($this->priorityCaseDukke . ' ' . $this->filteredPriorityDukke);
                    })
                    ->when($this->selectedStates, function ($query) {
                        // Filtrar por los estados seleccionados en los checkboxes
                        return $query->whereIn('reports.state', $this->selectedStates);
                    })
                    ->orderBy('expected_date', $this->expected_dateDukke)
                    ->where('state', '!=', 'Resuelto')
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            } else {
                $reportsDukke = null;
            }
            // Obtener los reports del usuario
            $reportsTask = User::select(
                'users.id as user',
                'users.name as user_name',
                'reports.*'
            )
                ->leftJoin('reports', 'users.id', '=', 'reports.delegate_id')
                ->where('reports.delegate_id', $user_id)
                ->where('reports.title', 'like', '%' . $this->searchTask . '%')
                ->where('reports.state', '!=', 'Resuelto')
                ->when($this->selectedStates, function ($query) {
                    // Filtrar por los estados seleccionados en los checkboxes
                    return $query->whereIn('reports.state', $this->selectedStates);
                })
                ->orderBy('reports.expected_date', $this->expected_dateTask)
                ->get();
            // Obtener las activities del usuario
            $activitiesTask = User::select(
                'users.id as user',
                'users.name as user_name',
                'activities.*'
            )
                ->leftJoin('activities', 'users.id', '=', 'activities.delegate_id')
                ->where('activities.delegate_id', $user_id)
                ->where('activities.title', 'like', '%' . $this->searchTask . '%')
                ->where('activities.state', '!=', 'Resuelto')
                ->when($this->selectedStates, function ($query) {
                    // Filtrar por los estados seleccionados en los checkboxes
                    return $query->whereIn('activities.state', $this->selectedStates);
                })
                ->orderBy('activities.expected_date', $this->expected_dateTask)
                ->get();
            // Combinar los resultados en una colección
            $tasks = $activitiesTask->merge($reportsTask);
            // Ordenar la colección combinada
            $tasks = $tasks->sortBy(function ($task) {
                return $task->expected_date;
            }, SORT_REGULAR, $this->expected_dateTask === 'desc');
            // Paginación manual
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = $this->perPage;
            $currentItems = $tasks->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $paginatedTask = new LengthAwarePaginator($currentItems, $tasks->count(), $perPage, $currentPage, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);
            // Obtener los reports del usuario
            $reportsCreated = User::select(
                'users.id as user',
                'users.name as user_name',
                'reports.*'
            )
                ->leftJoin('reports', 'users.id', '=', 'reports.user_id')
                ->where('reports.user_id', $user_id)
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('reports.delegate_id', $this->selectedDelegate);
                })
                ->when($this->selectedStates, function ($query) {
                    // Filtrar por los estados seleccionados en los checkboxes
                    return $query->whereIn('reports.state', $this->selectedStates);
                })
                ->where('reports.title', 'like', '%' . $this->searchCreated . '%')
                ->orderBy('reports.expected_date', $this->expected_dateCreated)
                ->get();
            // Obtener las activities del usuario
            $activitiesCreated = User::select(
                'users.id as user',
                'users.name as user_name',
                'activities.*'
            )
                ->leftJoin('activities', 'users.id', '=', 'activities.user_id')
                ->where('activities.user_id', $user_id)
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('activities.delegate_id', $this->selectedDelegate);
                })
                ->when($this->selectedStates, function ($query) {
                    // Filtrar por los estados seleccionados en los checkboxes
                    return $query->whereIn('activities.state', $this->selectedStates);
                })
                ->where('activities.title', 'like', '%' . $this->searchCreated . '%')
                ->orderBy('activities.expected_date', $this->expected_dateCreated)
                ->get();
            // Combinar los resultados manualmente
            $taskCreated = new \Illuminate\Database\Eloquent\Collection;

            foreach ($activitiesCreated as $activity) {
                $taskCreated->push($activity);
            }

            foreach ($reportsCreated as $report) {
                $taskCreated->push($report);
            }
            // Ordenar la colección combinada
            $taskCreated = $taskCreated->sortBy(function ($task) {
                return $task->expected_date;
            }, SORT_REGULAR, $this->expected_dateCreated === 'desc');
            // Paginación manual
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $perPage = $this->perPage;
            $currentItems = $taskCreated->slice(($currentPage - 1) * $perPage, $perPage)->all();
            $paginatedCreated = new LengthAwarePaginator($currentItems, $taskCreated->count(), $perPage, $currentPage, [
                'path' => LengthAwarePaginator::resolveCurrentPath(),
            ]);
        } else {
            $activities = Activity::where(function ($query) {
                $query
                    ->where('title', 'like', '%' . $this->searchActivity . '%');
            })
                ->where(function ($query) use ($user_id) {
                    $query->where('delegate_id', $user_id);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterActivity, function ($query) {
                    $query->orderByRaw($this->priorityCaseActivity . ' ' . $this->filteredPriorityActivity);
                })
                ->orderBy('expected_date', $this->expected_dateActivity)
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);

            $reports = Report::where(function ($query) {
                $query
                    ->where('title', 'like', '%' . $this->searchReport . '%');
                if (strtolower($this->searchReport) === 'reincidencia' || strtolower($this->searchReport) === 'Reincidencia') {
                    $query->orWhereNotNull('count');
                }
            })
                ->when(Auth::user()->area_id == 4, function ($query) {
                    $query->whereHas('user', function ($subQuery) {
                        $subQuery->where('type_user', '!=', 3);
                    });
                })
                ->where(function ($query) use ($user_id) {
                    $query
                        ->where('delegate_id', $user_id)
                        // O incluir registros donde user_id es igual a user_id y video es true
                        ->orWhere(function ($subQuery) use ($user_id) {
                            $subQuery->where('user_id', $user_id)->where('video', true);
                        });
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->filterReport, function ($query) {
                    $query->orderByRaw($this->priorityCaseReport . ' ' . $this->filteredPriorityReport);
                })
                ->orderBy('expected_date', $this->expected_dateReport)
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);

            if (Auth::user()->area_id == 4) {
                $reportsDukke = Report::where('project_id', 5)
                    ->whereHas('user', function ($query) {
                        $query->where('type_user', 3);
                    })
                    ->where(function ($query) {
                        $query
                            ->where('title', 'like', '%' . $this->searchDukke . '%');
                        if (strtolower($this->searchDukke) === 'reincidencia' || strtolower($this->searchDukke) === 'Reincidencia') {
                            $query->orWhereNotNull('count');
                        }
                    })
                    ->where(function ($query) use ($user_id) {
                        $query
                            ->where('delegate_id', $user_id)
                            // O incluir registros donde user_id es igual a user_id y video es true
                            ->orWhere(function ($subQuery) use ($user_id) {
                                $subQuery->where('user_id', $user_id)->where('video', true);
                            });
                    })
                    ->when($this->filterDukke, function ($query) {
                        $query->orderByRaw($this->priorityCaseDukke . ' ' . $this->filteredPriorityDukke);
                    })
                    ->orderBy('expected_date', $this->expected_dateDukke)
                    ->where('state', '!=', 'Resuelto')
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            } else {
                $reportsDukke = null;
            }
            $paginatedTask = null;
            $paginatedCreated = null;
        }
        // ADD ATRIBUTES ACTIVITIES
        foreach ($activities as $activity) {
            // SPRINT
            if ($activity->sprint) {
                $state = $activity->sprint->state;
                if ($state == 'Pendiente') {
                    $activity->sprint_status = 'Pendiente';
                } elseif ($state == 'Curso') {
                    $activity->sprint_status = 'Curso';
                } elseif ($state == 'Cerrado') {
                    $activity->sprint_status = 'Cerrado';
                }
            } else {
                $activity->sprint_status = null;
            }
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

                    // $this->user_chat = $lastMessageNoView->user_id;
                    // $this->receiver_chat = $lastMessageNoView->user_id;

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
                            $activity->user_id = false;
                        }
                    }
                }
            }
            // Calcula los mensajes no vistos para la actividad actual
            $activity->messages_count = $messages->where('look', false)->count();
        }
        // ADD ATRIBUTES REPORTS
        foreach ($reports as $report) {
            // ACTIONS
            $report->filteredActions = $this->getFilteredActions($report->state);
            // DELEGATE
            $report->usersFiltered = $this->allUsers
                ->reject(function ($user) use ($report) {
                    return $user->id === $report->delegate_id;
                })
                ->values();
            // PROGRESS
            if ($report->progress && $report->updated_at) {
                $progress = Carbon::parse($report->progress);
                $updated_at = Carbon::parse($report->updated_at);
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

                $report->timeDifference = $timeDifference;
            } else {
                $report->timeDifference = null;
            }
            // CHAT
            $messages = ChatReportsActivities::where('report_id', $report->id)->orderBy('created_at', 'asc')->get();
            $lastMessageNoView = ChatReportsActivities::where('report_id', $report->id)
                ->where('user_id', '!=', Auth::id())
                ->where('receiver_id', Auth::id())
                ->where('look', false)
                ->latest()
                ->first();
            // Verificar si la colección tiene al menos un mensaje
            if ($messages) {
                if ($lastMessageNoView) {
                    $report->user_chat = $lastMessageNoView->user_id;
                    $report->receiver_chat = $lastMessageNoView->receiver_id;

                    $receiver = User::find($lastMessageNoView->receiver_id);

                    if ($receiver->type_user == 3) {
                        $report->client = true;
                    } else {
                        $report->client = false;
                    }
                } else {
                    $lastMessage = $messages->last();
                    if ($lastMessage) {
                        if ($lastMessage->user_id == Auth::id()) {
                            $report->user_id = true;
                        } else {
                            if ($lastMessage->receiver) {
                                if ($lastMessage->receiver->type_user == 3) {
                                    $report->client = true;
                                } else {
                                    $report->client = false;
                                }
                            } else {
                                $report->client = false;
                            }
                            $report->user_id = false;
                        }
                    }
                }
            }
            $report->messages_count = $messages->where('look', false)->count();
        }
        // ADD ATRIBUTES TASK ADMIN
        if (Auth::user()->type_user == 1) {
            // ADD ATRIBUTES TASK
            foreach ($tasks as $task) {
                // ACTIONS
                $task->filteredActions = $this->getFilteredActions($task->state);
                // DELEGATE
                $task->usersFiltered = $this->allUsersTask->reject(function ($user) use ($task) {
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
                                $task->user_id = false;
                            }
                        }
                    }
                }
                $task->messages_count = $messages->where('look', false)->count();
            }
            // ADD ATRIBUTES TASK CREATED
            foreach ($taskCreated as $create) {
                // ACTIONS
                $create->filteredActions = $this->getFilteredActions($create->state);
                // DELEGATES
                $create->usersFiltered = $this->allUsersCreated->reject(function ($user) use ($create) {
                    return $user->id == $create->delegate_id;
                })->values();
                
                // DELEGADO
                $delegate_user = User::where('id', $create->delegate_id)->first();
                if ($delegate_user) {
                    $create->delegate_name = $delegate_user->name;
                    $create->delegate_id = $delegate_user->id;
                } else {
                    $create->delegate_name = 'Usuario eliminado';
                    $create->delegate_id = null;
                }
                // PROGRESS
                if ($create->progress && $create->updated_at) {
                    $create->progress = Carbon::parse($create->progress);
                    $progress = Carbon::parse($create->progress);
                    $updated_at = Carbon::parse($create->updated_at);
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

                    $create->timeDifference = $timeDifference;
                } else {
                    $create->timeDifference = null;
                }
                // NAME USUARIO CREO
                $user_created = User::where('id', $user_id)->first();
                if ($user_created) {
                    $create->created_name = $user_created->name;
                } else {
                    $create->created_name = 'Usuario eliminado';
                }
                // CHAT ACTIVITY
                if ($create->sprint_id) {
                    // MENSAJES
                    $messages = ChatReportsActivities::where('activity_id', $create->id)->orderBy('created_at', 'asc')->get();
                    $lastMessageNoView = ChatReportsActivities::where('activity_id', $create->id)
                        ->where('user_id', '!=', Auth::id())
                        ->where('receiver_id', Auth::id())
                        ->where('look', false)
                        ->latest()
                        ->first();
                    // Datos del proyecto
                    $sprint = Sprint::where('id', $create->sprint_id)->first(); // Obtener solo un modelo, no una colección
                    if ($sprint) {
                        if ($sprint->backlog) {
                            if ($sprint->backlog->project) {
                                // Acceder al proyecto asociado al backlog
                                $create->project_name = $sprint->backlog->project->name . ' (Actividad)';
                                $create->project_id = $sprint->backlog->project->id;
                                $create->project_activity = true;
                            } else {
                                // Manejar caso donde no hay backlog asociado
                                $create->project_name = 'Proyecto no disponible';
                                $create->project_activity = false;
                            }
                        } else {
                            // Manejar caso donde no hay backlog asociado
                            $create->project_name = 'Backlog no disponible';
                            $create->project_activity = false;
                        }
                    } else {
                        // Manejar caso donde no se encuentra el sprint
                        $create->project_name = 'Sprint no encontrado';
                        $create->project_activity = false;
                    }
                } else {
                    $messages = ChatReportsActivities::where('report_id', $create->id)->orderBy('created_at', 'asc')->get();
                    $lastMessageNoView = ChatReportsActivities::where('report_id', $create->id)
                        ->where('user_id', '!=', Auth::id())
                        ->where('receiver_id', Auth::id())
                        ->where('look', false)
                        ->latest()
                        ->first();
                    // Datos del proyecto
                    $project = Project::where('id', $create->project_id)->first(); // Obtener solo un modelo, no una colección
                    if ($project) {
                        // Acceder al proyecto a
                        $create->project_name = $project->name . ' (Reporte)';
                        $create->project_id = $project->id;
                        $create->project_report = true;
                    } else {
                        // Manejar caso donde no se encuentra el proyecto
                        $create->project_name = 'Proyecto no encontrado';
                        $create->project_report = false;
                    }
                }
                // Verificar si la colección tiene al menos un mensaje
                if ($messages) {
                    if ($lastMessageNoView) {
                        $create->user_chat = $lastMessageNoView->user_id;
                        $create->receiver_chat = $lastMessageNoView->receiver_id;

                        $receiver = User::find($lastMessageNoView->receiver_id);

                        if ($receiver->type_user == 3) {
                            $create->client = true;
                        } else {
                            $create->client = false;
                        }
                    } else {
                        $lastMessage = $messages->last();
                        if ($lastMessage) {
                            if ($lastMessage->user_id == Auth::id()) {
                                $create->user_id = true;
                            } else {
                                if ($lastMessage->receiver) {
                                    if ($lastMessage->receiver->type_user == 3) {
                                        $create->client = true;
                                    } else {
                                        $create->client = false;
                                    }
                                } else {
                                    $create->client = false;
                                }
                                $create->user_id = false;
                            }
                        }
                    }
                }
                $create->messages_count = $messages->where('look', false)->count();
            }
        }
        // ADD ATRIBUTES REPORTS DUKKE
        if (Auth::user()->area_id == 4) {
            // ADD ATRIBUTES REPORTS
            foreach ($reportsDukke as $report) {
                // ACTIONS
                $report->filteredActions = $this->getFilteredActions($report->state);
                // DELEGATE
                $report->usersFiltered = $this->allUsers
                    ->reject(function ($user) use ($report) {
                        return $user->id === $report->delegate_id;
                    })
                    ->values();
                // PROGRESS
                if ($report->progress && $report->updated_at) {
                    $progress = Carbon::parse($report->progress);
                    $updated_at = Carbon::parse($report->updated_at);
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

                    $report->timeDifference = $timeDifference;
                } else {
                    $report->timeDifference = null;
                }
                // CHAT
                $messages = ChatReportsActivities::where('report_id', $report->id)->orderBy('created_at', 'asc')->get();
                $lastMessageNoView = ChatReportsActivities::where('report_id', $report->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('receiver_id', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();
                // Verificar si la colección tiene al menos un mensaje
                if ($messages) {
                    if ($lastMessageNoView) {
                        $report->user_chat = $lastMessageNoView->user_id;
                        $report->receiver_chat = $lastMessageNoView->receiver_id;

                        $receiver = User::find($lastMessageNoView->receiver_id);

                        if ($receiver->type_user == 3) {
                            $report->client = true;
                        } else {
                            $report->client = false;
                        }
                    } else {
                        $lastMessage = $messages->last();
                        if ($lastMessage) {
                            if ($lastMessage->user_id == Auth::id()) {
                                $report->user_id = true;
                            } else {
                                if ($lastMessage->receiver) {
                                    if ($lastMessage->receiver->type_user == 3) {
                                        $report->client = true;
                                    } else {
                                        $report->client = false;
                                    }
                                } else {
                                    $report->client = false;
                                }
                                $report->user_id = false;
                            }
                        }
                    }
                }
                $report->messages_count = $messages->where('look', false)->count();
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

        return view('livewire.activities-reports.activities-reports', [
            'activities' => $activities,
            'reports' => $reports,
            'reportsDukke' => $reportsDukke,
            'tasks' => $paginatedTask,
            'taskCreated' => $paginatedCreated,
        ]);
    }
    // ACTIONS
    public function updateDelegateActivity($id, $delegate)
    {
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
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Actividad no encontrada',
            ]);
        }
    }

    public function updateStateActivity($id, $state)
    {
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

                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Estado actualizado',
                ]);
            }
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Actividad no encontrada',
            ]);
        }
    }

    public function updateReport($id, $project_id)
    {
        try {
            if (Auth::id() != 10 && Auth::user()->type_user != 3) {
                // Verificar si al menos uno de los campos está presente
                if ($this->changePoints == true) {
                    if (!$this->points) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'Agrega story points.',
                        ]);
                        return;
                    }
                } else {
                    if (!$this->point_know || !$this->point_many || !$this->point_effort) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'Por favor, complete el cuestionario.',
                        ]);
                        return;
                    }
                }
            }
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $report = Report::find($id);
        $project = Project::find($project_id);

        if ($report) {
            if ($this->file) {
                $extension = $this->file->extension();
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                if (in_array($extension, $extensionesImagen)) {
                    $maxSize = 5 * 1024 * 1024; // 5 MB
                    // Verificar el tamaño del archivo
                    if ($this->file->getSize() > $maxSize) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.'
                        ]);
                        return;
                    }
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Procesar la imagen
                    $image = \Intervention\Image\Facades\Image::make($this->file->getRealPath());
                    // Redimensionar la imagen si es necesario
                    $image->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // Guardar la imagen temporalmente
                    $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                    $image->save(storage_path('app/' . $tempPath));
                    // Eliminar imagen anterior
                    if (Storage::disk('reports')->exists($report->content)) {
                        Storage::disk('reports')->delete($report->content);
                    }
                    // Guardar la imagen redimensionada en el almacenamiento local
                    Storage::disk('reports')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                    // // Eliminar la imagen temporal
                    Storage::disk('local')->delete($tempPath);

                    $report->image = true;
                    $report->video = false;
                    $report->file = false;
                } elseif (in_array($extension, $extensionesVideo)) {
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Verificar y eliminar el archivo anterior si existe y coincide con la nueva ruta
                    if ($report->content && Storage::disk('reports')->exists($report->content)) {
                        $existingFilePath = pathinfo($report->content, PATHINFO_DIRNAME);
                        if ($existingFilePath == $filePath) {
                            Storage::disk('reports')->delete($report->content);
                        }
                    }
                    // Guardar el archivo en el disco 'reports'
                    $this->file->storeAs($filePath, $fileName, 'reports');

                    $report->image = false;
                    $report->video = true;
                    $report->file = false;
                } else {
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Verificar y eliminar el archivo anterior si existe y coincide con la nueva ruta
                    if ($report->content && Storage::disk('reports')->exists($report->content)) {
                        $existingFilePath = pathinfo($report->content, PATHINFO_DIRNAME);
                        if ($existingFilePath == $filePath) {
                            Storage::disk('reports')->delete($report->content);
                        }
                    }
                    // Guardar el archivo en el disco 'reports'
                    $this->file->storeAs($filePath, $fileName, 'reports');

                    $report->image = false;
                    $report->video = false;
                    $report->file = true;
                }

                $report->content = $fullNewFilePath;
            }

            $report->title = $this->tittle ?? $report->tittle;
            $report->comment = $this->comment ?? $report->comment;

            $fecha = Carbon::parse($report->expected_date)->toDateString();

            if ($report->updated_expected_date == false && $this->expected_date != $fecha) {
                $report->updated_expected_date = true;
                $report->expected_date = $this->expected_date;
            } else {
                $report->expected_date = $this->expected_date ?? $report->expected_date;
            }

            $report->evidence = $this->evidenceEdit  ?? $report->evidence;

            if ($this->priority1) {
                $report->priority = 'Alto';
            } elseif ($this->priority2) {
                $report->priority = 'Medio';
            } elseif ($this->priority3) {
                $report->priority = 'Bajo';
            }

            if ($this->changePoints == true) {
                $validPoints = [0, 1, 2, 3, 5, 8, 13];
                $report->points = $this->points;

                if (!in_array($this->points, $validPoints)) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Puntuaje no válido.',
                    ]);
                    return;
                } else {
                    $report->points = $this->points ?? $report->points;
                }
            } else {
                if (!$this->point_know || !$this->point_many || !$this->point_effort) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Por favor, complete el cuestionario.',
                    ]);
                    return;
                    $maxPoint = max($this->point_know, $this->point_many, $this->point_effort);
                    $report->points = $maxPoint ?? $report->points;
                } else {
                    $report->points = $report->points ?? 0;
                }
            }

            $report->save();
            $this->modalEditReport = false;
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Guardado exitoso',
            ]);
        }
    }

    public function updateDelegateReport($id, $delegate)
    {
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
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Reporte no encontrado',
            ]);
        }
    }

    public function updateStateReport($id, $project_id, $state)
    {
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
                    $this->modalEvidence = true;

                    $project = Project::find($project_id);
                    $report->project_id = $project->id;
                    $this->reportEvidenceReport = $report;
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

    public function updateEvidence($id, $project_id)
    {
        $report = Report::find($id);
        $project = Project::find($project_id);

        if ($report) {
            if ($this->evidence) {
                $now = Carbon::now();
                $dateString = $now->format("Y-m-d H_i_s");

                $fileExtension = $this->evidence->extension();
                $evidence = new Evidence;
                $evidence->report_id = $report->id;

                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];
                if (in_array($fileExtension, $extensionesImagen)) {
                    $maxSize = 5 * 1024 * 1024; // 5 MB
                    // Verificar el tamaño del archivo
                    if ($this->evidence->getSize() > $maxSize) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.'
                        ]);
                        return;
                    }
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $this->evidence->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Procesar la imagen
                    $image = \Intervention\Image\Facades\Image::make($this->evidence->getRealPath());
                    // Redimensionar la imagen si es necesario
                    $image->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // Guardar la imagen temporalmente
                    $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                    $image->save(storage_path('app/' . $tempPath));
                    // Guardar la imagen redimensionada en el almacenamiento local
                    Storage::disk('evidence')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                    // // Eliminar la imagen temporal
                    Storage::disk('local')->delete($tempPath);

                    $evidence->image = true;
                    $evidence->video = false;
                    $evidence->file = false;
                } elseif (in_array($fileExtension, $extensionesVideo)) {
                    $fileName = 'Evidencia ' . $project->name . ', ' . $dateString . '.' . $fileExtension;
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Verificar si la ruta existe, si no, crearla
                    if (!Storage::disk('evidence')->exists($filePath)) {
                        Storage::disk('evidence')->makeDirectory($filePath);
                    }
                    // Guardar el archivo en la ruta especificada dentro del disco 'evidence'
                    $this->evidence->storeAs($filePath, $fileName, 'evidence');

                    $evidence->image = false;
                    $evidence->video = true;
                    $evidence->file = false;
                } else {
                    $fileName = 'Evidencia ' . $project->name . ', ' . $dateString . '.' . $fileExtension;
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Verificar si la ruta existe, si no, crearla
                    if (!Storage::disk('evidence')->exists($filePath)) {
                        Storage::disk('evidence')->makeDirectory($filePath);
                    }
                    // Guardar el archivo en la ruta especificada dentro del disco 'evidence'
                    $this->evidence->storeAs($filePath, $fileName, 'evidence');

                    $evidence->image = false;
                    $evidence->video = false;
                    $evidence->file = true;
                }
                $evidence->content = $fullNewFilePath;
                $evidence->save();

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Evidencia actualizada',
                ]);
                $report->updated_expected_date = true;
                $report->end_date = Carbon::now();
                $report->state = 'Resuelto';
                $report->repeat = true;
                $report->save();
                $this->modalEvidence = false;
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Selecciona un archivo',
                ]);
            }
        }
    }
    // INFO MODAL
    public function showActivity($id)
    {
        $this->showActivity = true;

        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
            $this->modalShowReport = false;
            $this->showActivity = false;
            $this->showReport = false;
        } else {
            $this->modalShowActivity = true;
            $this->loadMessagesActivity($id);
        }
    }

    public function loadMessagesActivity($id)
    {
        $this->activityShow = Activity::find($id);
        $this->messagesActivity = ChatReportsActivities::where('activity_id', $this->activityShow->id)->orderBy('created_at', 'asc')->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReportsActivities::where('activity_id', $this->activityShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();
        if ($lastMessage) {
            if ($lastMessage->transmitter == null || $lastMessage->receiver == null) {
                $lastMessage->look = true;
                $lastMessage->save();
            } else {
                // cliente
                if ($lastMessage->transmitter->type_user != 3 && $lastMessage->receiver->type_user == Auth::user()->type_user && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
                // mismo usuario
                if ($lastMessage->transmitter->id == $lastMessage->receiver->id && Auth::user()->type_user == 1) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
                // usuario administrador
                if ($lastMessage->transmitter->type_user == 3 && $lastMessage->receiver->type_user != 3 && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                } elseif ($lastMessage->transmitter->type_user == 1 && $lastMessage->receiver->type_user != 3 && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
            }
        }

        if ($this->messagesActivity) {
            $this->showChatActivity = true;
            $this->messagesActivity->messages_count = $this->messagesActivity->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            // dd($this->messages);
            if ($this->messagesActivity->messages_count >= 2) {
                // Filtrar los mensajes que no han sido vistos
                $moreMessages = $this->messagesActivity->where('look', false);

                foreach ($moreMessages as $message) {
                    if ($message->receiver_id == Auth::id()) {
                        $message->look = true;
                        $message->save();
                    }
                }
            }
        }

        $user = Auth::user();
        if ($this->activityShow && $this->activityShow->delegate_id == $user->id && $this->activityShow->state == 'Abierto' && $this->activityShow->progress == null) {
            $this->activityShow->progress = Carbon::now();
            $this->activityShow->look = true;
            $this->activityShow->save();
        }
        // Verificar si el archivo existe en la base de datos
        if ($this->activityShow && $this->activityShow->image) {
            // Verificar si el archivo existe en la carpeta
            $filePath = public_path('activities/' . $this->activityShow->image);
            if (file_exists($filePath)) {
                $this->activityShow->imageExists = true;
            } else {
                $this->activityShow->imageExists = false;
            }
        } else {
            $this->activityShow->imageExists = false;
        }
    }

    public function showReport($id)
    {
        $this->showReport = true;

        if ($this->modalShowReport == true) {
            $this->modalShowActivity = false;
            $this->modalShowReport = false;
            $this->showActivity = false;
            $this->showReport = false;
        } else {
            $this->modalShowReport = true;
            $this->loadMessagesReport($id);
        }
    }

    public function loadMessagesReport($id)
    {
        $this->reportShow = Report::find($id);
        $this->evidenceShow = Evidence::where('report_id', $this->reportShow->id)->first();

        $this->messagesReport = ChatReportsActivities::where('report_id', $this->reportShow->id)->orderBy('created_at', 'asc')->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReportsActivities::where('report_id', $this->reportShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();

        if ($lastMessage) {
            if ($lastMessage->transmitter == null || $lastMessage->receiver == null) {
                $lastMessage->look = true;
                $lastMessage->save();
            } else {
                // cliente
                if ($lastMessage->transmitter->type_user != 3 && $lastMessage->receiver->type_user == Auth::user()->type_user && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
                // mismo usuario
                if ($lastMessage->transmitter->id == $lastMessage->receiver->id && Auth::user()->type_user == 1) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
                // usuario administrador
                if ($lastMessage->transmitter->type_user == 3 && $lastMessage->receiver->type_user != 3 && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                } elseif ($lastMessage->transmitter->type_user == 1 && $lastMessage->receiver->type_user != 3 && $lastMessage->receiver_id == Auth::id()) {
                    $lastMessage->look = true;
                    $lastMessage->save();
                }
            }
        }

        if ($this->messagesReport) {
            $this->showChatReport = true;
            $this->messagesReport->messages_count = $this->messagesReport->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            if ($this->messagesReport->messages_count >= 2) {
                // Filtrar los mensajes que no han sido vistos
                $moreMessages = $this->messagesReport->where('look', false);

                foreach ($moreMessages as $message) {
                    if ($message->receiver_id == Auth::id()) {
                        $message->look = true;
                        $message->save();
                    }
                }
            }
        }

        if ($this->evidenceShow) {
            $this->showEvidence = true;
        }

        $user = Auth::user();
        // REPORTE EN VISTO
        if ($this->reportShow && $this->reportShow->delegate_id == $user->id && $this->reportShow->state == 'Abierto' && $this->reportShow->progress == null) {
            $this->reportShow->progress = Carbon::now();
            $this->reportShow->look = true;
            $this->reportShow->save();
        }
        // Verificar si el archivo existe en la base de datos
        if ($this->reportShow && $this->reportShow->content) {
            // Verificar si el archivo existe en la carpeta
            $filePath = public_path('reportes/' . $this->reportShow->content);
            $fileExtension = pathinfo($this->reportShow->content, PATHINFO_EXTENSION);
            if (file_exists($filePath)) {
                $this->reportShow->contentExists = true;
                $this->reportShow->fileExtension = $fileExtension;
            } else {
                $this->reportShow->contentExists = false;
            }
        } else {
            $this->reportShow->contentExists = false;
        }
        // USUARIO CREADOR ELIMINADO
        $create_user = User::find($this->reportShow->user_id);
        if ($create_user) {
            $this->reportShow->create_user = true;
        } else {
            $this->reportShow->create_user = false;
        }
    }

    public function showEditReport($id)
    {
        $this->showEditReport = true;

        if ($this->modalEditReport == true) {
            $this->modalEditReport = false;
        } else {
            $this->modalEditReport = true;
        }

        $this->reportEdit = Report::find($id);
        $this->tittle = $this->reportEdit->title;
        $this->comment = $this->reportEdit->comment;

        $fecha = Carbon::parse($this->reportEdit->expected_date);
        $this->expected_date = $fecha->toDateString();

        $this->evidenceEdit = false;
        if ($this->reportEdit->evidence == true) {
            $this->evidenceEdit = true;
        }

        $this->priority1 = false;
        $this->priority2 = false;
        $this->priority3 = false;

        if ($this->reportEdit->priority == 'Alto') {
            $this->priority1 = true;
        } elseif ($this->reportEdit->priority == 'Medio') {
            $this->priority2 = true;
        } elseif ($this->reportEdit->priority == 'Bajo') {
            $this->priority3 = true;
        }

        $this->points = $this->reportEdit->points;
        $this->changePoints = true;
    }
    // MODAL
    public function modalShowActivity()
    {
        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
            $this->modalShowReport = false;
            $this->showActivity = false;
            $this->showReport = false;
        } else {
            $this->modalShowActivity = true;
        }
        $this->messageActivity = '';
        $this->messageReport = '';
    }

    public function modalShowReport()
    {
        if ($this->modalShowReport == true) {
            $this->modalShowActivity = false;
            $this->modalShowReport = false;
            $this->showActivity = false;
            $this->showReport = false;
        } else {
            $this->modalShowReport = true;
        }
    }

    public function modalEditReport()
    {
        $this->showEditReport = false;

        if ($this->modalEditReport == true) {
            $this->modalEditReport = false;
        } else {
            $this->modalEditReport = true;
        }
    }

    public function modalEvidence()
    {
        if ($this->modalEvidence == true) {
            $this->modalEvidence = false;
        } else {
            $this->modalEvidence = true;
        }
    }
    // FILTER
    public function filterDown($type)
    {
        if ($type == 'expected_dateTask') {
            $this->filteredTask = false;
            $this->expected_dateTask = 'asc';
        }

        if ($type == 'expected_dateCreated') {
            $this->filteredCreated = false;
            $this->expected_dateCreated = 'asc';
        }

        if ($type == 'activity') {
            $this->filterActivity = true;
            $this->filteredActivity = false;
            $this->filteredPriorityActivity = 'asc';
            $this->priorityCaseActivity = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
        if ($type == 'expected_dateActivity') {
            $this->filterActivity = false;
            $this->filteredActivity = false;
            $this->expected_dateActivity = 'asc';
        }

        if ($type == 'report') {
            $this->filterReport = true;
            $this->filteredReport = false;
            $this->filteredPriorityReport = 'asc';
            $this->priorityCaseReport = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
        if ($type == 'expected_dateReport') {
            $this->filterReport = false;
            $this->filteredReport = false;
            $this->expected_dateReport = 'asc';
        }

        if ($type == 'reportDukke') {
            $this->filterDukke = true;
            $this->filteredDukke = false;
            $this->filteredPriorityDukke = 'asc';
            $this->priorityCaseDukke = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
        if ($type == 'expected_dateDukke') {
            $this->filterDukke = false;
            $this->filteredDukke = false;
            $this->expected_dateDukke = 'asc';
        }
    }

    public function filterUp($type)
    {
        if ($type == 'expected_dateTask') {
            $this->filteredTask = true;
            $this->expected_dateTask = 'desc';
        }

        if ($type == 'expected_dateCreated') {
            $this->filteredCreated = true;
            $this->expected_dateCreated = 'desc';
        }

        if ($type == 'activity') {
            $this->filterActivity = true;
            $this->filteredActivity = true;
            $this->filteredPriorityActivity = 'asc';
            $this->priorityCaseActivity = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        if ($type == 'expected_dateActivity') {
            $this->filterActivity = false;
            $this->filteredActivity = true;
            $this->expected_dateActivity = 'desc';
        }

        if ($type == 'report') {
            $this->filterReport = true;
            $this->filteredReport = true;
            $this->filteredPriorityReport = 'asc';
            $this->priorityCaseReport = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        if ($type == 'expected_dateReport') {
            $this->filterReport = false;
            $this->filteredReport = true;
            $this->expected_dateReport = 'desc';
        }

        if ($type == 'reportDukke') {
            $this->filterDukke = true;
            $this->filteredDukke = true;
            $this->filteredPriorityDukke = 'asc';
            $this->priorityCaseDukke = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        if ($type == 'expected_dateDukke') {
            $this->filterDukke = false;
            $this->filteredDukke = true;
            $this->expected_dateDukke = 'desc';
        }
    }
    // EXTRAS
    public function updateChatActivity($id)
    {
        $activity = Activity::find($id);
        $user = Auth::user();

        if ($activity) {
            if ($this->messageActivity != '') {
                $lastMessage = ChatReportsActivities::where('activity_id', $activity->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();

                $chat = new ChatReportsActivities();
                if ($user->type_user == 1) {
                    $chat->user_id = $user->id; // envia
                    if ($activity->user->id == Auth::id()) {
                        $chat->receiver_id = $activity->delegate->id; //recibe
                    } else {
                        if ($user->type_user == 1) {
                            if ($activity->user->type_user == 3) {
                                $chat->receiver_id = $activity->user->id; //recibe
                            } else {
                                $chat->receiver_id = $activity->delegate->id; //recibe
                            }
                        } else {
                            $chat->receiver_id = $activity->user->id; //recibe
                        }
                    }
                } elseif ($user->type_user == 2) {
                    $chat->user_id = $user->id; // envia
                    if ($activity->user->type_user == 3) {
                        $chat->receiver_id = $activity->user->id; //recibe
                    } else {
                        $chat->receiver_id = $activity->delegate->id; //recibe
                    }
                } elseif ($user->type_user == 3) {
                    $chat->user_id = $user->id; // envia
                    $chat->receiver_id = $activity->delegate->id; //recibe
                }

                $chat->activity_id = $activity->id;
                $chat->message = $this->messageActivity;
                $chat->look = false;
                $chat->save();

                if ($lastMessage) {
                    // administrador
                    if ($lastMessage->transmitter->type_user == 3 && Auth::user()->type_user == 1) {
                        $lastMessage->look = true;
                        $lastMessage->save();
                    }
                }
                // Emitir un evento después de enviar el mensaje
                $this->emit('messageSentActivity', $activity->id);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Mensaje enviado',
                ]);

                $this->messageActivity = '';
            } else {
                $this->modalShowActivity = false;
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El mensaje está vacío.',
                ]);
            }
        }
    }

    public function updateChatReport($id)
    {
        $report = Report::find($id);
        $user = Auth::user();

        if ($report) {
            if ($this->messageReport != '') {
                $lastMessage = ChatReportsActivities::where('report_id', $report->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();

                $chat = new ChatReportsActivities();
                if ($user->type_user == 1) {
                    $chat->user_id = $user->id; // envia
                    if ($report->user->id == Auth::id()) {
                        $chat->receiver_id = $report->delegate->id; //recibe
                    } else {
                        if ($user->type_user == 1) {
                            if ($report->user->type_user == 3) {
                                $chat->receiver_id = $report->user->id; //recibe
                            } else {
                                $chat->receiver_id = $report->delegate->id; //recibe
                            }
                        } else {
                            $chat->receiver_id = $report->user->id; //recibe
                        }
                    }
                } elseif ($user->type_user == 2) {
                    $chat->user_id = $user->id; // envia
                    if ($report->user->type_user == 3) {
                        $chat->receiver_id = $report->user->id; //recibe
                    } else {
                        $chat->receiver_id = $report->delegate->id; //recibe
                    }
                } elseif ($user->type_user == 3) {
                    $chat->user_id = $user->id; // envia
                    $chat->receiver_id = $report->delegate->id; //recibe
                }

                $chat->report_id = $report->id;
                $chat->message = $this->messageReport;
                $chat->look = false;
                $chat->save();

                if ($lastMessage) {
                    // administrador
                    if ($lastMessage->transmitter->type_user == 3 && Auth::user()->type_user == 1) {
                        $lastMessage->look = true;
                        $lastMessage->save();
                    }
                }
                // Emitir un evento después de enviar el mensaje
                $this->emit('messageSentReport', $report->id);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Mensaje enviado',
                ]);

                $this->messageReport = '';
            } else {
                $this->modalShowReport = false;
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El mensaje está vacío.',
                ]);
            }
        }
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->resetComponentState();
    }

    public function changePoints()
    {
        if ($this->changePoints == true) {
            $this->changePoints = false;
            if ($this->reportEdit == null) {
                $this->points = '';
            }
        } else {
            $this->changePoints = true;
            $this->point_know = '';
            $this->point_many = '';
            $this->point_effort = '';
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
    // PRIVATE
    private function resetComponentState()
    {
        $this->searchActivity = '';
        $this->searchReport = '';
        $this->searchDukke = '';
        $this->modalShowActivity = false;
        $this->modalShowReport = false;
    }
}
