<?php

namespace App\Http\Livewire\Auth;

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
    public $name, $email, $password, $confirmPassword, $photos, $idUser;
    public $files = [];
    public $userPhoto = false;

    public function render()
    {
        $user = Auth::user();

        $this->name = $user->name;
        $this->email = $user->email;
        $this->idUser = $user->id;

        if (Auth::user()->profile_photo) {
            // Verificar si el archivo existe en la carpeta
            $filePath = public_path('usuarios/' . Auth::user()->profile_photo);

            $fileExtension = pathinfo(Auth::user()->profile_photo, PATHINFO_EXTENSION);
            if (file_exists($filePath)) {
                $this->userPhoto = true;
            } else {
                $this->userPhoto = false;
            }
        } else {
            $this->userPhoto = false;
        }

        return view('livewire.auth.profile', [
            'user' => $user,
        ]);
    }

    public function update($id)
    {
        $user = User::find($id);
        $user->name = $this->name;
        $user->email = $this->email;

        if (!isset($this->password) && isset($this->confirmPassword)) {
            $this->password = '';
            $this->confirmPassword = '';

            $user->save();
            return redirect()->to('/profile')->with('errorPassword', true);
        }

        if (isset($this->password) && !isset($this->confirmPassword)) {
            $this->password = '';
            $this->confirmPassword = '';

            $user->save();
            return redirect()->to('/profile')->with('errorPassword', true);
        }

        if (isset($this->password) && isset($this->confirmPassword)) {
            if ($this->password == $this->confirmPassword) {
                $user->password = Hash::make($this->password);
            } else {
                $this->password = '';
                $this->confirmPassword = '';

                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'La contraseña no coincide'
                ]);
                return;
            }
        }

        $user->save();
        return redirect()->to('/profile')->with('userUpdate', true);
    }


    public function updatedFiles()
    {
        $user = User::find($this->idUser);

        if ($this->files) {
            $file = $this->files[0];
            $fileExtension = $file->extension();
            $extensionesImagen = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];
            if (in_array($fileExtension, $extensionesImagen)) {
                $maxSize = 5 * 1024 * 1024; // 5 MB
                // Verificar el tamaño del archivo
                if ($file->getSize() > $maxSize) {
                    $this->dispatchBrowserEvent('swal:modal', [
                        'type' => 'error',
                        'title' => 'El archivo supera el tamaño permitido, Debe ser máximo de 5Mb.'
                    ]);
                    return;
                }
                $filePath = $file->getClientOriginalName();
                // Procesar la imagen
                $image = \Intervention\Image\Facades\Image::make($file->getRealPath());
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
                $this->reset('files');
                $this->dispatchBrowserEvent('swal:modal', [
                    'type' => 'error',
                    'title' => 'El archivo no está en formato de imagen.'
                ]);
                return;
            }

            $user->save();
            return redirect()->to('/profile')->with('photoUpdate', true);;
        }
    }
}
