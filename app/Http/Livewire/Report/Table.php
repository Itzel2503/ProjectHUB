<?php

namespace App\Http\Livewire\Report;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Table extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalCreateEdit = false, $modalDelete = false, $modalRestore = false;
    public $showUpdate = false, $showDelete = false, $showRestore = false;
    // table, action's user
    public $leader = false;
    public $search, $project, $reportDelete;
    public $perPage = '25';
    public $rules = [];
    // inputs
    public $name, $type, $priority, $customer;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');
        
        $reports = Report::where(function ($query) {
            $query->where('tittle', 'like', '%' . $this->search . '%')
                ->orWhere('comment', 'like', '%' . $this->search . '%')
                ->orWhere('state', 'like', '%' . $this->search . '%');
        })->paginate($this->perPage);

        $user = Auth::user();
        foreach ($this->project->users as $projectUser) {
            if ($projectUser->id === $user->id && $projectUser->pivot->leader) {
                $this->leader = true;
                break;
            }
        }

        return view('livewire.report.table', [
            'reports' => $reports,
        ]);
    }

    public function create($id_project)
    {
        return redirect()->route('reports.create', ['id_project' => $id_project]);
    }

    public function destroy($id)
    {
        $report = Report::find($id);

        if ($report) {
            $report->delete();
        }

        $this->modalDelete = false;
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

        $this->reportDelete = Report::find($id);
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
