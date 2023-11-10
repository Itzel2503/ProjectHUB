<?php

namespace App\Http\Livewire;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserCatalog extends Component
{
    public $modal = false, $editar = false;
    public $search;
    public $perPage = '25';

    public function render()
    {
        $areas = Area::all();
        $users = User::query()
            ->where('name', 'ilike', '%' . $this->search . '%')
            ->where('lastname', 'ilike', '%' . $this->search . '%')
            ->where('email', 'ilike', '%' . $this->search . '%')
            ->paginate($this->perPage);
        $user = Auth::user();
        $areaUser = $areas->find($user->area_id);


        foreach ($areas as $key => $oneArea) {
            if ($oneArea->name === $areaUser->name) {
                unset($areas[$key]);
            }
        }

        return view('livewire.user-catalog', [
            'users' => $users,
            'user' => $user,
            'areaUser' => $areaUser,
            'areas' => $areas,
        ]);
    }

    public function toggleModal()
    {
        if ($this->modal == true) {
            $this->modal = false;
            $this->reset(['name', 'paternal_name', 'maternal_name', 'id_user_type', 'id_area', 'password', 'email', 'birth_date', 'editar']);
        } else {
            $this->modal = true;
        }
    }
}
