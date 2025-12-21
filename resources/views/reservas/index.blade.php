@extends('layouts.app')

@section('title', 'Mis Reservas')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container py-5" style="font-family: 'Inter', sans-serif;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
        <div>
            <h1 class="font-weight-700 mb-1" style="color: #1a202c; letter-spacing: -0.5px;">Mis Reservas</h1>
            <p class="text-muted mb-0">Gestiona y consulta el estado de tus traslados en tiempo real.</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if (session('user_type') === 'admin')
                <a href="{{ route('admin.hotels.create') }}" class="btn btn-outline-primary px-4 py-2-5 shadow-sm transition-hover" style="border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-plus mr-2"></i>Crear Hotel
                </a>
                <a href="{{ route('admin.hotels.list') }}" class="btn btn-outline-secondary px-4 py-2-5 shadow-sm transition-hover" style="border-radius: 12px; font-weight: 600;">
                    <i class="fas fa-list mr-2"></i>Gestionar Hoteles
                </a>
            @endif
            @if (session('user_type') !== 'hotel')
                <a href="{{ route('reservas.create') }}" class="btn btn-primary px-4 py-2-5 shadow-sm transition-hover" 
                   style="border-radius: 12px; font-weight: 600; background: #4e73df; border: none;">
                    <i class="fas fa-plus-circle mr-2"></i>Nueva Reserva
                </a>
            @endif
        </div>
    </div>

    @if ($reservas->count() > 0)
        <div class="card border-0 shadow-sm" style="border-radius: 20px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover mb-0" style="vertical-align: middle;">
                    <thead style="background-color: #f8fafc;">
                        <tr>
                            <th class="border-0 px-4 py-3 text-xs font-weight-bold text-muted text-uppercase">Localizador</th>
                            <th class="border-0 px-4 py-3 text-xs font-weight-bold text-muted text-uppercase">Hotel</th>
                            <th class="border-0 px-4 py-3 text-xs font-weight-bold text-muted text-uppercase">Tipo</th>
                            <th class="border-0 px-4 py-3 text-xs font-weight-bold text-muted text-uppercase">Cliente</th>
                            <th class="border-0 px-4 py-3 text-xs font-weight-bold text-muted text-uppercase">Fecha Entrada</th>
                            <th class="border-0 px-4 py-3 text-xs font-weight-bold text-muted text-uppercase text-center">Estado</th>
                            <th class="border-0 px-4 py-3 text-xs font-weight-bold text-muted text-uppercase text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservas as $reserva)
                            <tr style="transition: all 0.2s;">
                                <td class="px-4 py-4">
                                    <span class="font-weight-700 text-dark" style="font-family: monospace; font-size: 1.05rem;">{{ $reserva->localizador }}</span>
                                </td>
                                <td class="px-4 py-4">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm mr-2 bg-blue-light text-primary rounded d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                                            <i class="fas fa-hotel fa-xs"></i>
                                        </div>
                                        <span class="font-weight-600 text-secondary small">{{ $reserva->hotel->nombre_hotel ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-muted small">
                                    {{ $reserva->tipoReserva->nombre ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-dark font-weight-600">
                                    {{ $reserva->nombre_cliente }}
                                </td>
                                <td class="px-4 py-4 text-muted small">
                                    <i class="far fa-calendar-alt mr-1"></i>
                                    {{ $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-4 py-4 text-center">
                                    @php
                                        $statusClass = match($reserva->estado) {
                                            'confirmada' => 'badge-soft-success',
                                            'cancelada' => 'badge-soft-danger',
                                            default => 'badge-soft-warning',
                                        };
                                    @endphp
                                    <span class="badge badge-pill py-2 px-3 {{ $statusClass }}">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-right">
                                    <div class="btn-group shadow-sm" style="border-radius: 8px; overflow: hidden;">
                                        <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="btn btn-white btn-sm px-3 border-right" title="Ver">
                                            <i class="fas fa-eye text-primary"></i>
                                        </a>
                                        @if (session('user_type') === 'admin')
                                            <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn btn-white btn-sm px-3 border-right" title="Editar">
                                                <i class="fas fa-edit text-warning"></i>
                                            </a>
                                            <form action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-white btn-sm px-3" onclick="return confirm('¿Está seguro?')" title="Eliminar">
                                                    <i class="fas fa-trash-alt text-danger"></i>
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
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $reservas->links() }}
        </div>

    @else
        <div class="text-center py-5 bg-white shadow-sm mt-4" style="border-radius: 20px;">
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076432.png" alt="No data" style="width: 80px; opacity: 0.5;">
            <h4 class="mt-4 font-weight-600 text-muted">No hay reservas disponibles</h4>
            <p class="text-muted small">Parece que aún no tienes traslados registrados en el sistema.</p>
            @if (session('user_type') !== 'hotel')
                <a href="{{ route('reservas.create') }}" class="btn btn-primary rounded-pill px-4 mt-2">Crear mi primera reserva</a>
            @endif
        </div>
    @endif
</div>

<style>
    body { background-color: #f4f7fe; }
    .font-weight-700 { font-weight: 700; }
    .font-weight-600 { font-weight: 600; }
    .text-xs { font-size: 0.7rem; letter-spacing: 0.05em; }
    
    /* Colores Suaves (Soft UI) */
    .bg-blue-light { background-color: rgba(78, 115, 223, 0.1); }
    
    .badge-soft-success { background-color: #e6fffa; color: #28a745; border: 1px solid #b2f5ea; }
    .badge-soft-warning { background-color: #fffaf0; color: #dd6b20; border: 1px solid #feebc8; }
    .badge-soft-danger { background-color: #fff5f5; color: #e53e3e; border: 1px solid #fed7d7; }

    .btn-white { background-color: #fff; border: none; }
    .btn-white:hover { background-color: #f8fafc; }

    /* Animaciones y Hover */
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { 
        transform: translateY(-2px); 
        box-shadow: 0 8px 15px rgba(0,0,0,0.1);
    }
    
    .table tbody tr:hover { background-color: #fcfcfd; }
    .py-2-5 { padding-top: 0.75rem; padding-bottom: 0.75rem; }
    .text-dark { color: #2d3748 !important; }

    /* Ajuste de Paginación */
    .pagination { gap: 5px; }
    .page-link { border-radius: 8px !important; border: none; color: #4e73df; font-weight: 600; }
    .page-item.active .page-link { background-color: #4e73df; }
</style>
@endsection