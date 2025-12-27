<?php

namespace App\Livewire\Admin;

use App\Models\Etapa;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use App\Models\Logs;
use App\Models\Alternativa;
use App\Models\Alternativadestino;
use App\Models\Pregunta;

class Etapas extends Component{
    use WithPagination, WithoutUrlPagination;
    private $logs;
    private $etapa;
    private $alternativa;
    private $alternativadestino;
    private $pregunta;
    public function __construct(){
        $this->logs = new Logs();
        $this->etapa = new Etapa();
        $this->alternativa = new Alternativa();
        $this->alternativadestino = new Alternativadestino();
        $this->pregunta = new Pregunta();
    }
    public $search_etapa = "";
    public $pagination_etapa = 10;
    public $id_caso = "";
    public $id_etapa = "";
    public $etapa_titulo = "";
    public $etapa_problema = "";
    public $etapa_final = false;
    public $etapa_estado = 0;
    public $alt_a_puntos = "";
    public $alt_a_texto = "";
    public $alt_b_puntos = "";
    public $alt_b_texto = "";
    public $alt_c_puntos = "";
    public $alt_c_texto = "";
    public $etapa_orden = "";
    public $id_destino_a = "";
    public $id_destino_b = "";
    public $id_destino_c = "";
    public $listar_opc_etapas = [];
    public $id_etapa_a = "";
    public $id_etapa_b = "";
    public $id_etapa_c = "";
    public $etapa_pregunta_doctrinal = false;
    public $pregunta_descripcion = "";
    public $pregunta_texto = "";
    public $pregunta_clave = "";
    public $pregunta_puntos = "";
    public function mount($id_caso){
        $this->id_caso = $id_caso;
    }

    public function render(){
        try {
            $listar_etapa = $this->etapa->listar_etapa_vista($this->id_caso, $this->search_etapa,$this->pagination_etapa);
            return view('livewire.admin.etapas', compact('listar_etapa'));
        }catch (\Exception $e) {
            $this->logs->insertarLog($e);
        }
    }

    public function clear_form(){
        $this->id_etapa = "";
        $this->etapa_titulo = "";
        $this->etapa_problema = "";
        $this->etapa_final = false;
        $this->etapa_estado = "";
    }

    public function save_etapa(){
        try {
            // VALIDACIÓN
            $this->validate([
                'id_caso' => 'required|integer',
                'etapa_titulo' => 'required|string|max:255',
                'etapa_problema' => 'required|string|max:998',
                'id_etapa' => 'nullable|integer',
            ], [
                'id_caso.required' => 'La categoría es obligatoria.',
                'id_caso.integer' => 'La categoría debe ser un número entero.',

                'etapa_titulo.required' => 'El título del caso es obligatorio.',
                'etapa_titulo.string' => 'El título del caso debe ser texto.',
                'etapa_titulo.max' => 'El título del caso no debe superar los 255 caracteres.',

                'etapa_problema.required' => 'El problema del caso es obligatorio.',
                'etapa_problema.string' => 'El problema del caso debe ser texto.',
                'etapa_problema.max' => 'El problema del caso no debe superar los 998 caracteres.',

                'id_etapa.integer' => 'El identificador del caso debe ser un número entero.',
            ]);
            // LÓGICA INSERT/UPDATE
            $microtime = microtime(true);

            DB::beginTransaction();

            if (!$this->id_etapa) { // CREAR
                if (!Gate::allows('crear_etapa')) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No tiene permisos para crear.');
                    return;
                }

                // Obtener el último orden
                $ultimoOrden = DB::table('etapas')
                    ->where('id_caso', $this->id_caso)
                    ->max('etapa_orden');

                // Si no existe, empieza en 1; si existe, suma 1
                $nuevoOrden = $ultimoOrden ? ($ultimoOrden + 1) : 1;

                $save_etapa = new Etapa();
                $save_etapa->id_users = Auth::id();
                $save_etapa->id_caso  = $this->id_caso;
                $save_etapa->etapa_titulo = $this->etapa_titulo;
                $save_etapa->etapa_problema = $this->etapa_problema;
                $save_etapa->etapa_orden     = $nuevoOrden;
                $save_etapa->etapa_final = $this->etapa_final ? 1 : 0;
                $save_etapa->etapa_pregunta_doctrinal = 0;
                $save_etapa->etapa_microtime = $microtime;
                $save_etapa->etapa_estado = 1;

                if ($save_etapa->save()) {
                    DB::commit();
                    $this->dispatch('hide_modal_etapa');
                    session()->flash('success', 'Registro guardado correctamente.');
                } else {
                    DB::rollBack();
                    session()->flash('error', 'No se pudo guardar la etapa.');
                    return;
                }

            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error_modal', 'Ocurrió un error al guardar el registro. Por favor, inténtelo nuevamente.');
        }
    }

