<?php

namespace App\Http\Controllers;

use App\Models\TransferReserva;
use App\Models\Hotel;
use App\Models\Vehiculo;
use App\Models\TipoReserva;
use App\Models\Destino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransferReservaController extends Controller
{
    /**
     * Mostrar todas las reservas
     */
    public function index()
    {
        $userType = session('user_type');
        $userId = session('user_id');
        $userEmail = session('user_email');

        // Filtrar reservas según el tipo de usuario
        if ($userType === 'admin') {
            $reservas = TransferReserva::with('hotel', 'tipoReserva', 'vehiculo')
                ->orderBy('fecha_entrada', 'desc')
                ->paginate(15);
        } else {
            $reservas = TransferReserva::where('email_cliente', $userEmail)
                ->with('hotel', 'tipoReserva', 'vehiculo')
                ->orderBy('fecha_entrada', 'desc')
                ->paginate(15);
        }

        return view('reservas.index', ['reservas' => $reservas, 'userType' => $userType]);
    }

    /**
     * Mostrar el formulario para crear una nueva reserva
     */
    public function create()
    {
        $hotels = Hotel::all();
        $vehiculos = Vehiculo::all();
        $tiposReserva = TipoReserva::all();
        $destinos = Destino::all();

        return view('reservas.create', [
            'hotels' => $hotels,
            'vehiculos' => $vehiculos,
            'tiposReserva' => $tiposReserva,
            'destinos' => $destinos,
        ]);
    }

    /**
     * Almacenar una nueva reserva creada
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_hotel' => 'required|exists:tranfer_hotel,id_hotel',
            'id_tipo_reserva' => 'required|exists:tipo_reserva,id_tipo_reserva',
            'email_cliente' => 'required|email',
            'nombre_cliente' => 'required|string',
            'apellido1_cliente' => 'required|string',
            'apellido2_cliente' => 'nullable|string',
            'fecha_entrada' => 'nullable|date',
            'hora_entrada' => 'nullable|date_format:H:i',
            'numero_vuelo_entrada' => 'nullable|string',
            'origen_vuelo_entrada' => 'nullable|string',
            'fecha_vuelo_salida' => 'nullable|date',
            'hora_partida' => 'nullable|date_format:H:i',
            'num_viajeros' => 'nullable|integer',
            'id_vehiculo' => 'nullable|exists:transfer_vehiculo,id_vehiculo',
            'estado' => 'nullable|in:pendiente,confirmada,cancelada,completada',
        ]);

        $reserva = TransferReserva::create([
            'localizador' => TransferReserva::generateLocalizador(),
            'id_hotel' => $validated['id_hotel'],
            'id_tipo_reserva' => $validated['id_tipo_reserva'],
            'email_cliente' => $validated['email_cliente'],
            'nombre_cliente' => $validated['nombre_cliente'],
            'apellido1_cliente' => $validated['apellido1_cliente'],
            'apellido2_cliente' => $validated['apellido2_cliente'] ?? null,
            'fecha_entrada' => $validated['fecha_entrada'] ?? null,
            'hora_entrada' => $validated['hora_entrada'] ?? null,
            'numero_vuelo_entrada' => $validated['numero_vuelo_entrada'] ?? null,
            'origen_vuelo_entrada' => $validated['origen_vuelo_entrada'] ?? null,
            'fecha_vuelo_salida' => $validated['fecha_vuelo_salida'] ?? null,
            'hora_partida' => $validated['hora_partida'] ?? null,
            'num_viajeros' => $validated['num_viajeros'] ?? null,
            'id_vehiculo' => $validated['id_vehiculo'] ?? null,
            'estado' => $validated['estado'] ?? 'pendiente',
        ]);

        return redirect()->route('reservas.show', $reserva->id_reserva)
            ->with('success', '\u00a1Reserva creada correctamente!');
    }

    /**
     * Mostrar una reserva específica
     */
    public function show($id)
    {
        $reserva = TransferReserva::with('hotel', 'tipoReserva', 'vehiculo')->find($id);

        if (!$reserva) {
            return back()->withErrors(['Reservation not found']);
        }

        // Verificar permiso
        $userType = session('user_type');
        $userEmail = session('user_email');

        if ($userType !== 'admin' && $reserva->email_cliente !== $userEmail) {
            return back()->withErrors(['Reserva no encontrada']);
        }

        // Solo los administradores pueden editar
        if (session('user_type') !== 'admin') {
            return back()->withErrors(['Acceso no autorizado']);
        }

        $hotels = Hotel::all();
        $vehiculos = Vehiculo::all();
        $tiposReserva = TipoReserva::all();

        return view('reservas.edit', [
            'reserva' => $reserva,
            'hotels' => $hotels,
            'vehiculos' => $vehiculos,
            'tiposReserva' => $tiposReserva,
        ]);
    }

    /**
     * Actualizar una reserva
     */
    public function update(Request $request, $id)
    {
        if (session('user_type') !== 'admin') {
            return back()->withErrors(['Acceso no autorizado']);
        }

        $reserva = TransferReserva::find($id);

        if (!$reserva) {
            return back()->withErrors(['Reserva no encontrada']);
        }

        $validated = $request->validate([
            'id_hotel' => 'required|exists:tranfer_hotel,id_hotel',
            'id_tipo_reserva' => 'required|exists:tipo_reserva,id_tipo_reserva',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'num_viajeros' => 'nullable|integer',
            'id_vehiculo' => 'nullable|exists:transfer_vehiculo,id_vehiculo',
        ]);

        $reserva->update($validated);

        return redirect()->route('reservas.show', $id)
            ->with('success', '\u00a1Reserva actualizada correctamente!');
    }

    /**
     * Eliminar una reserva
     */
    public function destroy($id)
    {
        if (session('user_type') !== 'admin') {
            return back()->withErrors(['Acceso no autorizado']);
        }

        $reserva = TransferReserva::find($id);

        if (!$reserva) {
            return back()->withErrors(['Reserva no encontrada']);
        }

        $reserva->delete();

        return redirect()->route('reservas.index')
            ->with('success', '¡Reserva eliminada correctamente!');
    }

    /**
     * Mostrar reservas en vista de calendario
     */
    public function calendar(Request $request)
    {
        $userType = session('user_type');
        $userId = session('user_id');
        $userEmail = session('user_email');
        $viewMode = $request->query('view', 'month'); // 'día', 'semana' o 'mes'
        $dateParam = $request->query('date'); // fecha en formato Y-m-d
        $currentDate = $dateParam ? \Carbon\Carbon::parse($dateParam) : \Carbon\Carbon::now();

        // Obtener reservas para el calendario
        if ($userType === 'admin') {
            $reservas = TransferReserva::with('hotel', 'tipoReserva')
                ->get();
        } else {
            $reservas = TransferReserva::where('email_cliente', $userEmail)
                ->with('hotel', 'tipoReserva')
                ->get();
        }

        // Agrupar reservas por fecha para mostrar en el calendario
        $calendarReservas = [];
        foreach ($reservas as $reserva) {
            if ($reserva->fecha_entrada) {
                $date = \Carbon\Carbon::parse($reserva->fecha_entrada)->format('Y-m-d');
                if (!isset($calendarReservas[$date])) {
                    $calendarReservas[$date] = [];
                }
                $calendarReservas[$date][] = $reserva;
            }
        }

        return view('reservas.calendar', [
            'calendarReservas' => $calendarReservas,
            'userType' => $userType,
            'currentDate' => $currentDate,
            'currentMonth' => $currentDate->copy()->startOfMonth(),
            'viewMode' => $viewMode,
        ]);
    }
}

