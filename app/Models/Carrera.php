<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Carrera extends Model{
    use HasFactory;
    protected $table = "carreras";
    protected $primaryKey = "id_carrera";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }

    public function listar_carreras_vista($search,$pagination,$order = 'asc'){
        try {
            $query = DB::table('carreras')
                ->where(function($q) use ($search) {
                    $q->where('carrera_nombre', 'like', '%' . $search . '%')
                        ->orWhere('carrera_descripcion', 'like', '%' . $search . '%')
                        ->orWhereNull('carrera_nombre')
                        ->orWhereNull('carrera_descripcion');
                })->orderBy('id_carrera', $order);

            $result = $query->paginate($pagination);

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_carrera_x_id($id){
        try {
            $result = DB::table('carreras')
                ->where('id_carrera','=',$id)
                ->first();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_carreras_activas($search,$order = 'asc'){
        try {
            $query = DB::table('carreras')
                ->where('carrera_estado', '=', 1)
                ->where(function($q) use ($search) {
                    $q->where('carrera_nombre', 'like', '%' . $search . '%')
                        ->orWhere('carrera_descripcion', 'like', '%' . $search . '%')
                        ->orWhereNull('carrera_nombre')
                        ->orWhereNull('carrera_descripcion');
                })->orderBy('id_carrera', $order);

            $result = $query->get();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

}
