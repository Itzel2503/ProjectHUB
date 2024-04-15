<?php

namespace App\Http\Livewire\Auth;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public $listeners = ['reloadPage' => 'reloadPage'];
    public $editing = false;
    public $name, $lastname, $phone, $date_birthday, $area, $email, $password, $file;

    public function render()
    {
        $areas = Area::all();
        $user = Auth::user();
        $areaUser = $areas->find($user->area_id);

        foreach ($areas as $key => $oneArea) {
            if ($oneArea->name === $areaUser->name) {
                unset($areas[$key]);
            }
        }

        return view('livewire.auth.profile', [
            'user' => $user,
            'areaUser' => $areaUser,
            'areas' => $areas,
        ]);
    }

    public function edit($id)
    {
        if ($this->editing == true) {
            $this->editing = false;
        } else {
            $this->editing = true;
        }
    }

    public function update($id)
    {
        $user = User::find($id);

        if ($this->file) {
            $fileExtension = $this->file->extension();
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'svg', 'webp'];
            if (in_array($fileExtension, $extensionesImagen)) {
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
            if ($this->password) {
                $user->password = Hash::make($this->password);
            }
            $user->save();
            return redirect()->to('/profile');
        }

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }
        $user->save();
        $this->editing = false;
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
