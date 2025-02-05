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
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class MyActivities extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    // FILTROS
    public $search, $allUsers, $allProjects;
    public $selectedDelegate = '';
    public $allUsersFiltered = [], $allProjectsFiltered = [], $selectedStates = [], $selectedProjects = [];
    public $filtered = false; // cambio de dirección de flechas
    public $isOptionsVisibleState = false, $isOptionsVisibleProject = false; // Controla la visibilidad del panel de opciones
    public $visiblePanels = []; // Asociativa para controlar los paneles de opciones por ID de reporte
    public $perPage = '20';
    // variables para la consulta
    public $filterPriotiry = false, $filterState = false, $filterExpected = true;
    public $filteredPriority = '', $filteredState = '', $priorityCase = '', $filteredExpected = 'asc';
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
                $query->whereIn('reports.state', $this->selectedStates);
            }, function ($query) {
                // Excluir "Resuelto" si no se seleccionan estados
                $query->where('reports.state', '!=', 'Resuelto');
            })
            ->when(!empty($this->selectedProjects), function ($query) {
                // Filtrar por los estados seleccionados en los checkboxes
                $query->whereIn('reports.project_id', $this->selectedProjects);
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
                $query->whereIn('activities.state', $this->selectedStates);
            }, function ($query) {
                // Excluir "Resuelto" si no se seleccionan estados
                $query->where('activities.state', '!=', 'Resuelto');
            })
            ->when(!empty($this->selectedProjects), function ($query) {
                $query->whereIn('backlogs.project_id', $this->selectedProjects); // Filtrar por proyectos seleccionados
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
            // FECHA DE ENTREGA
            $currentDate = Carbon::parse($task->expected_date);
            $this->expected_day[$task->id] = $currentDate->day;
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
                            $task->project_name = $sprint->backlog->project->name . ' (Actividad)';
                            $task->project_id = $sprint->backlog->project->id;
                            $task->sprint_state = $sprint->state;
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
        return view('livewire.activities-reports.my-activities', [
            'tasks' => $paginatedTask,
        ]);
    }
    // ACTIONS
    public function updateDelegate($id, $delegate, $type)
    {
        // Emitir evento de carga iniciada
        $this->emit('loadingStarted');

        if ($type == 'report') {
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
        // Emitir evento de carga terminada
        $this->emit('loadingEnded');
    }

    public function updateState($id, $project_id, $state)
    {
        // Emitir evento de carga iniciada
        $this->emit('loadingStarted');

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
                    // Actualizacion de grafica
                    $this->effortPoints();
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
                        $report->state = $state;
                        $report->updated_expected_date = true;
                        $report->end_date = Carbon::now();
                        $report->repeat = true;
                        $report->save();
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
        // Emitir evento de carga terminada
        $this->emit('loadingEnded');
    }

    public function updateExpectedDay($id, $type, $day)
    {
        // Emitir evento de carga iniciada
        $this->emit('loadingStarted');

        if ($type == 'report') {
            $task = Report::find($id);
        } else {
            $task = Activity::find($id);
        }

        if ($task) {
            $currentDate = Carbon::parse($task->expected_date);
            $month = $currentDate->month;
            $year = $currentDate->year;
            $oldDay = $currentDate->day;
            // Ajustar el día si no es válido para el mes/año actual
            $lastDayOfMonth = Carbon::create($year, $month)->endOfMonth()->day;

            if ($lastDayOfMonth < $day) {
                $this->expected_day[$task->id] = $oldDay;
                // Emitir evento de carga terminada
                $this->emit('loadingEnded');
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'No se puede seleccionar un día fuera del mes y año de la fecha esperada'
                ]);
                return;
            }

            $newDate = $currentDate->setDay($day);
            $task->expected_date = $newDate;
            $task->save();
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Día actualizado correctamente',
            ]);
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Tarea no encontrada',
            ]);
        }
        // Emitir evento de carga terminada
        $this->emit('loadingEnded');
    }

    public function updateExpectedMonth($id, $type, $month)
    {
        // Emitir evento de carga iniciada
        $this->emit('loadingStarted');

        if ($type == 'report') {
            $task = Report::find($id);
        } else {
            $task = Activity::find($id);
        }

        if ($task) {
            $currentDate = Carbon::parse($task->expected_date);
            $year = $currentDate->year;
            $day = $currentDate->day;
            // Ajustar el día si no es válido para el mes/año actual
            $lastDayOfMonth = Carbon::create($year, $month)->endOfMonth()->day;
            if ($lastDayOfMonth < $day) {
                $day = $lastDayOfMonth; // Ajustar el día al último día del mes
                $this->expected_day[$task->id] = $day;

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'warning',
                    'title' => 'Día ajustada al mes seleccionado'
                ]);
            }
            // Crear la nueva fecha usando día, mes y año
            $newDate = Carbon::create($year, $month, $day);
            // Actualizar el registro
            $task->expected_date = $newDate;
            $task->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Mes actualizado correctamente',
            ]);
        } else {
            // Emitir evento de carga terminada
            $this->emit('loadingEnded');

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Tarea no encontrada',
            ]);
        }
        // Emitir evento de carga terminada
        $this->emit('loadingEnded');
    }

    public function updateExpectedYear($id, $type, $year)
    {
        // Emitir evento de carga iniciada
        $this->emit('loadingStarted');

        if ($type == 'report') {
            $task = Report::find($id);
        } else {
            $task = Activity::find($id);
        }

        if ($task) {
            $currentDate = Carbon::parse($task->expected_date);
            $month = $currentDate->month;
            $day = $currentDate->day;
            // Ajustar el día si no es válido para el mes/año actual
            $lastDayOfMonth = Carbon::create($year, $month)->endOfMonth()->day;
            if ($lastDayOfMonth < $day) {
                $day = $lastDayOfMonth; // Ajustar el día al último día del mes
                $this->expected_day[$task->id] = $day;

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'warning',
                    'title' => 'Día ajustada al mes seleccionado'
                ]);
            }
            // Crear la nueva fecha usando día, mes y año
            $newDate = Carbon::create($year, $month, $day);
            // Actualizar el registro
            $task->expected_date = $newDate;
            $task->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Año actualizado correctamente',
            ]);
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Tarea no encontrada',
            ]);
        }
        // Emitir evento de carga terminada
        $this->emit('loadingEnded');
    }

    public function finishEvidence($project_id, $report_id)
    {
        $this->redirectRoute('projects.reports.index', ['project' => $project_id, 'reports' => $report_id, 'highlight' => $report_id]);
    }
    // MODAL
    public function show($id, $type)
    {
        // Emitir evento de carga iniciada
        $this->emit('loadingStarted');

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
        // Emitir evento de carga terminada
        $this->emit('loadingEnded');
    }
    // FILTER
    public function filterDown($type)
    {
        $this->emit('loadingStarted');
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

        $this->emit('loadingEnded');
    }

    public function filterUp($type)
    {
        $this->emit('loadingStarted');
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

        $this->emit('loadingEnded');
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
}
