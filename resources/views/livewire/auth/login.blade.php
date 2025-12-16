<div>
    <form wire:submit.prevent="login" class="sign-in-form">
        <div class="d-block">
            <h2 class="font-weight-light">Iniciar Sesión.</h2>
        </div>
        <div class="input-field">
            <i class="fas fa-user"></i>
            <input type="text" class="@error('email') is-invalid @enderror" wire:model.defer="email" placeholder="Nombre de usuario" />
        </div>
        <div class="input-field">
            <i class="fas fa-lock"></i>
            <input type="password" id="passwordInput" class="@error('password') is-invalid @enderror" wire:model.defer="password" placeholder="Contraseña" />
            <span class="input-group-text cursor-pointer toggle-password" onclick="togglePassword()" style="cursor: pointer!important; position: absolute; right: 15px; top: 50%; transform: translateY(-50%);background: #F0F0F0; height: 20px">
                <i class="fa-solid fa-eye"></i>
            </span>
        </div>

        <div class="mt-3 d-block justify-content-between align-items-center">
            <div class="form-check form-check-flat form-check-primary">
                <label class="form-check-label">
                    <input type="checkbox" class="form-check-input" wire:model="remember">
                    Recordar sesión
                    <i class="input-helper"></i></label>
            </div>

            <button type="submit" class="btn" >INICIAR SESIÓN</button>
        </div>

        <div class="mt-3 mb-3">
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible show fade">
                    {{ session('error') }}
                </div>
            @endif
        </div>

        <div class="mb-3 mt-3">
            @if (session()->has('status'))
                <div class="alert alert-success alert-dismissible show fade">
                    {{ session('status') }}
                </div>
            @endif
        </div>
    </form>








{{--    <form wire:submit.prevent="login" class="sign-in-form">--}}
{{--        @csrf--}}
{{--        <div class="form-group">--}}
{{--            <input type="text"  class="form-control form-control-lg   @error('email') is-invalid @enderror " wire:model.defer="email"  autofocus id="email" placeholder="Nombre de usuario o correo electrónico">--}}
{{--            @error('email')--}}
{{--                <span class="invalid-feedback" role="alert">--}}
{{--                    <strong>{{ $message }}</strong>--}}
{{--                </span>--}}
{{--            @enderror--}}
{{--        </div>--}}
{{--        <div class="form-group">--}}
{{--            <div class="input-group input-group-merge has-validation">--}}
{{--                <input type="password" class="form-control form-control-lg   @error('password') is-invalid @enderror" wire:model.defer="password"  id="password" placeholder="Contraseña">--}}
{{--                <span class="input-group-text cursor-pointer toggle-password bg-white" style="cursor: pointer!important;">--}}
{{--                    <i class="fa-solid fa-eye"></i>--}}
{{--                </span>--}}
{{--            </div>--}}
{{--            @error('password')--}}
{{--            <span class="invalid-feedback" role="alert">--}}
{{--                        <strong>{{ $message }}</strong>--}}
{{--                    </span>--}}
{{--            @enderror--}}
{{--        </div>--}}


{{--        @if (session()->has('error'))--}}
{{--            <div class="alert alert-danger">--}}
{{--                {{ session('error') }}--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        @if (session()->has('status'))--}}
{{--            <div class="alert alert-success">--}}
{{--                {{ session('status') }}--}}
{{--            </div>--}}
{{--        @endif--}}

{{--        <div class="mt-3">--}}
{{--            <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >INICIAR SESIÓN</button>--}}
{{--        </div>--}}

{{--        <div class="my-2 d-flex justify-content-between align-items-center">--}}

{{--            <div class="form-check form-check-flat form-check-primary">--}}
{{--                <label class="form-check-label">--}}
{{--                    <input type="checkbox" class="form-check-input" wire:model="remember">--}}
{{--                    Recordar sesión--}}
{{--                    <i class="input-helper"></i></label>--}}
{{--            </div>--}}
{{--            <a href="{{route('password.request')}}" class="auth-link text-black">¿Has olvidado tu contraseña?</a>--}}
{{--        </div>--}}
{{--    </form>--}}
{{--    <style>--}}
{{--        .border_ra{--}}
{{--            border-radius: 15px!important;--}}
{{--        }--}}
{{--    </style>--}}
</div>

@assets
    <script src="{{asset('js/domain.js')}}"></script>
@endassets
@script
    <script>
        $wire.on('redirectAfterSuccess', function (url) {
            setTimeout(function() {
                window.location.href = url;
            }, 2000);
        });
    </script>
@endscript
