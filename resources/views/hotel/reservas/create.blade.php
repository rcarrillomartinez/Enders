@extends('layouts.app')

@section('title', 'Nueva Reserva')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b; 
        --bg-main: #f8fafc;
        --card-border: #e2e8f0;
        --text-main: #0f172a;
        --accent-blue: #3b82f6;
    }

    body { 
        background-color: var(--bg-main); 
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-main);
    }

    /* Botón Volver al Panel Estilizado */
    .btn-back-elegant {
        display: inline-flex;
        align-items: center;
        padding: 0.7rem 1.2rem;
        background-color: white;
        color: var(--brand-navy);
        border: 1px solid var(--card-border);
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.8rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-back-elegant:hover {
        background-color: var(--brand-navy);
        color: white !important;
        transform: translateX(-5px);
        box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
    }

    .animate-page { animation: slideUp 0.6s ease-out; }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .reserva-container { max-width: 900px; margin: 0 auto; }

    .glass-card {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid var(--card-border);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .brand-header {
        background: var(--brand-navy);
        padding: 3rem 2.5rem;
        position: relative;
    }

    .brand-header h3 { font-weight: 800; color: #ffffff; letter-spacing: -0.5px; }
    .brand-header p { color: #ffffff; opacity: 0.8; font-weight: 500; }

    .flight-block {
        border-radius: 20px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        transition: all 0.4s ease;
    }

    .flight-icon-badge {
        width: 45px; height: 45px; border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px; background: var(--brand-navy); color: white;
    }

    .form-section-title {
        font-size: 0.8rem; font-weight: 800; color: var(--brand-navy);
        text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1.5rem;
        display: flex; align-items: center; margin-top: 2rem;
    }

    .form-section-title::after {
        content: ""; flex: 1; height: 1px; background: #e2e8f0; margin-left: 15px;
    }

    .input-premium {
        border: 1px solid #cbd5e1; border-radius: 12px; padding: 0.8rem 1.1rem;
        font-size: 0.95rem; font-weight: 500; transition: all 0.3s ease;
        background-color: #ffffff; width: 100%;
    }

    .input-premium:focus {
        border-color: var(--brand-navy); box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08); outline: none;
    }

    .btn-submit-premium {
        background: var(--brand-navy); color: white; border: none; padding: 1.2rem;
        border-radius: 16px; font-weight: 700; font-size: 1rem; letter-spacing: 1px;
        width: 100%; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-top: 2rem;
    }

    .btn-submit-premium:hover {
        transform: translateY(-3px); box-shadow: 0 12px 20px -5px rgba(15, 23, 42, 0.25);
        background: var(--brand-hover);
    }
</style>

<div class="container py-5 animate-page">
    <div class="reserva-container">
        
        <div class="mb-4 d-flex justify-content-start">
            <a href="{{ route('hotel.dashboard') }}" class="btn-back-elegant">
                <i class="fas fa-chevron-left me-2"></i> Volver al Panel
            </a>
        </div>

        <div class="glass-card">
            <div class="brand-header d-flex justify-content-between align-items-center text-white">
                <div>
                    <h3 class="mb-1"><i class="fas fa-paper-plane me-2"></i>Nueva Reserva</h3>
                    <p class="mb-0 small">Gestión de Transfer &bull; Hotel {{ Auth::guard('hotel')->user()->nombre_hotel }}</p>
                </div>
                <div class="status-pill d-none d-md-block" style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); font-size: 0.75rem; font-weight: 700;">
                    <i class="fas fa-check-circle me-2"></i>SISTEMA ACTIVO
                </div>
            </div>

            <div class="p-4 p-md-5">
                <form action="{{ route('hotel.reservas.store') }}" method="POST" id="reservaForm">
                    @csrf

                    <div class="form-section-title">
                        <i class="fas fa-user-circle me-2"></i> Información del Cliente
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="fw-bold small mb-2 d-block">Nombre</label>
                            <input type="text" name="nombre_cliente" class="input-premium" placeholder="Ej: Juan" required>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-bold small mb-2 d-block">Primer Apellido</label>
                            <input type="text" name="apellido1_cliente" class="input-premium" placeholder="Ej: Pérez" required>
                        </div>
                        <div class="col-md-12">
                            <label class="fw-bold small mb-2 d-block">Email de Contacto</label>
                            <input type="email" name="email_cliente" class="input-premium" placeholder="cliente@correo.com" required>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-route me-2"></i> Detalles del Servicio
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label class="fw-bold small mb-2 d-block">Seleccione Trayecto</label>
                            <select class="form-select input-premium" id="id_tipo_reserva" name="id_tipo_reserva" required>
                                <option value="" disabled selected>Seleccione...</option>
                                @foreach (\App\Models\TipoReserva::all() as $tipo)
                                    <option value="{{ $tipo->id_tipo_reserva }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="fw-bold small mb-2 d-block">Nº Pasajeros</label>
                            <input type="number" class="input-premium" id="num_viajeros" name="num_viajeros" min="1" value="1" required>
                        </div>
                    </div>

                    <div id="bloque_ida" class="flight-block p-4 mb-4" style="display:none;">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flight-icon-badge"><i class="fas fa-plane-arrival"></i></div>
                            <h6 class="mb-0 fw-bold">Recogida (Llegada)</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Fecha</label>
                                <input type="date" name="fecha_entrada" class="input-premium">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Hora Estimada</label>
                                <input type="time" name="hora_entrada" class="input-premium">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Nº Vuelo</label>
                                <input type="text" name="numero_vuelo_entrada" class="input-premium" placeholder="Ex: IB3244">
                            </div>
                        </div>
                    </div>

                    <div id="bloque_vuelta" class="flight-block p-4 mb-4" style="display:none;">
                        <div class="d-flex align-items-center mb-4">
                            <div class="flight-icon-badge"><i class="fas fa-plane-departure"></i></div>
                            <h6 class="mb-0 fw-bold">Regreso (Salida)</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Fecha Regreso</label>
                                <input type="date" name="fecha_vuelo_salida" class="input-premium">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Hora de Recogida</label>
                                <input type="time" name="hora_partida" class="input-premium">
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-bold text-muted">Vuelo Regreso</label>
                                <input type="text" name="numero_vuelo_salida" class="input-premium" placeholder="Ex: IB3245">
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-car-side me-2"></i> Vehículo y Extras
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold small mb-2 d-block">Vehículo</label>
                        <select class="form-select input-premium" id="id_vehiculo" name="id_vehiculo" required>
                            <option value="">-- Seleccione un vehículo disponible --</option>
                            @foreach ($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}" data-capacidad="{{ $vehiculo->capacidad }}">
                                    {{ $vehiculo->descripcion }} (Capacidad: {{ $vehiculo->capacidad }} pax)
                                </option>
                            @endforeach
                        </select>
                        <div id="no-vehiculos" class="alert alert-danger mt-3 border-0 py-3 small shadow-sm" style="display:none; border-radius: 12px;">
                            <i class="fas fa-times-circle me-2"></i> El número de pasajeros excede la capacidad de los vehículos disponibles.
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="fw-bold small mb-2 d-block">Observaciones Especiales</label>
                        <textarea name="observaciones" class="input-premium" rows="2" placeholder="Silla de bebé, maletas extra, etc."></textarea>
                    </div>

                    <button type="submit" class="btn-submit-premium" id="btnConfirmar">
                        CONFIRMAR Y FINALIZAR RESERVA
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectTipo = document.getElementById('id_tipo_reserva');
    const bIda = document.getElementById('bloque_ida');
    const bVuelta = document.getElementById('bloque_vuelta');
    const inputPax = document.getElementById('num_viajeros');
    const selectVehiculo = document.getElementById('id_vehiculo');
    const aviso = document.getElementById('no-vehiculos');
    const form = document.getElementById('reservaForm');
    const btnConfirmar = document.getElementById('btnConfirmar');

    function refresh() {
        const val = selectTipo.value;
        const pax = parseInt(inputPax.value) || 0;
        let disponibles = 0;

        // Mostrar/Ocultar bloques según tipo de reserva
        // Ajustar IDs si son distintos en tu BD (1=Ida, 2=Vuelta, 3=Ida/Vuelta)
        bIda.style.display = (val == "1" || val == "3") ? "block" : "none";
        bVuelta.style.display = (val == "2" || val == "3") ? "block" : "none";

        // Filtrar vehículos por capacidad
        Array.from(selectVehiculo.options).forEach(opt => {
            if (opt.value === "") return;
            const cap = parseInt(opt.dataset.capacidad);
            if (pax > cap) {
                opt.style.display = 'none';
                opt.disabled = true;
                if (selectVehiculo.value == opt.value) selectVehiculo.value = "";
            } else {
                opt.style.display = 'block';
                opt.disabled = false;
                disponibles++;
            }
        });

        aviso.style.display = (disponibles === 0 && pax > 0) ? "block" : "none";
    }

    // Evitar doble envío
    form.addEventListener('submit', function() {
        btnConfirmar.disabled = true;
        btnConfirmar.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> PROCESANDO...';
    });

    selectTipo.addEventListener('change', refresh);
    inputPax.addEventListener('input', refresh);
    
    refresh(); 
});
</script>
@endsection