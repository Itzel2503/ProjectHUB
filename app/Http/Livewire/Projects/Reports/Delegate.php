<?php

namespace App\Http\Livewire\Projects\Reports;

use Livewire\Component;

class Delegate extends Component
{
    public $delegate, $report;

    public function render()
    {
        
        return view('livewire.projects.reports.delegate');
    }
}
