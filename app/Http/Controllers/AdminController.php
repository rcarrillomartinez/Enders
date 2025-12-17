<?php

namespace App\Http\Controllers;

use App\Models\TransferReserva;
use App\Models\Hotel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminController extends Controller
{
    // List all hotels for admin to choose from
    public function listHotels()
    {
        if (!auth('admin')->check()) {
            abort(403);
        }

        $hotels = Hotel::all();
        return view('admin.hotels_list', compact('hotels'));
    }

    // List reservations for a hotel, ordered by commission
    public function hotelReservations($hotelId)
    {
        $hotel = Hotel::findOrFail($hotelId);

        $reservas = TransferReserva::where('id_hotel', $hotelId)->get()->sortByDesc(function ($r) {
            return $r->commission();
        });

        // Calculate total commission per month for this hotel
        $monthly = TransferReserva::where('id_hotel', $hotelId)
            ->get()
            ->groupBy(function ($item) {
                if ($item->fecha_reserva) {
                    $date = is_string($item->fecha_reserva) ? \Carbon\Carbon::parse($item->fecha_reserva) : $item->fecha_reserva;
                    return $date->format('Y-m');
                }
                return 'Sin fecha';
            })
            ->map(function ($items) {
                return collect($items)->sum(function ($r) { return $r->commission(); });
            });

        return view('admin.hotel_reservas', compact('hotel', 'reservas', 'monthly'));
    }

    // Delete a hotel and its related data
    public function destroyHotel($hotelId)
    {
        if (!auth('admin')->check()) {
            abort(403);
        }

        $hotel = Hotel::findOrFail($hotelId);

        Log::info('Attempting to delete hotel', ['id' => $hotel->id_hotel, 'usuario' => $hotel->usuario]);

        try {
            DB::transaction(function () use ($hotel) {
                // Delete reservations
                TransferReserva::where('id_hotel', $hotel->id_hotel)->delete();

                // Delete precio records and collect vehicle ids to consider deleting
                $vehiculoIds = [];
                if (Schema::hasTable('transfer_precios')) {
                    $vehiculoIds = DB::table('transfer_precios')
                        ->where('id_hotel', $hotel->id_hotel)
                        ->pluck('id_vehiculo')
                        ->toArray();

                    DB::table('transfer_precios')->where('id_hotel', $hotel->id_hotel)->delete();
                }

                // Delete vehicles that were associated only via precios (if any)
                if (!empty($vehiculoIds)) {
                    \App\Models\Vehiculo::whereIn('id_vehiculo', $vehiculoIds)->delete();
                }

                // Finally delete the hotel
                $hotel->delete();
            });

            Log::info('Hotel deleted', ['id' => $hotel->id_hotel]);

            return redirect()->route('admin.hotels.list')->with('success', 'Hotel eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error deleting hotel', ['id' => $hotel->id_hotel, 'error' => $e->getMessage()]);
            return redirect()->route('admin.hotels.list')->with('error', 'No se pudo eliminar el hotel: ' . $e->getMessage());
        }
    }

    /**
     * Devuelve un JSON con el número de traslados por zona y el % del total
     */
    public function reservasPorZona(Request $request)
    {
        $data = DB::table('transfer_zona as z')
            ->leftJoin('tranfer_hotel as h', 'h.id_zona', '=', 'z.id_zona')
            ->leftJoin('transfer_reservas as r', 'r.id_hotel', '=', 'h.id_hotel')
            ->select('z.id_zona', 'z.descripcion', DB::raw('COUNT(r.id_reserva) as traslados'))
            ->groupBy('z.id_zona', 'z.descripcion')
            ->get();

        $total = $data->sum('traslados');

        $result = $data->map(function ($row) use ($total) {
            $pct = $total ? round(((int)$row->traslados / $total) * 100, 2) : 0;
            return [
                'id_zona' => $row->id_zona,
                'descripcion' => $row->descripcion,
                'traslados' => (int)$row->traslados,
                'porcentaje' => $pct,
            ];
        });

        return response()->json([
            'total_reservas' => (int)$total,
            'zonas' => $result,
        ]);
    }
}
