@extends('layouts.app')

@section('title', 'Reservas del Hotel - Admin')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0">Reservas de {{ $hotel->nombre_hotel }}</h4>
                </div>
                <div class="card-body">
                    <h5>Totales por mes</h5>
                    <table class="table mb-4">
                        <thead><tr><th>Mes</th><th>Total comisión (€)</th></tr></thead>
                        <tbody>
                            @foreach($monthly as $month => $total)
                                <tr>
                                    <td>{{ $month }}</td>
                                    <td>{{ number_format($total,2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <h5>Reservas (ordenadas por comisión)</h5>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Localizador</th>
                                <th>Cliente</th>
                                <th>Fecha</th>
                                <th>Tipo</th>
                                <th>Comisión (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $r)
                                <tr>
                                    <td>{{ $r->localizador }}</td>
                                    <td>{{ $r->nombre_cliente ?? $r->email_cliente }}</td>
                                    <td>
                                        @if ($r->fecha_reserva)
                                            {{ is_string($r->fecha_reserva) ? \Carbon\Carbon::parse($r->fecha_reserva)->format('Y-m-d H:i') : $r->fecha_reserva->format('Y-m-d H:i') }}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ $r->tipoReserva->nombre ?? '-' }}</td>
                                    <td>{{ number_format($r->commission(),2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
