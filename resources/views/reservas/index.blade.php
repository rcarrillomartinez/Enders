@extends('layouts.app')

@section('title', 'Mis Reservas')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --accent-blue: #3b82f6;
        --success: #10b981;
        --danger: #ef4444;
        --warning: #f59e0b;
        --bg-main: #f8fafc;
    }

    body { 
        background-color: var(--bg-main); 
        font-family: 'Plus Jakarta Sans', sans-serif; 
    }

    .animate-page {
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @keyframes slideInRight {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .reservas-card-main {
        border-radius: 28px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
        overflow: hidden;
        background: #ffffff;
    }

    .header-navy {
        background: var(--brand-navy);
        padding: 2.5rem;
        color: white;
        position: relative;
    }

    .header-navy h3 {
        font-weight: 800;
        letter-spacing: -0.5px;
    }

    .header-navy p {
        font-weight: 500;
        opacity: 0.8;
        font-size: 0.85rem;
        letter-spacing: 1px;
    }

    .btn-action-top {
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        color: white;
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 0.7rem 1.4rem;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.8rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        text-transform: uppercase;
    }

    .btn-action-top:hover {
        background: white;
        color: var(--brand-navy);
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }

    .info-bar-custom {
        background: linear-gradient(90deg, #f8fafc 0%, #ffffff 100%);
        border: 1px solid #e2e8f0;
        border-left: 5px solid var(--brand-navy);
        border-radius: 20px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        align-items: center;
        animation: slideInRight 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    .info-icon-circle {
        background: var(--brand-navy);
        color: white;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1.25rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(15, 23, 42, 0.15);
    }

    .info-bar-custom span {
        color: var(--brand-navy);
        font-weight: 500;
        font-size: 0.95rem;
    }

    .info-bar-custom strong {
        color: var(--brand-navy);
        font-weight: 800;
        background: rgba(15, 23, 42, 0.05);
        padding: 2px 8px;
        border-radius: 6px;
    }

    .table-responsive { border-radius: 16px; }
    
    .table thead th {
        background: #f1f5f9;
        color: #475569;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        letter-spacing: 1px;
        padding: 1.25rem;
        border: none;
    }

    .table tbody tr {
        transition: all 0.2s ease;
        border-bottom: 1px solid #f1f5f9;
    }

    .table tbody tr:hover {
        background-color: #f8fafc;
        transform: scale(1.002);
    }

    .table td {
        padding: 1.25rem;
        vertical-align: middle;
        color: #1e293b;
        font-weight: 500;
    }

    .localizador-badge {
        font-family: 'JetBrains Mono', monospace;
        background: #f1f5f9;
        color: var(--brand-navy);
        padding: 6px 12px;
        border-radius: 10px;
        font-weight: 800;
        font-size: 0.85rem;
        border: 1px solid #e2e8f0;
    }

    .status-pill {
        padding: 6px 14px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.7rem;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .status-confirmada { background: #dcfce7; color: #166534; }
    .status-cancelada { background: #fee2e2; color: #991b1b; }
    .status-pendiente { background: #fef3c7; color: #92400e; }

    .btn-circle {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background: white;
        color: #64748b;
        text-decoration: none;
    }

    .btn-view-only:hover {
        background: var(--brand-navy);
        color: white;
        border-color: var(--brand-navy);
        transform: none !important; 
    }

    .btn-circle:not(.btn-view-only):hover {
        background: var(--brand-navy);
        color: white;
        border-color: var(--brand-navy);
    }

    .btn-circle-danger:hover {
        background: var(--danger);
        color: white;
        border-color: var(--danger);
    }

    .mobile-reserva-card {
        border-radius: 20px;
        border: 1px solid #e2e8f0;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: white;
    }
    
    .text-hotel-custom {
        color: var(--brand-navy) !important;
    }
</style>

<div class="container py-5 animate-page">
    <div class="row justify-content-center">
        <div class="col-xl-11">
            <div class="reservas-card-main">
                
                <div class="header-navy d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
                    <div>
                        <p class="text-uppercase mb-2">Panel de Control</p>
                        <h3 class="mb-0"><i class="fas fa-route me-2"></i>Gestión de Transfers</h3>
                    </div>
                    <div class="d-flex gap-2">
                        @if (session('user_type') === 'admin')
                            <a href="{{ route('admin.hotels.create') }}" class="btn btn-action-top">
                                <i class="fas fa-plus me-2"></i>Hotel
                            </a>
                        @endif
                        @if (session('user_type') !== 'hotel')
                            <a href="{{ route('reservas.create') }}" class="btn btn-action-top">
                                <i class="fas fa-calendar-plus me-2"></i>Nueva Reserva
                            </a>
                        @endif
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    <div class="info-bar-custom">
                        <div class="info-icon-circle">
                            <i class="fas fa-info"></i>
                        </div>
                        <span>¡Hola! Actualmente tienes <strong>{{ $reservas->total() }}</strong> servicios registrados en el sistema.</span>
                    </div>

                    @if ($reservas->count() > 0)
                        <div class="table-responsive d-none d-md-block">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Localizador</th>
                                        <th>Información Hotel</th>
                                        <th>Titular</th>
                                        <th>Fecha Ida</th>
                                        <th>Fecha Vuelta</th>
                                        <th class="text-center">Estado</th>
                                        <th class="text-end">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reservas as $reserva)
                                        @php
                                            $statusClass = match($reserva->estado) {
                                                'confirmada' => 'status-confirmada',
                                                'cancelada' => 'status-cancelada',
                                                default => 'status-pendiente',
                                            };
                                        @endphp
                                        <tr>
                                            <td><span class="localizador-badge">{{ $reserva->localizador }}</span></td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <div class="bg-light rounded-3 p-2 me-3">
                                                        <i class="fas fa-hotel text-hotel-custom"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fw-bold">{{ $reserva->hotel->nombre_hotel ?? 'N/A' }}</div>
                                                        <div class="small text-muted">ID: #{{ $reserva->hotel->id ?? '0' }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="fw-bold text-dark">{{ $reserva->nombre_cliente }}</span>
                                            </td>
                                            {{-- IDA --}}
                                            <td>
                                                @if($reserva->fecha_entrada)
                                                    <div class="d-flex align-items-center text-nowrap">
                                                        <i class="far fa-calendar-alt me-2 text-primary"></i>
                                                        {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d M, Y') }}
                                                    </div>
                                                @else
                                                    <span class="text-muted opacity-50">—</span>
                                                @endif
                                            </td>
                                            {{-- VUELTA (Usando fecha_vuelo_salida) --}}
                                            <td>
                                                @if($reserva->fecha_vuelo_salida)
                                                    <div class="d-flex align-items-center text-nowrap">
                                                        <i class="fas fa-undo me-2 text-info"></i>
                                                        {{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d M, Y') }}
                                                    </div>
                                                @else
                                                    <span class="text-muted opacity-50">—</span>
                                                @endif
                                            </td>
                                            <td class="text-center">
                                                <span class="status-pill {{ $statusClass }}">
                                                    <i class="fas fa-circle" style="font-size: 6px;"></i>
                                                    {{ $reserva->estado }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex justify-content-end gap-2">
                                                    <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="btn-circle btn-view-only" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @if (session('user_type') === 'admin')
                                                        <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn-circle" title="Editar">
                                                            <i class="fas fa-pen"></i>
                                                        </a>
                                                        <form action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST" class="d-inline">
                                                            @csrf @method('DELETE')
                                                            <button type="submit" class="btn-circle btn-circle-danger" onclick="return confirm('¿Eliminar reserva?')" title="Borrar">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- VISTA MÓVIL --}}
                        <div class="d-md-none">
                            @foreach ($reservas as $reserva)
                                @php
                                    $statusClass = match($reserva->estado) {
                                        'confirmada' => 'status-confirmada',
                                        'cancelada' => 'status-cancelada',
                                        default => 'status-pendiente',
                                    };
                                @endphp
                                <div class="mobile-reserva-card shadow-sm">
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="localizador-badge">{{ $reserva->localizador }}</span>
                                        <span class="status-pill {{ $statusClass }}">{{ $reserva->estado }}</span>
                                    </div>
                                    <h5 class="fw-bold mb-3">{{ $reserva->nombre_cliente }}</h5>
                                    <div class="mb-4">
                                        <p class="mb-2 small"><i class="fas fa-hotel me-2 text-muted"></i>{{ $reserva->hotel->nombre_hotel ?? 'N/A' }}</p>
                                        
                                        @if($reserva->fecha_entrada)
                                            <p class="mb-1 small"><i class="far fa-calendar-alt me-2 text-primary"></i>Ida: {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') }}</p>
                                        @endif
                                        
                                        @if($reserva->fecha_vuelo_salida)
                                            <p class="mb-0 small"><i class="fas fa-undo me-2 text-info"></i>Vuelta: {{ \Carbon\Carbon::parse($reserva->fecha_vuelo_salida)->format('d/m/Y') }}</p>
                                        @endif
                                    </div>
                                    <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="btn w-100 fw-bold" style="background: var(--brand-navy); color: white; border-radius: 12px; padding: 0.8rem;">
                                        VER DETALLES
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-center mt-5">
                            {{ $reservas->links() }}
                        </div>

                    @else
                        <div class="text-center py-5">
                            <div class="icon-circle bg-light d-inline-flex align-items-center justify-content-center mb-4" style="width: 120px; height: 120px; border-radius: 50%;">
                                <i class="fas fa-clipboard-list fa-3x text-muted opacity-20"></i>
                            </div>
                            <h4 class="fw-bold">No hay reservas activas</h4>
                            <p class="text-muted mb-4">Parece que no hay registros que coincidan con tu búsqueda.</p>
                            @if (session('user_type') !== 'hotel')
                                <a href="{{ route('reservas.create') }}" class="btn btn-action-top bg-dark text-white">
                                    CREAR MI PRIMERA RESERVA
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
                
                <div class="bg-light py-4 text-center border-top">
                    <span class="text-uppercase text-muted fw-bold" style="font-size: 0.65rem; letter-spacing: 2px;">
                        Enders Transfer Solutions &copy; {{ date('Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection