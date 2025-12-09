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

                        <input type="hidden" name="user_type" value="viajero">

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
            // No hotel registration option on public form; show viajero fields by default
            document.getElementById('viajero-fields').style.display = 'block';
        </script>
    @endsection
@endsection
