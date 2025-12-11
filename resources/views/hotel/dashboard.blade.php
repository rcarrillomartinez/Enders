@extends('layouts.app')

@section('title', 'Panel Hotel')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Panel del Hotel</h4>
                </div>
                <div class="card-body">
                    <p>Bienvenido al panel de hotel. Opciones disponibles:</p>
                    <ul>
                        <li><a href="{{ route('hotel.reservas.index') }}">Ver reservas</a></li>
                        <li><a href="{{ route('hotel.reservas.create') }}">Crear reserva</a></li>
                        <li><a href="{{ route('hotel.commissions') }}">Ver comisiones mensuales</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
