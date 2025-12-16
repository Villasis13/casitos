<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternativa extends Model{
    use HasFactory;
    protected $table = "alternativas";
    protected $primaryKey = "id_alternativa";
    private $logs;

    public function __construct(){
        parent::__construct();
        $this->logs = new Logs();
    }
}
