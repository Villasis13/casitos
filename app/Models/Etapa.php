<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Etapa extends Model{
    use HasFactory;
    protected $table = "etapas";
    protected $primaryKey = "id_etapa";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }

    public function listar_etapa_vista($id_caso, $search,$pagination,$order = 'asc'){
        try {

            $query = DB::table('etapas')
                ->where('id_caso', '=', $id_caso)
                ->where(function($q) use ($search) {
                    $q->where('etapa_titulo', 'like', '%' . $search . '%')
                        ->orWhereNull('etapa_titulo');
                })->orderBy('id_etapa', $order);

            $result = $query->paginate($pagination);

        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            $result = [];
        }
        return $result;
    }

    public function listar_opc_etapas($id_caso, $ordenActual){
        try {
            $result = DB::table('etapas')
                ->where('etapa_estado', '=', 1)
                ->where('id_caso', '=', $id_caso)
                ->where('etapa_orden', '>', $ordenActual)
                ->orderBy('etapa_orden', 'asc')
                ->get();
        } catch (\Exception $e) {
            $this->logs->insertarLog($e);
            $result = [];
        }

        return $result;
    }
}
