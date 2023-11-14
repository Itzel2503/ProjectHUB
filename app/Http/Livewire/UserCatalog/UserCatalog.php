<?php

namespace App\Http\Livewire\UserCatalog;

use App\Models\Area;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UserCatalog extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];
    public $modalCreateEdit = false, $modalDelete = false, $update = false, $show = false;
    public $search, $userEdit, $areaUser, $userDelete;
    public $perPage = '25';
    public $rules = [], $allAreas = [];
    public $name, $lastname, $date_birthday, $curp, $rfc, $phone, $area, $email, $password, $password_confirmation;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

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

    public function create()
    {
        $this->rules = [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'date_birthday' => 'required|date|max:255',
            'curp' => 'max:18',
            'rfc' => 'max:13',
            'phone' => 'required|numeric',
            'area' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8',
        ];
        $this->validate();

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
        $this->modalCreateEdit = false;
        $this->emit('reloadPage');
    }

    public function show($id)
    {
        $this->show = true;

        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
        }

        $this->userDelete = User::find($id);
    }

    public function edit($id)
    {
        $this->update = true;

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
    }

    public function update($id)
    {
        $this->rules = [
            'name' => 'max:255',
            'lastname' => 'max:255',
            'date_birthday' => 'date|max:255',
            'curp' => 'max:18',
            'rfc' => 'max:13',
            'phone' => 'numeric',
            'email' => 'email',
            'password' => 'min:8',
        ];
        $this->validateOnly($id, $this->rules);

        $user = User::find($id);
        $user->name = $this->name ?? $user->name;
        $user->lastname = $this->lastname ?? $user->lastname;
        $user->phone = $this->phone ?? $user->phone;
        $user->curp = $this->curp ?? $user->curp;
        $user->rfc = $this->rfc ?? $user->rfc;
        $user->date_birthday = $this->date_birthday ?? $user->date_birthday;
        $user->email = $this->email ?? $user->email;
        $user->area_id = $this->area ?? $user->area_id;

        if ($this->area == 1) {
            $user->type_user = 1;
        } else {
            $user->type_user = 2;
        }

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();
        $this->modalCreateEdit = false;
        $this->emit('reloadPage');
    }

    public function destroy($id)
    {   
        $user = User::find($id);

        if ($user) {
            $user->delete();
            session()->flash('message', 'Usuario eliminado correctamente.');
        } else {
            session()->flash('error', 'Usuario no encontrado.');
        }

        $this->modalDelete = false;
        $this->emit('reloadPage');
    }

    public function modalCreateEdit()
    {
        $this->update = false;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }
    }

    public function modalDelete()
    {
        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
        }
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
