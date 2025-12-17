@extends('layouts.app')

@section('title', 'Nueva Reserva')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Nueva Reserva</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('reservas.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="nombre_cliente" class="form-label">Nombre Cliente *</label>
                                <input type="text" class="form-control @error('nombre_cliente') is-invalid @enderror" id="nombre_cliente" name="nombre_cliente" value="{{ old('nombre_cliente') }}" required>
                                @error('nombre_cliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="apellido1_cliente" class="form-label">Apellido 1 *</label>
                                <input type="text" class="form-control @error('apellido1_cliente') is-invalid @enderror" id="apellido1_cliente" name="apellido1_cliente" value="{{ old('apellido1_cliente') }}" required>
                                @error('apellido1_cliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="apellido2_cliente" class="form-label">Apellido 2</label>
                                <input type="text" class="form-control" id="apellido2_cliente" name="apellido2_cliente" value="{{ old('apellido2_cliente') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email_cliente" class="form-label">Email *</label>
                                <input type="email" class="form-control @error('email_cliente') is-invalid @enderror" id="email_cliente" name="email_cliente" value="{{ old('email_cliente', session('user_email') ?? '') }}" required>
                                @error('email_cliente')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <hr>

                        <h5>Detalles de la Reserva</h5>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_hotel" class="form-label">Hotel *</label>
                                <select class="form-select @error('id_hotel') is-invalid @enderror" id="id_hotel" name="id_hotel" required>
                                    <option value="">Selecciona un hotel</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id_hotel }}" {{ old('id_hotel') == $hotel->id_hotel ? 'selected' : '' }}>
                                            {{ $hotel->nombre_hotel }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_hotel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_tipo_reserva" class="form-label">Tipo de Reserva *</label>
                                <select class="form-select @error('id_tipo_reserva') is-invalid @enderror" id="id_tipo_reserva" name="id_tipo_reserva" required>
                                    <option value="">Selecciona un tipo</option>
                                    @foreach ($tiposReserva as $tipo)
                                        <option value="{{ $tipo->id_tipo_reserva }}" {{ old('id_tipo_reserva') == $tipo->id_tipo_reserva ? 'selected' : '' }}>
                                            {{ $tipo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_tipo_reserva')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_entrada" class="form-label">Fecha de Entrada</label>
                                <input type="date" class="form-control @error('fecha_entrada') is-invalid @enderror" id="fecha_entrada" name="fecha_entrada" value="{{ old('fecha_entrada') }}">
                                @error('fecha_entrada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hora_entrada" class="form-label">Hora de Entrada</label>
                                <input type="time" class="form-control @error('hora_entrada') is-invalid @enderror" id="hora_entrada" name="hora_entrada" value="{{ old('hora_entrada') }}">
                                @error('hora_entrada')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="numero_vuelo_entrada" class="form-label">Número de Vuelo Entrada</label>
                                <input type="text" class="form-control" id="numero_vuelo_entrada" name="numero_vuelo_entrada" value="{{ old('numero_vuelo_entrada') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="origen_vuelo_entrada" class="form-label">Origen Vuelo Entrada</label>
                                <input type="text" class="form-control" id="origen_vuelo_entrada" name="origen_vuelo_entrada" value="{{ old('origen_vuelo_entrada') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="fecha_vuelo_salida" class="form-label">Fecha de Salida</label>
                                <input type="date" class="form-control" id="fecha_vuelo_salida" name="fecha_vuelo_salida" value="{{ old('fecha_vuelo_salida') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="hora_partida" class="form-label">Hora de Partida</label>
                                <input type="time" class="form-control" id="hora_partida" name="hora_partida" value="{{ old('hora_partida') }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="num_viajeros" class="form-label">Número de Viajeros</label>
                                <input type="number" class="form-control" id="num_viajeros" name="num_viajeros" value="{{ old('num_viajeros') }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_vehiculo" class="form-label">Vehículo</label>
                                <select class="form-select" id="id_vehiculo" name="id_vehiculo">
                                    <option value="">Selecciona un vehículo</option>
                                    @foreach ($vehiculos as $vehiculo)
                                        <option value="{{ $vehiculo->id_vehiculo }}" {{ old('id_vehiculo') == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                            {{ $vehiculo->descripcion }} @if(isset($vehiculo->capacidad)) (capacidad: {{ $vehiculo->capacidad }}) @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Crear Reserva</button>
                            <a href="{{ route('reservas.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
