<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use App\Models\Categoria;
use App\Models\Especialidad;
use Illuminate\Http\Request;
use App\Models\Logs;
use App\Models\Caso;

class PortalusuariosController extends Controller{
    private $logs;
    private $carrera;
    private $especialidad;
    private $categoria;
    private $caso;
    public function __construct(){
        $this->logs = new Logs();
        $this->carrera = new Carrera();
        $this->especialidad = new Especialidad();
        $this->categoria = new Categoria();
        $this->caso = new Caso();
    }

    public function usuarios_vista(){
        try {
            return view('portalusuario.usuarios_vista');
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

    public function especialidades_users(){
        try {
            $id_carrera = base64_decode($_GET['id_carrera']);
            if ($id_carrera){
                $informacion_carrera = $this->carrera->listar_carrera_x_id($id_carrera);

                return view('portalusuario.especialidades_users',compact('informacion_carrera'));
            }
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

    public function categorias_users(){
        try {
            $id_especialidad = base64_decode($_GET['id_especialidad']);
            if ($id_especialidad){
                $informacion_especialidad = $this->especialidad->listar_especialidad_x_id($id_especialidad);

                return view('portalusuario.categorias_users',compact('informacion_especialidad'));
            }
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

    public function casos_users(){
        try {
            $id_categoria = base64_decode($_GET['id_categoria']);
            if ($id_categoria){
                $informacion_categoria = $this->categoria->listar_categoria_x_id($id_categoria);

                return view('portalusuario.casos_users',compact('informacion_categoria'));
            }
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

    public function iniciar_caso(){
        try {
            $id_caso = base64_decode($_GET['id_caso']);
            if ($id_caso){
                $informacion_caso = $this->caso->listar_caso_x_id($id_caso);

                return view('portalusuario.iniciar_caso',compact('informacion_caso'));
            }
        }catch (\Exception $e){
            $this->logs->insertarLog($e);
            return redirect()->route('intranet')->with('error', 'Ocurrió un error al intentar mostrar el contenido.');
        }
    }

}
