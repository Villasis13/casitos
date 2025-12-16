<div>

    <!-- DISEÑO 1: Cards Elevadas con Iconos Animados -->
    <div class="row mb-4 mt-1 g-4">
        <div class="col-lg-6 col-sm-12 mb-3">
            <h3 class="career-title-line">{{ $carrera_nombre }}</h3>
        </div>
        <div class="col-lg-6 col-sm-12 mb-3 text-end">
            <a href="{{ route('Portalusuarios.usuarios_vista') }}">Carreras</a> / Especialidades
        </div>
        @foreach($listar_especialidades as $especialidad)
            <div class="col-lg-4 col-md-4 col-sm-12">
                <div class="specialty-card">
                    <div class="specialty-icon-container">
                        <div class="specialty-icon">
                            <img src="{{ asset($especialidad->especialidad_imagen) }}">
                        </div>
                        <div class="specialty-particles">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                    <h4 class="specialty-title">{{ $especialidad->especialidad_nombre }}</h4>
                    <p class="specialty-description">{{ $especialidad->especialidad_descripcion }}</p>
                    <a href="{{route('Portalusuarios.categorias_users',['id_especialidad'=>base64_encode($especialidad->id_especialidad)])}}" class="btn-specialty">
                        <span>Explorar categorías</span>
                        <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        /* DISEÑO 1: Cards Elevadas con Iconos Animados */
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

        .specialty-card {
            background: white;
            border-radius: 20px;
            padding: 32px 24px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
            overflow: hidden;
            border: 2px solid transparent;
        }

        .specialty-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.05), transparent);
            transition: left 0.6s ease;
        }

        .specialty-card:hover::before {
            left: 100%;
        }

        .specialty-card:hover {
            transform: translateY(-12px);
            box-shadow: 0 20px 40px rgba(59, 130, 246, 0.2);
            border-color: #3b82f6;
        }

        .specialty-icon-container {
            position: relative;
            margin-bottom: 24px;
        }

        .specialty-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 100%);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 32px;
            transition: all 0.4s ease;
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
        }

        .specialty-icon img {
            width: 80%;
        }

        .specialty-card:hover .specialty-icon {
            transform: scale(1.1) rotate(10deg);
            box-shadow: 0 12px 30px rgba(59, 130, 246, 0.4);
        }

        .specialty-particles {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            height: 100%;
        }

        .specialty-particles span {
            position: absolute;
            width: 8px;
            height: 8px;
            background: #3b82f6;
            border-radius: 50%;
            opacity: 0;
            transition: all 0.6s ease;
        }

        .specialty-particles span:nth-child(1) {
            top: 0;
            left: 50%;
        }

        .specialty-particles span:nth-child(2) {
            top: 50%;
            left: 0;
        }

        .specialty-particles span:nth-child(3) {
            top: 50%;
            right: 0;
        }

        .specialty-card:hover .specialty-particles span {
            opacity: 1;
        }

        .specialty-card:hover .specialty-particles span:nth-child(1) {
            transform: translateY(-30px);
        }

        .specialty-card:hover .specialty-particles span:nth-child(2) {
            transform: translateX(-30px);
        }

        .specialty-card:hover .specialty-particles span:nth-child(3) {
            transform: translateX(30px);
        }

        .specialty-title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .specialty-description {
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
            flex-grow: 1;
        }

        .btn-specialty {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
        }

        .btn-specialty:hover {
            background: #2563eb;
            transform: scale(1.05);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
            color: white;
        }

        .btn-specialty i {
            transition: transform 0.3s ease;
        }

        .btn-specialty:hover i {
            transform: translateX(4px);
        }
    </style>

</div>
