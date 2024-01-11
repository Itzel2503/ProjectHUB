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
    public $perPage = '25';
    public $rules = [];
    // inputs
    public $name;

    public function render()
    {
        $this->dispatchBrowserEvent('reloadModalAfterDelay');

        $customers = Customer::where(function ($query) {
            $query->where('name', 'like', '%' . $this->search . '%');
        })
            ->paginate($this->perPage);

        return view('livewire.customers.table-customers', [
            'customers' => $customers,
        ]);
    }

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

    public function showUpdate($id)
    {
        $this->showUpdate = true;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }

        $this->customerEdit = Customer::find($id);
    }

    public function modalCreateEdit()
    {
        $this->showUpdate = false;

        if ($this->modalCreateEdit == true) {
            $this->modalCreateEdit = false;
        } else {
            $this->modalCreateEdit = true;
        }
    }

    public function reloadPage()
    {
        $this->reset();
        $this->render();
    }
}
