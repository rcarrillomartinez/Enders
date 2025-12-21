@extends('layouts.app')

@section('title', 'Calendario de Trayectos')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">

<div class="container-fluid py-4 main-calendar-wrapper">
    
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h2 class="fw-bold text-slate-900 mb-1">Calendario de Trayectos</h2>
            <p class="text-muted mb-0 small">Panel de control y logística de servicios</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('reservas.index') }}" class="btn btn-white border shadow-sm px-3 text-secondary flex-fill">
                <i class="fa fa-list me-2"></i><span>Lista</span>
            </a>
            <a href="{{ route('reservas.create') }}" class="btn btn-dark shadow-sm px-4 fw-bold flex-fill" style="background-color: #1e293b; border: none;">
                <i class="fa fa-plus me-2"></i><span>Nuevo</span>
            </a>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-2 p-md-3">
            <div class="row align-items-center g-3">
                <div class="col-12 col-md-4 order-2 order-md-1">
                    <div class="btn-group w-100 p-1 bg-light" style="border-radius: 10px;">
                        @foreach(['day' => 'Día', 'week' => 'Sem', 'month' => 'Mes'] as $key => $label)
                            <a href="{{ route('reservas.calendar', ['view' => $key, 'date' => $currentDate->format('Y-m-d')]) }}" 
                               class="btn {{ $viewMode === $key ? 'btn-dark shadow-sm' : 'btn-light border-0 text-muted' }} px-2 px-md-3 fw-bold flex-fill"
                               style="border-radius: 8px; font-size: 0.85rem;">
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>
                </div>

                <div class="col-12 col-md-4 text-center order-1 order-md-2">
                    <div class="d-flex align-items-center justify-content-between justify-content-md-center gap-2">
                        @php
                            \Carbon\Carbon::setLocale('es');
                            $displayText = ($viewMode === 'day') ? ucfirst($currentDate->translatedFormat('d M Y')) : 
                                           (($viewMode === 'week') ? "Semana " . $currentDate->format('W') : ucfirst($currentMonth->translatedFormat('F Y')));
                            
                            $prev = ($viewMode === 'day') ? $currentDate->copy()->subDay() : (($viewMode === 'week') ? $currentDate->copy()->subWeek() : $currentMonth->copy()->subMonth());
                            $next = ($viewMode === 'day') ? $currentDate->copy()->addDay() : (($viewMode === 'week') ? $currentDate->copy()->addWeek() : $currentMonth->copy()->addMonth());
                        @endphp
                        
                        <a href="{{ route('reservas.calendar', ['view' => $viewMode, 'date' => $prev->format('Y-m-d')]) }}" class="nav-btn-grey">
                            <span>&#10094;</span>
                        </a>
                        
                        <div id="datepicker-trigger" class="px-3 py-2 rounded-3 border bg-white hover-zinc flex-grow-1 flex-md-grow-0" style="cursor: pointer; position: relative;">
                            <span class="mb-0 fw-bold text-dark me-2" style="font-size: 0.95rem;">{{ $displayText }}</span>
                            <i class="fa fa-calendar-alt text-secondary small"></i>
                            <input type="text" id="hidden-date-picker">
                        </div>

                        <a href="{{ route('reservas.calendar', ['view' => $viewMode, 'date' => $next->format('Y-m-d')]) }}" class="nav-btn-grey">
                            <span>&#10095;</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-4 text-end d-none d-md-block order-3">
                    <a href="{{ route('reservas.calendar', ['view' => $viewMode, 'date' => now()->format('Y-m-d')]) }}" 
                       class="btn btn-outline-secondary btn-sm px-4 fw-bold" style="border-radius: 8px;">Hoy</a>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-lg" style="border-radius: 16px; overflow: hidden; background: #fff;">
        
        @if ($viewMode === 'month')
            <div class="d-none d-md-block">
                <div class="month-grid">
                    <div class="month-days-header bg-slate-800">
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
                                $dayRes = collect($calendarReservas[$loopDate->format('Y-m-d')] ?? [])->sortBy('fecha_entrada');
                                $isToday = $loopDate->isToday();
                            @endphp
                            <div class="day-cell {{ $loopDate->month !== $currentMonth->month ? 'inactive' : '' }} {{ $isToday ? 'today-month' : '' }}">
                                <span class="day-number">{{ $loopDate->day }}</span>
                                <div class="event-container">
                                    @foreach ($dayRes->take(3) as $res)
                                        <a href="{{ route('reservas.show', $res->id_reserva) }}" class="event-pill-grey">
                                            <strong>{{ \Carbon\Carbon::parse($res->fecha_entrada)->format('H:i') }}</strong> {{ Str::limit($res->nombre_cliente, 10) }}
                                        </a>
                                    @endforeach
                                    @if($dayRes->count() > 3)
                                        <div class="text-muted extra-events">+{{ $dayRes->count() - 3 }} más</div>
                                    @endif
                                </div>
                            </div>
                            @php $loopDate->addDay(); @endphp
                        @endwhile
                    </div>
                </div>
            </div>

            <div class="d-md-none p-3">
                @php $foundMonth = false; @endphp
                @foreach($calendarReservas as $date => $reservas)
                    @if(count($reservas) > 0 && \Carbon\Carbon::parse($date)->format('m') == $currentMonth->format('m'))
                        @php $foundMonth = true; @endphp
                        <div class="mobile-day-group mb-4">
                            <div class="mobile-date-divider">{{ \Carbon\Carbon::parse($date)->translatedFormat('l, d \d\e F') }}</div>
                            @foreach(collect($reservas)->sortBy('fecha_entrada') as $res)
                                <a href="{{ route('reservas.show', $res->id_reserva) }}" class="mobile-card">
                                    <div class="m-time">{{ \Carbon\Carbon::parse($res->fecha_entrada)->format('H:i') }}</div>
                                    <div class="m-info">
                                        <div class="m-name">{{ $res->nombre_cliente }}</div>
                                        <div class="m-hotel">{{ $res->hotel->nombre_hotel ?? 'Sin Hotel' }}</div>
                                    </div>
                                    <i class="fa fa-chevron-right"></i>
                                </a>
                            @endforeach
                        </div>
                    @endif
                @endforeach
                @if(!$foundMonth)
                    <div class="text-center py-5 text-muted">No hay trayectos este mes.</div>
                @endif
            </div>
        @endif

        @if ($viewMode === 'day')
            <div class="p-3 p-md-4 bg-white">
                @php $dayRes = collect($calendarReservas[$currentDate->format('Y-m-d')] ?? [])->sortBy('fecha_entrada'); @endphp
                @forelse($dayRes as $res)
                    <a href="{{ route('reservas.show', $res->id_reserva) }}" class="trayecto-item-grey mb-3 p-3 d-block text-decoration-none">
                        <div class="d-flex align-items-center">
                            <div class="time-badge-grey me-3 me-md-4">
                                {{ \Carbon\Carbon::parse($res->fecha_entrada)->format('H:i') }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1 text-slate-800">{{ $res->nombre_cliente }}</h6>
                                <p class="mb-0 text-muted small"><i class="fa fa-hotel me-1"></i> {{ $res->hotel->nombre_hotel ?? 'N/A' }}</p>
                            </div>
                            <i class="fa fa-chevron-right text-light"></i>
                        </div>
                    </a>
                @empty
                    <div class="text-center py-5 text-muted">
                        <i class="fa fa-calendar-times fa-3x mb-3 opacity-10"></i>
                        <p class="mb-0 fw-bold">No hay trayectos para hoy.</p>
                    </div>
                @endforelse
            </div>
        @endif

        @if ($viewMode === 'week')
            <div class="table-responsive d-none d-md-block">
                <table class="table table-bordered mb-0">
                    <thead class="bg-slate-800 text-white">
                        <tr class="text-center">
                            @php $ws = $currentDate->copy()->startOfWeek(); @endphp
                            @for ($i = 0; $i < 7; $i++)
                                @php $dl = $ws->copy()->addDays($i); @endphp
                                <th class="py-3 border-0 {{ $dl->isToday() ? 'today-header-active' : '' }}">
                                    <div class="small {{ $dl->isToday() ? 'text-white' : 'opacity-50' }}">{{ $dl->translatedFormat('D') }}</div>
                                    <div class="fs-5 fw-bold {{ $dl->isToday() ? 'text-white' : '' }}">{{ $dl->day }}</div>
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @for ($i = 0; $i < 7; $i++)
                                @php 
                                    $ds = $ws->copy()->addDays($i)->format('Y-m-d');
                                    $dayRes = collect($calendarReservas[$ds] ?? [])->sortBy('fecha_entrada');
                                    $isToday = $ws->copy()->addDays($i)->isToday();
                                @endphp
                                <td class="week-cell p-2 {{ $isToday ? 'today-column-active' : '' }}">
                                    @forelse ($dayRes as $res)
                                        <a href="{{ route('reservas.show', $res->id_reserva) }}" class="week-event-grey">
                                            <div class="fw-bold small">{{ \Carbon\Carbon::parse($res->fecha_entrada)->format('H:i') }}</div>
                                            <div class="text-truncate small">{{ $res->nombre_cliente }}</div>
                                        </a>
                                    @empty
                                        <div class="text-center py-4 {{ $isToday ? 'text-white' : 'opacity-25' }}">
                                            <i class="fa fa-calendar-times d-block mb-1"></i>
                                            <span style="font-size: 0.65rem;">No hay trayectos para hoy.</span>
                                        </div>
                                    @endforelse
                                </td>
                            @endfor
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="d-md-none p-3">
                @for ($i = 0; $i < 7; $i++)
                    @php 
                        $ds = $ws->copy()->addDays($i);
                        $dayRes = collect($calendarReservas[$ds->format('Y-m-d')] ?? [])->sortBy('fecha_entrada');
                    @endphp
                    <div class="mobile-day-group mb-3">
                        <div class="mobile-date-divider {{ $ds->isToday() ? 'bg-dark text-white' : '' }}">
                            {{ $ds->translatedFormat('l, d M') }}
                            @if($ds->isToday()) <span class="badge bg-primary ms-2">HOY</span> @endif
                        </div>
                        @forelse($dayRes as $res)
                            <a href="{{ route('reservas.show', $res->id_reserva) }}" class="mobile-card">
                                <div class="m-time">{{ \Carbon\Carbon::parse($res->fecha_entrada)->format('H:i') }}</div>
                                <div class="m-info">
                                    <div class="m-name">{{ $res->nombre_cliente }}</div>
                                    <div class="m-hotel small text-muted">{{ $res->hotel->nombre_hotel ?? 'N/A' }}</div>
                                </div>
                                <i class="fa fa-chevron-right text-light"></i>
                            </a>
                        @empty
                            <div class="p-3 text-center border rounded-3 bg-light opacity-50 mb-2">
                                <small class="text-muted">No hay trayectos para hoy.</small>
                            </div>
                        @endforelse
                    </div>
                @endfor
            </div>
        @endif
    </div>
</div>

<div id="calendar-overlay" onclick="closeCalendar()"></div>

<style>
    /* VARIABLES Y BASE */
    .main-calendar-wrapper { font-family: 'Inter', sans-serif; background-color: #f1f5f9; min-height: 100vh; }
    .bg-slate-800 { background-color: #1e293b !important; }
    .btn-white { background: white; color: #475569; }
    
    /* ESTILO HOY SEMANAL (FONDO OSCURO Y LETRAS BLANCAS) */
    .today-header-active { background-color: #334155 !important; border-bottom: none !important; }
    .today-column-active { background-color: #334155 !important; border-top: none !important; }
    .today-column-active .week-event-grey { background: rgba(255,255,255,0.15); border-color: rgba(255,255,255,0.2); color: white !important; }
    .today-column-active .text-dark { color: white !important; }

    /* NAVEGACIÓN */
    .nav-btn-grey { width: 38px; height: 38px; display: flex; align-items: center; justify-content: center; background: #fff; border: 1px solid #e2e8f0; border-radius: 50%; color: #64748b; text-decoration: none; transition: 0.2s; }
    .nav-btn-grey:hover { background: #1e293b; color: white; }
    .hover-zinc:hover { background: #f8fafc !important; }
    #hidden-date-picker { visibility: hidden; width: 0; height: 0; position: absolute; }

    /* GRID MENSAL */
    .month-days-header { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #f8fafc; }
    .header-cell { padding: 15px; }
    .month-body { display: grid; grid-template-columns: repeat(7, 1fr); gap: 1px; background: #e2e8f0; border: 1px solid #e2e8f0; }
    .day-cell { background: white; min-height: 120px; padding: 10px; transition: 0.2s; position: relative; }
    .day-cell.inactive { background: #f8fafc; color: #cbd5e1; }
    .day-cell.today-month { background: #eff6ff; box-shadow: inset 0 0 0 2px #3b82f6; }
    .day-number { font-weight: 800; color: #94a3b8; font-size: 0.9rem; }
    .event-pill-grey { display: block; background: #f1f5f9; color: #334155; padding: 4px 8px; border-radius: 6px; font-size: 0.7rem; text-decoration: none; margin-top: 5px; border-left: 3px solid #64748b; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .extra-events { font-size: 0.65rem; font-weight: bold; margin-top: 4px; padding-left: 5px; }

    /* ESTILO DIARIO / SEMANAL */
    .trayecto-item-grey { border: 1px solid #e2e8f0; border-left: 4px solid #1e293b; border-radius: 12px; background: #fff; transition: 0.2s; }
    .time-badge-grey { background: #1e293b; color: white; padding: 8px 12px; border-radius: 8px; font-weight: 700; min-width: 65px; text-align: center; }
    .week-cell { vertical-align: top; min-height: 250px; background: white; width: 14.28%; }
    .week-event-grey { display: block; background: #f8fafc; border: 1px solid #e2e8f0; border-left: 3px solid #1e293b; padding: 8px; margin-bottom: 6px; border-radius: 6px; text-decoration: none; color: inherit; }

    /* ESTILOS MÓVIL */
    .mobile-date-divider { background: #f1f5f9; padding: 8px 15px; font-weight: 800; font-size: 0.8rem; color: #64748b; text-transform: uppercase; border-radius: 8px; margin-bottom: 10px; }
    .mobile-card { display: flex; align-items: center; background: white; border: 1px solid #e2e8f0; padding: 12px; border-radius: 12px; text-decoration: none; color: inherit; margin-bottom: 8px; }
    .m-time { font-weight: 800; color: #1e293b; font-size: 1rem; padding-right: 15px; border-right: 2px solid #f1f5f9; margin-right: 15px; }
    .m-info .m-name { font-weight: 700; color: #334155; font-size: 0.95rem; }
    .m-info .m-hotel { font-size: 0.8rem; color: #94a3b8; }

    /* FLATICKR OVERLAY */
    #calendar-overlay { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); z-index: 9998; backdrop-filter: blur(4px); }
    #calendar-overlay.active { display: block; }
    .centered-calendar { position: fixed !important; top: 50% !important; left: 50% !important; transform: translate(-50%, -50%) !important; z-index: 9999 !important; border: 0 !important; }

    @media (max-width: 768px) {
        .week-cell { min-height: 150px; }
    }
</style>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fp = flatpickr("#hidden-date-picker", {
            locale: "es",
            dateFormat: "Y-m-d",
            disableMobile: "true",
            onOpen: (dates, str, inst) => {
                document.getElementById('calendar-overlay').classList.add('active');
                inst.calendarContainer.classList.add('centered-calendar');
            },
            onClose: () => document.getElementById('calendar-overlay').classList.remove('active'),
            onChange: (dates, str) => window.location.href = "{{ route('reservas.calendar') }}?view={{ $viewMode }}&date=" + str
        });
        document.getElementById('datepicker-trigger').addEventListener('click', () => fp.open());
    });
    function closeCalendar() {
        document.getElementById('calendar-overlay').classList.remove('active');
    }
</script>
@endsection