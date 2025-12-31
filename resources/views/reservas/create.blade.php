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

    .animate-page {
        animation: slideUp 0.6s ease-out;
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .reserva-container {
        max-width: 900px;
        margin: 0 auto;
    }

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

    .animate-takeoff {
        animation: takeoff 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    @keyframes takeoff {
        0% { transform: translate(-20px, 20px); opacity: 0; }
        100% { transform: translate(0, 0); opacity: 1; }
    }

    .animate-landing {
        animation: landing 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
    }

    @keyframes landing {
        0% { transform: translate(20px, -20px); opacity: 0; }
        100% { transform: translate(0, 0); opacity: 1; }
    }

    .flight-icon-badge {
        width: 45px;
        height: 45px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        margin-right: 15px; background: var(--brand-navy); color: white;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.2);
    }

    .form-section-title {
        font-size: 0.8rem; font-weight: 800; color: var(--brand-navy);
        text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 1.5rem;
        display: flex; align-items: center;
    }

    .form-section-title::after {
        content: ""; flex: 1; height: 1px; background: #e2e8f0; margin-left: 15px;
    }

    .input-premium {
        border: 1px solid #cbd5e1; border-radius: 12px; padding: 0.8rem 1.1rem;
        font-size: 0.95rem; font-weight: 500; transition: all 0.3s ease;
        background-color: #ffffff;
    }

    .input-premium:focus {
        border-color: var(--brand-navy); box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.08); outline: none;
    }

    .btn-submit-premium {
        background: var(--brand-navy); color: white; border: none; padding: 1.2rem;
        border-radius: 16px; font-weight: 700; font-size: 1rem; letter-spacing: 1px;
        width: 100%; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); margin-top: 1rem;
    }

    .btn-submit-premium:hover {
        transform: translateY(-3px); box-shadow: 0 12px 20px -5px rgba(15, 23, 42, 0.25);
        background: var(--brand-hover);
    }
</style>

