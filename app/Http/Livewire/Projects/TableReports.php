<?php

namespace App\Http\Livewire\Projects;

use App\Models\Activity;
use App\Models\ChatReportsActivities;
use App\Models\Evidence;
use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TableReports extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['delete'];
    // ENVIADAS
    public $project;
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
    public $filteredPriority = '', $filteredState = '', $priorityCase = '', $filteredExpected = 'asc', $orderByType = 'expected_date';
    // MODAL SHOW
    public $showReport = false;
    public $reportShow = '';
    // MODAL EDIT
    public $editReport = false;
    public $reportEdit = '';
    // MODAL EVIDENCE
    public $showEvidence = false;
    public  $reportEvidence;
    public $evidenceActRep = false;
    // INPUTS
    public $evidence;
    // GRAFICA EFFORT POINTS
    public $starMonth, $endMonth;

    // Resetear paginación cuando se actualiza el campo de búsqueda
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $user = Auth::user();
        $user_id = $user->id;
        // DELEGATE
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get(); // TODOS LOS USUARIOS MENOS CLIENTES
        // FILTRO DELEGADOS
        $this->allUsersFiltered = []; 
        foreach ($this->allUsers as $user) {
            $this->allUsersFiltered[] = [
                'id' => $user->id,
                'name' => $user->name,
            ];
        }
        // CONSULTAS DE DATOS
        if (Auth::user()->type_user == 1) {
            if ($this->project == null) {
                $reports = Report::where(function ($query) {
                        $query->where('title', 'like', '%' . $this->search . '%');
                        // Buscar por 'reincidencia' para seleccionar reportes con 'repeat' en true
                        if (strtolower($this->search) === 'reincidencia' || strtolower($this->search) === 'Reincidencia') {
                            $query->orWhereNotNull('count');
                        }
                        // Si no se seleccionan estados, excluir "Resuelto"
                        if (empty($this->selectedStates)) {
                            $query->where('reports.state', '!=', 'Resuelto');
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
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredState);
                    })
                    ->join('users as delegates', 'reports.delegate_id', '=', 'delegates.id')
                    ->select('reports.*', 'delegates.name')
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            } else {
                $reports = Report::where('project_id', $this->project->id)
                    ->where(function ($query) {
                        $query->where('title', 'like', '%' . $this->search . '%');
                        // Buscar por 'reincidencia' para seleccionar reportes con 'repeat' en true
                        if (strtolower($this->search) === 'reincidencia' || strtolower($this->search) === 'Reincidencia') {
                            $query->orWhereNotNull('count');
                        }
                        // Si no se seleccionan estados, excluir "Resuelto"
                        if (empty($this->selectedStates)) {
                            $query->where('reports.state', '!=', 'Resuelto');
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
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredState);
                    })
                    ->join('users as delegates', 'reports.delegate_id', '=', 'delegates.id')
                    ->select('reports.*', 'delegates.name')
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            }
        } elseif (Auth::user()->type_user == 2) {
            if ($this->project == null) {
                $reports = Report::where(function ($query) {
                        $query->where('title', 'like', '%' . $this->search . '%');
                        // Buscar por 'reincidencia' para seleccionar reportes con 'repeat' en true
                        if (strtolower($this->search) === 'reincidencia' || strtolower($this->search) === 'Reincidencia') {
                            $query->orWhereNotNull('count');
                        }
                        // Si no se seleccionan estados, excluir "Resuelto"
                        if (empty($this->selectedStates)) {
                            $query->where('reports.state', '!=', 'Resuelto');
                        } else {
                            // Incluir todos los estados seleccionados
                            $query->whereIn('state', $this->selectedStates);
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
                    ->when($this->filterPriotiry, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                    })
                    ->when($this->selectedDelegate, function ($query) {
                        $query->where('delegate_id', $this->selectedDelegate);
                    })
                    ->when($this->filterState, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredState);
                    })
                    ->join('users as delegates', 'reports.delegate_id', '=', 'delegates.id')
                    ->select('reports.*', 'delegates.name')
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
            } else {
                $reports = Report::where('project_id', $this->project->id)
                    ->where(function ($query) {
                        $query->where('title', 'like', '%' . $this->search . '%');
                        if (strtolower($this->search) === 'reincidencia' || strtolower($this->search) === 'Reincidencia') {
                            $query->orWhereNotNull('count');
                        }
                        // Si no se seleccionan estados, excluir "Resuelto"
                        if (empty($this->selectedStates)) {
                            $query->where('reports.state', '!=', 'Resuelto');
                        } else {
                            // Incluir todos los estados seleccionados
                            $query->whereIn('state', $this->selectedStates);
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
                    ->when($this->filterPriotiry, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                    })
                    ->when($this->filterState, function ($query) {
                        $query->orderByRaw($this->priorityCase . ' ' . $this->filteredState);
                    })
                    ->join('users as delegates', 'reports.delegate_id', '=', 'delegates.id')
                    ->select('reports.*', 'delegates.name')
                    ->orderBy($this->orderByType, $this->filteredExpected)
                    ->with(['user', 'delegate'])
                    ->paginate($this->perPage);
                }
        } elseif (Auth::user()->type_user == 3) {
            $reports = Report::where('project_id', $this->project->id)
                ->whereHas('user', function ($query) {
                    $query->where('type_user', 3);
                })
                ->where(function ($query) {
                    $query->where('title', 'like', '%' . $this->search . '%');
                    if (strtolower($this->search) === 'reincidencia' || strtolower($this->search) === 'Reincidencia') {
                        $query->orWhereNotNull('count');
                    }
                    // Si no se seleccionan estados, excluir "Resuelto"
                    if (empty($this->selectedStates)) {
                        $query->where('reports.state', '!=', 'Resuelto');
                    } else {
                        // Incluir todos los estados seleccionados
                        $query->whereIn('state', $this->selectedStates);
                    }
                })
                ->when($this->filterPriotiry, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredPriority);
                })
                ->when($this->filterState, function ($query) {
                    $query->orderByRaw($this->priorityCase . ' ' . $this->filteredState);
                })
                ->join('users as delegates', 'reports.delegate_id', '=', 'delegates.id')
                ->select('reports.*', 'delegates.name')
                ->orderBy($this->orderByType, $this->filteredExpected)
                ->with(['user', 'delegate'])
                ->paginate($this->perPage);
        }
        // ADD ATRIBUTES
        foreach ($reports as $report) {
            // ACTIONS
            $report->filteredActions = $this->getFilteredActions($report->state);
            // DELEGATE
            $report->usersFiltered = $this->allUsers->reject(function ($user) use ($report) {
                return $user->id === $report->delegate_id;
            })->values();
            // ETIQUETA DE PROGRESS
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
                    $report->user_id = $lastMessageNoView->user_id;
                    $report->receiver_id = $lastMessageNoView->receiver_id;

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
                            // VER MENSAJES EXCLUSIVOS DE CLIENTE PARA ADMINISTRADORES
                            if ($lastMessage->transmitter && Auth::user()->type_user == 1) {
                                if ($lastMessage->transmitter->type_user == 3) {
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
            // Verificar si el archivo existe en la base de datos y si es un video
            if ($report->content && $report->video == true) {
                // Verificar si el archivo existe en la carpeta
                $filePath = public_path('reportes/' . $report->content);
                if (file_exists($filePath)) {
                    $report->contentExists = true;
                } else {
                    $report->contentExists = false;
                }
            } else {
                $report->contentExists = true;
            }
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
                    if ($this->project == null) {
                        $this->evidenceActRep = true;
                        $this->reportEvidence = $report;
                    } else {
                        $this->showEvidence = true;
                        $project = Project::find($project_id);
                        $report->project_id = $project->id;
                        $this->reportEvidence = $report;
                    }
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
                $this->dispatchBrowserEvent('file-reset');

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Evidencia actualizada',
                ]);
                $report->updated_expected_date = true;
                $report->end_date = Carbon::now();
                $report->state = 'Resuelto';
                $report->repeat = true;
                $report->save();
                $this->showEvidence = false;
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Selecciona un archivo',
                ]);
            }
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

    public function finishEvidence($project_id, $report_id)
    {
        $this->redirectRoute('projects.reports.index', ['project' => $project_id, 'reports' => $report_id, 'highlight' => $report_id]);
    }
    // MODAL
    public function showReport($id)
    {
        if ($this->showReport == true) {
            $this->showReport = false;
            $this->reportShow = null;
        } else {
            $this->reportShow = Report::find($id);
            if ($this->reportShow) {
                $this->showReport = true;
            } else {
                $this->showReport = false;
                // Maneja un caso en el que no se encuentra el reporte (opcional)
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El reporte no existe',
                ]);
            }
        }
    }

    public function editReport($id)
    {
        if ($this->editReport == true) {
            $this->editReport = false;
            $this->reportEdit = null;
        } else {
            $this->reportEdit = Report::find($id);
            if ($this->reportEdit) {
                $this->editReport = true;
            } else {
                $this->editReport = false;
                // Maneja un caso en el que no se encuentra el reporte (opcional)
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El reporte no existe',
                ]);
            }
        }
    }

    public function evidenceActRep($id)
    {
        dd($id);
        $this->evidenceActRep = !$this->evidenceActRep;
    }
    // FILTER
    public function togglePanel($reportId)
    {
        // Si el panel ya está visible, lo cerramos
        if (isset($this->visiblePanels[$reportId]) && $this->visiblePanels[$reportId]) {
            unset($this->visiblePanels[$reportId]);
        } else {
            // Cerrar todos los demás paneles
            $this->visiblePanels = [];

            // Abrir el panel seleccionado
            $this->visiblePanels[$reportId] = true;
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
    // PROTECTED
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
