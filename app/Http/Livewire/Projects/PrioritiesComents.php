<?php

namespace App\Http\Livewire\Projects;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class PrioritiesComents extends Component
{
    // MODAL
    public $edit = false;
    // PANEL
    public $activeTab = 'Avisos';
    // INPUTS
    public $avisos, $seguimiento, $pruebas, $resolucion, $entregado;

    public function render()
    {
        $sections = DB::table('priorities')
            ->orderBy('id', 'asc')
            ->get();

        return view('livewire.projects.priorities-coments', [
            'sections' => $sections,
        ]);
    }
    // ACTIONS
    public function saveComments()
    {
        if ($this->avisos) {
            DB::table('priorities')->where('id', 1)->update(['content' => $this->avisos]);
        }
        if ($this->seguimiento) {
            DB::table('priorities')->where('id', 2)->update(['content' => $this->seguimiento]);
        }
        if ($this->pruebas) {
            DB::table('priorities')->where('id', 3)->update(['content' => $this->pruebas]);
        }
        if ($this->resolucion) {
            DB::table('priorities')->where('id', 4)->update(['content' => $this->resolucion]);
        }
        if ($this->entregado) {
            DB::table('priorities')->where('id', 5)->update(['content' => $this->entregado]);
        }
    
        $this->edit = false;
        $this->dispatchBrowserEvent('swal:modal', [
            'type' => 'success',
            'title' => 'Comentario/s guardado/s',
        ]);
    }

    public function available($id)
    {
        $section = DB::table('priorities')->where('id', $id)->first();

        if ($section) {
            if ($section->available == true) {
                DB::table('priorities')->where('id', $id)->update(['available' => false]);
            } else {
                DB::table('priorities')->where('id', $id)->update(['available' => true]);
            }
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'success',
                'title' => 'Actualizado con éxito.',
            ]);
        } else {
            // Emitir un evento de navegador
            $this->dispatchBrowserEvent('swal:modal', [
                'type' => 'error',
                'title' => 'No se encontro la sección',
            ]);
        }
    }
    // MODAL
    public function showEdit()
    {
        if ($this->edit == true) {
            $this->edit = false;
        } else {
            $this->edit = true;
        }
    }
    // EXTRAS
    public function setActiveTab($tab)
    {
        $this->activeTab = $tab;

        $section = DB::table('priorities')->where('section', $tab)->first();
        if ($section->id == 1) {
            $this->avisos = $section->content;
        } elseif ($section->id == 2) {
            $this->seguimiento = $section->content;
        } elseif ($section->id == 3) {
            $this->pruebas = $section->content;
        } elseif ($section->id == 4) {
            $this->resolucion = $section->content;
        } elseif ($section->id == 5) {
            $this->entregado = $section->content;
        }
    }
}
