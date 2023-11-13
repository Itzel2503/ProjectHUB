<?php

namespace App\Http\Livewire\UserCatalog;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ModalNew extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];
    public $name, $lastname, $date_birthday, $curp, $rfc, $phone, $area, $email, $password;
    public $modal = true;
    
    public function render()
    {
        $areas = Area::all();

        return view('livewire.user-catalog.modal-new', ['areas' => $areas,]);
    }

    public function closeModal()
    {
        $this->modal = true;
    }

    public function create() {
        $user = new User();
        $user->name = $this->name;
        $user->lastname = $this->lastname;
        $user->phone = $this->phone ?? null;
        $user->curp = $this->curp ?? null;
        $user->rfc = $this->rfc ?? $user->rfc;
        $user->date_birthday = $this->date_birthday;
        $user->email = $this->email;
        $user->area_id = $this->area;

        if ($this->area == 1) {        
            $user->type_user = 1;
        } else {
            $user->type_user = 2;
        }

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();
        $this->modal = false;
        $this->emit('reloadPage');
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }

}
