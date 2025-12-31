@extends('layouts.app')

@section('title', 'Registro')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --text-main: #0f172a;
        --border-color: #e2e8f0;
        --success-color: #22c55e;
        --error-color: #ef4444;
    }

    body { 
        background-color: #f8fafc; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .register-wrapper {
        min-height: 90vh;
        padding: 3rem 1rem;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .glass-card-register {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid var(--border-color);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        width: 100%;
        max-width: 700px;
    }

    .brand-header-register {
        background: var(--brand-navy);
        padding: 2rem;
        text-align: center;
        color: white;
    }

    .brand-header-register h3 {
        font-weight: 800;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .form-container {
        padding: 2.5rem;
    }

    .form-label {
        font-weight: 700;
        color: var(--brand-navy);
        font-size: 0.85rem;
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-label i {
        color: #64748b;
        font-size: 0.9rem;
    }

    .password-wrapper {
        position: relative;
    }

    .password-toggle {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #64748b;
        z-index: 10;
        transition: color 0.2s;
    }

    .password-toggle:hover {
        color: var(--brand-navy);
    }

    /* Validación visual */
    .valido {
        border-color: var(--success-color) !important;
        box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1) !important;
    }

    .invalido {
        border-color: var(--error-color) !important;
        box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1) !important;
    }

    .field-error-msg {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--error-color);
        margin-top: 5px;
        display: none; /* Se muestra via JS */
    }

    .pass-feedback {
        font-size: 0.75rem;
        font-weight: 600;
        margin-top: 5px;
        display: block;
        min-height: 1rem;
    }

    .form-control {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 1px solid var(--border-color);
        background-color: #f8fafc;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--brand-navy);
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.1);
        background-color: white;
    }

    .btn-register-submit {
        background-color: var(--brand-navy);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
        margin-top: 1rem;
    }

    .btn-register-submit:hover {
        background-color: var(--brand-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .section-divider {
        height: 1px;
        background: var(--border-color);
        margin: 2rem 0;
        position: relative;
    }

    .section-divider span {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 0 1rem;
        color: #94a3b8;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .login-link {
        color: var(--brand-navy);
        text-decoration: none;
        font-weight: 800;
        display: inline-block;
    }

    .login-link:hover {
        color: var(--brand-navy);
        text-decoration: underline;
    }

    .sweep-animate {
        animation: sweepIn 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards;
    }

    @keyframes sweepIn { 
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); } 
    }
</style>

