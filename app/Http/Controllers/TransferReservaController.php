<?php

namespace App\Http\Controllers;

use App\Models\TransferReserva;
use App\Models\Hotel;
use App\Models\Vehiculo;
use App\Models\TipoReserva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TransferReservaController extends Controller
{
    /**
     * Asegura que solo usuarios autenticados accedan.
     */
    public function __construct()
    {
        $this->middleware('CheckMultiGuardAuth');
    }

    public function index()
    {
        $user = Auth::user();
        // Fallback para determinar el tipo de usuario y el email
        $userType = session('user_type') ?? ($user->role ?? 'client');
        $userEmail = session('user_email') ?? $user->email;

        $query = TransferReserva::with(['hotel', 'tipoReserva', 'vehiculo']);

        if ($userType !== 'admin') {
            $query->where('email_cliente', $userEmail);
        }

        $reservas = $query->orderBy('fecha_entrada', 'desc')->paginate(15);

        return view('reservas.index', compact('reservas', 'userType'));
    }

    public function create()
    {
        $hotels = Hotel::all();
        $vehiculos = Vehiculo::all();
        $tiposReserva = TipoReserva::all();
        $authUser = Auth::user();

        $userData = [
            'email'    => old('email_cliente', session('user_email') ?? $authUser->email ?? ''),
            'nombre'   => old('nombre_cliente', session('user_nombre') ?? $authUser->name ?? ''),
            'apellido' => old('apellido1_cliente', session('user_apellido') ?? $authUser->apellido ?? ''),
        ];

        return view('reservas.create', compact('hotels', 'vehiculos', 'tiposReserva', 'userData'));
    }

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
            'hora_entrada' => 'nullable',
            'numero_vuelo_entrada' => 'nullable|string',
            'origen_vuelo_entrada' => 'nullable|string',
            'fecha_vuelo_salida' => 'nullable|date',
            'hora_partida' => 'nullable',
            'num_viajeros' => 'required|integer|min:1',
            'id_vehiculo' => 'required|exists:transfer_vehiculo,id_vehiculo',
            'estado' => 'nullable|in:pendiente,confirmada,cancelada,completada',
        ]);

        $reserva = new TransferReserva();
        $reserva->fill($validated);
        
        $reserva->localizador = TransferReserva::generateLocalizador();
        $reserva->fecha_reserva = now();
        $reserva->estado = $request->estado ?? 'pendiente';
        
        $reserva->save();

        return redirect()->route('reservas.show', ['reserva' => $reserva->id_reserva])
            ->with('success', '¡Reserva creada! Localizador: ' . $reserva->localizador);
    }

    public function show($id)
    {
        $reserva = TransferReserva::with(['hotel', 'tipoReserva', 'vehiculo'])->findOrFail($id);
        
        $user = Auth::user();
        $userType = session('user_type') ?? ($user->role ?? 'client');
        $userEmail = session('user_email') ?? $user->email;

        // Validación de propiedad
        if ($userType !== 'admin' && $reserva->email_cliente !== $userEmail) {
            return redirect()->route('reservas.index')->withErrors(['No tienes permiso para ver esta reserva.']);
        }

        return view('reservas.show', compact('reserva', 'userType'));
    }

    public function edit($id)
    {
        if ((session('user_type') ?? Auth::user()->role) !== 'admin') {
            return redirect()->route('reservas.index')->withErrors(['Acceso denegado.']);
        }

        $reserva = TransferReserva::findOrFail($id);
        $hotels = Hotel::all();
        $vehiculos = Vehiculo::all();
        $tiposReserva = TipoReserva::all();

        return view('reservas.edit', compact('reserva', 'hotels', 'vehiculos', 'tiposReserva'));
    }

    public function update(Request $request, $id)
    {
        if ((session('user_type') ?? Auth::user()->role) !== 'admin') {
            abort(403);
        }

        $reserva = TransferReserva::findOrFail($id);

        $validated = $request->validate([
            'id_hotel' => 'required|exists:tranfer_hotel,id_hotel',
            'id_tipo_reserva' => 'required|exists:tipo_reserva,id_tipo_reserva',
            'estado' => 'required|in:pendiente,confirmada,cancelada,completada',
            'num_viajeros' => 'required|integer|min:1',
            'id_vehiculo' => 'required|exists:transfer_vehiculo,id_vehiculo',
        ]);

        $reserva->update($validated);
        $reserva->fecha_modificacion = now();
        $reserva->save();

        return redirect()->route('reservas.show', ['reserva' => $id])
            ->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy($id)
    {
        if ((session('user_type') ?? Auth::user()->role) !== 'admin') {
            abort(403);
        }

        TransferReserva::findOrFail($id)->delete();
        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada.');
    }

    public function calendar(Request $request)
    {
        $user = Auth::user();
        $userType = session('user_type') ?? ($user->role ?? 'client');
        $userEmail = session('user_email') ?? $user->email;
        
        $currentDate = $request->query('date') ? Carbon::parse($request->query('date')) : Carbon::now();

        $query = TransferReserva::with(['hotel', 'tipoReserva']);
        if ($userType !== 'admin') {
            $query->where('email_cliente', $userEmail);
        }
        $reservas = $query->get();

        $calendarReservas = [];
        foreach ($reservas as $reserva) {
            if ($reserva->fecha_entrada) {
                $date = Carbon::parse($reserva->fecha_entrada)->format('Y-m-d');
                $calendarReservas[$date][] = $reserva;
            }
        }

        return view('reservas.calendar', [
            'calendarReservas' => $calendarReservas,
            'userType' => $userType,
            'currentDate' => $currentDate,
            'currentMonth' => $currentDate->copy()->startOfMonth(),
            'viewMode' => $request->query('view', 'month'),
        ]);
    }
}