<?php

namespace App\Http\Controllers;

use App\Models\TransferReserva;
use App\Models\Vehiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class HotelPanelController extends Controller
{
    // Hotel dashboard
    public function index()
    {
        if (!Auth::guard('hotel')->check()) {
            abort(403);
        }

        return view('hotel.dashboard');
    }

    // List reservations for this hotel
    public function reservasIndex()
    {
        if (!Auth::guard('hotel')->check()) {
            abort(403);
        }

        $hotel = Auth::guard('hotel')->user();
        $reservas = TransferReserva::where('id_hotel', $hotel->id_hotel)->orderBy('fecha_reserva', 'desc')->get();

        return view('hotel.reservas.index', compact('reservas'));
    }

    // Show create reservation form
    public function reservasCreate()
    {
        if (!Auth::guard('hotel')->check()) {
            abort(403);
        }

        $vehiculos = Vehiculo::all();
        return view('hotel.reservas.create', compact('vehiculos'));
    }

    // Store reservation created by hotel
    public function reservasStore(Request $request)
    {
        if (!Auth::guard('hotel')->check()) {
            abort(403);
        }

        $hotel = Auth::guard('hotel')->user();

        $validated = $request->validate([
            'id_tipo_reserva' => 'nullable|integer',
            'email_cliente' => 'required|email',
            'fecha_entrada' => 'nullable|date',
            'hora_entrada' => 'nullable',
            'numero_vuelo_entrada' => 'nullable|string',
            'origen_vuelo_entrada' => 'nullable|string',
            'fecha_vuelo_salida' => 'nullable|date',
            'hora_partida' => 'nullable',
            'num_viajeros' => 'nullable|integer',
            'id_vehiculo' => 'nullable|integer',
            'nombre_cliente' => 'nullable|string',
            'apellido1_cliente' => 'nullable|string',
            'apellido2_cliente' => 'nullable|string',
        ]);

        $res = TransferReserva::create([
            'localizador' => Str::upper(Str::random(10)),
            'id_hotel' => $hotel->id_hotel,
            'id_tipo_reserva' => $validated['id_tipo_reserva'] ?? null,
            'email_cliente' => $validated['email_cliente'],
            'fecha_reserva' => now(),
            'fecha_entrada' => $validated['fecha_entrada'] ?? null,
            'hora_entrada' => $validated['hora_entrada'] ?? null,
            'numero_vuelo_entrada' => $validated['numero_vuelo_entrada'] ?? null,
            'origen_vuelo_entrada' => $validated['origen_vuelo_entrada'] ?? null,
            'fecha_vuelo_salida' => $validated['fecha_vuelo_salida'] ?? null,
            'hora_partida' => $validated['hora_partida'] ?? null,
            'num_viajeros' => $validated['num_viajeros'] ?? null,
            'id_vehiculo' => $validated['id_vehiculo'] ?? null,
            'estado' => 'pendiente',
            'nombre_cliente' => $validated['nombre_cliente'] ?? null,
            'apellido1_cliente' => $validated['apellido1_cliente'] ?? null,
            'apellido2_cliente' => $validated['apellido2_cliente'] ?? null,
        ]);

        return redirect()->route('hotel.reservas.index')->with('success', 'Reserva creada correctamente');
    }

    // Show commissions per month for this hotel
    public function commissionsMonthly()
    {
        if (!Auth::guard('hotel')->check()) {
            abort(403);
        }

        $hotel = Auth::guard('hotel')->user();

        $rows = TransferReserva::selectRaw("YEAR(fecha_reserva) as year, MONTH(fecha_reserva) as month, COUNT(*) as count")
            ->where('id_hotel', $hotel->id_hotel)
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get()
            ->map(function ($r) use ($hotel) {
                // compute total commission for month
                $reservas = TransferReserva::whereYear('fecha_reserva', $r->year)
                    ->whereMonth('fecha_reserva', $r->month)
                    ->where('id_hotel', $hotel->id_hotel)
                    ->get();
                $total = $reservas->sum(function ($res) { return $res->commission(); });
                return [
                    'year' => $r->year,
                    'month' => $r->month,
                    'count' => $r->count,
                    'total_commission' => $total,
                ];
            });

        return view('hotel.reservas.commissions', ['months' => $rows]);
    }
}
