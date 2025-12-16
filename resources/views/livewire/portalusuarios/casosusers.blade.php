<div>

    <!-- DISEÑO 3: Cards con Indicador de Progreso Individual -->
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
                    <span class="stat-number">{{  $total_casos }}</span>
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

    <style>
        /* DISEÑO 3: Cards con Indicador de Progreso Individual */
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

        @media (max-width: 768px) {
            .stats-header {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>

</div>
