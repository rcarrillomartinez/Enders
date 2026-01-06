@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --bg-main: #f8fafc;
        --success-green: #22c55e;
        --error-red: #ef4444;
    }

    body { background-color: var(--bg-main); font-family: 'Plus Jakarta Sans', sans-serif; }
    
    .profile-card-main {
        border-radius: 28px;
        border: none;
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        overflow: hidden;
        background: #ffffff;
    }

    .sidebar-navy {
        background: var(--brand-navy);
        padding: 3rem 2rem;
        color: white;
    }

    .form-label-custom {
        font-weight: 800;
        color: var(--brand-navy);
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 0.6rem;
        display: block;
    }

    .custom-control { 
        width: 100%; padding: 12px 16px; background-color: #fff; 
        border: 1.5px solid #e2e8f0; border-radius: 12px; color: #1e293b; transition: all 0.3s ease;
    }
    
    .custom-control:focus { 
        border-color: var(--brand-navy); outline: none;
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.1);
    }

    .btn-save-profile {
        background: var(--brand-navy);
        color: white !important;
        border: none;
        border-radius: 14px;
        font-weight: 700;
        padding: 1.1rem;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 1px;
        width: 100%;
        cursor: pointer;
    }

    .btn-save-profile:hover {
        background: var(--brand-hover);
        transform: translateY(-2px);
        box-shadow: 0 8px 15px rgba(15, 23, 42, 0.2);
    }

    .camera-label {
        position: absolute; bottom: 5px; right: 5px;
        width: 42px; height: 42px; cursor: pointer;
        background-color: white !important;
        color: var(--brand-navy) !important;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        transition: 0.3s;
        z-index: 10;
    }

    .custom-input-group { position: relative; display: flex; align-items: center; }
    .custom-input-group .icon { position: absolute; left: 16px; color: #94a3b8; z-index: 10; }
    .custom-input-group .custom-control { padding-left: 45px !important; }

    .toggle-password-icon {
        position: absolute; right: 15px; cursor: pointer;
        color: #94a3b8; z-index: 20;
    }

    .progress-main {
        height: 8px; border-radius: 10px; background: rgba(255,255,255,0.15); overflow: hidden;
    }
    #profile-bar { transition: width 0.8s ease; background-color: white !important; }

    .password-error-msg {
        color: var(--error-red); font-size: 0.7rem; font-weight: 700;
        margin-top: 5px; display: none; align-items: center; gap: 4px;
    }
    .match-success { color: var(--success-green) !important; }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            {{-- NOTA: He quitado el bloque @if(session('success')) de aquí --}}
            {{-- para evitar que salga doble si ya está en tu layouts.app --}}

            <div class="card profile-card-main">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profileForm">
                    @csrf 
                    @method('PUT')
                    
                    <div class="row g-0">
                        <div class="col-md-4 sidebar-navy d-flex flex-column align-items-center justify-content-center text-center">
                            
                            @if (session('user_type') !== 'hotel')
                                <div class="position-relative mb-4">
                                    <div class="rounded-circle bg-white p-1 shadow-lg" style="width: 155px; height: 155px; overflow: hidden;">
                                        <img id="user-avatar" 
                                             src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nombre ?? 'U').'&background=random&size=150' }}" 
                                             class="rounded-circle w-100 h-100" style="object-fit: cover;">
                                    </div>
                                    <label for="foto-input" class="camera-label">
                                        <i class="fas fa-camera"></i>
                                        <input type="file" name="foto" id="foto-input" class="d-none" accept="image/*">
                                    </label>
                                </div>
                            @else
                                <div class="mb-4">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; background: rgba(255,255,255,0.1); border: 2px dashed rgba(255,255,255,0.3);">
                                        <i class="fas fa-hotel fa-3x text-white"></i>
                                    </div>
                                </div>
                            @endif

                            <h4 class="fw-bold mb-1" id="display-name">{{ $user->nombre ?? $user->nombre_hotel ?? 'Usuario' }}</h4>
                            <p class="text-white-50 small mb-4 text-uppercase fw-bold">{{ session('user_type') }}</p>
                            
                            <div class="w-100 px-3 mt-4">
                                <p class="small mb-2">Perfil completado: <span id="percent-text" class="fw-bold">0%</span></p>
                                <div class="progress progress-main">
                                    <div id="profile-bar" class="progress-bar" style="width: 0%;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 bg-white p-4 p-md-5">
                            <h3 class="fw-bold mb-5" style="color: var(--brand-navy);">Configuración del Perfil</h3>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label class="form-label-custom">
                                        {{ session('user_type') === 'hotel' ? 'Usuario de acceso' : 'Correo electrónico' }}
                                    </label>
                                    <div class="custom-input-group">
                                        <i class="fas fa-{{ session('user_type') === 'hotel' ? 'user' : 'envelope' }} icon"></i>
                                        <input type="text" 
                                               name="{{ session('user_type') === 'hotel' ? 'usuario' : 'email' }}" 
                                               class="custom-control profile-input" 
                                               value="{{ $user->email ?? $user->usuario ?? '' }}">
                                    </div>
                                </div>

                                @if (session('user_type') !== 'hotel')
                                    <div class="col-md-6 mb-4">
                                        <label class="form-label-custom">Nombre</label>
                                        <input type="text" name="nombre" id="input-nombre" class="custom-control profile-input" value="{{ $user->nombre ?? '' }}">
                                    </div>
                                    @if (session('user_type') === 'viajero')
                                        <div class="col-md-6 mb-4">
                                            <label class="form-label-custom">Primer Apellido</label>
                                            <input type="text" name="apellido1" class="custom-control profile-input" value="{{ $user->apellido1 ?? '' }}">
                                        </div>
                                        <div class="col-12 mb-4">
                                            <label class="form-label-custom">Ciudad</label>
                                            <div class="custom-input-group">
                                                <i class="fas fa-location-dot icon"></i>
                                                <input type="text" name="ciudad" class="custom-control profile-input" value="{{ $user->ciudad ?? '' }}">
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <div class="col-12 mb-4">
                                        <label class="form-label-custom">Nombre del Hotel</label>
                                        <div class="custom-input-group">
                                            <i class="fas fa-hotel icon"></i>
                                            <input type="text" name="nombre_hotel" id="input-hotel" class="custom-control profile-input" value="{{ $user->nombre_hotel ?? '' }}">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <hr class="my-5 opacity-25">

                            <h6 class="fw-bold mb-4" style="color: var(--brand-navy);">SEGURIDAD (Opcional)</h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="form-label-custom text-muted">Nueva Contraseña</label>
                                    <div class="custom-input-group">
                                        <i class="fas fa-lock icon"></i>
                                        <input type="password" name="password" id="pass-field" class="custom-control" placeholder="••••••••">
                                        <i class="fas fa-eye toggle-password-icon" id="togglePass"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="form-label-custom text-muted">Confirmar Password</label>
                                    <div class="custom-input-group">
                                        <i id="check-icon" class="fas fa-check-double icon"></i>
                                        <input type="password" name="password_confirmation" id="confirm-pass-field" class="custom-control" placeholder="••••••••">
                                        <i class="fas fa-eye toggle-password-icon" id="toggleConfirmPass"></i>
                                    </div>
                                    <div id="error-msg" class="password-error-msg">
                                        <i class="fas fa-times-circle"></i> Las contraseñas no coinciden
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn-save-profile">GUARDAR CAMBIOS</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.profile-input');
    const bar = document.getElementById('profile-bar');
    const percentText = document.getElementById('percent-text');
    const inputNombre = document.getElementById('input-nombre') || document.getElementById('input-hotel');
    const displayName = document.getElementById('display-name');
    const avatar = document.getElementById('user-avatar');
    const passField = document.getElementById('pass-field');
    const confirmField = document.getElementById('confirm-pass-field');
    const checkIcon = document.getElementById('check-icon');
    const errorMsg = document.getElementById('error-msg');
    const fotoInput = document.getElementById('foto-input');

    if (fotoInput) {
        fotoInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) { if(avatar) avatar.src = e.target.result; }
                reader.readAsDataURL(file);
            }
        });
    }

    function updateProgress() {
        let total = inputs.length;
        let filled = 0;
        inputs.forEach(input => { if (input.value.trim() !== '') filled++; });
        let percentage = total > 0 ? Math.round((filled / total) * 100) : 0;
        bar.style.width = percentage + '%';
        percentText.innerText = percentage + '%';
    }

    function validatePasswords() {
        if (confirmField.value.length > 0) {
            if (passField.value === confirmField.value) {
                checkIcon.classList.add('match-success');
                confirmField.style.borderColor = 'var(--success-green)';
                errorMsg.style.display = 'none';
            } else {
                checkIcon.classList.remove('match-success');
                confirmField.style.borderColor = 'var(--error-red)';
                errorMsg.style.display = 'flex';
            }
        } else {
            checkIcon.classList.remove('match-success');
            confirmField.style.borderColor = '#e2e8f0';
            errorMsg.style.display = 'none';
        }
    }

    passField.addEventListener('input', validatePasswords);
    confirmField.addEventListener('input', validatePasswords);
    if(inputNombre) {
        inputNombre.addEventListener('input', function() {
            displayName.innerText = this.value || 'Usuario';
        });
    }
    inputs.forEach(input => { input.addEventListener('input', updateProgress); });

    function setupPasswordToggle(toggleId, inputId) {
        const toggle = document.getElementById(toggleId);
        const input = document.getElementById(inputId);
        if (toggle && input) {
            toggle.addEventListener('click', function() {
                input.type = input.type === 'password' ? 'text' : 'password';
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });
        }
    }
    setupPasswordToggle('togglePass', 'pass-field');
    setupPasswordToggle('toggleConfirmPass', 'confirm-pass-field');

    updateProgress();
});
</script>
@endsection