    //ALTERNATIVAS
    public function edit_etapa($id){
        $id_etapa = base64_decode($id);
        $etapa = Etapa::find($id_etapa);

        if (!$etapa) {
            session()->flash('error_modal', 'No se encontró la etapa seleccionado.');
            return;
        }

        // DATOS DE LA ETAPA
        $this->id_etapa = $etapa->id_etapa;
        $this->id_caso = $etapa->id_caso;
        $this->etapa_titulo = $etapa->etapa_titulo;
        $this->etapa_problema = $etapa->etapa_problema;
        $this->etapa_orden = $etapa->etapa_orden;
        $this->etapa_pregunta_doctrinal = $etapa->etapa_pregunta_doctrinal == 1;

        // PREGUNTA DOCTRINAL
        $this->pregunta_descripcion = "";
        $this->pregunta_texto = "";
        $this->pregunta_clave = "";
        $this->pregunta_puntos = "";

        // SOLO si la etapa indica que SI tiene pregunta, recién consultamos preguntas
        if ($this->etapa_pregunta_doctrinal) {
            $preg = DB::table('preguntas')
                ->where('id_etapa', $this->id_etapa)
                ->where('pregunta_estado', 1)
                ->orderByDesc('id_pregunta')
                ->first();

            if ($preg) {
                $this->pregunta_descripcion = $preg->pregunta_descripcion ?? "";
                $this->pregunta_texto = $preg->pregunta_texto ?? "";
                $this->pregunta_clave = $preg->pregunta_palabra_clave ?? "";
                $this->pregunta_puntos = $preg->pregunta_puntos ?? "";
            }
        }

        // LISTA ETAPAS DESTINO (solo superiores)
        $this->listar_opc_etapas = $this->etapa->listar_opc_etapas($this->id_caso, $this->etapa_orden);

        // SIEMPRE LIMPIA CAMPOS (por defecto)
        $this->alt_a_puntos = "";
        $this->alt_a_texto = "";
        $this->id_destino_a = "";
        $this->id_etapa_a = "";

        $this->alt_b_puntos = "";
        $this->alt_b_texto = "";
        $this->id_destino_b = "";
        $this->id_etapa_b = "";

        $this->alt_c_puntos = "";
        $this->alt_c_texto = "";
        $this->id_destino_c = "";
        $this->id_etapa_c = "";

        // CONSULTA: alternativas + destinos (últimas por letra)
        $alts = DB::table('alternativas as a')
            ->leftJoin('alternativas_destinos as d', 'd.id_alternativa', '=', 'a.id_alternativa')
            ->where('a.id_etapa', $this->id_etapa)
            ->where('a.alternativa_estado', 1)
            ->orderByDesc('a.id_alternativa')
            ->select(
                'a.alternativa_letra',
                'a.alternativa_texto',
                'a.alternativa_puntos',
                'd.destino_tipo',
                'd.id_etapa_destino'
            )
            ->get();

        // CONDICIÓN: si no hay, se queda limpio (ya está limpio)
        if ($alts->isEmpty()) {
            return;
        }

        // MAPEAR 1 sola (la más reciente) por letra A/B/C
        $map = [];
        foreach ($alts as $row) {
            $letra = strtoupper((string)$row->alternativa_letra);
            if (!isset($map[$letra])) {
                $map[$letra] = $row;
            }
        }

        // CARGAR A
        if (isset($map['A'])) {
            $this->alt_a_texto  = $map['A']->alternativa_texto ?? "";
            $this->alt_a_puntos = $map['A']->alternativa_puntos ?? "";
            $this->id_destino_a = $map['A']->destino_tipo ?? "";
            $this->id_etapa_a   = ($this->id_destino_a == 1) ? ($map['A']->id_etapa_destino ?? "") : "";
        }

        // CARGAR B
        if (isset($map['B'])) {
            $this->alt_b_texto  = $map['B']->alternativa_texto ?? "";
            $this->alt_b_puntos = $map['B']->alternativa_puntos ?? "";
            $this->id_destino_b = $map['B']->destino_tipo ?? "";
            $this->id_etapa_b   = ($this->id_destino_b == 1) ? ($map['B']->id_etapa_destino ?? "") : "";
        }

        // CARGAR C
        if (isset($map['C'])) {
            $this->alt_c_texto  = $map['C']->alternativa_texto ?? "";
            $this->alt_c_puntos = $map['C']->alternativa_puntos ?? "";
            $this->id_destino_c = $map['C']->destino_tipo ?? "";
            $this->id_etapa_c   = ($this->id_destino_c == 1) ? ($map['C']->id_etapa_destino ?? "") : "";
        }
    }

