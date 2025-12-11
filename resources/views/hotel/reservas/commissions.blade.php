@extends('layouts.app')

@section('title','Comisiones mensuales')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white"><h4 class="mb-0">Comisiones mensuales</h4></div>
                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr><th>Year-Month</th><th>Reservas</th><th>Total comisión (€)</th></tr>
                        </thead>
                        <tbody>
                            @foreach($months as $m)
                                <tr>
                                    <td>{{ $m['year'] }}-{{ str_pad($m['month'],2,'0',STR_PAD_LEFT) }}</td>
                                    <td>{{ $m['count'] }}</td>
                                    <td>{{ number_format($m['total_commission'],2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
