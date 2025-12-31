@extends('layouts.app')

@section('title', 'Registrar Hotel')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --bg-main: #f8fafc;
        --error-red: #ef4444;
    }

    body { background-color: var(--bg-main); font-family: 'Plus Jakarta Sans', sans-serif; }

    .reservas-card-main {
        border-radius: 28px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #ffffff;
    }

    .header-navy { background: var(--brand-navy); padding: 2.5rem; color: white; }

    .form-label {
        font-weight: 700; color: var(--brand-navy); font-size: 0.82rem;
        text-transform: uppercase; letter-spacing: 0.8px; margin-bottom: 0.85rem;
        display: flex; align-items: center;
    }

    .form-label i {
        background: #f1f5f9; color: var(--brand-navy);
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center; margin-right: 12px;
    }

    .password-wrapper { position: relative; }

    .form-control {
        border-radius: 12px; padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0; transition: all 0.2s ease;
    }

    .form-control:focus {
        border-color: var(--brand-navy);
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.1);
        outline: none;
    }

    .toggle-password {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        color: #94a3b8;
        transition: color 0.2s;
        z-index: 10;
    }

    .toggle-password:hover { color: var(--brand-navy); }

    .invalid-feedback-custom {
        color: var(--error-red); font-size: 0.75rem; font-weight: 700;
        margin-top: 0.5rem; display: flex; align-items: center; gap: 5px;
    }

    .btn-primary-custom {
        background: var(--brand-navy); color: white !important;
        border: none; padding: 1rem; border-radius: 14px;
        font-weight: 700; text-transform: uppercase;
        width: 100%; transition: 0.3s;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
    }

    .btn-primary-custom:hover { background: var(--brand-hover); transform: translateY(-2px); }

    .btn-outline-custom {
        border: 2px solid #e2e8f0; color: #64748b !important;
        padding: 1rem; border-radius: 14px; font-weight: 700;
        text-transform: uppercase; width: 100%;
        display: flex; align-items: center; justify-content: center;
        text-decoration: none;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-7">
            <div class="reservas-card-main">
                <div class="header-navy">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fas fa-hotel fa-2x"></i>
                        <div>
                            <p class="text-uppercase mb-0" style="opacity: 0.7; font-size: 0.7rem; font-weight: 700;">Panel Administrativo</p>
                            <h3 class="mb-0 fw-bold">Registrar Nuevo Hotel</h3>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <form action="{{ route('admin.hotels.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-12 mb-4">
                                <label class="form-label"><i class="fas fa-building"></i> Nombre del Hotel</label>
                                <input type="text" name="nombre_hotel" class="form-control @error('nombre_hotel') is-invalid @enderror" value="{{ old('nombre_hotel') }}" placeholder="Ej. Hotel Paraíso">
                                @error('nombre_hotel')
                                    <div class="invalid-feedback-custom"><i class="fas fa-circle-exclamation"></i> El nombre del hotel es obligatorio.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label"><i class="fas fa-user"></i> Usuario de Acceso</label>
                                <input type="text" name="usuario" class="form-control @error('usuario') is-invalid @enderror" value="{{ old('usuario') }}" placeholder="usuario_hotel">
                                @error('usuario')
                                    <div class="invalid-feedback-custom"><i class="fas fa-circle-exclamation"></i> El usuario es obligatorio y debe ser único.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label"><i class="fas fa-map-pin"></i> ID Zona</label>
                                <input type="number" name="id_zona" class="form-control" value="{{ old('id_zona') }}" placeholder="0">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label"><i class="fas fa-lock"></i> Contraseña</label>
                                <div class="password-wrapper">
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                                    <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)"></i>
                                </div>
                                @error('password')
                                    <div class="invalid-feedback-custom"><i class="fas fa-circle-exclamation"></i> Revisa la contraseña.</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label"><i class="fas fa-check-double"></i> Confirmar Contraseña</label>
                                <div class="password-wrapper">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password') is-invalid @enderror" placeholder="••••••••">
                                    <i class="fas fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)"></i>
                                </div>
                            </div>
                        </div>

                        <div class="row g-3 mt-4">
                            <div class="col-md-8">
                                <button type="submit" class="btn-primary-custom">
                                    <i class="fas fa-save me-2"></i> Guardar Hotel
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('admin.hotels.list') }}" class="btn-outline-custom">Cancelar</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
</script>
@endsection