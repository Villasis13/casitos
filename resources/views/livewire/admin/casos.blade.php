<div>

    <style>
        .etapa-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }
        .etapa-1 { background: #e3f2fd; color: #1976d2; }
        .etapa-2 { background: #fff3e0; color: #f57c00; }
        .etapa-3 { background: #e8f5e9; color: #388e3c; }

        .alternativa-box {
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            background: #f8f9fa;
        }

        .letra-badge {
            display: inline-block;
            width: 35px;
            height: 35px;
            background: #1976d2;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 35px;
            font-weight: bold;
            margin-right: 10px;
        }

        .pregunta-section {
            background: #e8f5e9;
            border-left: 4px solid #4caf50;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }

        .selector-alternativa {
            border: 2px solid #2196f3;
            background: #e3f2fd;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>

    {{--    MODAL CREAR / EDITAR REGISTRO--}}
    <x-modal-general  wire:ignore.self >
        <x-slot name="id_modal">modal_casos</x-slot>
        <x-slot name="titleModal">Gestionar Caso</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_casos">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @if (session()->has('error_modal'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_modal') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <!-- CONTEXTO -->
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Título <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="caso_titulo" wire:model="caso_titulo">
                            @error('caso_titulo') <span class="message-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Objetivo <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="caso_objetivo" wire:model="caso_objetivo">
                            @error('caso_objetivo') <span class="message-error">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Contexto <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="caso_contexto" wire:model="caso_contexto" rows="4" maxlength="2000"></textarea>
                            <small class="text-muted float-end"><span id="contador">0</span>/2000</small>
                            @error('caso_contexto') <span class="message-error">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- ETAPA 1 (PRIMERA ETAPA) -->
                    <div class="border-top pt-3 mt-3">
                        <h6 class="text-primary"><i class="fas fa-layer-group"></i> Etapa 1 - Primera Interacción</h6>

                        <div class="mb-3">
                            <label class="form-label">Título de Etapa <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="etapa1_titulo" wire:model="etapa1_titulo" placeholder="Ej: Análisis inicial">
                            @error('etapa1_titulo') <span class="message-error">{{ $message }}</span> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Problema Inicial <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="etapa1_problema" wire:model="etapa1_problema" rows="3" placeholder="Describe el problema que enfrentará..."></textarea>
                            @error('etapa1_problema') <span class="message-error">{{ $message }}</span> @enderror
                        </div>

                        <!-- ALTERNATIVAS ETAPA 1 -->
                        <div class="mb-3">
                            <label class="form-label fw-bold">Alternativas (3)</label>

                            <div class="alternativa-box">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="letra-badge">A</span>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="alt1a_puntos" wire:model="alt1a_puntos" placeholder="Puntos" style="width: 100px;">

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="alt1a_correcta" wire:model.live="alt1a_correcta" wire:change="marcarAlternativaCorrecta('A')">
                                                <label class="form-check-label" for="alt1a_correcta">Correcta</label>
                                            </div>
                                        </div>
                                        <textarea class="form-control" id="alt1a_texto" wire:model="alt1a_texto" rows="2" placeholder="Texto alternativa A..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="alternativa-box">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="letra-badge">B</span>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="alt1b_puntos" wire:model="alt1b_puntos" placeholder="Puntos" style="width: 100px;">

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="alt1b_correcta" wire:model.live="alt1b_correcta" wire:change="marcarAlternativaCorrecta('B')">
                                                <label class="form-check-label" for="alt1b_correcta">Correcta</label>
                                            </div>
                                        </div>
                                        <textarea class="form-control" id="alt1b_texto" wire:model="alt1b_texto" rows="2" placeholder="Texto alternativa B..."></textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="alternativa-box">
                                <div class="d-flex align-items-start mb-2">
                                    <span class="letra-badge">C</span>
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="alt1c_puntos" wire:model="alt1c_puntos" placeholder="Puntos" style="width: 100px;">

                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="alt1c_correcta" wire:model.live="alt1c_correcta" wire:change="marcarAlternativaCorrecta('C')">
                                                <label class="form-check-label" for="alt1c_correcta">Correcta</label>
                                            </div>
                                        </div>
                                        <textarea class="form-control" id="alt1c_texto" wire:model="alt1c_texto" rows="2" placeholder="Texto alternativa C..."></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PREGUNTA ETAPA 1 -->
                        <div class="pregunta-section">
                            <label class="form-label fw-bold"> Pregunta Doctrinal</label>

                            <div class="mb-2">
                                <label class="form-label">Descripción</label>
                                <input type="text" class="form-control form-control-sm" id="pregunta1_descripcion" wire:model="pregunta1_descripcion" placeholder="Contexto de la pregunta...">
                            </div>

                            <textarea class="form-control mb-2" id="pregunta1_texto" wire:model="pregunta1_texto" rows="2" placeholder="¿Qué herramienta...?"></textarea>

                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label">Palabra Clave</label>
                                    <input type="text" class="form-control" id="pregunta1_clave" wire:model="pregunta1_clave" placeholder="Ej: logs">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Puntos</label>
                                    <input type="text" class="form-control" onkeyup="validar_numeros(this.id)" id="pregunta1_puntos" wire:model="pregunta1_puntos">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Caso</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
    {{--    MODAL CREAR / EDITAR REGISTRO--}}

    {{--    MODAL ETAPA 2   --}}
    <x-modal-general  wire:ignore.self >
        <x-slot name="tama">modal-xl</x-slot>
        <x-slot name="id_modal">modal_etapa_dos</x-slot>
        <x-slot name="titleModal">Crear Etapa 2 - Segunda Interacción</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_etapa_dos">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @if (session()->has('error_etapa_dos'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_etapa_dos') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    <div class="col-lg-12 mb-3">
                        <div class="alert alert-info alert-dismissible show fade">
                            <strong>Instrucciones:</strong> Crearás 3 escenarios diferentes según la alternativa elegida en Etapa 1.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    </div>

                    <!-- ESCENARIO A -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <strong>Escenario A</strong>
                        </div>
                        <div class="card-body">
                            <div class="selector-alternativa mt-3 mb-3">
                                <label class="form-label">Alternativa Padre (Etapa 1)</label>
                                <select class="form-select" id="e2a_alt_padre" wire:model.live="e2a_alt_padre"
                                        @if($modo_edicion_etapa2) disabled @endif>
                                    <option value="">Seleccionar alternativa de etapa 1...</option>

                                    @if(!$modo_edicion_etapa2)
                                        {{-- MODO CREAR: evitar repetidos --}}
                                        @foreach($listar_alternativas_etapa_uno as $alt)
                                            @php
                                                $usadaEnB = $e2b_alt_padre == $alt->id_alternativa;
                                                $usadaEnC = $e2c_alt_padre == $alt->id_alternativa;
                                            @endphp
                                            @if((!$usadaEnB && !$usadaEnC) || $e2a_alt_padre == $alt->id_alternativa)
                                                <option value="{{ $alt->id_alternativa }}">
                                                    {{ $alt->alternativa_letra }} - {{ $alt->alternativa_texto }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        {{-- MODO EDITAR: mostrar todas, Livewire marca seleccionada --}}
                                        @foreach($listar_alternativas_etapa_uno as $alt)
                                            <option value="{{ $alt->id_alternativa }}">
                                                {{ $alt->alternativa_letra }} - {{ $alt->alternativa_texto }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('e2a_alt_padre') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Título de Etapa</label>
                                <input type="text" class="form-control" id="e2a_titulo" wire:model="e2a_titulo" placeholder="Título...">
                                @error('e2a_titulo') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Problema</label>
                                <textarea class="form-control" id="e2a_problema" wire:model="e2a_problema" rows="3" placeholder="Problema..."></textarea>
                                @error('e2a_problema') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">A</span>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm"
                                                   onkeyup="validar_numeros(this.id)"
                                                   id="e2a_altA_puntos" wire:model="e2a_altA_puntos"
                                                   placeholder="puntaje" style="width: 120px;">
                                            @error('e2a_altA_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2a_altA_correcta"
                                                       wire:model.live="e2a_altA_correcta"
                                                       wire:change="setCorrectaE2('A','A')">
                                                <label class="form-check-label" for="e2a_altA_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2a_altA_texto" wire:model="e2a_altA_texto" rows="3" placeholder="Alternativa A"></textarea>
                                        @error('e2a_altA_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">B</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="e2a_altB_puntos" wire:model="e2a_altB_puntos" placeholder="puntaje" style="width: 120px;">
                                            @error('e2a_altB_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2a_altB_correcta"
                                                       wire:model.live="e2a_altB_correcta"
                                                       wire:change="setCorrectaE2('A','B')">
                                                <label class="form-check-label" for="e2a_altB_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2a_altB_texto" wire:model="e2a_altB_texto" rows="3" placeholder="Alternativa B"></textarea>
                                        @error('e2a_altB_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">C</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm mb-2" onkeyup="validar_numeros(this.id)" id="e2a_altC_puntos" wire:model="e2a_altC_puntos" placeholder="puntaje" style="width: 120px;">
                                            @error('e2a_altC_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2a_altC_correcta"
                                                       wire:model.live="e2a_altC_correcta"
                                                       wire:change="setCorrectaE2('A','C')">
                                                <label class="form-check-label" for="e2a_altC_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2a_altC_texto" wire:model="e2a_altC_texto" rows="3" placeholder="Alternativa C"></textarea>
                                        @error('e2a_altC_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pregunta-section">
                                <label class="form-label fw-bold">Pregunta Doctrinal</label>
                                <input type="text" class="form-control form-control-sm mb-2" id="e2a_pregunta_desc" wire:model="e2a_pregunta_desc" placeholder="Descripción...">
                                @error('e2a_pregunta_desc') <span class="message-error">{{ $message }}</span> @enderror
                                <textarea class="form-control mb-2" id="e2a_pregunta_texto" wire:model="e2a_pregunta_texto" rows="2" placeholder="Pregunta..."></textarea>
                                @error('e2a_pregunta_texto') <span class="message-error">{{ $message }}</span> @enderror
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="e2a_pregunta_clave" wire:model="e2a_pregunta_clave" placeholder="Palabra clave">
                                        @error('e2a_pregunta_clave') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" onkeyup="validar_numeros(this.id)" id="e2a_pregunta_puntos" wire:model="e2a_pregunta_puntos" placeholder="Puntaje">
                                        @error('e2a_pregunta_puntos') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- =========================
                            ETAPA 3 - RAMAS (ESCENARIO A)
                            ========================= --}}
                            <div class="mt-4">
                                <div class="alert alert-success">
                                    Configura a dónde irá el usuario según la alternativa que elija en esta Etapa 2.
                                </div>

                                @php $esc = 'A' @endphp
                                @foreach(['A','B','C'] as $alt2)
                                    <div class="card mb-2" style="border: 1px dashed #198754;">
                                        <div class="card-header d-flex justify-content-between align-items-center" style="background:#e8f5e9;">
                                            <div>
                                                <strong>Si el usuario elige la alternativa: {{ $alt2 }}</strong>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           wire:model.live="e3.{{ $esc }}.{{ $alt2 }}.enabled"
                                                           id="e3_enabled_{{ $esc }}_{{ $alt2 }}">
                                                    <label class="form-check-label" for="e3_enabled_{{ $esc }}_{{ $alt2 }}">
                                                        Crear rama
                                                    </label>
                                                </div>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success"
                                                        wire:click="toggleE3('{{ $esc }}','{{ $alt2 }}')"
                                                    @disabled(empty($e3[$esc][$alt2]['enabled']))>
                                                    Configurar
                                                </button>
                                            </div>
                                        </div>

                                        @if(!empty($e3_open[$esc][$alt2]))
                                            <div class="card-body">

                                                <div class="mb-3">
                                                    <label class="form-label">Título Etapa 3</label>
                                                    <input type="text" class="form-control"
                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.titulo">
                                                    @error("e3.$esc.$alt2.titulo") <span class="message-error">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Problema Etapa 3</label>
                                                    <textarea class="form-control" rows="3"
                                                              wire:model="e3.{{ $esc }}.{{ $alt2 }}.problema"></textarea>
                                                    @error("e3.$esc.$alt2.problema") <span class="message-error">{{ $message }}</span> @enderror
                                                </div>

                                                {{-- =========================
                                                    ALTERNATIVAS ETAPA 3 (A,B,C) + CORRECTA
                                                   ========================= --}}
                                                <div class="row">
                                                    @foreach(['A','B','C'] as $l3)
                                                        <div class="col-md-4">
                                                            <div class="alternativa-box">
                                                                <span class="letra-badge">{{ $l3 }}</span>

                                                                {{-- PUNTAJE + SWITCH CORRECTA --}}
                                                                <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                                                                    <input type="text"
                                                                           class="form-control form-control-sm"
                                                                           onkeyup="validar_numeros(this.id)"
                                                                           id="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_puntos"
                                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.puntos"
                                                                           placeholder="puntaje" style="width: 120px;">

                                                                    <div class="form-check form-switch m-0">
                                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                                               id="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_correcta"
                                                                               wire:model.live="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.correcta"
                                                                               wire:change="setCorrectaE3('{{ $esc }}','{{ $alt2 }}','{{ $l3 }}')">
                                                                        <label class="form-check-label"
                                                                               for="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_correcta">
                                                                            Correcta
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                @error("e3.$esc.$alt2.alts.$l3.puntos")
                                                                <span class="message-error">{{ $message }}</span>
                                                                @enderror

                                                                <textarea class="form-control"
                                                                          rows="3"
                                                                          wire:model="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.texto"
                                                                          placeholder="Alternativa {{ $l3 }}"></textarea>

                                                                @error("e3.$esc.$alt2.alts.$l3.texto")
                                                                <span class="message-error">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- =========================
                                                    PREGUNTA ETAPA 3
                                                   ========================= --}}
                                                <div class="pregunta-section mt-3">
                                                    <label class="form-label fw-bold">Pregunta doctrinal (Etapa 3)</label>

                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.desc"
                                                           placeholder="Descripción...">
                                                    @error("e3.$esc.$alt2.preg.desc") <span class="message-error">{{ $message }}</span> @enderror

                                                    <textarea class="form-control mb-2"
                                                              rows="2"
                                                              wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.texto"
                                                              placeholder="Pregunta..."></textarea>
                                                    @error("e3.$esc.$alt2.preg.texto") <span class="message-error">{{ $message }}</span> @enderror

                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control"
                                                                   wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.clave"
                                                                   placeholder="Palabra clave">
                                                            @error("e3.$esc.$alt2.preg.clave") <span class="message-error">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control"
                                                                   onkeyup="validar_numeros(this.id)"
                                                                   id="e3_{{ $esc }}_{{ $alt2 }}_preg_puntos"
                                                                   wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.puntos"
                                                                   placeholder="Puntaje">
                                                            @error("e3.$esc.$alt2.preg.puntos") <span class="message-error">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- ESCENARIO B -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <strong>Escenario B</strong>
                        </div>
                        <div class="card-body">
                            <div class="selector-alternativa mt-3 mb-3">
                                <select class="form-select" id="e2b_alt_padre" wire:model.live="e2b_alt_padre"
                                        @if($modo_edicion_etapa2) disabled @endif>
                                    <option value="">Seleccionar alternativa de etapa 1...</option>

                                    @if(!$modo_edicion_etapa2)
                                        @foreach($listar_alternativas_etapa_uno as $alt)
                                            @php
                                                $usadaEnA = $e2a_alt_padre == $alt->id_alternativa;
                                                $usadaEnC = $e2c_alt_padre == $alt->id_alternativa;
                                            @endphp
                                            @if((!$usadaEnA && !$usadaEnC) || $e2b_alt_padre == $alt->id_alternativa)
                                                <option value="{{ $alt->id_alternativa }}">
                                                    {{ $alt->alternativa_letra }} - {{ $alt->alternativa_texto }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($listar_alternativas_etapa_uno as $alt)
                                            <option value="{{ $alt->id_alternativa }}">
                                                {{ $alt->alternativa_letra }} - {{ $alt->alternativa_texto }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('e2b_alt_padre') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb3">
                                <label class="form-label">Título de Etapa</label>
                                <input type="text" class="form-control mb-3" id="e2b_titulo" wire:model="e2b_titulo" placeholder="Título...">
                                @error('e2b_titulo') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb3">
                                <label class="form-label">Problema</label>
                                <textarea class="form-control mb-3" id="e2b_problema" wire:model="e2b_problema" rows="3" placeholder="Problema..."></textarea>
                                @error('e2b_problema') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <!-- A -->
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">A</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm"
                                                   onkeyup="validar_numeros(this.id)"
                                                   id="e2b_altA_puntos" wire:model="e2b_altA_puntos"
                                                   placeholder="puntaje" style="width: 120px;">
                                            @error('e2b_altA_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2b_altA_correcta"
                                                       wire:model.live="e2b_altA_correcta"
                                                       wire:change="setCorrectaE2('B','A')">
                                                <label class="form-check-label" for="e2b_altA_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2b_altA_texto" wire:model="e2b_altA_texto" rows="3" placeholder="Alternativa A"></textarea>
                                         @error('e2b_altA_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!-- B -->
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">B</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm"
                                                   onkeyup="validar_numeros(this.id)"
                                                   id="e2b_altB_puntos" wire:model="e2b_altB_puntos"
                                                   placeholder="puntaje" style="width: 120px;">
                                            @error('e2b_altB_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2b_altB_correcta"
                                                       wire:model.live="e2b_altB_correcta"
                                                       wire:change="setCorrectaE2('B','B')">
                                                <label class="form-check-label" for="e2b_altB_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2b_altB_texto" wire:model="e2b_altB_texto" rows="3" placeholder="Alternativa B"></textarea>
                                         @error('e2b_altB_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <!-- C -->
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">C</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm"
                                                   onkeyup="validar_numeros(this.id)"
                                                   id="e2b_altC_puntos" wire:model="e2b_altC_puntos"
                                                   placeholder="puntaje" style="width: 120px;">
                                            @error('e2b_altC_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2b_altC_correcta"
                                                       wire:model.live="e2b_altC_correcta"
                                                       wire:change="setCorrectaE2('B','C')">
                                                <label class="form-check-label" for="e2b_altC_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2b_altC_texto" wire:model="e2b_altC_texto" rows="3" placeholder="Alternativa C"></textarea>
                                         @error('e2b_altC_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pregunta-section">
                                <input type="text" class="form-control form-control-sm mb-2" id="e2b_pregunta_desc" wire:model="e2b_pregunta_desc" placeholder="Descripción...">
                                @error('e2b_pregunta_desc') <span class="message-error">{{ $message }}</span> @enderror
                                <textarea class="form-control mb-2" id="e2b_pregunta_texto" wire:model="e2b_pregunta_texto" rows="2" placeholder="Pregunta..."></textarea>
                                @error('e2b_pregunta_texto') <span class="message-error">{{ $message }}</span> @enderror
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="e2b_pregunta_clave" wire:model="e2b_pregunta_clave" placeholder="Palabra clave">
                                        @error('e2b_pregunta_clave') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" onkeyup="validar_numeros(this.id)" id="e2b_pregunta_puntos" wire:model="e2b_pregunta_puntos" placeholder="Puntaje">
                                        @error('e2b_pregunta_puntos') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- =========================
                            ETAPA 3 - RAMAS (ESCENARIO B)
                            ========================= --}}
                            <div class="mt-4">
                                <div class="alert alert-success">
                                    Configura a dónde irá el usuario según la alternativa que elija en esta Etapa 2.
                                </div>

                                @php $esc = 'B' @endphp
                                @foreach(['A','B','C'] as $alt2)
                                    <div class="card mb-2" style="border: 1px dashed #198754;">
                                        <div class="card-header d-flex justify-content-between align-items-center" style="background:#e8f5e9;">
                                            <div>
                                                <strong>Si el usuario elige la alternativa: {{ $alt2 }}</strong>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           wire:model.live="e3.{{ $esc }}.{{ $alt2 }}.enabled"
                                                           id="e3_enabled_{{ $esc }}_{{ $alt2 }}">
                                                    <label class="form-check-label" for="e3_enabled_{{ $esc }}_{{ $alt2 }}">
                                                        Crear rama
                                                    </label>
                                                </div>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success"
                                                        wire:click="toggleE3('{{ $esc }}','{{ $alt2 }}')"
                                                    @disabled(empty($e3[$esc][$alt2]['enabled']))>
                                                    Configurar
                                                </button>
                                            </div>
                                        </div>

                                        @if(!empty($e3_open[$esc][$alt2]))
                                            <div class="card-body">

                                                <div class="mb-3">
                                                    <label class="form-label">Título Etapa 3</label>
                                                    <input type="text" class="form-control"
                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.titulo">
                                                    @error("e3.$esc.$alt2.titulo") <span class="message-error">{{ $message }}</span> @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label class="form-label">Problema Etapa 3</label>
                                                    <textarea class="form-control" rows="3"
                                                              wire:model="e3.{{ $esc }}.{{ $alt2 }}.problema"></textarea>
                                                    @error("e3.$esc.$alt2.problema") <span class="message-error">{{ $message }}</span> @enderror
                                                </div>

                                                {{-- =========================
                                                    ALTERNATIVAS ETAPA 3 (A,B,C) + CORRECTA
                                                   ========================= --}}
                                                <div class="row">
                                                    @foreach(['A','B','C'] as $l3)
                                                        <div class="col-md-4">
                                                            <div class="alternativa-box">
                                                                <span class="letra-badge">{{ $l3 }}</span>

                                                                {{-- PUNTAJE + SWITCH CORRECTA --}}
                                                                <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                                                                    <input type="text"
                                                                           class="form-control form-control-sm"
                                                                           onkeyup="validar_numeros(this.id)"
                                                                           id="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_puntos"
                                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.puntos"
                                                                           placeholder="puntaje" style="width: 120px;">

                                                                    <div class="form-check form-switch m-0">
                                                                        <input class="form-check-input" type="checkbox" role="switch"
                                                                               id="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_correcta"
                                                                               wire:model.live="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.correcta"
                                                                               wire:change="setCorrectaE3('{{ $esc }}','{{ $alt2 }}','{{ $l3 }}')">
                                                                        <label class="form-check-label"
                                                                               for="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_correcta">
                                                                            Correcta
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                @error("e3.$esc.$alt2.alts.$l3.puntos")
                                                                <span class="message-error">{{ $message }}</span>
                                                                @enderror

                                                                <textarea class="form-control"
                                                                          rows="3"
                                                                          wire:model="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.texto"
                                                                          placeholder="Alternativa {{ $l3 }}"></textarea>

                                                                @error("e3.$esc.$alt2.alts.$l3.texto")
                                                                <span class="message-error">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- =========================
                                                    PREGUNTA ETAPA 3
                                                   ========================= --}}
                                                <div class="pregunta-section mt-3">
                                                    <label class="form-label fw-bold">Pregunta doctrinal (Etapa 3)</label>

                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.desc"
                                                           placeholder="Descripción...">
                                                    @error("e3.$esc.$alt2.preg.desc") <span class="message-error">{{ $message }}</span> @enderror

                                                    <textarea class="form-control mb-2"
                                                              rows="2"
                                                              wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.texto"
                                                              placeholder="Pregunta..."></textarea>
                                                    @error("e3.$esc.$alt2.preg.texto") <span class="message-error">{{ $message }}</span> @enderror

                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control"
                                                                   wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.clave"
                                                                   placeholder="Palabra clave">
                                                            @error("e3.$esc.$alt2.preg.clave") <span class="message-error">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control"
                                                                   onkeyup="validar_numeros(this.id)"
                                                                   id="e3_{{ $esc }}_{{ $alt2 }}_preg_puntos"
                                                                   wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.puntos"
                                                                   placeholder="Puntaje">
                                                            @error("e3.$esc.$alt2.preg.puntos") <span class="message-error">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <!-- ESCENARIO C -->
                    <div class="card mb-3">
                        <div class="card-header bg-primary text-white">
                            <strong>Escenario C</strong>
                        </div>
                        <div class="card-body">
                            <div class="selector-alternativa mt-3 mb-3">
                                <label class="form-label">Alternativa Padre (Etapa 1)</label>
                                <select class="form-select" id="e2c_alt_padre" wire:model.live="e2c_alt_padre"
                                        @if($modo_edicion_etapa2) disabled @endif>
                                    <option value="">Seleccionar alternativa de etapa 1...</option>

                                    @if(!$modo_edicion_etapa2)
                                        @foreach($listar_alternativas_etapa_uno as $alt)
                                            @php
                                                $usadaEnA = $e2a_alt_padre == $alt->id_alternativa;
                                                $usadaEnB = $e2b_alt_padre == $alt->id_alternativa;
                                            @endphp
                                            @if((!$usadaEnA && !$usadaEnB) || $e2c_alt_padre == $alt->id_alternativa)
                                                <option value="{{ $alt->id_alternativa }}">
                                                    {{ $alt->alternativa_letra }} - {{ $alt->alternativa_texto }}
                                                </option>
                                            @endif
                                        @endforeach
                                    @else
                                        @foreach($listar_alternativas_etapa_uno as $alt)
                                            <option value="{{ $alt->id_alternativa }}">
                                                {{ $alt->alternativa_letra }} - {{ $alt->alternativa_texto }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('e2c_alt_padre') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Título de Etapa</label>
                                <input type="text" class="form-control mb-3" id="e2c_titulo" wire:model="e2c_titulo" placeholder="Título...">
                                @error('e2c_titulo') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Problema</label>
                                <textarea class="form-control mb-3" id="e2c_problema" wire:model="e2c_problema" rows="3" placeholder="Problema..."></textarea>
                                @error('e2c_problema') <span class="message-error">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <!-- Alternativa A -->
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">A</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm"
                                                   onkeyup="validar_numeros(this.id)"
                                                   id="e2c_altA_puntos" wire:model="e2c_altA_puntos"
                                                   placeholder="puntaje" style="width: 120px;">
                                            @error('e2c_altA_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2c_altA_correcta"
                                                       wire:model.live="e2c_altA_correcta"
                                                       wire:change="setCorrectaE2('C','A')">
                                                <label class="form-check-label" for="e2c_altA_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2c_altA_texto" wire:model="e2c_altA_texto" rows="3" placeholder="Alternativa A"></textarea>
                                        @error('e2c_altA_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Alternativa B -->
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">B</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm"
                                                   onkeyup="validar_numeros(this.id)"
                                                   id="e2c_altB_puntos" wire:model="e2c_altB_puntos"
                                                   placeholder="puntaje" style="width: 120px;">
                                            @error('e2c_altB_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2c_altB_correcta"
                                                       wire:model.live="e2c_altB_correcta"
                                                       wire:change="setCorrectaE2('C','B')">
                                                <label class="form-check-label" for="e2c_altB_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2c_altB_texto" wire:model="e2c_altB_texto" rows="3" placeholder="Alternativa B"></textarea>
                                        @error('e2c_altB_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>

                                <!-- Alternativa C -->
                                <div class="col-md-4">
                                    <div class="alternativa-box">
                                        <span class="letra-badge">C</span>

                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <input type="text" class="form-control form-control-sm"
                                                   onkeyup="validar_numeros(this.id)"
                                                   id="e2c_altC_puntos" wire:model="e2c_altC_puntos"
                                                   placeholder="puntaje" style="width: 120px;">
                                            @error('e2c_altC_puntos') <span class="message-error">{{ $message }}</span> @enderror

                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                       id="e2c_altC_correcta"
                                                       wire:model.live="e2c_altC_correcta"
                                                       wire:change="setCorrectaE2('C','C')">
                                                <label class="form-check-label" for="e2c_altC_correcta">Correcta</label>
                                            </div>
                                        </div>

                                        <textarea class="form-control" id="e2c_altC_texto" wire:model="e2c_altC_texto" rows="3" placeholder="Alternativa C"></textarea>
                                        @error('e2c_altC_texto') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="pregunta-section mt-3">
                                <label class="form-label fw-bold">Pregunta Doctrinal</label>
                                <input type="text" class="form-control form-control-sm mb-2" id="e2c_pregunta_desc" wire:model="e2c_pregunta_desc" placeholder="Descripción...">
                                @error('e2c_pregunta_desc') <span class="message-error">{{ $message }}</span> @enderror
                                <textarea class="form-control mb-2" id="e2c_pregunta_texto" wire:model="e2c_pregunta_texto" rows="2" placeholder="Pregunta..."></textarea>
                                @error('e2c_pregunta_texto') <span class="message-error">{{ $message }}</span> @enderror
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control" id="e2c_pregunta_clave" wire:model="e2c_pregunta_clave" placeholder="Palabra clave">
                                        @error('e2c_pregunta_clave') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control" onkeyup="validar_numeros(this.id)" id="e2c_pregunta_puntos" wire:model="e2c_pregunta_puntos" placeholder="Puntaje">
                                        @error('e2c_pregunta_puntos') <span class="message-error">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- =========================
                            ETAPA 3 - RAMAS (ESCENARIO C)
                            ========================= --}}
                            <div class="mt-4">
                                <div class="alert alert-success">
                                    Configura a dónde irá el usuario según la alternativa que elija en esta Etapa 2.
                                </div>

                                @php $esc = 'C' @endphp
                                @foreach(['A','B','C'] as $alt2)
                                    <div class="card mb-2" style="border: 1px dashed #198754;">
                                        <div class="card-header d-flex justify-content-between align-items-center" style="background:#e8f5e9;">
                                            <div>
                                                <strong>Si el usuario elige la alternativa: {{ $alt2 }}</strong>
                                            </div>

                                            <div class="d-flex gap-2 align-items-center">
                                                <div class="form-check form-switch m-0">
                                                    <input class="form-check-input"
                                                           type="checkbox"
                                                           wire:model.live="e3.{{ $esc }}.{{ $alt2 }}.enabled"
                                                           id="e3_enabled_{{ $esc }}_{{ $alt2 }}">
                                                    <label class="form-check-label" for="e3_enabled_{{ $esc }}_{{ $alt2 }}">
                                                        Crear rama
                                                    </label>
                                                </div>

                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success"
                                                        wire:click="toggleE3('{{ $esc }}','{{ $alt2 }}')"
                                                    @disabled(empty($e3[$esc][$alt2]['enabled']))>
                                                    Configurar
                                                </button>
                                            </div>
                                        </div>

                                        @if(!empty($e3_open[$esc][$alt2]))
                                            <div class="card-body">

                                                {{-- TÍTULO --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Título Etapa 3</label>
                                                    <input type="text" class="form-control"
                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.titulo">
                                                    @error("e3.$esc.$alt2.titulo") <span class="message-error">{{ $message }}</span> @enderror
                                                </div>

                                                {{-- PROBLEMA --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Problema Etapa 3</label>
                                                    <textarea class="form-control" rows="3"
                                                              wire:model="e3.{{ $esc }}.{{ $alt2 }}.problema"></textarea>
                                                    @error("e3.$esc.$alt2.problema") <span class="message-error">{{ $message }}</span> @enderror
                                                </div>

                                                {{-- =========================
                                                    ALTERNATIVAS ETAPA 3 (A,B,C)
                                                   ========================= --}}
                                                <div class="row">
                                                    @foreach(['A','B','C'] as $l3)
                                                        <div class="col-md-4">
                                                            <div class="alternativa-box">
                                                                <span class="letra-badge">{{ $l3 }}</span>

                                                                <div class="d-flex justify-content-between align-items-center mb-2 gap-2">
                                                                    <input type="text"
                                                                           class="form-control form-control-sm"
                                                                           onkeyup="validar_numeros(this.id)"
                                                                           id="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_puntos"
                                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.puntos"
                                                                           placeholder="puntaje" style="width:120px;">

                                                                    <div class="form-check form-switch m-0">
                                                                        <input class="form-check-input"
                                                                               type="checkbox"
                                                                               role="switch"
                                                                               id="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_correcta"
                                                                               wire:model.live="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.correcta"
                                                                               wire:change="setCorrectaE3('{{ $esc }}','{{ $alt2 }}','{{ $l3 }}')">
                                                                        <label class="form-check-label"
                                                                               for="e3_{{ $esc }}_{{ $alt2 }}_{{ $l3 }}_correcta">
                                                                            Correcta
                                                                        </label>
                                                                    </div>
                                                                </div>

                                                                @error("e3.$esc.$alt2.alts.$l3.puntos")
                                                                <span class="message-error">{{ $message }}</span>
                                                                @enderror

                                                                <textarea class="form-control"
                                                                          rows="3"
                                                                          wire:model="e3.{{ $esc }}.{{ $alt2 }}.alts.{{ $l3 }}.texto"
                                                                          placeholder="Alternativa {{ $l3 }}"></textarea>

                                                                @error("e3.$esc.$alt2.alts.$l3.texto")
                                                                <span class="message-error">{{ $message }}</span>
                                                                @enderror
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>

                                                {{-- =========================
                                                    PREGUNTA ETAPA 3
                                                   ========================= --}}
                                                <div class="pregunta-section mt-3">
                                                    <label class="form-label fw-bold">Pregunta doctrinal (Etapa 3)</label>

                                                    <input type="text" class="form-control form-control-sm mb-2"
                                                           wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.desc"
                                                           placeholder="Descripción...">
                                                    @error("e3.$esc.$alt2.preg.desc") <span class="message-error">{{ $message }}</span> @enderror

                                                    <textarea class="form-control mb-2"
                                                              rows="2"
                                                              wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.texto"
                                                              placeholder="Pregunta..."></textarea>
                                                    @error("e3.$esc.$alt2.preg.texto") <span class="message-error">{{ $message }}</span> @enderror

                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <input type="text" class="form-control"
                                                                   wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.clave"
                                                                   placeholder="Palabra clave">
                                                            @error("e3.$esc.$alt2.preg.clave") <span class="message-error">{{ $message }}</span> @enderror
                                                        </div>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control"
                                                                   onkeyup="validar_numeros(this.id)"
                                                                   id="e3_{{ $esc }}_{{ $alt2 }}_preg_puntos"
                                                                   wire:model="e3.{{ $esc }}.{{ $alt2 }}.preg.puntos"
                                                                   placeholder="Puntaje">
                                                            @error("e3.$esc.$alt2.preg.puntos") <span class="message-error">{{ $message }}</span> @enderror
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Registros</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
    {{--     FIN MODAL ETAPA 2  --}}

    {{--    MODAL DELETE REGISTROS--}}
    <x-modal-delete  wire:ignore.self >
        <x-slot name="id_modal">modal_delete_caso</x-slot>
        <x-slot name="modalContentDelete">
            <form wire:submit.prevent="disable_caso">
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        <h2 class="deleteTitle">{{$messageDelete}}</h2>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12">
                        @error('id_caso') <span class="message-error">{{ $message }}</span> @enderror

                        @error('caso_estado') <span class="message-error">{{ $message }}</span> @enderror

                        @if (session()->has('error_delete'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_delete') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 mt-3 text-center">
                        <button type="submit" class="btn btn-primary text-white btnDelete">SI</button>
                        <button type="button" data-bs-dismiss="modal" class="btn btn-danger btnDelete">No</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-delete>
    {{--    MODAL DELETE REGISTROS--}}

    {{--    MODAL CREAR / EDITAR ANÁLISIS--}}
    <x-modal-general wire:ignore.self>
        <x-slot name="id_modal">modal_crear_analisis</x-slot>
        <x-slot name="titleModal">Gestionar Análisis del Caso</x-slot>
        <x-slot name="tama">modal-lg</x-slot>
        <x-slot name="modalContent">
            <form wire:submit.prevent="save_analisis">
                <div class="row">
                    {{-- ERRORES --}}
                    <div class="col-12 mb-3">
                        @if (session()->has('error_modal_analisis'))
                            <div class="alert alert-danger alert-dismissible show fade">
                                {{ session('error_modal_analisis') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                    </div>

                    {{-- ================= ANALISIS ================= --}}
                    <div class="col-lg-8 col-md-8 col-sm-12 mb-3">
                        <h6 class="fw-bold mb-2">Análisis académico del caso</h6>
                        <textarea class="form-control" rows="15" id="analisis_contenido" wire:model.defer="analisis_contenido" placeholder="Redacta el análisis completo del caso. Usa Enter para separar párrafos."></textarea>
                    </div>

                    {{-- ================= CONCEPTOS Y FRASES ================= --}}
                    <div class="col-lg-4 col-md-4 col-sm-12 mb-3">
                        <div class="row">

                            {{-- CONCEPTOS --}}
                            <div class="col-12 mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">Conceptos</h6>
                                    <button type="button" class="btn btn-sm btn-primary" wire:click="agregar_concepto">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                @foreach($conceptos as $index => $concepto)
                                    @if(!($concepto['deleted'] ?? false))
                                        <div class="border rounded p-2 mb-2">
                                            <input type="text" class="form-control mb-2" placeholder="Título" wire:model.defer="conceptos.{{ $index }}.titulo">

                                            <textarea class="form-control mb-2" rows="3" placeholder="Descripción" wire:model.defer="conceptos.{{ $index }}.descripcion"></textarea>

                                            <button type="button" class="btn btn-sm btn-danger w-100" wire:click="eliminar_concepto({{ $index }})">
                                                Eliminar concepto
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>

                            <hr>

                            {{-- FRASES --}}
                            <div class="col-12">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0">Frases</h6>
                                    <button type="button" class="btn btn-sm btn-success" wire:click="agregar_frase">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>

                                @foreach($frases as $index => $frase)
                                    @if(!($frase['deleted'] ?? false))
                                        <div class="border rounded p-2 mb-2">
                                            <textarea class="form-control mb-2" rows="2" placeholder="Frase" wire:model.defer="frases.{{ $index }}.texto"></textarea>

                                            <button type="button" class="btn btn-sm btn-danger w-100" wire:click="eliminar_frese({{ $index }})">
                                                Eliminar frase
                                            </button>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- BOTONES --}}
                    <div class="col-12 mt-3 text-end">
                        <button type="button" data-bs-dismiss="modal" class="btn btn-secondary">Cerrar</button>
                        <button type="submit" class="btn btn-success text-white">Guardar Análisis</button>
                    </div>
                </div>
            </form>
        </x-slot>
    </x-modal-general>
    {{--    MODAL CREAR / EDITAR ANÁLISIS--}}


    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-12 d-flex align-items-center mb-2">
            <input type="text" class="form-control w-50 me-4"  wire:model.live="search_caso" placeholder="Buscar">
            <x-select-filter wire:model.live="pagination_caso" />
        </div>
        <div class="col-lg-6 col-md-6 col-sm-12 text-end">
            <x-btn-export wire:click="clear_form" class="bg-success text-white" data-bs-toggle="modal" data-bs-target="#modal_casos" >
                <x-slot name="icons">
                    fa-solid fa-plus
                </x-slot>
                Agregar Caso
            </x-btn-export>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible show fade mt-2">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible show fade mt-2">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <x-card-general-view>
        <x-slot name="content">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12">
                    <x-table-general>
                        <x-slot name="thead">
                            <tr>
                                <th>N°</th>
                                <th>Título</th>
                                <th>Objetivo</th>
                                <th>Contexto</th>
                                <th>Crear Etapa 2</th>
                                <th>Análisis</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </x-slot>

                        <x-slot name="tbody">
                            @if(count($listar_casos) > 0)
                                @php $conteo = 1; @endphp
                                @foreach($listar_casos as $me)
                                    <tr>
                                        <td>{{$conteo}}</td>
                                        <td>{{$me->caso_titulo}}</td>
                                        <td>{{$me->caso_objetivo}}</td>
                                        <td>{{$me->caso_contexto}}</td>
                                        @php
                                            $id_etapa = \Illuminate\Support\Facades\DB::table('etapas')
                                                ->where('id_caso', '=', $me->id_caso)
                                                ->where('etapa_numero', '=', 1)
                                                ->where('etapa_estado', '=', 1)
                                                ->value('id_etapa');

                                            $tieneEtapa2 = \Illuminate\Support\Facades\DB::table('etapas')
                                                ->where('id_caso', '=', $me->id_caso)
                                                ->where('etapa_numero', '=', 2)
                                                ->where('etapa_estado', '=', 1)
                                                ->exists();
                                        @endphp
                                        <td>
                                            @if(!$tieneEtapa2)
                                                {{-- BTN CREAR ETAPA 2 --}}
                                                <a class="btn btn-sm btn-success" wire:click="crear_etapa_dos('{{ base64_encode($me->id_caso) }}', '{{ base64_encode($id_etapa) }}')" data-bs-toggle="modal" data-bs-target="#modal_etapa_dos">
                                                    <i class="fas fa-plus"></i> Agregar
                                                </a>
                                            @else
                                                {{-- BTN EDITAR ETAPA 2 --}}
                                                <a class="btn btn-sm btn-primary" wire:click="editar_etapa_dos('{{ base64_encode($me->id_caso) }}', '{{ base64_encode($id_etapa) }}')" data-bs-toggle="modal" data-bs-target="#modal_etapa_dos">
                                                    <i class="fa-solid fa-pen-to-square"></i> Editar
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                $analisis = \App\Models\Analisis::where('id_caso', $me->id_caso)
                                                    ->where('analisis_estado', 1)
                                                    ->first();
                                            @endphp

                                            @if(!$analisis)
                                                {{-- BTN CREAR ANÁLISIS --}}
                                                <a class="btn btn-sm btn-success" wire:click="btn_crear_analisis('{{ base64_encode($me->id_caso) }}')" data-bs-toggle="modal" data-bs-target="#modal_crear_analisis">
                                                    <i class="fas fa-plus"></i> Agregar
                                                </a>
                                            @else
                                                {{-- BTN EDITAR ANÁLISIS --}}
                                                <a class="btn btn-sm btn-primary" wire:click="btn_editar_analisis('{{ base64_encode($analisis->id_analisis) }}')" data-bs-toggle="modal" data-bs-target="#modal_crear_analisis">
                                                    <i class="fas fa-edit"></i> Editar
                                                </a>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="font-bold badge {{$me->caso_estado == 1 ? 'bg-label-success ' : 'bg-label-danger'}}">
                                                {{$me->caso_estado == 1 ? 'Habilitado ' : 'Deshabilitado'}}
                                            </span>
                                        </td>
                                        <td>
                                            <x-btn-accion class=" text-primary"  wire:click="edit_data('{{ base64_encode($me->id_caso) }}')" data-bs-toggle="modal" data-bs-target="#modal_casos">
                                                <x-slot name="message">
                                                    <i class="fa-solid fa-pen-to-square"></i>
                                                </x-slot>
                                            </x-btn-accion>

                                            @if($me->caso_estado == 1)
                                                <x-btn-accion class=" text-danger" wire:click="btn_disable('{{ base64_encode($me->id_caso) }}',0)" data-bs-toggle="modal" data-bs-target="#modal_delete_caso">
                                                    <x-slot name="message">
                                                        <i class="fa-solid fa-ban"></i>
                                                    </x-slot>
                                                </x-btn-accion>
                                            @else
                                                <x-btn-accion class=" text-success" wire:click="btn_disable('{{ base64_encode($me->id_caso) }}',1)" data-bs-toggle="modal" data-bs-target="#modal_delete_caso">
                                                    <x-slot name="message">
                                                        <i class="fa-solid fa-check"></i>
                                                    </x-slot>
                                                </x-btn-accion>
                                            @endif
                                        </td>
                                    </tr>
                                    @php $conteo++; @endphp
                                @endforeach
                            @else
                                <tr class="odd">
                                    <td valign="top" colspan="9" class="dataTables_empty text-center">
                                        No se han encontrado resultados.
                                    </td>
                                </tr>
                            @endif
                        </x-slot>
                    </x-table-general>
                </div>
            </div>
        </x-slot>
    </x-card-general-view>
    {{ $listar_casos->links(data: ['scrollTo' => false]) }}
</div>

@script
<script>
    $wire.on('hide_modal_casos', () => {
        $('#modal_casos').modal('hide');
    });

    $wire.on('hide_modal_delete_caso', () => {
        $('#modal_delete_caso').modal('hide');
    });

    $wire.on('hide_modal_etapa_dos', () => {
        $('#modal_etapa_dos').modal('hide');
    });

    $wire.on('hide_modal_crear_analisis', () => {
        $('#modal_crear_analisis').modal('hide');
    });
</script>
@endscript
