<?php

namespace App\Livewire\Portalusuarios;

use App\Models\Categoria;
use App\Models\Caso;
use App\Models\Logs;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Categoriasusers extends Component
{
    use WithPagination, WithoutUrlPagination;

    private $logs;
    private $categoria;
    private $caso;

    public function __construct(){
        $this->logs = new Logs();
        $this->categoria = new Categoria();
        $this->caso = new Caso();
    }

    public $search_categoria = "";
    public $search_casos = "";
    public $id_categoria = "";
    public $id_especialidad = "";
    public $id_carrera = "";
    public $especialidad_nombre = "";

    public function mount($id_especialidad){
        $this->id_especialidad = $id_especialidad;
        $this->id_carrera = DB::table('especialidades')
            ->where('id_especialidad', '=', $this->id_especialidad)
            ->value('id_carrera');

        $this->especialidad_nombre = DB::table('especialidades')
            ->where('id_especialidad', '=', $this->id_especialidad)
            ->value('especialidad_nombre');

        // Seleccionar por defecto la primera categoría (si aún no hay seleccionada)
        if (empty($this->id_categoria)) {
            $listar_categorias = $this->categoria->listar_categorias_activas($this->id_especialidad, $this->search_categoria);

            if (!empty($listar_categorias) && isset($listar_categorias[0]->id_categoria)) {
                $this->id_categoria = $listar_categorias[0]->id_categoria;
            }
        }
    }

    public function render(){
        try {
            // Listar categorías activas
            $listar_categorias = $this->categoria->listar_categorias_activas($this->id_especialidad, $this->search_categoria);

            // Si hay una categoría seleccionada, obtener sus casos
            $listar_casos = [];
            $total_casos = 0;

            if ($this->id_categoria) {
                $listar_casos = $this->caso->listar_casos_activos($this->id_categoria, $this->search_casos);
                $total_casos = count($listar_casos);
            }

            return view('livewire.portalusuarios.categoriasusers', compact('listar_categorias', 'listar_casos', 'total_casos'));

        } catch (\Exception $e) {
            $this->logs->insertarLog($e);
        }
    }
}
