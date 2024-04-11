<?php

namespace App\Http\Livewire\Auth;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class Profile extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];
    public $editing = false;
    public $name, $lastname, $phone, $date_birthday, $area, $email, $password;
    
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

        $user = User::find($id);

        $this->name = $user->name;
        $this->lastname = $user->lastname;
        $this->phone = $user->phone;
        $this->date_birthday = $user->date_birthday;
        $this->email = $user->email;
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
            
        }

        $user->name = $this->name ?? $user->name;
        $user->lastname = $this->lastname ?? $user->lastname;
        $user->phone = $this->phone ?? $user->phone;
        $user->date_birthday = $this->date_birthday ?? $user->date_birthday;
        $user->email = $this->email ?? $user->email;
        $user->area_id = $this->area ?? $user->area_id;

        if ($this->area == 1) {        
            $user->type_user = 1;
        } else if ($this->area == null) {
            $user->type_user = $user->type_user;
        } else {
            $user->type_user = 2;
        }
        
        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();
        $this->editing = false;
        $this->emit('reloadPage');
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
