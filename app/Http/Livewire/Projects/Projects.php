<?php

namespace App\Http\Livewire\Projects;

use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Livewire\Component;

class Projects extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalCreateEdit = false, $modalDelete = false, $modalRestore = false;
    public $showUpdate = false, $showDelete = false, $showRestore = false;
    // table, action's user
    public $search, $projectCustomer, $projectEdit, $projectDelete, $projectRestore;
    public $perPage = '25';
    public $rules = [], $allCustomers = [], $selectedLeaders = [];
    // inputs
    public $name, $type, $priority, $customer;

    public function render()
    {

        $customers = Customer::all();
        $leaders = User::all();

        $projects = Project::onlyTrashed()
            ->select('projects.*', 'customers.name as customer_name')
            ->leftJoin('customers', 'projects.customer_id', '=', 'customers.id')
            ->where('projects.type', 'like', '%' . $this->search . '%')
            ->orwhere('projects.name', 'like', '%' . $this->search . '%')
            ->orWhere('projects.priority', 'like', '%' . $this->search . '%')
            ->with(['users' => function ($query) {
                $query->wherePivot('leader', true);
            }])
            ->paginate($this->perPage);

        return view('livewire.projects.projects', [
            'projects' => $projects,
            'customers' => $customers,
            'leaders' => $leaders,
        ]);
    }

    public function create()
    {
        $this->rules = [
            'name' => 'required|max:255',
            'type' => 'required|max:255',
            'priority' => 'required|numeric|between:0,99',
            'customer' => 'required',
            'selectedLeaders' => 'required',
        ];
        $this->validate();

        $project = new Project();
        $project->customer_id = $this->customer;
        $project->type = $this->type;
        $project->name = $this->name;
        $project->priority = $this->priority;

        $project->save();

        // Adjuntar usuarios al proyecto
        foreach ($this->selectedLeaders as $userId => $isLeader) {
            $project->users()->attach($userId, ['leader' => $isLeader]);
        }

        $this->modalCreateEdit = false;
        $this->emit('reloadPage');
    }

    public function update($id)
    {
        dd($this);
        $this->rules = [
            'name' => 'max:255',
            'type' => 'max:255',
            'priority' => 'numeric|between:0,99',
            'customer' => '',
            'selectedLeaders' => '',
        ];
        $this->validateOnly($id, $this->rules);

        $project = Project::find($id);
        $project->customer_id = $this->customer;
        $project->type = $this->type;
        $project->name = $this->name;
        $project->priority = $this->priority;
        $project->save();

        // Adjuntar usuarios al proyecto
        foreach ($this->selectedLeaders as $userId => $isLeader) {
            $project->users()->attach($userId, ['leader' => $isLeader]);
        }

        $this->modalCreateEdit = false;
        $this->emit('reloadPage');
    }

    public function destroy($id)
    {
        $user = Project::find($id);

        if ($user) {
            $user->delete();
        }

        $this->modalDelete = false;
        $this->emit('reloadPage');
    }

    public function restore($id)
    {
        $user = Project::withTrashed()->find($id);

        if ($user) {
            $user->restore();
        }

        $this->modalRestore = false;
        $this->emit('reloadPage');
    }

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
        $this->showUpdate = true;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->projectEdit = Project::find($id);
        $this->allCustomers = Customer::all();
        $this->projectCustomer = $this->allCustomers->find($this->projectEdit->customer_id);

        foreach ($this->allCustomers as $key => $oneCustomer) {
            if ($oneCustomer->name === $this->projectCustomer->name) {
                unset($this->allCustomers[$key]);
            }
        }
    }

    public function modalCreateEdit()
    {
        $this->showUpdate = false;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
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

    public function modalRestore()
    {
        if ($this->modalRestore == true) {
            $this->modalRestore = false;
        } else {
            $this->modalRestore = true;
        }
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
