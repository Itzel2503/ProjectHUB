<?php

namespace App\Http\Livewire\Modals\ReportsActivities;

use App\Models\Activity;
use App\Models\ActivityFiles;
use App\Models\ActivityRecurrent;
use App\Models\ErrorLog;
use App\Models\Log;
use App\Models\Sprint;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Create extends Component
{
    use WithFileUploads;
    use WithPagination;

    // ENVIADAS
    public $project, $sprint;

    public $recording;
    // DINAMICOS
    public $changePoints = true, $chooseEndDate = false;
    public $allUsers;
    public $selectedIcon = null;
    public $repeatMessage = '', $endDateMessage = '', $expectedDateMessage = '';
    // INPUTS
    public $files = [];
    public $title, $description, $delegate, $expected_date, $priority, $repeat, $end_date, $points, $point_know, $point_many, $point_effort;

    public function render()
    {
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();

        return view('livewire.modals.reports-activities.create');
    }

    public function selectIcon($icon)
    {
        $this->selectedIcon = $icon;
    }

    public function expected_date($day)
    {
        $now = Carbon::now()->format('Y-m-d'); // Fecha actual en formato 'Y-m-d'
        
        // Convertir $day a Carbon para comparar
        $dayFormatted = Carbon::parse($day)->format('Y-m-d');

        // Verificar si $day es menor que $now
        if ($dayFormatted < $now) {
            $this->expected_date = $now;
            $this->expectedDateMessage = 'La fecha de entrega debe ser posterior o igual a la fecha actual.';
        } else {
            $this->expectedDateMessage = '';
        }
    }

    public function repeat($type)
    {
        $this->chooseEndDate = ($type != 'Once') ? true : false;

        ($type == 'Once') ? $this->end_date = '' : '';

        switch ($type) {
            case 'Dairy':
                $this->repeatMessage = 'Se creará diariamente por un mes, luego continuará.';
                break;
            case 'Weekly':
                $this->repeatMessage = 'Se creará semanalmente por un mes, luego continuará.';
                break;
            case 'Monthly':
                $this->repeatMessage = 'Se creará mensualmente por 3 meses, luego continuará.';
                break;
            case 'Yearly':
                $this->repeatMessage = 'Se creará anualmente un mes antes.';
                break;
            default:
                $this->repeatMessage = '';
                break;
        }
    }

    public function end_date($day)
    {
        $now = Carbon::now()->format('Y-m-d'); // Fecha actual en formato 'Y-m-d'
        
        // Convertir $day a Carbon para comparar
        $dayFormatted = ($day != '') ? Carbon::parse($day)->format('Y-m-d') : null;

        // Verificar si $day es menor que $now
        if ($dayFormatted <= $now && $dayFormatted != null) {
            $this->end_date = Carbon::parse($now)->addDay()->format('Y-m-d');
            $this->endDateMessage = 'La fecha de entrega debe ser posterior a la fecha actual.';
        } else {
            $this->endDateMessage = '';
        }
    }

    public function selectPriority($value)
    {
        // Si la prioridad seleccionada es igual al valor actual, deselecciona
        $this->priority = $this->priority === $value ? null : $value;
    }

    public function addInput()
    {
        $this->files[] = null;
    }

    public function removeInput($index)
    {
        unset($this->files[$index]);
        $this->files = array_values($this->files); // Reindexar el array
    }

    public function changePoints()
    {
        // Alternar el estado de $changePoints
        if ($this->changePoints == true) {
            $this->changePoints = false;
            $this->points = '';
        } else {
            $this->changePoints = true;
            $this->point_know = '';
            $this->point_many = '';
            $this->point_effort = '';
        }
    }

    public function create()
    {
        try {
            $rules = [
                'title' => 'required|max:255',
                'delegate' => 'required',
            ];
            
            $messages = [
                'title.required' => 'El título es obligatorio.',
                'title.max:255' => 'El título no debe tener más caracteres que 255.',
                'delegate.required' => 'El delegado es obligatorio.',
            ];
            
            if ($this->repeat !== null) {
                $rules['expected_date'] = 'required';
                $messages['expected_date'] = 'La fecha de entrega es obligatoria.';
            }
            
            $this->validate($rules, $messages);

            $sprint = Sprint::find($this->sprint);

            if ($sprint->state == 'Cerrado') {
                $sprint->state = 'Curso';
                $sprint->save();
                $updateSprint = true;
            } else {
                $updateSprint = false;
            }

            $activity = new Activity();
            
            $activity->sprint_id = $this->sprint;
            $activity->delegate_id = $this->delegate;
            $activity->user_id = Auth::id();
            $activity->icon = ($this->selectedIcon == "🚫" || $this->selectedIcon == null) ? '' : $this->selectedIcon;
            $activity->title = $this->title;
            $activity->description = $this->description;
            $activity->priority = $this->priority;
            $activity->state = 'Abierto';
            if ($this->changePoints != true) {
                if (!$this->points) {
                    $activity->points = 0;
                } else {
                    $validPoints = [1, 2, 3, 5, 8, 13];
                    $activity->points = $this->points;

                    if (!in_array($this->points, $validPoints)) {
                        $this->dispatchBrowserEvent('swal:modal', [
                            'type' => 'error',
                            'title' => 'Puntuaje no válido.',
                        ]);
                        return; // O cualquier otra acción que desees realizar
                    } else {
                        $activity->points = $this->points;
                    }
                }
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
                if (!$this->point_know && !$this->point_many && !$this->point_effort) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'warning',
                        'title' => 'El formulario está incompleto o no se han seleccionado los puntos necesarios.',
                    ]);
                    $activity->points = 0;
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

            $activity->delegated_date = Carbon::now();
            $activity->expected_date = ($this->expected_date != '') ? $this->expected_date : null;

            // Crear repeticiones según el filtro seleccionado 
            switch ($this->repeat) {
                case 'Dairy':
                    $this->createDailyRepetitions($activity);

                    if ($updateSprint == true) {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Reactivación de Sprint',
                            'message' => 'Actividades diarias creadas',
                            'details' => 'Sprint: ' . $sprint->id,
                        ]);
                    } else {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Actividades recurrentes',
                            'message' => 'Actividades diarias creadas',
                            'details' => 'Delegado: ' . $activity->delegate_id,
                        ]);
                    }

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Actividades diarias creadas',
                    ]);

                    break;
                case 'Weekly':
                    $this->createWeeklyRepetitions($activity);

                    if ($updateSprint == true) {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Reactivación de Sprint',
                            'message' => 'Actividades semanales creadas',
                            'details' => 'Sprint: ' . $sprint->id,
                        ]);
                    } else {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Actividades recurrentes',
                            'message' => 'Actividades semanales creadas',
                            'details' => 'Delegado: ' . $activity->delegate_id,
                        ]);
                    }

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Actividades semanales creadas',
                    ]);

                    break;
                case 'Monthly':
                    $this->createMonthlyRepetitions($activity);

                    if ($updateSprint == true) {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Reactivación de Sprint',
                            'message' => 'Actividades mensuales creadas',
                            'details' => 'Sprint: ' . $sprint->id,
                        ]);
                    } else {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Actividades recurrentes',
                            'message' => 'Actividades mensuales creadas',
                            'details' => 'Delegado: ' . $activity->delegate_id,
                        ]);
                    }

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Actividades mensuales creadas',
                    ]);

                    break;
                case 'Yearly':
                    // No se repite (opción "Once")
                    $activityRepeat = bin2hex(random_bytes(8));
                    $activity->activity_repeat = $activityRepeat;
                    $activity->save();

                    // Guardar información de recurrencia
                    $activityRecurrent = new ActivityRecurrent();
                    $activityRecurrent->activity_repeat = $activityRepeat;
                    $activityRecurrent->frequency = $this->repeat;
                    $activityRecurrent->day_created = $activity->created_at;
                    // Aumentar un año a la fecha last_date
                    $lastDate = Carbon::parse($this->expected_date)->addYear(); // Incrementa un año
                    $activityRecurrent->last_date = $lastDate;
                    $activityRecurrent->end_date = $this->end_date;
                    $activityRecurrent->save();

                    if ($updateSprint == true) {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Reactivación de Sprint',
                            'message' => 'Actividad anual creada',
                            'details' => 'Sprint: ' . $sprint->id,
                        ]);
                    } else {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Creación de actividad',
                            'message' => 'Actividad anual creada',
                            'details' => 'Delegado: ' . $activity->delegate_id,
                        ]);
                    }

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Actividad anual creada',
                    ]);

                    break;
                default:
                    if ($updateSprint == true) {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Reactivación de Sprint',
                            'message' => 'Actividad creada',
                            'details' => 'Sprint: ' . $sprint->id,
                        ]);
                    } else {
                        Log::create([
                            'user_id' => Auth::id(),
                            'project_id' => $this->project->id,
                            'activity_id' => $activity->id,
                            'view' => 'livewire/modals/reports-activities/create',
                            'action' => 'Creación de actividad',
                            'message' => 'Actividad creada',
                            'details' => 'Delegado: ' . $activity->delegate_id,
                        ]);
                    }
                    // No se repite (opción "Once")
                    $activity->save();

                    if (!empty($this->files) || !empty(array_filter($this->files))) {
                        // Tu código aquí si $this->files no está vacío y al menos un elemento no es null
                        foreach ($this->files as $index => $fileArray) {
                            $file = $fileArray[0];
                            $fileExtension = $file->extension();
                            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
                            $extensionesVideo = ['mp4', 'mov', 'wmv', 'avi', 'avchd', 'flv', 'mkv', 'webm'];
        
                            $projectName = Str::slug($this->project->name, '_');
                            $customerName = Str::slug($this->project->customer->name, '_');
                            $now = Carbon::now();
                            $dateString = $now->format("Y-m-d H_i_s");
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

                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Actividad creada',
                    ]);
            }

            $this->clearInputs();
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Captura de errores de validación
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);

            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => $this->project->id,
                'view' => 'livewire/modals/reports-activities/create',
                'action' => 'Creación de actividad',
                'message' => 'Error en crear actividad',
                'details' => $e->getMessage(), // Mensaje de la excepción
            ]);

            throw $e;
        } catch (\Exception $e) {
            // Captura de cualquier otro error
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => $this->project->id,
                'view' => 'livewire/modals/reports-activities/create',
                'action' => 'Creación de actividad',
                'message' => 'Error en crear actividad',
                'details' => $e->getMessage(), // Mensaje de la excepción
            ]);

            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error inesperado. Por favor, inténtalo de nuevo.',
            ]);
        }
    }

    public function clearInputs()
    {
        $this->selectedIcon = null;
        $this->chooseEndDate = false;
        $this->changePoints = true;
        $this->repeatMessage = '';
        $this->expectedDateMessage = '';
        $this->title = '';
        $this->title = '';
        $this->description = '';
        $this->delegate = '';
        $this->expected_date = '';
        $this->repeat = '';
        $this->end_date = '';
        $this->priority = '';
        $this->files = [];
        $this->points = '';
        $this->point_know = '';
        $this->point_many = '';
        $this->point_effort = '';
        $this->dispatchBrowserEvent('file-reset');
    }

    private function createDailyRepetitions($task)
    {
        try {
            $startDate = Carbon::now();
            $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date) : null;
            $deadline = $this->end_date ? Carbon::parse($this->end_date)->addDay() : null;

            // Calcular la diferencia en días
            $daysDifference = $deadline ? $expectedDate->diffInDays($deadline) : Carbon::now()->diffInDays(Carbon::now()->addMonths(1));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));

            // Preparar datos para inserción masiva
            $events = [];
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

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
                    'state' => $task->state,
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

            // Guardar información de recurrencia
            $activityRecurrent = new ActivityRecurrent();
            $activityRecurrent->activity_repeat = $activityRepeat;
            $activityRecurrent->frequency = $this->repeat;
            $activityRecurrent->day_created = $startDate;
            $activityRecurrent->last_date = $tempExpectedDate->format('Y-m-d H:i:s'); // Última fecha generada
            $activityRecurrent->end_date = $this->end_date;
            $activityRecurrent->save();
        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => $this->project->id ?? null,
                'view' => 'livewire/modals/reports-activities/create',
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
            $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date) : null;
            $deadline = $this->end_date ? Carbon::parse($this->end_date)->addDay() : null;
            // Calcular la diferencia en días
            $daysDifference = $deadline ? $expectedDate->diffInWeeks($deadline) + 1 : Carbon::now()->diffInWeeks(Carbon::now()->addMonths(1));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));

            // Preparar datos para inserción masiva
            $events = [];
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

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
                    'state' => $task->state,
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

            // Guardar información de recurrencia
            $activityRecurrent = new ActivityRecurrent();
            $activityRecurrent->activity_repeat = $activityRepeat;
            $activityRecurrent->frequency = $this->repeat;
            $activityRecurrent->day_created = $startDate;
            $activityRecurrent->last_date = $tempExpectedDate->format('Y-m-d H:i:s'); // Última fecha generada
            $activityRecurrent->end_date = $this->end_date;
            $activityRecurrent->save();

        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => $this->project->id ?? null,
                'view' => 'livewire/modals/reports-activities/create',
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
            $expectedDate = $task->expected_date ? Carbon::parse($task->expected_date) : null;
            $deadline = $this->end_date ? Carbon::parse($this->end_date)->addDay() : null;
            // Calcular la diferencia en días
            $daysDifference = $deadline ? $expectedDate->diffInMonths($deadline) + 1 : Carbon::now()->diffInMonths(Carbon::now()->addMonths(3));

            // Obtener el identificador de repetición
            $activityRepeat = $task->activity_repeat ?? bin2hex(random_bytes(8));

            // Preparar datos para inserción masiva
            $events = [];
            $tempStartDate = clone $startDate;
            $tempExpectedDate = $expectedDate ? clone $expectedDate : null;

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
                    'state' => $task->state,
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

            // Guardar información de recurrencia
            $activityRecurrent = new ActivityRecurrent();
            $activityRecurrent->activity_repeat = $activityRepeat;
            $activityRecurrent->frequency = $this->repeat;
            $activityRecurrent->day_created = $startDate;
            $activityRecurrent->last_date = $tempExpectedDate->format('Y-m-d H:i:s'); // Última fecha generada
            $activityRecurrent->end_date = $this->end_date;
            $activityRecurrent->save();

        } catch (\Exception $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'project_id' => $this->project->id ?? null,
                'view' => 'livewire/modals/reports-activities/create',
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
}
