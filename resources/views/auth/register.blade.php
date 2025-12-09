@extends('layouts.app')

@section('title', 'Registro')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Registro</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('auth.register.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="user_type" class="form-label">Tipo de Usuario</label>
                            <select class="form-select @error('user_type') is-invalid @enderror" id="user_type" name="user_type" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="viajero">Viajero</option>
                                <option value="hotel">Hotel</option>
                            </select>
                            @error('user_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Campos de Viajero -->
                        <div id="viajero-fields" style="display:none;">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Nombre</label>
                                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre">
                                    @error('nombre')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="apellido1" class="form-label">Apellido 1</label>
                                    <input type="text" class="form-control @error('apellido1') is-invalid @enderror" id="apellido1" name="apellido1">
                                    @error('apellido1')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="apellido2" class="form-label">Apellido 2</label>
                                    <input type="text" class="form-control" id="apellido2" name="apellido2">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="ciudad" class="form-label">Ciudad</label>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="codigoPostal" class="form-label">Código Postal</label>
                                    <input type="text" class="form-control" id="codigoPostal" name="codigoPostal">
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="pais" class="form-label">País</label>
                                    <input type="text" class="form-control" id="pais" name="pais">
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control" id="direccion" name="direccion">
                            </div>
                        </div>

                        <!-- Campos de Hotel -->
                        <div id="hotel-fields" style="display:none;">
                            <div class="mb-3">
                                <label for="usuario" class="form-label">Usuario</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario">
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="nombre_hotel" class="form-label">Nombre del Hotel</label>
                                <input type="text" class="form-control @error('nombre_hotel') is-invalid @enderror" id="nombre_hotel" name="nombre_hotel">
                                @error('nombre_hotel')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="id_zona" class="form-label">Zona</label>
                                <input type="number" class="form-control" id="id_zona" name="id_zona">
                            </div>
                        </div>

                        <!-- Campos Comunes -->
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

                        <button type="submit" class="btn btn-primary w-100">Registrarse</button>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                                                ¿Ya tienes cuenta? <a href="{{ route('login') }}">Inicia sesión aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.getElementById('user_type').addEventListener('change', function() {
                document.getElementById('viajero-fields').style.display = this.value === 'viajero' ? 'block' : 'none';
                document.getElementById('hotel-fields').style.display = this.value === 'hotel' ? 'block' : 'none';
            });
        </script>
    @endsection
@endsection
