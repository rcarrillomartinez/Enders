@extends('layouts.app')

@section('title', 'Gestión de Trayectos')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

<div class="container-fluid py-4" style="font-family: 'Inter', sans-serif; background-color: #f1f5f9; min-height: 100vh;">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-dark mb-1">Calendario de Trayectos</h2>
            <p class="text-muted mb-0">Consulta y gestión de traslados programados</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reservas.index') }}" class="btn btn-light border shadow-sm px-3">
                <i class="fa fa-list me-2"></i>Ver Lista
            </a>
            <a href="{{ route('reservas.create') }}" class="btn btn-primary shadow-sm px-4 fw-bold">
                <i class="fa fa-plus me-2"></i>Nuevo Trayecto
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 16px;">
        <div class="card-body p-3">
            <div class="row align-items-center g-3">
                
                <div class="col-md-4">
                    <div class="btn-group p-1 bg-light" style="border-radius: 12px;">
                        @foreach(['day' => 'Día', 'week' => 'Semana', 'month' => 'Mes'] as $key => $label)
                            <a href="{{ route('reservas.calendar', ['view' => $key, 'date' => $currentDate->format('Y-m-d')]) }}" 
                               class="btn {{ $viewMode === $key ? 'btn-white shadow-sm active-view' : 'btn-light border-0' }} px-3 fw-bold"
                               style="border-radius: 10px; font-size: 0.9rem;">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-md-4 text-center">
                    <div class="d-flex align-items-center justify-content-center gap-3">
                        @php
                            \Carbon\Carbon::setLocale('es');
                            if($viewMode === 'day') {
                                $prev = $currentDate->copy()->subDay(); $next = $currentDate->copy()->addDay();
                                $label = ucfirst($currentDate->translatedFormat('l, d \d\e F Y'));
                            } elseif($viewMode === 'week') {
                                $prev = $currentDate->copy()->subWeek(); $next = $currentDate->copy()->addWeek();
                                $label = "Semana " . $currentDate->format('W') . " (" . $currentDate->startOfWeek()->translatedFormat('M') . ")";
                            } else {
                                $prev = $currentMonth->copy()->subMonth(); $next = $currentMonth->copy()->addMonth();
                                $label = ucfirst($currentMonth->translatedFormat('F Y'));
                            }
                        @endphp
                        
                        <a href="{{ route('reservas.calendar', ['view' => $viewMode, 'date' => $prev->format('Y-m-d')]) }}" class="nav-btn">
                            <span>&#10094;</span>
                        </a>
                        
                        <h5 class="mb-0 fw-bold text-dark px-3" style="min-width: 220px;">{{ $label }}</h5>

                        <a href="{{ route('reservas.calendar', ['view' => $viewMode, 'date' => $next->format('Y-m-d')]) }}" class="nav-btn">
                            <span>&#10095;</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 text-end">
                    <a href="{{ route('reservas.calendar', ['view' => $viewMode, 'date' => now()->format('Y-m-d')]) }}" 
                       class="btn btn-outline-primary btn-sm px-4 fw-bold" style="border-radius: 8px;">Hoy</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
        
        @if ($viewMode === 'month')
            <div class="month-grid">
                <div class="month-days-header">
                    @foreach(['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'] as $d)
                        <div class="header-cell">{{ $d }}</div>
                    @endforeach
                </div>
                <div class="month-body">
                    @php
                        $loopDate = $currentMonth->copy()->startOfMonth()->startOfWeek();
                        $endDate = $currentMonth->copy()->endOfMonth()->endOfWeek();
                    @endphp
                    @while ($loopDate <= $endDate)
                        @php
                            $dateStr = $loopDate->format('Y-m-d');
                            $isToday = $loopDate->isToday();
                            $isOtherMonth = $loopDate->month !== $currentMonth->month;
                            $dayReservas = collect($calendarReservas[$dateStr] ?? []);
                        @endphp
                        <div class="day-cell {{ $isOtherMonth ? 'inactive' : '' }} {{ $isToday ? 'today' : '' }}">
                            <div class="day-number">{{ $loopDate->day }}</div>
                            <div class="event-container">
                                @foreach ($dayReservas as $reserva)
                                    <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="event-pill" title="Ver detalles">
                                        <strong>{{ $reserva->nombre_cliente }}</strong>
                                        <span class="d-block small opacity-75 text-truncate">{{ $reserva->hotel->nombre_hotel ?? 'Sin Hotel' }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        @php $loopDate->addDay(); @endphp
                    @endwhile
                </div>
            </div>
        @endif

        @if ($viewMode === 'day')
            <div class="p-4 bg-white">
                <div class="list-group list-group-flush">
                    @php $dateStr = $currentDate->format('Y-m-d'); $any = false; @endphp
                    @foreach(collect($calendarReservas[$dateStr] ?? [])->sortBy('fecha_entrada') as $reserva)
                        @php $any = true; @endphp
                        <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="list-group-item list-group-item-action border-0 mb-3 p-3 shadow-sm trayecto-item">
                            <div class="d-flex align-items-center">
                                <div class="time-badge me-4">
                                    {{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('H:i') }}
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="fw-bold mb-1">{{ $reserva->nombre_cliente }}</h6>
                                    <span class="text-muted small"><i class="fa fa-map-marker-alt me-1 text-danger"></i> {{ $reserva->hotel->nombre_hotel ?? 'Ubicación no especificada' }}</span>
                                </div>
                                <div class="text-primary fw-bold">Ver detalles &#10095;</div>
                            </div>
                        </a>
                    @endforeach
                    @if(!$any)
                        <div class="text-center py-5 text-muted">
                            <i class="fa fa-calendar-times fa-3x mb-3 opacity-25"></i>
                            <p>No hay trayectos programados para hoy.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if ($viewMode === 'week')
            <div class="table-responsive bg-white">
                <table class="table table-bordered mb-0 week-table">
                    <thead>
                        <tr class="bg-dark text-white">
                            @php $weekStart = $currentDate->copy()->startOfWeek(); @endphp
                            @for ($i = 0; $i < 7; $i++)
                                @php $dayLoop = $weekStart->copy()->addDays($i); @endphp
                                <th class="text-center py-3 {{ $dayLoop->isToday() ? 'bg-primary' : '' }}">
                                    <div class="small opacity-75 text-uppercase">{{ $dayLoop->translatedFormat('D') }}</div>
                                    <div class="fs-5">{{ $dayLoop->day }}</div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @for ($i = 0; $i < 7; $i++)
                                @php
                                    $dayStr = $weekStart->copy()->addDays($i)->format('Y-m-d');
                                    $dayRes = collect($calendarReservas[$dayStr] ?? []);
                                @endphp
                                <td class="week-cell {{ $weekStart->copy()->addDays($i)->isToday() ? 'bg-light' : '' }}">
                                    @forelse ($dayRes as $reserva)
                                        <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="week-event">
                                            <div class="fw-bold">{{ $reserva->nombre_cliente }}</div>
                                            <div class="small opacity-75">{{ \Carbon\Carbon::parse($reserva->fecha_entrada)->format('H:i') }}</div>
                                        </a>
                                    @empty
                                        <div class="text-center text-muted small py-4 opacity-50">- Vacío -</div>
                                    @endforelse
                                </td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<style>
    /* Globales */
    .btn-white { background: white; color: #0d6efd; border: 1px solid #dee2e6; }
    .active-view { border-bottom: 2px solid #0d6efd !important; color: #0d6efd !important; }
    .nav-btn { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 50%; color: #64748b; text-decoration: none; transition: 0.2s; font-weight: bold; }
    .nav-btn:hover { background: #0d6efd; color: white; transform: scale(1.1); }

    /* Estilos Vista Mensual */
    .month-days-header { display: grid; grid-template-columns: repeat(7, 1fr); background: #1e293b; color: #fff; }
    .header-cell { padding: 12px; text-align: center; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; }
    .month-body { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #e2e8f0; }
    .day-cell { background: #fff; min-height: 140px; padding: 10px; transition: 0.2s; }
    .day-cell.inactive { background: #f8fafc; }
    .day-cell.today { background: #f0f9ff; }
    .day-number { font-weight: 800; color: #64748b; margin-bottom: 8px; }
    .today .day-number { color: #0d6efd; }
    .event-pill { display: block; background: #0d6efd; color: #fff; padding: 6px 10px; border-radius: 8px; font-size: 0.75rem; text-decoration: none; margin-bottom: 5px; border-left: 4px solid #084ab2; }
    .event-pill:hover { background: #0b5ed7; color: #fff; transform: translateY(-2px); }

    /* Estilos Vista Diaria */
    .trayecto-item { border-radius: 12px !important; transition: 0.2s; border-left: 5px solid #0d6efd !important; }
    .trayecto-item:hover { transform: translateX(10px); background: #f8fafc; }
    .time-badge { background: #e0f2fe; color: #0369a1; padding: 8px 15px; border-radius: 10px; font-weight: 800; }

    /* Estilos Vista Semanal */
    .week-table { table-layout: fixed; }
    .week-cell { vertical-align: top; height: 400px; padding: 10px !important; }
    .week-event { display: block; background: #f1f5f9; border-left: 4px solid #64748b; padding: 8px; border-radius: 6px; text-decoration: none; color: #1e293b; font-size: 0.8rem; margin-bottom: 8px; }
    .week-event:hover { background: #e2e8f0; border-left-color: #0d6efd; }
</style>
@endsection