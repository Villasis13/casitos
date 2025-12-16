<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregunta extends Model{
    use HasFactory;
    protected $table = "preguntas";
    protected $primaryKey = "id_pregunta";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }
}
