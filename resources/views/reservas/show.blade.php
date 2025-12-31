@extends('layouts.app')

@section('title', 'Reserva ' . $reserva->localizador)

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-accent: #3b82f6;
        --bg-main: #f1f5f9;
        --text-dark: #0f172a;
        --text-muted: #64748b;
    }

    body { 
        background-color: var(--bg-main); 
        font-family: 'Plus Jakarta Sans', sans-serif;
        color: var(--text-dark);
    }

    .reveal { animation: reveal 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }

    @keyframes reveal {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .premium-card {
        background: #ffffff;
        border-radius: 30px;
        border: none;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.04);
        transition: transform 0.3s ease;
    }

    .brand-header-premium {
        background: var(--brand-navy);
        padding: 2.5rem;
        color: white;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .user-profile-img {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        object-fit: cover;
        border: 3px solid #ffffff; 
        background: var(--brand-navy);
    }

    .info-label {
        font-size: 0.65rem;
        font-weight: 800;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 1.2px;
        margin-bottom: 0.5rem;
        display: block;
    }

    .value-main { font-weight: 700; font-size: 1.15rem; color: var(--brand-navy); }

    .timeline-premium {
        background: #f8fafc;
        border-radius: 24px;
        padding: 2rem;
        border: 1px solid #f1f5f9;
        margin-bottom: 2rem;
    }

    .icon-box {
        width: 48px; height: 48px;
        background: white;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        color: var(--brand-navy); 
        box-shadow: 0 4px 10px rgba(0,0,0,0.03);
        margin-bottom: 1rem;
    }

    .status-pill {
        padding: 6px 16px;
        border-radius: 50px;
        font-weight: 800;
        font-size: 0.7rem;
        text-transform: uppercase;
    }
    .status-confirmada { background: #dcfce7; color: #15803d; }
    .status-pendiente { background: #fef9c3; color: #a16207; }

    .btn-action {
        border-radius: 16px;
        padding: 14px 24px;
        font-weight: 700;
        transition: all 0.3s;
        border: none;
        width: 100%;
        text-decoration: none;
        display: inline-block;
        text-align: center;
    }
    .btn-primary-navy { background: var(--brand-navy); color: white; }
    .btn-primary-navy:hover { background: #1e293b; transform: translateY(-2px); color: white; }
    
    .sidebar-sticky { position: sticky; top: 2rem; }

    .text-primary { color: var(--brand-navy) !important; }
    .alert-primary { background: #f8fafc; color: var(--brand-navy); border: 1px solid #e2e8f0; }

    @media print {
        .no-print { display: none !important; }
        body { background: white; }
        .premium-card { box-shadow: none; border: 1px solid #eee; }
    }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4 px-2 no-print reveal">
        <div>
            <h2 class="fw-800 text-dark mb-0">Reserva #{{ $reserva->localizador }}</h2>
            <p class="text-muted small mb-0">Gestiona los detalles del servicio de transfer</p>
        </div>
        <a href="{{ route('reservas.index') }}" class="btn btn-white border rounded-pill px-4 fw-bold text-muted shadow-sm">
            <i class="fas fa-chevron-left me-2"></i> Volver
        </a>
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div class="premium-card reveal delay-1">
                @php
                    // LÓGICA DE USUARIO Y FOTO (Sincronizada con modelo Viajero)
                    $user = Auth::user();
                    if (!$user || (session('user_type') === 'admin')) {
                        $viajeroOwner = \App\Models\Viajero::where('email', $reserva->email_cliente)->first();
                        $targetUser = $viajeroOwner ?: $user;
                    } else {
                        $targetUser = $user;
                    }

                    $displayName = $targetUser ? $targetUser->nombre : ($reserva->nombre_cliente ?? 'Invitado');
                    
                    // Avatar con fondo Navy para coherencia
                    $profilePhoto = ($targetUser && $targetUser->foto) 
                                    ? asset('storage/' . $targetUser->foto) 
                                    : "https://ui-avatars.com/api/?name=".urlencode($displayName)."&background=0f172a&color=fff&bold=true";

                    $direccionCliente = ($targetUser && $targetUser->direccion) 
                                        ? $targetUser->direccion 
                                        : ($reserva->direccion_cliente ?? 'No especificada');
                @endphp

                <div class="brand-header-premium">
                    <div>
                        <h4 class="mb-0 fw-800" style="letter-spacing: -0.5px;">ENDER <span class="fw-300">TRANSFER</span></h4>
                        <p class="mb-0 opacity-75 small">Voucher Oficial de Servicio</p>
                    </div>
                    <div class="d-flex align-items-center">
                        <div class="text-end me-3 d-none d-md-block">
                            <p class="mb-0 small opacity-75">Pasajero:</p>
                            <p class="mb-0 fw-bold">{{ $displayName }}</p>
                        </div>
                        <img src="{{ $profilePhoto }}" class="user-profile-img shadow-sm" alt="Profile">
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <div class="row g-4 mb-5">
                        <div class="col-md-4 text-center text-md-start">
                            <span class="info-label">Estado del Servicio</span>
                            <span class="status-pill status-{{ strtolower($reserva->estado) }}">{{ $reserva->estado }}</span>
                        </div>
                        <div class="col-md-4 text-center text-md-start border-start border-end">
                            <span class="info-label">Pasajeros</span>
                            <div class="value-main"><i class="fas fa-users me-2 opacity-25"></i>{{ $reserva->num_viajeros }} PAX</div>
                        </div>
                        <div class="col-md-4 text-center text-md-start">
                            <span class="info-label">Destino / Hotel</span>
                            <div class="value-main text-truncate"><i class="fas fa-hotel me-2 opacity-25"></i>{{ $reserva->hotel->nombre_hotel ?? 'Punto acordado' }}</div>
                        </div>
                    </div>

                    <div class="timeline-premium">
                        <div class="row g-4 align-items-center text-center text-md-start">
                            @if(in_array($reserva->id_tipo_reserva, [1, 3]))
                            <div class="col-md-5">
                                <div class="icon-box mx-auto mx-md-0"><i class="fas fa-plane-arrival"></i></div>
                                <span class="info-label">Vuelo de Llegada</span>
                                <div class="h3 fw-800 text-primary mb-1">{{ $reserva->hora_entrada ?? '--:--' }}</div>
                                <div class="fw-bold text-dark mb-2">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d M, Y') }}</div>
                                <span class="badge bg-white text-muted border rounded-pill px-3 py-2 small fw-bold">
                                    <i class="fas fa-tag me-1 text-primary"></i> {{ $reserva->numero_vuelo_entrada ?? 'Privado' }}
                                </span>
                            </div>
                            @endif

                            @if($reserva->id_tipo_reserva == 3)
                            <div class="col-md-2 text-center d-none d-md-block">
                                <i class="fas fa-exchange-alt opacity-10 fa-2x"></i>
                            </div>
                            @endif

                            @if(in_array($reserva->id_tipo_reserva, [2, 3]))
                            <div class="col-md-5 text-md-end">
                                <div class="icon-box ms-auto mx-auto mx-md-0"><i class="fas fa-plane-departure"></i></div>
                                <span class="info-label">Vuelo de Regreso</span>
                                <div class="h3 fw-800 mb-1" style="color: var(--brand-navy)">{{ $reserva->hora_partida ?? 'Pendiente' }}</div>
                                <div class="fw-bold text-dark mb-2">{{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d M, Y') }}</div>
                                <span class="badge bg-white text-muted border rounded-pill px-3 py-2 small fw-bold">
                                    <i class="fas fa-tag me-1 text-danger"></i> {{ $reserva->numero_vuelo_salida ?? 'Sin Vuelo' }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 d-flex align-items-center">
                                <div class="icon-box mb-0 me-3 shadow-none bg-white"><i class="fas fa-car-side"></i></div>
                                <div>
                                    <span class="info-label mb-0">Vehículo</span>
                                    <div class="fw-bold small">{{ $reserva->vehiculo->descripcion ?? 'Por asignar' }}</div>
                                    <div class="small text-primary fw-600">{{ $reserva->vehiculo->email_conductor ?? '' }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-4 d-flex align-items-center">
                                <div class="icon-box mb-0 me-3 shadow-none bg-white"><i class="fab fa-whatsapp text-success"></i></div>
                                <div>
                                    <span class="info-label mb-0">Soporte 24/7</span>
                                    <div class="fw-bold small">Atención Ender Transfer</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="premium-card mt-4 p-4 reveal delay-2">
                <div class="d-flex align-items-center flex-wrap">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($reserva->nombre_cliente) }}&background=0f172a&color=fff&bold=true" class="rounded-circle me-4 border shadow-sm mb-3 mb-md-0" width="60" alt="Cliente">
                    <div class="flex-grow-1">
                        <span class="info-label mb-0">Titular de la reserva</span>
                        <h4 class="fw-bold text-dark mb-1">{{ $reserva->nombre_cliente }} {{ $reserva->apellido1_cliente }}</h4>
                        <div class="d-flex gap-3 flex-wrap">
                            <span class="small text-muted"><i class="fas fa-envelope me-1 text-primary"></i> {{ $reserva->email_cliente }}</span>
                            <span class="small text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $direccionCliente }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 no-print">
            <div class="sidebar-sticky reveal delay-2">
                <div class="premium-card p-4">
                    <h6 class="fw-800 text-dark mb-4 border-bottom pb-2">Panel de Gestión</h6>
                    
                    <div class="d-grid gap-3">
                        @if (session('user_type') === 'admin')
                            <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn-action btn-primary-navy">
                                <i class="fas fa-pen-fancy me-2"></i> Editar Datos
                            </a>
                            
                            <button class="btn btn-white border rounded-4 py-3 fw-bold text-dark shadow-sm" onclick="window.print()">
                                <i class="fas fa-file-pdf me-2 text-primary"></i> Generar Voucher
                            </button>

                            <div class="bg-light p-3 rounded-4 text-center border border-dashed">
                                <span class="info-label mb-1">Registrada el</span>
                                <p class="fw-bold small mb-0">{{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}</p>
                            </div>

                            <form action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST" class="mt-2" onsubmit="return confirm('¿Eliminar definitivamente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger w-100 text-decoration-none fw-bold small">
                                    <i class="fas fa-trash-alt me-1"></i> Eliminar Reserva
                                </button>
                            </form>
                        @else
                            <button class="btn-action btn-primary-navy shadow-lg" onclick="window.print()">
                                <i class="fas fa-download me-2"></i> Descargar Voucher
                            </button>
                            <div class="alert alert-primary border-0 rounded-4 p-3 mt-2 shadow-sm">
                                <div class="d-flex">
                                    <i class="fas fa-info-circle me-3 mt-1"></i>
                                    <p class="small mb-0 fw-600">Presente este documento a su llegada para agilizar el servicio.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection