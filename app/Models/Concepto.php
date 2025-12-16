<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concepto extends Model{
    use HasFactory;
    protected $table = "conceptos";
    protected $primaryKey = "id_concepto";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }
}
