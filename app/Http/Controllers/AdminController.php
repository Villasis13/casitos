<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\Carrera;
use App\Models\Especialidad;
use App\Models\Categoria;

class AdminController extends Controller{
    private $logs;
    private $carrera;
    private $especialidad;
    private $categoria;
    public function __construct(){
        $this->logs = new Logs();
        $this->carrera = new Carrera();
        $this->especialidad = new Especialidad();
        $this->categoria = new Categoria();
    }

    public function carreras(){
        try {
            return view('admin.carreras');
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

    public function especialidades(){
        try {
            $id_carrera = base64_decode($_GET['id_carrera']);
            if ($id_carrera){
                $informacion_carrera = $this->carrera->listar_carrera_x_id($id_carrera);

                return view('admin.especialidades',compact('informacion_carrera'));
            }
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

    public function categorias(){
        try {
            $id_especialidad = base64_decode($_GET['id_especialidad']);
            if ($id_especialidad){
                $informacion_especialidad = $this->especialidad->listar_especialidad_x_id($id_especialidad);

                return view('admin.categorias',compact('informacion_especialidad'));
            }
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

    public function casos(){
        try {
            $id_categoria = base64_decode($_GET['id_categoria']);
            if ($id_categoria){
                $informacion_categoria = $this->categoria->listar_categoria_x_id($id_categoria);

                return view('admin.casos',compact('informacion_categoria'));
            }
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

}
