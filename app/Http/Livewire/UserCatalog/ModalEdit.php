<?php

namespace App\Http\Livewire\UserCatalog;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class ModalEdit extends Component
{
    public $modal = false, $editar = false;
    public $search;
    public $perPage = '25';
    
    public function render()
    {
        $areas = Area::all();
        $users = User::query()
            ->where('name', 'ilike', '%' . $this->search . '%')
            ->orwhere('lastname', 'ilike', '%' . $this->search . '%')
            ->orwhere('email', 'ilike', '%' . $this->search . '%')
            ->paginate($this->perPage);
        $user = Auth::user();
        $areaUser = $areas->find($user->area_id);


        foreach ($areas as $key => $oneArea) {
            if ($oneArea->name === $areaUser->name) {
                unset($areas[$key]);
            }
        }

        return view('livewire.user-catalog.modal-edit', [
            'users' => $users,
            'user' => $user,
            'areaUser' => $areaUser,
            'areas' => $areas,
        ]);
    }

    public function closeModal()
    {
        if ($this->modal == true) {
            $this->modal = false;
            // $this->reset(['name', 'lastname', 'date_birthday', 'curp', 'rfc', 'phone', 'area', 'email', 'password']);
        } else {
            $this->modal = true;
        }
    }
}
