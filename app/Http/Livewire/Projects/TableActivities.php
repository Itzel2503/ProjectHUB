<?php

namespace App\Http\Livewire\Projects;

use App\Models\Activity;
use App\Models\ChatReports;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TableActivities extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'messageSent' => 'loadMessages', 'sprintsUpdated', 'destroyActivity', 'destroySprint'];
    // GENERALES
    public $project, $backlog, $allUsers, $firstSprint;
    public $sprints = [];
    // Sprint
    public $selectSprint, $sprintState, $startDate, $endDate, $idSprint, $percentageResolved;
    public $filteredState = [];
    public $changeSprint = false;
    // modal backlog
    public $modalBacklog = false;
    // modal sprint
    public $modalCreateSprint = false;
    public $showUpdateSprint = false;
    public $sprintEdit;
    public $number, $name_sprint, $start_date, $end_date;
    // modal activity
    public $modalCreateActivity = false,
        $modalShowActivity = false;
    public $showUpdateActivity = false,
        $showActivity = false,
        $showChat = false;
    public $activityShow, $messages, $activityEdit, $moveActivity;
    public $title, $file, $description, $delegate, $expected_date, $priority1, $priority2, $priority3, $message;
    // modal activity points
    public $changePoints = false;
    public $points, $point_know, $point_many, $point_effort;
    // table, action's activities
    public $search;
    public $perPage = '';
    public $selectedDelegate = '', $filteredPriority = '', $filteredStateArrow = '', $priorityCase = '', $filteredExpected = 'desc', $orderByType = 'expected_date';
    public $usersFiltered = [],
        $allUsersFiltered = [],
        $selectedStates = [],
        $statesFiltered = [];
    public $filtered = false, $filter = false, $filterPriotiry = false, $filterState = false;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        // Obtener los sprints y ordenarlos por el número de sprint
        $backlog = $this->backlog;
        $this->sprints = $backlog->sprints->sortBy('number');

        if ($this->sprints->isNotEmpty()) {
            // PRIMERO
            if (!$this->changeSprint) {
                // Buscar el primer sprint con estado 'Curso'
                $courseSprint = $this->sprints->firstWhere('state', 'Curso');
                // Si no se encontró ningún sprint con estado 'Curso', buscar el primer sprint con estado 'Curso'
                if (!$courseSprint) {
                    $pendingSprint = $this->sprints->firstWhere('state', 'Pendiente');

                    // Si se encontró un sprint con estado 'Pendiente', asignarlo a $this->firstSprint
                    if ($pendingSprint) {
                        $this->firstSprint = $pendingSprint;
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
            // SELECT
            $this->updateSprintData($this->selectSprint);
            $this->firstSprint = Sprint::find($this->selectSprint);

            if ($this->firstSprint) {
                $sprintDesc = $this->sprints;
                // Encuentra el sprint anterior en la secuencia
                $previousSprint = $sprintDesc->sortByDesc('number')->first(function ($sprint) {
                    return $sprint->number < $this->firstSprint->number;
                });

                if ($previousSprint) {
                    if ($previousSprint->state == 'Curso') {
                        $this->filteredState = [];
                    } else {
                        if ($this->firstSprint->state == 'Curso') {
                            $this->filteredState = [];
                        } elseif ($this->firstSprint->state == 'Pendiente') {
                            $this->filteredState = ['Curso'];
                        } else {
                            $this->filteredState = [];
                        }
                    }
                } else {
                    if ($this->firstSprint->state == 'Curso') {
                        $this->filteredState = [];
                    } elseif ($this->firstSprint->state == 'Pendiente') {
                        $this->filteredState = ['Curso'];
                    } else {
                        $this->filteredState = [];
                    }
                }
            }
        } else {
            $this->selectSprint = null;
        }
        // Filtro de consulta
        $user = Auth::user();
        $user_id = $user->id;
        // DELEGATE
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
        // ACTIVITIES
        if (Auth::user()->type_user == 1) {
            $activities = Activity::where('sprint_id', $this->selectSprint)
                ->where(function ($query) {
                    $query
                        ->where('title', 'like', '%' . $this->search . '%');
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when(!empty($this->selectedStates), function ($query) {
                    $query->whereIn('state', $this->selectedStates);
                })
                ->when($this->filterPriotiry, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                })
                ->when($this->filterState, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                })
                ->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                ->select('activities.*', 'delegates.name')
                ->orderBy($this->orderByType, $this->filteredExpected)
                ->with(['user', 'delegate'])
                ->get();
        } else {
            $activities = Activity::where('sprint_id', $this->selectSprint)
                ->where(function ($query) {
                    $query
                        ->where('title', 'like', '%' . $this->search . '%');
                })
                ->where(function ($query) use ($user_id) {
                    $query->where('delegate_id', $user_id);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when(!empty($this->selectedStates), function ($query) {
                    $query->whereIn('state', $this->selectedStates);
                })
                ->when($this->filterPriotiry, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                })
                ->when($this->filterState, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredStateArrow);
                })
                ->join('users as delegates', 'activities.delegate_id', '=', 'delegates.id')
                ->select('activities.*', 'delegates.name')
                ->orderBy($this->orderByType, $this->filteredExpected)
                ->with(['user', 'delegate'])
                ->get();
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
            $activity->messages_count = $messages->where('look', false)->count();
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

        return view('livewire.projects.table-activities', [
            'activities' => $activities,
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
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

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

        $this->emit('sprintsUpdated');
        $this->modalCreateSprint = false;
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Sprint creado',
        ]);
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
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $sprint = Sprint::find($id);
        $sprint->name = $this->name_sprint ?? $sprint->name;
        $sprint->start_date = $this->start_date ?? $sprint->start_date;
        $sprint->end_date = $this->end_date ?? $sprint->end_date;
        $sprint->save();

        $this->emit('sprintsUpdated');
        $this->modalCreateSprint = false;
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Sprint actualizado',
        ]);
    }

    public function updateStateSprint($id, $state)
    {
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

                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Estado de sprint actualizado',
                ]);
            } elseif ($previousSprint) {
                if ($previousSprint->state == 'Pendiente') {
                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Sprint anterior no iniciado',
                    ]);
                } else {
                    $sprint->state = $state;
                    $sprint->save();
                    $this->emit('sprintsUpdated');

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Estado de sprint actualizado',
                    ]);
                }
            }
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Sprint no encontrado',
            ]);
        }
    }

    public function destroySprint($id)
    {
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
    }

    public function createActivity()
    {
        try {
            $this->validate(
                [
                    'title' => 'required|max:255',
                    'delegate' => 'required',
                    'expected_date' => 'required|date',
                ],
                [
                    'title.required' => 'El título es obligatorio.',
                    'title.max:255' => 'El título no debe tener más caracteres que 255.',
                    'delegate.required' => 'El delegado es obligatorio.',
                    'expected_date.required' => 'La fecha esperada es obligatoria.',
                    'expected_date.date' => 'La fecha esperada debe ser una fecha válida.',
                ],
            );
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $activity = new Activity();
        if ($this->file) {
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (in_array($this->file->extension(), $extensionesImagen)) {
                $maxSize = 5 * 1024 * 1024; // 5 MB
                // Verificar el tamaño del archivo
                if ($this->file->getSize() > $maxSize) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                    ]);
                    return;
                }
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $this->project->customer->name . '/' . $this->project->name;
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
                // Guardar la imagen redimensionada en el almacenamiento local
                Storage::disk('activities')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                // // Eliminar la imagen temporal
                Storage::disk('local')->delete($tempPath);
                $activity->image = $fullNewFilePath;
            } else {
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $this->project->customer->name . '/' . $this->project->name;
                $fileName = $this->file->getClientOriginalName();
                $fullNewFilePath = $filePath . '/' . $fileName;
                // Guardar el archivo en el disco 'activities'
                $this->file->storeAs($filePath, $fileName, 'activities');
                $activity->image = $fullNewFilePath;
            }
        }

        $activity->sprint_id = $this->selectSprint;
        $activity->delegate_id = $this->delegate;
        $activity->user_id = Auth::id();
        $activity->title = $this->title;
        $activity->description = $this->description;

        if ($this->priority1) {
            $activity->priority = 'Alto';
        } elseif ($this->priority2) {
            $activity->priority = 'Medio';
        } elseif ($this->priority3) {
            $activity->priority = 'Bajo';
        }
        $activity->state = 'Abierto';

        if ($this->changePoints == true) {
            if (!$this->points) {
                $activity->points = 0;
            } else {
                $validPoints = [1, 2, 3, 5, 8, 13];
                $activity->points = $this->points;

                if (!in_array($this->points, $validPoints)) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Puntuaje no válido.',
                    ]);
                    return; // O cualquier otra acción que desees realizar
                } else {
                    $activity->points = $this->points;
                }
            }
            $questionsPoints = [
                'pointKnow' => null,
                'pointMany' => null,
                'pointEffort' => null,
            ];
            // Convertir el array a JSON
            $questionsPointsJson = json_encode($questionsPoints);
            // Asignar y guardar 
            $activity->questions_points = $questionsPointsJson;
        } else {
            if (!$this->point_know && !$this->point_many && !$this->point_effort) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'warning',
                    'title' => 'El formulario está incompleto o no se han seleccionado los puntos necesarios.',
                ]);
                $activity->points = 0;
                $questionsPoints = [
                    'pointKnow' => null,
                    'pointMany' => null,
                    'pointEffort' => null,
                ];
                // Convertir el array a JSON
                $questionsPointsJson = json_encode($questionsPoints);
                // Asignar y guardar 
                $activity->questions_points = $questionsPointsJson;
            } else {
                $maxPoint = max($this->point_know, $this->point_many, $this->point_effort);
                $activity->points = $maxPoint;
                // Crear un array asociativo con los valores
                $questionsPoints = [
                    'pointKnow' => $this->point_know,
                    'pointMany' => $this->point_many,
                    'pointEffort' => $this->point_effort,
                ];
                // Convertir el array a JSON
                $questionsPointsJson = json_encode($questionsPoints);
                // Asignar y guardar 
                $activity->questions_points = $questionsPointsJson;
            }
        }
        $activity->delegated_date = Carbon::now();
        $activity->expected_date = $this->expected_date;
        $activity->save();

        $this->emit('sprintsUpdated');
        $this->modalCreateActivity = false;
        $this->clearInputs();
        $this->render();
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Actividad creada',
        ]);
    }

    public function updateChat($id)
    {
        $activity = Activity::find($id);
        $user = Auth::user();

        if ($activity) {
            if ($this->message != '') {
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
                $chat->message = $this->message;
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
                $this->emit('messageSent', $activity->id);

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Mensaje enviado',
                ]);

                $this->message = '';
            } else {
                $this->modalShowActivity = false;
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El mensaje está vacío.',
                ]);
            }
        }
    }

    public function updateActivity($id)
    {
        try {
            $this->validate(
                [
                    'title' => 'required|max:255',
                    'expected_date' => 'required|date',
                ],
                [
                    'title.required' => 'El título es obligatorio.',
                    'title.max:255' => 'El título no debe tener más caracteres que 255.',
                    'expected_date.required' => 'La fecha esperada es obligatoria.',
                    'expected_date.date' => 'La fecha esperada debe ser una fecha válida.',
                ],
            );
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }
        $activity = Activity::find($id);
        
        if ($this->file) {
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (in_array($this->file->extension(), $extensionesImagen)) {
                $maxSize = 5 * 1024 * 1024; // 5 MB
                // Verificar el tamaño del archivo
                if ($this->file->getSize() > $maxSize) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                    ]);
                    return;
                }
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $this->project->customer->name . '/' . $this->project->name;
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
                if (Storage::disk('activities')->exists($activity->image)) {
                    Storage::disk('activities')->delete($activity->image);
                }
                // Guardar la imagen redimensionada en el almacenamiento local
                Storage::disk('activities')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                // // Eliminar la imagen temporal
                Storage::disk('local')->delete($tempPath);
                $activity->image = $fullNewFilePath;
            } else {
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $this->project->customer->name . '/' . $this->project->name;
                $fileName = $this->file->getClientOriginalName();
                $fullNewFilePath = $filePath . '/' . $fileName;

                // Verificar y eliminar el archivo anterior si existe y coincide con la nueva ruta
                if ($activity->image && Storage::disk('activities')->exists($activity->image)) {
                    $existingFilePath = pathinfo($activity->image, PATHINFO_DIRNAME);

                    if ($existingFilePath == $filePath) {
                        Storage::disk('activities')->delete($activity->image);
                    }
                }
                // Guardar el archivo en el disco 'activities'
                $this->file->storeAs($filePath, $fileName, 'activities');
                $activity->image = $fullNewFilePath;
            }
        }

        $activity->sprint_id = $this->moveActivity ?? $activity->sprint_id;
        if ($this->moveActivity) {
            $sprint = Sprint::find($this->moveActivity);
            if ($sprint->state == 'Cerrado') {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Sprint cerrado',
                ]);

                return;
            }
        }
        $activity->title = $this->title ?? $activity->title;
        $activity->description = $this->description ?? $activity->description;

        if ($this->priority1) {
            $activity->priority = 'Alto';
        } elseif ($this->priority2) {
            $activity->priority = 'Medio';
        } elseif ($this->priority3) {
            $activity->priority = 'Bajo';
        }

        $activity->expected_date = $this->expected_date ?? $activity->expected_date;

        if ($this->changePoints == true) {
            $validPoints = [0, 1, 2, 3, 5, 8, 13];
            $activity->points = $this->points;

            if (!in_array($this->points, $validPoints)) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Puntuaje no válido.',
                ]);
                return; // O cualquier otra acción que desees realizar
            } else {
                $activity->points = $this->points ?? $activity->points;
            }
            // Crear un array asociativo con los valores
            $questionsPoints = [
                'pointKnow' => null,
                'pointMany' => null,
                'pointEffort' => null,
            ];
            // Convertir el array a JSON
            $questionsPointsJson = json_encode($questionsPoints);
            // Asignar y guardar 
            $activity->questions_points = $questionsPointsJson;
        } else {
            if (!$this->point_know || !$this->point_many || !$this->point_effort) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'warning',
                    'title' => 'El formulario está incompleto o no se han seleccionado los puntos necesarios.',
                ]);
                $activity->points = $activity->points ?? 0;
                $questionsPoints = [
                    'pointKnow' => null,
                    'pointMany' => null,
                    'pointEffort' => null,
                ];
                // Convertir el array a JSON
                $questionsPointsJson = json_encode($questionsPoints);
                // Asignar y guardar 
                $activity->questions_points = $questionsPointsJson;
            } else {
                $maxPoint = max($this->point_know, $this->point_many, $this->point_effort);
                $activity->points = $maxPoint;
                // Crear un array asociativo con los valores
                $questionsPoints = [
                    'pointKnow' => $this->point_know,
                    'pointMany' => $this->point_many,
                    'pointEffort' => $this->point_effort,
                ];
                // Convertir el array a JSON
                $questionsPointsJson = json_encode($questionsPoints);
                // Asignar y guardar 
                $activity->questions_points = $questionsPointsJson;
            }
        }
        $activity->save();

        $this->clearInputs();
        $this->emit('sprintsUpdated');
        $this->modalCreateActivity = false;
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Actividad actualizada',
        ]);
    }

    public function destroyActivity($id)
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

                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Estado actualizado',
                ]);
            }
        }
    }
    // INFO MODAL
    public function showEditSprint($id)
    {
        $this->showUpdateSprint = true;

        if ($this->modalCreateSprint == true) {
            $this->modalCreateSprint = false;
        } else {
            $this->modalCreateSprint = true;
        }

        $this->sprintEdit = Sprint::find($id);
        $this->name_sprint = $this->sprintEdit->name;

        $fecha_start = Carbon::parse($this->sprintEdit->start_date);
        $this->start_date = $fecha_start->toDateString();
        $fecha_end = Carbon::parse($this->sprintEdit->end_date);
        $this->end_date = $fecha_end->toDateString();
    }

    public function showActivity($id)
    {
        $this->showActivity = true;

        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
        } else {
            $this->modalShowActivity = true;
            $this->loadMessages($id);
        }
    }

    public function loadMessages($id)
    {
        $this->activityShow = Activity::find($id);
        $this->messages = ChatReports::where('activity_id', $this->activityShow->id)->orderBy('created_at', 'asc')->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('activity_id', $this->activityShow->id)
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

        if ($this->messages) {
            $this->showChat = true;
            $this->messages->messages_count = $this->messages->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            if ($this->messages->messages_count >= 2) {
                // Filtrar los mensajes que no han sido vistos
                $moreMessages = $this->messages->where('look', false);

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

    public function showEditActivity($id)
    {
        $this->showUpdateActivity = true;

        if ($this->modalCreateActivity == true) {
            $this->modalCreateActivity = false;
        } else {
            $this->modalCreateActivity = true;
        }

        $this->activityEdit = Activity::find($id);
        $this->moveActivity = $this->activityEdit->sprint_id;
        $this->title = $this->activityEdit->title;
        $this->description = $this->activityEdit->description;

        $fecha_expected = Carbon::parse($this->activityEdit->expected_date);
        $this->expected_date = $fecha_expected->toDateString();

        $this->priority1 = false;
        $this->priority2 = false;
        $this->priority3 = false;

        if ($this->activityEdit->priority == 'Alto') {
            $this->priority1 = true;
        } elseif ($this->activityEdit->priority == 'Medio') {
            $this->priority2 = true;
        } elseif ($this->activityEdit->priority == 'Bajo') {
            $this->priority3 = true;
        }
        // EFFORT PONTS
        $this->points = $this->activityEdit->points;
        $this->changePoints = true;
        $questionsPoints = json_decode($this->activityEdit->questions_points, true);
        $this->point_know = $questionsPoints['pointKnow'] ?? null;
        $this->point_many = $questionsPoints['pointMany'] ?? null;
        $this->point_effort = $questionsPoints['pointEffort'] ?? null;
    }
    // MODAL
    public function modalBacklog()
    {
        if ($this->modalBacklog == true) {
            $this->modalBacklog = false;
        } else {
            $this->modalBacklog = true;
        }
    }

    public function modalCreateSprint()
    {
        $this->showUpdateSprint = false;

        if ($this->modalCreateSprint == true) {
            $this->modalCreateSprint = false;
        } else {
            $this->modalCreateSprint = true;
        }
        $this->clearInputs();
        $this->resetErrorBag();
    }

    public function modalShowActivity()
    {
        if ($this->modalShowActivity == true) {
            $this->modalShowActivity = false;
        } else {
            $this->modalShowActivity = true;
        }
        $this->clearInputs();
    }

    public function modalCreateActivity()
    {
        $this->showUpdateActivity = false;

        if ($this->modalCreateActivity == true) {
            $this->modalCreateActivity = false;
        } else {
            $this->activityEdit = null;
            $this->modalCreateActivity = true;
        }
        $this->clearInputs();
        $this->resetErrorBag();
    }
    // EXTRAS
    public function clearInputs()
    {
        $this->number = '';
        $this->name_sprint = '';
        $this->start_date = '';
        $this->end_date = '';

        $this->title = '';
        $this->dispatchBrowserEvent('file-reset');
        $this->description = '';
        $this->delegate = '';
        $this->expected_date = '';
        $this->priority1 = false;
        $this->priority2 = false;
        $this->priority3 = false;

        $this->changePoints = false;
        $this->points = '';
        $this->point_know = '';
        $this->point_many = '';

        $this->message = '';
        $this->point_effort = '';
    }

    public function sprintsUpdated()
    {
        $this->sprints = $this->backlog->sprints;
    }

    public function selectSprint($id)
    {
        $this->changeSprint = true;
        $this->updateSprintData($id);
        $this->selectSprint = $id;
        $this->emit('sprintsUpdated');
    }

    public function selectPriority($value)
    {
        $this->priority1 = false;
        $this->priority2 = false;
        $this->priority3 = false;

        if ($value === 'Alto') {
            $this->priority1 = true;
        } elseif ($value === 'Medio') {
            $this->priority2 = true;
        } elseif ($value === 'Bajo') {
            $this->priority3 = true;
        }
    }

    public function changePoints()
    {
        if ($this->changePoints == true) {
            $this->changePoints = false;
            if ($this->activityEdit == null) {
                $this->points = '';
            }
        } else {
            $this->changePoints = true;
            $this->point_know = '';
            $this->point_many = '';
            $this->point_effort = '';
        }
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
    // FILTER
    public function filterDown($type) 
    {
        $this->filter = true;
        $this->filtered = false;

        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filterState = false;
            $this->filteredPriority = 'asc';
            $this->priorityCase = "CASE WHEN priority = 'Bajo' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 ELSE 4 END";
        }

        if ($type == 'delegate') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->orderByType = 'name';
            $this->filteredExpected = 'asc';
        }

        if ($type == 'state') {
            $this->filterPriotiry = false;
            $this->filterState = true;
            $this->filteredStateArrow = 'asc';
            $this->priorityCase = "CASE WHEN state = 'Abierto' THEN 1 WHEN state = 'Proceso' THEN 2 WHEN state = 'Conflicto' THEN 3 WHEN state = 'Resuelto' THEN 4 ELSE 5 END";
        }
        
        if ($type == 'expected_date') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->filteredExpected = 'asc';
        }
    }

    public function filterUp($type) 
    {
        $this->filter = true;
        $this->filtered = true;

        if ($type == 'priority') {
            $this->filterPriotiry = true;
            $this->filterState = false;
            $this->filteredPriority = 'asc';
            $this->priorityCase = "CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 ELSE 4 END";
        }

        if ($type == 'delegate') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->orderByType = 'name';
            $this->filteredExpected = 'desc';
        }

        if ($type == 'state') {
            $this->filterPriotiry = false;
            $this->filterState = true;
            $this->filteredStateArrow = 'asc';
            $this->priorityCase = "CASE WHEN state = 'Resuelto' THEN 1 WHEN state = 'Conflicto' THEN 2 WHEN state = 'Proceso' THEN 3 WHEN state = 'Abierto' THEN 4 ELSE 5 END";
        }
        
        if ($type == 'expected_date') {
            $this->filterPriotiry = false;
            $this->filterState = false;
            $this->filteredExpected = 'desc';
        }
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
        }
    }

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
