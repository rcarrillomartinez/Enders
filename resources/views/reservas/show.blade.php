@extends('layouts.app')

@section('title', 'Reserva ' . $reserva->localizador)

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid py-4" style="background-color: #f4f7f6; min-height: 100vh;">
    <div class="d-flex justify-content-between align-items-center mb-4 px-3">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-transparent p-0 mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('reservas.index') }}" class="text-decoration-none text-muted">Gestión de Reservas</a></li>
                    <li class="breadcrumb-item active font-weight-bold" aria-current="page">{{ $reserva->localizador }}</li>
                </ol>
            </nav>
            <h2 class="h3 mb-0 text-dark font-weight-bold">Ficha de Reserva</h2>
        </div>
        <a href="{{ route('reservas.index') }}" class="btn btn-white shadow-sm border rounded-pill px-4 transition-all">
            <i class="fas fa-arrow-left fa-sm mr-2 text-primary"></i> Volver al Listado
        </a>
    </div>

    <div class="row px-2">
        <div class="col-lg-8">
            
            <div class="card border-0 shadow-sm mb-4 overflow-hidden" style="border-radius: 15px;">
                <div class="card-body p-4">
                    <div class="row align-items-center">
                        <div class="col-md-4 text-center border-right">
                            <p class="text-muted small text-uppercase font-weight-bold mb-1">Localizador</p>
                            <h4 class="text-primary font-weight-bold mb-0">{{ $reserva->localizador }}</h4>
                        </div>
                        <div class="col-md-4 text-center border-right">
                            <p class="text-muted small text-uppercase font-weight-bold mb-1">Estado actual</p>
                            @php
                                $statusClasses = [
                                    'confirmada' => 'bg-success-light text-success',
                                    'pendiente' => 'bg-warning-light text-warning',
                                    'cancelada' => 'bg-danger-light text-danger',
                                    'completada' => 'bg-info-light text-info'
                                ];
                                $class = $statusClasses[$reserva->estado] ?? 'bg-light text-muted';
                            @endphp
                            <span class="badge {{ $class }} rounded-pill px-3 py-2 text-capitalize">
                                <i class="fas fa-check-circle mr-1"></i> {{ $reserva->estado }}
                            </span>
                        </div>
                        <div class="col-md-4 text-center">
                            <p class="text-muted small text-uppercase font-weight-bold mb-1">Ocupación</p>
                            <h4 class="mb-0 font-weight-bold text-dark"><i class="fas fa-user-friends text-muted mr-2"></i>{{ $reserva->num_viajeros }} <small class="text-muted h6">pax</small></h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3 d-flex align-items-center">
                    <div class="icon-circle bg-primary-light text-primary mr-3 shadow-sm" style="width: 40px; height: 40px;">
                        <i class="fas fa-route"></i>
                    </div>
                    <h6 class="m-0 font-weight-bold text-dark">Detalles del Trayecto</h6>
                </div>
                <div class="card-body px-4">
                    <div class="row mb-4">
                        <div class="col-md-6 mb-3 mb-md-0 border-right">
                            <div class="d-flex align-items-start">
                                <div class="icon-circle bg-primary-light text-primary mr-3 shadow-sm">
                                    <i class="fas fa-hotel"></i>
                                </div>
                                <div>
                                    <label class="text-muted small mb-0 font-weight-bold text-uppercase">Hotel / Destino</label>
                                    <p class="mb-0 font-weight-bold text-dark h5">{{ $reserva->hotel->nombre_hotel ?? 'No asignado' }}</p>
                                    <span class="badge badge-light border text-muted mt-1"><i class="fas fa-tag mr-1 small"></i> {{ $reserva->tipoReserva->nombre ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-start">
                                <div class="icon-circle bg-secondary-light text-secondary mr-3 shadow-sm">
                                    <i class="fas fa-car-side"></i>
                                </div>
                                <div>
                                    <label class="text-muted small mb-0 font-weight-bold text-uppercase">Vehículo y Conductor</label>
                                    @if($reserva->vehiculo)
                                        <p class="mb-0 font-weight-bold text-dark h5">{{ $reserva->vehiculo->descripcion }}</p>
                                        <p class="text-muted small mb-0 mt-1">
                                            <i class="fas fa-envelope text-primary mr-1"></i> 
                                            <span class="font-italic">{{ $reserva->vehiculo->email_conductor ?? 'Email no disponible' }}</span>
                                        </p>
                                    @else
                                        <p class="text-warning font-weight-bold h5">Pendiente de asignación</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Línea de Tiempo del Trayecto --}}
                    <div class="timeline bg-light p-4 rounded-lg border">
                        <div class="row text-center align-items-center">
                            
                            {{-- Servicio de ENTRADA (Tipo 1: Solo ida, Tipo 3: Ida y Vuelta) --}}
                            @if($reserva->id_tipo_reserva == 1 || $reserva->id_tipo_reserva == 3)
                            <div class="col">
                                <div class="mb-2 text-primary">
                                    <i class="fas fa-plane-arrival fa-lg"></i>
                                    <h6 class="text-muted small font-weight-bold text-uppercase mt-2 mb-1">Servicio de Entrada</h6>
                                </div>
                                <p class="h6 mb-1 font-weight-bold text-dark">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d M, Y') }}</p>
                                <p class="mb-1 text-primary font-weight-bold h5">{{ $reserva->hora_entrada ?? '--:--' }}</p>
                                <span class="badge bg-white border rounded-pill px-3 text-muted">
                                    <i class="fas fa-ticket-alt mr-1 small"></i> {{ $reserva->numero_vuelo_entrada ?? 'Privado' }}
                                </span>
                            </div>
                            @endif

                            {{-- Flecha central LIMPIA (Sin raya gris) --}}
                            @if($reserva->id_tipo_reserva == 3)
                            <div class="col-md-1 d-none d-md-block text-center">
                                <i class="fas fa-long-arrow-alt-right fa-2x text-muted opacity-50"></i>
                            </div>
                            @endif

                            {{-- Servicio de REGRESO (Tipo 2: Solo vuelta, Tipo 3: Ida y Vuelta) --}}
                            @if($reserva->id_tipo_reserva == 2 || $reserva->id_tipo_reserva == 3)
                            <div class="col mt-3 mt-md-0">
                                <div class="mb-2 text-secondary">
                                    <i class="fas fa-plane-departure fa-lg"></i>
                                    <h6 class="text-muted small font-weight-bold text-uppercase mt-2 mb-1">Servicio de Regreso</h6>
                                </div>
                                
                                @if($reserva->fecha_vuelo_salida)
                                    <p class="h6 mb-1 font-weight-bold text-dark">
                                        {{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d M, Y') }}
                                    </p>
                                    
                                    {{-- Hora: Usando 'hora_partida' según tu base de datos --}}
                                    <p class="mb-1 text-secondary font-weight-bold h5">
                                        {{ $reserva->hora_partida ?? 'Pendiente' }}
                                    </p>

                                    {{-- Vuelo: Usando el nuevo campo 'numero_vuelo_salida' --}}
                                    <span class="badge bg-white border rounded-pill px-3 text-muted">
                                        <i class="fas fa-ticket-alt mr-1 small"></i> 
                                        {{ $reserva->numero_vuelo_salida ?? 'Sin Vuelo' }} 
                                    </span>
                                @else
                                    <div class="py-2">
                                        <p class="text-muted font-italic mb-0 small">Hora pendiente de confirmación</p>
                                    </div>
                                @endif
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-header bg-white border-0 py-3">
                    <h6 class="m-0 font-weight-bold text-dark"><i class="fas fa-user-circle text-primary mr-2"></i> Información del Cliente</h6>
                </div>
                <div class="card-body px-4 py-3">
                    <div class="d-flex align-items-center">
                        <div class="mr-3">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($reserva->nombre_cliente . ' ' . $reserva->apellido1_cliente) }}&background=4e73df&color=fff&size=128" class="rounded-circle shadow-sm" width="60" alt="Avatar">
                        </div>
                        <div class="border-left pl-3">
                            <h5 class="mb-1 font-weight-bold text-dark text-capitalize">{{ $reserva->nombre_cliente }} {{ $reserva->apellido1_cliente }} {{ $reserva->apellido2_cliente }}</h5>
                            <div class="d-flex flex-wrap">
                                <a href="mailto:{{ $reserva->email_cliente }}" class="text-muted text-decoration-none mr-3 small">
                                    <i class="fas fa-envelope text-primary mr-1"></i> {{ $reserva->email_cliente }}
                                </a>
                                <span class="text-muted small">
                                    <i class="fas fa-id-card text-primary mr-1"></i> Titular de la reserva
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card border-0 shadow-sm sticky-top" style="border-radius: 15px; top: 20px;">
                <div class="card-body p-4 text-center">
                    <h6 class="text-dark font-weight-bold mb-4 text-uppercase small tracking-wide">Gestión de Reserva</h6>
                    
                    <div class="d-grid gap-3">
                        @if (session('user_type') === 'admin')
                            <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn btn-primary btn-block rounded-pill py-2 shadow mb-3 font-weight-bold transition-all">
                                <i class="fas fa-edit mr-2"></i> Editar Datos
                            </a>
                            
                            <button class="btn btn-outline-dark btn-block rounded-pill py-2 mb-3 shadow-sm font-weight-bold" onclick="window.print()">
                                <i class="fas fa-print mr-2 text-primary"></i> Imprimir Voucher
                            </button>

                            <hr class="my-4">
                            
                            <form action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST" onsubmit="return confirm('¿Eliminar registro permanentemente?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-link text-danger btn-sm w-100 text-decoration-none">
                                    <i class="fas fa-trash-alt mr-1"></i> Borrar Histórico
                                </button>
                            </form>
                        @else
                            <button class="btn btn-dark btn-block rounded-pill py-3 shadow mb-3" onclick="window.print()">
                                <i class="fas fa-download mr-2 text-primary"></i> Guardar Reserva
                            </button>
                            <div class="p-3 bg-light rounded-lg border border-info" style="border-left: 4px solid #36b9cc !important;">
                                <p class="mb-0 small text-muted">
                                    <i class="fas fa-info-circle text-info mr-1"></i> Contacte con soporte para cualquier cambio en el trayecto.
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer bg-white border-top-0 text-center pb-4 pt-0">
                    <div class="text-muted small py-2 px-3 bg-light rounded-pill d-inline-block">
                        <i class="fas fa-calendar-check mr-1"></i> {{ \Carbon\Carbon::parse($reserva->fecha_reserva)->format('d/m/Y H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-all { transition: all 0.3s ease; }
    .transition-all:hover { transform: translateY(-2px); }
    .bg-primary-light { background-color: rgba(78, 115, 223, 0.12) !important; }
    .bg-secondary-light { background-color: rgba(133, 135, 150, 0.12) !important; }
    .bg-success-light { background-color: rgba(28, 200, 138, 0.15) !important; }
    .bg-warning-light { background-color: rgba(246, 194, 62, 0.15) !important; }
    .bg-danger-light { background-color: rgba(231, 74, 59, 0.15) !important; }
    .bg-info-light { background-color: rgba(54, 185, 204, 0.15) !important; }

    .icon-circle {
        height: 52px;
        width: 52px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
    }
    .tracking-wide { letter-spacing: 1px; }

    @media print {
        .btn, .breadcrumb, .col-lg-4, .sticky-top { display: none !important; }
        .col-lg-8 { width: 100% !important; max-width: 100% !important; flex: 0 0 100%; }
        .card { shadow: none !important; border: 1px solid #eee !important; }
        body { background-color: white !important; }
    }
</style>
@endsection