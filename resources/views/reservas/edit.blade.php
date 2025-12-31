@extends('layouts.app')

@section('title', 'Editar Reserva')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --accent-blue: #3b82f6;
        --bg-main: #f8fafc;
        --border-focus: #475569; /* Un gris azulado oscuro para el foco */
    }

    body { 
        background-color: var(--bg-main); 
        font-family: 'Plus Jakarta Sans', sans-serif; 
    }

    .reservas-card-main {
        border-radius: 28px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #ffffff;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-navy {
        background: var(--brand-navy);
        padding: 2.5rem;
        color: white;
    }

    .header-navy h3 {
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    /* ESTILO ICONOS EXTERNOS (Labels) */
    .form-label {
        font-weight: 700;
        color: var(--brand-navy);
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 0.85rem;
        display: flex;
        align-items: center;
    }

    .form-label i {
        background: #f1f5f9;
        color: var(--brand-navy);
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 12px;
        font-size: 0.9rem;
    }

    .form-control, .form-select {
        border-radius: 12px;
        padding: 0.75rem 1rem;
        border: 1px solid #e2e8f0;
        font-weight: 500;
        color: #1e293b;
        transition: all 0.2s ease;
    }

    /* CAMBIO: Ahora el focus usa el azul oscuro de la marca */
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-navy);
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.1); /* Sombra suave basada en el navy */
        outline: none;
    }

    .input-disabled-custom {
        background-color: #f8fafc !important;
        font-family: 'JetBrains Mono', monospace;
        font-weight: 800;
        color: var(--brand-navy) !important;
        border: 1px dashed #cbd5e1;
    }

    /* ESTADO DEL SERVICIO: Borde lateral oscuro */
    .select-estado-custom {
        border-left: 5px solid var(--brand-navy) !important;
    }

    .btn-primary-custom {
        background: var(--brand-navy);
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-primary-custom:hover {
        background: var(--brand-hover);
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        color: white;
    }

    .btn-outline-custom {
        border: 2px solid #e2e8f0;
        color: #64748b;
        padding: 1rem;
        border-radius: 14px;
        font-weight: 700;
        text-transform: uppercase;
        transition: all 0.3s ease;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .btn-outline-custom:hover {
        background: #f1f5f9;
        color: var(--brand-navy);
        border-color: #cbd5e1;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-9">
            <div class="reservas-card-main">
                
                <div class="header-navy">
                    <div class="d-flex align-items-center gap-3">
                        <div style="background: rgba(255,255,255,0.1); padding: 10px; border-radius: 12px;">
                            <i class="fas fa-pen-to-square fa-lg"></i>
                        </div>
                        <div>
                            <p class="text-uppercase mb-0" style="opacity: 0.7; font-size: 0.75rem; letter-spacing: 2px;">Panel de Edición</p>
                            <h3 class="mb-0">Actualizar Reserva</h3>
                        </div>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <form action="{{ route('reservas.update', $reserva->id_reserva) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label class="form-label"><i class="fas fa-fingerprint"></i> Localizador (No Editable)</label>
                            <input type="text" class="form-control input-disabled-custom" value="{{ $reserva->localizador }}" disabled>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="id_hotel" class="form-label"><i class="fas fa-hotel"></i> Hotel *</label>
                                <select class="form-select" id="id_hotel" name="id_hotel" required>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id_hotel }}" {{ $reserva->id_hotel == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nombre_hotel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="id_tipo_reserva" class="form-label"><i class="fas fa-tags"></i> Tipo de Reserva *</label>
                                <select class="form-select" id="id_tipo_reserva" name="id_tipo_reserva" required>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}" {{ $reserva->id_tipo_reserva == $tipo->id_tipo_reserva ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label for="num_viajeros" class="form-label"><i class="fas fa-user-group"></i> Pasajeros</label>
                                <input type="number" class="form-control" id="num_viajeros" name="num_viajeros" value="{{ $reserva->num_viajeros }}">
                            </div>

                            <div class="col-md-6 mb-4">
                                <label for="id_vehiculo" class="form-label"><i class="fas fa-car-side"></i> Vehículo</label>
                                <select class="form-select" id="id_vehiculo" name="id_vehiculo">
                                    <option value="">Sin asignar</option>
                                    @foreach ($vehiculos as $vehiculo)
                                        <option value="{{ $vehiculo->id_vehiculo }}" {{ $reserva->id_vehiculo == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                            {{ $vehiculo->descripcion }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label for="estado" class="form-label"><i class="fas fa-circle-check"></i> Estado del Servicio *</label>
                            <select class="form-select select-estado-custom @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="pendiente" {{ $reserva->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ $reserva->estado === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelada" {{ $reserva->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                <option value="completada" {{ $reserva->estado === 'completada' ? 'selected' : '' }}>Completada</option>
                            </select>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-8">
                                <button type="submit" class="btn btn-primary-custom w-100">
                                    <i class="fas fa-save me-2"></i>Guardar Cambios
                                </button>
                            </div>
                            <div class="col-md-4">
                                <a href="{{ route('reservas.index') }}" class="btn btn-outline-custom w-100">
                                    Volver
                                </a>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-light py-4 text-center border-top">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 2px;">
                        Enders Transfer Solutions &copy; {{ date('Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection