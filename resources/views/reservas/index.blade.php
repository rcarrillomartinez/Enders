@extends('layouts.app')

@section('title', 'Mis Reservas')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid py-4 main-wrapper">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-slate-900 mb-1">Mis Reservas</h2>
            <p class="text-muted mb-0 small">Gestión y control de traslados registrados</p>
        </div>
        <div class="d-flex gap-2 flex-wrap">
            @if (session('user_type') === 'admin')
                <a href="{{ route('admin.hotels.create') }}" class="btn btn-white border shadow-sm px-3 text-secondary">
                    <i class="fas fa-plus me-2"></i>Hotel
                </a>
            @endif
            @if (session('user_type') !== 'hotel')
                <a href="{{ route('reservas.create') }}" class="btn btn-dark shadow-sm px-4 fw-bold" style="background-color: #1e293b; border: none;">
                    <i class="fas fa-plus-circle me-2"></i>Nueva Reserva
                </a>
            @endif
        </div>
    </div>

    @if ($reservas->count() > 0)
        <div class="card border-0 shadow-sm d-none d-md-block" style="border-radius: 16px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-slate-800 text-white">
                        <tr>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold">Localizador</th>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold">Hotel</th>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold">Cliente</th>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold">Fecha Entrada</th>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-center">Estado</th>
                            <th class="px-4 py-3 border-0 small text-uppercase fw-bold text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        @foreach ($reservas as $reserva)
                            <tr>
                                <td class="px-4 py-3 align-middle">
                                    <span class="fw-bold text-slate-900" style="font-family: monospace; font-size: 1rem;">{{ $reserva->localizador }}</span>
                                </td>
                                <td class="px-4 py-3 align-middle">
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-hotel me-2 text-slate-400"></i>
                                        <span class="small fw-medium">{{ $reserva->hotel->nombre_hotel ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-4 py-3 align-middle fw-semibold text-slate-700">
                                    {{ $reserva->nombre_cliente }}
                                </td>
                                <td class="px-4 py-3 align-middle small text-muted">
                                    <i class="far fa-calendar-alt me-1"></i>
                                    {{ $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') : 'N/A' }}
                                </td>
                                <td class="px-4 py-3 align-middle text-center">
                                    @php
                                        $statusClass = match($reserva->estado) {
                                            'confirmada' => 'status-success',
                                            'cancelada' => 'status-danger',
                                            default => 'status-warning',
                                        };
                                    @endphp
                                    <span class="badge-status {{ $statusClass }}">
                                        {{ ucfirst($reserva->estado) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 align-middle text-end">
                                    <div class="btn-group shadow-sm rounded-3 overflow-hidden">
                                        <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="btn btn-light btn-sm px-3" title="Ver">
                                            <i class="fas fa-eye text-slate-600"></i>
                                        </a>
                                        @if (session('user_type') === 'admin')
                                            <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn btn-light btn-sm px-3" title="Editar">
                                                <i class="fas fa-edit text-slate-600"></i>
                                            </a>
                                            <form action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST" class="d-inline">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-light btn-sm px-3" onclick="return confirm('¿Eliminar reserva?')" title="Eliminar">
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

        <div class="d-md-none">
            @foreach ($reservas as $reserva)
                <div class="card border-0 shadow-sm mb-3" style="border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="fw-bold text-slate-900" style="font-family: monospace;">#{{ $reserva->localizador }}</span>
                            @php
                                $statusClass = match($reserva->estado) {
                                    'confirmada' => 'status-success',
                                    'cancelada' => 'status-danger',
                                    default => 'status-warning',
                                };
                            @endphp
                            <span class="badge-status {{ $statusClass }} small">{{ ucfirst($reserva->estado) }}</span>
                        </div>
                        <h6 class="fw-bold text-slate-800 mb-1">{{ $reserva->nombre_cliente }}</h6>
                        <div class="small text-muted mb-3">
                            <div class="mb-1"><i class="fas fa-hotel me-2"></i>{{ $reserva->hotel->nombre_hotel ?? 'N/A' }}</div>
                            <div><i class="far fa-calendar-alt me-2"></i>{{ $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') : 'N/A' }}</div>
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="btn btn-light btn-sm flex-fill fw-bold py-2">Ver Detalle</a>
                            @if (session('user_type') === 'admin')
                                <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn btn-light btn-sm px-3 py-2"><i class="fas fa-edit"></i></a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $reservas->links() }}
        </div>

    @else
        <div class="text-center py-5 bg-white shadow-sm mt-4" style="border-radius: 20px;">
            <i class="fas fa-calendar-times fa-4x mb-3 text-slate-200"></i>
            <h4 class="fw-bold text-slate-700">No hay reservas</h4>
            <p class="text-muted small">No se han encontrado registros en el sistema.</p>
            @if (session('user_type') !== 'hotel')
                <a href="{{ route('reservas.create') }}" class="btn btn-dark px-4 mt-2" style="background-color: #1e293b; border-radius: 10px;">Crear reserva</a>
            @endif
        </div>
    @endif
</div>

<style>
    .main-wrapper { font-family: 'Inter', sans-serif; background-color: #f1f5f9; min-height: 100vh; }
    .bg-slate-800 { background-color: #1e293b !important; }
    .text-slate-900 { color: #0f172a !important; }
    .text-slate-800 { color: #1e293b !important; }
    .text-slate-700 { color: #334155 !important; }
    .text-slate-600 { color: #475569 !important; }
    .text-slate-400 { color: #94a3b8 !important; }
    
    .btn-white { background: white; color: #64748b; }
    .btn-light { background: #f8fafc; border: 1px solid #e2e8f0; }

    /* Badges de Estado */
    .badge-status {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 700;
        display: inline-block;
    }
    .status-success { background-color: #dcfce7; color: #166534; }
    .status-warning { background-color: #fef9c3; color: #854d0e; }
    .status-danger { background-color: #fee2e2; color: #991b1b; }

    /* Estilos de Tabla */
    .table thead th { font-size: 0.7rem; letter-spacing: 0.05em; border: none; }
    .table tbody tr { transition: background 0.2s; }
    .table tbody tr:hover { background-color: #f8fafc; }

    /* Personalización de Paginación */
    .pagination .page-item .page-link {
        border: none;
        color: #475569;
        margin: 0 2px;
        border-radius: 8px !important;
    }
    .pagination .page-item.active .page-link {
        background-color: #1e293b;
        color: white;
    }
</style>
@endsection