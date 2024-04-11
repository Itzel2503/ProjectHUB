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
            $originalFileName = $this->file->getClientOriginalName();
            $filePath = $originalFileName;

            if (Storage::disk('users')->exists($user->profile_photo)) {
                Storage::disk('users')->delete($user->profile_photo);
            }

            $this->file->storeAs('/', $filePath, 'users');
            $user->profile_photo = $originalFileName;

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
