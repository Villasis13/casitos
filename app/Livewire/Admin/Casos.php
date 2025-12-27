<?php

namespace App\Livewire\Admin;

use App\Livewire\Intranet\sidebar;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use App\Models\Logs;
use App\Models\Caso;
use App\Models\Etapa;
use App\Models\Analisis;
use App\Models\Concepto;
use App\Models\Frase;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Casos extends Component{
    use WithPagination, WithoutUrlPagination;
    private $logs;
    private $caso;
    private $etapa;
    private $analisis;
    private $concepto;
    private $frase;
    public function __construct(){
        $this->logs = new Logs();
        $this->caso = new Caso();
        $this->etapa = new Etapa();
        $this->analisis = new Analisis();
        $this->concepto = new Concepto();
        $this->frase = new Frase();
    }
    public $search_casos;
    public $pagination_casos = 10;
    public $id_caso = "";
    public $id_categoria = "";
    public $caso_titulo = "";
    public $caso_objetivo = "";
    public $caso_contexto = "";
    public $caso_estado = "";
    public $messageDelete = "";
    // ANALISIS
    public $id_analisis = '';
    public $analisis_contenido = '';
    public $conceptos = [];
    public $frases = [];

    public function mount($id_categoria){
        $this->id_categoria = $id_categoria;
    }

    public function render(){
        try {
            $listar_casos = $this->caso->listar_casos_vista($this->id_categoria, $this->search_casos,$this->pagination_casos);
            return view('livewire.admin.casos', compact('listar_casos'));
        }catch (\Exception $e) {
            $this->logs->insertarLog($e);
        }
    }

    public function clear_form(){
        $this->id_caso = "";

        // Datos del caso
        $this->caso_titulo = "";
        $this->caso_objetivo = "";
        $this->caso_contexto = "";
        $this->caso_estado = "";
    }

    public function edit_data($id){
        $idCaso = base64_decode($id);
        $caso = Caso::find($idCaso);

        if (!$caso) {
            session()->flash('error_modal', 'No se encontró el caso seleccionado.');
            return;
        }

        // Datos del caso
        $this->id_caso = $caso->id_caso;
        $this->id_categoria  = $caso->id_categoria;
        $this->caso_titulo = $caso->caso_titulo;
        $this->caso_objetivo = $caso->caso_objetivo;
        $this->caso_contexto = $caso->caso_contexto;
        $this->caso_estado = $caso->caso_estado;
    }

    public function btn_disable($id_caso,$esta){
        $id = base64_decode($id_caso);
        $status = $esta;
        if ($id){
            $this->id_caso = $id;
            $this->caso_estado = $status;
            if ($status == 0){
                $this->messageDelete = "¿Está seguro que desea deshabilitar este registro?";
            }else{
                $this->messageDelete = "¿Está seguro que desea habilitar este registro?";
            }
        }
    }

    public function disable_caso(){
        try {
            if (!Gate::allows('disable_caso')) {
                session()->flash('error_delete', 'No tiene permisos para cambiar los estados.');
                return;
            }

            $this->validate([
                'id_caso' => 'required|integer',
                'caso_estado' => 'required|integer',
            ], [
                'id_caso.required' => 'El identificador es obligatorio.',
                'id_caso.integer' => 'El identificador debe ser un número entero.',

                'caso_estado.required' => 'El estado es obligatorio.',
                'caso_estado.integer' => 'El estado debe ser un número entero.',
            ]);

            DB::beginTransaction();
            $caso_delete = Caso::find($this->id_caso);
            $caso_delete->caso_estado = $this->caso_estado;
            if ($caso_delete->save()) {
                DB::commit();
                $this->dispatch('hide_modal_delete_caso');
                if ($this->caso_estado == 0){
                    session()->flash('success', 'Registro deshabilitado correctamente.');
                }else{
                    session()->flash('success', 'Registro habilitado correctamente.');
                }
            } else {
                DB::rollBack();
                session()->flash('error_delete', 'No se pudo cambiar el estado del registro.');
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

    public function save_casos(){
        try {
            // VALIDACIÓN
            $this->validate([
                'id_categoria' => 'required|integer',
                'caso_titulo' => 'required|string|max:255',
                'caso_objetivo' => 'required|string|max:255',
                'caso_contexto' => 'required|string|max:2000',
                'caso_estado' => 'nullable|integer',
                'id_caso' => 'nullable|integer',
            ], [
                // id_categoria
                'id_categoria.required' => 'La categoría es obligatoria.',
                'id_categoria.integer' => 'La categoría debe ser un número entero.',

                // Caso
                'caso_titulo.required' => 'El título del caso es obligatorio.',
                'caso_titulo.string' => 'El título del caso debe ser texto.',
                'caso_titulo.max' => 'El título del caso no debe superar los 255 caracteres.',

                'caso_objetivo.required' => 'El objetivo del caso es obligatorio.',
                'caso_objetivo.string' => 'El objetivo del caso debe ser texto.',
                'caso_objetivo.max' => 'El objetivo del caso no debe superar los 255 caracteres.',

                'caso_contexto.required' => 'El contexto del caso es obligatorio.',
                'caso_contexto.string' => 'El contexto del caso debe ser texto.',
                'caso_contexto.max' => 'El contexto no debe superar los 2000 caracteres.',

                'caso_estado.integer' => 'El estado del caso debe ser un número entero.',

                'id_caso.integer' => 'El identificador del caso debe ser un número entero.',
            ]);
            // LÓGICA INSERT/UPDATE
            $microtime = microtime(true);

            DB::beginTransaction();

            if (!$this->id_caso) { // CREAR
                if (!Gate::allows('crear_caso')) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No tiene permisos para crear.');
                    return;
                }

                // CASO
                $caso = new Caso();
                $caso->id_users = Auth::id();
                $caso->id_categoria  = $this->id_categoria;
                $caso->etapa_inicial_id  = null;
                $caso->caso_titulo = $this->caso_titulo;
                $caso->caso_objetivo = $this->caso_objetivo;
                $caso->caso_contexto = $this->caso_contexto;
                $caso->caso_microtime = $microtime;
                $caso->caso_estado = 1;

                if ($caso->save()) {
                    DB::commit();
                    $this->dispatch('hide_modal_casos');
                    session()->flash('success', 'Registro guardado correctamente.');
                } else {
                    DB::rollBack();
                    session()->flash('error', 'No se pudo guardar el caso.');
                    return;
                }

            } else { // ACTUALIZAR
                if (!Gate::allows('actualizar_caso')) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No tiene permisos para actualizar.');
                    return;
                }

                // CASO
                $update_caso = Caso::findOrFail($this->id_caso);
                $update_caso->caso_titulo = $this->caso_titulo;
                $update_caso->caso_objetivo = $this->caso_objetivo;
                $update_caso->caso_contexto = $this->caso_contexto;

                if (!$update_caso->save()) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No se pudo actualizar el caso.');
                    return;
                }

                DB::commit();
                $this->dispatch('hide_modal_casos');
                session()->flash('success', 'Caso actualizado correctamente');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error_modal', 'Ocurrió un error al guardar el registro. Por favor, inténtelo nuevamente.');
        }
    }

    // ANALISIS
    public function btn_crear_analisis($id_caso){
        $this->id_caso = base64_decode($id_caso);
        // reset
        $this->id_analisis = '';
        $this->analisis_contenido = '';
        $this->conceptos = [];
        $this->frases = [];
    }

    public function agregar_concepto(){
        $this->conceptos[] = [
            'id_concepto' => null,
            'titulo' => '',
            'descripcion' => '',
            'deleted' => false,
        ];
    }

    public function eliminar_concepto($index){
        if (!empty($this->conceptos[$index]['id_concepto'])) {
            $this->conceptos[$index]['deleted'] = true;
        } else {
            unset($this->conceptos[$index]);
            $this->conceptos = array_values($this->conceptos);
        }
    }

    public function agregar_frase(){
        $this->frases[] = [
            'id_frase' => null,
            'texto' => '',
            'deleted' => false,
        ];
    }

    public function eliminar_frese($index){
        if (!empty($this->frases[$index]['id_frase'])) {
            $this->frases[$index]['deleted'] = true;
        } else {
            unset($this->frases[$index]);
            $this->frases = array_values($this->frases);
        }
    }

    public function btn_editar_analisis($id_analisis){
        $id = base64_decode($id_analisis);

        $edit = Analisis::find($id);
        if ($edit){
            $this->id_analisis = $edit->id_analisis;
            $this->id_caso = $edit->id_caso;
            $this->analisis_contenido = $edit->analisis_contenido;

            // cargar conceptos activos
            $this->conceptos = Concepto::where('id_analisis', $edit->id_analisis)
                ->where('concepto_estado', 1)
                ->get()
                ->map(fn($c) => [
                    'id_concepto' => $c->id_concepto,
                    'titulo' => $c->concepto_titulo,
                    'descripcion' => $c->concepto_descripcion,
                    'deleted' => false,
                ])->toArray();

            // cargar frases activas
            $this->frases = Frase::where('id_analisis', $edit->id_analisis)
                ->where('frase_estado', 1)
                ->get()
                ->map(fn($f) => [
                    'id_frase' => $f->id_frase,
                    'texto' => $f->frase_concepto,
                    'deleted' => false,
                ])->toArray();
        }
    }

    public function save_analisis(){
        try {
            $this->validate([
                'id_analisis'        => 'nullable|integer',
                'id_caso'            => 'required|integer',
                'analisis_contenido' => 'required|string|min:10',

                'conceptos'                => 'array',
                'conceptos.*.titulo'       => 'nullable|string|max:255',
                'conceptos.*.descripcion'  => 'nullable|string|max:500',

                'frases'              => 'array',
                'frases.*.texto'      => 'nullable|string|max:500',
            ], [
                'id_caso.required' => 'No se detectó el caso.',
                'analisis_contenido.required' => 'El análisis es obligatorio.',
                'analisis_contenido.min' => 'El análisis debe tener al menos 10 caracteres.',
                'id_analisis.integer' => 'El identificador debe ser un número entero.',
            ]);

            if (!$this->id_analisis) { // INSERT

                if (!Gate::allows('crear_analisis')) {
                    session()->flash('error_modal_analisis', 'No tiene permisos para crear.');
                    return;
                }

                // CONDICIONES
                $tieneConcepto = false;
                foreach ($this->conceptos as $c) {
                    $titulo = trim($c['titulo'] ?? '');
                    $desc   = trim($c['descripcion'] ?? '');
                    if ($titulo !== '' || $desc !== '') {
                        $tieneConcepto = true;
                        break;
                    }
                }

                $tieneFrase = false;
                foreach ($this->frases as $f) {
                    if (trim($f ?? '') !== '') {
                        $tieneFrase = true;
                        break;
                    }
                }

                if (!$tieneConcepto) {
                    session()->flash('error_modal_analisis', 'Debe registrar al menos 1 concepto.');
                    return;
                }

                if (!$tieneFrase) {
                    session()->flash('error_modal_analisis', 'Debe registrar al menos 1 frase.');
                    return;
                }

                $microtime = (string) microtime(true);

                DB::beginTransaction();

                // GUARDAR ANÁLISIS
                $save_analisis = new Analisis();
                $save_analisis->id_users = Auth::id();
                $save_analisis->id_caso = $this->id_caso;
                $save_analisis->analisis_contenido = $this->analisis_contenido;
                $save_analisis->analisis_microtime = $microtime;
                $save_analisis->analisis_estado = 1;

                if (!$save_analisis->save()) {
                    DB::rollBack();
                    session()->flash('error_modal_analisis', 'No se pudo crear el análisis.');
                    return;
                }

                // GUARDAR CONCEPTOS
                foreach ($this->conceptos as $c) {

                    $titulo = trim($c['titulo'] ?? '');
                    $desc   = trim($c['descripcion'] ?? '');

                    // no guardes vacíos
                    if ($titulo === '' && $desc === '') {
                        continue;
                    }

                    $save_concepto = new Concepto();
                    $save_concepto->id_users = Auth::id();
                    $save_concepto->id_analisis = $save_analisis->id_analisis;
                    $save_concepto->concepto_titulo = $titulo;
                    $save_concepto->concepto_descripcion = $desc;
                    $save_concepto->concepto_microtime = $microtime;
                    $save_concepto->concepto_estado = 1;

                    if (!$save_concepto->save()) {
                        DB::rollBack();
                        session()->flash('error_modal_analisis', 'No se pudo crear un concepto.');
                        return;
                    }
                }

                // GUARDAR FRASES
                foreach ($this->frases as $f) {
                    $frase = trim($f ?? '');
                    // no guardes vacíos
                    if ($frase === '') {
                        continue;
                    }

                    $save_frase = new Frase();
                    $save_frase->id_users = Auth::id();
                    $save_frase->id_analisis = $save_analisis->id_analisis;
                    $save_frase->frase_concepto = $frase;
                    $save_frase->frase_microtime = $microtime;
                    $save_frase->frase_estado = 1;

                    if (!$save_frase->save()) {
                        DB::rollBack();
                        session()->flash('error_modal_analisis', 'No se pudo crear una frase.');
                        return;
                    }
                }

                DB::commit();
                $this->dispatch('hide_modal_crear_analisis');
                session()->flash('success', 'Análisis guardado correctamente.');
                return;
            } else {

                if (!Gate::allows('update_analisis')) {
                    session()->flash('error_modal_analisis', 'No tiene permisos para actualizar.');
                    return;
                }

                $tieneConcepto = false;
                foreach ($this->conceptos as $c) {
                    if (($c['deleted'] ?? false) === true) continue;

                    $titulo = trim($c['titulo'] ?? '');
                    $desc   = trim($c['descripcion'] ?? '');

                    if ($titulo !== '' || $desc !== '') {
                        $tieneConcepto = true;
                        break;
                    }
                }

                $tieneFrase = false;
                foreach ($this->frases as $f) {
                    if (($f['deleted'] ?? false) === true) continue;

                    $texto = trim($f['texto'] ?? '');
                    if ($texto !== '') {
                        $tieneFrase = true;
                        break;
                    }
                }

                if (!$tieneConcepto) {
                    session()->flash('error_modal_analisis', 'Debe registrar al menos 1 concepto.');
                    return;
                }

                if (!$tieneFrase) {
                    session()->flash('error_modal_analisis', 'Debe registrar al menos 1 frase.');
                    return;
                }

                $microtime = (string) microtime(true);

                DB::beginTransaction();

                // UPDATE ANALISIS
                $update_analisis = Analisis::findOrFail($this->id_analisis);
                $update_analisis->analisis_contenido = $this->analisis_contenido;

                if (!$update_analisis->save()) {
                    DB::rollBack();
                    session()->flash('error_modal_analisis', 'No se pudo actualizar el análisis.');
                    return;
                }

                // CONCEPTOS
                foreach ($this->conceptos as $c) {

                    $idConcepto = $c['id_concepto'] ?? null;
                    $deleted    = $c['deleted'] ?? false;

                    $titulo = trim($c['titulo'] ?? '');
                    $desc   = trim($c['descripcion'] ?? '');

                    // EXISTE -> UPDATE CONCEPTOS
                    if (!empty($idConcepto)) {
                        $conceptoDB = Concepto::find($idConcepto);
                        if (!$conceptoDB) continue;

                        if ($deleted) {
                            $conceptoDB->concepto_estado = 0;
                        } else {
                            // si está vacío y no deleted, puedes decidir: o permitirlo o forzar estado 0
                            if ($titulo === '' && $desc === '') {
                                $conceptoDB->concepto_estado = 0;
                            } else {
                                $conceptoDB->concepto_titulo = $titulo;
                                $conceptoDB->concepto_descripcion = $desc;
                                $conceptoDB->concepto_estado = 1;
                            }
                        }

                        if (!$conceptoDB->save()) {
                            DB::rollBack();
                            session()->flash('error_modal_analisis', 'No se pudo actualizar un concepto.');
                            return;
                        }

                        continue;
                    }

                    // NUEVO CONCEPTOS
                    if ($deleted) continue;
                    if ($titulo === '' && $desc === '') continue;

                    $save_concepto = new Concepto();
                    $save_concepto->id_users = Auth::id();
                    $save_concepto->id_analisis = $update_analisis->id_analisis;
                    $save_concepto->concepto_titulo = $titulo;
                    $save_concepto->concepto_descripcion = $desc;
                    $save_concepto->concepto_microtime = $microtime;
                    $save_concepto->concepto_estado = 1;

                    if (!$save_concepto->save()) {
                        DB::rollBack();
                        session()->flash('error_modal_analisis', 'No se pudo crear un concepto nuevo.');
                        return;
                    }
                }

                // FRASES
                foreach ($this->frases as $f) {

                    $idFrase = $f['id_frase'] ?? null;
                    $deleted = $f['deleted'] ?? false;

                    $texto = trim($f['texto'] ?? '');

                    // EXISTE -> UPDATE FRASES
                    if (!empty($idFrase)) {
                        $fraseDB = Frase::find($idFrase);
                        if (!$fraseDB) continue;

                        if ($deleted) {
                            $fraseDB->frase_estado = 0;
                        } else {
                            if ($texto === '') {
                                $fraseDB->frase_estado = 0;
                            } else {
                                $fraseDB->frase_concepto = $texto;
                                $fraseDB->frase_estado = 1;
                            }
                        }

                        if (!$fraseDB->save()) {
                            DB::rollBack();
                            session()->flash('error_modal_analisis', 'No se pudo actualizar una frase.');
                            return;
                        }

                        continue;
                    }

                    // NUEVA -> CREATE FRASES
                    if ($deleted) continue;
                    if ($texto === '') continue;

                    $save_frase = new Frase();
                    $save_frase->id_users = Auth::id();
                    $save_frase->id_analisis = $update_analisis->id_analisis;
                    $save_frase->frase_concepto = $texto;
                    $save_frase->frase_microtime = $microtime;
                    $save_frase->frase_estado = 1;

                    if (!$save_frase->save()) {
                        DB::rollBack();
                        session()->flash('error_modal_analisis', 'No se pudo crear una frase nueva.');
                        return;
                    }
                }

                DB::commit();

                $this->dispatch('hide_modal_crear_analisis');
                session()->flash('success', 'Análisis actualizado correctamente.');
                return;
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
            session()->flash('error_modal_analisis', 'Revisa los campos ingresados.');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error_modal_analisis', 'Ocurrió un error al guardar. Por favor, inténtelo nuevamente.');
        }
    }

}
