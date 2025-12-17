<?php

namespace App\Http\Controllers;

use App\Models\Viajero;
use App\Models\Hotel;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Mostrar página de inicio de sesión
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Mostrar página de registro
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Manejar solicitud de inicio de sesión
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'user_type' => 'required|in:viajero,hotel,admin',
            'email' => 'required_if:user_type,viajero,admin|nullable|email',
            'usuario' => 'required_if:user_type,hotel|nullable|string',
            'password' => 'required',
        ]);

        $userType = $validated['user_type'];
        $password = $validated['password'];

        switch ($userType) {
            case 'viajero':
                return $this->loginViajero($request->email, $password);
            case 'hotel':
                return $this->loginHotel($request->usuario, $password);
            case 'admin':
                return $this->loginAdmin($request->email, $password);
            default:
                return back()->withErrors(['Tipo de usuario inválido']);
        }
    }

    /**
     * Inicio de sesión para viajeros
     */
    private function loginViajero($email, $password)
    {
        $viajero = Viajero::where('email', $email)->first();

        if (!$viajero || !Hash::check($password, $viajero->password)) {
            return back()->withErrors(['email' => 'Credenciales inválidas'])->withInput();
        }

        // Logout all other guards first
        Auth::guard('hotel')->logout();
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();

        Auth::guard('viajero')->login($viajero);
        session(['user_type' => 'viajero', 'user_id' => $viajero->id_viajero, 'user_email' => $viajero->email]);

        return redirect()->route(route: 'reservas.index');
    }

    /**
     * Inicio de sesión para hoteles
     */
    private function loginHotel($usuario, $password)
    {
        $hotel = Hotel::where('usuario', $usuario)->first();

        if (!$hotel || !Hash::check($password, $hotel->password)) {
            return back()->withErrors(['usuario' => 'Credenciales inválidas'])->withInput();
        }

        // Logout all other guards first
        Auth::guard('viajero')->logout();
        Auth::guard('admin')->logout();
        Auth::guard('web')->logout();

        Auth::guard('hotel')->login($hotel);
        session(['user_type' => 'hotel', 'user_id' => $hotel->id_hotel, 'user_name' => $hotel->usuario]);

        // Redirect hotels to their dedicated dashboard
        return redirect()->route('hotel.dashboard');
    }

    /**
     * Inicio de sesión para administradores
     */
    private function loginAdmin($email, $password)
    {
        $admin = Admin::where('email', $email)->first();

        if (!$admin || !Hash::check($password, $admin->password)) {
            return back()->withErrors(['email' => 'Credenciales inválidas'])->withInput();
        }

        // Logout all other guards first
        Auth::guard('viajero')->logout();
        Auth::guard('hotel')->logout();
        Auth::guard('web')->logout();

        Auth::guard('admin')->login($admin);
        session(['user_type' => 'admin', 'user_id' => $admin->id_admin, 'user_email' => $admin->email]);

        return redirect()->route('reservas.index');
    }

    /**
     * Manejar solicitud de registro
     */
    public function register(Request $request)
    {
        $userType = $request->user_type;

        switch ($userType) {
            case 'viajero':
                return $this->registerViajero($request);
            default:
                return back()->withErrors(['Tipo de usuario inválido']);
        }
    }

    /**
     * Mostrar formulario de creación de hotel (solo admin)
     */
    public function showHotelCreate()
    {
        if (!Auth::guard('admin')->check()) {
            abort(403);
        }

        return view('auth.hotel_register');
    }

    /**
     * Almacenar nuevo hotel creado por admin
     */
    public function storeHotel(Request $request)
    {
        if (!Auth::guard('admin')->check()) {
            abort(403);
        }

        $validated = $request->validate([
            'usuario' => 'required|string|unique:tranfer_hotel,usuario',
            'nombre_hotel' => 'required|string',
            'id_zona' => 'nullable|integer',
            'password' => 'required|min:6|confirmed',
        ]);

        $hotel = Hotel::create([
            'usuario' => $validated['usuario'],
            'nombre_hotel' => $validated['nombre_hotel'],
            'id_zona' => $validated['id_zona'] ?? null,
            'password' => Hash::make($validated['password']),
            'comision' => 10,
        ]);

        return redirect()->route('profile.show')->with('success', 'Hotel creado correctamente');
    }

    /**
     * Registrar un nuevo viajero
     */
    private function registerViajero(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|unique:transfer_viajeros',
            'nombre' => 'required|string',
            'apellido1' => 'required|string',
            'apellido2' => 'nullable|string',
            'direccion' => 'nullable|string',
            'codigoPostal' => 'nullable|string',
            'ciudad' => 'nullable|string',
            'pais' => 'nullable|string',
            'password' => 'required|min:6|confirmed',
        ]);

        $viajero = Viajero::create([
            'email' => $validated['email'],
            'nombre' => $validated['nombre'],
            'apellido1' => $validated['apellido1'],
            'apellido2' => $validated['apellido2'] ?? null,
            'direccion' => $validated['direccion'] ?? null,
            'codigoPostal' => $validated['codigoPostal'] ?? null,
            'ciudad' => $validated['ciudad'] ?? null,
            'pais' => $validated['pais'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::guard('viajero')->login($viajero);
        session(['user_type' => 'viajero', 'user_id' => $viajero->id_viajero, 'user_email' => $viajero->email]);

        return redirect()->route('reservas.index')->with('success', 'Registro exitoso!');
    }

    /**
     * Registrar un nuevo hotel
     */
    private function registerHotel(Request $request)
    {
        $validated = $request->validate([
            'usuario' => 'required|string|unique:tranfer_hotel',
            'nombre_hotel' => 'required|string',
            'id_zona' => 'nullable|integer',
            'password' => 'required|min:6|confirmed',
        ]);

        $hotel = Hotel::create([
            'usuario' => $validated['usuario'],
            'nombre_hotel' => $validated['nombre_hotel'],
            'id_zona' => $validated['id_zona'] ?? null,
            'password' => Hash::make($validated['password']),
            'comision' => 10,
        ]);

        Auth::guard('hotel')->login($hotel);
        session(['user_type' => 'hotel', 'user_id' => $hotel->id_hotel, 'user_name' => $hotel->usuario]);

        return redirect()->route('reservas.index')->with('success', 'Registro exitoso!');
    }

    /**
     * Cerrar sesión del usuario
     */
    public function logout(Request $request)
    {
        // Logout from all guards
        Auth::guard('viajero')->logout();
        Auth::guard('hotel')->logout();
        Auth::guard('admin')->logout();
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Sesion cerrada correctamente');
    }
}
