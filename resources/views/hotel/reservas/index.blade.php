@extends('layouts.app')

@section('title', 'Reservas del Hotel')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Reservas</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Localizador</th>
                                <th>Cliente</th>
                                <th>Fecha Reserva</th>
                                <th>Tipo</th>
                                <th>Estado</th>
                                <th>Comisión (€)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservas as $r)
                                <tr>
                                    <td>{{ $r->localizador }}</td>
                                    <td>{{ $r->nombre_cliente ?? $r->email_cliente }}</td>
                                    <td>{{ optional($r->fecha_reserva)->format('Y-m-d H:i') }}</td>
                                    <td>{{ $r->tipoReserva->nombre ?? '-' }}</td>
                                    <td>{{ $r->estado }}</td>
                                    <td>{{ number_format($r->commission(), 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
