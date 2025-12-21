@extends('layouts.app')

@section('title', 'Nueva Reserva')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between">
                    <h4 class="mb-0">Gestión de Transfers: Nueva Reserva</h4>
                    <span class="badge bg-light text-primary">Localizador automático</span>
                </div>
                <div class="card-body">
                    <form action="{{ route('reservas.store') }}" method="POST" id="reservaForm">
                        @csrf

                        <div class="alert alert-info py-2">Datos del Solicitante</div>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Email *</label>
                                <input type="email" name="email_cliente" class="form-control" value="{{ $userData['email'] }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Nombre *</label>
                                <input type="text" name="nombre_cliente" class="form-control" value="{{ $userData['nombre'] }}" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Apellidos *</label>
                                <input type="text" name="apellido1_cliente" class="form-control" value="{{ $userData['apellido'] }}" required>
                            </div>
                        </div>

                        <hr>

                        <div class="row bg-light p-3 rounded mb-4 border">
                            <div class="col-md-5 mb-3">
                                <label for="id_tipo_reserva" class="form-label fw-bold">Tipo de Trayecto *</label>
                                <select class="form-select border-primary" id="id_tipo_reserva" name="id_tipo_reserva" required>
                                    <option value="">-- Selecciona Trayecto --</option>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}" {{ old('id_tipo_reserva') == $tipo->id_tipo_reserva ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="id_hotel" class="form-label fw-bold">Hotel *</label>
                                <select class="form-select" id="id_hotel" name="id_hotel" required>
                                    <option value="">Selecciona un hotel</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id_hotel }}" {{ (auth()->user()->id_hotel_asociado ?? null) == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nombre_hotel }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="num_viajeros" class="form-label fw-bold">Nº Pasajeros *</label>
                                <input type="number" class="form-control" id="num_viajeros" name="num_viajeros" min="1" value="{{ old('num_viajeros', 1) }}" required>
                            </div>
                        </div>

                        <div id="bloque_ida" class="card border-info mb-4 shadow-sm" style="display:none;">
                            <div class="card-header bg-info text-white py-1">Datos de Llegada (Aeropuerto → Hotel)</div>
                            <div class="card-body row">
                                <div class="col-md-3 mb-2"><label>Fecha Entrada *</label><input type="date" name="fecha_entrada" class="form-control" value="{{ date('Y-m-d') }}"></div>
                                <div class="col-md-3 mb-2"><label>Hora Vuelo *</label><input type="time" name="hora_entrada" class="form-control" value="12:00"></div>
                                <div class="col-md-3 mb-2"><label>Nº Vuelo *</label><input type="text" name="numero_vuelo_entrada" class="form-control" placeholder="Ej: IB1234"></div>
                                <div class="col-md-3 mb-2"><label>Origen *</label><input type="text" name="origen_vuelo_entrada" class="form-control" value="Madrid"></div>
                            </div>
                        </div>

                        <div id="bloque_vuelta" class="card border-success mb-4 shadow-sm" style="display:none;">
                            <div class="card-header bg-success text-white py-1">Datos de Salida (Hotel → Aeropuerto)</div>
                            <div class="card-body row">
                                <div class="col-md-3 mb-2"><label>Fecha Vuelo *</label><input type="date" name="fecha_vuelo_salida" class="form-control" value="{{ date('Y-m-d', strtotime('+7 days')) }}"></div>
                                <div class="col-md-3 mb-2"><label>Hora Vuelo *</label><input type="time" name="hora_vuelo_salida" class="form-control" value="15:00"></div>
                                <div class="col-md-3 mb-2"><label>Nº Vuelo Salida *</label><input type="text" name="numero_vuelo_salida" class="form-control"></div>
                                <div class="col-md-3 mb-2"><label>Hora Recogida *</label><input type="time" name="hora_partida" class="form-control" value="12:00"></div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Seleccionar Vehículo *</label>
                            <select class="form-select border-primary" id="id_vehiculo" name="id_vehiculo" required>
                                <option value="">-- Selecciona el vehículo --</option>
                                @foreach ($vehiculos as $vehiculo)
                                    <option value="{{ $vehiculo->id_vehiculo }}" data-capacidad="{{ $vehiculo->capacidad }}">
                                        {{ $vehiculo->descripcion }} (Máx: {{ $vehiculo->capacidad }} pax)
                                    </option>
                                @endforeach
                            </select>
                            <div id="no-vehiculos" class="text-danger mt-2" style="display:none;">
                                No hay vehículos disponibles para este número de pasajeros.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow">Confirmar Reserva</button>
                    </form>
                </div>
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

        // Mostrar/Ocultar bloques
        bIda.style.display = (val == "1" || val == "3") ? "block" : "none";
        bVuelta.style.display = (val == "2" || val == "3") ? "block" : "none";

        // Filtrar Vehículos por capacidad
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