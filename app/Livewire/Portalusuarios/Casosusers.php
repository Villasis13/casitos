<?php

namespace App\Livewire\Portalusuarios;

use App\Models\Caso;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Casosusers extends Component{
    use WithPagination, WithoutUrlPagination;
    private $logs;
    private $caso;
    public function __construct(){
        $this->logs = new Logs();
        $this->caso = new Caso();
    }
    public $search_casos;
    public $id_caso = "";
    public $id_categoria = "";
    public function mount($id_categoria){
        $this->id_categoria = $id_categoria;
    }

    public function render(){
        try {
            $listar_casos = $this->caso->listar_casos_activos($this->id_categoria, $this->search_casos);

            // Calcular progreso: casos resueltos por el usuario
            $total_casos = count($listar_casos);


            return view('livewire.portalusuarios.casosusers', compact('listar_casos', 'total_casos'));
        } catch (\Exception $e) {
            $this->logs->insertarLog($e);
        }
    }

}
