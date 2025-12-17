@extends('layouts.app')

@section('title', 'Hoteles - Admin')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Gestión de Hoteles</h4>
                    <a href="{{ route('admin.hotels.create') }}" class="btn btn-sm btn-success">+ Crear Hotel</a>
                </div>
                <div class="card-body">
                    @if ($hotels->isEmpty())
                        <div class="alert alert-info">No hay hoteles registrados.</div>
                    @else
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre del Hotel</th>
                                    <th>Usuario</th>
                                    <th>Comisión (€)</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hotels as $hotel)
                                    <tr>
                                        <td>{{ $hotel->nombre_hotel }}</td>
                                        <td>{{ $hotel->usuario }}</td>
                                        <td>{{ $hotel->comision }}</td>
                                        <td>
                                            <a href="{{ route('admin.hotels.reservas', $hotel->id_hotel) }}" class="btn btn-sm btn-primary">
                                                Ver Reservas
                                            </a>
                                            <form action="{{ route('admin.hotels.destroy', $hotel->id_hotel) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Seguro que deseas eliminar este hotel y todos sus datos relacionados?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-sm btn-danger">Eliminar</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
