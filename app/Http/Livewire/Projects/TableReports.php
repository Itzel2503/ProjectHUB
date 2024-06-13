<?php

namespace App\Http\Livewire\Projects;

use App\Models\ChatReports;
use App\Models\Evidence;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TableReports extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'delete'];
    // Generales
    public $allUsers;
    // modal
    public $modalShow = false, $modalEdit = false, $modalEvidence = false;
    public $showReport = false, $showEdit = false, $showEvidence = false, $showChat = false;
    public $messages;
    // modal activity points
    public $changePoints = false;
    public $points, $point_know, $point_many, $point_effort;
    // table, action's reports
    public $leader = false;
    public $search, $project, $reportShow, $reportEdit, $reportEvidence, $evidenceShow;
    public $perPage = '100';
    public $selectedDelegate = '';
    public $selectedStates = [], $rules = [], $usersFiltered = [], $allUsersFiltered = [];
    public $Filtered = false;
    // inputs
    public $tittle, $type, $file, $comment, $evidenceEdit, $expected_date, $priority1, $priority2, $priority3, $evidence, $message;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $user = Auth::user();
        $user_id = $user->id;
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();

        if (Auth::user()->type_user == 1) {
            $reports = Report::where('project_id', $this->project->id)
                ->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('comment', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('user', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    // Buscar por 'reincidencia' para seleccionar reportes con 'repeat' en true
                    if (strtolower($this->search) === 'reincidencia' || strtolower($this->search) === 'Reincidencia') {
                        $query->orWhereNotNull('count');
                    }
                })
                ->when($this->selectedStates, function ($query) {
                    $query->whereIn('state', $this->selectedStates);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->orderBy('created_at', 'desc')
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);
        } elseif (Auth::user()->type_user == 2) {
            $reports = Report::where('project_id', $this->project->id)
                ->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('comment', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                    if (strtolower($this->search) === 'reincidencia') {
                        $query->orWhereNotNull('count');
                    }
                })
                ->where(function ($query) use ($user_id) {
                    $query->where('delegate_id', $user_id)
                        // O incluir registros donde user_id es igual a user_id y video es true
                        ->orWhere(function ($subQuery) use ($user_id) {
                            $subQuery->where('user_id', $user_id)
                                ->where('video', true);
                        });
                })
                ->when($this->selectedStates, function ($query) {
                    $query->whereIn('state', $this->selectedStates);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->orderBy('created_at', 'desc')
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);
        } elseif (Auth::user()->type_user == 3) {
            $reports = Report::where('project_id', $this->project->id)
                ->whereHas('user', function ($query) {
                    $query->where('type_user', 3);
                })
                ->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%')
                        ->orWhere('comment', 'like', '%' . $this->search . '%')
                        ->orWhere('state', 'like', '%' . $this->search . '%')
                        ->orWhereHas('delegate', function ($subQuery) {
                            $subQuery->where('name', 'like', '%' . $this->search . '%');
                        });
                })
                ->when($this->selectedStates, function ($query) {
                    $query->whereIn('state', $this->selectedStates);
                })
                ->orderBy('created_at', 'desc')
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);
        }
        // LEADER TABLE
        foreach ($this->project->users as $projectUser) {
            if ($projectUser->id === $user->id && $projectUser->pivot->leader) {
                $this->leader = true;
                break;
            }
        }

        foreach ($this->allUsers as $key => $user) {
            // TODOS LOS DELEGADOS
            $this->allUsersFiltered[$user->id] = $user->name;
        }

        // ADD ATRIBUTES
        foreach ($reports as $report) {
            // ACTIONS
            $report->filteredActions = $this->getFilteredActions($report->state);
            // DELEGATE
            $report->usersFiltered = $this->allUsers->reject(function ($user) use ($report) {
                return $user->id === $report->delegate_id;
            })->values();
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
        return view('livewire.projects.table-reports', [
            'reports' => $reports,
        ]);
    }
    // ACTIONS
    public function create($project_id)
    {
        $this->redirectRoute('projects.reports.create', ['project' => $project_id]);
    }

    public function updateState($id, $project_id, $state)
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
                    $this->reportEvidence = $report;
                } else {
                    $report->state = $state;
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

    public function updateDelegate($id, $delegate)
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
        }
    }

    public function updateChat($id)
    {
        $report = Report::find($id);
        $user = Auth::user();

        if ($report) {
            if ($this->message != '') {
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

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Mensaje enviado',
                ]);

                $this->message = '';
                $this->modalShow = false;
            } else {
                $this->modalShow = false;
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El mensaje está vacío.',
                ]);
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

    public function update($id, $project_id)
    {
        try {
            // Verificar si al menos uno de los campos está presente
            if ($this->changePoints == true) {
                if (!$this->points) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Agrega puntos de esfuerzo.',
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
                $validPoints = [1, 2, 3, 5, 8, 13];
                $report->points = $this->points;
    
                if (!in_array($this->points, $validPoints)) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Puntuaje no válido.',
                    ]);
                    return; // O cualquier otra acción que desees realizar
                } else {
                    $report->points = $this->points ?? $report->points;
                }
            } else {
                $maxPoint = max($this->point_know, $this->point_many, $this->point_effort);
                $report->points = $maxPoint ?? $report->points;
            }

            $report->save();

            $this->modalEdit = false;

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Guardado exitoso',
            ]);
        }
    }

    public function delete($id, $project_id)
    {
        $project = Project::find($project_id);
        $report = Report::find($id);

        if ($report) {
            if ($report->content) {
                $contentPath = 'reportes/' . $report->content;
                $fullPath = public_path($contentPath);

                if (File::exists($fullPath)) {
                    File::delete($fullPath);
                }
            }
            $report->delete();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Reporte eliminado',
            ]);

            return redirect()->to('/projects/' . $project->id . '/reports');
        } else {
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Reporte no encontrado',
            ]);
        }
    }
    // INFO MODAL
    public function showReport($id)
    {
        $this->showReport = true;

        if ($this->modalShow == true) {
            $this->modalShow = false;
        } else {
            $this->modalShow = true;
        }

        $this->reportShow = Report::find($id);
        $this->evidenceShow = Evidence::where('report_id', $this->reportShow->id)->first();
        $this->messages = ChatReports::where('report_id', $this->reportShow->id)->orderBy('created_at', 'asc')->get();
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

        if ($this->messages) {
            $this->showChat = true;
            $this->messages->messages_count = $this->messages->where('look', false)->count();
            // Marcar como vistos los mensajes si hay dos o más sin ver
            // dd($this->messages);
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

    public function showEdit($id)
    {
        $this->showEdit = true;

        if ($this->modalEdit == true) {
            $this->modalEdit = false;
        } else {
            $this->modalEdit = true;
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
    public function modalShow()
    {
        if ($this->modalShow == true) {
            $this->modalShow = false;
        } else {
            $this->modalShow = true;
        }
    }

    public function modalEdit()
    {
        $this->showEdit = false;

        if ($this->modalEdit == true) {
            $this->modalEdit = false;
        } else {
            $this->modalEdit = true;
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
    // EXTRAS
    public function reportRepeat($project_id, $report_id)
    {
        return redirect()->route('projects.reports.show', ['project' => $project_id, 'report' => $report_id]);
    }

    public function getFilteredActions($currentState)
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

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
