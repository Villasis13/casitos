<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Especialidad extends Model{
    use HasFactory;
    protected $table = "especialidades";
    protected $primaryKey = "id_especialidad";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }

    public function listar_especialidades_vista($id_carrera, $search,$pagination,$order = 'asc'){
        try {
            $query = DB::table('especialidades')
                ->where('id_carrera', '=', $id_carrera)
                ->where(function($q) use ($search) {
                    $q->where('especialidad_nombre', 'like', '%' . $search . '%')
                        ->orWhere('especialidad_descripcion', 'like', '%' . $search . '%')
                        ->orWhereNull('especialidad_nombre')
                        ->orWhereNull('especialidad_descripcion');
                })->orderBy('id_especialidad', $order);

            $result = $query->paginate($pagination);

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_especialidad_x_id($id){
        try {
            $result = DB::table('especialidades')
                ->where('id_especialidad','=',$id)
                ->first();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_especialidades_activas($id_carrera, $search,$order = 'asc'){
        try {
            $query = DB::table('especialidades')
                ->where('id_carrera', '=', $id_carrera)
                ->where('especialidad_estado', '=', 1)
                ->where(function($q) use ($search) {
                    $q->where('especialidad_nombre', 'like', '%' . $search . '%')
                        ->orWhere('especialidad_descripcion', 'like', '%' . $search . '%')
                        ->orWhereNull('especialidad_nombre')
                        ->orWhereNull('especialidad_descripcion');
                })->orderBy('id_especialidad', $order);

            $result = $query->get();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

}
