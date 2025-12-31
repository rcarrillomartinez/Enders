@extends('layouts.app')

@section('title', 'Gestión de Hoteles - Admin')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    :root {
        --brand-navy: #0f172a;
        --brand-hover: #1e293b;
        --accent-blue: #3b82f6;
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

    .btn-primary-custom {
        background: #ffffff;
        color: var(--brand-navy);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.75rem;
        transition: all 0.3s ease;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-primary-custom:hover {
        background: #f1f5f9;
        transform: translateY(-2px);
        color: var(--brand-navy);
    }

    .table thead th {
        background: #ffffff;
        text-transform: uppercase;
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 1px;
        color: #94a3b8;
        border-bottom: 2px solid #f1f5f9;
        padding: 1.5rem 1.25rem;
    }

    .table tbody td {
        padding: 1.25rem;
        vertical-align: middle;
        border-bottom: 1px solid #f8fafc;
        color: #334155;
        font-weight: 600;
    }

    .icon-wrapper {
        background: #f1f5f9;
        color: var(--brand-navy);
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.85rem;
    }

    .action-btn {
        width: 40px;
        height: 40px;
        border-radius: 12px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
        border: 1px solid #e2e8f0;
        background: #ffffff;
        color: var(--brand-navy);
        text-decoration: none;
    }

    .action-btn:hover {
        background: var(--brand-navy);
        color: #ffffff;
        border-color: var(--brand-navy);
        transform: translateY(-2px);
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
                                <i class="fas fa-city fa-lg"></i>
                            </div>
                            <div>
                                <p class="text-uppercase mb-0" style="opacity: 0.7; font-size: 0.7rem; letter-spacing: 2px; font-weight: 700;">Administración</p>
                                <h3 class="mb-0 fw-bold">Gestión de Hoteles</h3>
                            </div>
                        </div>
                        <a href="{{ route('admin.hotels.create') }}" class="btn-primary-custom">
                            <i class="fas fa-plus me-2"></i> Nuevo Hotel
                        </a>
                    </div>
                </div>

                <div class="table-responsive">
                    @if ($hotels->isEmpty())
                        <div class="p-5 text-center">
                            <i class="fas fa-hotel fa-3x mb-3 text-light"></i>
                            <h5 class="text-muted fw-bold">No hay registros disponibles</h5>
                        </div>
                    @else
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Información del Hotel</th>
                                    <th>Usuario</th>
                                    <th>Comisión</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hotels as $hotel)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="icon-wrapper">
                                                    <i class="fas fa-h-square fa-lg"></i>
                                                </div>
                                                <span class="text-dark fw-bold">{{ $hotel->nombre_hotel }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-secondary" style="font-size: 0.9rem;">
                                                <i class="fas fa-user-circle me-1 opacity-50"></i> {{ $hotel->usuario }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge" style="background: #f1f5f9; color: var(--brand-navy); font-size: 0.85rem; padding: 0.6rem 0.8rem; border-radius: 8px; font-weight: 700;">
                                                {{ $hotel->comision }} €
                                            </span>
                                        </td>
                                        <td class="text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <a href="{{ route('admin.hotels.reservas', $hotel->id_hotel) }}" 
                                                   class="action-btn" 
                                                   title="Ver Reservas">
                                                    <i class="fas fa-calendar-check"></i>
                                                </a>
                                                
                                                <form action="{{ route('admin.hotels.destroy', $hotel->id_hotel) }}" 
                                                      method="POST" 
                                                      onsubmit="return confirm('¿Confirmas la eliminación definitiva?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn" title="Borrar">
                                                        <i class="fas fa-trash-can"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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