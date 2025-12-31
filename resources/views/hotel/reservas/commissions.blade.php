@extends('layouts.app')

@section('title', 'Comisiones Mensuales')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-blue: #0f172a; /* Tu azul principal */
        --brand-blue-soft: #f1f5f9;
        --brand-blue-muted: #64748b;
        --bg-main: #f8fafc;
    }

    body { background-color: var(--bg-main); font-family: 'Plus Jakarta Sans', sans-serif; }

    .dashboard-container { max-width: 1000px; margin: 2rem auto; padding: 0 1.5rem; }

    /* Botón Volver */
    .btn-dashboard-back {
        display: inline-flex;
        align-items: center;
        padding: 0.6rem 1.2rem;
        background: white;
        color: var(--brand-blue);
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        font-weight: 700;
        font-size: 0.75rem;
        text-decoration: none;
        transition: all 0.2s ease;
        margin-bottom: 1.5rem;
    }
    .btn-dashboard-back:hover { 
        background: var(--brand-blue); 
        color: white !important; 
        transform: translateX(-4px); 
    }

    /* Tarjeta Principal */
    .glass-card-dashboard {
        background: white;
        border-radius: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .brand-header-dashboard {
        background: var(--brand-blue);
        padding: 2.5rem;
        color: white;
        display: flex;
        align-items: center;
        gap: 1.5rem;
    }

    .brand-header-dashboard h3 { font-weight: 800; margin: 0; font-size: 1.6rem; letter-spacing: -0.5px; }

    /* Tabla */
    .table-container { padding: 1.5rem; }
    
    .custom-table { width: 100%; border-collapse: separate; border-spacing: 0; }
    
    .custom-table thead th {
        padding: 1.2rem 1rem;
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: var(--brand-blue-muted);
        border-bottom: 2px solid var(--brand-blue-soft);
        font-weight: 800;
    }

    .custom-table tbody tr { transition: all 0.2s; }
    .custom-table tbody tr:hover { background: var(--brand-blue-soft); }

    .custom-table tbody td {
        padding: 1.2rem 1rem;
        border-bottom: 1px solid var(--brand-blue-soft);
        vertical-align: middle;
        color: var(--brand-blue);
    }

    /* Estilo de los Meses y Números */
    .month-badge {
        background: var(--brand-blue-soft);
        color: var(--brand-blue);
        padding: 0.5rem 1rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.9rem;
        display: inline-block;
        border: 1px solid #e2e8f0;
    }

    .count-circle {
        width: 35px;
        height: 35px;
        background: white;
        color: var(--brand-blue);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-weight: 800;
        font-size: 0.85rem;
        border: 2px solid var(--brand-blue);
    }

    .commission-text {
        color: var(--brand-blue);
        font-weight: 800;
        font-size: 1.1rem;
    }

    .sweep-animate { animation: fadeIn 0.6s ease-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(15px); } to { opacity: 1; transform: translateY(0); } }
</style>

<div class="dashboard-container sweep-animate">
    <a href="{{ route('hotel.dashboard') }}" class="btn-dashboard-back">
        <i class="fas fa-arrow-left me-2"></i> VOLVER AL PANEL
    </a>

    <div class="glass-card-dashboard">
        <div class="brand-header-dashboard">
            <div class="header-icon bg-white bg-opacity-10 p-3 rounded-4">
                <i class="fas fa-file-invoice-dollar fa-2x"></i>
            </div>
            <div>
                <h3>Comisiones Mensuales</h3>
                <p class="mb-0 text-white-50 small">Histórico unificado de ingresos transfer</p>
            </div>
        </div>

        <div class="table-container">
            <div class="table-responsive">
                <table class="custom-table">
                    <thead>
                        <tr>
                            <th>Periodo (Año-Mes)</th>
                            <th class="text-center">Total Reservas</th>
                            <th class="text-end">Comisión Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($months as $m)
                        <tr>
                            <td>
                                <div class="month-badge">
                                    <i class="far fa-calendar-alt me-2"></i>
                                    {{ $m['year'] }} - {{ str_pad($m['month'], 2, '0', STR_PAD_LEFT) }}
                                </div>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <span class="count-circle">
                                        {{ $m['count'] }}
                                    </span>
                                </div>
                            </td>
                            <td class="text-end">
                                <span class="commission-text">
                                    {{ number_format($m['total_commission'], 2) }}€
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                No hay datos de comisiones disponibles.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection