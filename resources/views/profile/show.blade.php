@extends('layouts.app')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container py-5" style="font-family: 'Inter', sans-serif;">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card border-0 shadow-sm overflow-hidden" style="border-radius: 24px;">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf 
                    @method('PUT')
                    
                    <div class="row no-gutters">
                        <div class="col-md-4 d-none d-md-flex flex-column align-items-center justify-content-center p-5 text-white" 
                             style="background: linear-gradient(180deg, #4e73df 0%, #224abe 100%);">
                            
                            <div class="position-relative mb-4">
                                <div class="rounded-circle bg-white p-1 shadow-lg" style="width: 150px; height: 150px; overflow: hidden;">
                                    <img id="user-avatar" 
                                         src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://ui-avatars.com/api/?name='.urlencode($user->nombre ?? $user->nombre_hotel ?? 'U').'&background=random&size=150' }}" 
                                         class="rounded-circle w-100 h-100" 
                                         style="object-fit: cover;" 
                                         alt="Avatar">
                                </div>
                                <label for="foto-input" class="position-absolute bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" 
                                       style="bottom: 5px; right: 5px; width: 42px; height: 42px; cursor: pointer; border: 3px solid #fff; transition: all 0.2s;">
                                    <i class="fas fa-camera"></i>
                                    <input type="file" name="foto" id="foto-input" class="d-none" accept="image/*">
                                </label>
                            </div>

                            <h4 class="font-weight-bold text-center mb-1" id="display-name">{{ $user->nombre ?? $user->nombre_hotel ?? 'Usuario' }}</h4>
                            <p class="text-white-50 small mb-4 text-uppercase tracking-wider">{{ session('user_type') }}</p>
                            
                            <div class="w-100 mt-4">
                                <p class="small mb-1 text-center">Progreso del perfil: <span id="percent-text">{{ round($porcentaje) }}%</span></p>
                                <div class="progress" style="height: 8px; border-radius: 10px; background: rgba(255,255,255,0.2);">
                                    <div id="profile-bar" class="progress-bar bg-white transition-all" 
                                         style="width: {{ $porcentaje }}%; transition: width 0.5s ease;"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8 bg-white p-4 p-md-5">
                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <h3 class="font-weight-700 mb-0" style="color: #1a202c;">Configuración</h3>
                                <span class="badge badge-light p-2 px-3 text-primary" style="border-radius: 10px;">
                                    ID: #{{ $user->id_hotel ?? $user->id_admin ?? $user->id_viajero ?? session('user_id') }}
                                </span>
                            </div>

                            <div class="row">
                                <div class="col-12 mb-4">
                                    <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">
                                        {{ session('user_type') === 'hotel' ? 'Usuario de acceso' : 'Cuenta de acceso' }}
                                    </label>
                                    <div class="custom-input-group">
                                        <i class="fas fa-{{ session('user_type') === 'hotel' ? 'user' : 'envelope' }} icon"></i>
                                        <input type="text" name="email" id="input-email" class="custom-control profile-input" 
                                               value="{{ $user->email ?? $user->usuario ?? '' }}" 
                                               placeholder="{{ session('user_type') === 'hotel' ? 'Nombre de usuario' : 'email@ejemplo.com' }}">
                                    </div>
                                </div>

                                @if (session('user_type') === 'viajero' || session('user_type') === 'admin')
                                    <div class="col-md-6 mb-3">
                                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Nombre</label>
                                        <input type="text" name="nombre" id="input-nombre" class="custom-control profile-input shadow-none" value="{{ $user->nombre ?? '' }}">
                                    </div>
                                    @if (session('user_type') === 'viajero')
                                        <div class="col-md-6 mb-3">
                                            <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Apellido</label>
                                            <input type="text" name="apellido1" id="input-apellido" class="custom-control profile-input shadow-none" value="{{ $user->apellido1 ?? '' }}">
                                        </div>
                                        <div class="col-12 mb-3">
                                            <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Ciudad</label>
                                            <div class="custom-input-group">
                                                <i class="fas fa-location-dot icon"></i>
                                                <input type="text" name="ciudad" id="input-ciudad" class="custom-control profile-input shadow-none" value="{{ $user->ciudad ?? '' }}">
                                            </div>
                                        </div>
                                    @endif
                                @endif

                                @if (session('user_type') === 'hotel')
                                    <div class="col-12 mb-3">
                                        <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Nombre del Hotel</label>
                                        <div class="custom-input-group">
                                            <i class="fas fa-hotel icon"></i>
                                            <input type="text" name="nombre_hotel" id="input-hotel" class="custom-control profile-input shadow-none" value="{{ $user->nombre_hotel ?? '' }}">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <hr class="my-4" style="border-top: 1px dashed #e2e8f0;">

                            <h6 class="font-weight-bold mb-4" style="color: #2d3748;">
                                <i class="fas fa-shield-alt mr-2 text-primary"></i> Seguridad de la cuenta
                            </h6>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Nueva Contraseña</label>
                                    <div class="custom-input-group">
                                        <i class="fas fa-lock icon"></i>
                                        <input type="password" name="new_password" id="pass-field" class="custom-control" placeholder="••••••••">
                                        <i class="fas fa-eye position-absolute" id="togglePass" style="right: 15px; cursor: pointer; color: #a0aec0; z-index: 20;"></i>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="text-xs font-weight-bold text-muted text-uppercase mb-2 d-block">Confirmar</label>
                                    <div class="custom-input-group">
                                        <i class="fas fa-check-double icon"></i>
                                        <input type="password" name="password_confirmation" class="custom-control" placeholder="••••••••">
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary btn-lg px-5 py-3 shadow-sm transition-hover w-100" 
                                        style="border-radius: 14px; font-weight: 600;">
                                    Guardar Cambios
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    body { background-color: #f4f7fe; }
    .text-xs { font-size: 0.75rem; }
    .font-weight-700 { font-weight: 700; }
    
    .custom-input-group { position: relative; display: flex; align-items: center; }
    .custom-input-group .icon { position: absolute; left: 16px; color: #a0aec0; z-index: 10; }
    .custom-input-group .custom-control { padding-left: 45px !important; }

    .custom-control { 
        width: 100%; padding: 12px 16px; background-color: #f8fafc; 
        border: 1.5px solid #e2e8f0; border-radius: 12px; color: #4a5568; transition: all 0.2s ease;
    }
    
    .custom-control:focus { 
        border-color: #4e73df; outline: none; background-color: #fff;
        box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.1);
    }

    .transition-hover { transition: transform 0.2s, box-shadow 0.2s; }
    .transition-hover:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 15px rgba(78, 115, 223, 0.25); 
    }
    
    label[for="foto-input"]:hover { transform: scale(1.1); background-color: #224abe !important; }
    .progress-bar { transition: width 0.6s cubic-bezier(0.4, 0, 0.2, 1), background-color 0.3s; }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.profile-input');
    const bar = document.getElementById('profile-bar');
    const percentText = document.getElementById('percent-text');
    const inputNombre = document.getElementById('input-nombre') || document.getElementById('input-hotel');
    const displayName = document.getElementById('display-name');
    const avatar = document.getElementById('user-avatar');
    const togglePass = document.getElementById('togglePass');
    const passField = document.getElementById('pass-field');
    const fotoInput = document.getElementById('foto-input');

    // Previsualización de Foto Real
    fotoInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                avatar.src = e.target.result;
            }
            reader.readAsDataURL(file);
        }
    });

    // Función para calcular progreso real
    function updateProgress() {
        let total = inputs.length;
        let filled = 0;
        inputs.forEach(input => {
            if (input.value.trim() !== '') filled++;
        });
        let percentage = Math.round((filled / total) * 100);
        bar.style.width = percentage + '%';
        percentText.innerText = percentage + '%';
        if(percentage === 100) {
            bar.style.backgroundColor = '#2ecc71';
        } else {
            bar.style.backgroundColor = '#ffffff';
        }
    }

    // Actualizar nombre en vivo
    if(inputNombre) {
        inputNombre.addEventListener('input', function() {
            displayName.innerText = this.value || 'Usuario';
            if (avatar.src.includes('ui-avatars.com')) {
                avatar.src = `https://ui-avatars.com/api/?name=${encodeURIComponent(this.value)}&background=random&size=150`;
            }
        });
    }

    inputs.forEach(input => {
        input.addEventListener('input', updateProgress);
    });

    togglePass.addEventListener('click', function() {
        const type = passField.getAttribute('type') === 'password' ? 'text' : 'password';
        passField.setAttribute('type', type);
        this.classList.toggle('fa-eye-slash');
        this.classList.toggle('fa-eye');
    });

    updateProgress();
});
</script>
@endsection