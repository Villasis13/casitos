<?php

namespace App\Livewire\Portalusuarios;

use App\Models\Especialidad;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Especialidadesusers extends Component{
    use WithPagination, WithoutUrlPagination;
    private $logs;
    private $especialidad;
    public function __construct(){
        $this->logs = new Logs();
        $this->especialidad = new Especialidad();
    }
    public $search_especialidad = "";
    public $id_especialidad = "";
    public $id_carrera = "";
    public $carrera_nombre = "";
    public function mount($id_carrera){
        $this->id_carrera = $id_carrera;
        $this->carrera_nombre = DB::table('carreras')
            ->where('id_carrera', '=', $this->id_carrera)
            ->value('carrera_nombre');
    }

    public function render(){
        try {
            $listar_especialidades = $this->especialidad->listar_especialidades_activas($this->id_carrera, $this->search_especialidad);
            return view('livewire.portalusuarios.especialidadesusers', compact('listar_especialidades'));
        }catch (\Exception $e) {
            $this->logs->insertarLog($e);
        }
    }
}
