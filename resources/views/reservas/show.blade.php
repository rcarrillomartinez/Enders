@extends('layouts.app')

@section('title', 'Detalles de Reserva')

@section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Detalles de la Reserva</h4>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h6 class="text-muted">Localizador</h6>
                            <p><strong>{{ $reserva->localizador }}</strong></p>
                        </div>
                        <div class="col-md-6">
                            <h6 class="text-muted">Estado</h6>
                            <p>
                                <span class="badge bg-{{ $reserva->estado === 'confirmada' ? 'success' : ($reserva->estado === 'cancelada' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <hr>

                    <h5>Información del Cliente</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Nombre</h6>
                            <p>{{ $reserva->nombre_cliente }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Apellidos</h6>
                            <p>{{ $reserva->apellido1_cliente }} {{ $reserva->apellido2_cliente }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Email</h6>
                            <p>{{ $reserva->email_cliente }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Número de Viajeros</h6>
                            <p>{{ $reserva->num_viajeros ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <hr>

                    <h5>Información de la Reserva</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Hotel</h6>
                            <p>{{ $reserva->hotel->nombre_hotel ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Tipo de Reserva</h6>
                            <p>{{ $reserva->tipoReserva->nombre ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Fecha Entrada</h6>
                            <p>{{ $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y H:i') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Vuelo Entrada</h6>
                            <p>{{ $reserva->numero_vuelo_entrada ?? 'N/A' }} ({{ $reserva->origen_vuelo_entrada ?? 'N/A' }})</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Fecha Salida</h6>
                            <p>{{ $reserva->fecha_vuelo_salida ? \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Vehículo</h6>
                            <p>{{ $reserva->vehiculo->tipo_vehiculo ?? 'N/A' }} ({{ $reserva->vehiculo->matricula ?? 'N/A' }})</p>
                        </div>
                    </div>

                    <hr>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Fecha de Reserva</h6>
                            <p>{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <h6 class="text-muted">Última Modificación</h6>
                            <p>{{ \Carbon\Carbon::parse($reserva->fecha_modificacion)->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Acciones</h5>
                </div>
                <div class="card-body">
                    <a href="{{ route('reservas.index') }}" class="btn btn-outline-primary w-100 mb-2">Volver a Reservas</a>
                    @if (session('user_type') === 'admin')
                        <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn btn-warning w-100 mb-2">Editar</a>
                        <form action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger w-100" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
