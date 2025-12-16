<?php

namespace App\Livewire\Portalusuarios;

use Livewire\Component;
use App\Models\Logs;
use App\Models\Carrera;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Carrerasusers extends Component{
    use WithPagination, WithoutUrlPagination;
    private $logs;
    private $carrera;
    public function __construct(){
        $this->logs = new Logs();
        $this->carrera = new Carrera();
    }
    public $search_carreras = "";

    public function render(){
        try {
            $listar_carreras = $this->carrera->listar_carreras_activas($this->search_carreras);
            return view('livewire.portalusuarios.carrerasusers', compact('listar_carreras'));
        }catch (\Exception $e) {
            $this->logs->insertarLog($e);
        }
    }
}