<div class="container py-5 animate-page">
    <div class="reserva-container">
        <div class="glass-card">
            
            <div class="brand-header d-flex justify-content-between align-items-center text-white">
                <div>
                    <h3 class="mb-1"><i class="fas fa-paper-plane me-2"></i>Nueva Reserva</h3>
                    <p class="mb-0 small">Enders Transfer Solutions &bull; Gestión Premium</p>
                </div>
                <div class="status-pill" style="background: rgba(255,255,255,0.1); padding: 8px 16px; border-radius: 50px; border: 1px solid rgba(255,255,255,0.2); font-size: 0.75rem; font-weight: 700;">
                    <i class="fas fa-lock me-2"></i>SECURE CHECKOUT
                </div>
            </div>

            <div class="p-4 p-md-5">
                {{-- LÓGICA DE DETECCIÓN DE USUARIO --}}
                @php 
                    $user = auth('viajero')->user() ?? auth()->user(); 
                @endphp

                <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                    @csrf

                    <div class="form-section-title">
                        <i class="fas fa-user-circle me-2"></i> Datos del Pasajero Principal
                    </div>
                    
                    <div class="row g-4 mb-5">
                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Nombre</label>
                            <input type="text" name="nombre_cliente" class="form-control input-premium" 
                                   value="{{ $user->nombre ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Primer Apellido</label>
                            <input type="text" name="apellido1_cliente" class="form-control input-premium" 
                                   value="{{ $user->apellido1 ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Segundo Apellido</label>
                            <input type="text" name="apellido2_cliente" class="form-control input-premium" 
                                   value="{{ $user->apellido2 ?? '' }}">
                        </div>

                        <div class="col-md-8">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Dirección de Residencia</label>
                            <input type="text" name="direccion_cliente" class="form-control input-premium" 
                                   value="{{ $user->direccion ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Código Postal</label>
                            <input type="text" name="cp_cliente" class="form-control input-premium" 
                                   value="{{ $user->codigoPostal ?? '' }}" required>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Ciudad</label>
                            <input type="text" name="ciudad_cliente" class="form-control input-premium" 
                                   value="{{ $user->ciudad ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">País</label>
                            <input type="text" name="pais_cliente" class="form-control input-premium" 
                                   value="{{ $user->pais ?? '' }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Email de Contacto</label>
                            <input type="email" name="email_cliente" class="form-control input-premium" 
                                   value="{{ $user->email ?? '' }}" required>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-route me-2"></i> Configuración del Itinerario
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-5">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Seleccione Trayecto</label>
                            <select class="form-select input-premium" id="id_tipo_reserva" name="id_tipo_reserva" required>
                                <option value="" disabled selected>Seleccione...</option>
                                @foreach ($tiposReserva as $tipo)
                                    <option value="{{ $tipo->id_tipo_reserva }}">{{ $tipo->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Hotel Referencia</label>
                            <select class="form-select input-premium" id="id_hotel" name="id_hotel" required>
                                @foreach ($hotels as $hotel)
                                    <option value="{{ $hotel->id_hotel }}" {{ ($user->id_hotel_asociado ?? null) == $hotel->id_hotel ? 'selected' : '' }}>
                                        {{ $hotel->nombre_hotel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-premium fw-bold small mb-2 d-block">Pasajeros</label>
                            <input type="number" class="form-control input-premium" id="num_viajeros" name="num_viajeros" min="1" value="1" required>
                        </div>
                    </div>

                    <div id="bloque_ida" class="flight-block p-4 mb-4" style="display:none;">
                        <div class="d-flex align-items-center mb-4 animate-landing">
                            <div class="flight-icon-badge"><i class="fas fa-plane-arrival"></i></div>
                            <h6 class="mb-0 fw-bold">Detalles de Llegada (Vuelo de Entrada)</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Fecha Llegada</label>
                                <input type="date" name="fecha_entrada" class="form-control input-premium">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Hora Estimada</label>
                                <input type="time" name="hora_entrada" class="form-control input-premium">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Nº Vuelo</label>
                                <input type="text" name="numero_vuelo_entrada" class="form-control input-premium" placeholder="Ex: IB3244">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Origen</label>
                                <input type="text" name="origen_vuelo_entrada" class="form-control input-premium">
                            </div>
                        </div>
                    </div>

                    <div id="bloque_vuelta" class="flight-block p-4 mb-4" style="display:none;">
                        <div class="d-flex align-items-center mb-4 animate-takeoff">
                            <div class="flight-icon-badge"><i class="fas fa-plane-departure"></i></div>
                            <h6 class="mb-0 fw-bold">Detalles de Salida (Retorno al Aeropuerto)</h6>
                        </div>
                        <div class="row g-3">
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Fecha Salida</label>
                                <input type="date" name="fecha_vuelo_salida" class="form-control input-premium">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Hora de Vuelo</label>
                                <input type="time" name="hora_vuelo_salida" class="form-control input-premium">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Nº Vuelo</label>
                                <input type="text" name="numero_vuelo_salida" class="form-control input-premium">
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold text-muted">Recogida Hotel</label>
                                <input type="time" name="hora_partida" class="form-control input-premium">
                            </div>
                        </div>
                    </div>

                    <div class="form-section-title">
                        <i class="fas fa-car-side me-2"></i> Selección de Vehículo
                    </div>

                    <div class="mb-5">
                        <select class="form-select input-premium" id="id_vehiculo" name="id_vehiculo" required>
                            <option value="">-- Seleccione un vehículo disponible --</option>
                            @foreach ($vehiculos as $vehiculo)
                                <option value="{{ $vehiculo->id_vehiculo }}" data-capacidad="{{ $vehiculo->capacidad }}">
                                    {{ $vehiculo->descripcion }} (Máximo {{ $vehiculo->capacidad }} pax)
                                </option>
                            @endforeach
                        </select>
                        <div id="no-vehiculos" class="alert alert-danger mt-3 border-0 py-3 small shadow-sm" style="display:none; border-radius: 12px;">
                            <i class="fas fa-times-circle me-2"></i> El número de pasajeros excede la capacidad de los vehículos disponibles.
                        </div>
                    </div>

                    <button type="submit" class="btn-submit-premium">
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

    function refresh() {
        const val = selectTipo.value;
        const pax = parseInt(inputPax.value) || 0;
        let disponibles = 0;

        bIda.style.display = (val == "1" || val == "3") ? "block" : "none";
        bVuelta.style.display = (val == "2" || val == "3") ? "block" : "none";

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

    selectTipo.addEventListener('change', refresh);
    inputPax.addEventListener('input', refresh);
    refresh(); 
});
</script>
@endsection