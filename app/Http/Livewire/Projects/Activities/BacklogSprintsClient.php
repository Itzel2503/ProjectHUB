<?php

namespace App\Http\Livewire\Projects\Activities;

use App\Models\Activity;
use App\Models\Backlog;
use App\Models\Sprint;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Livewire\Component;

class BacklogSprintsClient extends Component
{
    public $listeners = ['activityUpdated' => 'recalculatePercentageResolved', 'destroySprint'];
    protected $paginationTheme = 'tailwind';
    // ENVIADAS
    public $project, $backlog;
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
                if (file_exists($filePath)) {
                    $filesBacklog[] = $file->route;
                } else {
                    $filesBacklog[] = false;
                }
            }
        }
        $this->backlog->filesBacklog = $filesBacklog;
        if (!$this->sprints) {
            $this->sprints = $this->backlog->sprints->sortBy('number');
        }

        if ($this->sprints->isNotEmpty()) {
            // PRIMERO SPRINT
            if (!$this->changeSprint) {
                // Buscar el primer sprint con estado 'Curso'
                $courseSprint = $this->sprints->firstWhere('state', 'Curso');
                // Si no se encontró ningún sprint con estado 'Curso', buscar el primer sprint con estado 'Curso'
                if (!$courseSprint) {
                    $pendingSprint = $this->sprints->firstWhere('state', 'Pendiente');
                    // Si se encontró un sprint con estado 'Pendiente', asignarlo a $this->firstSprint
                    if ($pendingSprint) {
                        $this->firstSprint = $pendingSprint;
                        // Obtenemos las fechas relevantes
                        $startDate = Carbon::parse($pendingSprint->start_date)->startOfDay();
                        $currentDate = Carbon::now()->startOfDay();
                        // Verificamos si la fecha de inicio del sprint es igual o mayor a hoy
                        if ($startDate->lte($currentDate)) {
                            // Solo cambiar a "Curso" si estamos en o después del 13 de enero
                            if ($currentDate->greaterThanOrEqualTo(Carbon::create($startDate))) {
                                // Emitir evento al componente hijo
                                $this->emit('sprintUpdated', $pendingSprint->id);
                            }
                        }
                    } else {
                        // Si no se encontró ningún sprint con estado 'Curso' o 'Pendiente', asignar el primer sprint de la colección
                        $this->firstSprint = $this->sprints->first();
                    }
                } else {
                    // Si se encontró un sprint con estado 'Curso', asignarlo a $this->firstSprint
                    $this->firstSprint = $courseSprint;
                }

                $this->selectSprint = $this->firstSprint->id;
            }
            // SELECT SPRINT
            $this->updateSprintData($this->selectSprint);
            $this->firstSprint = Sprint::find($this->selectSprint);

            if ($this->firstSprint) {
                $sprintDesc = $this->sprints;
                // Encuentra el sprint anterior en la secuencia
                $previousSprint = $sprintDesc->sortByDesc('number')->first(function ($sprint) {
                    return $sprint->number < $this->firstSprint->number;
                });

                if ($previousSprint) {
                    if ($previousSprint->state == 'Curso') {
                        $this->filteredState = [];
                    } else {
                        if ($this->firstSprint->state == 'Curso') {
                            $this->filteredState = [];
                        } elseif ($this->firstSprint->state == 'Pendiente') {
                            $this->filteredState = [];
                        } else {
                            $this->filteredState = [];
                        }
                    }
                } else {
                    if ($this->firstSprint->state == 'Curso') {
                        $this->filteredState = [];
                    } elseif ($this->firstSprint->state == 'Pendiente') {
                        $this->filteredState = [];
                    } else {
                        $this->filteredState = [];
                    }
                }
            }
        } else {
            $this->selectSprint = null;
        }
        // COUNT ACTIVITIES
        $totalActivities = Activity::where('sprint_id', $this->selectSprint)->count(); // Contar el número total de actividades del sprint
        $allActivities = Activity::where('sprint_id', $this->selectSprint)->get(); // Seleccionar todas las actividades del sprint
        $sprint = Sprint::find($this->selectSprint);
        if ($totalActivities && Auth::user()->type_user == 1) {
            if ($sprint->state == 'Curso' || $sprint->state == 'Cerrado') {
                $resolvedActivities = $allActivities->where('state', 'Resuelto')->count(); // Contar el número de actividades resueltas
                if ($totalActivities > 0) {
                    $this->percentageResolved = ($resolvedActivities / $totalActivities) * 100; // Calcular el porcentaje de actividades resueltas sobre el total de actividades
                    $this->percentageResolved = round($this->percentageResolved, 2); // Redondear el porcentaje a dos decimales
                    // Verificar si todas las actividades están en estado "Resuelto"
                    $allResolved = $allActivities->every(function ($activity) {
                        return $activity->state === 'Resuelto';
                    });
                    // Si todas las actividades están en estado "Resuelto", actualizar el estado del sprint a "Cerrado"
                    if ($allResolved && Auth::user()->type_user == 1) {
                        $this->firstSprint = $sprint;
                        $this->sprints = $this->backlog->sprints;
                    }
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

                if ($allResolved && $sprint->state !== 'Cerrado') {
                    $this->firstSprint = $sprint;
                    $this->sprints = $this->backlog->sprints;
                }
            } else {
                $this->percentageResolved = 0;
            }
        } else {
            $this->percentageResolved = 0;
        }
    }
}

