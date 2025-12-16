<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Logs;
use App\Models\Categoria;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Categorias extends Component{
    use WithPagination, WithoutUrlPagination;
    private $logs;
    private $categoria;
    public function __construct(){
        $this->logs = new Logs();
        $this->categoria = new Categoria();
    }
    public $search_categoria = "";
    public $pagination_categoria = 9;
    public $id_categoria = "";
    public $id_especialidad = "";
    public $categoria_nombre = "";
    public $categoria_descripcion = "";
    public $categoria_estado = "";
    public $messageDelete = "";
    public function mount($id_especialidad){
        $this->id_especialidad = $id_especialidad;
    }
    public function render(){
        $listar_categorias = $this->categoria->listar_categorias_vista($this->id_especialidad, $this->search_categoria,$this->pagination_categoria);
        return view('livewire.admin.categorias', compact('listar_categorias'));
    }

    public function clear_form(){
        $this->id_categoria = "";
        $this->categoria_nombre = "";
        $this->categoria_descripcion = "";
        $this->categoria_estado = "";
    }

    public function edit_data($id){
        $editar = Categoria::find(base64_decode($id));
        if ($editar){
            $this->categoria_nombre = $editar->categoria_nombre;
            $this->categoria_descripcion = $editar->categoria_descripcion;
            $this->id_especialidad = $editar->id_especialidad;
            $this->id_categoria = $editar->id_categoria;
        }
    }

    public function btn_disable($id_categoria,$esta){
        $id = base64_decode($id_categoria);
        $status = $esta;
        if ($id){
            $this->id_categoria = $id;
            $this->categoria_estado = $status;
            if ($status == 0){
                $this->messageDelete = "¿Está seguro que desea deshabilitar esta categoría?";
            }else{
                $this->messageDelete = "¿Está seguro que desea habilitar esta categoría?";
            }
        }
    }

    public function disable_categoria(){
        try {
            if (!Gate::allows('disable_categoria')) {
                session()->flash('error_delete', 'No tiene permisos para cambiar los estados.');
                return;
            }

            $this->validate([
                'id_categoria' => 'required|integer',
                'categoria_estado' => 'required|integer',
            ], [
                'id_categoria.required' => 'El identificador es obligatorio.',
                'id_categoria.integer' => 'El identificador debe ser un número entero.',

                'categoria_estado.required' => 'El estado es obligatorio.',
                'categoria_estado.integer' => 'El estado debe ser un número entero.',
            ]);

            DB::beginTransaction();
            $categoria_delete = Categoria::find($this->id_categoria);
            $categoria_delete->categoria_estado = $this->categoria_estado;
            if ($categoria_delete->save()) {
                DB::commit();
                $this->dispatch('hide_modal_delete_categoria');
                if ($this->categoria_estado == 0){
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

    public function save_categoria(){
        try {
            $this->validate([
                'id_especialidad' => 'required|integer',
                'categoria_nombre' => 'required|string',
                'categoria_descripcion' => 'nullable|string|max:1500',
                'categoria_estado' => 'nullable|integer',
                'id_categoria' => 'nullable|integer',
            ], [
                'id_especialidad.required' => 'La especialidad es obligatorio.',
                'id_especialidad.integer' => 'La especialidad debe ser un número entero.',

                'categoria_nombre.required' => 'El nombre de la categoría es obligatorio.',
                'categoria_nombre.string' => 'El nombre de la categoría debe ser una cadena de texto.',

//                'categoria_descripcion.required' => 'La descripción de la categoría es obligatorio.',
                'categoria_descripcion.string' => 'La descripción de la categoría debe ser una cadena de texto.',
                'categoria_descripcion.max' => 'La descripción no debe superar los 1500 caracteres.',

                'categoria_estado.integer' => 'El estado debe ser un número entero.',

                'id_categoria.integer' => 'El identificador debe ser un número entero.',
            ]);

            if (!$this->id_categoria) { // INSERT
                if (!Gate::allows('crear_categoria')) {
                    session()->flash('error_modal', 'No tiene permisos para crear.');
                    return;
                }

                $microtime = microtime(true);

                DB::beginTransaction();
                $save = new Categoria();
                $save->id_users = Auth::id();
                $save->id_especialidad = $this->id_especialidad;
                $save->categoria_nombre = $this->categoria_nombre;
                $save->categoria_descripcion = $this->categoria_descripcion ?: null;
                $save->categoria_microtime = $microtime;
                $save->categoria_estado = 1;
                if ($save->save()) {
                    DB::commit();
                    $this->dispatch('hide_modal_categoria');
                    session()->flash('success', 'Registro guardado correctamente.');
                } else {
                    DB::rollBack();
                    session()->flash('error_modal', 'Ocurrió un error al guardar el registro.');
                    return;
                }
            } else { // UPDATE
                if (!Gate::allows('actualizar_categoria')) {
                    session()->flash('error_modal', 'No tiene permisos para actualizar.');
                    return;
                }

                DB::beginTransaction();
                $update_categoria = Categoria::findOrFail($this->id_categoria);
                $update_categoria->categoria_nombre = $this->categoria_nombre;
                $update_categoria->categoria_descripcion = $this->categoria_descripcion;
                if (!$update_categoria->save()) {
                    session()->flash('error_modal', 'No se pudo actualizar el registro.');
                    return;
                }

                DB::commit();
                $this->dispatch('hide_modal_categoria');
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
