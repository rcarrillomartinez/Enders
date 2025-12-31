@extends('layouts.app')

@section('title', 'Reservas del Hotel')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --text-main: #0f172a;
        --border-color: #e2e8f0;
    }

    body { 
        overflow-x: hidden; 
        background-color: #f8fafc; 
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .dashboard-wrapper {
        min-height: 90vh;
        padding: 2rem 1rem;
        display: flex;
        justify-content: center;
        align-items: flex-start;
    }

    .dashboard-container {
        width: 100%;
        max-width: 1250px; 
    }

    .btn-dashboard-back {
        display: inline-flex;
        align-items: center;
        padding: 0.6rem 1.2rem;
        background-color: white;
        color: var(--brand-navy);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.85rem;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
        margin-bottom: 1.5rem;
    }

    .btn-dashboard-back:hover {
        background-color: var(--brand-navy);
        color: white !important;
        transform: translateX(-5px);
    }

    .glass-card-dashboard {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid var(--border-color);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .brand-header-dashboard {
        background: var(--brand-navy);
        padding: 2.5rem;
        text-align: center;
        color: white;
    }

    .brand-header-dashboard h3 {
        font-weight: 800;
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }

    .table-container { padding: 1.5rem 2rem; }

    .custom-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }

    .custom-table thead th {
        background: transparent;
        border: none;
        color: #64748b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.7rem;
        padding: 0.75rem 1rem;
        letter-spacing: 0.5px;
    }

    .custom-table tbody tr { background: #ffffff; transition: transform 0.2s ease; }
    .custom-table tbody tr:hover { transform: scale(1.002); }

    .custom-table tbody td {
        padding: 1.2rem 1rem;
        border-top: 1px solid #f1f5f9;
        border-bottom: 1px solid #f1f5f9;
        color: var(--text-main);
        vertical-align: middle;
    }

    .custom-table tbody td:first-child {
        border-left: 1px solid #f1f5f9;
        border-top-left-radius: 12px;
        border-bottom-left-radius: 12px;
        font-weight: 800;
    }

    .custom-table tbody td:last-child {
        border-right: 1px solid #f1f5f9;
        border-top-right-radius: 12px;
        border-bottom-right-radius: 12px;
    }

    .status-pill {
        padding: 0.4rem 0.8rem;
        border-radius: 50px;
        font-size: 0.7rem;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        text-transform: uppercase;
    }
    .status-confirmada { background: #dcfce7; color: #166534; }
    .status-pendiente { background: #fef9c3; color: #854d0e; }
    .status-cancelada { background: #fee2e2; color: #991b1b; }

    .type-pill {
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        font-size: 0.65rem;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: 1px solid transparent;
    }
    .type-ida { background: #e0f2fe; color: #0369a1; border-color: #bae6fd; }
    .type-vuelta { background: #fef2f2; color: #b91c1c; border-color: #fecaca; }
    .type-idavuelta { background: #f0fdf4; color: #15803d; border-color: #bbf7d0; }

    .obs-trigger {
        cursor: help;
        color: #94a3b8;
        transition: color 0.2s;
    }
    .obs-trigger:hover { color: #3b82f6; }

    .sweep-animate {
        animation: sweepIn 0.8s cubic-bezier(0.2, 1, 0.3, 1) forwards;
    }
    @keyframes sweepIn { 
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); } 
    }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-container sweep-animate">
        
        <div class="d-flex justify-content-start">
            <a href="{{ route('hotel.dashboard') }}" class="btn-dashboard-back">
                <i class="fas fa-chevron-left me-2"></i> VOLVER AL PANEL
            </a>
        </div>

        <div class="glass-card-dashboard">
            <div class="brand-header-dashboard">
                  <i class="fas fa-calendar-check fa-2x mb-3 text-white-50"></i>
                <h3>Listado de Reservas</h3>
                <p class="mb-0 text-white-50 small">Visualización detallada de servicios de transfer</p>
            </div>

            <div class="table-container">
                <div class="table-responsive">
                    <table class="custom-table">
                        <thead>
                            <tr>
                                <th>Localizador</th>
                                <th>Tipo</th>
                                <th>Cliente</th>
                                <th>Vuelo y Horario</th>
                                <th>Vehículo</th>
                                <th>Estado</th>
                                <th>Notas</th> 
                                <th class="text-end">Comisión</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $r)
                                <tr>
                                    <td>#{{ $r->localizador }}</td>

                                    <td>
                                        @php
                                            $tipo = strtolower($r->tipoReserva->nombre ?? '');
                                        @endphp
                                        @if(str_contains($tipo, 'vuelta') && str_contains($tipo, 'ida'))
                                            <span class="type-pill type-idavuelta"><i class="fas fa-exchange-alt"></i> IDA Y VUELTA</span>
                                        @elseif(str_contains($tipo, 'vuelta'))
                                            <span class="type-pill type-vuelta"><i class="fas fa-arrow-left"></i> SOLO VUELTA</span>
                                        @else
                                            <span class="type-pill type-ida"><i class="fas fa-arrow-right"></i> SOLO IDA</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold">{{ $r->nombre_cliente }} {{ $r->apellido1_cliente }}</span>
                                            <small class="text-muted" style="font-size: 0.75rem;">{{ $r->email_cliente }}</small>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-bold text-nowrap">
                                                <i class="far fa-calendar-alt me-1 text-muted"></i> 
                                                {{ $r->fecha_entrada ? \Carbon\Carbon::parse($r->fecha_entrada)->format('d/m/Y') : '--/--/----' }}
                                            </span>
                                            <div class="d-flex align-items-center gap-2 mt-1">
                                                <small class="badge bg-light text-dark border">
                                                    <i class="far fa-clock me-1 text-muted"></i> 
                                                    {{ $r->hora_entrada ? \Carbon\Carbon::parse($r->hora_entrada)->format('H:i') : '--:--' }}h
                                                </small>
                                                @if($r->num_vuelo_entrada)
                                                    <small class="text-primary fw-bold" style="font-size: 0.7rem;">
                                                        <i class="fas fa-plane-arrival me-1"></i> {{ $r->num_vuelo_entrada }}
                                                    </small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    <td>
                                        <div class="d-flex flex-column">
                                            @if($r->vehiculo)
                                                <span class="fw-bold" style="font-size: 0.85rem;">
                                                    <i class="fas fa-car me-1 text-muted"></i> {{ $r->vehiculo->descripcion }}
                                                </span>
                                                <small class="text-muted" style="font-size: 0.7rem;">Capacidad: {{ $r->vehiculo->capacidad }} pax</small>
                                            @else
                                                <span class="text-muted italic small">No asignado</span>
                                            @endif
                                        </div>
                                    </td>

                                    <td>
                                        @php
                                            $estado = strtolower($r->estado);
                                            $statusClass = match($estado) {
                                                'pendiente' => 'status-pill status-pendiente',
                                                'cancelada' => 'status-pill status-cancelada',
                                                default => 'status-pill status-confirmada',
                                            };
                                        @endphp
                                        <span class="{{ $statusClass }}">
                                            <i class="fas fa-circle me-1" style="font-size: 0.4rem;"></i>
                                            {{ $r->estado }}
                                        </span>
                                    </td>

                                    <td class="text-center">
                                        @if($r->observaciones)
                                            <span class="obs-trigger" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $r->observaciones }}">
                                                <i class="fas fa-comment-dots fa-lg"></i>
                                            </span>
                                        @else
                                            <span class="text-light">-</span>
                                        @endif
                                    </td>

                                    <td class="text-end fw-bold" style="color: var(--brand-navy); font-size: 1rem;">
                                        {{ number_format($r->commission(), 2) }}€
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>

@endsection