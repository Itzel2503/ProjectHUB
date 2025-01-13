<?php

namespace App\Http\Livewire\Modals\ReportsActivities;

use App\Models\Activity;
use App\Models\Backlog;
use App\Models\Project;
use App\Models\Report;
use App\Models\Sprint;
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
    public $title, $file, $description, $expected_date, $moveActivity, $priority, $points, $point_know, $point_many, $point_effort;

    public function mount()
    {
        $this->recording = $this->type === 'report'
            ? Report::find($this->recordingedit)
            : Activity::with('sprint.backlog.project')->find($this->recordingedit);
        // Inicializar las propiedades con los valores del registro
        $this->title = $this->recording->title;
        if ($this->type != 'report') {
            $this->sprints = Backlog::find($this->backlog->id)->sprints->map(function ($sprint) {
                return is_array($sprint) ? (object) $sprint : $sprint; // Asegurarse de que sean objetos
            });
            // Reorganizar la colección para que el sprint coincidente sea el primero
            $this->sprints = $this->sprints->sortBy('number')->values();
    
            $this->sprints = $this->sprints->partition(function ($sprint) {
                return $sprint->id == $this->sprint; // Mueve el que coincide al principio
            })->flatten();
            // Establecer `moveActivity` con el ID del sprint seleccionado
            $this->moveActivity = $this->sprint;
        }
        $this->description = $this->recording->description;
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
        if ($this->type == 'activity') {
            try {
                $this->validate(
                    [
                        'title' => 'required|max:255',
                        'expected_date' => 'required|date',
                    ],
                    [
                        'title.required' => 'El título es obligatorio.',
                        'title.max:255' => 'El título no debe tener más caracteres que 255.',
                        'expected_date.required' => 'La fecha esperada es obligatoria.',
                        'expected_date.date' => 'La fecha esperada debe ser una fecha válida.',
                    ],
                );
                // Aquí puedes continuar con tu lógica después de la validación exitosa
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Faltan campos o campos incorrectos',
                ]);
                throw $e;
            }
        }
        
        if ($this->type == 'report') {
            $report = Report::find($id);
            $activity = null;
        } else {
            $activity = Activity::find($id);
            $report = null;
        }
        $project = Project::find($project_id);
        // REPORTE
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
                    $fileName = str_replace(' ', '_', $fileName);
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
                    $fileName = str_replace(' ', '_', $fileName);
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
                    $fileName = str_replace(' ', '_', $fileName);
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
            $report->description = $this->description ?? $report->description;

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
                'title' => 'Reporte actualizado.',
            ]);
        }
        // ACTIVIDAD
        if ($activity) {
            if ($this->file) {
                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
                if (in_array($this->file->extension(), $extensionesImagen)) {
                    $maxSize = 5 * 1024 * 1024; // 5 MB
                    // Verificar el tamaño del archivo
                    if ($this->file->getSize() > $maxSize) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                        ]);
                        return;
                    }
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $this->project->customer->name . '/' . $this->project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fileName = str_replace(' ', '_', $fileName);
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
                    if (Storage::disk('activities')->exists($activity->image)) {
                        Storage::disk('activities')->delete($activity->image);
                    }
                    // Guardar la imagen redimensionada en el almacenamiento local
                    Storage::disk('activities')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                    // // Eliminar la imagen temporal
                    Storage::disk('local')->delete($tempPath);
                    $activity->image = $fullNewFilePath;
                } else {
                    $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $this->project->customer->name . '/' . $this->project->name;
                    $fileName = $this->file->getClientOriginalName();
                    $fileName = str_replace(' ', '_', $fileName);
                    $fullNewFilePath = $filePath . '/' . $fileName;
    
                    // Verificar y eliminar el archivo anterior si existe y coincide con la nueva ruta
                    if ($activity->image && Storage::disk('activities')->exists($activity->image)) {
                        $existingFilePath = pathinfo($activity->image, PATHINFO_DIRNAME);
    
                        if ($existingFilePath == $filePath) {
                            Storage::disk('activities')->delete($activity->image);
                        }
                    }
                    // Guardar el archivo en el disco 'activities'
                    $this->file->storeAs($filePath, $fileName, 'activities');
                    $activity->image = $fullNewFilePath;
                }
            }
            $activity->sprint_id = $this->moveActivity ?? $activity->sprint_id;
            if ($this->moveActivity) {
                $sprint = Sprint::find($this->moveActivity);
                if ($sprint->state == 'Cerrado') {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Sprint cerrado',
                    ]);
    
                    return;
                }
            }
            $activity->title = $this->title ?? $activity->title;
            $activity->description = $this->description ?? $activity->description;
            $activity->priority = $this->priority ?? $activity->priority;
            $activity->expected_date = $this->expected_date ?? $activity->expected_date;
            
            if ($this->changePoints == false) {
                $validPoints = [0, 1, 2, 3, 5, 8, 13];
                $activity->points = $this->points;
    
                if (!in_array($this->points, $validPoints)) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Puntuaje no válido.',
                    ]);
                    return; // O cualquier otra acción que desees realizar
                } else {
                    $activity->points = $this->points ?? $activity->points;
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
                $activity->questions_points = $questionsPointsJson;
            } else {
                if (!$this->point_know || !$this->point_many || !$this->point_effort) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'warning',
                        'title' => 'El formulario está incompleto o no se han seleccionado los puntos necesarios.',
                    ]);
                    $activity->points = $activity->points ?? 0;
                    $questionsPoints = [
                        'pointKnow' => null,
                        'pointMany' => null,
                        'pointEffort' => null,
                    ];
                    // Convertir el array a JSON
                    $questionsPointsJson = json_encode($questionsPoints);
                    // Asignar y guardar 
                    $activity->questions_points = $questionsPointsJson;
                } else {
                    $maxPoint = max($this->point_know, $this->point_many, $this->point_effort);
                    $activity->points = $maxPoint;
                    // Crear un array asociativo con los valores
                    $questionsPoints = [
                        'pointKnow' => $this->point_know,
                        'pointMany' => $this->point_many,
                        'pointEffort' => $this->point_effort,
                    ];
                    // Convertir el array a JSON
                    $questionsPointsJson = json_encode($questionsPoints);
                    // Asignar y guardar 
                    $activity->questions_points = $questionsPointsJson;
                }
            }
            
            $activity->save();
            $this->dispatchBrowserEvent('file-reset');

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Actividad actualizada.',
            ]);
        }
    }
}
