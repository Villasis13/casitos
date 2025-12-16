<div>

    <style>
        .career-title-line{
            position:relative;
            margin:0;
            padding-left:14px;
            font-weight:800;
            color:#111827;
        }
        .career-title-line::before{
            content:"";
            position:absolute;
            left:0;
            top:50%;
            transform:translateY(-50%);
            width:5px;
            height:30px;
            border-radius:10px;
            background:#3b82f6;
        }

        /* ===================================
           DISEÑO 2: Tabs Pills con Animación
           =================================== */
        .tabs-design-2 {
            margin-bottom: 40px;
        }

        .tabs-scroll-container-2 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        }

        .tabs-pills-wrapper-2 {
            display: flex;
            gap: 10px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .tab-pill-2 {
            position: relative;
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            padding: 12px 24px;
            color: white;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            margin: 0px 5px;
        }

        .tab-pill-2::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 0;
            height: 0;
            background: white;
            border-radius: 50%;
            transform: translate(-50%, -50%);
            transition: all 0.5s ease;
        }

        .tab-pill-2:hover {
            transform: scale(1.05);
            border-color: rgba(255, 255, 255, 0.5);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
        }

        .tab-pill-2:hover::before {
            width: 300px;
            height: 300px;
            opacity: 0.1;
        }

        .tab-pill-2.active {
            background: white;
            border-color: white;
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.25);
        }

        .pill-content-2 {
            position: relative;
            z-index: 1;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .tab-pill-2.active .pill-content-2 {
            color: #667eea;
        }

        .pill-icon-2 {
            font-size: 16px;
            transition: transform 0.3s ease;
        }

        .tab-pill-2:hover .pill-icon-2 {
            transform: rotate(15deg) scale(1.2);
        }

        .tab-pill-2.small {
            padding: 8px 18px;
            font-size: 14px;
        }

        .tabs-secondary-row-2 {
            display: flex;
            gap: 8px;
            margin-top: 16px;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ===================================
           Estado Vacío
           =================================== */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        }

        .empty-icon {
            width: 120px;
            height: 120px;
            margin: 0 auto 24px;
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #9ca3af;
        }

        .empty-title {
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .empty-text {
            font-size: 16px;
            color: #6b7280;
            max-width: 400px;
            margin: 0 auto;
        }

        /* ===================================
           DISEÑO DE CASOS (tu diseño existente)
           =================================== */
        .cases-progress-individual {
            margin-bottom: 32px;
        }

        .stats-header {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }

        .stat-box {
            background: white;
            border-radius: 16px;
            padding: 20px;
            display: flex;
            align-items: center;
            gap: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.06);
            transition: all 0.3s ease;
            border-left: 4px solid #3b82f6;
        }

        .stat-box:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        }

        .stat-box.stat-success {
            border-left-color: #10b981;
        }

        .stat-box.stat-pending {
            border-left-color: #f59e0b;
        }

        .stat-box.stat-progress {
            border-left-color: #8b5cf6;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            color: white;
            flex-shrink: 0;
        }

        .stat-success .stat-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stat-pending .stat-icon {
            background: linear-gradient(135deg, #f59e0b, #d97706);
        }

        .stat-progress .stat-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .stat-info {
            display: flex;
            flex-direction: column;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 800;
            color: #1f2937;
            line-height: 1;
        }

        .stat-label {
            font-size: 13px;
            color: #6b7280;
            margin-top: 4px;
        }

        .case-progress-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.4s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .case-progress-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 16px 40px rgba(59, 130, 246, 0.2);
        }

        .case-progress-bar {
            height: 6px;
            background: #f3f4f6;
            position: relative;
            overflow: hidden;
        }

        .progress-indicator {
            height: 100%;
            width: 0%;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6);
            transition: width 1s ease;
        }

        .case-progress-card:hover .progress-indicator {
            width: 100%;
        }

        .case-body-progress {
            padding: 24px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .case-header-progress {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 16px;
        }

        .case-number-tag {
            padding: 6px 12px;
            background: #eff6ff;
            color: #3b82f6;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .case-status-dot {
            width: 12px;
            height: 12px;
            background: #10b981;
            border-radius: 50%;
            animation: pulse-status 2s ease infinite;
        }

        @keyframes pulse-status {
            0%, 100% {
                box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
            }
            50% {
                box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
            }
        }

        .case-title-progress {
            font-size: 20px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
        }

        .case-objective-progress {
            color: #6b7280;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        .case-actions {
            margin-top: auto;
        }

        .btn-init-case {
            display: inline-flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 14px 24px;
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            justify-content: center;
        }

        .btn-init-case:hover {
            transform: scale(1.02);
            box-shadow: 0 8px 24px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-icon-wrapper {
            width: 32px;
            height: 32px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.3s ease;
        }

        .btn-init-case:hover .btn-icon-wrapper {
            transform: scale(1.2);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .stats-header {
                grid-template-columns: repeat(2, 1fr);
            }

            .tabs-wrapper-1,
            .tabs-pills-wrapper-2 {
                gap: 8px;
            }

            .tab-item-1,
            .tab-pill-2 {
                padding: 10px 16px;
                font-size: 14px;
            }

            .tabs-grid-3 {
                grid-template-columns: repeat(2, 1fr);
                gap: 12px;
            }
        }
    </style>

    <div class="row">
        <div class="col-lg-6 col-sm-12 mb-3">
            <h3 class="career-title-line">{{ $especialidad_nombre }}</h3>
        </div>
        <div class="col-lg-6 col-sm-6 mb-3 text-end">
            <a href="{{ route('Portalusuarios.usuarios_vista') }}">Carreras</a> / <a href="{{ route('Portalusuarios.especialidades_users', ['id_carrera'=>base64_encode($id_carrera)]) }}">Especialidades</a> / Categorías
        </div>
    </div>

    <!-- DISEÑO 2: Tabs Pills con Animación -->
    <div class="tabs-design-2 mb-5" syle="display: none;">
        <div class="tabs-scroll-container-2">
            <div class="tabs-pills-wrapper-2">
                @foreach($listar_categorias as $index => $categoria)
                    @if($index < 6)
                        <button
                            wire:click="$set('id_categoria', '{{ $categoria->id_categoria }}')"
                            class="tab-pill-2 {{ $id_categoria == $categoria->id_categoria ? 'active' : '' }}">
                            <div class="pill-content-2">
                                <i class="fas fa-tag pill-icon-2"></i>
                                <span class="pill-text-2">{{ $categoria->categoria_nombre }}</span>
                            </div>
                        </button>
                    @endif
                @endforeach
            </div>
        </div>

        @if(count($listar_categorias) > 6)
            <div class="tabs-secondary-row-2">
                @foreach($listar_categorias as $index => $categoria)
                    @if($index >= 6)
                        <button
                            wire:click="$set('id_categoria', '{{ $categoria->id_categoria }}')"
                            class="tab-pill-2 small {{ $id_categoria == $categoria->id_categoria ? 'active' : '' }}">
                            <div class="pill-content-2">
                                <i class="fas fa-tag pill-icon-2"></i>
                                <span class="pill-text-2">{{ $categoria->categoria_nombre }}</span>
                            </div>
                        </button>
                    @endif
                @endforeach
            </div>
        @endif
    </div>


    <!-- Contenedor de Casos -->
    @if($id_categoria)
        <div class="casos-container">
            @if(count($listar_casos) > 0)
                <!-- DISEÑO DE CASOS (tu diseño existente) -->
                <div class="cases-progress-individual mb-4">
                    <!-- Header con estadísticas -->
                    <div class="stats-header">
                        <div class="stat-box">
                            <div class="stat-icon">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ $total_casos }}</span>
                                <span class="stat-label">Total Casos</span>
                            </div>
                        </div>
                        <div class="stat-box stat-success">
                            <div class="stat-icon">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">20</span>
                                <span class="stat-label">Resueltos</span>
                            </div>
                        </div>
                        <div class="stat-box stat-pending">
                            <div class="stat-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">{{ $total_casos }}</span>
                                <span class="stat-label">Pendientes</span>
                            </div>
                        </div>
                        <div class="stat-box stat-progress">
                            <div class="stat-icon">
                                <i class="fas fa-chart-pie"></i>
                            </div>
                            <div class="stat-info">
                                <span class="stat-number">20%</span>
                                <span class="stat-label">Completado</span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid de casos con progreso -->
                    <div class="row g-4 mt-3">
                        @foreach($listar_casos as $caso)
                            <div class="col-lg-6 col-md-6 col-sm-12">
                                <div class="case-progress-card">
                                    <div class="case-progress-bar">
                                        <div class="progress-indicator"></div>
                                    </div>
                                    <div class="case-body-progress">
                                        <div class="case-header-progress">
                                            <span class="case-number-tag">CASO {{ $loop->iteration }}</span>
                                            <div class="case-status-dot"></div>
                                        </div>
                                        <h4 class="case-title-progress">{{ $caso->caso_titulo }}</h4>
                                        <p class="case-objective-progress">{{ $caso->caso_objetivo }}</p>
                                        <div class="case-actions">
                                            <a href="{{route('Portalusuarios.iniciar_caso',['id_caso'=>base64_encode($caso->id_caso)])}}" class="btn-init-case">
                                                <div class="btn-icon-wrapper">
                                                    <i class="fas fa-rocket"></i>
                                                </div>
                                                <span>Iniciar Caso</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <!-- Mensaje cuando no hay casos -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-inbox"></i>
                    </div>
                    <h3 class="empty-title">No hay casos disponibles</h3>
                    <p class="empty-text">Esta categoría no tiene casos para resolver en este momento.</p>
                </div>
            @endif
        </div>
    @endif
</div>

