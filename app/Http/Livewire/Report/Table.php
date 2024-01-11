<?php

namespace App\Http\Livewire\Report;

use App\Models\Report;
use Livewire\Component;

class Table extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];

    public $search, $reportDelete;

    public $modalDelete = false;
    public $showDelete = false;

    public $perPage = '25';
    public $rules = [];

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $reports = Report::where(function ($query) {
            $query->where('tittle', 'like', '%' . $this->search . '%')
                ->orWhere('state', 'like', '%' . $this->search . '%')
                ->orWhere('value', 'like', '%' . $this->search . '%')
                ->orWhere('reports.url', 'ilike', '%' . $this->search . '%');
        })
            ->paginate($this->perPage);

        return view('livewire.report.table', [
            'reports' => $reports,
        ]);
    }

    public function create()
    {
        return redirect()->route('reports.create');
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
