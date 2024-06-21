<?php

namespace App\Http\Livewire\ActivitiesReports;

use App\Models\Activity;
use App\Models\ChatReports;
use App\Models\Evidence;
use App\Models\Project;
use App\Models\Report;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
    public $allUsersFiltered = [];
    public $selectedDelegate = '';
    // FILTRO PUNTOS
    public $startDate, $endDate, $starMonth, $endMonth;
    // ------------------------------ ACTIVITY ------------------------------
    // modal activity
    public $modalShowActivity = false;
    public $showActivity = false,
        $showChatActivity = false;
    public $activityShow, $messagesActivity, $messageActivity;
    // table, action's activities
    public $searchActivity;
    public $filteredActivity = false, $filterActivity = false;
    public $filteredPriorityActivity = '', $priorityCaseActivity = '';
    // ------------------------------ REPORT ------------------------------
    // modal show report
    public $modalShowReport = false;
    public $showReport = false,
        $showChatReport = false,
        $showEvidence = false;
    public $reportShow;
    // modal evidence report
    public $modalEvidence = false;
    public $evidenceShow;
    public $messagesReport, $messageReport;
    // table, action's reports
    public $searchReport;
    public $searchDukke;
    public $filteredReport = false, $filterReport = false;
    public $filteredDukke = false, $filterDukke = false;
    public $filteredPriorityReport = '', $priorityCaseReport = '', $filteredPriorityDukke = '', $priorityCaseDukke = '';
    // ------------------------------ TASK ADMIN ------------------------------
    public $searchTask;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');
        $this->allUsers = User::all();
        // Filtro de consulta
        $user = Auth::user();
        $user_id = $user->id;
        // PUNTOS
        $this->startDate = Carbon::now()->startOfWeek()->format('Y-m-d'); // Lunes de esta semana
        $this->endDate = Carbon::now()->endOfWeek()->format('Y-m-d'); // Domingo de esta semana
        $this->starMonth = Carbon::now()->startOfMonth()->format('Y-m-d'); // Primer día del mes actual
        $this->endMonth = Carbon::now()->endOfMonth()->format('Y-m-d'); // Último día del mes actual
        // Subconsulta de Reports por mes incluyendo puntos resueltos y los demás estados
        $reportsMonthly = Report::select(
                'delegate_id',
                DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto') THEN points ELSE 0 END) as total_points_reports"),
                DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_reports")
            )
            ->where('delegate_id', $user_id)
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('expected_date', [$this->starMonth, $this->endMonth]);
            })
            ->groupBy('delegate_id');
        // Subconsulta de Activities por mes incluyendo puntos resueltos y los demás estados
        $activitiesMonthly = Activity::select(
                'delegate_id',
                DB::raw("SUM(CASE WHEN state IN ('Abierto', 'Proceso', 'Conflicto') THEN points ELSE 0 END) as total_points_activities"),
                DB::raw("SUM(CASE WHEN state = 'Resuelto' THEN points ELSE 0 END) as total_resuelto_activities")
            )
            ->where('delegate_id', $user_id)
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('expected_date', [$this->starMonth, $this->endMonth]);
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
            ->where('delegate_id', $user_id)
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('expected_date', [$this->starMonth, $this->endMonth]);
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
            ->where('delegate_id', $user_id)
            ->where(function ($query) {
                $query->whereBetween('updated_at', [$this->starMonth, $this->endMonth])
                    ->orWhereBetween('expected_date', [$this->starMonth, $this->endMonth]);
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
            ->where('users.id', $user_id)
            ->get();

        foreach ($points as $key => $point) {
            // Puntos por terminar
            $points_finish = $point->total_points_reports + $point->total_points_activities;
            // Puntos por asignar
            $point->points_assigned = $point->effort_points - $points_finish;
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
            ]
        ];
        // Preparar los datos para el gráfico
        $categories = $points->map(function ($point) {
            return $point->name_with_advance;
        })->toArray();

        $totalEffortPoints = $points->map(function ($point) {
            return $point->total_effort_points;
        })->toArray();
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
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();

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
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();
            $reportsDukke = null;
            // Obtener los reports del usuario
            $reportsAdmin = User::select(
                    'users.id as user',
                    'users.name as user_name',
                    'reports.*'
                )
                ->leftJoin('reports', 'users.id', '=', 'reports.delegate_id')
                ->where('reports.delegate_id', $user_id)
                ->where('reports.title', 'like', '%' . $this->searchTask . '%')
                ->where('reports.state', '!=', 'Resuelto')
                ->orderBy('reports.created_at', 'desc')
                ->get();
            // Obtener las activities del usuario
            $activitiesAdmin = User::select(
                    'users.id as user',
                    'users.name as user_name',
                    'activities.*'
                )
                ->leftJoin('activities', 'users.id', '=', 'activities.delegate_id')
                ->where('activities.delegate_id', $user_id)
                ->where('activities.title', 'like', '%' . $this->searchTask . '%')
                ->where('activities.state', '!=', 'Resuelto')
                ->orderBy('activities.created_at', 'desc')
                ->get();
            // Combinar los resultados en una colección
            $tasks = $activitiesAdmin->merge($reportsAdmin);
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
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();

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
                ->orderBy('created_at', 'desc')
                ->where('state', '!=', 'Resuelto')
                ->with(['user', 'delegate'])
                ->get();

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
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when($this->filterDukke, function ($query) {
                        $query->orderByRaw($this->priorityCaseDukke . ' ' . $this->filteredPriorityDukke);
                    })
                    ->orderBy('created_at', 'desc')
                    ->where('state', '!=', 'Resuelto')
                    ->with(['user', 'delegate'])
                    ->get();
            } else {
                $reportsDukke = null;
            }
            $tasks = null;
        }
        // ADD ATRIBUTES ACTIVITIES
        foreach ($activities as $activity) {
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
            $messages = ChatReports::where('activity_id', $activity->id)->orderBy('created_at', 'asc')->get();
            $lastMessageNoView = ChatReports::where('activity_id', $activity->id)
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
                            if ($lastMessage->receiver->type_user == 3) {
                                $activity->client = true;
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
        // ADD ATRIBUTES REPORTS
        foreach ($reports as $report) {
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
            $messages = ChatReports::where('report_id', $report->id)->orderBy('created_at', 'asc')->get();
            $lastMessageNoView = ChatReports::where('report_id', $report->id)
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
                            if ($lastMessage->receiver->type_user == 3) {
                                $report->client = true;
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
            // ADD ATRIBUTES REPORTS
            foreach ($tasks as $task) {
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
                // NAME USUARIO CREADOR
                $user_created = User::where('id', $task->user_id)->first();
                $task->user_created = $user_created->name;
                // CHAT ACTIVITY
                if ($task->sprint_id) {
                    $messages = ChatReports::where('activity_id', $task->id)->orderBy('created_at', 'asc')->get();
                    $lastMessageNoView = ChatReports::where('activity_id', $task->id)
                        ->where('user_id', '!=', Auth::id())
                        ->where('receiver_id', Auth::id())
                        ->where('look', false)
                        ->latest()
                        ->first();
                    // Datos del proyecto
                    $sprint = Sprint::where('id', $task->sprint_id)->first(); // Obtener solo un modelo, no una colección
                    if ($sprint) {
                        if ($sprint->backlog) {
                            // Acceder al proyecto asociado al backlog
                            $task->project_name = $sprint->backlog->project->name . ' (Actividad)';
                            $task->project_id = $sprint->backlog->project->id;
                            $task->project_activity = true;
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
                    $messages = ChatReports::where('report_id', $task->id)->orderBy('created_at', 'asc')->get();
                    $lastMessageNoView = ChatReports::where('report_id', $task->id)
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
                                if ($lastMessage->receiver->type_user == 3) {
                                    $task->client = true;
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
        }
        // ADD ATRIBUTES REPORTS DUKKE
        if (Auth::user()->area_id == 4) {
            // ADD ATRIBUTES REPORTS
            foreach ($reportsDukke as $report) {
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
                $messages = ChatReports::where('report_id', $report->id)->orderBy('created_at', 'asc')->get();
                $lastMessageNoView = ChatReports::where('report_id', $report->id)
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
                                if ($lastMessage->receiver->type_user == 3) {
                                    $report->client = true;
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
        foreach ($this->allUsers as $key => $user) {
            $this->allUsersFiltered[$user->id] = $user->name;
        }
        return view('livewire.activities-reports.activities-reports', [
            'activities' => $activities,
            'reports' => $reports,
            'reportsDukke' => $reportsDukke,
            'tasks' => $tasks,
            'categories' => $categories,
            'series' => $series,
            'totalEffortPoints' => $totalEffortPoints,
        ]);
    }
    // INFO MODAL
    public function showActivity($id)
    {
        $this->showActivity = true;

        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
        } else {
            $this->modalShowActivity = true;
            $this->loadMessagesActivity($id);
        }
    }

    public function loadMessagesActivity($id)
    {
        $this->activityShow = Activity::find($id);
        $this->messagesActivity = ChatReports::where('activity_id', $this->activityShow->id)->orderBy('created_at', 'asc')->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('activity_id', $this->activityShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();
        if ($lastMessage) {
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
            $this->modalShowReport = false;
        } else {
            $this->modalShowReport = true;
            $this->loadMessagesReport($id);
        }
    }

    public function loadMessagesReport($id)
    {
        $this->reportShow = Report::find($id);
        $this->evidenceShow = Evidence::where('report_id', $this->reportShow->id)->first();


        $this->messagesReport = ChatReports::where('report_id', $this->reportShow->id)->orderBy('created_at', 'asc')->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('report_id', $this->reportShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();
        if ($lastMessage) {
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

        if ($this->messagesReport) {
            $this->showChatReport = true;
            $this->messagesReport->messages_count = $this->messagesReport->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            // dd($this->messages);
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
    }
    // MODAL
    public function modalShowActivity()
    {
        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
        } else {
            $this->modalShowActivity = true;
        }
    }

    public function modalShowReport()
    {
        if ($this->modalShowReport == true) {
            $this->modalShowReport = false;
        } else {
            $this->modalShowReport = true;
        }
    }
    // FILTER
    public function filterDown($type)
    {
        if ($type == 'activity') {
            $this->filterActivity = true;
            $this->filteredActivity = false;
            $this->filteredPriorityActivity = 'asc';
            $this->priorityCaseActivity = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
        if ($type == 'report') {
            $this->filterReport = true;
            $this->filteredReport = false;
            $this->filteredPriorityReport = 'asc';
            $this->priorityCaseReport = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
        if ($type == 'reportDukke') {
            $this->filterDukke = true;
            $this->filteredDukke = false;
            $this->filteredPriorityDukke = 'asc';
            $this->priorityCaseDukke = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }
    }

    public function filterUp($type)
    {
        if ($type == 'activity') {
            $this->filterActivity = true;
            $this->filteredActivity = true;
            $this->filteredPriorityActivity = 'asc';
            $this->priorityCaseActivity = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        if ($type == 'report') {
            $this->filterReport = true;
            $this->filteredReport = true;
            $this->filteredPriorityReport = 'asc';
            $this->priorityCaseReport = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
        if ($type == 'reportDukke') {
            $this->filterDukke = true;
            $this->filteredDukke = true;
            $this->filteredPriorityDukke = 'asc';
            $this->priorityCaseDukke = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }
    }
    // EXTRAS
    public function updateChatActivity($id)
    {
        $activity = Activity::find($id);
        $user = Auth::user();

        if ($activity) {
            if ($this->messageActivity != '') {
                $lastMessage = ChatReports::where('activity_id', $activity->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();

                $chat = new ChatReports();
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
                $lastMessage = ChatReports::where('report_id', $report->id)
                    ->where('user_id', '!=', Auth::id())
                    ->where('look', false)
                    ->latest()
                    ->first();

                $chat = new ChatReports();
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
        $this->searchActivity = '';
        $this->searchReport = '';
        $this->searchDukke = '';
        $this->modalShowActivity = false;
        $this->modalShowReport = false;
        $this->activeTab = $tab;
    }
}
