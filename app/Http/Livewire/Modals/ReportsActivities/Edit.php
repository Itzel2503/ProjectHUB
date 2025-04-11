<?php

namespace App\Http\Livewire\Modals\ReportsActivities;

use App\Models\Activity;
use App\Models\ActivityRecurrent;
use App\Models\ActivityFiles;
use App\Models\Backlog;
use App\Models\ErrorLog;
use App\Models\Log;
use App\Models\Project;
use App\Models\Report;
use App\Models\ReportFiles;
use App\Models\Sprint;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
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
    public $evidenceEdit, $changePoints = false, $chooseEndDate = false;
    public $selectedIcon = null;
    public $showExistingContent = true; // Agrega esta propiedad para controlar el estado
    // MENSJES
    public $repeatMessage = '', $moveActivityMessage = '', $endDateMessage = '', $expectedDateMessage = '';
    // ACTIVIDADES RECURRENTES
    public $repeat_updated = false, $newActivityRecurrent = true, $deleteActivities = false, $createActivities = false;
    public $activitiesNoResueltas, $activitiesAbiertas;
    // ACTIVIDADES
    public $sprints;
    // INPUTS
    public $filesNew = [], $selectedFiles = [];
    public $title, $description, $expected_date, $repeat, $end_date, $moveActivity, $priority, $points, $point_know, $point_many, $point_effort;

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
        if ($this->recording->icon == '') {
            $this->selectedIcon = '🚫';
        } else {
            $this->selectedIcon = $this->recording->icon;
        }

        $this->description = $this->recording->description;
        $this->expected_date = (!empty($this->recording->expected_date)) ? Carbon::parse($this->recording->expected_date)->toDateString() : '';

        if ($this->recording->activity_repeat) {
            $activityRepeat = ($this->recording->activity_repeat != null) ? ActivityRecurrent::where('activity_repeat', $this->recording->activity_repeat)->first() : null;
            $this->repeat = $activityRepeat->frequency;
            $this->chooseEndDate = true;
            $this->end_date = ($activityRepeat->end_date != null) ? Carbon::parse($activityRepeat->end_date)->toDateString() : '';

            if ($this->end_date == '') {
                switch ($activityRepeat->frequency) {
                    case 'Dairy':
                        $this->repeatMessage = 'Se repetirá diariamente desde el último registro.';
                        break;
                    case 'Weekly':
                        $this->repeatMessage = 'Se repetirá semanalmente desde el último registro.';
                        break;
                    case 'Monthly':
                        $this->repeatMessage = 'Se repetirá cada 3 meses desde el último registro.';
                        break;
                    case 'Yearly':
                        $this->repeatMessage = 'Se repetirá anualmente un mes antes del último registro.';
                        break;
                    default:
                        $this->repeatMessage = '';
                        break;
                }
            }
        } else {
            $activityRepeat = null;
            $this->repeat = 'Once';
        }

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
        // Verificar si el archivo existe en la base de datos
        if ($this->recording && $this->recording->content) {
            // Verificar si el archivo existe en la carpeta
            if ($this->type == 'report') {
                $filePath = public_path('reportes/' . $this->recording->content);
            } else {
                $filePath = public_path('activities/' . $this->recording->content);
            }
            
            $fileExtension = pathinfo($this->recording->content, PATHINFO_EXTENSION);
            if (file_exists($filePath)) {
                $this->recording->contentExists = true;
                $this->recording->fileExtension = $fileExtension;
            } else {
                $this->recording->contentExists = false;
            }
        } else {
            $this->recording->contentExists = false;
        }

        if ($this->recording && $this->recording->files) {
            $basePath = $this->type == 'report' ? 'reportes' : 'activities';
            
            foreach ($this->recording->files as $file) {
                // Verificar existencia del archivo físico
                $filePath = public_path($basePath . '/' . $file->route);
                $file->exists = file_exists($filePath);
                
                // Asignar URL pública
                $file->public_url = asset($basePath . '/' . $file->route);
                
                // Determinar tipo de archivo si no está definido
                $fileExtension = strtolower(pathinfo($file->route, PATHINFO_EXTENSION));
                $file->fileExtension = $fileExtension;
                
                // Asignar contentExists basado en la existencia del archivo
                $file->contentExists = $file->exists;
            }
        }

        return view('livewire.modals.reports-activities.edit');
    }

    // Método para seleccionar un ícono
    public function selectIcon($icon)
    {
        $this->selectedIcon = $icon;
    }

    public function expected_date($day)
    {
        $now = Carbon::now()->format('Y-m-d'); // Fecha actual en formato 'Y-m-d'

        // Convertir $day a Carbon para comparar
        $dayFormatted = Carbon::parse($day)->format('Y-m-d');
        $endDate = Carbon::parse($this->end_date)->format('Y-m-d');

        if ($this->repeat_updated) {
            if ($dayFormatted >= $endDate) {
                $this->end_date = Carbon::parse($day)->addDay()->format('Y-m-d');
                $this->endDateMessage = 'La fecha límite debe ser posterior a la fecha de entrega.';
            } elseif ($dayFormatted < $now) {
                $this->expected_date = $now;
                $this->expectedDateMessage = 'La fecha de entrega debe ser posterior o igual a la fecha actual.';
            } else {
                $this->expectedDateMessage = '';
                $this->endDateMessage = '';
            }
        } else {
            // Verificar si $day es menor que $now
            if ($dayFormatted < $now) {
                $this->expected_date = $now;
                $this->expectedDateMessage = 'La fecha de entrega debe ser posterior o igual a la fecha actual.';
            } else {
                $this->expectedDateMessage = '';
            }
        }
    }

    public function repeat_updated($checked)
    {
        $this->repeat_updated = $checked;

        if ($this->recording->activity_repeat != null) {
            // Obtener la nueva ultima actividad
            $lastActivityRepeat = Activity::where('activity_repeat', $this->recording->activity_repeat)->where('state', '!=', 'Resuelto')->orderBy('expected_date', 'asc')->latest()->first();
            $activityRecurrents = ActivityRecurrent::where('activity_repeat', $this->recording->activity_repeat)->first();
            // Convertir $day a Carbon para comparar
            $dayEndFormatted = Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d');
            if ($checked) {
                $this->expected_date = $dayEndFormatted;
                $this->expectedDateMessage = 'Fecha de la última actividad como referencia para actualizar o crear nuevas actividades, con opción a modificarla.';
            } else {
                $this->expected_date = Carbon::parse($this->recording->expected_date)->format('Y-m-d');
                $this->end_date = Carbon::parse($activityRecurrents->end_date)->format('Y-m-d');
                $this->expectedDateMessage = '';
                $this->repeat = $activityRecurrents->frequency;
            }
        } else {
            $this->expected_date = Carbon::parse($this->recording->expected_date)->format('Y-m-d');
            $this->expectedDateMessage = ($checked) ? 'Fecha de inicio de las actividades recurrentes.' : '';
            $this->repeat = ($checked) ? '' : 'Once';
        }
    }

    public function repeat($type)
    {
        $this->chooseEndDate = ($type != 'Once') ? true : false;

        if ($this->end_date == '') {
            switch ($type) {
                case 'Dairy':
                    $this->repeatMessage = 'Se repetirá diariamente desde el último registro.';
                    break;
                case 'Weekly':
                    $this->repeatMessage = 'Se repetirá semanalmente desde el último registro.';
                    break;
                case 'Monthly':
                    $this->repeatMessage = 'Se repetirá cada 3 meses desde el último registro.';
                    break;
                case 'Yearly':
                    $this->repeatMessage = 'Se repetirá anualmente un mes antes del último registro.';
                    break;
                default:
                    $this->repeatMessage = '';
                    break;
            }
        }
    }

    public function end_date($day)
    {
        $now = Carbon::now()->format('Y-m-d'); // Fecha actual en formato 'Y-m-d'
        // Convertir $day a Carbon para comparar
        $dayFormatted = ($day != '') ? Carbon::parse($day)->format('Y-m-d') : null;
        if ($this->recording->activity_repeat != null) {
            // Obtener la nueva ultima actividad
            $lastActivityRepeat = Activity::where('activity_repeat', $this->recording->activity_repeat)->where('state', '!=', 'Abierto')->orderBy('expected_date', 'desc')->latest()->first();
            // Convertir $day a Carbon para comparar
            $dayEndFormatted = ($lastActivityRepeat) ? Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d') : null;
            $expectedDate = Carbon::parse($this->recording->expected_date)->format('Y-m-d');
            if ($dayFormatted != null) {
                if ($dayEndFormatted && $dayEndFormatted > $dayFormatted) {
                    $this->end_date = Carbon::parse($dayEndFormatted)->format('Y-m-d');
                    $this->endDateMessage = 'Debe ser posterior o igual a la última actividad no "Abierta".';
                } elseif ($expectedDate >= $dayFormatted) {
                    $this->end_date = Carbon::parse($this->recording->expected_date)->addDay()->format('Y-m-d');
                    $this->endDateMessage = 'Debe ser posterior o igual a la fecha de entrega de esta actividad.';
                } elseif ($dayFormatted <= $now) {
                    $this->end_date = Carbon::parse($now)->addDay()->format('Y-m-d');
                    $this->endDateMessage = 'Debe ser posterior a la fecha actual.';
                } else {
                    $this->endDateMessage = '';
                }
            } else {
                $this->endDateMessage = '';
            }
        } else {
            // Verificar si $day es menor que $now
            if ($dayFormatted <= $now) {
                $this->end_date = Carbon::parse($now)->addDay()->format('Y-m-d');
                $this->endDateMessage = 'Debe ser posterior a la fecha actual.';
            } else {
                $this->endDateMessage = '';
            }
        }
    }

    public function selectPriority($value)
    {
        // Actualizar la prioridad seleccionada
        $this->priority = $value;
    }

    public function changePoints()
    {
        // Alternar el estado de $changePoints
        $this->changePoints = !$this->changePoints;
    }

    public function moveActivity($sprint_id)
    {
        if ($this->recording->activity_repeat != null && $this->recording->sprint_id != $sprint_id) {
            $this->moveActivityMessage = 'Todas las actividades recurrentes se moverán al sprint seleccionado.';
        } else {
            $this->moveActivityMessage = '';
        }
    }

    public function addInput()
    {
        $this->filesNew[] = null;
        $this->showExistingContent = true; // Mantener visible el contenido existente
    }

    public function removeInput($index)
    {
        unset($this->filesNew[$index]);
        $this->filesNew = array_values($this->filesNew);
        $this->showExistingContent = true;
    }

    public function update($id, $project_id)
    {
        if ($this->type == 'activity') {
            try {
                $this->validate(
                    [
                        'title' => 'required|max:255',
                    ],
                    [
                        'title.required' => 'El título es obligatorio.',
                        'title.max:255' => 'El título no debe tener más caracteres que 255.',
                    ],
                );
                // Aquí puedes continuar con tu lógica después de la validación exitosa
            } catch (\Illuminate\Validation\ValidationException $e) {
                // Guardar el error en la base de datos
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'view' => 'livewire/modals/edit',
                    'action' => 'update',
                    'message' => 'Error de validación en el formulario de editar la actividad',
                    'details' => $e->getMessage(),
                ]);
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
        $projectName = Str::slug($project->name, '_');
        $customerName = Str::slug($project->customer->name, '_');
        $now = Carbon::now();
        $dateString = $now->format("Y-m-d_H_i_s");
        // REPORTE
        if ($report) {
            try {
                $report->icon = ($this->selectedIcon == "🚫" || $this->selectedIcon == null) ? '' : $this->selectedIcon;
                $report->title = $this->title ?? $report->title;
                $report->description = $this->description ?? $report->description;

                if ($this->expected_date == '' && $report->expected_date == null) {
                    $report->expected_date = null;
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

                $reportFiles = ReportFiles::where('report_id', $report->id)->get();
                // Eliminar los archivos seleccionados
                if (!empty($this->selectedFiles) || !empty(array_filter($this->selectedFiles))) {
                    foreach ($this->selectedFiles as $fileId) {
                        // Buscar el archivo en la colección de archivos
                        $fileToDelete = $reportFiles->where('id', $fileId)->first();
                        // Verificar si se encontró el archivo
                        if ($fileToDelete) {
                            // Eliminar el archivo físico si existe en el disco
                            if (Storage::disk('reports')->exists($fileToDelete->route)) {
                                Storage::disk('reports')->delete($fileToDelete->route);
                            }
                            // Eliminar el archivo de la base de datos
                            $fileToDelete->delete();

                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Archivo eliminado.',
                            ]);
                        }
                    }
                    // Recargar el registro con relaciones frescas
                    $this->recording = $report->fresh()->load('files');
                            
                    // Resetear variables temporales
                    $this->reset(['filesNew', 'selectedFiles']);
                    
                    // Notificar éxito
                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'success',
                        'message' => 'Archivos eliminados correctamente'
                    ]);
                }

                if (!empty($this->filesNew) || !empty(array_filter($this->filesNew))) {
                    foreach ($this->filesNew as $index => $file) {
                        // $file = $fileArray[0];
                        if ($file instanceof \Livewire\TemporaryUploadedFile) {
                            $fileExtension = $file->extension();
                            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                            $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                            // Sanitizar nombres de archivo y directorios
                            $fileName = 'Reporte_' . $projectName . '_' . $dateString . '.' . $fileExtension;
                            $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $customerName . '/' . $projectName;
                            $fullNewFilePath = $filePath . '/' . $fileName;
                            
                            if (in_array($fileExtension, $extensionesImagen)) {
                                $maxSize = 5 * 1024 * 1024; // 5 MB
                                // Verificar el tamaño del archivo
                                if ($file->getSize() > $maxSize) {
                                    $this->dispatchBrowserEvent('swal:modal', [
                                        'type' => 'error',
                                        'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                                    ]);
                                    return;
                                }
                                // Procesar la imagen
                                $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                                // Redimensionar la imagen si es necesario
                                $image->resize(800, null, function ($constraint) {
                                    $constraint->aspectRatio();
                                });
                                // Guardar la imagen temporalmente
                                $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                                $image->save(storage_path('app/' . $tempPath));
                                // Guardar la imagen redimensionada en el almacenamiento local
                                // Guardar directamente sin usar archivo temporal
                                Storage::disk('reports')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                                // Eliminar la imagen temporal
                                Storage::disk('local')->delete($tempPath);

                                $reportFile = new ReportFiles();
                                $reportFile->report_id = $report->id;
                                $reportFile->route = $fullNewFilePath;
                                $reportFile->image = true;
                                $reportFile->save();
                            } else if (in_array($fileExtension, $extensionesVideo)) {
                                // Guardar la imagen redimensionada en el almacenamiento local
                                $file->storeAs('', $fullNewFilePath, 'reports');

                                $reportFile = new ReportFiles();
                                $reportFile->report_id = $report->id;
                                $reportFile->route = $fullNewFilePath;
                                $reportFile->video = true;
                                $reportFile->save();
                            } else {
                                // Guardar la imagen redimensionada en el almacenamiento local
                                $file->storeAs('', $fullNewFilePath, 'reports');
                                
                                $reportFile = new ReportFiles();
                                $reportFile->report_id = $report->id;
                                $reportFile->route = $fullNewFilePath;
                                $reportFile->file = true;
                                $reportFile->save();
                            }
                        }
                    }
                    // Recargar el registro con relaciones frescas
                    $this->recording = $report->fresh()->load('files');
                            
                    // Resetear variables temporales
                    $this->reset(['filesNew', 'selectedFiles']);
                    
                    // Notificar éxito
                    $this->dispatchBrowserEvent('notify', [
                        'type' => 'success',
                        'message' => 'Archivos guardados correctamente'
                    ]);
                }

                Log::create([
                    'user_id' => Auth::id(),
                    'report_id' => $report->id,
                    'view' => 'livewire/modals/reports-activities/edit',
                    'action' => 'update report',
                    'message' => 'Reporte actualizado exitosamente',
                    'details' => 'Reporte: ' .  $report->id,
                ]);

                $this->dispatchBrowserEvent('file-reset');
                $this->filesNew = [];
                $this->render();

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Reporte actualizado.',
                ]);
            } catch (\Exception $e) {
                // Guardar el error en la base de datos
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'view' => 'livewire/modals/reports-activities/edit',
                    'action' => 'update',
                    'message' => 'Error al editar el reporte',
                    'details' => $e->getMessage(),
                ]);

                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Ocurrió un error al guardar el reporte',
                ]);

                throw $e;
            }
        }
        // ACTIVIDAD
        if ($activity) {
            try {
                if ($this->moveActivity) {
                    $sprint = Sprint::find($this->moveActivity);

                    if ($sprint->state == 'Cerrado') {
                        $this->moveActivity = $activity->sprint_id;
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'Sprint cerrado',
                        ]);

                        return;
                    } else {
                        if ($activity->activity_repeat != null) {
                            $activities = Activity::where('activity_repeat', $activity->activity_repeat)->where('state', '!=', 'Resuelto')->get();

                            foreach ($activities as $activityRepeat) {
                                $activityRepeat->sprint_id = $this->moveActivity ?? $activity->sprint_id;
                                $activityRepeat->save();
                            }
                        } else {
                            $activity->sprint_id = $this->moveActivity ?? $activity->sprint_id;
                        }
                    }
                }

                $activity->icon = ($this->selectedIcon == "🚫" || $this->selectedIcon == null) ? '' : $this->selectedIcon;
                $activity->title = $this->title ?? $activity->title;
                $activity->description = $this->description ?? $activity->description;
                $activity->priority = $this->priority ?? $activity->priority;

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

                if ($activity->activity_repeat == null) {
                    if ($this->expected_date == '' && $activity->expected_date == null) {
                        $fecha = null;
                    } else {
                        $fecha = $this->expected_date ?? $activity->expected_date;
                    }

                    if ($this->repeat_updated == true) {
                        $activity->activity_repeat = bin2hex(random_bytes(8));
                    }

                    $activity->expected_date = $fecha;
                    $activity->save();

                    // Crear repeticiones según el filtro seleccionado 
                    switch ($this->repeat) {
                        case 'Dairy':
                            $this->createDailyRepetitions($activity);

                            Log::create([
                                'user_id' => Auth::id(),
                                'activity_id' => $activity->id,
                                'view' => 'livewire/modals/reports-activities/edit',
                                'action' => 'Actividades recurrentes',
                                'message' => 'Actividades diarias creadas',
                                'details' => 'Delegado: ' . $activity->delegate_id,
                            ]);

                            // Emitir un evento de navegador
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Actividades diarias creadas',
                            ]);

                            break;
                        case 'Weekly':
                            $this->createWeeklyRepetitions($activity);

                            Log::create([
                                'user_id' => Auth::id(),
                                'activity_id' => $activity->id,
                                'view' => 'livewire/modals/reports-activities/edit',
                                'action' => 'Actividades recurrentes',
                                'message' => 'Actividades semanales creadas',
                                'details' => 'Delegado: ' . $activity->delegate_id,
                            ]);

                            // Emitir un evento de navegador
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Actividades semanales creadas',
                            ]);

                            break;
                        case 'Monthly':
                            $this->createMonthlyRepetitions($activity);

                            Log::create([
                                'user_id' => Auth::id(),
                                'activity_id' => $activity->id,
                                'view' => 'livewire/modals/reports-activities/edit',
                                'action' => 'Actividades recurrentes',
                                'message' => 'Actividades mensuales creadas',
                                'details' => 'Delegado: ' . $activity->delegate_id,
                            ]);

                            // Emitir un evento de navegador
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Actividades mensuales creadas',
                            ]);

                            break;
                        case 'Yearly':
                            // Guardar información de recurrencia
                            $activityRecurrent = new ActivityRecurrent();
                            $activityRecurrent->activity_repeat = $activity->activity_repeat;
                            $activityRecurrent->frequency = $this->repeat;
                            $activityRecurrent->day_created = $activity->created_at;
                            // Aumentar un año a la fecha last_date
                            $lastDate = Carbon::parse($this->expected_date)->addYear(); // Incrementa un año
                            $activityRecurrent->last_date = $lastDate;
                            $activityRecurrent->end_date = $this->end_date;
                            $activityRecurrent->save();

                            Log::create([
                                'user_id' => Auth::id(),
                                'activity_id' => $activity->id,
                                'view' => 'livewire/modals/reports-activities/edit',
                                'action' => 'Creación de actividad',
                                'message' => 'Actividad anual creada',
                                'details' => 'Delegado: ' . $activity->delegate_id,
                            ]);

                            // Emitir un evento de navegador
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Actividad anual creada',
                            ]);

                            break;
                        default:
                            Log::create([
                                'user_id' => Auth::id(),
                                'activity_id' => $activity->id,
                                'view' => 'livewire/modals/reports-activities/edit',
                                'action' => 'Creación de actividad',
                                'message' => 'Actividad creada',
                                'details' => 'Delegado: ' . $activity->delegate_id,
                            ]);

                            // Emitir un evento de navegador
                            $this->dispatchBrowserEvent('swal:modal', [
                                'type' => 'success',
                                'title' => 'Actividad actualizada',
                            ]);
                    }

                    if (!empty($this->selectedFiles) || !empty(array_filter($this->selectedFiles))) {
                        $activityFiles = ActivityFiles::where('activity_id', $activity->id)->get();
                        // Eliminar los archivos seleccionados
                        foreach ($this->selectedFiles as $fileId) {
                            // Buscar el archivo en la colección de archivos
                            $fileToDelete = $activityFiles->where('id', $fileId)->first();
                            // Verificar si se encontró el archivo
                            if ($fileToDelete) {
                                // Eliminar el archivo físico si existe en el disco
                                if (Storage::disk('activities')->exists($fileToDelete->route)) {
                                    Storage::disk('activities')->delete($fileToDelete->route);
                                }
                                // Eliminar el archivo de la base de datos
                                $fileToDelete->delete();
                                
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Archivo eliminado.',
                                ]);
                            }
                        }
                        // Recargar el registro con relaciones frescas
                        $this->recording = $activity->fresh()->load('files');
                                
                        // Resetear variables temporales
                        $this->reset(['filesNew', 'selectedFiles']);
                        
                        // Notificar éxito
                        $this->dispatchBrowserEvent('notify', [
                            'type' => 'success',
                            'message' => 'Archivos eliminados correctamente'
                        ]);
                    }

                    if (!empty($this->filesNew) || !empty(array_filter($this->filesNew))) {
                        foreach ($this->filesNew as $index => $file) {
                            // $file = $fileArray[0];
                            if ($file instanceof \Livewire\TemporaryUploadedFile) {
                                $fileExtension = $file->extension();
                                $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                                $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];

                                // Sanitizar nombres de archivo y directorios
                                $fileName = 'Actividad_' . $projectName . '_' . $dateString . '.' . $fileExtension;
                                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $customerName . '/' . $projectName;
                                $fullNewFilePath = $filePath . '/' . $fileName;
                                
                                if (in_array($fileExtension, $extensionesImagen)) {
                                    $maxSize = 5 * 1024 * 1024; // 5 MB
                                    // Verificar el tamaño del archivo
                                    if ($file->getSize() > $maxSize) {
                                        $this->dispatchBrowserEvent('swal:modal', [
                                            'type' => 'error',
                                            'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.',
                                        ]);
                                        return;
                                    }
                                    // Procesar la imagen
                                    $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
                                    // Redimensionar la imagen si es necesario
                                    $image->resize(800, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                    });
                                    // Guardar la imagen temporalmente
                                    $tempPath = $fileName; // Carpeta temporal dentro del almacenamiento
                                    $image->save(storage_path('app/' . $tempPath));
                                    // Guardar la imagen redimensionada en el almacenamiento local
                                    // Guardar directamente sin usar archivo temporal
                                    Storage::disk('activities')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                                    // Eliminar la imagen temporal
                                    Storage::disk('local')->delete($tempPath);

                                    $activityFile = new ActivityFiles();
                                    $activityFile->activity_id = $activity->id;
                                    $activityFile->route = $fullNewFilePath;
                                    $activityFile->image = true;
                                    $activityFile->save();
                                } else if (in_array($fileExtension, $extensionesVideo)) {
                                    // Guardar la imagen redimensionada en el almacenamiento local
                                    $file->storeAs('', $fullNewFilePath, 'activities');

                                    $activityFile = new ActivityFiles();
                                    $activityFile->activity_id = $activity->id;
                                    $activityFile->route = $fullNewFilePath;
                                    $activityFile->video = true;
                                    $activityFile->save();
                                } else {
                                    // Guardar la imagen redimensionada en el almacenamiento local
                                    $file->storeAs('', $fullNewFilePath, 'activities');
                                    
                                    $activityFile = new ActivityFiles();
                                    $activityFile->activity_id = $activity->id;
                                    $activityFile->route = $fullNewFilePath;
                                    $activityFile->file = true;
                                    $activityFile->save();
                                }
                            }
                        }

                        // Recargar el registro con relaciones frescas
                        $this->recording = $activity->fresh()->load('files');
                        
                        // Resetear variables temporales
                        $this->reset(['filesNew', 'selectedFiles']);
                        
                        // Notificar éxito
                        $this->dispatchBrowserEvent('notify', [
                            'type' => 'success',
                            'message' => 'Archivos guardados correctamente'
                        ]);
                    }
                } else {
                    $ActivityRepeat = Activity::where('activity_repeat', $activity->activity_repeat)->get();
                    $activitiesRecurrents = ActivityRecurrent::where('activity_repeat', $activity->activity_repeat)->first();

                    $activityExpectedDate = Carbon::parse($activity->expected_date)->startOfDay(); // Solo toma la fecha (sin hora)
                    $thisExpectedDate = Carbon::parse($this->expected_date)->startOfDay(); // Solo toma la fecha (sin hora)
                    // Actualizacion de fecha de entrega
                    if ($activityExpectedDate != $thisExpectedDate) {
                        // actualizar fecha de entrega de la actividad
                        $activity->expected_date = $this->expected_date;
                        $activity->save();

                        if ($ActivityRepeat->count() == 1) { // solo existe una actividad recurrente
                            switch ($activitiesRecurrents->frequency) {
                                case 'Dairy':
                                    if ($activitiesRecurrents->end_date != null) {
                                        $activitiesRecurrents->end_date = Carbon::parse($thisExpectedDate);
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate);
                                    } else {
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate)->addDay();
                                    }
                                    $activitiesRecurrents->save();
                                    break;
                                case 'Weekly':
                                    if ($activitiesRecurrents->end_date != null) {
                                        $activitiesRecurrents->end_date = Carbon::parse($thisExpectedDate);
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate);
                                    } else {
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate)->addWeek();
                                    }
                                    $activitiesRecurrents->save();
                                    break;
                                case 'Monthly':
                                    if ($activitiesRecurrents->end_date != null) {
                                        $activitiesRecurrents->end_date = Carbon::parse($thisExpectedDate);
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate);
                                    } else {
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate)->addMonth();
                                    }
                                    $activitiesRecurrents->save();
                                    break;
                                case 'Yearly':
                                    if ($activitiesRecurrents->end_date != null) {
                                        $activitiesRecurrents->end_date = Carbon::parse($thisExpectedDate);
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate);
                                    } else {
                                        $activitiesRecurrents->last_date = Carbon::parse($thisExpectedDate)->addYear();
                                    }
                                    $activitiesRecurrents->save();
                                    break;
                                default:
                                    break;
                            }
                        } else {
                            $lastActivityRepeat = Activity::where('activity_repeat', $activity->activity_repeat)->orderBy('expected_date', 'desc')->latest()->first();

                            $activitiesRecurrents->last_date = Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d');

                            if ($activitiesRecurrents->end_date != null) {
                                $activitiesRecurrents->end_date = Carbon::parse($lastActivityRepeat->expected_date)->format('Y-m-d');
                            }

                            $activitiesRecurrents->save();
                        }
                    }
                    // Guardar actividad antes de crear repeticiones
                    // $activity->save();

                    // ACTUALIZACION DE REPETICIONES
                    if ($this->repeat_updated) {
                        $activitiesNoResueltas = $ActivityRepeat->whereIn('state', ['Proceso', 'Conflicto']);
                        $this->activitiesNoResueltas = $activitiesNoResueltas->isNotEmpty() ? $activitiesNoResueltas : null;

                        $activitiesAbiertas = $ActivityRepeat->where('state', 'Abierto');
                        $this->activitiesAbiertas = $activitiesAbiertas->isNotEmpty() ? $activitiesAbiertas : null;

                        switch ($this->repeat) {
                            case 'Dairy':
                                $this->createDailyRepetitions($activity);

                                Log::create([
                                    'user_id' => Auth::id(),
                                    'activity_id' => $activity->id,
                                    'view' => 'livewire/modals/reports-activities/edit',
                                    'action' => 'Actividades recurrentes',
                                    'message' => 'Actividades diarias actualizadas',
                                    'details' => 'Delegado: ' . $activity->delegate_id,
                                ]);

                                // Emitir un evento de navegador
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Actividades diarias actualizadas',
                                ]);

                                break;
                            case 'Weekly':
                                $this->createWeeklyRepetitions($activity);

                                Log::create([
                                    'user_id' => Auth::id(),
                                    'activity_id' => $activity->id,
                                    'view' => 'livewire/modals/reports-activities/edit',
                                    'action' => 'Actividades recurrentes',
                                    'message' => 'Actividades semanales actualizadas',
                                    'details' => 'Delegado: ' . $activity->delegate_id,
                                ]);

                                // Emitir un evento de navegador
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Actividades semanales actualizadas',
                                ]);

                                break;
                            case 'Monthly':
                                $this->createMonthlyRepetitions($activity);

                                Log::create([
                                    'user_id' => Auth::id(),
                                    'activity_id' => $activity->id,
                                    'view' => 'livewire/modals/reports-activities/edit',
                                    'action' => 'Actividades recurrentes',
                                    'message' => 'Actividades mensuales actualizadas',
                                    'details' => 'Delegado: ' . $activity->delegate_id,
                                ]);

                                // Emitir un evento de navegador
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Actividades mensuales actualizadas',
                                ]);

                                break;
                            case 'Yearly':
                                $this->createYearlyRepetitions($activity);

                                Log::create([
                                    'user_id' => Auth::id(),
                                    'activity_id' => $activity->id,
                                    'view' => 'livewire/modals/reports-activities/edit',
                                    'action' => 'Creación de actividad',
                                    'message' => 'Actividad anual actualizadas',
                                    'details' => 'Delegado: ' . $activity->delegate_id,
                                ]);

                                // Emitir un evento de navegador
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Actividad anual actualizadas',
                                ]);

                                break;
                            default:
                                $this->updatedNotRepetitions($activity);

                                Log::create([
                                    'user_id' => Auth::id(),
                                    'activity_id' => $activity->id,
                                    'view' => 'livewire/modals/reports-activities/edit',
                                    'action' => 'No repeticiones',
                                    'message' => 'Desactivar repeticiones correctamente',
                                ]);

                                // Emitir un evento de navegador
                                $this->dispatchBrowserEvent('swal:modal', [
                                    'type' => 'success',
                                    'title' => 'Actividades sin repeticiones',
                                ]);
                        }
                    } else {
                        Log::create([
                            'user_id' => Auth::id(),
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/edit',
                            'action' => 'Actividad actualizada',
                            'message' => 'Actividad actualizada correctamente',
                        ]);

                        // Emitir un evento de navegador
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'success',
                            'title' => 'Actividad actualizada',
                        ]);
                    }
                }

                $this->dispatchBrowserEvent('file-reset');
            } catch (\Exception $e) {
                // Guardar el error en la base de datos
                ErrorLog::create([
                    'user_id' => Auth::id(),
                    'view' => 'livewire/modals/reports-activities/edit',
                    'action' => 'update',
                    'message' => 'Error al guardar la actividad',
                    'details' => $e->getMessage(),
                ]);

                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Ocurrió un error al guardar la actividad',
                ]);

                throw $e;
            }
        }
    }

    private function updatedNotRepetitions($task)
    {
        try {
            $activities = Activity::where('activity_repeat', $task->activity_repeat)->get();
            foreach ($activities as $activity) {
                $activity->activity_repeat = null;
                $activity->save(); // Guardar cambios en la BD
            }

            $activityRecurrent = ActivityRecurrent::where('activity_repeat', $task->activity_repeat)->first();
            if ($activityRecurrent) {
                $activityRecurrent->delete();
            }
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/modals/reports-activities/edit',
                'action' => 'No repeticiones',
                'message' => 'Error al desactivar las repeticiones',
                'details' => $e->getMessage(),
            ]);

            // Emitir evento para notificar error en el navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error al desactivar las repeticiones',
            ]);
        }
    }

    private function createDailyRepetitions($task)
    {
        try {
            $startDate = Carbon::now();

            if ($this->repeat_updated) {
                $expectedDate = Carbon::parse($this->expected_date);
            } else {
                $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addDay() : null;
            }

            $deadlineOneDay = $this->end_date ? Carbon::parse($this->end_date)->addDay() : null;
            $deadline = $this->end_date ? Carbon::parse($this->end_date) : null;
            // Calcular la diferencia en días
            $daysDifference = $deadlineOneDay ? $expectedDate->diffInDays($deadlineOneDay) : Carbon::now()->diffInDays(Carbon::now()->addMonths(1));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

            if ($this->repeat_updated) {
                // Actualizar actividades en Proceso o Conflicto
                if ($this->activitiesNoResueltas != null) {
                    foreach ($this->activitiesNoResueltas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 day');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 day');
                }

                // Actualizar actividades Abiertas
                if ($this->activitiesAbiertas != null) {
                    if ($this->activitiesNoResueltas != null) {
                        $tempExpectedDate->modify('+1 day');
                    }

                    foreach ($this->activitiesAbiertas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 day');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 day');
                }

                // Sobran fechas
                if (!is_null($deadline) && $tempExpectedDate > $deadline) {
                    foreach ($this->activitiesAbiertas as $activity) {
                        $expectedDateActivity = Carbon::parse($activity->expected_date);

                        if ($expectedDateActivity > $deadline) {
                            if ($activity->image) {
                                $contentPath = 'activities/' . $activity->image;
                                $fullPath = public_path($contentPath);

                                if (File::exists($fullPath)) {
                                    File::delete($fullPath);
                                }
                            }

                            $activity->delete();
                            $tempExpectedDate->modify('-1 day');
                        }
                    }
                }
                // Faltan fechas
                if ($tempExpectedDate < $deadline) {
                    $daysDifference = $deadline ? $tempExpectedDate->diffInDays($deadline) : Carbon::now()->diffInDays(Carbon::now()->addMonths(1));
                    // Preparar datos para inserción masiva
                    $events = [];
                    $tempExpectedDate->modify('+1 day');

                    for ($i = 0; $i < $daysDifference; $i++) {
                        $events[] = [
                            'sprint_id' => $task->sprint_id,
                            'user_id' => $task->user_id,
                            'delegate_id' => $task->delegate_id,
                            'icon' => $task->icon,
                            'title' => $task->title,
                            'content' => $task->content,
                            'description' => $task->description,
                            'priority' => $task->priority,
                            'state' => 'Abierto',
                            'points' => $task->points,
                            'questions_points' => $task->questions_points,
                            'activity_repeat' => $activityRepeat,
                            'delegated_date' => $task->delegated_date,
                            'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                            'created_at' => $startDate,
                            'updated_at' => $startDate,
                        ];

                        $tempStartDate->modify('+1 day');
                        // Avanzar un día sin modificar el original
                        if ($tempExpectedDate) {
                            $tempExpectedDate->modify('+1 day');
                        }
                    }
                    // Restar una semana al resultado final
                    $tempExpectedDate->modify('-1 day');

                    // Inserción masiva en lotes si es necesario
                    foreach (array_chunk($events, 500) as $batch) {
                        Activity::insert($batch);
                    }
                }
            } else {
                // Preparar datos para inserción masiva
                $events = [];

                for ($i = 0; $i < $daysDifference; $i++) {
                    $events[] = [
                        'sprint_id' => $task->sprint_id,
                        'user_id' => $task->user_id,
                        'delegate_id' => $task->delegate_id,
                        'icon' => $task->icon,
                        'title' => $task->title,
                        'content' => $task->content,
                        'description' => $task->description,
                        'priority' => $task->priority,
                        'state' => 'Abierto',
                        'points' => $task->points,
                        'questions_points' => $task->questions_points,
                        'activity_repeat' => $activityRepeat,
                        'delegated_date' => $task->delegated_date,
                        'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                        'created_at' => $startDate,
                        'updated_at' => $startDate,
                    ];

                    $tempStartDate->modify('+1 day');
                    // Avanzar un día sin modificar el original
                    if ($tempExpectedDate) {
                        $tempExpectedDate->modify('+1 day');
                    }
                }
                // Restar una semana al resultado final
                $tempExpectedDate->modify('-1 day');
                // Inserción masiva en lotes si es necesario
                foreach (array_chunk($events, 500) as $batch) {
                    Activity::insert($batch);
                }
            }
            // Versión mejorada para guardar recurrencia
            $recurrentData = [
                'activity_repeat' => $task->activity_repeat ?? bin2hex(random_bytes(8)),
                'frequency' => $this->repeat,
                'last_date' => $tempExpectedDate->format('Y-m-d H:i:s'),
                'end_date' => $this->end_date ? Carbon::parse($this->end_date) : null
            ];

            if ($this->repeat_updated) {
                $recurrentData['day_created'] = now();
                ActivityRecurrent::updateOrCreate(
                    ['activity_repeat' => $task->activity_repeat],
                    $recurrentData
                );
            } else {
                $recurrentData['day_created'] = $startDate;
                ActivityRecurrent::create($recurrentData);
            }
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/modals/reports-activities/edit',
                'action' => 'Crear repeticiones diarias',
                'message' => 'Error al crear repeticiones diarias',
                'details' => $e->getMessage(),
            ]);

            // Emitir evento para notificar error en el navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error al crear repeticiones diarias',
            ]);
        }
    }

    private function createWeeklyRepetitions($task)
    {
        try {
            $startDate = Carbon::now();

            if ($this->repeat_updated) {
                $expectedDate = Carbon::parse($this->expected_date);
            } else {
                $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addWeek() : null;
            }

            $deadlineOneDay = $this->end_date ? Carbon::parse($this->end_date)->addDay() : null;
            $deadline = $this->end_date ? Carbon::parse($this->end_date) : null;
            // Calcular la diferencia en días
            $daysDifference = $deadlineOneDay ? $expectedDate->diffInWeeks($deadlineOneDay) + 1 : Carbon::now()->diffInWeeks(Carbon::now()->addMonths(1));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

            if ($this->repeat_updated) {
                if ($this->activitiesNoResueltas != null) {
                    // Actualizar actividades en Proceso o Conflicto
                    foreach ($this->activitiesNoResueltas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 week');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 week');
                }

                // Actualizar actividades Abiertas
                if ($this->activitiesAbiertas != null) {
                    if ($this->activitiesNoResueltas != null) {
                        $tempExpectedDate->modify('+1 week');
                    }

                    foreach ($this->activitiesAbiertas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 week');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 week');
                }

                // Sobran fechas
                if (!is_null($deadline)  && $tempExpectedDate > $deadline) {
                    foreach ($this->activitiesAbiertas as $activity) {
                        $expectedDateActivity = Carbon::parse($activity->expected_date);

                        if ($expectedDateActivity > $deadline) {
                            if ($activity->image) {
                                $contentPath = 'activities/' . $activity->image;
                                $fullPath = public_path($contentPath);

                                if (File::exists($fullPath)) {
                                    File::delete($fullPath);
                                }
                            }
                            $activity->delete();
                            $tempExpectedDate->modify('-1 week');
                        }
                    }
                }
                // Faltan fechas
                if ($tempExpectedDate < $deadline) {
                    $daysDifference = $deadline ? $tempExpectedDate->diffInWeeks($deadline) : Carbon::now()->diffInWeeks(Carbon::now()->addMonths(1));
                    // Preparar datos para inserción masiva
                    $events = [];
                    $tempExpectedDate->modify('+1 week');

                    for ($i = 0; $i < $daysDifference; $i++) {
                        $events[] = [
                            'sprint_id' => $task->sprint_id,
                            'user_id' => $task->user_id,
                            'delegate_id' => $task->delegate_id,
                            'icon' => $task->icon,
                            'title' => $task->title,
                            'content' => $task->content,
                            'description' => $task->description,
                            'priority' => $task->priority,
                            'state' => 'Abierto',
                            'points' => $task->points,
                            'questions_points' => $task->questions_points,
                            'activity_repeat' => $activityRepeat,
                            'delegated_date' => $task->delegated_date,
                            'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                            'created_at' => $startDate,
                            'updated_at' => $startDate,
                        ];

                        $tempStartDate->modify('+1 week');
                        // Avanzar un día sin modificar el original
                        if ($tempExpectedDate) {
                            $tempExpectedDate->modify('+1 week');
                        }
                    }
                    // Restar una semana al resultado final
                    $tempExpectedDate->modify('-1 week');

                    // Inserción masiva en lotes si es necesario
                    foreach (array_chunk($events, 500) as $batch) {
                        Activity::insert($batch);
                    }
                }
            } else {
                // Preparar datos para inserción masiva
                $events = [];

                for ($i = 0; $i < $daysDifference; $i++) {
                    $events[] = [
                        'sprint_id' => $task->sprint_id,
                        'user_id' => $task->user_id,
                        'delegate_id' => $task->delegate_id,
                        'icon' => $task->icon,
                        'title' => $task->title,
                        'content' => $task->content,
                        'description' => $task->description,
                        'priority' => $task->priority,
                        'state' => 'Abierto',
                        'points' => $task->points,
                        'questions_points' => $task->questions_points,
                        'activity_repeat' => $activityRepeat,
                        'delegated_date' => $task->delegated_date,
                        'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                        'created_at' => $startDate,
                        'updated_at' => $startDate,
                    ];

                    $tempStartDate->modify('+1 week');
                    // Avanzar un día sin modificar el original
                    if ($tempExpectedDate) {
                        $tempExpectedDate->modify('+1 week');
                    }
                }
                // Restar una semana al resultado final
                $tempExpectedDate->modify('-1 week');
                // Inserción masiva en lotes si es necesario
                foreach (array_chunk($events, 500) as $batch) {
                    Activity::insert($batch);
                }
            }
            // Versión mejorada para guardar recurrencia
            $recurrentData = [
                'activity_repeat' => $task->activity_repeat ?? bin2hex(random_bytes(8)),
                'frequency' => $this->repeat,
                'last_date' => $tempExpectedDate->format('Y-m-d H:i:s'),
                'end_date' => $this->end_date ? Carbon::parse($this->end_date) : null
            ];

            if ($this->repeat_updated) {
                $recurrentData['day_created'] = now();
                ActivityRecurrent::updateOrCreate(
                    ['activity_repeat' => $task->activity_repeat],
                    $recurrentData
                );
            } else {
                $recurrentData['day_created'] = $startDate;
                ActivityRecurrent::create($recurrentData);
            }
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/modals/reports-activities/edit',
                'action' => 'Crear repeticiones semanales',
                'message' => 'Error al crear repeticiones semanales',
                'details' => $e->getMessage(),
            ]);

            // Emitir evento para notificar error en el navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error al crear repeticiones semanales',
            ]);
        }
    }

    private function createMonthlyRepetitions($task)
    {
        try {
            $startDate = Carbon::now();

            if ($this->repeat_updated) {
                $expectedDate = Carbon::parse($this->expected_date);
            } else {
                $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addMonth() : null;
            }

            $deadlineOneDay = $this->end_date ? Carbon::parse($this->end_date)->addDay() : null;
            $deadline = $this->end_date ? Carbon::parse($this->end_date) : null;
            // Calcular la diferencia en días
            $daysDifference = $deadlineOneDay ? $expectedDate->diffInMonths($deadlineOneDay) + 1 : Carbon::now()->diffInMonths(Carbon::now()->addMonths(3));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

            if ($this->repeat_updated) {
                // Actualizar actividades en Proceso o Conflicto
                if ($this->activitiesNoResueltas != null) {
                    foreach ($this->activitiesNoResueltas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 month');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 month');
                }

                // Actualizar actividades Abiertas
                if ($this->activitiesAbiertas != null) {
                    if ($this->activitiesNoResueltas != null) {
                        $tempExpectedDate->modify('+1 month');
                    }

                    foreach ($this->activitiesAbiertas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 month');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 month');
                }

                // Sobran fechas
                if (!is_null($deadline) && $tempExpectedDate > $deadline) {
                    foreach ($this->activitiesAbiertas as $activity) {
                        $expectedDateActivity = Carbon::parse($activity->expected_date);

                        if ($expectedDateActivity > $deadline) {
                            if ($activity->image) {
                                $contentPath = 'activities/' . $activity->image;
                                $fullPath = public_path($contentPath);

                                if (File::exists($fullPath)) {
                                    File::delete($fullPath);
                                }
                            }

                            $activity->delete();
                            $tempExpectedDate->modify('-1 month');
                        }
                    }
                }

                // Faltan fechas
                if ($tempExpectedDate < $deadline) {
                    $daysDifference = $deadline ? $tempExpectedDate->diffInMonths($deadline) : Carbon::now()->diffInMonths(Carbon::now()->addMonths(3));
                    // Preparar datos para inserción masiva
                    $events = [];
                    $tempExpectedDate->modify('+1 month');

                    for ($i = 0; $i < $daysDifference; $i++) {
                        $events[] = [
                            'sprint_id' => $task->sprint_id,
                            'user_id' => $task->user_id,
                            'delegate_id' => $task->delegate_id,
                            'icon' => $task->icon,
                            'title' => $task->title,
                            'content' => $task->content,
                            'description' => $task->description,
                            'priority' => $task->priority,
                            'state' => 'Abierto',
                            'points' => $task->points,
                            'questions_points' => $task->questions_points,
                            'activity_repeat' => $activityRepeat,
                            'delegated_date' => $task->delegated_date,
                            'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                            'created_at' => $startDate,
                            'updated_at' => $startDate,
                        ];

                        $tempStartDate->modify('+1 month');
                        // Avanzar un día sin modificar el original
                        if ($tempExpectedDate) {
                            $tempExpectedDate->modify('+1 month');
                        }
                    }
                    // Restar una semana al resultado final
                    $tempExpectedDate->modify('-1 month');

                    // Inserción masiva en lotes si es necesario
                    foreach (array_chunk($events, 500) as $batch) {
                        Activity::insert($batch);
                    }
                }
            } else {
                // Preparar datos para inserción masiva
                $events = [];

                for ($i = 0; $i < $daysDifference; $i++) {
                    $events[] = [
                        'sprint_id' => $task->sprint_id,
                        'user_id' => $task->user_id,
                        'delegate_id' => $task->delegate_id,
                        'icon' => $task->icon,
                        'title' => $task->title,
                        'content' => $task->content,
                        'description' => $task->description,
                        'priority' => $task->priority,
                        'state' => 'Abierto',
                        'points' => $task->points,
                        'questions_points' => $task->questions_points,
                        'activity_repeat' => $activityRepeat,
                        'delegated_date' => $task->delegated_date,
                        'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                        'created_at' => $startDate,
                        'updated_at' => $startDate,
                    ];

                    // Avanzar un día sin modificar el original
                    $tempStartDate->modify('+1 month');
                    if ($tempExpectedDate) {
                        $tempExpectedDate->modify('+1 month');
                    }
                }
                // Restar una semana al resultado final
                $tempExpectedDate->modify('-1 month');

                // Inserción masiva en lotes si es necesario
                foreach (array_chunk($events, 500) as $batch) {
                    Activity::insert($batch);
                }
            }

            // Versión mejorada para guardar recurrencia
            $recurrentData = [
                'activity_repeat' => $task->activity_repeat ?? bin2hex(random_bytes(8)),
                'frequency' => $this->repeat,
                'last_date' => $tempExpectedDate->format('Y-m-d H:i:s'),
                'end_date' => $this->end_date ? Carbon::parse($this->end_date) : null
            ];

            if ($this->repeat_updated) {
                $recurrentData['day_created'] = now();
                ActivityRecurrent::updateOrCreate(
                    ['activity_repeat' => $task->activity_repeat],
                    $recurrentData
                );
            } else {
                $recurrentData['day_created'] = $startDate;
                ActivityRecurrent::create($recurrentData);
            }
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/modals/reports-activities/edit',
                'action' => 'Crear repeticiones mensuales',
                'message' => 'Error al crear repeticiones mensuales',
                'details' => $e->getMessage(),
            ]);

            // Emitir evento para notificar error en el navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error al crear repeticiones mensuales',
            ]);
        }
    }

    private function createYearlyRepetitions($task)
    {
        try {
            $startDate = Carbon::now();

            if ($this->repeat_updated) {
                $expectedDate = Carbon::parse($this->expected_date);
            } else {
                $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date)->addDay() : null;
            }

            $deadlineOneDay = $this->end_date ? Carbon::parse($this->end_date)->addDay() : null;
            $deadline = $this->end_date ? Carbon::parse($this->end_date) : null;
            // Calcular la diferencia en días
            $daysDifference = $deadlineOneDay ? $expectedDate->diffInYears($deadlineOneDay) : Carbon::now()->diffInYears(Carbon::now()->addYears(1));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

            if ($this->repeat_updated) {
                // Actualizar actividades en Proceso o Conflicto
                if ($this->activitiesNoResueltas != null) {
                    foreach ($this->activitiesNoResueltas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 year');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 year');
                }

                // Actualizar actividades Abiertas
                if ($this->activitiesAbiertas != null) {
                    if ($this->activitiesNoResueltas != null) {
                        $tempExpectedDate->modify('+1 year');
                    }

                    foreach ($this->activitiesAbiertas as $activity) {
                        // Avanzar la fecha de cada actividad
                        $activity->expected_date = $tempExpectedDate->format('Y-m-d H:i:s');
                        $activity->save(); // Guardar cambios en la BD
                        $tempExpectedDate->modify('+1 year');
                    }
                    // Volver a fecha original
                    $tempExpectedDate->modify('-1 year');
                }

                // Sobran fechas
                if (!is_null($deadline) && $tempExpectedDate > $deadline) {
                    foreach ($this->activitiesAbiertas as $activity) {
                        $expectedDateActivity = Carbon::parse($activity->expected_date);

                        if ($expectedDateActivity > $deadline) {
                            if ($activity->image) {
                                $contentPath = 'activities/' . $activity->image;
                                $fullPath = public_path($contentPath);

                                if (File::exists($fullPath)) {
                                    File::delete($fullPath);
                                }
                            }

                            $activity->delete();
                            $tempExpectedDate->modify('-1 year');
                        }
                    }
                }
                // Faltan fechas
                if ($tempExpectedDate < $deadline) {
                    $daysDifference = $deadline ? $tempExpectedDate->diffInYears($deadline) : Carbon::now()->diffInYears(Carbon::now()->addYears(1));
                    // Preparar datos para inserción masiva
                    $events = [];
                    $tempExpectedDate->modify('+1 year');

                    for ($i = 0; $i < $daysDifference; $i++) {
                        $events[] = [
                            'sprint_id' => $task->sprint_id,
                            'user_id' => $task->user_id,
                            'delegate_id' => $task->delegate_id,
                            'icon' => $task->icon,
                            'title' => $task->title,
                            'content' => $task->content,
                            'description' => $task->description,
                            'priority' => $task->priority,
                            'state' => 'Abierto',
                            'points' => $task->points,
                            'questions_points' => $task->questions_points,
                            'activity_repeat' => $activityRepeat,
                            'delegated_date' => $task->delegated_date,
                            'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                            'created_at' => $startDate,
                            'updated_at' => $startDate,
                        ];

                        $tempStartDate->modify('+1 year');
                        // Avanzar un día sin modificar el original
                        if ($tempExpectedDate) {
                            $tempExpectedDate->modify('+1 year');
                        }
                    }
                    // Restar una semana al resultado final
                    $tempExpectedDate->modify('-1 year');

                    // Inserción masiva en lotes si es necesario
                    foreach (array_chunk($events, 500) as $batch) {
                        Activity::insert($batch);
                    }
                }
            } else {
                // Preparar datos para inserción masiva
                $events = [];

                for ($i = 0; $i < $daysDifference; $i++) {
                    $events[] = [
                        'sprint_id' => $task->sprint_id,
                        'user_id' => $task->user_id,
                        'delegate_id' => $task->delegate_id,
                        'icon' => $task->icon,
                        'title' => $task->title,
                        'content' => $task->content,
                        'description' => $task->description,
                        'priority' => $task->priority,
                        'state' => 'Abierto',
                        'points' => $task->points,
                        'questions_points' => $task->questions_points,
                        'activity_repeat' => $activityRepeat,
                        'delegated_date' => $task->delegated_date,
                        'expected_date' => $tempExpectedDate ? $tempExpectedDate->format('Y-m-d H:i:s') : null, // Verificación de null
                        'created_at' => $startDate,
                        'updated_at' => $startDate,
                    ];

                    $tempStartDate->modify('+1 year');
                    // Avanzar un día sin modificar el original
                    if ($tempExpectedDate) {
                        $tempExpectedDate->modify('+1 year');
                    }
                }
                // Restar una semana al resultado final
                $tempExpectedDate->modify('-1 year');
                // Inserción masiva en lotes si es necesario
                foreach (array_chunk($events, 500) as $batch) {
                    Activity::insert($batch);
                }
            }
            // Versión mejorada para guardar recurrencia
            $recurrentData = [
                'activity_repeat' => $task->activity_repeat ?? bin2hex(random_bytes(8)),
                'frequency' => $this->repeat,
                'last_date' => $tempExpectedDate->format('Y-m-d H:i:s'),
                'end_date' => $this->end_date ? Carbon::parse($this->end_date) : null
            ];

            if ($this->repeat_updated) {
                $recurrentData['day_created'] = now();
                ActivityRecurrent::updateOrCreate(
                    ['activity_repeat' => $task->activity_repeat],
                    $recurrentData
                );
            } else {
                $recurrentData['day_created'] = $startDate;
                ActivityRecurrent::create($recurrentData);
            }
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/modals/reports-activities/edit',
                'action' => 'Crear repeticiones diarias',
                'message' => 'Error al crear repeticiones diarias',
                'details' => $e->getMessage(),
            ]);

            // Emitir evento para notificar error en el navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error al crear repeticiones diarias',
            ]);
        }
    }
}
