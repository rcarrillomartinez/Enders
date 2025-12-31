@extends('layouts.app')

@section('title', 'Reservas del Hotel - Admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --bg-main: #f8fafc;
    }

    body { 
        background-color: var(--bg-main); 
        font-family: 'Plus Jakarta Sans', sans-serif; 
    }

    .reservas-card-main {
        border-radius: 28px;
        border: 1px solid rgba(226, 232, 240, 0.8);
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
        overflow: hidden;
        background: #ffffff;
        animation: fadeIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-navy {
        background: var(--brand-navy);
        padding: 2.5rem;
        color: white;
    }

    .section-title {
        font-size: 0.75rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        color: #64748b;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    /* Iconos de sección en el azul de la marca */
    .section-title i {
        color: var(--brand-navy) !important;
    }

    /* ESTILO DE TABLAS */
    .table thead th {
        background: #ffffff;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1px;
        color: #94a3b8;
        border-bottom: 2px solid #f1f5f9;
        padding: 1rem 1.25rem;
    }

    .table tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
        color: #334155;
        font-weight: 600;
    }

    .text-navy-custom {
        color: var(--brand-navy) !important;
    }

    .badge-commission {
        background: #f1f5f9;
        color: var(--brand-navy);
        font-size: 0.85rem;
        padding: 0.6rem 0.8rem;
        border-radius: 8px;
        font-weight: 700;
        display: inline-block;
    }

    .badge-type {
        background: #f1f5f9; /* Cambiado a gris suave para no distraer */
        color: var(--brand-navy); /* Texto en azul de la marca */
        border-radius: 6px;
        padding: 0.4rem 0.6rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .btn-outline-custom {
        border: 2px solid rgba(255,255,255,0.2);
        color: white !important;
        padding: 0.75rem 1.25rem;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-outline-custom:hover {
        background: white;
        color: var(--brand-navy) !important;
    }
</style>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="reservas-card-main">
                
                <div class="header-navy">
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="background: rgba(255,255,255,0.1); padding: 12px; border-radius: 14px;">
                                <i class="fas fa-file-invoice-dollar fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-uppercase mb-0" style="opacity: 0.7; font-size: 0.7rem; letter-spacing: 2px; font-weight: 700;">Administración de Cuentas</p>
                                <h3 class="mb-0 fw-bold">{{ $hotel->nombre_hotel }}</h3>
                            </div>
                        </div>
                        <a href="{{ route('admin.hotels.list') }}" class="btn-outline-custom">
                            <i class="fas fa-arrow-left me-2"></i> Volver al listado
                        </a>
                    </div>
                </div>

                <div class="p-4 p-md-5">
                    
                    <div class="mb-5">
                        <h5 class="section-title">
                            <i class="fas fa-calendar-check"></i> Acumulado por Periodo Mensual
                        </h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Mes</th>
                                        <th class="text-end">Total Comisión (€)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthly as $month => $total)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <i class="far fa-calendar-alt text-muted"></i>
                                                    <span class="text-dark">{{ $month }}</span>
                                                </div>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge-commission">{{ number_format($total,2) }} €</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <hr class="my-5 opacity-25">

                    <div>
                        <h5 class="section-title">
                            <i class="fas fa-receipt"></i> Desglose Detallado de Reservas
                        </h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead>
                                    <tr>
                                        <th>Localizador</th>
                                        <th>Cliente</th>
                                        <th>Fecha del Servicio</th>
                                        <th>Tipo</th>
                                        <th class="text-end">Comisión</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservas as $r)
                                        <tr>
                                            <td>
                                                <span class="fw-bold text-navy-custom">#{{ $r->localizador }}</span>
                                            </td>
                                            <td>
                                                <div style="line-height: 1.4;">
                                                    <div class="text-dark fw-bold">{{ $r->nombre_cliente ?? 'N/A' }}</div>
                                                    <div class="text-muted small fw-medium">{{ $r->email_cliente }}</div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-secondary small fw-bold">
                                                    <i class="far fa-clock me-1 text-muted"></i>
                                                    @if ($r->fecha_reserva)
                                                        {{ is_string($r->fecha_reserva) ? \Carbon\Carbon::parse($r->fecha_reserva)->format('d/m/Y H:i') : $r->fecha_reserva->format('d/m/Y H:i') }}
                                                    @else
                                                        -
                                                    @endif
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge-type">
                                                    {{ $r->tipoReserva->nombre ?? '-' }}
                                                </span>
                                            </td>
                                            <td class="text-end">
                                                <span class="fw-bold text-navy-custom">{{ number_format($r->commission(),2) }} €</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

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