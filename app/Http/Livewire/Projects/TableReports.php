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

    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalShow = false, $modalEdit = false, $modalDelete = false, $modalEvidence = false;
    public $showReport = false, $showEdit = false, $showDelete = false, $showEvidence = false, $showChat = false;
    public $messages;

    // table, action's reports
    public $leader = false;
    public $search, $project, $reportShow, $reportEdit, $reportDelete, $reportEvidence, $evidenceShow;
    public $perPage = '10';
    public $selectedDelegate = '', $priorityOrder = '', $datesOrder = '', $progressOrder = '', $expectedOrder = '', $createdOrder = '';
    public $selectedStates = [], $rules = [], $usersFiltered = [], $allUsersFiltered = [];
    public $priorityFiltered = false, $progressFiltered = false, $expectedFiltered = false, $createdFiltered = false;

    // inputs
    public $name, $type, $customer, $file, $comment, $evidence, $message, $expected_date;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $user = Auth::user();
        $user_id = $user->id;
        $allUsers = User::all();

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
            ->when(!empty($this->selectedStates), function ($query) {
                $query->whereIn('state', $this->selectedStates);
            })
            ->when($this->selectedDelegate, function ($query) {
                $query->where('delegate_id', $this->selectedDelegate);
            })
            ->when($this->priorityOrder, function ($query) {
                if ($this->priorityOrder == 'Alto') {
                    $query->orderByRaw("CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 END desc");
                } else {
                    $query->orderByRaw("CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 END asc");
                }
            })
            ->when($this->datesOrder, function ($query) {
                if ($this->datesOrder == 'progress') {
                    $query->orderBy('progress', $this->progressOrder);
                } 
                if ($this->datesOrder == 'expected_date') {
                    $query->orderBy('expected_date', $this->expectedOrder);
                } 
                if ($this->datesOrder == 'created_at') {
                    $query->orderBy('created_at', $this->createdOrder);
                } 
            })
            ->with(['user', 'delegate'])
            ->paginate($this->perPage);
        } else {
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
                ->where(function($query) use ($user_id) {
                    $query->where('delegate_id', $user_id)
                        // O incluir registros donde user_id es igual a user_id y video es true
                        ->orWhere(function($subQuery) use ($user_id) {
                            $subQuery->where('user_id', $user_id)
                                    ->where('video', true);
                        });
                })
                ->when($this->selectedState, function ($query) {
                    $query->where('state', $this->selectedState);
                })
                ->when($this->selectedState, function ($query) {
                    $query->where('state', $this->selectedState);
                })
                ->when($this->selectedDelegate, function ($query) {
                    $query->where('delegate_id', $this->selectedDelegate);
                })
                ->when($this->priorityOrder, function ($query) {
                    if ($this->priorityOrder == 'Alto') {
                        $query->orderByRaw("CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Alto' THEN 3 END desc");
                    } else {
                        $query->orderByRaw("CASE WHEN priority = 'Alto' THEN 1 WHEN priority = 'Medio' THEN 2 WHEN priority = 'Bajo' THEN 3 END asc");
                    }
                })
                ->when($this->datesOrder, function ($query) {
                    if ($this->datesOrder == 'progress') {
                        $query->orderBy('progress', $this->progressOrder);
                    } 
                    if ($this->datesOrder == 'expected_date') {
                        $query->orderBy('expected_date', $this->expectedOrder);
                    } 
                    if ($this->datesOrder == 'created_at') {
                        $query->orderBy('created_at', $this->createdOrder);
                    } 
                })
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

        foreach ($allUsers as $key => $user) {
            // TODOS LOS DELEGADOS
            $this->allUsersFiltered[$user->id] = $user->name .  $user->lastname;
        }

        // ADD ATRIBUTES
        foreach ($reports as $report) {    
            // ACTIONS
            $report->filteredActions = $this->getFilteredActions($report->state);
            // DELEGATE
            $report->usersFiltered = $allUsers->reject(function ($user) use ($report) {
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
            $messages = ChatReports::where('report_id', $report->id)->get();
            // Verificar si la colección tiene al menos un mensaje
            if ($messages->isNotEmpty()) {
                $lastMessage = $messages->last();
                $report->user_chat = $lastMessage->user_id;
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
            $chat = new ChatReports();
            $chat->report_id = $report->id;
            $chat->user_id = $user->id;
            $chat->message = $this->message;
            $chat->look = false;
            $chat->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Mensaje enviado',
            ]);

            $this->message = '';
            $this->modalShow = false;
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
                $fileName = 'Evidencia ' . $project->name . ', ' . $dateString . '.' . $fileExtension;
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                $fullNewFilePath = $filePath . '/' . $fileName;

                // Verificar si la ruta existe, si no, crearla
                if (!Storage::disk('evidence')->exists($filePath)) {
                    Storage::disk('evidence')->makeDirectory($filePath);
                }

                // Guardar el archivo en la ruta especificada dentro del disco 'evidence'
                $this->evidence->storeAs($filePath, $fileName, 'evidence');

                $evidence = new Evidence;

                $evidence->report_id = $report->id;
                $evidence->content = $fullNewFilePath;

                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];
                if (in_array($fileExtension, $extensionesImagen)) {
                    $evidence->image = true;
                    $evidence->video = false;
                    $evidence->file = false;
                } elseif (in_array($fileExtension, $extensionesVideo)) {
                    $evidence->image = false;
                    $evidence->video = true;
                    $evidence->file = false;
                } else {
                    $evidence->image = false;
                    $evidence->video = false;
                    $evidence->file = true;
                }

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
        $report = Report::find($id);
        $project = Project::find($project_id);

        if ($report) {
            if ($this->file) {
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

                $extension = $this->file->extension();
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                if (in_array($extension, $extensionesImagen)) {
                    $report->image = true;
                    $report->video = false;
                    $report->file = false;
                } elseif (in_array($extension, $extensionesVideo)) {
                    $report->image = false;
                    $report->video = true;
                    $report->file = false;
                } else {
                    $report->image = false;
                    $report->video = false;
                    $report->file = true;
                }

                $report->content = $fullNewFilePath;
            }
            $report->comment = $this->comment;
            $report->expected_date = $this->expected_date;
            $report->save();
            
            $this->modalEdit = false;

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Guardado exitoso',
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
        $this->messages = ChatReports::where('report_id', $this->reportShow->id)->get();
        // Primero, obtén el último mensaje para este reporte que no haya sido visto por el usuario autenticado
        $lastMessage = ChatReports::where('report_id', $this->reportShow->id)
            ->where('user_id', '!=', Auth::id())
            ->where('look', false)
            ->latest()
            ->first();

        if ($lastMessage) {
            $lastMessage->look = true;
            $lastMessage->save();
        }
        
        if($this->messages) {
            $this->showChat = true;
            $this->messages->messages_count = $this->messages->where('look', false)->count();
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
        $this->comment = $this->reportEdit->comment;

        $fecha = Carbon::parse($this->reportEdit->expected_date);
        $this->expected_date = $fecha->toDateString();;
    }

    public function showDelete($id, $project_id)
    {
        $this->showDelete = true;

        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
        }

        $project = Project::find($project_id);
        $report = Report::find($id);
        $report->project_id = $project->id;
        $this->reportDelete = $report;
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

    public function modalDelete()
    {
        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
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
    // FILTERED
    public function orderByHighPriority()
    {
        $this->priorityFiltered = true;
        $this->priorityOrder = 'Alto';
    }

    public function orderByLowPriority()
    {
        $this->priorityFiltered = false;
        $this->priorityOrder = 'Bajo';
    }

    public function orderByHighDates($type)
    {
        if ($type == 'progress') {
            $this->progressFiltered = true;
            $this->datesOrder = 'progress';
            $this->progressOrder = 'desc';
        }

        if ($type == 'expected_date') {
            $this->expectedFiltered = true;
            $this->datesOrder = 'expected_date';
            $this->expectedOrder = 'desc';
        }

        if ($type == 'created_at') {
            $this->createdFiltered = true;
            $this->datesOrder = 'created_at';
            $this->createdOrder = 'desc';
        }
    }

    public function orderByLowDates($type)
    {
        if ($type == 'progress') {
            $this->progressFiltered = false;
            $this->datesOrder = 'progress';
            $this->progressOrder = 'asc';
        }

        if ($type == 'expected_date') {
            $this->expectedFiltered = false;
            $this->datesOrder = 'expected_date';
            $this->expectedOrder = 'asc';
        }

        if ($type == 'created_at') {
            $this->createdFiltered = false;
            $this->datesOrder = 'created_at';
            $this->createdOrder = 'asc';
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
    
    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
