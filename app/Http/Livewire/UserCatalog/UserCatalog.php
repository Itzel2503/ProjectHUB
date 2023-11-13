<?php

namespace App\Http\Livewire\UserCatalog;

use App\Models\Area;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserCatalog extends Component
{
    public $modal = false, $editar = false;
    public $search;
    public $perPage = '20';

    public function render()
    {
        $areas = Area::all();
        $users = User::query()
            ->select('users.*', 'areas.name as area_name') // Selecciona todos los campos de users y el campo name de areas
            ->leftJoin('areas', 'users.area_id', '=', 'areas.id') // Realiza una left join con la tabla "areas"
            ->where('users.name', 'ilike', '%' . $this->search . '%')
            ->orWhere('users.lastname', 'ilike', '%' . $this->search . '%')
            ->orWhere('users.email', 'ilike', '%' . $this->search . '%')
            ->orWhere('areas.name', 'ilike', '%' . $this->search . '%') // Agrega esta condición para el nombre del área
            ->paginate($this->perPage);

        return view('livewire.user-catalog.user-catalog', [
            'users' => $users,
            'areas' => $areas,
        ]);
    }

    public function openModal()
    {
        $this->modal = true;
    }
}
