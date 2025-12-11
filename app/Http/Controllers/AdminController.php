<?php

namespace App\Http\Controllers;

use App\Models\TransferReserva;
use App\Models\Hotel;
use Illuminate\Http\Request;

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
}
