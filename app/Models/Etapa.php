<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etapa extends Model{
    use HasFactory;
    protected $table = "etapas";
    protected $primaryKey = "id_etapa";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }
}
