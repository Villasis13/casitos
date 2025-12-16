<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Categoria extends Model{
    use HasFactory;
    protected $table = "categorias";
    protected $primaryKey = "id_categoria";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }

    public function listar_categorias_vista($id_especialidad, $search,$pagination,$order = 'asc'){
        try {
            $query = DB::table('categorias')
                ->where('id_especialidad', '=', $id_especialidad)
                ->where(function($q) use ($search) {
                    $q->where('categoria_nombre', 'like', '%' . $search . '%')
                        ->orWhere('categoria_descripcion', 'like', '%' . $search . '%')
                        ->orWhereNull('categoria_nombre')
                        ->orWhereNull('categoria_descripcion');
                })->orderBy('id_categoria', $order);

            $result = $query->paginate($pagination);

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_categoria_x_id($id){
        try {
            $result = DB::table('categorias')
                ->where('id_categoria','=',$id)
                ->first();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_categorias_activas($id_especialidad, $search,$order = 'asc'){
        try {
            $query = DB::table('categorias')
                ->where('id_especialidad', '=', $id_especialidad)
                ->where('categoria_estado', '=', 1)
                ->where(function($q) use ($search) {
                    $q->where('categoria_nombre', 'like', '%' . $search . '%')
                        ->orWhere('categoria_descripcion', 'like', '%' . $search . '%')
                        ->orWhereNull('categoria_nombre')
                        ->orWhereNull('categoria_descripcion');
                })->orderBy('id_categoria', $order);

            $result = $query->get();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }
}
