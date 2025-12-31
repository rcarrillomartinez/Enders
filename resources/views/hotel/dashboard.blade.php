@extends('layouts.app')

@section('title', 'Panel Hotel')

@section('content')
<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --text-main: #0f172a;
        --accent-blue: #3b82f6;
    }

    /* Centrado absoluto: usa flex y min-height para que flote en el centro de la pantalla */
    .dashboard-wrapper {
        min-height: 85vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 1rem;
    }

    .dashboard-container {
        width: 100%;
        max-width: 1000px;
    }

    .glass-card-dashboard {
        background: #ffffff;
        border-radius: 28px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .brand-header-dashboard {
        background: var(--brand-navy);
        padding: 3rem 2rem;
        text-align: center;
        color: white;
    }

    .brand-header-dashboard h3 {
        font-weight: 800;
        margin-bottom: 1rem;
        letter-spacing: -0.5px;
    }

    .status-badge {
        background: rgba(255, 255, 255, 0.1);
        padding: 0.5rem 1.2rem;
        border-radius: 50px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid rgba(255, 255, 255, 0.2);
        display: inline-flex;
        align-items: center;
    }

    /* Filas de tarjetas centradas */
    .cards-row-custom {
        display: flex;
        justify-content: center;
        align-items: stretch;
        gap: 1.5rem;
        flex-wrap: wrap;
        padding: 0;
    }

    .action-card {
        background: #ffffff;
        border: 1px solid #f1f5f9;
        border-radius: 24px;
        padding: 2.5rem 1.5rem;
        text-decoration: none !important;
        color: var(--text-main);
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        flex: 1 1 250px;
        max-width: 280px;
        transition: transform 0.4s cubic-bezier(0.2, 1, 0.3, 1), 
                    box-shadow 0.4s cubic-bezier(0.2, 1, 0.3, 1), 
                    border-color 0.3s ease;
        opacity: 0;
        transform: translateX(-30px);
    }

    .action-card:hover {
        transform: translateY(-12px) !important; 
        border-color: var(--brand-navy);
        box-shadow: 0 20px 30px -10px rgba(15, 23, 42, 0.15);
    }

    .icon-box {
        width: 75px;
        height: 75px;
        background: #f8fafc;
        color: var(--brand-navy);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
        font-size: 1.8rem;
        transition: all 0.3s ease;
    }

    .action-card:hover .icon-box {
        background: var(--brand-navy);
        color: white;
        transform: scale(1.1);
    }

    .action-card h5 {
        font-weight: 700;
        margin-bottom: 0.75rem;
        color: var(--brand-navy);
    }

    .action-card p {
        font-size: 0.9rem;
        color: #64748b;
        line-height: 1.5;
        margin-bottom: 0;
    }

    @keyframes sweepIn {
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .sweep-animate {
        animation: sweepIn 0.8s cubic-bezier(0.23, 1, 0.32, 1) forwards;
    }
</style>

<div class="dashboard-wrapper">
    <div class="dashboard-container">
        <div class="glass-card-dashboard">
            
            <div class="brand-header-dashboard">
                <div class="icon-circle bg-white bg-opacity-10 d-inline-flex p-3 rounded-circle mb-3">
                    <i class="fas fa-hotel fa-2x text-white"></i>
                </div>
                <h3>{{ Auth::guard('hotel')->user()->nombre_hotel ?? 'Panel Hotelero' }}</h3>
                
                <div class="d-flex justify-content-center">
                    <span class="status-badge">
                        <i class="fas fa-user-circle me-2 text-white-50"></i> {{ Auth::guard('hotel')->user()->usuario }}
                    </span>
                </div>
            </div>

            <div class="p-4 p-md-5">
                <div class="cards-row-custom">
                    <a href="{{ route('hotel.reservas.index') }}" class="action-card">
                        <div class="icon-box">
                            <i class="fas fa-clipboard-list"></i>
                        </div>
                        <h5>Ver Reservas</h5>
                        <p>Gestione el listado completo de entradas y salidas de pasajeros.</p>
                    </a>

                    <a href="{{ route('hotel.reservas.create') }}" class="action-card">
                        <div class="icon-box">
                            <i class="fas fa-calendar-plus"></i>
                        </div>
                        <h5>Nueva Reserva</h5>
                        <p>Registre un nuevo servicio de transfer de forma inmediata.</p>
                    </a>

                    <a href="{{ route('hotel.commissions') }}" class="action-card">
                        <div class="icon-box">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h5>Comisiones</h5>
                        <p>Consulte el balance mensual y el histórico de sus beneficios.</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const cards = document.querySelectorAll('.action-card');
        cards.forEach((card, index) => {
            setTimeout(() => {
                card.classList.add('sweep-animate');
            }, index * 150); 
        });
    });
</script>
@endsection