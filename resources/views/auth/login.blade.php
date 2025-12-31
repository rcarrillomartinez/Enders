@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --text-main: #0f172a;
        --error-red: #ef4444;
    }

    .login-wrapper {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .login-container {
        width: 100%;
        max-width: 450px;
    }

    /* Animación de sacudida si hay errores */
    .shake-error {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }

    @keyframes shake {
        10%, 90% { transform: translate3d(-1px, 0, 0); }
        20%, 80% { transform: translate3d(2px, 0, 0); }
        30%, 50%, 70% { transform: translate3d(-4px, 0, 0); }
        40%, 60% { transform: translate3d(4px, 0, 0); }
    }

    .glass-card-login {
        background: #ffffff;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .brand-header-login {
        background: var(--brand-navy);
        padding: 3rem 2rem;
        text-align: center;
    }

    .brand-header-login h3 {
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 1px;
    }

    .input-group-premium {
        display: flex;
        border: 1px solid #cbd5e1;
        border-radius: 12px;
        transition: all 0.3s ease;
        overflow: hidden;
        background: #ffffff;
        position: relative;
        align-items: center;
    }

    .input-group-premium:focus-within {
        border-color: var(--brand-navy);
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08);
    }

    /* Borde rojo cuando hay error */
    .input-error {
        border-color: var(--error-red) !important;
    }

    .input-premium {
        border: none !important;
        padding: 0.75rem 1rem;
        flex: 1;
        outline: none !important;
        box-shadow: none !important;
    }

    /* Estilo del ojo */
    .toggle-password-eye {
        position: absolute;
        right: 15px;
        cursor: pointer;
        color: #94a3b8;
        transition: color 0.2s;
        z-index: 10;
    }

    .toggle-password-eye:hover { color: var(--brand-navy); }

    .alert-premium {
        background-color: #fef2f2;
        border: 1px solid #fee2e2;
        color: #991b1b;
        padding: 0.8rem 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        font-size: 0.85rem;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .btn-submit-premium {
        background-color: var(--brand-navy) !important;
        color: #ffffff !important;
        border: none;
        padding: 1.1rem;
        border-radius: 14px;
        font-weight: 600;
        width: 100%;
        margin-top: 1rem;
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }

    .btn-submit-premium:hover {
        background-color: var(--brand-hover) !important;
        transform: translateY(-2px);
    }

    .register-link {
        color: var(--brand-navy);
        font-weight: 700;
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .register-link:hover { transform: scale(1.05); display: inline-block; }

    .field-transition { animation: slideIn 0.3s ease forwards; }
    @keyframes slideIn {
        from { opacity: 0; transform: translateY(-5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<div class="login-wrapper">
    <div class="login-container {{ $errors->any() ? 'shake-error' : '' }}">
        <div class="glass-card-login">
            <div class="brand-header-login">
                <div class="bg-white bg-opacity-10 d-inline-flex p-3 rounded-circle mb-3">
                    <i class="fas fa-lock fa-2x text-white" id="lock-icon"></i>
                </div>
                <h3>Acceso Enders</h3>
                <p class="mb-0 text-white-50">Ingresa tus credenciales oficiales</p>
            </div>

            <div class="p-4 p-md-5">
                {{-- MOSTRAR ERRORES DE LARAVEL --}}
                @if ($errors->any())
                    <div class="alert-premium">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Credenciales incorrectas o datos inválidos.</span>
                    </div>
                @endif

                <form action="{{ route('login.post') }}" method="POST" id="loginForm">
                    @csrf

                    <div class="mb-4">
                        <label class="form-label-premium">Tipo de Perfil</label>
                        <div class="input-group-premium">
                            <span class="input-group-text-premium px-3"><i class="fas fa-id-badge"></i></span>
                            <select class="form-select input-premium" id="user_type" name="user_type" required>
                                <option value="" selected disabled>Selecciona perfil...</option>
                                <option value="viajero" {{ old('user_type') == 'viajero' ? 'selected' : '' }}>Viajero</option>
                                <option value="hotel" {{ old('user_type') == 'hotel' ? 'selected' : '' }}>Hotel</option>
                                <option value="admin" {{ old('user_type') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                        </div>
                    </div>

                    {{-- Campo Email (Viajero/Admin) --}}
                    <div class="mb-4" id="email-field" style="display:none;">
                        <label class="form-label-premium">Correo Electrónico</label>
                        <div class="input-group-premium {{ $errors->has('email') ? 'input-error' : '' }}">
                            <span class="input-group-text-premium px-3"><i class="fas fa-envelope"></i></span>
                            <input type="email" class="form-control input-premium" name="email" value="{{ old('email') }}" placeholder="ejemplo@correo.com">
                        </div>
                    </div>

                    {{-- Campo Usuario (Hotel) --}}
                    <div class="mb-4" id="usuario-field" style="display:none;">
                        <label class="form-label-premium">Nombre de Usuario</label>
                        <div class="input-group-premium {{ $errors->has('usuario') ? 'input-error' : '' }}">
                            <span class="input-group-text-premium px-3"><i class="fas fa-user-tag"></i></span>
                            <input type="text" class="form-control input-premium" name="usuario" value="{{ old('usuario') }}" placeholder="Usuario hotelero">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label-premium">Contraseña</label>
                        <div class="input-group-premium {{ $errors->any() ? 'input-error' : '' }}">
                            <span class="input-group-text-premium px-3"><i class="fas fa-key"></i></span>
                            <input type="password" class="form-control input-premium" id="password" name="password" required placeholder="••••••••">
                            <i class="fas fa-eye toggle-password-eye" id="toggleLoginPass"></i>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-premium">
                        <span>ENTRAR AL PANEL</span>
                    </button>
                </form>

                <div class="mt-5 text-center">
                    <p class="text-muted small">
                        ¿No tienes una cuenta activa? <br>
                        <a href="{{ route('auth.register') }}" class="register-link mt-2">REGÍSTRATE AHORA</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    const userType = document.getElementById('user_type');
    const emailField = document.getElementById('email-field');
    const usuarioField = document.getElementById('usuario-field');
    const passwordInput = document.getElementById('password');
    const togglePass = document.getElementById('toggleLoginPass');
    const lockIcon = document.getElementById('lock-icon');

    // 1. Mostrar/Ocultar campos dinámicamente
    function handleFields() {
        if (userType.value === 'hotel') {
            emailField.style.display = 'none';
            usuarioField.style.display = 'block';
            usuarioField.classList.add('field-transition');
        } else if (userType.value) {
            emailField.style.display = 'block';
            usuarioField.style.display = 'none';
            emailField.classList.add('field-transition');
        }
    }

    userType.addEventListener('change', handleFields);
    window.onload = handleFields; // Para que persista si hay error

    // 2. Lógica del OJO
    togglePass.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        this.classList.toggle('fa-eye');
        this.classList.toggle('fa-eye-slash');
        
        // Efecto visual: Abrir candado superior si se ve la contraseña
        lockIcon.className = isPassword ? 'fas fa-lock-open fa-2x text-white' : 'fas fa-lock fa-2x text-white';
    });
</script>
@endsection
@endsection