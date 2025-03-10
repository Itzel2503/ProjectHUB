<?php

namespace App\Http\Livewire\ActivitiesReports;

use App\Models\ErrorLog;
use App\Models\Layout;
use App\Models\Log;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class ActivitiesReports extends Component
{
    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'refreshChart',
    ];
    // PESTAÑA
    public $activeTab = 'actividades';
    // DUKKE
    public $dukke = '';
    // MODO KANVAN
    public $mode = false, $seeProjects = false;

    public function mount()
    {
        $this->activeTab = 'task';
        $this->dukke = Project::where('id', 5)->first();
        // Obtener el estado del modo Kanban según los registros del usuario autenticado
        $this->mode = auth()->user()->layouts()->where('kanvan', true)->exists();
    }

    public function render()
    {
        return view('livewire.activities-reports.activities-reports');
    }

    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;
        $this->seeProjects = false;
        $this->updatedSeeProjects(false);
    }

    public function refreshChart($categories, $series, $totalEffortPoints)
    {
        $this->dispatchBrowserEvent('update-chart', [
            'categories' => $categories,
            'series' => $series,
            'totalEffortPoints' => $totalEffortPoints,
        ]);
    }

    public function updatedSeeProjects($value) {
        // Emitir un evento al componente hijo con el valor de seeProjects
        $this->emit('seeProjectsUpdated', $value);
    }

    public function updatedMode($value)
    {
        $user = Auth::user()->id;

        try {
            // Buscar si ya existe un registro en la tabla layouts
            $layout = Layout::where('user_id', $user)->where('view', $this->activeTab)->first();

            if ($layout) {
                $layout->kanvan = $value;
                $layout->save();

                Log::create([
                    'user_id' => Auth::id(),
                    'view' => 'livewire/activities-reports/activities-reports',
                    'action' => 'Mode Kanban',
                    'message' => 'Modo kanva actualizado',
                    'details' => 'Vista ' . $this->activeTab . ': ' . $value,
                ]);
            } else {
                $layout = new Layout();
                $layout->user_id = $user;
                $layout->view = $this->activeTab;
                $layout->kanvan = $value;
                $layout->save();

                $this->seeProjects = false;

                Log::create([
                    'user_id' => Auth::id(),
                    'view' => 'livewire/activities-reports/activities-reports',
                    'action' => 'Mode Kanban',
                    'message' => 'Modo kanva agregado',
                    'details' => 'Vista ' . $this->activeTab . ': ' . $value,
                ]);
            }
            // Para actualizar vista hijo
            $this->emit('modeUpdated', $value);
            // Emitir evento para inicializar el JavaScript
            $this->dispatchBrowserEvent('initializeScrollButtons');
            $this->dispatchBrowserEvent('initializeSortableJS');

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Modo kanva actualizado.',
            ]);
        } catch (\Exception $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/activities-reports/activities-reports',
                'action' => 'Mode Kanban',
                'message' => 'Error al actualizar el modo Kanban',
                'details' => $e->getMessage(), // Mensaje de la excepción
            ]);
    
            // Emitir un evento de navegador para mostrar un mensaje de error
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error',
                'text' => 'Error al actualizar el modo Kanban: ' . $e->getMessage(),
            ]);
        }
    }
}
