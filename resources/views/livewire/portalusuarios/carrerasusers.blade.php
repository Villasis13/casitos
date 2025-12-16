<div>

    <!-- DISEÑO 1: Grid con Cards Modernas (Recomendado) -->
    <div class="row mb-4 g-4">
        <div class="col-lg-12 col-sm-12 mb-2 text-center">
            <div class="title-divider">
                <span>CARRERAS</span>
            </div>
        </div>
        @foreach($listar_carreras as $carrera)
            <div class="col-lg-4 col-md-6 col-sm-12">
                <div class="career-card">
                    <div class="career-icon">
{{--                        <i class="fas fa-graduation-cap"></i>--}}
                        <img src="{{ asset($carrera->carrera_imagen) }}">
                    </div>
                    <h3 class="career-title">{{ $carrera->carrera_nombre }}</h3>
                    <p class="career-description">{{ $carrera->carrera_descripcion }}</p>
                    <a class="btn btn-explore" href="{{route('Portalusuarios.especialidades_users',['id_carrera'=>base64_encode($carrera->id_carrera)])}}">
                        Explorar <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        /* DISEÑO 1: Grid con Cards Modernas */
        .title-divider{
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
            color: #1f2937;
            font-weight: 800;
            letter-spacing: 2px;
            font-size: 25px;
        }

        .title-divider::before,
        .title-divider::after{
            content: "";
            flex: 1;
            max-width: 120px;
            height: 2px;
            background: #789ac6;
        }

        .career-card {
            background: white;
            border-radius: 16px;
            padding: 32px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            height: 100%;
            display: flex;
            flex-direction: column;
            border: 1px solid #e5e7eb;
        }

        .career-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 28px rgba(0,0,0,0.15);
            border-color: #528ff6;
        }

        .career-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #6299ed 0%, #adc4f1 100%);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            transition: transform 0.3s ease;
        }

        .career-card:hover .career-icon {
            transform: scale(1.1) rotate(5deg);
        }

        .career-icon img {
            width: 90%;
        }

        .career-title {
            font-size: 22px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 12px;
            line-height: 1.3;
        }

        .career-description {
            color: #6b7280;
            font-size: 15px;
            line-height: 1.6;
            margin-bottom: 24px;
            flex-grow: 1;
        }

        .btn-explore {
            width: 100%;
            padding: 12px 24px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-explore:hover {
            background: #2563eb;
            transform: scale(1.02);
            color: whitesmoke;
        }

        .btn-explore i {
            transition: transform 0.3s ease;
        }

        .btn-explore:hover i {
            transform: translateX(4px);
        }
    </style>

</div>
