<?php

namespace App\Livewire\Admin;

use App\Livewire\Intranet\sidebar;
use App\Models\Logs;
use App\Models\Carrera;
use App\Models\General;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Carreras extends Component{
    use WithPagination, WithoutUrlPagination;
    use WithFileUploads;
    private $logs;
    private $carrera;
    private $general;
    public function __construct(){
        $this->logs = new Logs();
        $this->carrera = new Carrera();
        $this->general = new General();
    }
    public $search_carrera;
    public $ppagination_carrera = 9;
    public $id_carrera = "";
    public $carrera_nombre = "";
    public $carrera_descripcion = "";
    public $carrera_imagen = "";
    public $ruta_img_default = "";
    public $carrera_estado = "";
    public $messageDelete = "";

    public function render(){
        $listar_carreras = $this->carrera->listar_carreras_vista($this->search_carrera,$this->ppagination_carrera);
        return view('livewire.admin.carreras', compact('listar_carreras'));
    }

    public function clear_form(){
        $this->id_carrera = "";
        $this->carrera_nombre = "";
        $this->carrera_descripcion = "";
        $this->carrera_imagen = "";
        $this->carrera_estado = "";
        $this->ruta_img_default = "uploads/admin/carrera/birrete.png";
        $this->dispatch('PersonalImg', ['image' => asset($this->ruta_img_default)]);
    }

    public function edit_data($id){
        $editar = Carrera::find(base64_decode($id));
        if ($editar){
            $this->carrera_nombre = $editar->carrera_nombre;
            $this->carrera_descripcion = $editar->carrera_descripcion;
            $this->ruta_img_default = $editar->carrera_imagen;
            $this->dispatch('PersonalImg', ['image' => asset($this->ruta_img_default)]);
            $this->id_carrera = $editar->id_carrera;
        }
    }

    public function btn_disable($id_carrera,$esta){
        $id = base64_decode($id_carrera);
        $status = $esta;
        if ($id){
            $this->id_carrera = $id;
            $this->carrera_estado = $status;
            if ($status == 0){
                $this->messageDelete = "¿Está seguro que desea deshabilitar esta carrera?";
            }else{
                $this->messageDelete = "¿Está seguro que desea habilitar esta carrera?";
            }
        }
    }

    public function disable_carrera(){
        try {
            if (!Gate::allows('disable_carrera')) {
                session()->flash('error_delete', 'No tiene permisos para cambiar los estados.');
                return;
            }

            $this->validate([
                'id_carrera' => 'required|integer',
                'carrera_estado' => 'required|integer',
            ], [
                'id_carrera.required' => 'El identificador es obligatorio.',
                'id_carrera.integer' => 'El identificador debe ser un número entero.',

                'carrera_estado.required' => 'El estado es obligatorio.',
                'carrera_estado.integer' => 'El estado debe ser un número entero.',
            ]);

            DB::beginTransaction();
            $carrera_delete = Carrera::find($this->id_carrera);
            $carrera_delete->carrera_estado = $this->carrera_estado;
            if ($carrera_delete->save()) {
                DB::commit();
                $this->dispatch('hide_modal_delete_carrera');
                if ($this->carrera_estado == 0){
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

    public function save_carrera(){
        try {
            $this->validate([
                'carrera_nombre' => 'required|string',
                'carrera_descripcion' => 'required|string|max:1500',
                'carrera_imagen' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
                'carrera_estado' => 'nullable|integer',
                'id_carrera' => 'nullable|integer',
            ], [
                'carrera_nombre.required' => 'El nombre de la carrera es obligatorio.',
                'carrera_nombre.string' => 'El nombre de la carrera debe ser una cadena de texto.',

                'carrera_descripcion.required' => 'La descripción de la carrera es obligatorio.',
                'carrera_descripcion.string' => 'La descripción de la carrera debe ser una cadena de texto.',
                'carrera_descripcion.max' => 'La descripción no debe superar los 1500 caracteres.',

                'carrera_imagen.file' => 'Debe cargar un archivo válido.',
                'carrera_imagen.mimes' => 'El archivo debe ser JPG, JPEG o PNG.',
                'carrera_imagen.max' => 'El archivo no puede exceder los 2MB.',

                'carrera_estado.integer' => 'El estado debe ser un número entero.',

                'id_carrera.integer' => 'El identificador debe ser un número entero.',
            ]);

            if (!$this->id_carrera) { // INSERT
                if (!Gate::allows('crear_carrera')) {
                    session()->flash('error_modal', 'No tiene permisos para crear.');
                    return;
                }

                $validar = DB::table('carreras')->where('carrera_nombre', '=',$this->carrera_nombre)->exists();

                if (!$validar) {
                    $microtime = microtime(true);

                    DB::beginTransaction();
                    $carrera = new Carrera();
                    $carrera->id_users = Auth::id();
                    $carrera->carrera_nombre = $this->carrera_nombre;
                    $carrera->carrera_descripcion = $this->carrera_descripcion;
                    if ($this->carrera_imagen) {
                        $carrera->carrera_imagen = $this->general->save_files($this->carrera_imagen, 'admin/carrera', true);
                    } else {
                        $carrera->carrera_imagen = 'uploads/admin/carrera/birrete.png';
                    }
                    $carrera->carrera_microtime = $microtime;
                    $carrera->carrera_estado = 1;
                    if ($carrera->save()) {
                        DB::commit();
                        $this->dispatch('hide_modal_carrera');
                        session()->flash('success', 'Registro guardado correctamente.');
                    } else {
                        DB::rollBack();
                        session()->flash('error_modal', 'Ocurrió un error al guardar el registro.');
                        return;
                    }
                } else{
                    session()->flash('error_modal', 'La carrera ingresada ya existe.');
                    return;
                }
            } else { // UPDATE
                if (!Gate::allows('actualizar_carrera')) {
                    session()->flash('error_modal', 'No tiene permisos para actualizar.');
                    return;
                }

                $validar_update = DB::table('carreras')
                    ->where('id_carrera', '<>',$this->id_carrera)
                    ->where('carrera_nombre', '=',$this->carrera_nombre)
                    ->exists();

                if (!$validar_update){
                    DB::beginTransaction();
                    $update_carrera = Carrera::findOrFail($this->id_carrera);
                    $update_carrera->carrera_nombre = $this->carrera_nombre;
                    $update_carrera->carrera_descripcion = $this->carrera_descripcion;

                    if ($this->carrera_imagen) {
                        try {
                            unlink($update_carrera->carrera_imagen);
                        } catch (\Exception $e) {}

                        $update_carrera->carrera_imagen = $this->general->save_files($this->carrera_imagen, 'admin/carrera', true);
                    }

                    if (!$update_carrera->save()) {
                        session()->flash('error_modal', 'No se pudo actualizar el registro.');
                        return;
                    }

                    DB::commit();
                    $this->dispatch('hide_modal_carrera');
                    session()->flash('success', 'Registro actualizado correctamente.');
                } else{
                    session()->flash('error_modal', 'La carrera ingresada ya existe.');
                    return;
                }
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