    public function save_alternativas(){
        try {
            if (!Gate::allows('save_alternativas')) {
                session()->flash('error_modal_alter', 'No tiene permisos para crear las alternativas.');
                return;
            }

            $this->validate([
                'id_etapa' => 'required|integer',
                'etapa_titulo' => 'nullable|string|max:255',
                'etapa_problema' => 'nullable|string|max:998',

                // Alternativas obligatorias
                'alt_a_texto' => 'required|string|max:2000',
                'alt_a_puntos' => 'required|integer|min:0',
                'id_destino_a' => 'required|integer|in:1,3',
                'id_etapa_a' => 'required_if:id_destino_a,1|nullable|integer',

                'alt_b_texto' => 'required|string|max:2000',
                'alt_b_puntos' => 'required|integer|min:0',
                'id_destino_b' => 'required|integer|in:1,3',
                'id_etapa_b' => 'required_if:id_destino_b,1|nullable|integer',

                'alt_c_texto' => 'required|string|max:2000',
                'alt_c_puntos' => 'required|integer|min:0',
                'id_destino_c' => 'required|integer|in:1,3',
                'id_etapa_c' => 'required_if:id_destino_c,1|nullable|integer',

                // Switch pregunta doctrinal
                'etapa_pregunta_doctrinal' => 'nullable|boolean',
                'pregunta_descripcion' => 'required_if:etapa_pregunta_doctrinal,1|nullable|string|max:255',
                'pregunta_texto'       => 'required_if:etapa_pregunta_doctrinal,1|nullable|string|max:998',
                'pregunta_clave'       => 'required_if:etapa_pregunta_doctrinal,1|nullable|string|max:255',
                'pregunta_puntos'      => 'required_if:etapa_pregunta_doctrinal,1|nullable|integer|min:0',
            ], [
                'id_etapa.required' => 'El identificador es obligatorio.',
                'id_etapa.integer' => 'El identificador debe ser un número entero.',

                'etapa_titulo.string' => 'El título del caso debe ser texto.',
                'etapa_titulo.max' => 'El título del caso no debe superar los 255 caracteres.',

                'etapa_problema.string' => 'El problema del caso debe ser texto.',
                'etapa_problema.max' => 'El problema del caso no debe superar los 998 caracteres.',

                'alt_a_texto.required' => 'El texto de la alternativa A es obligatorio.',
                'alt_a_puntos.required' => 'Los puntos de la alternativa A son obligatorios.',
                'alt_a_puntos.integer' => 'Los puntos de la alternativa A deben ser numéricos.',
                'id_destino_a.required' => 'El destino de la alternativa A es obligatorio.',
                'id_etapa_a.required_if' => 'Debe seleccionar una etapa destino para la alternativa A.',

                'alt_b_texto.required' => 'El texto de la alternativa B es obligatorio.',
                'alt_b_puntos.required' => 'Los puntos de la alternativa B son obligatorios.',
                'alt_b_puntos.integer' => 'Los puntos de la alternativa B deben ser numéricos.',
                'id_destino_b.required' => 'El destino de la alternativa B es obligatorio.',
                'id_etapa_b.required_if' => 'Debe seleccionar una etapa destino para la alternativa B.',

                'alt_c_texto.required' => 'El texto de la alternativa C es obligatorio.',
                'alt_c_puntos.required' => 'Los puntos de la alternativa C son obligatorios.',
                'alt_c_puntos.integer' => 'Los puntos de la alternativa C deben ser numéricos.',
                'id_destino_c.required' => 'El destino de la alternativa C es obligatorio.',
                'id_etapa_c.required_if' => 'Debe seleccionar una etapa destino para la alternativa C.',

                'pregunta_descripcion.required_if' => 'La descripción de la pregunta doctrinal es obligatoria.',
                'pregunta_texto.required_if'       => 'El texto de la pregunta doctrinal es obligatorio.',
                'pregunta_clave.required_if'       => 'La palabra clave de la pregunta doctrinal es obligatoria.',
                'pregunta_puntos.required_if'      => 'Los puntos de la pregunta doctrinal son obligatorios.',
                'pregunta_puntos.integer'          => 'Los puntos de la pregunta doctrinal deben ser numéricos.',
            ]);

            DB::beginTransaction();

            // ACTUALIZA ETAPA - OPCIONAL
            $update_etapa = Etapa::find($this->id_etapa);
            if (!$update_etapa) {
                DB::rollBack();
                session()->flash('error_modal', 'No se encontró la etapa.');
                return;
            }

            $update_etapa->etapa_titulo = $this->etapa_titulo;
            $update_etapa->etapa_problema = $this->etapa_problema;
            $update_etapa->etapa_pregunta_doctrinal = $this->etapa_pregunta_doctrinal ? 1 : 0;

            if (!$update_etapa->save()) {
                DB::rollBack();
                session()->flash('error_modal', 'No se pudo actualizar la etapa.');
                return;
            }

            $microtime = microtime(true);

            $alternativas_data = [
                ['letra' => 'A', 'puntos' => $this->alt_a_puntos, 'texto' => $this->alt_a_texto, 'destino_tipo' => $this->id_destino_a, 'id_etapa_destino' => $this->id_etapa_a],
                ['letra' => 'B', 'puntos' => $this->alt_b_puntos, 'texto' => $this->alt_b_texto, 'destino_tipo' => $this->id_destino_b, 'id_etapa_destino' => $this->id_etapa_b],
                ['letra' => 'C', 'puntos' => $this->alt_c_puntos, 'texto' => $this->alt_c_texto, 'destino_tipo' => $this->id_destino_c, 'id_etapa_destino' => $this->id_etapa_c],
            ];

            foreach ($alternativas_data as $alt_data) {

                // Normaliza destino: si es 3 => null
                if ((int)$alt_data['destino_tipo'] === 3) {
                    $alt_data['id_etapa_destino'] = null;
                }

                // BUSCAR alternativa existente (por etapa + letra)
                $alternativa = Alternativa::where('id_etapa', $this->id_etapa)
                    ->where('alternativa_letra', $alt_data['letra'])
                    ->where('alternativa_estado', 1)
                    ->orderByDesc('id_alternativa')
                    ->first();

                if ($alternativa) {
                    // UPDATE alternativa existente
                    $alternativa->id_users = Auth::id();
                    $alternativa->alternativa_texto = $alt_data['texto'];
                    $alternativa->alternativa_puntos = $alt_data['puntos'];
                    // alternativa_estado se mantiene

                    if (!$alternativa->save()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se pudo actualizar una de las alternativas.');
                        return;
                    }
                } else {
                    // CREATE alternativa si no existe
                    $alternativa = new Alternativa();
                    $alternativa->id_users = Auth::id();
                    $alternativa->id_etapa = $this->id_etapa;
                    $alternativa->id_caso = $this->id_caso;
                    $alternativa->alternativa_letra = $alt_data['letra'];
                    $alternativa->alternativa_texto = $alt_data['texto'];
                    $alternativa->alternativa_puntos = $alt_data['puntos'];
                    $alternativa->alternativa_microtime = $microtime;
                    $alternativa->alternativa_estado = 1;

                    if (!$alternativa->save()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se pudo guardar una de las alternativas.');
                        return;
                    }
                }

                // UPDATE o CREATE destino para esa alternativa
                $alter_destino = Alternativadestino::where('id_alternativa', $alternativa->id_alternativa)
                    ->where('destino_estado', 1)
                    ->first();

                if ($alter_destino) {
                    // UPDATE
                    $alter_destino->id_users = Auth::id();
                    $alter_destino->destino_tipo = $alt_data['destino_tipo'];
                    $alter_destino->id_etapa_destino = $alt_data['id_etapa_destino'];

                    if (!$alter_destino->save()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se pudo actualizar el destino.');
                        return;
                    }
                } else {
                    // CREATE
                    $alter_destino = new Alternativadestino();
                    $alter_destino->id_users = Auth::id();
                    $alter_destino->id_alternativa = $alternativa->id_alternativa;
                    $alter_destino->destino_tipo = (int)$alt_data['destino_tipo'];
                    $alter_destino->id_etapa_destino = $alt_data['id_etapa_destino'];
                    $alter_destino->destino_microtime = $microtime;
                    $alter_destino->destino_estado = 1;

                    if (!$alter_destino->save()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se pudo guardar el destino.');
                        return;
                    }
                }
            }

            // PREGUNTA
            $pregunta_existente = Pregunta::where('id_etapa', $this->id_etapa)
                ->where('pregunta_estado', 1)
                ->orderByDesc('id_pregunta')
                ->first();

            if ($this->etapa_pregunta_doctrinal) {

                // Si está ON: crear o actualizar
                if ($pregunta_existente) {
                    $pregunta = $pregunta_existente;
                } else {
                    $pregunta = new Pregunta();
                    $pregunta->id_etapa = $this->id_etapa;
                    $pregunta->id_caso = $this->id_caso;
                }

                $pregunta->id_users = Auth::id();
                $pregunta->pregunta_descripcion = $this->pregunta_descripcion;
                $pregunta->pregunta_texto  = $this->pregunta_texto;
                $pregunta->pregunta_palabra_clave = $this->pregunta_clave;
                $pregunta->pregunta_puntos = $this->pregunta_puntos;
                $pregunta->pregunta_microtime = $microtime;
                $pregunta->pregunta_estado = 1;

                if (!$pregunta->save()) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No se pudo guardar/actualizar la pregunta.');
                    return;
                }

            } else {

                // Si está OFF: desactivar pregunta existente
                if ($pregunta_existente) {
                    $pregunta_existente->pregunta_estado = 0;
                    $pregunta_existente->id_users = Auth::id();

                    if (!$pregunta_existente->save()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se pudo desactivar la pregunta.');
                        return;
                    }
                }
            }

            DB::commit();
            $this->dispatch('hide_modal_agregar_alternativa');
            session()->flash('success', 'Etapa actualizado correctamente');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error', 'Ocurrió un error. Por favor, inténtelo nuevamente.');
        }
    }

}
