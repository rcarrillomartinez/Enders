@extends('layouts.app')

@section('title', 'Mis Reservas')

@section('content')
    <div class="row mb-4">
        <div class="col">
            <h2>Mis Reservas</h2>
        </div>
        <div class="col-auto">
            @if (session('user_type') === 'admin')
                <a href="{{ route('admin.hotels.create') }}" class="btn btn-secondary">Crear hotel</a>
            @endif
            @if (session('user_type') !== 'hotel')
                <a href="{{ route('reservas.create') }}" class="btn btn-primary">Nueva Reserva</a>
            @endif
        </div>
    </div>

    @if ($reservas->count() > 0)
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Localizador</th>
                        <th>Hotel</th>
                        <th>Tipo</th>
                        <th>Cliente</th>
                        <th>Fecha Entrada</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($reservas as $reserva)
                        <tr>
                            <td><strong>{{ $reserva->localizador }}</strong></td>
                            <td>{{ $reserva->hotel->nombre_hotel ?? 'N/A' }}</td>
                            <td>{{ $reserva->tipoReserva->nombre ?? 'N/A' }}</td>
                            <td>{{ $reserva->nombre_cliente }}</td>
                            <td>{{ $reserva->fecha_entrada ? \Carbon\Carbon::parse($reserva->fecha_entrada)->format('d/m/Y') : 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $reserva->estado === 'confirmada' ? 'success' : ($reserva->estado === 'cancelada' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($reserva->estado) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('reservas.show', $reserva->id_reserva) }}" class="btn btn-sm btn-info">Ver</a>
                                @if (session('user_type') === 'admin')
                                    <a href="{{ route('reservas.edit', $reserva->id_reserva) }}" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="{{ route('reservas.destroy', $reserva->id_reserva) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')">Eliminar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <nav>
            {{ $reservas->links() }}
        </nav>
    @else
        <div class="alert alert-info" role="alert">
            No hay reservas disponibles. 
            @if (session('user_type') !== 'hotel')
                <a href="{{ route('reservas.create') }}">Crea una nueva reserva</a>
            @endif
        </div>
    @endif
@endsection
