<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternativadestino extends Model{
    use HasFactory;
    protected $table = "alternativas_destinos";
    protected $primaryKey = "id_destino";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }
}
