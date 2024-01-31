<?php

namespace App\Http\Livewire\Customers;

use App\Models\Customer;
use Livewire\Component;

class TableCustomers extends Component
{
    public $listeners = ['reloadPage' => 'reloadPage'];
    // modal
    public $modalCreateEdit = false;
    public $showUpdate = false;
    // table, action's user
    public $search, $customerEdit;
    public $perPage = '10';
    public $rules = [];
    // inputs
    public $name;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $customers = Customer::where(function ($query) {
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
        $this->rules = [
            'name' => 'required|max:255',
        ];
        $this->validate();

        $customer = new Customer();
        $customer->name = $this->name;
        
        $customer->save();
        $this->modalCreateEdit = false;
        $this->emit('reloadPage');
    }

    public function update($id)
    {
        $this->rules = [
            'name' => 'max:255',
        ];
        $this->validateOnly($id, $this->rules);

        $customer = Customer::find($id);
        $customer->name = $this->name ?? $customer->name;
        
        $customer->save();
        $this->modalCreateEdit = false;
        $this->emit('reloadPage');
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
