<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Models\Activity;
use App\Models\Backlog;
use App\Models\Sprint;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class BacklogSprintsClient extends Component
{
    public $listeners = ['activityUpdated' => 'recalculatePercentageResolved', 'destroySprint',  'sprintCreated' => 'sprintsUpdated'];
    protected $paginationTheme = 'tailwind';
    // ENVIADAS
    public $project, $backlog;
    public $highlightedSprint = null;
    // SPRINT
    public $sprints, $firstSprint, $selectSprint;
    public $changeSprint = false;
    public $filteredState = [];
    // MODAL BACKLOG
    public $showBacklog;
    // MODAL SPRINT
    public $newSprint = false, $updateSprint = false;
    public $sprintEdit;
    // inputs
    public $name_sprint, $start_date, $end_date;
    public $sprintState, $startDate, $endDate, $idSprint, $percentageResolved;

    public function mount()
    {
        $this->highlightedSprint = request()->get('sprint'); // Obtener sprint de la URL
    }

    public function render()
    {
        // Obtener los sprints y ordenarlos por el número de sprint
        $backlog = $this->backlog;
        // Verificar si el archivo existe en la base de datos
        $filesBacklog = [];
        if ($backlog && $backlog->files) {
            foreach ($backlog->files as $key => $file) {
                // Verificar si el archivo existe en la carpeta
                $filePath = public_path('backlogs/' . $file->route);
                $filesBacklog[] = file_exists($filePath) ? $file->route : false;
            }
        }

        $this->backlog->filesBacklog = $filesBacklog;

        if (!$this->sprints) {
            $this->sprints = $this->backlog->sprints->sortBy('number');
        }

        if ($this->sprints->isNotEmpty()) {
            // PRIMERO SPRINT
            if (!$this->changeSprint) {
                if ($this->highlightedSprint) {
                    // Buscar el sprint de la URL
                    $this->firstSprint = Sprint::find($this->highlightedSprint);
                    // $this->selectSprint = $this->highlightedSprint;
                    // $this->filteredState = $this->getFilteredStates($firstSprint->state);
                } else {
                    // Buscar el primer sprint con estado 'Curso'
                    $courseSprint = $this->sprints->firstWhere('state', 'Curso');
                    // Si no se encontró ningún sprint con estado 'Curso', buscar el primer sprint con estado 'Curso'
                    if (!$courseSprint) {
                        $pendingSprint = $this->sprints->firstWhere('state', 'Pendiente');
                        // Si se encontró un sprint con estado 'Pendiente', asignarlo a $this->firstSprint
                        if ($pendingSprint) {
                            $this->firstSprint = $pendingSprint;
                        } else {
                            // Si no se encontró ningún sprint con estado 'Curso' o 'Pendiente', asignar el primer sprint de la colección
                            $this->firstSprint = $this->sprints->first();
                        }
                    } else {
                        // Si se encontró un sprint con estado 'Curso', asignarlo a $this->firstSprint
                        $this->firstSprint = $courseSprint;
                    }
                }
                $this->selectSprint = $this->firstSprint->id;
                $this->filteredState = $this->getFilteredStates($this->firstSprint->state);
            }
            // SELECT SPRINT
            $this->updateSprintData($this->selectSprint);
            $this->filteredState = $this->getFilteredStates($this->sprintState);
            $this->firstSprint = Sprint::find($this->selectSprint);
        } else {
            $this->selectSprint = null;
        }
        // COUNT ACTIVITIES
        $totalActivities = Activity::where('sprint_id', $this->selectSprint)->count(); // Contar el número total de actividades del sprint
        $allActivities = Activity::where('sprint_id', $this->selectSprint)->get(); // Seleccionar todas las actividades del sprint
        $sprint = Sprint::find($this->selectSprint);
        if ($totalActivities) {
            if ($sprint->state == 'Curso' || $sprint->state == 'Cerrado') {
                $resolvedActivities = $allActivities->where('state', 'Resuelto')->count(); // Contar el número de actividades resueltas
                if ($totalActivities > 0) {
                    $this->percentageResolved = ($resolvedActivities / $totalActivities) * 100; // Calcular el porcentaje de actividades resueltas sobre el total de actividades
                    $this->percentageResolved = round($this->percentageResolved, 2); // Redondear el porcentaje a dos decimales
                    // Verificar si todas las actividades están en estado "Resuelto"
                    $allResolved = $allActivities->every(function ($activity) {
                        return $activity->state === 'Resuelto';
                    });
                } else {
                    // Manejo alternativo si $totalActivities es igual a cero
                    $this->percentageResolved = 0;
                }
            } else {
                // Manejo alternativo state es diferente
                $this->percentageResolved = 0;
            }
        } else {
            // Manejo alternativo si $totalActivities no existe
            $this->percentageResolved = 0;
        }

        return view('livewire.projects.activities.backlog-sprints-client', [
            'sprints' => $this->sprints,
        ]);
    }
    // MODAL
    public function showBacklog()
    {
        if ($this->showBacklog == true) {
            $this->showBacklog = false;
        } else {
            $this->showBacklog = true;
        }
    }

    public function newSprint()
    {
        // Lógica para alternar la ventana de creación
        $this->updateSprint = false;
        $this->newSprint = !$this->newSprint;
        // Limpia los inputs y errores
        $this->clearInputs();
        $this->resetErrorBag();
    }

    public function editSprint($id)
    {
        $this->updateSprint = true;

        ($this->newSprint == true) ? $this->newSprint = false :  $this->newSprint = true;

        $this->sprintEdit = Sprint::find($id);
        $this->name_sprint = $this->sprintEdit->name;

        $fecha_start = Carbon::parse($this->sprintEdit->start_date);
        $this->start_date = $fecha_start->toDateString();
        $fecha_end = Carbon::parse($this->sprintEdit->end_date);
        $this->end_date = $fecha_end->toDateString();
    }
    // ACCIONES EXTRAS
    public function sprintsUpdated()
    {
        $this->sprints = Backlog::find($this->backlog->id)->sprints->sortBy('number');
    }

    public function selectSprint($id)
    {
        $this->changeSprint = true;
        $this->updateSprintData($id);
        $this->selectSprint = $id;
        $this->emit('sprintsUpdated');
    }

    public function recalculatePercentageResolved()
    {
        $this->calculatePercentageResolved();
    }
    // PROTECTED
    protected function updateSprintData($id)
    {
        $sprint = Sprint::find($id);

        if ($sprint) {
            $this->startDate = $sprint->start_date;
            $this->endDate = $sprint->end_date;
            $this->sprintState = $sprint->state;
            $this->idSprint = $sprint->id;
        }
    }

    protected function getFilteredStates($currentState)
    {
        $actions = ['Pendiente', 'Curso', 'Cerrado'];

        if ($currentState == 'Pendiente') {
            return ['Curso'];
        }

        if ($currentState == 'Curso' || $currentState == 'Cerrado') {
            return [];
        }

        // En cualquier otro caso, elimina el estado actual del arreglo
        return array_filter($actions, function ($action) use ($currentState) {
            return $action != $currentState;
        });
    }

    protected function calculatePercentageResolved()
    {
        $totalActivities = Activity::where('sprint_id', $this->selectSprint)->count();
        $allActivities = Activity::where('sprint_id', $this->selectSprint)->get();
        $sprint = Sprint::find($this->selectSprint);

        if ($totalActivities && Auth::user()->type_user == 1) {
            $resolvedActivities = $allActivities->where('state', 'Resuelto')->count();

            if ($totalActivities > 0) {
                $this->percentageResolved = round(($resolvedActivities / $totalActivities) * 100, 2);

                $allResolved = $allActivities->every(function ($activity) {
                    return $activity->state === 'Resuelto';
                });

            } else {
                $this->percentageResolved = 0;
            }
        } else {
            $this->percentageResolved = 0;
        }
    }
}
