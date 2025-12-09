@extends('layouts.app')

@section('title', 'Iniciar Sesión')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Iniciar Sesión</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('login.post') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="user_type" class="form-label">Tipo de Usuario</label>
                            <select class="form-select @error('user_type') is-invalid @enderror" id="user_type" name="user_type" required>
                                <option value="">Selecciona un tipo</option>
                                <option value="viajero">Viajero</option>
                                <option value="hotel">Hotel</option>
                                <option value="admin">Administrador</option>
                            </select>
                            @error('user_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="email-field" style="display:none;">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3" id="usuario-field" style="display:none;">
                            <label for="usuario" class="form-label">Usuario</label>
                            <input type="text" class="form-control @error('usuario') is-invalid @enderror" id="usuario" name="usuario">
                            @error('usuario')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Contraseña</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Iniciar Sesión</button>
                    </form>

                    <hr>

                    <p class="text-center mb-0">
                        ¿No tienes cuenta? <a href="{{ route('auth.register') }}">Regístrate aquí</a>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @section('scripts')
        <script>
            document.getElementById('user_type').addEventListener('change', function() {
                document.getElementById('email-field').style.display = this.value === 'hotel' ? 'none' : 'block';
                document.getElementById('usuario-field').style.display = this.value === 'hotel' ? 'block' : 'none';
            });
        </script>
    @endsection
@endsection
