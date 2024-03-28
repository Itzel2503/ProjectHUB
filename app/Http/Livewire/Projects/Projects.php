<?php

namespace App\Http\Livewire\Projects;

use App\Models\Backlog;
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
    // modal
    public $modalCreateEdit = false, $createBacklog = false;
    public $showUpdate = false;
    // table, action's user
    public $scopesDisabled = false, $changeScopes = false;
    public $search, $projectCustomer, $projectEdit, $filteredType, $backlogEdit;
    public $perPage = '10';
    public $customers = [], $allType = ['Activo', 'Soporte', 'Resolución Piloto', 'Entregado seguimiento', 'No activo seguimiento'];
    // inputs
    public $code, $name, $type, $priority, $customer, $leader, $programmer, $general_objective, $scopes, $file, $start_date, $closing_date, $passwords;
    
    public function render()
    {
        $allCustomers = Customer::all();
        $allUsers = User::all();
        $backlogs = Backlog::all();

        if (Auth::user()->type_user == 1) {
            $projects = Project::select(
                'projects.*', 
                'customers.name as customer_name',
                'backlogs.id as backlog'
                )
                ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
                ->leftJoin('backlogs', 'projects.id', '=', 'backlogs.project_id')
                ->withTrashed()
                ->where(function ($query) {
                    $query->where('customers.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.code', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.type', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.priority', 'like', '%' . $this->search . '%');
                })
                ->with(['users' => function ($query) {
                    $query->withPivot('leader', 'programmer');
                }])
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        } else {
            $projects = Project::select(
                'projects.*', 
                'customers.name as customer_name',
                'backlogs.id as backlog'
                )
                ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
                ->leftJoin('backlogs', 'projects.id', '=', 'backlogs.project_id')
                ->where(function ($query) {
                    $query->where('customers.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.code', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.type', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.name', 'like', '%' . $this->search . '%')
                        ->orWhere('projects.priority', 'like', '%' . $this->search . '%');
                })
                ->with(['users' => function ($query) {
                    $query->withPivot('leader', 'programmer');
                }])
                ->orderBy('created_at', 'desc')
                ->paginate($this->perPage);
        }

        // ADD ATRIBUTES
        foreach ($projects as $project) {
            // Encuentra al líder y al programador dentro de los usuarios relacionados
            $leader = $project->users->where('pivot.leader', true)->first();
            $programmer = $project->users->where('pivot.programmer', true)->first();
        
            $project->leader = $leader;
            $project->programmer = $programmer;
        }

        return view('livewire.projects.projects', [
            'projects' => $projects,
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
                'priority' => 'required|numeric|between:0,99',
                'customer' => 'required',
                'leader' => 'required',
                'programmer' => 'required',
                'general_objective' => 'required|max:255',
                'file' => 'nullable',
                'scopes' => 'nullable',
                'start_date' => 'required|date|max:255',
                'closing_date' => 'required|date|max:255',
            ]);

            // Verificar si al menos uno de los campos está presente
            if (!$this->file && !$this->scopes) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Se requiere al menos un archivo o los alcances.',
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

        $project = new Project();
        $project->customer_id = $this->customer;
        $project->code = $this->code;
        $project->type = $this->type;
        $project->name = $this->name;
        $project->priority = $this->priority;
        $project->save();

        // Asocia el usuario al proyecto
        $project->users()->attach($this->leader, ['leader' => true, 'programmer' => false]);
        $project->users()->attach($this->programmer, ['leader' => false, 'programmer' => true]);

        $backlog = new Backlog();

        if ($this->file) {
            $fileExtension = $this->file->extension();
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
            if (in_array($fileExtension, $extensionesImagen)) {
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                $fileName = $this->file->getClientOriginalName();
                $fullNewFilePath = $filePath . '/' . $fileName;
                // Guardar el archivo en el disco 'backlogs'
                $this->file->storeAs($filePath, $fileName, 'backlogs');
                $backlog->file = $fullNewFilePath;
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El archivo debe ser una imagen',
                ]);
                return;
            }
        }

        $backlog->general_objective = $this->general_objective;
        $backlog->scopes = $this->scopes;
        $backlog->start_date = $this->start_date;
        $backlog->closing_date = $this->closing_date;
        $backlog->passwords = $this->passwords;
        $backlog->project_id = $project->id;
        $backlog->save();

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
                'priority' => 'required|numeric|between:0,99',
                'general_objective' => 'required|max:255',
                'file' => 'nullable|file',
                'scopes' => 'nullable',
                'start_date' => 'required|date|max:255',
                'closing_date' => 'required|date|max:255',
            ]);
            // Verificar si al menos uno de los campos está presente
            if (!$this->file && !$this->scopes) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Se requiere al menos un archivo o los alcances.',
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
        $project = Project::find($id);
        $project->customer_id = (!empty($this->customer) && is_numeric($this->customer)) ? $this->customer : $project->customer_id;
        $project->code = $this->code ?? $project->code;
        $project->type = ($this->type != null) ? $this->type : $project->type ;
        $project->name = $this->name ?? $project->name;
        $project->priority = $this->priority ?? $project->priority;
        $project->save();
        
        // Primero, quita las relaciones existentes para estos roles
        $project->users()->wherePivot('leader', true)->detach();
        $project->users()->wherePivot('programmer', true)->detach();

        // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
        $project->users()->syncWithoutDetaching([$this->leader => ['leader' => true, 'programmer' => false]]);
        $project->users()->syncWithoutDetaching([$this->programmer => ['leader' => false, 'programmer' => true]]);
        
        $backlog = Backlog::all()->where('project_id', $id)->first();

        if (isset($backlog)) {
            if ($this->file) {
                $fileExtension = $this->file->extension();
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                if (in_array($fileExtension, $extensionesImagen)) {
                    
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Verificar y eliminar el archivo anterior si existe y coincide con la nueva ruta
                    if ($backlog->file && Storage::disk('backlogs')->exists($backlog->file)) {
                        $existingFilePath = pathinfo($backlog->file, PATHINFO_DIRNAME);
                        if ($existingFilePath == $filePath) {
                            Storage::disk('backlogs')->delete($backlog->file);
                        }
                    }
                    // Guardar el archivo en el disco 'reports'
                    $this->file->storeAs($filePath, $fileName, 'backlogs');
                    $backlog->file = $fullNewFilePath;
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo debe ser una imagen',
                    ]);
                    return;
                }
            }

            $backlog->general_objective = $this->general_objective ?? $backlog->general_objective;
            $backlog->scopes = $this->scopes ?? $backlog->scopes;
            $backlog->start_date = $this->start_date ?? $backlog->start_date;
            $backlog->closing_date = $this->closing_date ?? $backlog->closing_date;
            $backlog->passwords = $this->passwords ?? $backlog->passwords;
            $backlog->save();
        } else {
            $backlog = new Backlog();

            if ($this->file) {
                $fileExtension = $this->file->extension();
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                if (in_array($fileExtension, $extensionesImagen)) {
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Guardar el archivo en el disco 'activities'
                    $this->file->storeAs($filePath, $fileName, 'backlogs');
                    $backlog->file = $fullNewFilePath;
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo debe ser una imagen',
                    ]);
                    return;
                }
            }

            $backlog->general_objective = $this->general_objective;
            $backlog->scopes = $this->scopes;
            $backlog->start_date = $this->start_date;
            $backlog->closing_date = $this->closing_date;
            $backlog->passwords = $this->passwords;
            $backlog->project_id = $project->id;
            $backlog->save();
        }

        $this->modalCreateEdit = false;
        $this->allType = ['Activo', 'Soporte', 'Resolución Piloto', 'Entregado seguimiento', 'No activo seguimiento'];
        $this->clearInputs();
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Proyecto actualizado',
        ]);
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
        $this->allType = ['Activo', 'Soporte', 'Resolución Piloto', 'Entregado seguimiento', 'No activo seguimiento'];
        $this->showUpdate = true;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->projectEdit = Project::find($id);
        $this->code = $this->projectEdit->code;
        $this->name = $this->projectEdit->name;
        $this->priority = $this->projectEdit->priority;

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
        $closing_date = Carbon::parse($this->backlogEdit->closing_date  ?? '');
        $this->closing_date = $closing_date->toDateString();

        $this->passwords = $this->backlogEdit->passwords ?? '';
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
    // EXTRAS
    public function showReports($project_id)
    {
        return redirect()->route('projects.reports.index', ['project' => $project_id]);
    }

    public function showActivities($project_id)
    {
        return redirect()->route('projects.activities.index', ['project' => $project_id]);
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
        $this->filteredType = '';
        $this->allType = ['Activo', 'Soporte', 'Resolución Piloto', 'Entregado seguimiento', 'No activo seguimiento'];
        $this->general_objective = '';
        $this->scopes = '';
        $this->start_date = '';
        $this->closing_date = '';
        $this->passwords = '';
        $this->dispatchBrowserEvent('file-reset');
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
