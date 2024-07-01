<?php

namespace App\Http\Livewire\Projects;

use App\Models\Backlog;
use App\Models\BacklogFiles;
use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Projects extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'destroy', 'restore'];
    // PESTAÑA
    public $activeTab = 'Activo';
    // MODAL CREATE EDIT
    public $modalCreateEdit = false,
        $createBacklog = false;
    public $showUpdate = false;
    public $customers = [],
        $selectedFiles = [];
    public $projectCustomer, $projectEdit, $backlogEdit;
    // MODAL PRIORITY
    public $modalPriority = false, $showPriority = false;
    public $projectPriority;
    // TABLE
    public $search;
    public $allType = ['Activo', 'Soporte', 'No activo', 'Entregado', 'Cerrado'];
    public $typeProject = 'Activo';
    // inputs
    public $code, $name, $type, $priority, $customer, $leader, $programmer, $general_objective, $scopes, $start_date, $closing_date, $passwords;
    public $files = [];
    public $severity, $impact, $satisfaction, $temporality, $magnitude, $strategy, $stage;

    public function render()
    {
        $allCustomers = Customer::all();
        $allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();
        $user = Auth::user();

        if (Auth::user()->type_user == 1) {
            $projects = Project::select('projects.*', 'customers.name as customer_name', 'backlogs.id as backlog')
                ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
                ->leftJoin('backlogs', 'projects.id', '=', 'backlogs.project_id')
                ->withTrashed()
                ->where(function ($query) {
                    $query
                        ->where('customers.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.code', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.type', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.priority', 'like', '%' . $this->search . '%');
                })
                ->where('type', $this->typeProject)
                ->with([
                    'users' => function ($query) {
                        $query->withPivot('leader', 'programmer');
                    },
                ])
                ->orderBy('projects.priority', 'asc')
                ->get();

            if ($this->activeTab == 'Activo') {
                $projectsSoporte = Project::select('projects.*', 'customers.name as customer_name', 'backlogs.id as backlog')
                    ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
                    ->leftJoin('backlogs', 'projects.id', '=', 'backlogs.project_id')
                    ->withTrashed()
                    ->where(function ($query) {
                        $query
                            ->where('customers.name', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.code', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.type', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.name', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.priority', 'like', '%' . $this->search . '%');
                    })
                    ->where('type', 'Soporte')
                    ->with([
                        'users' => function ($query) {
                            $query->withPivot('leader', 'programmer');
                        },
                    ])
                    ->orderBy('projects.priority', 'asc')
                    ->get();
            } else {
                $projectsSoporte = null;
            }
        } elseif (Auth::user()->type_user == 2) {
            $projects = Project::select('projects.*', 'customers.name as customer_name', 'backlogs.id as backlog')
                ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
                ->leftJoin('backlogs', 'projects.id', '=', 'backlogs.project_id')
                ->where(function ($query) {
                    $query
                        ->where('customers.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.code', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.type', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.priority', 'like', '%' . $this->search . '%');
                })
                ->where('type', $this->typeProject)
                ->with([
                    'users' => function ($query) {
                        $query->withPivot('leader', 'programmer');
                    },
                ])
                ->orderBy('projects.priority', 'asc')
                ->get();
            if ($this->activeTab == 'Activo') {
                $projectsSoporte = Project::select('projects.*', 'customers.name as customer_name', 'backlogs.id as backlog')
                    ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
                    ->leftJoin('backlogs', 'projects.id', '=', 'backlogs.project_id')
                    ->where(function ($query) {
                        $query
                            ->where('customers.name', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.code', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.type', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.name', 'like', '%' . $this->search . '%')
                            ->orWhere('projects.priority', 'like', '%' . $this->search . '%');
                    })
                    ->where('type', 'Soporte')
                    ->with([
                        'users' => function ($query) {
                            $query->withPivot('leader', 'programmer');
                        },
                    ])
                    ->orderBy('projects.priority', 'asc')
                    ->get();
            } else {
                $projectsSoporte = null;
            }
        } elseif (Auth::user()->type_user == 3) {
            $projects = $user->clientProjects()
                ->where('projects.name', 'like', '%' . $this->search . '%')
                ->orderBy('created_at', 'desc')
                ->get();

            $projectsSoporte = null;
        }

        // ADD ATRIBUTES
        foreach ($projects as $project) {
            // Encuentra al líder y al programador dentro de los usuarios relacionados
            $leader = $project->users->where('pivot.leader', true)->first();
            $programmer = $project->users->where('pivot.programmer', true)->first();

            $project->leader = $leader;
            $project->programmer = $programmer;
        }

        if ($projectsSoporte != null) {
            foreach ($projectsSoporte as $projectSoporte) {
                // Encuentra al líder y al programador dentro de los usuarios relacionados
                $leader = $projectSoporte->users->where('pivot.leader', true)->first();
                $programmer = $projectSoporte->users->where('pivot.programmer', true)->first();

                $projectSoporte->leader = $leader;
                $projectSoporte->programmer = $programmer;
            }
        }

        return view('livewire.projects.projects', [
            'projects' => $projects,
            'projectsSoporte' => $projectsSoporte,
            'allCustomers' => $allCustomers,
            'allUsers' => $allUsers,
        ]);
    }
    // ACTIONS
    public function create()
    {
        try {
            $this->validate([
                'code' => 'required|numeric',
                'name' => 'required|max:255',
                'type' => 'required|max:255',
                'customer' => 'required',
                'leader' => 'required',
                'programmer' => 'required',
                'general_objective' => 'required|max:255',
                'files' => 'nullable',
                'scopes' => 'nullable',
                'start_date' => 'required|date|max:255',
                'closing_date' => 'required|date|max:255',

                'severity' => 'required',
                'impact' => 'required',
                'satisfaction' => 'required',
                'temporality' => 'required',
                'magnitude' => 'required',
                'strategy' => 'required',
                'stage' => 'required',
            ]);

            // Verificar si al menos uno de los campos está presente
            if (!$this->files && !$this->scopes) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Faltan los alcances.',
                ]);
                return;
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

        if (empty($this->files) || empty(array_filter($this->files))) {
            if (empty($this->scopes)) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Se requiere seleccionar o cargar al menos una imagen.',
                ]);
                return;
            } else {
                $project = new Project();
                $project->customer_id = $this->customer;
                $project->code = $this->code;
                $project->type = $this->type;
                $project->name = $this->name;
                $total = $this->severity + $this->impact + $this->satisfaction + $this->temporality + $this->magnitude + $this->strategy + $this->stage;
                if ($total >= 80 && $total <= 108) {
                    $project->priority = 1;
                } elseif ($total >= 72 && $total <= 79) {
                    $project->priority = 2;
                } elseif ($total >= 65 && $total <= 71) {
                    $project->priority = 3;
                } elseif ($total >= 57 && $total <= 64) {
                    $project->priority = 4;
                } elseif ($total >= 50 && $total <= 56) {
                    $project->priority = 5;
                } elseif ($total >= 42 && $total <= 49) {
                    $project->priority = 6;
                } elseif ($total >= 35 && $total <= 41) {
                    $project->priority = 7;
                } elseif ($total >= 27 && $total <= 34) {
                    $project->priority = 8;
                } elseif ($total >= 20 && $total <= 26) {
                    $project->priority = 9;
                } elseif ($total >= 0 && $total <= 19) {
                    $project->priority = 10;
                }
                // Crear un array asociativo con los valores
                $questionsPriority = [
                    'severity' => $this->severity,
                    'impact' => $this->impact,
                    'satisfaction' => $this->satisfaction,
                    'temporality' => $this->temporality,
                    'magnitude' => $this->magnitude,
                    'strategy' => $this->strategy,
                    'stage' => $this->stage,
                ];
                // Convertir el array a JSON
                $questionsPriorityJson = json_encode($questionsPriority);
                // Asignar y guardar 
                $project->questions_priority = $questionsPriorityJson;
                $project->save();
                // Asocia el usuario al proyecto
                $project->users()->attach($this->leader, ['leader' => true, 'programmer' => false, 'client' => false]);
                $project->users()->attach($this->programmer, ['leader' => false, 'programmer' => true, 'client' => false]);

                $backlog = new Backlog();
                $backlog->general_objective = $this->general_objective;
                $backlog->scopes = $this->scopes;
                $backlog->start_date = $this->start_date;
                $backlog->closing_date = $this->closing_date;
                $backlog->passwords = $this->passwords;
                $backlog->project_id = $project->id;
                $backlog->save();
            }
        } else {
            $project = new Project();
            $project->customer_id = $this->customer;
            $project->code = $this->code;
            $project->type = $this->type;
            $project->name = $this->name;
            $total = $this->severity + $this->impact + $this->satisfaction + $this->temporality + $this->magnitude + $this->strategy + $this->stage;
            if ($total >= 80 && $total <= 108) {
                $project->priority = 1;
            } elseif ($total >= 72 && $total <= 79) {
                $project->priority = 2;
            } elseif ($total >= 65 && $total <= 71) {
                $project->priority = 3;
            } elseif ($total >= 57 && $total <= 64) {
                $project->priority = 4;
            } elseif ($total >= 50 && $total <= 56) {
                $project->priority = 5;
            } elseif ($total >= 42 && $total <= 49) {
                $project->priority = 6;
            } elseif ($total >= 35 && $total <= 41) {
                $project->priority = 7;
            } elseif ($total >= 27 && $total <= 34) {
                $project->priority = 8;
            } elseif ($total >= 20 && $total <= 26) {
                $project->priority = 9;
            } elseif ($total >= 0 && $total <= 19) {
                $project->priority = 10;
            }
            // Crear un array asociativo con los valores
            $questionsPriority = [
                'severity' => $this->severity,
                'impact' => $this->impact,
                'satisfaction' => $this->satisfaction,
                'temporality' => $this->temporality,
                'magnitude' => $this->magnitude,
                'strategy' => $this->strategy,
                'stage' => $this->stage,
            ];
            // Convertir el array a JSON
            $questionsPriorityJson = json_encode($questionsPriority);
            // Asignar y guardar 
            $project->questions_priority = $questionsPriorityJson;
            $project->save();
            // Asocia el usuario al proyecto
            $project->users()->attach($this->leader, ['leader' => true, 'programmer' => false, 'client' => false]);
            $project->users()->attach($this->programmer, ['leader' => false, 'programmer' => true, 'client' => false]);

            $backlog = new Backlog();
            $backlog->general_objective = $this->general_objective;
            $backlog->scopes = $this->scopes ?? null;
            $backlog->start_date = $this->start_date;
            $backlog->closing_date = $this->closing_date;
            $backlog->passwords = $this->passwords;
            $backlog->project_id = $project->id;
            $backlog->save();
            // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
            foreach ($this->files as $index => $fileArray) {
                // Verificar si $fileArray es null o está vacío
                if (is_null($fileArray) || empty($fileArray)) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'info',
                        'title' => 'No se selecciono al menos una imagen.',
                    ]);
                    continue; // Saltar al siguiente elemento del bucle si $fileArray es null o está vacío
                }
                // Accede al objeto TemporaryUploadedFile dentro del array
                $file = $fileArray[0];
                $fileExtension = $file->extension();
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                if (in_array($fileExtension, $extensionesImagen)) {
                    $maxSize = 5 * 1024 * 1024; // 5 MB
                    // Verificar el tamaño del archivo
                    if ($file->getSize() > $maxSize) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                        ]);
                        return;
                    }
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $file->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Procesar la imagen
                    $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                    // Redimensionar la imagen si es necesario
                    $image->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // Guardar la imagen temporalmente
                    $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                    $image->save(storage_path('app/' . $tempPath));
                    // Guardar la imagen redimensionada en el almacenamiento local
                    Storage::disk('backlogs')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                    // // Eliminar la imagen temporal
                    Storage::disk('local')->delete($tempPath);
                    // Guardar información de la imagen
                    $files = new BacklogFiles();
                    $files->backlog_id = $backlog->id;
                    $files->route = $fullNewFilePath;
                    $files->save();

                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Imagen guardada exitosamente.',
                    ]);
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'info',
                        'title' => 'El archivo no está en formato de imagen.',
                        'text' => 'Archivo: ' . $file->getClientOriginalName(),
                    ]);
                }
            }
        }

        $this->modalCreateEdit = false;
        $this->clearInputs();
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Proyecto creado',
        ]);
    }

    public function update($id)
    {
        try {
            $this->validate([
                'code' => 'required|numeric',
                'name' => 'required|max:255',
                'general_objective' => 'required|max:255',
                'start_date' => 'required|date|max:255',
                'closing_date' => 'required|date|max:255',
            ]);
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $backlog = Backlog::all()->where('project_id', $id)->first();
        if (isset($backlog)) {
            $backlogFiles = BacklogFiles::where('backlog_id', $backlog->id)->get();
            // No contiene archivos
            if ($backlogFiles->isEmpty()) {
                if (empty($this->files) || empty(array_filter($this->files))) {
                    if (empty($this->scopes)) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'Se requiere seleccionar o cargar al menos una imagen.',
                        ]);
                        return;
                    } else {
                        $project = Project::find($id);
                        $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                        $project->code = $this->code ?? $project->code;
                        $project->type = $this->type != null ? $this->type : $project->type;
                        $project->name = $this->name ?? $project->name;
                        $project->save();
                        // Primero, quita las relaciones existentes para estos roles
                        $project->users()->wherePivot('leader', true)->detach();
                        $project->users()->wherePivot('programmer', true)->detach();
                        // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                        $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                        $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                        $backlog->general_objective = $this->general_objective ?? $backlog->general_objective;
                        $backlog->scopes = $this->scopes ?? $backlog->scopes;
                        $backlog->start_date = $this->start_date ?? $backlog->start_date;
                        $backlog->closing_date = $this->closing_date ?? $backlog->closing_date;
                        $backlog->passwords = $this->passwords ?? $backlog->passwords;
                        $backlog->save();
                    }
                } else {
                    $project = Project::find($id);
                    $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                    $project->code = $this->code ?? $project->code;
                    $project->type = $this->type != null ? $this->type : $project->type;
                    $project->name = $this->name ?? $project->name;
                    $project->save();
                    // Primero, quita las relaciones existentes para estos roles
                    $project->users()->wherePivot('leader', true)->detach();
                    $project->users()->wherePivot('programmer', true)->detach();
                    // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                    $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                    $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                    $backlog->general_objective = $this->general_objective ?? $backlog->general_objective;
                    $backlog->scopes = $this->scopes ?? $backlog->scopes;
                    $backlog->start_date = $this->start_date ?? $backlog->start_date;
                    $backlog->closing_date = $this->closing_date ?? $backlog->closing_date;
                    $backlog->passwords = $this->passwords ?? $backlog->passwords;
                    $backlog->save();
                    // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
                    foreach ($this->files as $index => $fileArray) {
                        // Verificar si $fileArray es null o está vacío
                        if (is_null($fileArray) || empty($fileArray)) {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'No se selecciono al menos una imagen.',
                            ]);
                            continue; // Saltar al siguiente elemento del bucle si $fileArray es null o está vacío
                        }
                        // Accede al objeto TemporaryUploadedFile dentro del array
                        $file = $fileArray[0];
                        $fileExtension = $file->extension();
                        $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        if (in_array($fileExtension, $extensionesImagen)) {
                            $maxSize = 5 * 1024 * 1024; // 5 MB
                            // Verificar el tamaño del archivo
                            if ($file->getSize() > $maxSize) {
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'error',
                                    'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                                ]);
                                return;
                            }
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                            $fileName = $file->getClientOriginalName();
                            $fullNewFilePath = $filePath . '/' . $fileName;
                            // Procesar la imagen
                            $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                            // Redimensionar la imagen si es necesario
                            $image->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            // Guardar la imagen temporalmente
                            $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                            $image->save(storage_path('app/' . $tempPath));
                            // Guardar la imagen redimensionada en el almacenamiento local
                            Storage::disk('backlogs')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                            // // Eliminar la imagen temporal
                            Storage::disk('local')->delete($tempPath);
                            // Guardar información de la imagen
                            $files = new BacklogFiles();
                            $files->backlog_id = $backlog->id;
                            $files->route = $fullNewFilePath;
                            $files->save();

                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Imagen guardada exitosamente.',
                            ]);
                        } else {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'El archivo no está en formato de imagen.',
                                'text' => 'Archivo: ' . $file->getClientOriginalName(),
                            ]);
                        }
                    }
                }
            } else {
                if ($this->selectedFiles == []) {
                    $project = Project::find($id);
                    $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                    $project->code = $this->code ?? $project->code;
                    $project->type = $this->type != null ? $this->type : $project->type;
                    $project->name = $this->name ?? $project->name;
                    $project->save();
                    // Primero, quita las relaciones existentes para estos roles
                    $project->users()->wherePivot('leader', true)->detach();
                    $project->users()->wherePivot('programmer', true)->detach();
                    // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                    $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                    $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                    $backlog->general_objective = $this->general_objective ?? $backlog->general_objective;
                    $backlog->scopes = $this->scopes ?? $backlog->scopes;
                    $backlog->start_date = $this->start_date ?? $backlog->start_date;
                    $backlog->closing_date = $this->closing_date ?? $backlog->closing_date;
                    $backlog->passwords = $this->passwords ?? $backlog->passwords;
                    $backlog->save();
                } else {
                    // Contar la cantidad de archivos restantes
                    $remainingFilesCount = $backlogFiles->count();
                    // Eliminar los archivos seleccionados
                    foreach ($this->selectedFiles as $fileId) {
                        // Verificar si hay más de un archivo restante antes de eliminarlo
                        if ($remainingFilesCount > 1) {
                            // Buscar el archivo en la colección de archivos
                            $fileToDelete = $backlogFiles->where('id', $fileId)->first();
                            // Verificar si se encontró el archivo
                            if ($fileToDelete) {
                                // Eliminar el archivo físico si existe en el disco
                                if (Storage::disk('backlogs')->exists($fileToDelete->route)) {
                                    Storage::disk('backlogs')->delete($fileToDelete->route);
                                }
                                // Eliminar el archivo de la base de datos
                                $fileToDelete->delete();
                                // Disminuir el contador de archivos restantes
                                $remainingFilesCount--;
                                // Guardar backlog
                                $project = Project::find($id);
                                $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                                $project->code = $this->code ?? $project->code;
                                $project->type = $this->type != null ? $this->type : $project->type;
                                $project->name = $this->name ?? $project->name;
                                $project->priority = $this->priority ?? $project->priority;
                                $project->save();
                                // Primero, quita las relaciones existentes para estos roles
                                $project->users()->wherePivot('leader', true)->detach();
                                $project->users()->wherePivot('programmer', true)->detach();
                                // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                                $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                                $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                                $backlog->general_objective = $this->general_objective ?? $backlog->general_objective;
                                $backlog->scopes = $this->scopes ?? $backlog->scopes;
                                $backlog->start_date = $this->start_date ?? $backlog->start_date;
                                $backlog->closing_date = $this->closing_date ?? $backlog->closing_date;
                                $backlog->passwords = $this->passwords ?? $backlog->passwords;
                                $backlog->save();

                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Imagen eliminada.',
                                ]);
                            } else {
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'error',
                                    'title' => 'No se encontró la imagen.',
                                ]);
                            }
                        } elseif (empty($this->scopes)) {
                            // Esta vacio el textarea
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'warning',
                                'title' => 'Debe existir al menos una imagen asociada al backlog.',
                            ]);
                            return;
                        } else {
                            // Eliminar todos los archivos ya que tiene texto
                            // Buscar el archivo en la colección de archivos
                            $fileToDelete = $backlogFiles->where('id', $fileId)->first();
                            // Verificar si se encontró el archivo
                            if ($fileToDelete) {
                                // Eliminar el archivo físico si existe en el disco
                                if (Storage::disk('backlogs')->exists($fileToDelete->route)) {
                                    Storage::disk('backlogs')->delete($fileToDelete->route);
                                }
                                // Eliminar el archivo de la base de datos
                                $fileToDelete->delete();
                                // Disminuir el contador de archivos restantes
                                $remainingFilesCount--;
                                // Guardar backlog
                                $project = Project::find($id);
                                $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                                $project->code = $this->code ?? $project->code;
                                $project->type = $this->type != null ? $this->type : $project->type;
                                $project->name = $this->name ?? $project->name;
                                $project->save();
                                // Primero, quita las relaciones existentes para estos roles
                                $project->users()->wherePivot('leader', true)->detach();
                                $project->users()->wherePivot('programmer', true)->detach();
                                // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                                $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                                $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                                $backlog->general_objective = $this->general_objective ?? $backlog->general_objective;
                                $backlog->scopes = $this->scopes ?? $backlog->scopes;
                                $backlog->start_date = $this->start_date ?? $backlog->start_date;
                                $backlog->closing_date = $this->closing_date ?? $backlog->closing_date;
                                $backlog->passwords = $this->passwords ?? $backlog->passwords;
                                $backlog->save();

                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Imagen eliminada.',
                                ]);
                            } else {
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'error',
                                    'title' => 'No se encontró la imagen.',
                                ]);
                            }
                        }
                    }
                }

                if (!empty($this->files)) {
                    $project = Project::find($id);
                    $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                    $project->code = $this->code ?? $project->code;
                    $project->type = $this->type != null ? $this->type : $project->type;
                    $project->name = $this->name ?? $project->name;
                    $project->save();
                    // Primero, quita las relaciones existentes para estos roles
                    $project->users()->wherePivot('leader', true)->detach();
                    $project->users()->wherePivot('programmer', true)->detach();
                    // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                    $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                    $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                    $backlog->general_objective = $this->general_objective ?? $backlog->general_objective;
                    $backlog->scopes = $this->scopes ?? $backlog->scopes;
                    $backlog->start_date = $this->start_date ?? $backlog->start_date;
                    $backlog->closing_date = $this->closing_date ?? $backlog->closing_date;
                    $backlog->passwords = $this->passwords ?? $backlog->passwords;
                    $backlog->save();
                    // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
                    foreach ($this->files as $index => $fileArray) {
                        // Verificar si $fileArray es null o está vacío
                        if (is_null($fileArray) || empty($fileArray)) {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'No se selecciono al menos una imagen.',
                            ]);
                            continue; // Saltar al siguiente elemento del bucle si $fileArray es null o está vacío
                        }
                        // Accede al objeto TemporaryUploadedFile dentro del array
                        $file = $fileArray[0];
                        $fileExtension = $file->extension();
                        $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        if (in_array($fileExtension, $extensionesImagen)) {
                            $maxSize = 5 * 1024 * 1024; // 5 MB
                            // Verificar el tamaño del archivo
                            if ($file->getSize() > $maxSize) {
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'error',
                                    'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                                ]);
                                return;
                            }
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                            $fileName = $file->getClientOriginalName();
                            $fullNewFilePath = $filePath . '/' . $fileName;
                            // Procesar la imagen
                            $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                            // Redimensionar la imagen si es necesario
                            $image->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            // Guardar la imagen temporalmente
                            $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                            $image->save(storage_path('app/' . $tempPath));
                            // Guardar la imagen redimensionada en el almacenamiento local
                            Storage::disk('backlogs')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                            // // Eliminar la imagen temporal
                            Storage::disk('local')->delete($tempPath);
                            // Guardar información de la imagen
                            $files = new BacklogFiles();
                            $files->backlog_id = $backlog->id;
                            $files->route = $fullNewFilePath;
                            $files->save();

                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Imagen guardada exitosamente.',
                            ]);
                        } else {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'El archivo no está en formato de imagen.',
                                'text' => 'Archivo: ' . $file->getClientOriginalName(),
                            ]);
                        }
                    }
                }
            }
        } else {
            // Verificar si al menos uno de los campos está presente
            if (!$this->files && !$this->scopes) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Faltan los alcances.',
                ]);
                return;
            } else {
                if (empty($this->files) || empty(array_filter($this->files))) {
                    if (empty($this->scopes)) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'Se requiere seleccionar o cargar al menos una imagen.',
                        ]);
                        return;
                    } else {
                        $project = Project::find($id);
                        $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                        $project->code = $this->code ?? $project->code;
                        $project->type = $this->type != null ? $this->type : $project->type;
                        $project->name = $this->name ?? $project->name;
                        $project->save();
                        // Primero, quita las relaciones existentes para estos roles
                        $project->users()->wherePivot('leader', true)->detach();
                        $project->users()->wherePivot('programmer', true)->detach();
                        // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                        $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                        $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                        $backlog = new Backlog();
                        $backlog->general_objective = $this->general_objective;
                        $backlog->scopes = $this->scopes ?? null;
                        $backlog->start_date = $this->start_date;
                        $backlog->closing_date = $this->closing_date;
                        $backlog->passwords = $this->passwords;
                        $backlog->project_id = $project->id;
                        $backlog->save();
                    }
                } else {
                    $project = Project::find($id);
                    $project->customer_id = !empty($this->customer) && is_numeric($this->customer) ? $this->customer : $project->customer_id;
                    $project->code = $this->code ?? $project->code;
                    $project->type = $this->type != null ? $this->type : $project->type;
                    $project->name = $this->name ?? $project->name;
                    $project->save();
                    // Primero, quita las relaciones existentes para estos roles
                    $project->users()->wherePivot('leader', true)->detach();
                    $project->users()->wherePivot('programmer', true)->detach();
                    // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                    $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false, 'client' => false]]);
                    $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true, 'client' => false]]);

                    $backlog = new Backlog();
                    $backlog->general_objective = $this->general_objective;
                    $backlog->scopes = $this->scopes ?? null;
                    $backlog->start_date = $this->start_date;
                    $backlog->closing_date = $this->closing_date;
                    $backlog->passwords = $this->passwords;
                    $backlog->project_id = $project->id;
                    $backlog->save();
                    // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
                    foreach ($this->files as $index => $fileArray) {
                        // Verificar si $fileArray es null o está vacío
                        if (is_null($fileArray) || empty($fileArray)) {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'No se selecciono al menos una imagen.',
                            ]);
                            continue; // Saltar al siguiente elemento del bucle si $fileArray es null o está vacío
                        }
                        // Accede al objeto TemporaryUploadedFile dentro del array
                        $file = $fileArray[0];
                        $fileExtension = $file->extension();
                        $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                        if (in_array($fileExtension, $extensionesImagen)) {
                            $maxSize = 5 * 1024 * 1024; // 5 MB
                            // Verificar el tamaño del archivo
                            if ($file->getSize() > $maxSize) {
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'error',
                                    'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                                ]);
                                return;
                            }
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                            $fileName = $file->getClientOriginalName();
                            $fullNewFilePath = $filePath . '/' . $fileName;
                            // Procesar la imagen
                            $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                            // Redimensionar la imagen si es necesario
                            $image->resize(800, null, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                            // Guardar la imagen temporalmente
                            $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                            $image->save(storage_path('app/' . $tempPath));
                            // Guardar la imagen redimensionada en el almacenamiento local
                            Storage::disk('backlogs')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                            // // Eliminar la imagen temporal
                            Storage::disk('local')->delete($tempPath);
                            // Guardar información de la imagen
                            $files = new BacklogFiles();
                            $files->backlog_id = $backlog->id;
                            $files->route = $fullNewFilePath;
                            $files->save();

                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Imagen guardada exitosamente.',
                            ]);
                        } else {
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'info',
                                'title' => 'El archivo no está en formato de imagen.',
                                'text' => 'Archivo: ' . $file->getClientOriginalName(),
                            ]);
                        }
                    }
                }
            }
        }

        $this->modalCreateEdit = false;
        $this->allType = ['Activo', 'Soporte', 'Cerrado', 'Entregado', 'No activo'];
        $this->clearInputs();
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Proyecto actualizado.',
        ]);
    }

    public function updatePriority($id)
    {
        try {
            $this->validate([
                'severity' => 'required',
                'impact' => 'required',
                'satisfaction' => 'required',
                'temporality' => 'required',
                'magnitude' => 'required',
                'strategy' => 'required',
                'stage' => 'required',
            ]);
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $total = $this->severity + $this->impact + $this->satisfaction + $this->temporality + $this->magnitude + $this->strategy + $this->stage;

        $project = Project::find($id);
        if ($project) {
            if ($total >= 80 && $total <= 108) {
                $project->priority = 1;
            } elseif ($total >= 72 && $total <= 79) {
                $project->priority = 2;
            } elseif ($total >= 65 && $total <= 71) {
                $project->priority = 3;
            } elseif ($total >= 57 && $total <= 64) {
                $project->priority = 4;
            } elseif ($total >= 50 && $total <= 56) {
                $project->priority = 5;
            } elseif ($total >= 42 && $total <= 49) {
                $project->priority = 6;
            } elseif ($total >= 35 && $total <= 41) {
                $project->priority = 7;
            } elseif ($total >= 27 && $total <= 34) {
                $project->priority = 8;
            } elseif ($total >= 20 && $total <= 26) {
                $project->priority = 9;
            } elseif ($total >= 0 && $total <= 19) {
                $project->priority = 10;
            }
            // Crear un array asociativo con los valores
            $questionsPriority = [
                'severity' => $this->severity,
                'impact' => $this->impact,
                'satisfaction' => $this->satisfaction,
                'temporality' => $this->temporality,
                'magnitude' => $this->magnitude,
                'strategy' => $this->strategy,
                'stage' => $this->stage,
            ];
            // Convertir el array a JSON
            $questionsPriorityJson = json_encode($questionsPriority);
            // Asignar y guardar 
            $project->questions_priority = $questionsPriorityJson;
            $project->save();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Prioridad actualizada',
            ]);
            $this->clearInputs();
            $this->modalPriority = false;
            $this->showPriority = false;
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Proyecto no existe',
            ]);
        }
    }

    public function destroy($id)
    {
        $project = Project::find($id);

        if ($project) {
            $project->delete();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Proyecto eliminado',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Proyecto no existe',
            ]);
        }
    }

    public function restore($id)
    {
        $project = Project::withTrashed()->find($id);

        if ($project) {
            $project->restore();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Proyecto restaurado',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Proyecto no existe',
            ]);
        }
    }
    // INFO MODAL
    public function showUpdate($id)
    {
        $this->allType = ['Activo', 'Soporte', 'Cerrado', 'Entregado', 'No activo'];
        $this->showUpdate = true;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->projectEdit = Project::find($id);
        $this->code = $this->projectEdit->code;
        $this->name = $this->projectEdit->name;

        // TYPE PROJECT
        $this->type = $this->projectEdit ? $this->projectEdit->type : null;

        // CUSTOMERS
        $this->customers = Customer::all();
        $this->projectCustomer = $this->customers->find($this->projectEdit->customer_id);
        $this->customer = $this->projectCustomer ? $this->projectCustomer->id : null;

        // Aquí recuperas el líder y el programador actual del proyecto
        $leader = $this->projectEdit->users()->wherePivot('leader', true)->first();
        $programmer = $this->projectEdit->users()->wherePivot('programmer', true)->first();
        // Guarda los IDs para excluirlos de los selects
        $this->leader = $leader ? $leader->id : null;
        $this->programmer = $programmer ? $programmer->id : null;

        // BACKLOG
        $this->backlogEdit = Backlog::all()->where('project_id', $id)->first();
        $this->general_objective = $this->backlogEdit->general_objective ?? '';
        $this->scopes = $this->backlogEdit->scopes ?? '';

        $start_date = Carbon::parse($this->backlogEdit->start_date ?? '');
        $this->start_date = $start_date->toDateString();
        $closing_date = Carbon::parse($this->backlogEdit->closing_date ?? '');
        $this->closing_date = $closing_date->toDateString();

        $this->passwords = $this->backlogEdit->passwords ?? '';
    }

    public function showPriority($id)
    {
        $this->showPriority = true;

        if ($this->modalPriority == true) {
            $this->modalPriority = false;
            $this->showPriority = false;
        } else {
            $this->modalPriority = true;
        }

        $this->projectPriority = Project::find($id);

        if ($this->projectPriority) {
            $questionsPriority = json_decode($this->projectPriority->questions_priority, true);

            $this->severity = $questionsPriority['severity'] ?? null;
            $this->impact = $questionsPriority['impact'] ?? null;
            $this->satisfaction = $questionsPriority['satisfaction'] ?? null;
            $this->temporality = $questionsPriority['temporality'] ?? null;
            $this->magnitude = $questionsPriority['magnitude'] ?? null;
            $this->strategy = $questionsPriority['strategy'] ?? null;
            $this->stage = $questionsPriority['stage'] ?? null;
        }
    }
    // MODAL
    public function modalCreateEdit()
    {
        $this->showUpdate = false;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }
        $this->clearInputs();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('file-reset');
    }

    public function modalPriority()
    {
        if ($this->modalPriority == true) {
            $this->modalPriority = false;
            $this->showPriority = false;
        } else {
            $this->modalPriority = true;
        }

        $this->clearInputs();
        $this->resetErrorBag();
    }
    // EXTRAS
    public function addInput()
    {
        $this->files[] = null;
    }

    public function removeInput($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files); // Reindexar el array
    }

    public function showReports($project_id)
    {
        return redirect()->route('projects.reports.index', ['project' => $project_id]);
    }

    public function showActivities($project_id)
    {
        return redirect()->route('projects.activities.index', ['project' => $project_id]);
    }

    public function showProjectPriority()
    {
        return redirect()->route('priority.index');
    }

    public function clearInputs()
    {
        $this->code = '';
        $this->name = '';
        $this->type = '';
        $this->priority = '';
        $this->customer = '';
        $this->leader = '';
        $this->programmer = '';
        $this->allType = ['Activo', 'Soporte', 'Cerrado', 'Entregado', 'No activo'];
        $this->general_objective = '';
        $this->files = [];
        $this->selectedFiles = [];
        $this->scopes = '';
        $this->start_date = '';
        $this->closing_date = '';
        $this->passwords = '';
        $this->severity = '';
        $this->impact = '';
        $this->satisfaction = '';
        $this->temporality = '';
        $this->magnitude = '';
        $this->strategy = '';
        $this->stage = '';
        $this->dispatchBrowserEvent('file-reset');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->typeProject = $tab;
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
