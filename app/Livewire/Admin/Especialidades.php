<?php

namespace App\Livewire\Admin;

use App\Models\Especialidad;
use App\Models\Logs;
use App\Models\General;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Especialidades extends Component{
    use WithPagination, WithoutUrlPagination;
    use WithFileUploads;
    private $logs;
    private $especialidad;
    private $general;
    public function __construct(){
        $this->logs = new Logs();
        $this->especialidad = new Especialidad();
        $this->general = new General();
    }
    public $search_especialidad = "";
    public $pagination_especialidad = 9;
    public $id_especialidad = "";
    public $id_carrera = "";
    public $especialidad_nombre = "";
    public $especialidad_descripcion = "";
    public $especialidad_imagen = "";
    public $ruta_img_default = "";
    public $especialidad_estado = "";
    public $messageDelete = "";
    public function mount($id_carrera){
        $this->id_carrera = $id_carrera;
    }
    public function render(){
        $listar_especialidades = $this->especialidad->listar_especialidades_vista($this->id_carrera, $this->search_especialidad,$this->pagination_especialidad);
        return view('livewire.admin.especialidades', compact('listar_especialidades'));
    }

    public function clear_form(){
        $this->id_especialidad = "";
        $this->especialidad_nombre = "";
        $this->especialidad_descripcion = "";
        $this->especialidad_imagen = "";
        $this->especialidad_estado = "";
        $this->ruta_img_default = "uploads/admin/especialidad/birrete.png";
        $this->dispatch('PersonalImg', ['image' => asset($this->ruta_img_default)]);
    }

    public function edit_data($id){
        $editar = Especialidad::find(base64_decode($id));
        if ($editar){
            $this->especialidad_nombre = $editar->especialidad_nombre;
            $this->especialidad_descripcion = $editar->especialidad_descripcion;
            $this->ruta_img_default = $editar->especialidad_imagen;
            $this->dispatch('PersonalImg', ['image' => asset($this->ruta_img_default)]);
            $this->id_carrera = $editar->id_carrera;
            $this->id_especialidad = $editar->id_especialidad;
        }
    }

    public function btn_disable($id_especialidad,$esta){
        $id = base64_decode($id_especialidad);
        $status = $esta;
        if ($id){
            $this->id_especialidad = $id;
            $this->especialidad_estado = $status;
            if ($status == 0){
                $this->messageDelete = "¿Está seguro que desea deshabilitar esta especialidad?";
            }else{
                $this->messageDelete = "¿Está seguro que desea habilitar esta especialidad?";
            }
        }
    }

    public function disable_especialidad(){
        try {
            if (!Gate::allows('disable_especialidad')) {
                session()->flash('error_delete', 'No tiene permisos para cambiar los estados.');
                return;
            }

            $this->validate([
                'id_especialidad' => 'required|integer',
                'especialidad_estado' => 'required|integer',
            ], [
                'id_especialidad.required' => 'El identificador es obligatorio.',
                'id_especialidad.integer' => 'El identificador debe ser un número entero.',

                'especialidad_estado.required' => 'El estado es obligatorio.',
                'especialidad_estado.integer' => 'El estado debe ser un número entero.',
            ]);

            DB::beginTransaction();
            $especialidad_delete = Especialidad::find($this->id_especialidad);
            $especialidad_delete->especialidad_estado = $this->especialidad_estado;
            if ($especialidad_delete->save()) {
                DB::commit();
                $this->dispatch('hide_modal_delete_especialidad');
                if ($this->especialidad_estado == 0){
                    session()->flash('success', 'Registro deshabilitado correctamente.');
                }else{
                    session()->flash('success', 'Registro habilitado correctamente.');
                }
            } else {
                DB::rollBack();
                session()->flash('error_delete', 'No se pudo cambiar el estado.');
                return;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error', 'Ocurrió un error. Por favor, inténtelo nuevamente.');
        }
    }

    public function save_especialidad(){
        try {
            $this->validate([
                'id_carrera' => 'required|integer',
                'especialidad_nombre' => 'required|string',
                'especialidad_descripcion' => 'required|string|max:1500',
                'especialidad_imagen' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'especialidad_estado' => 'nullable|integer',
                'id_especialidad' => 'nullable|integer',
            ], [
                'id_carrera.required' => 'La carrera es obligatorio.',
                'id_carrera.integer' => 'La carrera debe ser un número entero.',

                'especialidad_nombre.required' => 'El nombre de la especialidad es obligatorio.',
                'especialidad_nombre.string' => 'El nombre de la especialidad debe ser una cadena de texto.',

                'especialidad_descripcion.required' => 'La descripción de la especialidad es obligatorio.',
                'especialidad_descripcion.string' => 'La descripción de la especialidad debe ser una cadena de texto.',
                'especialidad_descripcion.max' => 'La descripción no debe superar los 1500 caracteres.',

                'especialidad_imagen.file' => 'Debe cargar un archivo válido.',
                'especialidad_imagen.mimes' => 'El archivo debe ser JPG, JPEG o PNG.',
                'especialidad_imagen.max' => 'El archivo no puede exceder los 2MB.',

                'especialidad_estado.integer' => 'El estado debe ser un número entero.',

                'id_especialidad.integer' => 'El identificador debe ser un número entero.',
            ]);

            if (!$this->id_especialidad) { // INSERT
                if (!Gate::allows('crear_especialidad')) {
                    session()->flash('error_modal', 'No tiene permisos para crear.');
                    return;
                }

                $microtime = microtime(true);

                DB::beginTransaction();
                $save = new Especialidad();
                $save->id_users = Auth::id();
                $save->id_carrera = $this->id_carrera;
                $save->especialidad_nombre = $this->especialidad_nombre;
                $save->especialidad_descripcion = $this->especialidad_descripcion;
                if ($this->carrera_imagen) {
                    $save->especialidad_imagen = $this->general->save_files($this->especialidad_imagen, 'admin/especialidad', true);
                } else {
                    $save->especialidad_imagen = 'uploads/admin/especialidad/birrete.png';
                }
                $save->especialidad_microtime = $microtime;
                $save->especialidad_estado = 1;
                if ($save->save()) {
                    DB::commit();
                    $this->dispatch('hide_modal_especialidad');
                    session()->flash('success', 'Registro guardado correctamente.');
                } else {
                    DB::rollBack();
                    session()->flash('error_modal', 'Ocurrió un error al guardar el registro.');
                    return;
                }
            } else { // UPDATE
                if (!Gate::allows('actualizar_especialidad')) {
                    session()->flash('error_modal', 'No tiene permisos para actualizar.');
                    return;
                }

                DB::beginTransaction();
                $update_especialidad = Especialidad::findOrFail($this->id_especialidad);
                $update_especialidad->especialidad_nombre = $this->especialidad_nombre;
                $update_especialidad->especialidad_descripcion = $this->especialidad_descripcion;
                if ($this->especialidad_imagen) {
                    try {
                        unlink($update_especialidad->especialidad_imagen);
                    } catch (\Exception $e) {}

                    $update_especialidad->especialidad_imagen = $this->general->save_files($this->especialidad_imagen, 'admin/especialidad', true);
                }
                if (!$update_especialidad->save()) {
                    session()->flash('error_modal', 'No se pudo actualizar el registro.');
                    return;
                }

                DB::commit();
                $this->dispatch('hide_modal_especialidad');
                session()->flash('success', 'Registro actualizado correctamente.');
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error_modal', 'Ocurrió un error. Por favor, inténtelo nuevamente.');
        }
    }

}
