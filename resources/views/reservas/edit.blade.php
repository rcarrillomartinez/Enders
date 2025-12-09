@extends('layouts.app')

@section('title', 'Editar Reserva')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Editar Reserva</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('reservas.update', $reserva->id_reserva) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="localizador" class="form-label">Localizador</label>
                            <input type="text" class="form-control" id="localizador" value="{{ $reserva->localizador }}" disabled>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="id_hotel" class="form-label">Hotel *</label>
                                <select class="form-select @error('id_hotel') is-invalid @enderror" id="id_hotel" name="id_hotel" required>
                                    <option value="">Selecciona un hotel</option>
                                    @foreach ($hotels as $hotel)
                                        <option value="{{ $hotel->id_hotel }}" {{ $reserva->id_hotel == $hotel->id_hotel ? 'selected' : '' }}>
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
                                        <option value="{{ $tipo->id_tipo_reserva }}" {{ $reserva->id_tipo_reserva == $tipo->id_tipo_reserva ? 'selected' : '' }}>
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
                                <label for="num_viajeros" class="form-label">Número de Viajeros</label>
                                <input type="number" class="form-control" id="num_viajeros" name="num_viajeros" value="{{ $reserva->num_viajeros }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="id_vehiculo" class="form-label">Vehículo</label>
                                <select class="form-select" id="id_vehiculo" name="id_vehiculo">
                                    <option value="">Selecciona un vehículo</option>
                                    @foreach ($vehiculos as $vehiculo)
                                        <option value="{{ $vehiculo->id_vehiculo }}" {{ $reserva->id_vehiculo == $vehiculo->id_vehiculo ? 'selected' : '' }}>
                                            {{ $vehiculo->tipo_vehiculo }} - {{ $vehiculo->matricula }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="estado" class="form-label">Estado *</label>
                            <select class="form-select @error('estado') is-invalid @enderror" id="estado" name="estado" required>
                                <option value="pendiente" {{ $reserva->estado === 'pendiente' ? 'selected' : '' }}>Pendiente</option>
                                <option value="confirmada" {{ $reserva->estado === 'confirmada' ? 'selected' : '' }}>Confirmada</option>
                                <option value="cancelada" {{ $reserva->estado === 'cancelada' ? 'selected' : '' }}>Cancelada</option>
                                <option value="completada" {{ $reserva->estado === 'completada' ? 'selected' : '' }}>Completada</option>
                            </select>
                            @error('estado')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">Actualizar Reserva</button>
                            <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="btn btn-outline-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
