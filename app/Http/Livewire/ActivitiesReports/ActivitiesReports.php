<?php

namespace App\Http\Livewire\ActivitiesReports;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class ActivitiesReports extends Component
{
    protected $paginationTheme = 'tailwind';
    
    protected $listeners = ['refreshChart'];
    // PESTAÑA
    public $activeTab = 'actividades';
    // DUKKE
    public $dukke = '';

    public function mount()
    {
        $user = Auth::user();

        $this->activeTab = 'task';
        $this->dukke = Project::where('id', 5)->first();
    }

    public function render()
    {
        return view('livewire.activities-reports.activities-reports');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function refreshChart($categories, $series, $totalEffortPoints)
    {
        $this->dispatchBrowserEvent('update-chart', [
            'categories' => $categories,
            'series' => $series,
            'totalEffortPoints' => $totalEffortPoints,
        ]);
    }
}
