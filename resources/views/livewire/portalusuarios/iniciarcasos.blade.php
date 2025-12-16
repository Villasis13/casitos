<div>
    @php
        use Illuminate\Support\Facades\DB;

        // Obtener información del caso
        $caso = DB::table('casos')
            ->where('id_caso', $id_caso)
            ->where('caso_estado', 1)
            ->first();

        // Obtener etapa 1 (inicial)
        $etapa_1 = DB::table('etapas')
            ->where('id_caso', $id_caso)
            ->where('etapa_numero', 1)
            ->where('etapa_estado', 1)
            ->first();

        // Obtener alternativas de la etapa 1
        $alternativas_etapa_1 = DB::table('alternativas')
            ->where('id_caso', $id_caso)
            ->where('id_etapa', $etapa_1->id_etapa ?? 0)
            ->where('alternativa_estado', 1)
            ->get();

        // Obtener pregunta de etapa 1
        $pregunta_etapa_1 = DB::table('preguntas')
            ->where('id_caso', $id_caso)
            ->where('id_etapa', $etapa_1->id_etapa ?? 0)
            ->where('pregunta_estado', 1)
            ->first();

        // Si hay alternativa seleccionada (etapa 1), obtener etapa 2
        $etapa_2 = null;
        $alternativas_etapa_2 = [];
        $pregunta_etapa_2 = null;

        if($alternativa_seleccionada_1) {
            $etapa_2 = DB::table('etapas')
                ->where('id_caso', $id_caso)
                ->where('id_alternativa_padre', $alternativa_seleccionada_1)
                ->where('etapa_numero', 2)
                ->where('etapa_estado', 1)
                ->first();

            if($etapa_2) {
                $alternativas_etapa_2 = DB::table('alternativas')
                    ->where('id_caso', $id_caso)
                    ->where('id_etapa', $etapa_2->id_etapa)
                    ->where('id_alternativa_padre', $alternativa_seleccionada_1)
                    ->where('alternativa_estado', 1)
                    ->get();

                $pregunta_etapa_2 = DB::table('preguntas')
                    ->where('id_caso', $id_caso)
                    ->where('id_etapa', $etapa_2->id_etapa)
                    ->where('pregunta_estado', 1)
                    ->first();
            }
        }
    @endphp

    <div class="caso-container">
        <!-- Header del Caso -->
        <div class="caso-header">
            <a  class="btn-back">
                <i class="fas fa-arrow-left"></i> Inicio
            </a>
            <div class="caso-info">
                <h1 class="caso-titulo">{{ $caso->caso_titulo ?? 'Caso' }}</h1>
                <p class="caso-objetivo-header">{{ $caso->caso_objetivo ?? '' }}</p>
            </div>

            <!-- Contador de Tiempo -->
            <div class="time-counter" x-data="timerCounter()" x-init="startTimer()">
                <div class="timer-display">
                    <i class="fas fa-clock timer-icon"></i>
                    <span class="timer-text" x-text="formatTime()"></span>
                </div>
            </div>
        </div>

        <!-- Navegación de Tabs -->
        <div class="tabs-navigation">
            <div class="tab-item {{ $tab_actual == 1 ? 'active' : '' }} {{ $tab_actual > 1 ? 'completed' : '' }}"
                 wire:click="$set('tab_actual', 1)">
                <div class="tab-number">
                    @if($tab_actual > 1)
                        <i class="fas fa-check"></i>
                    @else
                        1
                    @endif
                </div>
                <span class="tab-label">Análisis inicial</span>
            </div>

            <div class="tab-connector {{ $tab_actual > 1 ? 'active' : '' }}"></div>

            <div class="tab-item {{ $tab_actual == 2 ? 'active' : '' }} {{ $tab_actual > 2 ? 'completed' : '' }} {{ !$alternativa_seleccionada_1 ? 'disabled' : '' }}"
                 wire:click="if($alternativa_seleccionada_1) $set('tab_actual', 2)">
                <div class="tab-number">
                    @if($tab_actual > 2)
                        <i class="fas fa-check"></i>
                    @else
                        2
                    @endif
                </div>
                <span class="tab-label">Pregunta doctrinal</span>
            </div>

            <div class="tab-connector {{ $tab_actual > 2 ? 'active' : '' }}"></div>

            <div class="tab-item {{ $tab_actual == 3 ? 'active' : '' }} {{ $tab_actual > 3 ? 'completed' : '' }} {{ !$respuesta_1 ? 'disabled' : '' }}"
                 wire:click="if($respuesta_1) $set('tab_actual', 3)">
                <div class="tab-number">
                    @if($tab_actual > 3)
                        <i class="fas fa-check"></i>
                    @else
                        3
                    @endif
                </div>
                <span class="tab-label">Decisión siguiente</span>
            </div>

            <div class="tab-connector {{ $tab_actual > 3 ? 'active' : '' }}"></div>

            <div class="tab-item {{ $tab_actual == 4 ? 'active' : '' }} {{ !$alternativa_seleccionada_2 ? 'disabled' : '' }}"
                 wire:click="if($alternativa_seleccionada_2) $set('tab_actual', 4)">
                <div class="tab-number">4</div>
                <span class="tab-label">Pregunta final</span>
            </div>
        </div>

        <div class="content-wrapper">
            <div class="main-content">
                <!-- TAB 1: CONTEXTO + ALTERNATIVAS ETAPA 1 -->
                @if($tab_actual == 1)
                    <div class="tab-content fade-in">
                        <!-- Contexto del Caso -->
                        <div class="contexto-card">
                            <h3 class="contexto-title">
                                <i class="fas fa-file-alt"></i>
                                Contexto del caso
                            </h3>
                            <p class="contexto-text">{{ $caso->caso_contexto ?? '' }}</p>
                        </div>

                        <div class="problema-alternativas-grid">
                            <!-- Imagen con Problema -->
                            <div class="problema-card">
                                <div class="problema-badge">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Situación Problemática
                                </div>
                                <div class="problema-texto">
                                    {{ $etapa_1->etapa_problema ?? '' }}
                                </div>
                                <div class="imagen-container">
                                    <img src="{{ asset('hombre_inicio.png') }}" alt="Caso" class="caso-imagen">
                                </div>
                            </div>

                            <!-- Alternativas -->
                            <div class="alternativas-card">
                                <h3 class="alternativas-title">
                                    <i class="fas fa-list-ul"></i>
                                    {{ $etapa_1->etapa_titulo ?? 'Análisis inicial' }}
                                </h3>

                                <div class="alternativas-list">
                                    @foreach($alternativas_etapa_1 as $alternativa)
                                        <div class="alternativa-item {{ $alternativa_seleccionada_1 == $alternativa->id_alternativa ? 'selected' : '' }}"
                                             wire:click="$set('alternativa_seleccionada_1', {{ $alternativa->id_alternativa }})">
                                            <div class="alternativa-radio">
                                                <div class="radio-dot"></div>
                                            </div>
                                            <div class="alternativa-content">
                                                <span class="alternativa-letra">{{ $alternativa->alternativa_letra }}</span>
                                                <span class="alternativa-texto">{{ $alternativa->alternativa_texto }}</span>
                                            </div>
                                            <div class="alternativa-check">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <a class="btn btn-confirmar {{ !$alternativa_seleccionada_1 ? 'disabled' : '' }}"
                                        wire:click="confirmarAlternativa1"
                                    {{ !$alternativa_seleccionada_1 ? 'disabled' : '' }}>
                                    <i class="fas fa-check"></i>
                                    Confirmar decisión
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- TAB 2: PREGUNTA DOCTRINAL ETAPA 1 -->
                @if($tab_actual == 2)
                    <div class="tab-content fade-in">
                        <div class="pregunta-grid">
                            <!-- Imagen con Descripción -->
                            <div class="pregunta-imagen-card">
                                <div class="pregunta-descripcion-badge">
                                    <i class="fas fa-info-circle"></i>
                                    Contexto
                                </div>
                                <div class="pregunta-descripcion-texto">
                                    {{ $pregunta_etapa_1->pregunta_descripcion ?? '' }}
                                </div>
                                <div class="imagen-container">
                                    <img src="{{ asset('hombre_pregunta.png') }}" alt="Pregunta" class="caso-imagen">
                                </div>
                            </div>

                            <!-- Pregunta y Respuesta -->
                            <div class="pregunta-respuesta-card">
                                <h3 class="pregunta-title">
                                    <i class="fas fa-question-circle"></i>
                                    Pregunta doctrinal:
                                </h3>
                                <p class="pregunta-texto">{{ $pregunta_etapa_1->pregunta_texto ?? '' }}</p>

                                <div class="respuesta-container">
                                    <label class="respuesta-label">
                                        <i class="fas fa-pen"></i>
                                        Escribe tu respuesta aquí...
                                    </label>
                                    <textarea
                                        wire:model.live="respuesta_1"
                                        class="respuesta-textarea"
                                        rows="8"
                                        placeholder="Desarrolla tu respuesta de manera clara y fundamentada..."></textarea>

                                    <div class="textarea-info">
                                        <span class="char-count" x-data="{ count: '{{ strlen($respuesta_1) }}' }">
                                            <span x-text="$wire.respuesta_1.length"></span> caracteres
                                        </span>
                                    </div>
                                </div>

                                <a class="btn btn-enviar-respuesta {{ !$respuesta_1 ? 'disabled' : '' }}"
                                        wire:click="enviarRespuesta1"
                                    {{ !$respuesta_1 ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane"></i>
                                    Enviar respuesta
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- TAB 3: ALTERNATIVAS ETAPA 2 -->
                @if($tab_actual == 3)
                    <div class="tab-content fade-in">
                        <div class="problema-alternativas-grid">
                            <!-- Imagen con Problema Etapa 2 -->
                            <div class="problema-card">
                                <div class="problema-badge">
                                    <i class="fas fa-exclamation-circle"></i>
                                    Nueva Situación
                                </div>
                                <div class="problema-texto">
                                    {{ $etapa_2->etapa_problema ?? '' }}
                                </div>
                                <div class="imagen-container">
                                    <img src="{{ asset('hombre_inicio.png') }}" alt="Caso" class="caso-imagen">
                                </div>
                            </div>

                            <!-- Alternativas Etapa 2 -->
                            <div class="alternativas-card">
                                <h3 class="alternativas-title">
                                    <i class="fas fa-list-ul"></i>
                                    {{ $etapa_2->etapa_titulo ?? 'Decisión siguiente' }}
                                </h3>

                                <div class="alternativas-list">
                                    @foreach($alternativas_etapa_2 as $alternativa)
                                        <div class="alternativa-item {{ $alternativa_seleccionada_2 == $alternativa->id_alternativa ? 'selected' : '' }}"
                                             wire:click="$set('alternativa_seleccionada_2', {{ $alternativa->id_alternativa }})">
                                            <div class="alternativa-radio">
                                                <div class="radio-dot"></div>
                                            </div>
                                            <div class="alternativa-content">
                                                <span class="alternativa-letra">{{ $alternativa->alternativa_letra }}</span>
                                                <span class="alternativa-texto">{{ $alternativa->alternativa_texto }}</span>
                                            </div>
                                            <div class="alternativa-check">
                                                <i class="fas fa-check-circle"></i>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                                <a class="btn btn-confirmar {{ !$alternativa_seleccionada_2 ? 'disabled' : '' }}"
                                        wire:click="confirmarAlternativa2"
                                    {{ !$alternativa_seleccionada_2 ? 'disabled' : '' }}>
                                    <i class="fas fa-check"></i>
                                    Confirmar decisión
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- TAB 4: PREGUNTA DOCTRINAL ETAPA 2 -->
                @if($tab_actual == 4)
                    <div class="tab-content fade-in">
                        <div class="pregunta-grid">
                            <!-- Imagen con Descripción -->
                            <div class="pregunta-imagen-card">
                                <div class="pregunta-descripcion-badge">
                                    <i class="fas fa-info-circle"></i>
                                    Contexto Final
                                </div>
                                <div class="pregunta-descripcion-texto">
                                    {{ $pregunta_etapa_2->pregunta_descripcion ?? '' }}
                                </div>
                                <div class="imagen-container">
                                    <img src="{{ asset('hombre_pregunta.png') }}" alt="Pregunta" class="caso-imagen">
                                </div>
                            </div>

                            <!-- Pregunta y Respuesta -->
                            <div class="pregunta-respuesta-card">
                                <h3 class="pregunta-title">
                                    <i class="fas fa-question-circle"></i>
                                    Pregunta doctrinal final:
                                </h3>
                                <p class="pregunta-texto">{{ $pregunta_etapa_2->pregunta_texto ?? '' }}</p>

                                <div class="respuesta-container">
                                    <label class="respuesta-label">
                                        <i class="fas fa-pen"></i>
                                        Escribe tu respuesta aquí...
                                    </label>
                                    <textarea
                                        wire:model.live="respuesta_2"
                                        class="respuesta-textarea"
                                        rows="8"
                                        placeholder="Desarrolla tu respuesta de manera clara y fundamentada..."></textarea>

                                    <div class="textarea-info">
                                        <span class="char-count" x-data>
                                            <span x-text="$wire.respuesta_2.length"></span> caracteres
                                        </span>
                                    </div>
                                </div>

                                <a class="btn btn-enviar-respuesta {{ !$respuesta_2 ? 'disabled' : '' }}"
                                        wire:click="enviarRespuesta2"
                                    {{ !$respuesta_2 ? 'disabled' : '' }}>
                                    <i class="fas fa-paper-plane"></i>
                                    Enviar respuesta
                                </a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Panel de Avance (Sidebar) -->
            <div class="avance-sidebar">
                <div class="avance-card">
                    <div class="avance-header">
                        <h3 class="avance-title">
                            <i class="fas fa-chart-line"></i>
                            Tu avance
                        </h3>
                    </div>

                    <!-- Puntuación Total -->
                    <div class="puntos-total-display">
                        <div class="puntos-icon">
                            <i class="fas fa-trophy"></i>
                        </div>
                        <div class="puntos-info">
                            <span class="puntos-label">Total</span>
                            <span class="puntos-numero">{{ $puntos_totales }} pts</span>
                        </div>
                    </div>

                    <!-- Estadísticas Rápidas -->
                    <div class="stats-grid">
                        <div class="stat-mini">
                            <i class="fas fa-tasks"></i>
                            <div>
                                <span class="stat-mini-value">{{ $total_respuestas }}</span>
                                <span class="stat-mini-label">Respuestas</span>
                            </div>
                        </div>
                        <div class="stat-mini">
                            <i class="fas fa-check-circle"></i>
                            <div>
                                <span class="stat-mini-value">{{ $alternativas_completadas }}</span>
                                <span class="stat-mini-label">Decisiones</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lista de Avances -->
                    <div class="avance-list">
                        @if($alternativa_1_guardada)
                            <div class="avance-item">
                                <div class="avance-numero">1</div>
                                <div class="avance-content">
                                    <h5 class="avance-item-title">Análisis inicial</h5>
                                    <p class="avance-opcion">Opción elegida: <strong>{{ $alternativa_1_letra }}</strong></p>
                                    <div class="avance-puntos">
                                        <i class="fas fa-star"></i>
                                        {{ $alternativa_1_puntos }} pts
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($respuesta_1_guardada)
                            <div class="avance-item">
                                <div class="avance-numero">2</div>
                                <div class="avance-content">
                                    <h5 class="avance-item-title">Pregunta doctrinal</h5>
                                    <p class="avance-respuesta">{{ Str::limit($respuesta_1, 80) }}</p>
                                    <div class="avance-puntos {{ $respuesta_1_correcta ? 'correcta' : 'incorrecta' }}">
                                        <i class="fas {{ $respuesta_1_correcta ? 'fa-check' : 'fa-times' }}"></i>
                                        {{ $pregunta_1_puntos }} pts
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($alternativa_2_guardada)
                            <div class="avance-item">
                                <div class="avance-numero">3</div>
                                <div class="avance-content">
                                    <h5 class="avance-item-title">Decisión siguiente</h5>
                                    <p class="avance-opcion">Opción elegida: <strong>{{ $alternativa_2_letra }}</strong></p>
                                    <div class="avance-puntos">
                                        <i class="fas fa-star"></i>
                                        {{ $alternativa_2_puntos }} pts
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if($respuesta_2_guardada)
                            <div class="avance-item">
                                <div class="avance-numero">4</div>
                                <div class="avance-content">
                                    <h5 class="avance-item-title">Pregunta final</h5>
                                    <p class="avance-respuesta">{{ Str::limit($respuesta_2, 80) }}</p>
                                    <div class="avance-puntos {{ $respuesta_2_correcta ? 'correcta' : 'incorrecta' }}">
                                        <i class="fas {{ $respuesta_2_correcta ? 'fa-check' : 'fa-times' }}"></i>
                                        {{ $pregunta_2_puntos }} pts
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!$alternativa_1_guardada)
                        <div class="avance-empty">
                            <i class="fas fa-clipboard-list"></i>
                            <p>Completa las etapas para ver tu progreso</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <style>
        .caso-container {
            max-width: 1600px;
            margin: 0 auto;
            padding: 20px;
        }

        /* Header del Caso */
        .caso-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 10px 40px rgba(102, 126, 234, 0.3);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 12px;
            color: white;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateX(-5px);
            color: white;
        }

        .caso-info {
            flex: 1;
            min-width: 300px;
        }

        .caso-titulo {
            font-size: 32px;
            font-weight: 800;
            color: white;
            margin: 0 0 8px 0;
        }

        .caso-objetivo-header {
            font-size: 16px;
            color: rgba(255, 255, 255, 0.9);
            margin: 0;
        }

        /* Contador de Tiempo */
        .time-counter {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 16px;
            padding: 16px 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }

        .timer-display {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .timer-icon {
            font-size: 24px;
            color: #667eea;
            animation: pulse-timer 2s ease infinite;
        }

        @keyframes pulse-timer {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .timer-text {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            font-family: 'Courier New', monospace;
        }

        /* Navegación de Tabs */
        .tabs-navigation {
            background: white;
            border-radius: 20px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            justify-content: space-between;
            overflow-x: auto;
        }

        .tab-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            min-width: 120px;
        }

        .tab-item.disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        .tab-number {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: #f3f4f6;
            border: 3px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: 800;
            color: #9ca3af;
            transition: all 0.4s ease;
        }

        .tab-item.active .tab-number {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            border-color: transparent;
            color: white;
            transform: scale(1.1);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
        }

        .tab-item.completed .tab-number {
            background: #10b981;
            border-color: transparent;
            color: white;
        }

        .tab-label {
            font-size: 14px;
            font-weight: 600;
            color: #6b7280;
            text-align: center;
            transition: color 0.3s ease;
        }

        .tab-item.active .tab-label {
            color: #3b82f6;
        }

        .tab-item.completed .tab-label {
            color: #10b981;
        }

        .tab-connector {
            flex: 1;
            height: 3px;
            background: #e5e7eb;
            margin: 0 16px;
            position: relative;
            overflow: hidden;
        }

        .tab-connector::after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 0;
            height: 100%;
            background: linear-gradient(90deg, #10b981, #059669);
            transition: width 0.5s ease;
        }

        .tab-connector.active::after {
            width: 100%;
        }

        /* Content Wrapper */
        .content-wrapper {
            display: grid;
            grid-template-columns: 1fr 380px;
            gap: 24px;
        }

        .main-content {
            min-height: 600px;
        }

        .tab-content {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Contexto Card */
        .contexto-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border-left: 6px solid #3b82f6;
        }

        .contexto-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .contexto-title i {
            color: #3b82f6;
            font-size: 24px;
        }

        .contexto-text {
            font-size: 15px;
            line-height: 1.8;
            color: #4b5563;
            margin: 0;
        }

        /* Grid Problema + Alternativas */
        .problema-alternativas-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 24px;
        }

        .problema-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
        }

        .problema-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 16px;
            align-self: flex-start;
        }

        .problema-texto {
            background: #fef3c7;
            border: 2px solid #fbbf24;
            border-radius: 12px;
            padding: 20px;
            font-size: 15px;
            line-height: 1.7;
            color: #78350f;
            margin-bottom: 20px;
            font-weight: 500;
        }

        .imagen-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-radius: 12px;
            padding: 20px;
        }

        .caso-imagen {
            max-width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: contain;
        }

        /* Alternativas Card */
        .alternativas-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .alternativas-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 24px;
        }

        .alternativas-title i {
            color: #3b82f6;
            font-size: 24px;
        }

        .alternativas-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            margin-bottom: 24px;
        }

        .alternativa-item {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px;
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .alternativa-item::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: linear-gradient(180deg, #3b82f6, #8b5cf6);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .alternativa-item:hover {
            background: #f0f9ff;
            border-color: #3b82f6;
            transform: translateX(4px);
        }

        .alternativa-item:hover::before {
            transform: scaleY(1);
        }

        .alternativa-item.selected {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border-color: #3b82f6;
            box-shadow: 0 4px 20px rgba(59, 130, 246, 0.2);
        }

        .alternativa-item.selected::before {
            transform: scaleY(1);
        }

        .alternativa-radio {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 2px solid #d1d5db;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .alternativa-item:hover .alternativa-radio {
            border-color: #3b82f6;
        }

        .alternativa-item.selected .alternativa-radio {
            border-color: #3b82f6;
            background: #3b82f6;
        }

        .radio-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: white;
            transform: scale(0);
            transition: transform 0.3s ease;
        }

        .alternativa-item.selected .radio-dot {
            transform: scale(1);
        }

        .alternativa-content {
            flex: 1;
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .alternativa-letra {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            background: #3b82f6;
            color: white;
            font-weight: 700;
            border-radius: 8px;
            flex-shrink: 0;
            font-size: 16px;
        }

        .alternativa-texto {
            font-size: 15px;
            line-height: 1.6;
            color: #374151;
            font-weight: 500;
        }

        .alternativa-check {
            width: 28px;
            height: 28px;
            background: #10b981;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 14px;
            opacity: 0;
            transform: scale(0);
            transition: all 0.3s ease;
            flex-shrink: 0;
        }

        .alternativa-item.selected .alternativa-check {
            opacity: 1;
            transform: scale(1);
        }

        /* Botones */
        .btn-confirmar {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 32px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-confirmar:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-confirmar.disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* Grid Pregunta */
        .pregunta-grid {
            display: grid;
            grid-template-columns: 400px 1fr;
            gap: 24px;
        }

        .pregunta-imagen-card {
            background: white;
            border-radius: 16px;
            padding: 24px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            display: flex;
            flex-direction: column;
        }

        .pregunta-descripcion-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            border-radius: 10px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 16px;
            align-self: flex-start;
        }

        .pregunta-descripcion-texto {
            background: #f5f3ff;
            border: 2px solid #c4b5fd;
            border-radius: 12px;
            padding: 20px;
            font-size: 15px;
            line-height: 1.7;
            color: #5b21b6;
            margin-bottom: 20px;
            font-weight: 500;
        }

        /* Pregunta y Respuesta Card */
        .pregunta-respuesta-card {
            background: white;
            border-radius: 16px;
            padding: 32px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .pregunta-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 16px;
        }

        .pregunta-title i {
            color: #8b5cf6;
            font-size: 24px;
        }

        .pregunta-texto {
            font-size: 16px;
            line-height: 1.8;
            color: #374151;
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 500;
        }

        .respuesta-container {
            margin-bottom: 24px;
        }

        .respuesta-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 12px;
        }

        .respuesta-label i {
            color: #3b82f6;
        }

        .respuesta-textarea {
            width: 100%;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 15px;
            line-height: 1.7;
            color: #374151;
            resize: vertical;
            transition: all 0.3s ease;
            font-family: inherit;
        }

        .respuesta-textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .textarea-info {
            display: flex;
            justify-content: flex-end;
            margin-top: 8px;
        }

        .char-count {
            font-size: 13px;
            color: #6b7280;
        }

        .btn-enviar-respuesta {
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 16px 32px;
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .btn-enviar-respuesta:hover:not(.disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 28px rgba(139, 92, 246, 0.4);
            color: white;
        }

        .btn-enviar-respuesta.disabled {
            background: #e5e7eb;
            color: #9ca3af;
            cursor: not-allowed;
        }

        /* Sidebar de Avance */
        .avance-sidebar {
            position: sticky;
            top: 20px;
            height: fit-content;
        }

        .avance-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.1);
        }

        .avance-header {
            margin-bottom: 20px;
        }

        .avance-title {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 20px;
            font-weight: 800;
            color: #1f2937;
            margin: 0;
        }

        .avance-title i {
            color: #f59e0b;
        }

        /* Puntos Total Display */
        .puntos-total-display {
            background: linear-gradient(135deg, #fbbf24, #f59e0b);
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
            box-shadow: 0 8px 24px rgba(251, 191, 36, 0.3);
        }

        .puntos-icon {
            width: 56px;
            height: 56px;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            color: white;
            animation: rotate-trophy 3s ease infinite;
        }

        @keyframes rotate-trophy {
            0%, 100% { transform: rotate(0deg); }
            25% { transform: rotate(-10deg); }
            75% { transform: rotate(10deg); }
        }

        .puntos-info {
            display: flex;
            flex-direction: column;
        }

        .puntos-label {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.9);
            font-weight: 600;
        }

        .puntos-numero {
            font-size: 32px;
            font-weight: 900;
            color: white;
            line-height: 1;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-bottom: 20px;
        }

        .stat-mini {
            background: #f9fafb;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .stat-mini i {
            font-size: 24px;
            color: #3b82f6;
        }

        .stat-mini-value {
            display: block;
            font-size: 20px;
            font-weight: 800;
            color: #1f2937;
            line-height: 1;
        }

        .stat-mini-label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-top: 4px;
        }

        /* Lista de Avances */
        .avance-list {
            display: flex;
            flex-direction: column;
            gap: 16px;
            max-height: 500px;
            overflow-y: auto;
            padding-right: 8px;
        }

        .avance-list::-webkit-scrollbar {
            width: 6px;
        }

        .avance-list::-webkit-scrollbar-track {
            background: #f3f4f6;
            border-radius: 10px;
        }

        .avance-list::-webkit-scrollbar-thumb {
            background: #d1d5db;
            border-radius: 10px;
        }

        .avance-item {
            background: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            gap: 12px;
            animation: slideInRight 0.5s ease;
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .avance-numero {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 14px;
            flex-shrink: 0;
        }

        .avance-content {
            flex: 1;
        }

        .avance-item-title {
            font-size: 14px;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 8px 0;
        }

        .avance-opcion {
            font-size: 13px;
            color: #6b7280;
            margin: 0 0 8px 0;
        }

        .avance-respuesta {
            font-size: 12px;
            color: #6b7280;
            line-height: 1.5;
            margin: 0 0 8px 0;
            font-style: italic;
        }

        .avance-puntos {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            background: #fef3c7;
            color: #78350f;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
        }

        .avance-puntos.correcta {
            background: #d1fae5;
            color: #065f46;
        }

        .avance-puntos.incorrecta {
            background: #fee2e2;
            color: #991b1b;
        }

        .avance-empty {
            text-align: center;
            padding: 40px 20px;
            color: #9ca3af;
        }

        .avance-empty i {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .avance-empty p {
            font-size: 14px;
            margin: 0;
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .content-wrapper {
                grid-template-columns: 1fr;
            }

            .avance-sidebar {
                position: relative;
                top: 0;
            }

            .problema-alternativas-grid,
            .pregunta-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .caso-header {
                flex-direction: column;
                align-items: flex-start;
            }

            .tabs-navigation {
                padding: 20px;
            }

            .tab-item {
                min-width: 80px;
            }

            .tab-number {
                width: 48px;
                height: 48px;
                font-size: 16px;
            }

            .tab-label {
                font-size: 12px;
            }

            .caso-titulo {
                font-size: 24px;
            }
        }
    </style>

    <script>
        function timerCounter() {
            return {
                seconds: 0,
                interval: null,
                startTimer() {
                    this.interval = setInterval(() => {
                        this.seconds++;
                    }, 1000);
                },
                formatTime() {
                    const hours = Math.floor(this.seconds / 3600);
                    const minutes = Math.floor((this.seconds % 3600) / 60);
                    const secs = this.seconds % 60;

                    return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
                }
            }
        }
    </script>
</div>
