@extends('layouts.app')

@section('title', 'Registrar Hotel')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Registrar Hotel (Admin)</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.hotels.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario" value="{{ old('usuario') }}">
                            @error('usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="nombre_hotel" class="form-label">Nombre del Hotel</label>
                            <input type="text" class="form-control @error('nombre_hotel') is-invalid @enderror" id="nombre_hotel" name="nombre_hotel" value="{{ old('nombre_hotel') }}">
                            @error('nombre_hotel')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="id_zona" class="form-label">Zona (opcional)</label>
                            <input type="number" class="form-control" id="id_zona" name="id_zona" value="{{ old('id_zona') }}">
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Crear Hotel</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
