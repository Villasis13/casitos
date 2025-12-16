<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Caso extends Model{
    use HasFactory;
    protected $table = "casos";
    protected $primaryKey = "id_caso";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }

    public function listar_casos_vista($id_categoria, $search,$pagination,$order = 'asc'){
        try {

            $query = DB::table('casos')
                ->where('id_categoria', '=', $id_categoria)
                ->where(function($q) use ($search) {
                    $q->where('caso_titulo', 'like', '%' . $search . '%')
                        ->orWhereNull('caso_titulo');
                })->orderBy('id_caso', $order);

            $result = $query->paginate($pagination);

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_casos_activos($id_categoria, $search,$order = 'asc'){
        try {
            $query = DB::table('casos')
                ->where('id_categoria', '=', $id_categoria)
                ->where('caso_estado', '=', 1)
                ->where(function($q) use ($search) {
                    $q->where('caso_titulo', 'like', '%' . $search . '%')
                        ->orWhere('caso_objetivo', 'like', '%' . $search . '%')
                        ->orWhereNull('caso_titulo')
                        ->orWhereNull('caso_objetivo');
                })->orderBy('id_caso', $order);

            $result = $query->get();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_caso_x_id($id){
        try {
            $result = DB::table('casos')
                ->where('id_caso','=',$id)
                ->first();

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }
}
