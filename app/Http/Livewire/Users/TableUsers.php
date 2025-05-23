<?php

namespace App\Http\Livewire\Users;

use App\Models\Area;
use App\Models\ErrorLog;
use App\Models\Log;
use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class TableUsers extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage', 'destroy', 'restore'];
    // modal
    public $modalCreateEdit = false;
    public $showUpdate = false;
    public $isClient = false;
    // table, action's user
    public $search, $userEdit, $areaUser;
    public $rules = [],
        $allAreas = [],
        $allTypes = [1, 2];
    // inputs
    public $file, $name, $date_birthday, $entry_date, $area, $type_user, $email, $password, $effort_points;
    public $projects = [];
    public $selectedProjects = [];

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $areas = Area::all();
        $areas = Area::whereNotIn('name', ['Cocina', 'Equipo audiovisual'])->get();
        $this->projects = Project::all();

        $users = User::onlyTrashed()
            ->select('users.*', 'areas.name as area_name')
            ->leftJoin('areas', 'users.area_id', '=', 'areas.id')
            ->where(function ($query) {
                if ($this->search == 'Administrador' || $this->search == 'administrador') {
                    $query->orWhere('users.type_user', 1);
                } elseif ($this->search == 'Usuario' || $this->search == 'usuario') {
                    $query->orWhere('users.type_user', 2);
                } elseif ($this->search == 'Activo' || $this->search == 'activo') {
                    $query->whereNull('users.deleted_at');
                } elseif ($this->search == 'Inactivo' || $this->search == 'inactivo') {
                    $query->whereNotNull('users.deleted_at');
                }
            })
            ->orWhere('users.name', 'like', '%' . $this->search . '%')
            ->orWhere('users.email', 'like', '%' . $this->search . '%')
            ->orWhere('areas.name', 'like', '%' . $this->search . '%')
            ->orderBy('name', 'asc')
            ->get();

        // Verificar si la foto de perfil existe para cada usuario
        foreach ($users as $user) {
            if ($user->profile_photo) {
                $filePath = public_path('usuarios/' . $user->profile_photo);
                $user->photo_exists = file_exists($filePath); // Agregar un atributo dinámico
            } else {
                $user->photo_exists = false; // Si no hay foto, establecer como false
            }
        }

        return view('livewire.users.table-users', [
            'users' => $users,
            'areas' => $areas,
        ]);
    }
    // ACTIONS
    public function create()
    {
        try {
            $rules = [
                'name' => 'required|max:255',
                'date_birthday' => 'required|date|max:255',
                'entry_date' => 'required|date|max:255',
                'type_user' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
                'effort_points' => 'required|numeric|between:0,100',
            ];

            if ($this->type_user == 3) {
                $rules['selectedProjects'] = 'required';
            } else {
                $rules['area'] = 'required';
            }

            $validator = Validator::make($this->all(), $rules);

            if ($validator->fails()) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Faltan campos o campos incorrectos',
                ]);
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            // Aquí puedes continuar con tu lógica después de la validación exitosa
            $user = new User();

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
                    $filePath = $this->file->getClientOriginalName();
                    // Procesar la imagen
                    $image = \Intervention\Image\Facades\Image::make($this->file->getRealPath());
                    // Redimensionar la imagen si es necesario
                    $image->resize(800, null, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    // Guardar la imagen temporalmente
                    $tempPath = $filePath; // Carpeta temporal dentro del almacenamiento
                    $image->save(storage_path('app/' . $tempPath));
                    // Guardar la imagen redimensionada en el almacenamiento local
                    Storage::disk('users')->put($filePath, Storage::disk('local')->get($tempPath));
                    // // Eliminar la imagen temporal
                    Storage::disk('local')->delete($tempPath);
                    $user->profile_photo = $filePath;
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo no está en formato de imagen.',
                    ]);
                    return;
                }
            }

            $user->name = $this->name;
            $user->date_birthday = $this->date_birthday;
            $user->entry_date = $this->entry_date;
            $user->email = $this->email;
            // Guardar relaciones en la tabla pivote
            if ($this->type_user == 3) {
                $user->area_id = 5;
            } else {
                $user->area_id = $this->area;
            }

            $user->type_user = $this->type_user;

            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->effort_points = $this->effort_points;
            $user->effort_points = 0;
            $user->save();
            // Guardar relaciones en la tabla pivote
            if ($this->type_user == 3) {
                foreach ($this->selectedProjects as $projectId) {
                    $user->projects()->attach($projectId, [
                        'client' => true,
                        'leader' => false,
                        'product_owner' => false,
                        'developer1' => false,
                        'developer2' => false,
                    ]);
                }
                $user->area_id = 5;
            }
            $this->clearInputs();
            $this->modalCreateEdit = false;

            if ($this->file) {
                $this->refreshPage();
            }

            Log::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/users/table-users',
                'action' => 'Crear usuario',
                'message' => 'Usuario creado: ' . $user->email,
            ]);

            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Usuario creado',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Registrar en el log de errores
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/users/table-users',
                'action' => 'Crear usuario',
                'message' => 'Faltan campos o campos incorrectos en el formulario',
                'details' => $e->getMessage(),
            ]);

            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/users/table-users',
                'action' => 'Crear usuario',
                'message' => 'El correo electrónico ya esta registrado',
                'details' => $e->getMessage(),
            ]);
            // Captura excepciones específicas de la base de datos si es necesario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error al crear el usuario',
                'text' => 'El correo electrónico ya está registrado.',
            ]);
        } catch (\Exception $e) {
            // Guardar el error en la base de datos
            ErrorLog::create([
                'user_id' => Auth::id(),
                'view' => 'livewire/users/table-users',
                'action' => 'Crear usuario',
                'message' => 'Error al crear el usuario',
                'details' => $e->getMessage(),
            ]);

            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Ocurrió un error al crear el usuario',
            ]);
            throw $e;
        }
    }

    public function update($id)
    {
        try {
            $rules = [
                'name' => 'required|max:255',
                'date_birthday' => 'required|date|max:255',
                'entry_date' => 'required|date|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'effort_points' => 'required|numeric|between:0,100',
            ];

            if ($this->type_user == 3) {
                $rules['selectedProjects'] = 'required';
            }

            $validator = Validator::make($this->all(), $rules);

            if ($validator->fails()) {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'Faltan campos o campos incorrectos',
                ]);
                throw new \Illuminate\Validation\ValidationException($validator);
            }
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $user = User::find($id);

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
                $filePath = $this->file->getClientOriginalName();
                // Procesar la imagen
                $image = \Intervention\Image\Facades\Image::make($this->file->getRealPath());
                // Redimensionar la imagen si es necesario
                $image->resize(800, null, function ($constraint) {
                    $constraint->aspectRatio();
                });
                // Guardar la imagen temporalmente
                $tempPath = $filePath; // Carpeta temporal dentro del almacenamiento
                $image->save(storage_path('app/' . $tempPath));
                // Eliminar imagen anterior
                if (Storage::disk('users')->exists($user->profile_photo)) {
                    Storage::disk('users')->delete($user->profile_photo);
                }
                // Guardar la imagen redimensionada en el almacenamiento local
                Storage::disk('users')->put($filePath, Storage::disk('local')->get($tempPath));
                // // Eliminar la imagen temporal
                Storage::disk('local')->delete($tempPath);
                $user->profile_photo = $filePath;
            } else {
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El archivo no es una imagen',
                ]);
                return;
            }
        }

        $user->name = $this->name ?? $user->name;
        $user->date_birthday = $this->date_birthday ?? $user->date_birthday;
        $user->entry_date = $this->entry_date ?? $user->entry_date;
        $user->email = $this->email ?? $user->email;

        if (!empty($this->area)) {
            if ($this->type_user != 3) {
                $user->area_id = $this->area;
            } else {
                $user->area_id = 5;
            }
        } elseif ($this->type_user == 3) {
            $user->area_id = 5;
        }

        $user->type_user = $this->type_user ?? $user->type_user;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        if ($this->type_user == 3) {
            $existingPivote = $user->projects()->exists();
            if ($existingPivote) {
                // Verificar si existe algún registro en la tabla pivote con client = true
                $existingClientProjects = $user->projects()->wherePivot('client', true)->exists();
                $existingDeveloper1Projects = $user->projects()->wherePivot('developer1', true)->exists();
                $existingDeveloper2Projects = $user->projects()->wherePivot('developer2', true)->exists();
                if (!$existingClientProjects || $existingDeveloper1Projects || $existingDeveloper2Projects) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Usuario asignado a proyecto',
                        'text' => 'Eliminar usuario del proyecto asignado antes de realizar cambios.',
                    ]);
                    return;
                }
            }
        }
        $user->effort_points = $this->effort_points ?? $user->effort_points;
        if ($this->file) {
            $this->refreshPage();
        }
        $user->save();

        if ($this->type_user == 3) {
            $existingPivote = $user->projects()->exists();
            if ($existingPivote) {
                // Verificar si existe algún registro en la tabla pivote con client = true
                $existingClientProjects = $user->projects()->wherePivot('client', true)->exists();
                if ($existingClientProjects) {
                    // Primero, quita las relaciones existentes para estos roles
                    $user->projects()->wherePivot('client', true)->detach();
                    // Crear un array con los datos de los proyectos seleccionados
                    $projectsData = [];
                    foreach ($this->selectedProjects as $projectId) {
                        $projectsData[$projectId] = ['leader' => false, 'product_owner' => false, 'developer1' => false, 'developer2' => false, 'client' => true];
                    }
                    // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                    $user->projects()->syncWithoutDetaching($projectsData);
                } else {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'Usuario asignado a proyecto',
                        'text' => 'Eliminar usuario del proyecto asignado antes de realizar cambios.',
                    ]);
                    return;
                }
            } else {
                // Crear un array con los datos de los proyectos seleccionados
                $projectsData = [];
                foreach ($this->selectedProjects as $projectId) {
                    $projectsData[$projectId] = ['leader' => false, 'product_owner' => false, 'developer1' => false, 'developer2' => false, 'client' => true];
                }
                // Luego, usa syncWithoutDetaching para evitar eliminar otras relaciones
                $user->projects()->syncWithoutDetaching($projectsData);
            }
        } else {
            $user->projects()->wherePivot('client', true)->detach();
        }

        $this->clearInputs();
        $this->modalCreateEdit = false;
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Usuario actualizado',
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if ($user) {
            $existingPivote = $user->projects()->exists();
            if ($existingPivote) {
                // Verificar si existe algún registro en la tabla pivote con leader y product_owner = true
                // $existingLeaderProjects = $user->projects()->wherePivot('leader', true)->exists();
                // $existingProductProjects = $user->projects()->wherePivot('product_owner', true)->exists();

                // if ($existingLeaderProjects || $existingProductProjects) {
                //     $this->dispatchBrowserEvent('swal:modal', [
                //         'type' => 'error',
                //         'title' => 'Usuario asignado a proyecto',
                //         'text' => 'Eliminar usuario del proyecto asignado antes de realizar cambios.',
                //     ]);
                //     return;
                // }
                // Verificar si existe algún registro en la tabla pivote con client = true
                $existingClientProjects = $user->projects()->wherePivot('client', true)->exists();
                if ($existingClientProjects) {
                    $user->projects()->wherePivot('client', true)->detach();
                    // Eliminar imagen anterior
                    if (Storage::disk('users')->exists($user->profile_photo)) {
                        Storage::disk('users')->delete($user->profile_photo);
                    }
                    // Actualizar el campos
                    $user->profile_photo = null;
                    // $user->effort_points = 0;
                    $user->save();
                    $user->delete();
                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Usuario eliminado',
                    ]);
                } else {
                    // Eliminar imagen anterior
                    if (Storage::disk('users')->exists($user->profile_photo)) {
                        Storage::disk('users')->delete($user->profile_photo);
                    }
                    // Actualizar el campos
                    $user->profile_photo = null;
                    // $user->effort_points = 0;
                    $user->save();
                    $user->delete();
                    // Emitir un evento de navegador
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'success',
                        'title' => 'Usuario eliminado',
                    ]);
                }
                // Verificar si existe algún registro en la tabla pivote con developer's = true
                // $existingDeveloper1Projects = $user->projects()->wherePivot('developer1', true)->exists();
                // $existingDeveloper2Projects = $user->projects()->wherePivot('developer2', true)->exists();
                // if ($existingDeveloper1Projects) {
                //     // Eliminar los registros existentes donde developer1 es true para el proyecto actual
                //     $user->projects()->wherePivot('developer1', true)->detach();
                //     // Eliminar imagen anterior
                //     if (Storage::disk('users')->exists($user->profile_photo)) {
                //         Storage::disk('users')->delete($user->profile_photo);
                //     }
                //     // Actualizar el campos
                //     $user->profile_photo = null;
                //     // $user->effort_points = 0;
                //     $user->save();
                //     $user->delete();
                //     // Emitir un evento de navegador
                //     $this->dispatchBrowserEvent('swal:modal', [
                //         'type' => 'success',
                //         'title' => 'Usuario eliminado',
                //         'text' => 'El usuario tenia tareas asignadas, buscarlas y reasignarlas a otro usuario.'
                //     ]);
                // }
                // if ($existingDeveloper2Projects) {
                //     // Eliminar los registros existentes donde developer1 es true para el proyecto actual
                //     $user->projects()->wherePivot('developer2', true)->detach();
                //     // Eliminar imagen anterior
                //     if (Storage::disk('users')->exists($user->profile_photo)) {
                //         Storage::disk('users')->delete($user->profile_photo);
                //     }
                //     // Actualizar el campos
                //     $user->profile_photo = null;
                //     // $user->effort_points = 0;
                //     $user->save();
                //     $user->delete();
                //     // Emitir un evento de navegador
                //     $this->dispatchBrowserEvent('swal:modal', [
                //         'type' => 'success',
                //         'title' => 'Usuario eliminado',
                //         'text' => 'El usuario tenia tareas asignadas, buscarlas y reasignarlas a otro usuario.'
                //     ]);
                // }
            } else {
                // Eliminar imagen anterior
                if (Storage::disk('users')->exists($user->profile_photo)) {
                    Storage::disk('users')->delete($user->profile_photo);
                }
                // Actualizar el campos
                $user->profile_photo = null;
                // $user->effort_points = 0;
                $user->save();
                $user->delete();
                // Emitir un evento de navegador
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'success',
                    'title' => 'Usuario eliminado',
                ]);
            }
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Usuario no existe',
            ]);
        }
        $this->emit('reloadPage');
    }

    public function restore($id)
    {
        $user = User::withTrashed()->find($id);

        if ($user) {
            $user->restore();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Usuario restaurado',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Usuario no existe',
            ]);
        }
    }
    // INFO MODAL
    public function showUpdate($id)
    {
        $this->showUpdate = true;
        $this->modalCreateEdit  = ($this->modalCreateEdit ) ? false : true;

        $this->userEdit = User::find($id);
        $this->allAreas = Area::all();
        $this->areaUser = $this->allAreas->find($this->userEdit->area_id);

        foreach ($this->allAreas as $key => $oneArea) {
            if ($oneArea->name === $this->areaUser->name) {
                unset($this->allAreas[$key]);
            }
        }
        // Establecer el tipo de usuario actual del usuario
        $this->type_user = $this->userEdit ? $this->userEdit->type_user : null;
        // Asegúrate de tener todos los tipos de usuario disponibles
        $this->allTypes = [1, 2, 3];

        if ($this->userEdit->type_user == 3) {
            $this->isClient = true;
        }

        $this->selectedProjects = $this->userEdit->projects()->wherePivot('client', true)->pluck('projects.id')->toArray();
        $this->name = $this->userEdit->name;
        $this->date_birthday = $this->userEdit->date_birthday;
        $this->entry_date = $this->userEdit->entry_date;
        $this->email = $this->userEdit->email;
        $this->effort_points = $this->userEdit->effort_points;
    }
    // MODAL
    public function modalCreateEdit()
    {
        $this->showUpdate = false;
        $this->modalCreateEdit  = ($this->modalCreateEdit ) ? false : true;

        $this->isClient = false;
        $this->clearInputs();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('file-reset');
    }
    // EXTRAS
    public function clearInputs()
    {
        $this->name = '';
        $this->date_birthday = '';
        $this->entry_date = '';
        $this->area = '';
        $this->type_user = '';
        $this->email = '';
        $this->password = '';
        $this->effort_points = '';
        $this->type_user = [];
        $this->selectedProjects = [];
    }

    public function updatedTypeUser($value)
    {
        $this->isClient = $value == 3;
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }

    public function refreshPage()
    {
        $this->dispatchBrowserEvent('refresh');
    }
}
