<?php

namespace App\Http\Livewire\Projects;

use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TableReports extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalShow = false;
    public $showReport = false;
    // table, action's user
    public $leader = false;
    public $search, $project, $reportShow;
    public $perPage = '8';
    public $rules = [];
    // inputs
    public $name, $type, $priority, $customer;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $user = Auth::user();
        
        $reports = Report::where('project_id', $this->project->id)
        ->where(function ($query) {
            $query->where('title', 'like', '%' . $this->search . '%')
                ->orWhere('comment', 'like', '%' . $this->search . '%')
                ->orWhere('state', 'like', '%' . $this->search . '%');
        })
        ->with(['user', 'delegate'])
        ->paginate($this->perPage);
        
        foreach ($this->project->users as $projectUser) {
            if ($projectUser->id === $user->id && $projectUser->pivot->leader) {
                $this->leader = true;
                break;
            }
        }

        return view('livewire.projects.table-reports', [
            'reports' => $reports,
        ]);
    }

    public function create($project_id)
    {
        return redirect()->route('projects.reports.create', ['project' => $project_id]);
    }

    public function show($report_id)
    {   
        return redirect()->route('reports.show', ['project_id' => $this->project->id, 'report_id' => $report_id]);
    }

    public function showReport($id)
    {
        $this->showReport = true;

        if ($this->modalShow == true) {
            $this->modalShow = false;
        } else {
            $this->modalShow = true;
        }

        $this->reportShow = Report::find($id);
    }

    public function modalShow()
    {
        if ($this->modalShow == true) {
            $this->modalShow = false;
        } else {
            $this->modalShow = true;
        }
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
