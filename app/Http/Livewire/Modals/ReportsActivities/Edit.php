<?php

namespace App\Http\Livewire\Modals\ReportsActivities;

use App\Models\Activity;
use App\Models\Backlog;
use App\Models\Project;
use App\Models\Report;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Edit extends Component
{
    use WithFileUploads;
    use WithPagination;
    // ENVIADAS
    public $recordingedit, $backlog, $sprint, $type;
    // REPORTE Y ACTIVIDAD
    public $recording;
    // DINAMICOS
    public $evidenceEdit, $changePoints = false;
    // ACTIVIDADES
    public $sprints;
    // INPUTS
    public $title, $file, $comment, $description, $expected_date, $moveActivity, $priority, $points, $point_know, $point_many, $point_effort;

    public function mount()
    {
        if ($this->type == 'activity') {
            $this->sprints = Backlog::find($this->backlog->id)->sprints;
            // Reorganizar la colección para que el sprint coincidente sea el primero
            $this->sprints = $this->sprints->sortBy('number')->values(); // Orden inicial
            $this->sprints = $this->sprints->partition(function ($sprint) {
                return $sprint->id == $this->sprint; // Mueve el que coincide al principio
            })->flatten();
            // Establecer `moveActivity` con el ID del sprint seleccionado
            $this->moveActivity = $this->sprint;
        }

        $this->recording = $this->type === 'report'
            ? Report::find($this->recordingedit)
            : Activity::with('sprint.backlog.project')->find($this->recordingedit);
        // Inicializar las propiedades con los valores del registro
        $this->title = $this->recording->title;
        if ($this->type == 'report') {
            $this->comment = $this->recording->comment;
        } else {
            $this->description = $this->recording->description;
        }

        $this->expected_date = Carbon::parse($this->recording->expected_date)->toDateString();
        $this->priority = $this->recording->priority ?? null;
        $this->points = $this->recording->points;
        // Cargar las preguntas del reporte
        $questionsPoints = json_decode($this->recording->questions_points, true);
        $this->point_know = $questionsPoints['pointKnow'] ?? null;
        $this->point_many = $questionsPoints['pointMany'] ?? null;
        $this->point_effort = $questionsPoints['pointEffort'] ?? null;
        // Determinar si mostrar "Agregar puntos directos" o "Cuestionario"
        $this->changePoints = !is_null($this->point_know) || !is_null($this->point_many) || !is_null($this->point_effort);
        // Inicializar evidencia
        $this->evidenceEdit = $this->recording->evidence ? true : false;
    }

    public function render()
    {
        return view('livewire.modals.reports-activities.edit');
    }

    public function changePoints()
    {
        // Alternar el estado de $changePoints
        $this->changePoints = !$this->changePoints;
    }

    public function selectPriority($value)
    {
        // Actualizar la prioridad seleccionada
        $this->priority = $value;
    }

    public function update($id, $project_id)
    {
        $report = Report::find($id);
        $project = Project::find($project_id);

        if ($report) {
            if ($this->file) {
                $extension = $this->file->extension();
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                if (in_array($extension, $extensionesImagen)) {
                    $maxSize = 5 * 1024 * 1024; // 5 MB
                    // Verificar el tamaño del archivo
                    if ($this->file->getSize() > $maxSize) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.'
                        ]);
                        return;
                    }
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $project->customer->name . '/' . $project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fullNewFilePath = $filePath . '/' . $fileName;
                    // Procesar la imagen
                    $image = \Intervention\Image\Facades\Image::make($this->file->getRealPath());
                    // Redimensionar la imagen si es necesario
                    $image->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // Guardar la imagen temporalmente
                    $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                    $image->save(storage_path('app/' . $tempPath));
                    // Eliminar imagen anterior
                    if (Storage::disk('reports')->exists($report->content)) {
                        Storage::disk('reports')->delete($report->content);
                    }
                    // Guardar la imagen redimensionada en el almacenamiento local
                    Storage::disk('reports')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                    // // Eliminar la imagen temporal
                    Storage::disk('local')->delete($tempPath);

                    $report->image = true;
                    $report->video = false;
                    $report->file = false;
                } elseif (in_array($extension, $extensionesVideo)) {
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

                    $report->image = false;
                    $report->video = true;
                    $report->file = false;
                } else {
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

                    $report->image = false;
                    $report->video = false;
                    $report->file = true;
                }

                $report->content = $fullNewFilePath;
            }

            $report->title = $this->title ?? $report->title;
            $report->comment = $this->comment ?? $report->comment;

            $fecha = Carbon::parse($report->expected_date)->toDateString();

            if ($report->updated_expected_date == false && $this->expected_date != $fecha) {
                $report->updated_expected_date = true;
                $report->expected_date = $this->expected_date;
            } else {
                $report->expected_date = $this->expected_date ?? $report->expected_date;
            }

            $report->evidence = $this->evidenceEdit  ?? $report->evidence;
            $report->priority = $this->priority ?? $report->priority;

            if (Auth::user()->type_user == 3) {
                $report->points = $report->points ?? 0;
            } else {
                if ($this->changePoints == false) {
                    $validPoints = [0, 1, 2, 3, 5, 8, 13];
                    $report->points = $this->points;

                    if (!in_array($this->points, $validPoints)) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'Puntuaje no válido.',
                        ]);
                        return; // O cualquier otra acción que desees realizar
                    } else {
                        $report->points = $this->points ?? $report->points;
                    }
                    // Crear un array asociativo con los valores
                    $questionsPoints = [
                        'pointKnow' => null,
                        'pointMany' => null,
                        'pointEffort' => null,
                    ];
                    // Convertir el array a JSON
                    $questionsPointsJson = json_encode($questionsPoints);
                    // Asignar y guardar 
                    $report->questions_points = $questionsPointsJson;
                } else {
                    if (!$this->point_know || !$this->point_many || !$this->point_effort) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'warning',
                            'title' => 'El formulario está incompleto o no se han seleccionado los puntos necesarios.',
                        ]);
                        $report->points = $report->points ?? 0;
                        $questionsPoints = [
                            'pointKnow' => null,
                            'pointMany' => null,
                            'pointEffort' => null,
                        ];
                        // Convertir el array a JSON
                        $questionsPointsJson = json_encode($questionsPoints);
                        // Asignar y guardar 
                        $report->questions_points = $questionsPointsJson;
                    } else {
                        $maxPoint = max($this->point_know, $this->point_many, $this->point_effort);
                        $report->points = $maxPoint;
                        // Crear un array asociativo con los valores
                        $questionsPoints = [
                            'pointKnow' => $this->point_know,
                            'pointMany' => $this->point_many,
                            'pointEffort' => $this->point_effort,
                        ];
                        // Convertir el array a JSON
                        $questionsPointsJson = json_encode($questionsPoints);
                        // Asignar y guardar 
                        $report->questions_points = $questionsPointsJson;
                    }
                }
            }
            $report->save();
            $this->dispatchBrowserEvent('file-reset');

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Guardado exitoso',
            ]);
        }
    }
}
