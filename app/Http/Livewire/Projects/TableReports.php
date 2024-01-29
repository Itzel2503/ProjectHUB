<?php

namespace App\Http\Livewire\Projects;

use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TableReports extends Component
{
    use WithFileUploads;

    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalShow = false, $modalEdit = false;

    public $showReport = false, $showEdit = false;
    // table, action's reports
    public $leader = false;
    public $search, $project, $reportShow, $reportEdit;
    public $perPage = '10';
    public $sortField = 'updated_at'; // La columna por defecto por la que se ordenará
    public $sortAsc = true; // Dirección del ordenamiento
    public $rules = [], $usersFiltered = [];
    // inputs
    public $name, $type, $priority, $customer, $file;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $user = Auth::user();
        $allUsers = User::all();

        $reports = Report::where('project_id', $this->project->id)
            ->where(function ($query) {
                $query->where('title', 'like', '%' . $this->search . '%')
                    ->orWhere('comment', 'like', '%' . $this->search . '%')
                    ->orWhere('state', 'like', '%' . $this->search . '%');
            })
            ->with(['user', 'delegate'])
            ->orderBy($this->sortField, $this->sortAsc ? 'desc' : 'asc')
            ->paginate($this->perPage);

        foreach ($this->project->users as $projectUser) {
            if ($projectUser->id === $user->id && $projectUser->pivot->leader) {
                $this->leader = true;
                break;
            }
        }

        foreach ($reports as $report) {
            $report->filteredActions = $this->getFilteredActions($report->state);
            $report->usersFiltered = $allUsers->reject(function ($user) use ($report) {
                return $user->id === $report->delegate_id;
            })->values();
        }

        return view('livewire.projects.table-reports', [
            'reports' => $reports,
        ]);
    }
    // ACTIONS
    public function create($project_id)
    {
        return redirect()->route('projects.reports.create', ['project' => $project_id]);
    }

    public function show($report_id)
    {
        return redirect()->route('reports.show', ['project_id' => $this->project->id, 'report_id' => $report_id]);
    }

    public function updateState($id, $state)
    {
        $report = Report::find($id);
        if ($report) {
            $report->state = $state;

            if ($state == 'Resuelto') {
                $report->resolved_id = Auth::id();
            }

            $report->save();

            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Operación exitosa',
            ]);
        }
    }

    public function updateDelegate($id, $delegate)
    {
        $report = Report::find($id);
        if ($report) {
            $report->delegate_id = $delegate;
            $report->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Operación exitosa',
            ]);
        }
    }

    public function update($id, $project_id)
    {
        $report = Report::find($id);
        $project = Project::find($project_id);
        
        if ($this->file) {
            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
            $fileName = $this->file->getClientOriginalName();

            $fullNewFilePath = $filePath . '/' . $fileName;

            // Verificar y eliminar el archivo anterior si existe y coincide con la nueva ruta
            if ($report->content && Storage::disk('reports')->exists($report->content)) {
                $existingFilePath = pathinfo($report->content, PATHINFO_DIRNAME);

                if ($existingFilePath == $filePath) {
                    Storage::disk('reports')->delete($report->content);
                }
            }
            // Guardar el archivo en el disco 'reports'
            $this->file->storeAs($filePath, $fileName, 'reports');

            $extension = $this->file->extension();
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
            $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv'];

            if (in_array($extension, $extensionesImagen)) {
                $report->image = true;
                $report->video = false;
            } elseif (in_array($extension, $extensionesVideo)) {
                $report->image = false;
                $report->video = true;
            } else {
                $report->image = false;
                $report->video = false;
            }

            $report->content = $fullNewFilePath;
            $report->save();

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Operación exitosa',
            ]);

            $this->modalEdit = false;
        }
    }
    // INFO MODAL
    public function showReport($id)
    {
        $this->showReport = true;

        if ($this->modalShow == true) {
            $this->modalShow = false;
        } else {
            $this->modalShow = true;
        }

        $this->reportShow = Report::find($id);
        $user = Auth::user();

        if ($this->reportShow && $this->reportShow->delegate_id == $user->id && $this->reportShow->state != 'Resuelto') {
            $this->reportShow->state = 'Proceso';
            $this->reportShow->save();
        }
    }

    public function showEdit($id)
    {
        $this->showEdit = true;

        if ($this->modalEdit == true) {
            $this->modalEdit = false;
        } else {
            $this->modalEdit = true;
        }

        $this->reportEdit = Report::find($id);
    }
    // MODAL
    public function modalShow()
    {
        if ($this->modalShow == true) {
            $this->modalShow = false;
        } else {
            $this->modalShow = true;
        }
    }

    public function modalEdit()
    {
        $this->showEdit = false;

        if ($this->modalEdit == true) {
            $this->modalEdit = false;
        } else {
            $this->modalEdit = true;
        }
    }
    // EXTRAS
    protected function getFilteredActions($currentState)
    {
        $actions = ['Abierto', 'Proceso', 'Resuelto', 'Conflicto'];

        if (in_array($currentState, ['Resuelto', 'Conflicto'])) {
            return [];
        }

        if ($currentState == 'Proceso') {
            return array_filter($actions, function ($action) {
                return !in_array($action, ['Abierto', 'Proceso']);
            });
        }

        return array_filter($actions, function ($action) use ($currentState) {
            return $action != $currentState;
        });
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortAsc = !$this->sortAsc;
        } else {
            $this->sortAsc = true;
            $this->sortField = $field;
        }
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
