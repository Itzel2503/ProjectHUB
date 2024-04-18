<?php

namespace App\Http\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class TableCustomers extends Component
{
    use WithPagination;
    protected $paginationTheme = 'tailwind';

    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalCreateEdit = false, $modalDelete = false, $modalRestore = false;
    public $showUpdate = false, $showDelete = false, $showRestore = false;
    // table, action's user
    public $search, $customerEdit, $customerDelete, $customerRestore;
    public $perPage = '';
    public $rules = [];
    // inputs
    public $name;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        
        $customers = Customer::withTrashed()
            ->where(function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name', 'asc')
            ->paginate($this->perPage);

        return view('livewire.customers.table-customers', [
            'customers' => $customers,
        ]);
    }
    // ACTIONS
    public function create()
    {
        try {
            $this->validate([
                'name' => 'required|max:255',
            ]);
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $customer = new Customer();
        $customer->name = $this->name;
        
        $customer->save();
        $this->modalCreateEdit = false;

        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Cliente creado',
        ]);
    }

    public function update($id)
    {
        try {
            $this->validate([
                'name' => 'required|max:255',
            ]);
            // Aquí puedes continuar con tu lógica después de la validación exitosa
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'Faltan campos o campos incorrectos',
            ]);
            throw $e;
        }

        $customer = Customer::find($id);
        $customer->name = $this->name ?? $customer->name;
        
        $customer->save();
        $this->modalCreateEdit = false;

        // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Cliente actualizado',
        ]);
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);

        if ($customer) {
            $customer->delete();
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Cliente eliminado',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Cliente no existe',
            ]);
        }

        $this->modalDelete = false;
    }

    public function restore($id)
    {
        $customer = Customer::withTrashed()->find($id);

        if ($customer) {
            $customer->restore();
            // Emitir un evento de navegador
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Cliente restaurado',
        ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Cliente no existe',
            ]);
        }

        $this->modalRestore = false;
    }
    // INFO MODAL
    public function showUpdate($id)
    {
        $this->showUpdate = true;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->customerEdit = Customer::find($id);
        $this->name = $this->customerEdit->name;
    }

    public function showDelete($id)
    {
        $this->showDelete = true;

        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
        }

        $this->customerDelete = Customer::find($id);
    }

    public function showRestore($id)
    {
        $this->showRestore = true;

        if ($this->modalRestore == true) {
            $this->modalRestore = false;
        } else {
            $this->modalRestore = true;
        }

        $this->customerRestore = Customer::withTrashed()->find($id);
    }

    // MODAL
    public function modalCreateEdit()
    {
        $this->showUpdate = false;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->clearInputs();
        $this->resetErrorBag();
    }

    public function modalDelete()
    {
        if ($this->modalDelete == true) {
            $this->modalDelete = false;
        } else {
            $this->modalDelete = true;
        }
    }

    public function modalRestore()
    {
        if ($this->modalRestore == true) {
            $this->modalRestore = false;
        } else {
            $this->modalRestore = true;
        }
        $this->resetErrorBag();
    }
    // EXTRAS
    public function clearInputs()
    {
        $this->name = '';
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
