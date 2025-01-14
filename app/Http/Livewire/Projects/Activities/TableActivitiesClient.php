<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Models\Activity;
use App\Models\Backlog;
use App\Models\ChatReportsActivities;
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

class TableActivitiesClient extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'sprintUpdated', 'delete'];
    // ENVIADAS
    public $idsprint, $project, $percentagetesolved;
    // BACKLOG
    public $backlog;
    // SPRINT
    public $sprint, $percentageResolved;
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

    // Resetear paginación cuando se actualiza el campo de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Verifica si se ha seleccionado un proyecto y un sprint
        if ($this->project != null && $this->idsprint != null) {
            // Backlog
            $this->backlog = Backlog::where('project_id', $this->project->id)->first();
            // Sprint
            $this->sprint = Sprint::find($this->idsprint);
        }
        // Filtro de consulta
        $user = Auth::user();
        $user_id = $user->id;
        // DELEGATE
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
        // ACTIVITIES
            if ($this->project == null && $this->idsprint == null) {
                $activities = Activity::where(function ($query) {
                    $query
                        ->where('title', 'like', '%' . $this->search . '%');
                        // Si no se seleccionan estados, excluir "Resuelto"
                        if (empty($this->selectedStates)) {
                            $query->where('activities.state', '!=', 'Resuelto');
                        } else {
                            // Incluir todos los estados seleccionados
                            $query->whereIn('state', $this->selectedStates);
                        }
                    })
                    ->when($this->filterPriotiry, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                    })
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when($this->filterState, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                    })
                    ->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                    ->select('activities.*', 'delegates.name')
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
                        $query->whereIn('state', $this->selectedStates);
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
            // ACTIONS
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

        return view('livewire.projects.activities.table-activities-client', [
            'activities' => $activities,
        ]);
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
    public function filterDown($type)
    {
        $this->filtered = false; // Cambio de flechas
        // Oculta todos los paneles
        $this->isOptionsVisible = false;
        
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
    }

    public function filterUp($type)
    {
        $this->filtered = true; // Cambio de flechas
        // Oculta todos los paneles
        $this->isOptionsVisible = false;
        
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
    }
}

