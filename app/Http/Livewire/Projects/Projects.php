<?php

namespace App\Http\Livewire\Projects;

use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class Projects extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';
    
    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalCreateEdit = false, $modalDelete = false, $modalRestore = false;
    public $showUpdate = false, $showDelete = false, $showRestore = false;
    // table, action's user
    public $search, $projectCustomer, $projectEdit, $projectDelete, $projectRestore, $filteredType;
    public $perPage = '10';
    public $customers = [], $allType = ['Activo', 'Soporte', 'Resolución Piloto', 'Entregado seguimiento', 'No activo seguimiento'];
    // inputs
    public $code, $name, $type, $priority, $customer, $leader, $programmer;
    
    public function render()
    {
        $allCustomers = Customer::all();
        $allUsers = User::all();

        if (Auth::user()->type_user == 1) {
            $projects = Project::select('projects.*', 'customers.name as customer_name')
            ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
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

            $projects = Project::select('projects.*', 'customers.name as customer_name')
            ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
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

        $this->modalCreateEdit = false;
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

        $this->modalCreateEdit = false;
        $this->allType = ['Activo', 'Soporte', 'Resolución Piloto', 'Entregado seguimiento', 'No activo seguimiento'];

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
                'type' => 'success',
                'title' => 'Proyecto no existe',
            ]);
        }

        $this->modalDelete = false;
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

        $this->modalRestore = false;
    }
    // INFO MODAL
    public function showDelete($id)
    {
        $this->showDelete = true;

        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
        }

        $this->projectDelete = Project::find($id);
    }
    
    public function showRestore($id)
    {
        $this->showRestore = true;

        if ($this->modalRestore == true) {
            $this->modalRestore = false;
        } else {
            $this->modalRestore = true;
        }

        $this->projectRestore = Project::withTrashed()->find($id);
    }

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
    }

    public function modalDelete()
    {
        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
        }
    }

    public function modalRestore()
    {
        if ($this->modalRestore == true) {
            $this->modalRestore = false;
        } else {
            $this->modalRestore = true;
        }
        $this->resetErrorBag();
    }
    // EXTRAS

    public function showReports($project_id)
    {
        return redirect()->route('projects.reports.index', ['project' => $project_id]);
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
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
