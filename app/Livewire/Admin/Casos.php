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
use App\Models\Alternativa;
use App\Models\Pregunta;
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
    private $alternativa;
    private $pregunta;
    private $analisis;
    private $concepto;
    private $frase;
    public function __construct(){
        $this->logs = new Logs();
        $this->caso = new Caso();
        $this->etapa = new Etapa();
        $this->alternativa = new Alternativa();
        $this->pregunta = new Pregunta();
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
    // Etapa 1
    public $etapa1_titulo = "";
    public $etapa1_problema = "";
    // Alternativas Etapa 1
    public $alt1a_puntos = "";
    public $alt1a_texto = "";
    public $alt1b_puntos = "";
    public $alt1b_texto = "";
    public $alt1c_puntos = "";
    public $alt1c_texto = "";
    // Pregunta Etapa 1
    public $pregunta1_descripcion = "";
    public $pregunta1_texto = "";
    public $pregunta1_clave = "";
    public $pregunta1_puntos = "";

    public $messageDelete = "";
    public $alt1a_correcta = false;
    public $alt1b_correcta = false;
    public $alt1c_correcta = false;

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
        // ID principal
        $this->id_caso = "";

        // Datos del caso
        $this->caso_titulo = "";
        $this->caso_objetivo = "";
        $this->caso_contexto = "";
        $this->caso_estado = "";

        // Etapa 1
        $this->etapa1_titulo = "";
        $this->etapa1_problema = "";

        // Alternativas Etapa 1
        $this->alt1a_puntos = "";
        $this->alt1a_texto = "";

        $this->alt1b_puntos = "";
        $this->alt1b_texto = "";

        $this->alt1c_puntos = "";
        $this->alt1c_texto = "";

        // Pregunta Etapa 1
        $this->pregunta1_descripcion = "";
        $this->pregunta1_texto = "";
        $this->pregunta1_clave = "";
        $this->pregunta1_puntos = "";

        $this->alt1a_correcta = false;
        $this->alt1b_correcta = false;
        $this->alt1c_correcta = false;
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

        // Cargar Etapa 1
        $etapa1 = Etapa::where('etapa_numero', 1)
            ->where('id_caso', $this->id_caso)
            ->first();

        if ($etapa1) {
            $this->etapa1_titulo   = $etapa1->etapa_titulo;
            $this->etapa1_problema = $etapa1->etapa_problema;

            // Cargar ALTERNATIVAS por consulta directa
            $alternativas = Alternativa::where('id_etapa', $etapa1->id_etapa)
                ->whereNull('id_alternativa_padre')
                ->get();

            // Buscar por letra para no depender del orden
            $altA = $alternativas->firstWhere('alternativa_letra', 'A');
            if ($altA) {
                $this->alt1a_puntos = $altA->alternativa_puntos;
                $this->alt1a_texto  = $altA->alternativa_texto;
            }

            $altB = $alternativas->firstWhere('alternativa_letra', 'B');
            if ($altB) {
                $this->alt1b_puntos = $altB->alternativa_puntos;
                $this->alt1b_texto  = $altB->alternativa_texto;
            }

            $altC = $alternativas->firstWhere('alternativa_letra', 'C');
            if ($altC) {
                $this->alt1c_puntos = $altC->alternativa_puntos;
                $this->alt1c_texto  = $altC->alternativa_texto;
            }

            $this->alt1a_correcta = $altA ? $altA->alternativa_correcta : false;
            $this->alt1b_correcta = $altB ? $altB->alternativa_correcta : false;
            $this->alt1c_correcta = $altC ? $altC->alternativa_correcta : false;

            // Cargar PREGUNTA de la etapa 1
            $pregunta = Pregunta::where('id_etapa', $etapa1->id_etapa)
                ->where('id_caso', $this->id_caso)
                ->first();

            if ($pregunta) {
                $this->pregunta1_descripcion = $pregunta->pregunta_descripcion;
                $this->pregunta1_texto = $pregunta->pregunta_texto;
                $this->pregunta1_clave = $pregunta->pregunta_palabra_clave;
                $this->pregunta1_puntos = $pregunta->pregunta_puntos;
            }
        }
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

    public function marcarAlternativaCorrecta($letra){
        // resetea todas
        $this->alt1a_correcta = false;
        $this->alt1b_correcta = false;
        $this->alt1c_correcta = false;

        // activa solo la seleccionada
        if ($letra === 'A') $this->alt1a_correcta = true;
        if ($letra === 'B') $this->alt1b_correcta = true;
        if ($letra === 'C') $this->alt1c_correcta = true;
    }

    public function save_casos(){
        try {
            // VALIDACIÓN
            $this->validate([
                'id_categoria' => 'required|integer',

                'caso_titulo' => 'required|string|max:255',
                'caso_objetivo' => 'required|string|max:255',
                'caso_contexto' => 'required|string|max:2000',

                'etapa1_titulo' => 'required|string|max:255',
                'etapa1_problema' => 'required|string',

                'alt1a_puntos' => 'required|numeric',
                'alt1a_texto' => 'required|string',
                'alt1b_puntos' => 'required|numeric',
                'alt1b_texto' => 'required|string',
                'alt1c_puntos' => 'required|numeric',
                'alt1c_texto' => 'required|string',

                'pregunta1_descripcion' => 'required|string',
                'pregunta1_texto' => 'required|string',
                'pregunta1_clave' => 'required|string|max:255',
                'pregunta1_puntos' => 'required|numeric',

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

                // Etapa 1
                'etapa1_titulo.required' => 'El título de la etapa 1 es obligatorio.',
                'etapa1_titulo.string' => 'El título de la etapa 1 debe ser texto.',
                'etapa1_titulo.max' => 'El título de la etapa 1 no debe superar los 255 caracteres.',

                'etapa1_problema.required' => 'El problema inicial de la etapa 1 es obligatorio.',
                'etapa1_problema.string' => 'El problema inicial de la etapa 1 debe ser texto.',

                // Alternativas (puntos numéricos)
                'alt1a_puntos.required' => 'Los puntos de la alternativa A son obligatorios.',
                'alt1a_puntos.numeric' => 'Los puntos de la alternativa A deben ser numéricos.',
                'alt1a_texto.required' => 'El texto de la alternativa A es obligatorio.',
                'alt1a_texto.string' => 'El texto de la alternativa A debe ser texto.',

                'alt1b_puntos.required' => 'Los puntos de la alternativa B son obligatorios.',
                'alt1b_puntos.numeric' => 'Los puntos de la alternativa B deben ser numéricos.',
                'alt1b_texto.required' => 'El texto de la alternativa B es obligatorio.',
                'alt1b_texto.string' => 'El texto de la alternativa B debe ser texto.',

                'alt1c_puntos.required' => 'Los puntos de la alternativa C son obligatorios.',
                'alt1c_puntos.numeric' => 'Los puntos de la alternativa C deben ser numéricos.',
                'alt1c_texto.required' => 'El texto de la alternativa C es obligatorio.',
                'alt1c_texto.string' => 'El texto de la alternativa C debe ser texto.',

                // Pregunta
                'pregunta1_descripcion.required' => 'La descripción de la pregunta doctrinal es obligatoria.',
                'pregunta1_descripcion.string' => 'La descripción de la pregunta doctrinal debe ser texto.',

                'pregunta1_texto.required' => 'El texto de la pregunta doctrinal es obligatorio.',
                'pregunta1_texto.string' => 'El texto de la pregunta doctrinal debe ser texto.',

                'pregunta1_clave.required' => 'La palabra clave de la pregunta es obligatoria.',
                'pregunta1_clave.string' => 'La palabra clave de la pregunta debe ser texto.',
                'pregunta1_clave.max' => 'La palabra clave no debe superar los 255 caracteres.',

                'pregunta1_puntos.required' => 'Los puntos de la pregunta doctrinal son obligatorios.',
                'pregunta1_puntos.numeric' => 'Los puntos de la pregunta doctrinal deben ser numéricos.',

                'id_caso.integer' => 'El identificador del caso debe ser un número entero.',
            ]);
            // LÓGICA INSERT/UPDATE
            $microtime = microtime(true);
            $mensajeSuccess = '';

            $cantidadCorrectas = 0;
            if ($this->alt1a_correcta) $cantidadCorrectas++;
            if ($this->alt1b_correcta) $cantidadCorrectas++;
            if ($this->alt1c_correcta) $cantidadCorrectas++;

            if ($cantidadCorrectas !== 1) {
                session()->flash('error_modal', 'Debe seleccionar exactamente 1 alternativa correcta.');
                return;
            }

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
                $caso->caso_titulo = $this->caso_titulo;
                $caso->caso_objetivo = $this->caso_objetivo;
                $caso->caso_contexto = $this->caso_contexto;
                $caso->caso_microtime = $microtime;
                $caso->caso_estado = 1;

                if (!$caso->save()) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No se pudo guardar el caso.');
                    return;
                }

                // ETAPA 1
                $etapa1 = new Etapa();
                $etapa1->id_users = Auth::id();
                $etapa1->id_caso = $caso->id_caso;
                $etapa1->id_alternativa_padre = null;
                $etapa1->etapa_numero = 1;
                $etapa1->etapa_titulo = $this->etapa1_titulo;
                $etapa1->etapa_problema = $this->etapa1_problema;
                $etapa1->etapa_microtime = $microtime;
                $etapa1->etapa_estado = 1;

                if (!$etapa1->save()) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No se pudo guardar la etapa.');
                    return;
                }

                // ALTERNATIVAS ETAPA 1
                $alternativas_data = [
                    ['letra' => 'A', 'puntos' => $this->alt1a_puntos, 'texto' => $this->alt1a_texto, 'correcta' => $this->alt1a_correcta],
                    ['letra' => 'B', 'puntos' => $this->alt1b_puntos, 'texto' => $this->alt1b_texto, 'correcta' => $this->alt1b_correcta],
                    ['letra' => 'C', 'puntos' => $this->alt1c_puntos, 'texto' => $this->alt1c_texto, 'correcta' => $this->alt1c_correcta],
                ];

                foreach ($alternativas_data as $alt_data) {
                    $alternativa = new Alternativa();
                    $alternativa->id_users = Auth::id();
                    $alternativa->id_etapa = $etapa1->id_etapa;
                    $alternativa->id_caso = $caso->id_caso;
                    $alternativa->id_alternativa_padre = null;
                    $alternativa->alternativa_letra = $alt_data['letra'];
                    $alternativa->alternativa_texto = $alt_data['texto'];
                    $alternativa->alternativa_puntos = $alt_data['puntos'];
                    $alternativa->alternativa_correcta = $alt_data['correcta'] ? 1 : 0;
                    $alternativa->alternativa_microtime = $microtime;
                    $alternativa->alternativa_estado = 1;

                    if (!$alternativa->save()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se pudo guardar una de las alternativas.');
                        return;
                    }
                }

                // PREGUNTA ETAPA 1
                $pregunta = new Pregunta();
                $pregunta->id_users = Auth::id();
                $pregunta->id_etapa = $etapa1->id_etapa;
                $pregunta->id_caso = $caso->id_caso;
                $pregunta->pregunta_descripcion = $this->pregunta1_descripcion;
                $pregunta->pregunta_texto  = $this->pregunta1_texto;
                $pregunta->pregunta_palabra_clave = $this->pregunta1_clave;
                $pregunta->pregunta_puntos = $this->pregunta1_puntos;
                $pregunta->pregunta_microtime = $microtime;
                $pregunta->pregunta_estado = 1;

                if (!$pregunta->save()) {
                    DB::rollBack();
                    session()->flash('error_modal', 'No se pudo guardar la pregunta.');
                    return;
                }

                $mensajeSuccess = 'Caso creado correctamente con Etapa 1.';

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

                // ETAPA 1
                $etapa1 = Etapa::where('id_caso', $update_caso->id_caso)
                    ->where('etapa_numero', 1)
                    ->first();

                if ($etapa1) {
                    $etapa1->etapa_titulo = $this->etapa1_titulo;
                    $etapa1->etapa_problema = $this->etapa1_problema;

                    if (!$etapa1->save()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se pudo actualizar la etapa.');
                        return;
                    }

                    // ALTERNATIVAS (asumiendo siempre A, B, C en orden)
                    $alternativas = Alternativa::where('id_etapa', $etapa1->id_etapa)
                        ->where('alternativa_estado', 1)
                        ->get()
                        ->keyBy('alternativa_letra');

                    if ($alternativas->isEmpty()) {
                        DB::rollBack();
                        session()->flash('error_modal', 'No se encontraron alternativas para actualizar.');
                        return;
                    }
                    if (isset($alternativas['A'])) {
                        $altA = $alternativas['A'];
                        $altA->alternativa_texto = $this->alt1a_texto;
                        $altA->alternativa_puntos = $this->alt1a_puntos;
                        $altA->alternativa_correcta = $this->alt1a_correcta ? 1 : 0;

                        if (!$altA->save()) {
                            DB::rollBack();
                            session()->flash('error_modal', 'No se pudo actualizar la alternativa A.');
                            return;
                        }
                    }
                    if (isset($alternativas['B'])) {
                        $altB = $alternativas['B'];
                        $altB->alternativa_texto = $this->alt1b_texto;
                        $altB->alternativa_puntos = $this->alt1b_puntos;
                        $altB->alternativa_correcta = $this->alt1b_correcta ? 1 : 0;

                        if (!$altB->save()) {
                            DB::rollBack();
                            session()->flash('error_modal', 'No se pudo actualizar la alternativa B.');
                            return;
                        }
                    }

                    if (isset($alternativas['C'])) {
                        $altC = $alternativas['C'];
                        $altC->alternativa_texto = $this->alt1c_texto;
                        $altC->alternativa_puntos = $this->alt1c_puntos;
                        $altC->alternativa_correcta = $this->alt1c_correcta ? 1 : 0;

                        if (!$altC->save()) {
                            DB::rollBack();
                            session()->flash('error_modal', 'No se pudo actualizar la alternativa C.');
                            return;
                        }
                    }

                    // PREGUNTA
                    if ($etapa1->pregunta) {
                        $etapa1->pregunta->update([
                            'pregunta_texto' => $this->pregunta1_texto,
                            'pregunta_descripcion' => $this->pregunta1_descripcion,
                            'pregunta_palabra_clave' => $this->pregunta1_clave,
                            'pregunta_puntos' => $this->pregunta1_puntos,
                        ]);
                    }
                }

                $mensajeSuccess = 'Registro actualizado correctamente.';
            }

            DB::commit();
            $this->dispatch('hide_modal_casos');
            session()->flash('success', $mensajeSuccess);

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error_modal', 'Ocurrió un error al guardar el registro. Por favor, inténtelo nuevamente.');
        }
    }

    // CREAR ETAPA 2
    // IDs base para Etapa 2
    public $id_caso_etapa2 = null;
    public $id_etapa1 = null;

    // Alternativas de etapa 1 (para los selects)
    public $listar_alternativas_etapa_uno = [];

    // ---------- ESCENARIO A ----------
    public $e2a_alt_padre = '';
    public $e2a_titulo = '';
    public $e2a_problema = '';
    public $e2a_altA_puntos = '';
    public $e2a_altA_texto = '';
    public $e2a_altB_puntos = '';
    public $e2a_altB_texto = '';
    public $e2a_altC_puntos = '';
    public $e2a_altC_texto = '';
    public $e2a_pregunta_desc = '';
    public $e2a_pregunta_texto = '';
    public $e2a_pregunta_clave = '';
    public $e2a_pregunta_puntos = '';

    // ---------- ESCENARIO B ----------
    public $e2b_alt_padre = '';
    public $e2b_titulo = '';
    public $e2b_problema = '';
    public $e2b_altA_puntos = '';
    public $e2b_altA_texto = '';
    public $e2b_altB_puntos = '';
    public $e2b_altB_texto = '';
    public $e2b_altC_puntos = '';
    public $e2b_altC_texto = '';
    public $e2b_pregunta_desc = '';
    public $e2b_pregunta_texto = '';
    public $e2b_pregunta_clave = '';
    public $e2b_pregunta_puntos = '';

    // ---------- ESCENARIO C ----------
    public $e2c_alt_padre = '';
    public $e2c_titulo = '';
    public $e2c_problema = '';
    public $e2c_altA_puntos = '';
    public $e2c_altA_texto = '';
    public $e2c_altB_puntos = '';
    public $e2c_altB_texto = '';
    public $e2c_altC_puntos = '';
    public $e2c_altC_texto = '';
    public $e2c_pregunta_desc = '';
    public $e2c_pregunta_texto = '';
    public $e2c_pregunta_clave = '';
    public $e2c_pregunta_puntos = '';

    // ===== CORRECTA ETAPA 2 (por escenario) =====
    public $e2a_altA_correcta = false;
    public $e2a_altB_correcta = false;
    public $e2a_altC_correcta = false;

    public $e2b_altA_correcta = false;
    public $e2b_altB_correcta = false;
    public $e2b_altC_correcta = false;

    public $e2c_altA_correcta = false;
    public $e2c_altB_correcta = false;
    public $e2c_altC_correcta = false;

    // IDs de las etapas 2 por escenario (para editar)
    public $id_etapa2_a = null;
    public $id_etapa2_b = null;
    public $id_etapa2_c = null;
    public $modo_edicion_etapa2 = false;


    public $e3 = [];
    public $e3_open = [];

    public function initE3(){
        $this->e3 = [];
        $this->e3_open = [];

        foreach (['A','B','C'] as $esc) {
            foreach (['A','B','C'] as $alt2) {
                $this->e3[$esc][$alt2] = [
                    'enabled' => false,
                    'titulo' => '',
                    'problema' => '',
                    'alts' => [
                        'A' => ['puntos' => '', 'texto' => '', 'correcta' => false],
                        'B' => ['puntos' => '', 'texto' => '', 'correcta' => false],
                        'C' => ['puntos' => '', 'texto' => '', 'correcta' => false],
                    ],
                    'preg' => ['desc' => '', 'texto' => '', 'clave' => '', 'puntos' => ''],
                ];
                $this->e3_open[$esc][$alt2] = false;
            }
        }
    }

    public function toggleE3($esc, $alt2){
        // si no está enabled, no abre
        if (empty($this->e3[$esc][$alt2]['enabled'])) {
            $this->e3_open[$esc][$alt2] = false;
            return;
        }

        $this->e3_open[$esc][$alt2] = !$this->e3_open[$esc][$alt2];
    }

    public function getAltIdByEtapa($idEtapa, $letra){
        return Alternativa::where('id_etapa', $idEtapa)
            ->where('alternativa_letra', $letra)
            ->where('alternativa_estado', 1)
            ->value('id_alternativa');
    }

    public function setCorrectaE2($escenario, $letra){
        if ($escenario === 'A') {
            $this->e2a_altA_correcta = ($letra === 'A');
            $this->e2a_altB_correcta = ($letra === 'B');
            $this->e2a_altC_correcta = ($letra === 'C');
        } elseif ($escenario === 'B') {
            $this->e2b_altA_correcta = ($letra === 'A');
            $this->e2b_altB_correcta = ($letra === 'B');
            $this->e2b_altC_correcta = ($letra === 'C');
        } else { // C
            $this->e2c_altA_correcta = ($letra === 'A');
            $this->e2c_altB_correcta = ($letra === 'B');
            $this->e2c_altC_correcta = ($letra === 'C');
        }
    }

    public function setCorrectaE3($esc, $alt2, $letra){
        foreach (['A','B','C'] as $l) {
            $this->e3[$esc][$alt2]['alts'][$l]['correcta'] = ($l === $letra);
        }
    }

    public function clear_form_etapa_dos(){

        // Lista de alternativas
        $this->listar_alternativas_etapa_uno = [];

        // Siempre que limpias, asume modo creación
        $this->modo_edicion_etapa2 = false;

        // IDs de etapas 2
        $this->id_etapa2_a = null;
        $this->id_etapa2_b = null;
        $this->id_etapa2_c = null;

        // ESCENARIO A
        $this->e2a_alt_padre = '';
        $this->e2a_titulo = '';
        $this->e2a_problema = '';
        $this->e2a_altA_puntos = '';
        $this->e2a_altA_texto = '';
        $this->e2a_altB_puntos = '';
        $this->e2a_altB_texto = '';
        $this->e2a_altC_puntos = '';
        $this->e2a_altC_texto = '';
        $this->e2a_pregunta_desc = '';
        $this->e2a_pregunta_texto = '';
        $this->e2a_pregunta_clave = '';
        $this->e2a_pregunta_puntos = '';

        // ESCENARIO B
        $this->e2b_alt_padre = '';
        $this->e2b_titulo = '';
        $this->e2b_problema = '';
        $this->e2b_altA_puntos = '';
        $this->e2b_altA_texto = '';
        $this->e2b_altB_puntos = '';
        $this->e2b_altB_texto = '';
        $this->e2b_altC_puntos = '';
        $this->e2b_altC_texto = '';
        $this->e2b_pregunta_desc = '';
        $this->e2b_pregunta_texto = '';
        $this->e2b_pregunta_clave = '';
        $this->e2b_pregunta_puntos = '';

        // ESCENARIO C
        $this->e2c_alt_padre = '';
        $this->e2c_titulo = '';
        $this->e2c_problema = '';
        $this->e2c_altA_puntos = '';
        $this->e2c_altA_texto = '';
        $this->e2c_altB_puntos = '';
        $this->e2c_altB_texto = '';
        $this->e2c_altC_puntos = '';
        $this->e2c_altC_texto = '';
        $this->e2c_pregunta_desc = '';
        $this->e2c_pregunta_texto = '';
        $this->e2c_pregunta_clave = '';
        $this->e2c_pregunta_puntos = '';

        // correctas etapa 2
        $this->e2a_altA_correcta = false;
        $this->e2a_altB_correcta = false;
        $this->e2a_altC_correcta = false;

        $this->e2b_altA_correcta = false;
        $this->e2b_altB_correcta = false;
        $this->e2b_altC_correcta = false;

        $this->e2c_altA_correcta = false;
        $this->e2c_altB_correcta = false;
        $this->e2c_altC_correcta = false;

        $this->initE3();
    }

    public function editar_etapa_dos($idcaso, $idetapa1){
        $this->clear_form_etapa_dos();

        // Aquí marcamos modo edición
        $this->modo_edicion_etapa2 = true;

        $this->id_caso_etapa2 = base64_decode($idcaso);
        $this->id_etapa1 = base64_decode($idetapa1);

        // LISTAR ALTERNATIVAS ETAPA 1 (ACTIVAS)
        $this->listar_alternativas_etapa_uno = DB::table('alternativas')
            ->where('id_etapa', '=', $this->id_etapa1)
            ->where('id_caso', '=', $this->id_caso_etapa2)
            ->where('alternativa_estado', '=', 1)
            ->select('id_alternativa', 'alternativa_letra', 'alternativa_texto', 'alternativa_puntos')
            ->get();

        // Traer todas las etapas 2 de este caso (escenarios)
        $etapas2 = Etapa::where('id_caso', $this->id_caso_etapa2)
            ->where('etapa_numero', 2)
            ->where('etapa_estado', 1)
            ->get();

        foreach ($etapas2 as $etapa2) {
            // Tomamos una alternativa hija para saber el padre
            $altHija = Alternativa::where('id_etapa', $etapa2->id_etapa)
                ->where('alternativa_estado', 1)
                ->first();

            if (!$altHija || !$altHija->id_alternativa_padre) {
                continue;
            }

            $altPadre = Alternativa::find($altHija->id_alternativa_padre);
            if (!$altPadre) {
                continue;
            }

            $letraEscenario = $altPadre->alternativa_letra; // 'A', 'B' o 'C'

            // Todas las alternativas de ese escenario
            $altsEscenario = Alternativa::where('id_etapa', $etapa2->id_etapa)
                ->where('alternativa_estado', 1)
                ->get();

            $altA = $altsEscenario->firstWhere('alternativa_letra', 'A');
            $altB = $altsEscenario->firstWhere('alternativa_letra', 'B');
            $altC = $altsEscenario->firstWhere('alternativa_letra', 'C');

            // Pregunta del escenario
            $pregunta = Pregunta::where('id_etapa', $etapa2->id_etapa)
                ->where('id_caso', $this->id_caso_etapa2)
                ->where('pregunta_estado', 1)
                ->first();

            // Llenamos según la letra del escenario (A/B/C)
            if ($letraEscenario === 'A') {
                $this->id_etapa2_a = $etapa2->id_etapa;
                $this->e2a_alt_padre = $altPadre->id_alternativa;
                $this->e2a_titulo = $etapa2->etapa_titulo;
                $this->e2a_problema = $etapa2->etapa_problema;

                if ($altA) {
                    $this->e2a_altA_puntos = $altA->alternativa_puntos;
                    $this->e2a_altA_texto  = $altA->alternativa_texto;
                    $this->e2a_altA_correcta = ((int)$altA->alternativa_correcta === 1);
                }
                if ($altB) {
                    $this->e2a_altB_puntos = $altB->alternativa_puntos;
                    $this->e2a_altB_texto  = $altB->alternativa_texto;
                    $this->e2a_altB_correcta = ((int)$altB->alternativa_correcta === 1);
                }
                if ($altC) {
                    $this->e2a_altC_puntos = $altC->alternativa_puntos;
                    $this->e2a_altC_texto  = $altC->alternativa_texto;
                    $this->e2a_altC_correcta = ((int)$altC->alternativa_correcta === 1);
                }

                if ($pregunta) {
                    $this->e2a_pregunta_desc = $pregunta->pregunta_descripcion;
                    $this->e2a_pregunta_texto = $pregunta->pregunta_texto;
                    $this->e2a_pregunta_clave = $pregunta->pregunta_palabra_clave;
                    $this->e2a_pregunta_puntos = $pregunta->pregunta_puntos;
                }

            } elseif ($letraEscenario === 'B') {
                $this->id_etapa2_b = $etapa2->id_etapa;
                $this->e2b_alt_padre = $altPadre->id_alternativa;
                $this->e2b_titulo = $etapa2->etapa_titulo;
                $this->e2b_problema = $etapa2->etapa_problema;

                if ($altA) {
                    $this->e2b_altA_puntos = $altA->alternativa_puntos;
                    $this->e2b_altA_texto = $altA->alternativa_texto;
                    $this->e2b_altA_correcta = ((int)$altA->alternativa_correcta === 1);
                }
                if ($altB) {
                    $this->e2b_altB_puntos = $altB->alternativa_puntos;
                    $this->e2b_altB_texto = $altB->alternativa_texto;
                    $this->e2b_altB_correcta = ((int)$altB->alternativa_correcta === 1);
                }
                if ($altC) {
                    $this->e2b_altC_puntos = $altC->alternativa_puntos;
                    $this->e2b_altC_texto = $altC->alternativa_texto;
                    $this->e2b_altC_correcta = ((int)$altC->alternativa_correcta === 1);
                }

                if ($pregunta) {
                    $this->e2b_pregunta_desc = $pregunta->pregunta_descripcion;
                    $this->e2b_pregunta_texto = $pregunta->pregunta_texto;
                    $this->e2b_pregunta_clave = $pregunta->pregunta_palabra_clave;
                    $this->e2b_pregunta_puntos = $pregunta->pregunta_puntos;
                }

            } elseif ($letraEscenario === 'C') {
                $this->id_etapa2_c = $etapa2->id_etapa;
                $this->e2c_alt_padre = $altPadre->id_alternativa;
                $this->e2c_titulo = $etapa2->etapa_titulo;
                $this->e2c_problema = $etapa2->etapa_problema;

                if ($altA) {
                    $this->e2c_altA_puntos = $altA->alternativa_puntos;
                    $this->e2c_altA_texto = $altA->alternativa_texto;
                    $this->e2c_altA_correcta = ((int)$altA->alternativa_correcta === 1);
                }
                if ($altB) {
                    $this->e2c_altB_puntos = $altB->alternativa_puntos;
                    $this->e2c_altB_texto = $altB->alternativa_texto;
                    $this->e2c_altB_correcta = ((int)$altB->alternativa_correcta === 1);
                }
                if ($altC) {
                    $this->e2c_altC_puntos = $altC->alternativa_puntos;
                    $this->e2c_altC_texto = $altC->alternativa_texto;
                    $this->e2c_altC_correcta = ((int)$altC->alternativa_correcta === 1);
                }

                if ($pregunta) {
                    $this->e2c_pregunta_desc = $pregunta->pregunta_descripcion;
                    $this->e2c_pregunta_texto = $pregunta->pregunta_texto;
                    $this->e2c_pregunta_clave = $pregunta->pregunta_palabra_clave;
                    $this->e2c_pregunta_puntos = $pregunta->pregunta_puntos;
                }
            }
        }
        foreach (['A','B','C'] as $esc) {

            $idEtapa2 = ($esc === 'A') ? $this->id_etapa2_a : (($esc === 'B') ? $this->id_etapa2_b : $this->id_etapa2_c);
            if (!$idEtapa2) continue;

            $mapAltEtapa2 = [
                'A' => $this->getAltIdByEtapa($idEtapa2, 'A'),
                'B' => $this->getAltIdByEtapa($idEtapa2, 'B'),
                'C' => $this->getAltIdByEtapa($idEtapa2, 'C'),
            ];

            foreach (['A','B','C'] as $alt2) {

                $idAltPadre = $mapAltEtapa2[$alt2] ?? null;
                if (!$idAltPadre) continue;

                $etapa3 = Etapa::where('id_caso', $this->id_caso_etapa2)
                    ->where('etapa_numero', 3)
                    ->where('id_alternativa_padre', $idAltPadre)
                    ->where('etapa_estado', 1)
                    ->first();

                // Si no existe etapa3, no prendemos enabled
                if (!$etapa3) continue;

                // ✅ AHORA SÍ: esto hará que el switch salga marcado
                $this->e3[$esc][$alt2]['enabled'] = true;
                $this->e3_open[$esc][$alt2] = false;

                $this->e3[$esc][$alt2]['titulo'] = $etapa3->etapa_titulo;
                $this->e3[$esc][$alt2]['problema'] = $etapa3->etapa_problema;

                $alts3 = Alternativa::where('id_etapa', $etapa3->id_etapa)
                    ->where('alternativa_estado', 1)
                    ->get()
                    ->keyBy('alternativa_letra');

                foreach (['A','B','C'] as $l3) {
                    if (!isset($alts3[$l3])) continue;

                    $this->e3[$esc][$alt2]['alts'][$l3]['puntos'] = $alts3[$l3]->alternativa_puntos;
                    $this->e3[$esc][$alt2]['alts'][$l3]['texto']  = $alts3[$l3]->alternativa_texto;
                    $this->e3[$esc][$alt2]['alts'][$l3]['correcta'] = ((int)$alts3[$l3]->alternativa_correcta === 1);
                }

                $preg3 = Pregunta::where('id_etapa', $etapa3->id_etapa)
                    ->where('id_caso', $this->id_caso_etapa2)
                    ->where('pregunta_estado', 1)
                    ->first();

                if ($preg3) {
                    $this->e3[$esc][$alt2]['preg']['desc'] = $preg3->pregunta_descripcion;
                    $this->e3[$esc][$alt2]['preg']['texto'] = $preg3->pregunta_texto;
                    $this->e3[$esc][$alt2]['preg']['clave'] = $preg3->pregunta_palabra_clave;
                    $this->e3[$esc][$alt2]['preg']['puntos'] = $preg3->pregunta_puntos;
                }
            }
        }
    }

    public function crear_etapa_dos($idcaso, $idetapa){
        $this->clear_form_etapa_dos();

        $this->id_caso_etapa2 = base64_decode($idcaso);
        $this->id_etapa1 = base64_decode($idetapa);

        // LISTAR ALTERNATIVAS ETAPA 1 (ACTIVAS)
        $this->listar_alternativas_etapa_uno = DB::table('alternativas')
            ->where('id_etapa', '=', $this->id_etapa1)
            ->where('id_caso', '=', $this->id_caso_etapa2)
            ->where('alternativa_estado', '=', 1)
            ->select('id_alternativa', 'alternativa_letra', 'alternativa_texto', 'alternativa_puntos')
            ->get();
    }

    public function save_etapa_dos(){
        try {
            $this->validate([
                // IDs base
                'id_caso_etapa2' => 'required|integer',
                'id_etapa1'      => 'required|integer',

                // ESCENARIO A
                'e2a_alt_padre'         => 'required|integer',
                'e2a_titulo'            => 'required|string|max:255',
                'e2a_problema'          => 'required|string',
                'e2a_altA_puntos'       => 'required|numeric',
                'e2a_altA_texto'        => 'required|string',
                'e2a_altB_puntos'       => 'required|numeric',
                'e2a_altB_texto'        => 'required|string',
                'e2a_altC_puntos'       => 'required|numeric',
                'e2a_altC_texto'        => 'required|string',
                'e2a_pregunta_desc'     => 'required|string',
                'e2a_pregunta_texto'    => 'required|string',
                'e2a_pregunta_clave'    => 'required|string|max:255',
                'e2a_pregunta_puntos'   => 'required|numeric',

                // ESCENARIO B
                'e2b_alt_padre'         => 'required|integer|different:e2a_alt_padre',
                'e2b_titulo'            => 'required|string|max:255',
                'e2b_problema'          => 'required|string',
                'e2b_altA_puntos'       => 'required|numeric',
                'e2b_altA_texto'        => 'required|string',
                'e2b_altB_puntos'       => 'required|numeric',
                'e2b_altB_texto'        => 'required|string',
                'e2b_altC_puntos'       => 'required|numeric',
                'e2b_altC_texto'        => 'required|string',
                'e2b_pregunta_desc'     => 'required|string',
                'e2b_pregunta_texto'    => 'required|string',
                'e2b_pregunta_clave'    => 'required|string|max:255',
                'e2b_pregunta_puntos'   => 'required|numeric',

                // ESCENARIO C
                'e2c_alt_padre'         => 'required|integer|different:e2a_alt_padre|different:e2b_alt_padre',
                'e2c_titulo'            => 'required|string|max:255',
                'e2c_problema'          => 'required|string',
                'e2c_altA_puntos'       => 'required|numeric',
                'e2c_altA_texto'        => 'required|string',
                'e2c_altB_puntos'       => 'required|numeric',
                'e2c_altB_texto'        => 'required|string',
                'e2c_altC_puntos'       => 'required|numeric',
                'e2c_altC_texto'        => 'required|string',
                'e2c_pregunta_desc'     => 'required|string',
                'e2c_pregunta_texto'    => 'required|string',
                'e2c_pregunta_clave'    => 'required|string|max:255',
                'e2c_pregunta_puntos'   => 'required|numeric',
            ], [
                // IDs base
                'id_caso_etapa2.required' => 'No se encontró el caso para la Etapa 2.',
                'id_caso_etapa2.integer'  => 'El identificador del caso debe ser un número entero.',
                'id_etapa1.required'      => 'No se encontró la Etapa 1 asociada.',
                'id_etapa1.integer'       => 'El identificador de la Etapa 1 debe ser un número entero.',

                // ESCENARIO A
                'e2a_alt_padre.required'  => 'Debe seleccionar una alternativa padre para el Escenario A.',
                'e2a_alt_padre.integer'   => 'La alternativa padre del Escenario A debe ser un número válido.',
                'e2a_titulo.required'     => 'El título de la Etapa del Escenario A es obligatorio.',
                'e2a_titulo.string'       => 'El título de la Etapa del Escenario A debe ser texto.',
                'e2a_titulo.max'          => 'El título de la Etapa del Escenario A no debe superar los 255 caracteres.',
                'e2a_problema.required'   => 'El problema del Escenario A es obligatorio.',
                'e2a_problema.string'     => 'El problema del Escenario A debe ser texto.',
                'e2a_altA_puntos.required'=> 'Los puntos de la alternativa A del Escenario A son obligatorios.',
                'e2a_altA_puntos.numeric' => 'Los puntos de la alternativa A del Escenario A deben ser numéricos.',
                'e2a_altA_texto.required' => 'El texto de la alternativa A del Escenario A es obligatorio.',
                'e2a_altA_texto.string'   => 'El texto de la alternativa A del Escenario A debe ser texto.',
                'e2a_altB_puntos.required'=> 'Los puntos de la alternativa B del Escenario A son obligatorios.',
                'e2a_altB_puntos.numeric' => 'Los puntos de la alternativa B del Escenario A deben ser numéricos.',
                'e2a_altB_texto.required' => 'El texto de la alternativa B del Escenario A es obligatorio.',
                'e2a_altB_texto.string'   => 'El texto de la alternativa B del Escenario A debe ser texto.',
                'e2a_altC_puntos.required'=> 'Los puntos de la alternativa C del Escenario A son obligatorios.',
                'e2a_altC_puntos.numeric' => 'Los puntos de la alternativa C del Escenario A deben ser numéricos.',
                'e2a_altC_texto.required' => 'El texto de la alternativa C del Escenario A es obligatorio.',
                'e2a_altC_texto.string'   => 'El texto de la alternativa C del Escenario A debe ser texto.',
                'e2a_pregunta_desc.required'   => 'La descripción de la pregunta doctrinal del Escenario A es obligatoria.',
                'e2a_pregunta_desc.string'     => 'La descripción de la pregunta doctrinal del Escenario A debe ser texto.',
                'e2a_pregunta_texto.required'  => 'El texto de la pregunta doctrinal del Escenario A es obligatorio.',
                'e2a_pregunta_texto.string'    => 'El texto de la pregunta doctrinal del Escenario A debe ser texto.',
                'e2a_pregunta_clave.required'  => 'La palabra clave de la pregunta del Escenario A es obligatoria.',
                'e2a_pregunta_clave.string'    => 'La palabra clave de la pregunta del Escenario A debe ser texto.',
                'e2a_pregunta_clave.max'       => 'La palabra clave de la pregunta del Escenario A no debe superar los 255 caracteres.',
                'e2a_pregunta_puntos.required' => 'Los puntos de la pregunta doctrinal del Escenario A son obligatorios.',
                'e2a_pregunta_puntos.numeric'  => 'Los puntos de la pregunta doctrinal del Escenario A deben ser numéricos.',

                // ESCENARIO B
                'e2b_alt_padre.required'  => 'Debe seleccionar una alternativa padre para el Escenario B.',
                'e2b_alt_padre.integer'   => 'La alternativa padre del Escenario B debe ser un número válido.',
                'e2b_alt_padre.different' => 'La alternativa padre del Escenario B debe ser distinta a la seleccionada en el Escenario A.',
                'e2b_titulo.required'     => 'El título de la Etapa del Escenario B es obligatorio.',
                'e2b_titulo.string'       => 'El título de la Etapa del Escenario B debe ser texto.',
                'e2b_titulo.max'          => 'El título de la Etapa del Escenario B no debe superar los 255 caracteres.',
                'e2b_problema.required'   => 'El problema del Escenario B es obligatorio.',
                'e2b_problema.string'     => 'El problema del Escenario B debe ser texto.',
                'e2b_altA_puntos.required'=> 'Los puntos de la alternativa A del Escenario B son obligatorios.',
                'e2b_altA_puntos.numeric' => 'Los puntos de la alternativa A del Escenario B deben ser numéricos.',
                'e2b_altA_texto.required' => 'El texto de la alternativa A del Escenario B es obligatorio.',
                'e2b_altA_texto.string'   => 'El texto de la alternativa A del Escenario B debe ser texto.',
                'e2b_altB_puntos.required'=> 'Los puntos de la alternativa B del Escenario B son obligatorios.',
                'e2b_altB_puntos.numeric' => 'Los puntos de la alternativa B del Escenario B deben ser numéricos.',
                'e2b_altB_texto.required' => 'El texto de la alternativa B del Escenario B es obligatorio.',
                'e2b_altB_texto.string'   => 'El texto de la alternativa B del Escenario B debe ser texto.',
                'e2b_altC_puntos.required'=> 'Los puntos de la alternativa C del Escenario B son obligatorios.',
                'e2b_altC_puntos.numeric' => 'Los puntos de la alternativa C del Escenario B deben ser numéricos.',
                'e2b_altC_texto.required' => 'El texto de la alternativa C del Escenario B es obligatorio.',
                'e2b_altC_texto.string'   => 'El texto de la alternativa C del Escenario B debe ser texto.',
                'e2b_pregunta_desc.required'   => 'La descripción de la pregunta doctrinal del Escenario B es obligatoria.',
                'e2b_pregunta_desc.string'     => 'La descripción de la pregunta doctrinal del Escenario B debe ser texto.',
                'e2b_pregunta_texto.required'  => 'El texto de la pregunta doctrinal del Escenario B es obligatorio.',
                'e2b_pregunta_texto.string'    => 'El texto de la pregunta doctrinal del Escenario B debe ser texto.',
                'e2b_pregunta_clave.required'  => 'La palabra clave de la pregunta del Escenario B es obligatoria.',
                'e2b_pregunta_clave.string'    => 'La palabra clave de la pregunta del Escenario B debe ser texto.',
                'e2b_pregunta_clave.max'       => 'La palabra clave de la pregunta del Escenario B no debe superar los 255 caracteres.',
                'e2b_pregunta_puntos.required' => 'Los puntos de la pregunta doctrinal del Escenario B son obligatorios.',
                'e2b_pregunta_puntos.numeric'  => 'Los puntos de la pregunta doctrinal del Escenario B deben ser numéricos.',

                // ESCENARIO C
                'e2c_alt_padre.required'  => 'Debe seleccionar una alternativa padre para el Escenario C.',
                'e2c_alt_padre.integer'   => 'La alternativa padre del Escenario C debe ser un número válido.',
                'e2c_alt_padre.different' => 'La alternativa padre del Escenario C debe ser distinta a las seleccionadas en los Escenarios A y B.',
                'e2c_titulo.required'     => 'El título de la Etapa del Escenario C es obligatorio.',
                'e2c_titulo.string'       => 'El título de la Etapa del Escenario C debe ser texto.',
                'e2c_titulo.max'          => 'El título de la Etapa del Escenario C no debe superar los 255 caracteres.',
                'e2c_problema.required'   => 'El problema del Escenario C es obligatorio.',
                'e2c_problema.string'     => 'El problema del Escenario C debe ser texto.',
                'e2c_altA_puntos.required'=> 'Los puntos de la alternativa A del Escenario C son obligatorios.',
                'e2c_altA_puntos.numeric' => 'Los puntos de la alternativa A del Escenario C deben ser numéricos.',
                'e2c_altA_texto.required' => 'El texto de la alternativa A del Escenario C es obligatorio.',
                'e2c_altA_texto.string'   => 'El texto de la alternativa A del Escenario C debe ser texto.',
                'e2c_altB_puntos.required'=> 'Los puntos de la alternativa B del Escenario C son obligatorios.',
                'e2c_altB_puntos.numeric' => 'Los puntos de la alternativa B del Escenario C deben ser numéricos.',
                'e2c_altB_texto.required' => 'El texto de la alternativa B del Escenario C es obligatorio.',
                'e2c_altB_texto.string'   => 'El texto de la alternativa B del Escenario C debe ser texto.',
                'e2c_altC_puntos.required'=> 'Los puntos de la alternativa C del Escenario C son obligatorios.',
                'e2c_altC_puntos.numeric' => 'Los puntos de la alternativa C del Escenario C deben ser numéricos.',
                'e2c_altC_texto.required' => 'El texto de la alternativa C del Escenario C es obligatorio.',
                'e2c_altC_texto.string'   => 'El texto de la alternativa C del Escenario C debe ser texto.',
                'e2c_pregunta_desc.required'   => 'La descripción de la pregunta doctrinal del Escenario C es obligatoria.',
                'e2c_pregunta_desc.string'     => 'La descripción de la pregunta doctrinal del Escenario C debe ser texto.',
                'e2c_pregunta_texto.required'  => 'El texto de la pregunta doctrinal del Escenario C es obligatorio.',
                'e2c_pregunta_texto.string'    => 'El texto de la pregunta doctrinal del Escenario C debe ser texto.',
                'e2c_pregunta_clave.required'  => 'La palabra clave de la pregunta del Escenario C es obligatoria.',
                'e2c_pregunta_clave.string'    => 'La palabra clave de la pregunta del Escenario C debe ser texto.',
                'e2c_pregunta_clave.max'       => 'La palabra clave de la pregunta del Escenario C no debe superar los 255 caracteres.',
                'e2c_pregunta_puntos.required' => 'Los puntos de la pregunta doctrinal del Escenario C son obligatorios.',
                'e2c_pregunta_puntos.numeric'  => 'Los puntos de la pregunta doctrinal del Escenario C deben ser numéricos.',
            ]);

            DB::beginTransaction();
            $microtime = microtime(true);
            $idUser    = Auth::id();

            // ===== validar 1 correcta por escenario Etapa 2 =====
            $cntA = (int)$this->e2a_altA_correcta + (int)$this->e2a_altB_correcta + (int)$this->e2a_altC_correcta;
            $cntB = (int)$this->e2b_altA_correcta + (int)$this->e2b_altB_correcta + (int)$this->e2b_altC_correcta;
            $cntC = (int)$this->e2c_altA_correcta + (int)$this->e2c_altB_correcta + (int)$this->e2c_altC_correcta;

            if ($cntA !== 1 || $cntB !== 1 || $cntC !== 1) {
                session()->flash('error_etapa_dos', 'Debe seleccionar exactamente 1 alternativa correcta por cada escenario (A, B y C).');
                return;
            }

            // ===== validar 1 correcta por cada rama Etapa 3 enabled =====
            foreach (['A','B','C'] as $esc) {
                foreach (['A','B','C'] as $alt2) {
                    if (empty($this->e3[$esc][$alt2]['enabled'])) continue;

                    $c = 0;
                    foreach (['A','B','C'] as $l3) {
                        if (!empty($this->e3[$esc][$alt2]['alts'][$l3]['correcta'])) $c++;
                    }

                    if ($c !== 1) {
                        session()->flash('error_etapa_dos', "En Etapa 3 ($esc-$alt2) debe seleccionar exactamente 1 alternativa correcta.");
                        return;
                    }
                }
            }


            $guardarEscenario = function ($letraEscenario) use ($idUser, $microtime) {
                $idCaso = $this->id_caso_etapa2;

                // 1) Tomar datos según escenario
                if ($letraEscenario === 'A') {
                    $altPadre    = $this->e2a_alt_padre;
                    $titulo      = $this->e2a_titulo;
                    $problema    = $this->e2a_problema;
                    $altA_puntos = $this->e2a_altA_puntos;
                    $altA_texto  = $this->e2a_altA_texto;
                    $altB_puntos = $this->e2a_altB_puntos;
                    $altB_texto  = $this->e2a_altB_texto;
                    $altC_puntos = $this->e2a_altC_puntos;
                    $altC_texto  = $this->e2a_altC_texto;
                    $preg_desc   = $this->e2a_pregunta_desc;
                    $preg_texto  = $this->e2a_pregunta_texto;
                    $preg_clave  = $this->e2a_pregunta_clave;
                    $preg_puntos = $this->e2a_pregunta_puntos;
                    $idEtapa2    = $this->id_etapa2_a;
                } elseif ($letraEscenario === 'B') {
                    $altPadre    = $this->e2b_alt_padre;
                    $titulo      = $this->e2b_titulo;
                    $problema    = $this->e2b_problema;
                    $altA_puntos = $this->e2b_altA_puntos;
                    $altA_texto  = $this->e2b_altA_texto;
                    $altB_puntos = $this->e2b_altB_puntos;
                    $altB_texto  = $this->e2b_altB_texto;
                    $altC_puntos = $this->e2b_altC_puntos;
                    $altC_texto  = $this->e2b_altC_texto;
                    $preg_desc   = $this->e2b_pregunta_desc;
                    $preg_texto  = $this->e2b_pregunta_texto;
                    $preg_clave  = $this->e2b_pregunta_clave;
                    $preg_puntos = $this->e2b_pregunta_puntos;
                    $idEtapa2    = $this->id_etapa2_b;
                } else { // C
                    $altPadre    = $this->e2c_alt_padre;
                    $titulo      = $this->e2c_titulo;
                    $problema    = $this->e2c_problema;
                    $altA_puntos = $this->e2c_altA_puntos;
                    $altA_texto  = $this->e2c_altA_texto;
                    $altB_puntos = $this->e2c_altB_puntos;
                    $altB_texto  = $this->e2c_altB_texto;
                    $altC_puntos = $this->e2c_altC_puntos;
                    $altC_texto  = $this->e2c_altC_texto;
                    $preg_desc   = $this->e2c_pregunta_desc;
                    $preg_texto  = $this->e2c_pregunta_texto;
                    $preg_clave  = $this->e2c_pregunta_clave;
                    $preg_puntos = $this->e2c_pregunta_puntos;
                    $idEtapa2    = $this->id_etapa2_c;
                }

                // 2) CREAR o ACTUALIZAR ETAPA
                if ($idEtapa2) {
                    // EDITAR
                    $etapa = Etapa::where('id_etapa', $idEtapa2)
                        ->where('id_caso', $idCaso)
                        ->where('etapa_numero', 2)
                        ->first();

                    if (!$etapa) {
                        DB::rollBack();
                        session()->flash('error_etapa_dos', 'No se encontró la Etapa 2 del escenario ' . $letraEscenario . ' para actualizar.');
                        return;
                    }

                    $etapa->etapa_titulo   = $titulo;
                    $etapa->etapa_problema = $problema;
                    // si quieres, puedes NO tocar el microtime en edición
                    if (!$etapa->save()) {
                        DB::rollBack();
                        session()->flash('error_etapa_dos', 'No se pudo actualizar la Etapa 2 del escenario ' . $letraEscenario);
                        return;
                    }

                } else {
                    // CREAR
                    $etapa = new Etapa();
                    $etapa->id_users             = $idUser;
                    $etapa->id_caso              = $idCaso;
                    $etapa->id_alternativa_padre = $altPadre;
                    $etapa->etapa_numero         = 2;
                    $etapa->etapa_titulo         = $titulo;
                    $etapa->etapa_problema       = $problema;
                    $etapa->etapa_microtime      = $microtime;
                    $etapa->etapa_estado         = 1;

                    if (!$etapa->save()) {
                        DB::rollBack();
                        session()->flash('error_etapa_dos', 'No se pudo guardar la etapa de escenario ' . $letraEscenario);
                        return;
                    }

                    // guardamos el id en la propiedad para futuras ediciones (en la misma request al menos)
                    if ($letraEscenario === 'A') {
                        $this->id_etapa2_a = $etapa->id_etapa;
                    } elseif ($letraEscenario === 'B') {
                        $this->id_etapa2_b = $etapa->id_etapa;
                    } else {
                        $this->id_etapa2_c = $etapa->id_etapa;
                    }
                }

                // 3) ALTERNATIVAS DEL ESCENARIO (A/B/C)
                $alternativasEsc = [
                    ['letra' => 'A', 'puntos' => $altA_puntos, 'texto' => $altA_texto, 'correcta' => (
                    $letraEscenario === 'A' ? $this->e2a_altA_correcta :
                        ($letraEscenario === 'B' ? $this->e2b_altA_correcta : $this->e2c_altA_correcta)
                    )],
                    ['letra' => 'B', 'puntos' => $altB_puntos, 'texto' => $altB_texto, 'correcta' => (
                    $letraEscenario === 'A' ? $this->e2a_altB_correcta :
                        ($letraEscenario === 'B' ? $this->e2b_altB_correcta : $this->e2c_altB_correcta)
                    )],
                    ['letra' => 'C', 'puntos' => $altC_puntos, 'texto' => $altC_texto, 'correcta' => (
                    $letraEscenario === 'A' ? $this->e2a_altC_correcta :
                        ($letraEscenario === 'B' ? $this->e2b_altC_correcta : $this->e2c_altC_correcta)
                    )],
                ];

                foreach ($alternativasEsc as $altData) {
                    $alt = Alternativa::where('id_etapa', $etapa->id_etapa)
                        ->where('id_caso', $idCaso)
                        ->where('alternativa_letra', $altData['letra'])
                        ->first();

                    if (!$alt) {
                        // crear
                        $alt = new Alternativa();
                        $alt->id_users = $idUser;
                        $alt->id_etapa = $etapa->id_etapa;
                        $alt->id_caso  = $idCaso;
                    }

                    $alt->id_alternativa_padre  = $altPadre;
                    $alt->alternativa_letra     = $altData['letra'];
                    $alt->alternativa_texto     = $altData['texto'];
                    $alt->alternativa_puntos    = $altData['puntos'];
                    $alt->alternativa_correcta = !empty($altData['correcta']) ? 1 : 0;
                    if (!$alt->exists) {
                        $alt->alternativa_microtime = $microtime;
                        $alt->alternativa_estado    = 1;
                    }

                    if (!$alt->save()) {
                        DB::rollBack();
                        session()->flash('error_etapa_dos', 'No se pudo guardar una alternativa del escenario ' . $letraEscenario);
                        return;
                    }
                }

                // 4) PREGUNTA DEL ESCENARIO
                $pregunta = Pregunta::where('id_etapa', $etapa->id_etapa)
                    ->where('id_caso', $idCaso)
                    ->first();

                if (!$pregunta) {
                    $pregunta = new Pregunta();
                    $pregunta->id_users = $idUser;
                    $pregunta->id_etapa = $etapa->id_etapa;
                    $pregunta->id_caso  = $idCaso;
                    $pregunta->pregunta_microtime = $microtime;
                    $pregunta->pregunta_estado    = 1;
                }

                $pregunta->pregunta_texto        = $preg_texto;
                $pregunta->pregunta_descripcion  = $preg_desc;
                $pregunta->pregunta_palabra_clave= $preg_clave;
                $pregunta->pregunta_puntos       = $preg_puntos;

                if (!$pregunta->save()) {
                    DB::rollBack(); session()->flash('error_etapa_dos', 'No se pudo guardar la pregunta del escenario ' . $letraEscenario); return;
                }
            };

            // Guardar/actualizar los 3 escenarios
            $guardarEscenario('A');
            $guardarEscenario('B');
            $guardarEscenario('C');

            // =========================
            // GUARDAR ETAPA 3 (9 RAMAS)
            // =========================

            // 1) mapa de IDs reales de alternativas etapa2
            $mapAltPadre = [
                'A' => [
                    'A' => $this->getAltIdByEtapa($this->id_etapa2_a, 'A'),
                    'B' => $this->getAltIdByEtapa($this->id_etapa2_a, 'B'),
                    'C' => $this->getAltIdByEtapa($this->id_etapa2_a, 'C'),
                ],
                'B' => [
                    'A' => $this->getAltIdByEtapa($this->id_etapa2_b, 'A'),
                    'B' => $this->getAltIdByEtapa($this->id_etapa2_b, 'B'),
                    'C' => $this->getAltIdByEtapa($this->id_etapa2_b, 'C'),
                ],
                'C' => [
                    'A' => $this->getAltIdByEtapa($this->id_etapa2_c, 'A'),
                    'B' => $this->getAltIdByEtapa($this->id_etapa2_c, 'B'),
                    'C' => $this->getAltIdByEtapa($this->id_etapa2_c, 'C'),
                ],
            ];

            foreach (['A','B','C'] as $esc) {
                foreach (['A','B','C'] as $alt2) {

                    if (empty($this->e3[$esc][$alt2]['enabled'])) {
                        continue;
                    }

                    $idAltPadre = $mapAltPadre[$esc][$alt2] ?? null;
                    if (!$idAltPadre) {
                        DB::rollBack();
                        session()->flash('error_etapa_dos', "No se encontró alternativa padre Etapa2 ($esc-$alt2).");
                        return;
                    }

                    // 2) crear o actualizar Etapa 3 para esa alternativa padre
                    $etapa3 = Etapa::where('id_caso', $this->id_caso_etapa2)
                        ->where('etapa_numero', 3)
                        ->where('id_alternativa_padre', $idAltPadre)
                        ->where('etapa_estado', 1)
                        ->first();

                    if (!$etapa3) {
                        $etapa3 = new Etapa();
                        $etapa3->id_users = Auth::id();
                        $etapa3->id_caso = $this->id_caso_etapa2;
                        $etapa3->etapa_numero = 3;
                        $etapa3->id_alternativa_padre = $idAltPadre;
                        $etapa3->etapa_microtime = $microtime;
                        $etapa3->etapa_estado = 1;
                    }

                    $etapa3->etapa_titulo = $this->e3[$esc][$alt2]['titulo'];
                    $etapa3->etapa_problema = $this->e3[$esc][$alt2]['problema'];

                    if (!$etapa3->save()) {
                        DB::rollBack();
                        session()->flash('error_etapa_dos', "No se pudo guardar Etapa 3 ($esc-$alt2).");
                        return;
                    }

                    // 3) alternativas etapa 3
                    foreach (['A','B','C'] as $l3) {
                        $alt3 = Alternativa::where('id_etapa', $etapa3->id_etapa)
                            ->where('alternativa_letra', $l3)
                            ->first();

                        if (!$alt3) {
                            $alt3 = new Alternativa();
                            $alt3->id_users = Auth::id();
                            $alt3->id_caso = $this->id_caso_etapa2;
                            $alt3->id_etapa = $etapa3->id_etapa;
                            $alt3->alternativa_microtime = $microtime;
                            $alt3->alternativa_estado = 1;
                        }

                        $alt3->id_alternativa_padre = $idAltPadre;
                        $alt3->alternativa_letra = $l3;
                        $alt3->alternativa_texto = $this->e3[$esc][$alt2]['alts'][$l3]['texto'];
                        $alt3->alternativa_puntos = $this->e3[$esc][$alt2]['alts'][$l3]['puntos'];
                        $alt3->alternativa_correcta = !empty($this->e3[$esc][$alt2]['alts'][$l3]['correcta']) ? 1 : 0;

                        if (!$alt3->save()) {
                            DB::rollBack();
                            session()->flash('error_etapa_dos', "No se pudo guardar alternativa Etapa 3 ($esc-$alt2-$l3).");
                            return;
                        }
                    }

                    // 4) pregunta etapa 3
                    $preg3 = Pregunta::where('id_etapa', $etapa3->id_etapa)
                        ->where('id_caso', $this->id_caso_etapa2)
                        ->first();

                    if (!$preg3) {
                        $preg3 = new Pregunta();
                        $preg3->id_users = Auth::id();
                        $preg3->id_etapa = $etapa3->id_etapa;
                        $preg3->id_caso = $this->id_caso_etapa2;
                        $preg3->pregunta_microtime = $microtime;
                        $preg3->pregunta_estado = 1;
                    }

                    $preg3->pregunta_descripcion = $this->e3[$esc][$alt2]['preg']['desc'];
                    $preg3->pregunta_texto = $this->e3[$esc][$alt2]['preg']['texto'];
                    $preg3->pregunta_palabra_clave = $this->e3[$esc][$alt2]['preg']['clave'];
                    $preg3->pregunta_puntos = $this->e3[$esc][$alt2]['preg']['puntos'];

                    if (!$preg3->save()) {
                        DB::rollBack();
                        session()->flash('error_etapa_dos', "No se pudo guardar pregunta Etapa 3 ($esc-$alt2).");
                        return;
                    }
                }
            }


            DB::commit();

            $this->dispatch('hide_modal_etapa_dos');
            session()->flash('success', 'Etapa 2 guardada correctamente con los 3 escenarios.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->setErrorBag($e->validator->errors());
        } catch (\Exception $e) {
            DB::rollBack();
            $this->logs->insertarLog($e);
            session()->flash('error_etapa_dos', 'Ocurrió un error al guardar la Etapa 2. Por favor, inténtelo nuevamente.');
        }
    }

    // ANALISIS
    public $id_analisis = '';
    public $analisis_contenido = '';
    public $conceptos = [];
    public $frases = [];

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
