<?php

namespace App\Http\Livewire\Users;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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
    // table, action's user
    public $search, $userEdit, $areaUser;
    public $perPage = '';
    public $rules = [], $allAreas = [], $allTypes = [1, 2];
    // inputs
    public $file, $name, $lastname, $date_birthday, $phone, $area, $type_user, $email, $password;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $areas = Area::all();

        $users = User::onlyTrashed()
            ->select('users.*', 'areas.name as area_name')
            ->leftJoin('areas', 'users.area_id', '=', 'areas.id')
            ->where(function ($query) {
                if ($this->search == 'Administrador' || $this->search == 'administrador') {
                    $query->orWhere('users.type_user', 1);
                } elseif ($this->search == 'Usuario' || $this->search == 'usuario') {
                    $query->orWhere('users.type_user', 2);
                }
            })
            ->orWhere('users.name', 'like', '%' . $this->search . '%')
            ->orWhere('users.lastname', 'like', '%' . $this->search . '%')
            ->orWhere('users.email', 'like', '%' . $this->search . '%')
            ->orWhere('areas.name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate($this->perPage);

        return view('livewire.users.table-users', [
            'users' => $users,
            'areas' => $areas,
        ]);
    }
    // ACTIONS
    public function create()
    {
        try {
            $this->validate([
                'name' => 'required|max:255',
                'lastname' => 'required|max:255',
                'date_birthday' => 'required|date|max:255',
                'phone' => 'required|numeric',
                'area' => 'required',
                'type_user' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|min:8',
            ]);
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura excepciones específicas de la base de datos si es necesario
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Error al crear el usuario',
                'text' => 'El correo electrónico ya está registrado.',
            ]);
        }

        $user = new User();

        if ($this->file) {
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (in_array($this->file->extension(), $extensionesImagen)) {
                $maxSize = 5 * 1024 * 1024; // 5 MB
                // Verificar el tamaño del archivo
                if ($this->file->getSize() > $maxSize) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.'
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
                    'title' => 'El archivo no está en formato de imagen.'
                ]);
                return;
            }
        }

        $user->name = $this->name;
        $user->lastname = $this->lastname;
        $user->phone = $this->phone ?? null;
        $user->date_birthday = $this->date_birthday;
        $user->email = $this->email;
        $user->area_id = $this->area;
        $user->type_user = $this->type_user;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();
        $this->clearInputs();
        $this->modalCreateEdit = false;

        if ($this->file) {
            $this->refreshPage();
        }
        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Usuario creado',
        ]);
    }

    public function update($id)
    {
        try {
            $this->validate([
                'name' => 'required|max:255',
                'lastname' => 'required|max:255',
                'date_birthday' => 'required|date|max:255',
                'phone' => 'required|numeric',
                'email' => 'required|email|unique:users,email,' . $id,
            ]);
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
                        'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.'
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
                    'title' => 'El archivo no es una imagen'
                ]);
                return;
            }
        }

        $user->name = $this->name ?? $user->name;
        $user->lastname = $this->lastname ?? $user->lastname;
        $user->phone = $this->phone ?? $user->phone;
        $user->date_birthday = $this->date_birthday ?? $user->date_birthday;
        $user->email = $this->email ?? $user->email;

        if (!empty($this->area)) {
            $user->area_id = $this->area;
        }

        $user->type_user = $this->type_user ?? $user->type_user;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        if ($this->file) {
            $this->refreshPage();
        }

        $user->save();
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
            // Eliminar imagen anterior
            if (Storage::disk('users')->exists($user->profile_photo)) {
                Storage::disk('users')->delete($user->profile_photo);
            }
            $user->delete();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Usuario eliminado',
            ]);
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

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->userEdit = User::find($id);

        $this->allAreas = Area::all();

        $this->areaUser = $this->allAreas->find($this->userEdit->area_id);

        foreach ($this->allAreas as $key => $oneArea) {
            if ($oneArea->name === $this->areaUser->name) {
                unset($this->allAreas[$key]);
            }
        }

        $this->type_user = $this->userEdit ? $this->userEdit->type_user : null;
        $this->name = $this->userEdit->name;
        $this->lastname = $this->userEdit->lastname;
        $this->date_birthday = $this->userEdit->date_birthday;
        $this->phone = $this->userEdit->phone;
        $this->email = $this->userEdit->email;
    }
    // MODAL
    public function modalCreateEdit()
    {
        $this->showUpdate = false;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }
        $this->clearInputs();
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('file-reset');
    }
    // EXTRAS
    public function clearInputs()
    {
        $this->name = '';
        $this->lastname = '';
        $this->date_birthday = '';
        $this->phone = '';
        $this->area = '';
        $this->type_user = '';
        $this->email = '';
        $this->password = '';
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
