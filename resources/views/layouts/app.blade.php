<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') Enders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --brand-navy: #0f172a;
            --brand-accent: #ffffff;
        }

        body {
            background-color: #f8fafc;
            font-family: 'Inter', sans-serif;
        }

        .navbar {
            background-color: var(--brand-navy) !important;
            padding: 0.8rem 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .navbar-brand {
            font-weight: 800;
            letter-spacing: 1.5px;
            color: white !important;
            text-transform: uppercase;
        }

        .user-name-display {
            color: white !important;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            display: flex;
            align-items: center;
            opacity: 0.9;
        }

        .user-name-display i {
            font-size: 1.1rem;
            margin-right: 8px;
            color: rgba(255,255,255,0.7);
        }

        .nav-link {
            color: rgba(255,255,255,0.8) !important;
            font-weight: 500;
            position: relative;
            margin: 0 5px;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            height: 100%;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px;
            left: 0;
            background-color: white;
            transition: width 0.3s ease;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link:hover {
            color: white !important;
        }

        .btn-logout-nav {
            background: none !important;
            border: none !important;
            padding: 0.5rem 1rem !important;
            font-size: inherit;
            line-height: inherit;
            display: flex;
            align-items: center;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-in { animation: fadeInUp 0.5s ease forwards; opacity: 0; }
        .delay-1 { animation-delay: 0.1s; }
        .delay-2 { animation-delay: 0.2s; }

        main { min-height: calc(100vh - 160px); }
    </style>
    @yield('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container animate-in">
            <a class="navbar-brand" href="/">
                <i class="fas fa-shipping-fast me-2"></i>ENDERS
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    @php
                        // Obtenemos el usuario autenticado según el guard
                        $user = Auth::guard('viajero')->user() ?? Auth::guard('admin')->user() ?? Auth::guard('hotel')->user();
                        $nombreMostrar = $user ? ($user->nombre ?? $user->nombre_hotel ?? $user->email) : 'Usuario';
                    @endphp

                    @if ($user)
                        <li class="nav-item me-3 d-none d-lg-block">
                            <span class="user-name-display">
                                <i class="fas fa-circle-user text-white"></i> {{ $nombreMostrar }}
                            </span>
                        </li>

                        @if (Auth::guard('viajero')->check() || Auth::guard('admin')->check())
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('reservas.index') ? 'active' : '' }}" href="{{ route('reservas.index') }}">Reservas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('reservas.calendar') ? 'active' : '' }}" href="{{ route('reservas.calendar') }}">Calendario</a>
                            </li>
                            @if (session('user_type') === 'admin')
                                <li class="nav-item">
                                    <a class="nav-link {{ Route::is('admin.hotels.list') ? 'active' : '' }}" href="{{ route('admin.hotels.list') }}">Hoteles</a>
                                </li>
                            @endif
                        @elseif (Auth::guard('hotel')->check())
                            <li class="nav-item">
                                <a class="nav-link {{ Route::is('hotel.dashboard') ? 'active' : '' }}" href="{{ route('hotel.dashboard') }}">Panel</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <a class="nav-link {{ Route::is('profile.show') ? 'active' : '' }}" href="{{ route('profile.show') }}">Perfil</a>
                        </li>

                        <li class="nav-item">
                            <form action="{{ route('auth.logout') }}" method="POST" class="m-0 p-0">
                                @csrf
                                <button type="submit" class="nav-link btn-logout-nav">
                                    <i class="fas fa-sign-out-alt me-1 d-lg-none"></i> Cerrar sesión
                                </button>
                            </form>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <main class="container my-5">
        <div class="animate-in delay-1">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                    <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>

        <div class="animate-in delay-2">
            @yield('content')
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>