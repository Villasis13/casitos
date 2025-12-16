<?php

namespace App\Livewire\Portalusuarios;

use App\Models\Logs;
use App\Models\Caso;
use App\Models\Etapa;
use App\Models\Alternativa;
use App\Models\Pregunta;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

class Iniciarcasos extends Component
{
    use WithPagination, WithoutUrlPagination;

    private $logs;

    public function __construct()
    {
        $this->logs = new Logs();
    }

    // Datos del caso
    public $id_caso = "";

    // Control de tabs
    public $tab_actual = 1;

    // Alternativas seleccionadas
    public $alternativa_seleccionada_1 = null;
    public $alternativa_seleccionada_2 = null;

    // Respuestas de preguntas
    public $respuesta_1 = "";
    public $respuesta_2 = "";

    // Control de guardado (para mostrar en el avance)
    public $alternativa_1_guardada = false;
    public $alternativa_1_letra = "";
    public $alternativa_1_puntos = 0;

    public $respuesta_1_guardada = false;
    public $respuesta_1_correcta = false;
    public $pregunta_1_puntos = 0;

    public $alternativa_2_guardada = false;
    public $alternativa_2_letra = "";
    public $alternativa_2_puntos = 0;

    public $respuesta_2_guardada = false;
    public $respuesta_2_correcta = false;
    public $pregunta_2_puntos = 0;

    // Estadísticas
    public $puntos_totales = 0;
    public $total_respuestas = 0;
    public $alternativas_completadas = 0;

    public function mount($id_caso){
        $this->id_caso = $id_caso;
    }

    public function render(){
        return view('livewire.portalusuarios.iniciarcasos');
    }

    /**
     * Confirmar selección de alternativa 1 (Etapa 1)
     */
    public function confirmarAlternativa1(){
        try {
            if (!$this->alternativa_seleccionada_1) {
                return;
            }

            // Obtener datos de la alternativa seleccionada
            $alternativa = DB::table('alternativas')
                ->where('id_alternativa', $this->alternativa_seleccionada_1)
                ->first();

            if ($alternativa) {
                // Guardar información para el avance
                $this->alternativa_1_guardada = true;
                $this->alternativa_1_letra = $alternativa->alternativa_letra;
                $this->alternativa_1_puntos = $alternativa->alternativa_puntos;

                // Actualizar estadísticas
                $this->puntos_totales += $alternativa->alternativa_puntos;
                $this->alternativas_completadas++;

                // Avanzar al siguiente tab
                $this->tab_actual = 2;
            }

        } catch (\Exception $e) {
            $this->logs->insertarLog($e);
            session()->flash('error', 'Ocurrió un error al confirmar la alternativa.');
        }
    }

    /**
     * Enviar respuesta de pregunta 1 (Etapa 1)
     */
    public function enviarRespuesta1(){
        try {
            if (!$this->respuesta_1) {
                return;
            }

            // Obtener información de la etapa 1
            $etapa_1 = DB::table('etapas')
                ->where('id_caso', $this->id_caso)
                ->where('etapa_numero', 1)
                ->where('etapa_estado', 1)
                ->first();

            if (!$etapa_1) {
                return;
            }

            // Obtener pregunta de etapa 1
            $pregunta = DB::table('preguntas')
                ->where('id_caso', $this->id_caso)
                ->where('id_etapa', $etapa_1->id_etapa)
                ->where('pregunta_estado', 1)
                ->first();

            if ($pregunta) {
                // Verificar si la respuesta contiene la palabra clave
                $respuesta_lower = strtolower($this->respuesta_1);
                $palabra_clave_lower = strtolower($pregunta->pregunta_palabra_clave);

                $this->respuesta_1_correcta = strpos($respuesta_lower, $palabra_clave_lower) !== false;

                // Asignar puntos solo si la respuesta es correcta
                if ($this->respuesta_1_correcta) {
                    $this->pregunta_1_puntos = $pregunta->pregunta_puntos;
                    $this->puntos_totales += $pregunta->pregunta_puntos;
                } else {
                    $this->pregunta_1_puntos = 0;
                }

                // Guardar información para el avance
                $this->respuesta_1_guardada = true;
                $this->total_respuestas++;

                // Avanzar al siguiente tab
                $this->tab_actual = 3;
            }

        } catch (\Exception $e) {
            $this->logs->insertarLog($e);
            session()->flash('error', 'Ocurrió un error al enviar la respuesta.');
        }
    }

    /**
     * Confirmar selección de alternativa 2 (Etapa 2)
     */
    public function confirmarAlternativa2(){
        try {
            if (!$this->alternativa_seleccionada_2) {
                return;
            }

            // Obtener datos de la alternativa seleccionada
            $alternativa = DB::table('alternativas')
                ->where('id_alternativa_padre', $this->alternativa_seleccionada_2)
                ->first();

            if ($alternativa) {
                // Guardar información para el avance
                $this->alternativa_2_guardada = true;
                $this->alternativa_2_letra = $alternativa->alternativa_letra;
                $this->alternativa_2_puntos = $alternativa->alternativa_puntos;

                // Actualizar estadísticas
                $this->puntos_totales += $alternativa->alternativa_puntos;
                $this->alternativas_completadas++;

                // Avanzar al siguiente tab
                $this->tab_actual = 4;
            }

        } catch (\Exception $e) {
            $this->logs->insertarLog($e);
            session()->flash('error', 'Ocurrió un error al confirmar la alternativa.');
        }
    }

    /**
     * Enviar respuesta de pregunta 2 (Etapa 2)
     */
    public function enviarRespuesta2(){
        try {
            if (!$this->respuesta_2) {
                return;
            }

            // Obtener información de la etapa 2
            $etapa_2 = DB::table('etapas')
                ->where('id_caso', $this->id_caso)
                ->where('id_alternativa_padre', $this->alternativa_seleccionada_1)
                ->where('etapa_numero', 2)
                ->where('etapa_estado', 1)
                ->first();

            if (!$etapa_2) {
                return;
            }

            // Obtener pregunta de etapa 2
            $pregunta = DB::table('preguntas')
                ->where('id_caso', $this->id_caso)
                ->where('id_etapa', $etapa_2->id_etapa)
                ->where('pregunta_estado', 1)
                ->first();

            if ($pregunta) {
                // Verificar si la respuesta contiene la palabra clave
                $respuesta_lower = strtolower($this->respuesta_2);
                $palabra_clave_lower = strtolower($pregunta->pregunta_palabra_clave);

                $this->respuesta_2_correcta = strpos($respuesta_lower, $palabra_clave_lower) !== false;

                // Asignar puntos solo si la respuesta es correcta
                if ($this->respuesta_2_correcta) {
                    $this->pregunta_2_puntos = $pregunta->pregunta_puntos;
                    $this->puntos_totales += $pregunta->pregunta_puntos;
                } else {
                    $this->pregunta_2_puntos = 0;
                }

                // Guardar información para el avance
                $this->respuesta_2_guardada = true;
                $this->total_respuestas++;

                // Aquí podrías mostrar un modal de finalización o redirigir
                session()->flash('success', '¡Caso completado! Tu puntuación final es: ' . $this->puntos_totales . ' puntos');
            }

        } catch (\Exception $e) {
            $this->logs->insertarLog($e);
            session()->flash('error', 'Ocurrió un error al enviar la respuesta.');
        }
    }
}