<div class="register-wrapper">
    <div class="glass-card-register sweep-animate">
        
        <div class="brand-header-register">
            <i class="fas fa-user-plus fa-2x mb-3 text-white-50"></i>
            <h3>Crear Cuenta</h3>
            <p class="mb-0 text-white-50 small">Regístrate para gestionar tus servicios de transfer</p>
        </div>

        <div class="form-container">
            <form action="{{ route('auth.register.post') }}" method="POST" id="formRegistro" novalidate>
                @csrf

                <input type="hidden" name="user_type" value="viajero">

                <div id="viajero-fields" style="display:none;">
                    <div class="mb-4">
                        <label for="email" class="form-label"><i class="fas fa-envelope"></i> Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="ejemplo@correo.com" required>
                        <div class="field-error-msg" id="err-email">Introduce un correo electrónico válido</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="nombre" class="form-label"><i class="fas fa-user"></i> Nombre</label>
                            <input type="text" class="form-control" id="nombre" name="nombre" placeholder="Tu nombre" required>
                            <div class="field-error-msg" id="err-nombre">Introduce tu nombre</div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="apellido1" class="form-label"><i class="fas fa-signature"></i> Primer Apellido</label>
                            <input type="text" class="form-control" id="apellido1" name="apellido1" placeholder="Primer apellido" required>
                            <div class="field-error-msg" id="err-apellido1">Introduce tu primer apellido</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="apellido2" class="form-label"><i class="fas fa-signature-slash"></i> Segundo Apellido</label>
                            <input type="text" class="form-control" id="apellido2" name="apellido2" placeholder="Opcional">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="ciudad" class="form-label"><i class="fas fa-city"></i> Ciudad</label>
                            <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="Ciudad" required>
                            <div class="field-error-msg" id="err-ciudad">Introduce tu ciudad</div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <label for="codigoPostal" class="form-label"><i class="fas fa-mail-bulk"></i> Código Postal</label>
                            <input type="text" class="form-control" id="codigoPostal" name="codigoPostal" placeholder="C.P." required>
                            <div class="field-error-msg" id="err-codigoPostal">Introduce el código postal</div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <label for="pais" class="form-label"><i class="fas fa-globe-europe"></i> País</label>
                            <input type="text" class="form-control" id="pais" name="pais" placeholder="Tu país" required>
                            <div class="field-error-msg" id="err-pais">Introduce tu país</div>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="direccion" class="form-label"><i class="fas fa-map-marked-alt"></i> Dirección</label>
                        <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Calle, número, piso..." required>
                        <div class="field-error-msg" id="err-direccion">La dirección es necesaria</div>
                    </div>
                </div>

                <div class="section-divider">
                    <span>SEGURIDAD</span>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-4">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePass('password', this)"></i>
                        </div>
                        <span id="password-msg" class="pass-feedback"></span>
                    </div>

                    <div class="col-md-6 mb-4">
                        <label for="password_confirmation" class="form-label"><i class="fas fa-check-double"></i> Confirmar Contraseña</label>
                        <div class="password-wrapper">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••" required>
                            <i class="fas fa-eye password-toggle" onclick="togglePass('password_confirmation', this)"></i>
                        </div>
                        <div class="field-error-msg" id="err-confirm">Las contraseñas no coinciden o son inválidas</div>
                    </div>
                </div>

                <button type="submit" class="btn btn-register-submit w-100">
                    Completar Registro <i class="fas fa-arrow-right ms-2"></i>
                </button>
            </form>

            <div class="text-center mt-4">
                <p class="mb-0 text-muted small">
                    ¿Ya tienes cuenta? 
                    <a href="{{ route('login') }}" class="login-link">Inicia sesión aquí</a>
                </p>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    document.getElementById('viajero-fields').style.display = 'block';

    function togglePass(id, icon) {
        const input = document.getElementById(id);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = "password";
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    const passInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');
    const passMsg = document.getElementById('password-msg');
    const emailInput = document.getElementById('email');
    const form = document.getElementById('formRegistro');

    // Función para validar formato de email
    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }

    // Validación en tiempo real para contraseñas
    function validarPass() {
        const p1 = passInput.value;
        const p2 = confirmInput.value;

        if(p1.length > 0) {
            if(p1.length < 8) {
                passInput.classList.add('invalido');
                passInput.classList.remove('valido');
                passMsg.textContent = "La contraseña debe tener al menos 8 caracteres";
                passMsg.style.color = "var(--error-color)";
            } else {
                passInput.classList.add('valido');
                passInput.classList.remove('invalido');
                passMsg.textContent = "Longitud de contraseña válida";
                passMsg.style.color = "var(--success-color)";
            }
        }

        if(p2.length > 0) {
            if(p1 === p2 && p1.length >= 8) {
                confirmInput.classList.add('valido');
                confirmInput.classList.remove('invalido');
                document.getElementById('err-confirm').style.display = 'none';
            } else {
                confirmInput.classList.add('invalido');
                confirmInput.classList.remove('valido');
            }
        }
    }

    passInput.addEventListener('input', validarPass);
    confirmInput.addEventListener('input', validarPass);

    // Validación general al intentar enviar el formulario
    form.addEventListener('submit', function(e) {
        let error = false;
        const requiredFields = form.querySelectorAll('[required]');

        requiredFields.forEach(field => {
            const errorDiv = document.getElementById('err-' + field.id) || (field.id === 'password' ? passMsg : null);
            let isFieldInvalid = false;

            // Validación de campo vacío
            if (!field.value.trim()) {
                isFieldInvalid = true;
            } 
            // Validación específica de Email
            else if (field.id === 'email' && !validateEmail(field.value)) {
                isFieldInvalid = true;
                if (errorDiv) errorDiv.textContent = "El formato del correo no es válido";
            }

            if (isFieldInvalid) {
                field.classList.add('invalido');
                field.classList.remove('valido');
                if (errorDiv && field.id !== 'password') errorDiv.style.display = 'block';
                error = true;
            } else {
                // Si el campo es válido (y no es contraseña que tiene su propia lógica)
                if (field.id !== 'password' && field.id !== 'password_confirmation') {
                    field.classList.remove('invalido');
                    field.classList.add('valido');
                    if (errorDiv) errorDiv.style.display = 'none';
                }
            }
        });

        // Validar que las contraseñas coincidan antes de enviar
        if (passInput.value !== confirmInput.value || passInput.value.length < 8) {
            document.getElementById('err-confirm').style.display = 'block';
            confirmInput.classList.add('invalido');
            error = true;
        }

        if (error) {
            e.preventDefault(); // Detiene el envío PERO mantiene los valores en los inputs
            // Opcional: Hacer scroll al primer error para mejorar UX
            const firstError = document.querySelector('.invalido');
            if (firstError) firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    });

    // Limpiar clases de error al escribir para mejorar UX
    form.querySelectorAll('.form-control').forEach(input => {
        input.addEventListener('input', function() {
            if (this.value.trim() !== "") {
                this.classList.remove('invalido');
                const errorDiv = document.getElementById('err-' + this.id);
                if (errorDiv) errorDiv.style.display = 'none';
            }
        });
    });
</script>
@endsection

@endsection