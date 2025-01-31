<?php

namespace App\Http\Livewire\Modals\ReportsActivities;

use App\Models\Activity;
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
    public $changePoints = true, $allUsers;
    // INPUTS
    public $title, $file, $description, $delegate, $expected_date, $priority, $points, $point_know, $point_many, $point_effort;

    public function render()
    {
        $this->allUsers = User::where('type_user', '!=', 3)->orderBy('name', 'asc')->get();

        return view('livewire.modals.reports-activities.create');
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

    public function selectPriority($value)
    {
        // Si la prioridad seleccionada es igual al valor actual, deselecciona
        $this->priority = $this->priority === $value ? null : $value;
    }

    public function create()
    {
        try {
            $this->validate(
                [
                    'title' => 'required|max:255',
                    'delegate' => 'required',
                    'expected_date' => 'required|date',
                ],
                [
                    'title.required' => 'El título es obligatorio.',
                    'title.max:255' => 'El título no debe tener más caracteres que 255.',
                    'delegate.required' => 'El delegado es obligatorio.',
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

        $activity = new Activity();
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
                $projectName = Str::slug($this->project->name, '_');
                $customerName = Str::slug($this->project->customer->name, '_');
                $now = Carbon::now();
                $dateString = $now->format("Y-m-d H_i_s");
                $fileExtension = $this->file->extension();
                // Sanitizar nombres de archivo y directorios
                $fileName = 'Actividad_' . $projectName . '_' . $dateString . '.' . $fileExtension;
                $filePath = now()->format('Y') . '/' . now()->format('F') . '/' . $customerName . '/' . $projectName;
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
                // Guardar la imagen redimensionada en el almacenamiento local
                Storage::disk('activities')->put($fullNewFilePath, Storage::disk('local')->get($tempPath));
                // // Eliminar la imagen temporal
                Storage::disk('local')->delete($tempPath);
                $activity->content = $fullNewFilePath;
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El archivo no es una imagen.',
                ]);
                return;
            }
        }
        $activity->sprint_id = $this->sprint;
        $activity->delegate_id = $this->delegate;
        $activity->user_id = Auth::id();
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
        $activity->expected_date = $this->expected_date;
        $activity->save();
        $this->clearInputs();
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Actividad creada',
        ]);
    }

    public function clearInputs()
    {
        $this->title = '';
        $this->description = '';
        $this->delegate = '';
        $this->expected_date = '';
        $this->priority = '';
        $this->points = '';
        $this->point_know = '';
        $this->point_many = '';
        $this->point_effort = '';
        $this->dispatchBrowserEvent('file-reset');
    }
}
