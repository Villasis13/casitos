@extends('layouts.auth_template')
@section('title','Iniciar Sesión')
@section('content')

    <style>
        .conttainer {
            position: relative;
            width: 100%;
            background-color: #fff;
            min-height: 100vh;
            overflow: hidden;
        }

        .forms-container {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
        }

        .signin {
            position: absolute;
            top: 70%;
            left: 75%;
            /*left: 50%;*/
            transform: translate(-50%, -50%);
            width: 50%;
            transition: 1s 0.7s ease-in-out;
            display: grid;
            grid-template-columns: 1fr;
            z-index: 5;
        }

        form {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0rem 5rem;
            transition: all 0.2s 0.7s;
            overflow: hidden;
            grid-column: 1 / 2;
            grid-row: 1 / 2;
        }

        /* ✅ estas clases las usaremos en los formularios de Livewire */
        form.sign-up-form {
            opacity: 0;
            z-index: 1;
        }

        form.sign-in-form {
            z-index: 2;
        }

        .title {
            font-size: 2.2rem;
            color: #444;
            margin-bottom: 10px;
        }

        .input-field {
            max-width: 380px;
            width: 100%;
            background-color: #f0f0f0;
            margin: 10px 0;
            height: 55px;
            border-radius: 55px;
            display: grid;
            grid-template-columns: 15% 85%;
            padding: 0 0.4rem;
            position: relative;
        }

        .input-field i {
            text-align: center;
            line-height: 55px;
            color: #acacac;
            transition: 0.5s;
            font-size: 1.1rem;
        }

        .input-field input {
            background: none;
            outline: none;
            border: none;
            line-height: 1;
            font-weight: 600;
            font-size: 1.1rem;
            color: #333;
        }

        .input-field input::placeholder {
            color: #aaa;
            font-weight: 500;
        }

        .social-text {
            padding: 0.7rem 0;
            font-size: 1rem;
        }

        .social-media {
            display: flex;
            justify-content: center;
        }

        .social-icon {
            height: 46px;
            width: 46px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 0.45rem;
            color: #333;
            border-radius: 50%;
            border: 1px solid #333;
            text-decoration: none;
            font-size: 1.1rem;
            transition: 0.3s;
        }

        .social-icon:hover {
            color: #4481eb;
            border-color: #4481eb;
        }

        .btn {
            width: 190px;
            background-color: #5995fd;
            border: none;
            outline: none;
            height: 49px;
            border-radius: 49px;
            color: #fff;
            text-transform: uppercase;
            font-weight: 600;
            margin: 10px 0;
            cursor: pointer;
            transition: 0.5s;
        }

        .btn:hover {
            background-color: #4d84e2;
        }
        .panels-container {
            position: absolute;
            height: 100%;
            width: 100%;
            top: 0;
            left: 0;
            display: grid;
            grid-template-columns: repeat(2, 1fr);
        }

        .conttainer:before {
            content: "";
            position: absolute;
            height: 2000px;
            width: 2000px;
            top: -10%;
            right: 48%;
            transform: translateY(-50%);
            background-image: linear-gradient(-45deg, #4481eb 0%, #04befe 100%);
            transition: 1.8s ease-in-out;
            border-radius: 50%;
            z-index: 6;
        }

        .image {
            width: 100%;
            transition: transform 1.1s ease-in-out;
            transition-delay: 0.4s;
        }

        .panel {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            justify-content: space-around;
            text-align: center;
            z-index: 6;
        }

        .left-panel {
            pointer-events: all;
            padding: 3rem 17% 2rem 12%;
            align-items: center;
        }

        .right-panel {
            pointer-events: none;
            padding: 3rem 12% 2rem 17%;
        }

        .panel .content {
            color: #fff;
            transition: transform 0.9s ease-in-out;
            transition-delay: 0.6s;
        }

        .panel h3 {
            font-weight: 600;
            line-height: 1;
            font-size: 1.5rem;
        }

        .panel p {
            font-size: 0.95rem;
            padding: 0.7rem 0;
        }

        .btn.transparent {
            margin: 0;
            background: none;
            border: 2px solid #fff;
            width: 170px;
            height: 41px;
            font-weight: 600;
            font-size: 0.8rem;
        }

        .right-panel .image,
        .right-panel .content {
            transform: translateX(800px);
        }

        /* ANIMATION */

        .conttainer.sign-up-mode:before {
            transform: translate(100%, -50%);
            right: 52%;
        }

        .conttainer.sign-up-mode .left-panel .image,
        .conttainer.sign-up-mode .left-panel .content {
            transform: translateX(-800px);
        }

        /* ❌ Antes movías el formulario a la izquierda.
           Lo quitamos para que SIEMPRE esté centrado. */
        .conttainer.sign-up-mode .signin {
            left: 25%;
        }

        .conttainer.sign-up-mode form.sign-up-form {
            position: relative;
            bottom: 75%;
            opacity: 1;
            z-index: 2;
        }

        .conttainer.sign-up-mode form.sign-in-form {
            opacity: 0;
            z-index: 1;
        }

        .conttainer.sign-up-mode .right-panel .image,
        .conttainer.sign-up-mode .right-panel .content {
            transform: translateX(0%);
        }

        .conttainer.sign-up-mode .left-panel {
            pointer-events: none;
        }

        .conttainer.sign-up-mode .right-panel {
            pointer-events: all;
        }

        @media (max-width: 870px) {
            .conttainer {
                min-height: 800px;
                height: 100vh;
            }
            .signin {
                width: 100%;
                top: 95%;
                transform: translate(-50%, -100%);
                transition: 1s 0.8s ease-in-out;
            }

            .signin,
            .conttainer.sign-up-mode .signin {
                left: 50%;
            }

            .panels-container {
                grid-template-columns: 1fr;
                grid-template-rows: 1fr 2fr 1fr;
            }

            .panel {
                flex-direction: row;
                justify-content: space-around;
                align-items: center;
                padding: 2.5rem 8%;
                grid-column: 1 / 2;
            }

            .right-panel {
                grid-row: 3 / 4;
            }

            .left-panel {
                grid-row: 1 / 2;
            }

            .image {
                width: 200px;
                transition: transform 0.9s ease-in-out;
                transition-delay: 0.6s;
            }

            .panel .content {
                padding-right: 15%;
                transition: transform 0.9s ease-in-out;
                transition-delay: 0.8s;
            }

            .panel h3 {
                font-size: 1.2rem;
            }

            .panel p {
                font-size: 0.7rem;
                padding: 0.5rem 0;
            }

            .btn.transparent {
                width: 110px;
                height: 35px;
                font-size: 0.7rem;
            }

            .conttainer:before {
                width: 1500px;
                height: 1500px;
                transform: translateX(-50%);
                left: 30%;
                bottom: 68%;
                right: initial;
                top: initial;
                transition: 2s ease-in-out;
            }

            .conttainer.sign-up-mode:before {
                transform: translate(-50%, 100%);
                bottom: 32%;
                right: initial;
            }

            .conttainer.sign-up-mode .left-panel .image,
            .conttainer.sign-up-mode .left-panel .content {
                transform: translateY(-300px);
            }

            .conttainer.sign-up-mode .right-panel .image,
            .conttainer.sign-up-mode .right-panel .content {
                transform: translateY(0px);
            }

            .right-panel .image,
            .right-panel .content {
                transform: translateY(300px);
            }

            .conttainer.sign-up-mode .signin {
                top: 5%;
                transform: translate(-50%, 0);
            }
        }

        @media (max-width: 570px) {
            form {
                padding: 0 1.5rem;
            }

            .image {
                display: none;
            }
            .panel .content {
                padding: 0.5rem 1rem;
            }
            .conttainer {
                padding: 1.5rem;
            }

            .conttainer:before {
                bottom: 72%;
                left: 50%;
            }

            .conttainer.sign-up-mode:before {
                bottom: 28%;
                left: 50%;
            }
        }
    </style>

    <div class="conttainer">
        <div class="forms-container">
            <div class="signin">
                {{-- ✅ ambos componentes se renderizan aquí,
                     el CSS se encarga de mostrar solo uno según el modo --}}
                @livewire('auth.login')
                @livewire('auth.crearusers')
            </div>
        </div>

        <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h3>¿Nuevo por aquí?</h3>
                    <p>
                        Chato crea tu cuenta p.
                    </p>
                    <button class="btn transparent" id="sign-up-btn">
                        Crear cuenta
                    </button>
                </div>
                <img src="{{ asset('log.svg') }}" class="image" alt="" />
            </div>
            <div class="panel right-panel">
                <div class="content">
                    <h3>¿Ya eres parte?</h3>
                    <p>
                        Inicia sesión con tu cuenta para continuar donde lo dejaste.
                    </p>
                    <button class="btn transparent" id="sign-in-btn">
                        Iniciar sesión
                    </button>
                </div>
                <img src="{{ asset('register.svg')}}" class="image" alt="" />
            </div>
        </div>
    </div>

    <script>
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".conttainer");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });
    </script>
@endsection
