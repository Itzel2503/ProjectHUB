<?php

namespace App\Http\Livewire\User;

use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Profile extends Component
{   
    public $editing = false;
    public $name, $lastname, $phone, $curp, $rfc, $date_birthday, $area, $email, $password;
     
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
        
        return view('livewire.user.profile', [
            'user' => $user,
            'areaUser' => $areaUser,
            'areas' => $areas,
        ]);
    }

    public function edit()
    {
        if ($this->editing == true) {
            $this->editing = false;
        } else {
            $this->editing = true;
        }
    }

    public function update($id)
    {
        dd($id,$this);
    }
}
