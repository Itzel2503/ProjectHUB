<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Models\Activity;
use App\Models\Backlog;
use App\Models\ChatReportsActivities;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TableActivities extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'sprintUpdated', 'delete'];
    // ENVIADAS
    public $idsprint, $project;
    // BACKLOG
    public $backlog;
    // SPRINT
    public $sprint;
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

    // Resetear paginación cuando se actualiza el campo de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
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
        if (Auth::user()->type_user == 1) {
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
        } else {
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
                    ->where(function ($query) use ($user_id) {
                        $query->where('delegate_id', $user_id);
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

        return view('livewire.projects.activities.table-activities', [
            'activities' => $activities,
        ]);
    }
    // ACTIONS
    public function delete($id)
    {
        $activity = Activity::find($id);

        if ($activity) {
            if ($activity->image) {
                $contentPath = 'activities/' . $activity->image;
                $fullPath = public_path($contentPath);

                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }
            $activity->delete();

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
    }

    public function updateDelegate($id, $delegate)
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
        }
    }

    public function updateState($id, $state)
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
                // Emitir un evento para notificar al componente padre
                $this->emitUp('activityUpdated');
                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Estado actualizado',
                ]);
            }
        }
    }
    // MODAL
    public function create($idProject)
    {
        if ($idProject != 0) {
            $this->createEdit = !$this->createEdit;
        }
    }

    public function showActivity($id)
    {
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
}
