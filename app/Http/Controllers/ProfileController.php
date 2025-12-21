<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage; 

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function show()
    {
        $userType = session('user_type');
        $userId = session('user_id');

        $profileData = match ($userType) {
            'viajero' => \App\Models\Viajero::find($userId),
            'hotel' => \App\Models\Hotel::find($userId),
            'admin' => \App\Models\Admin::find($userId),
            default => null,
        };

        // Definimos los campos para el cálculo de progreso
        // Incluimos 'foto' para que también sume puntos
        $campos = ['email', 'nombre', 'apellido1', 'ciudad', 'password', 'foto'];
        $llenos = 0;

        if ($profileData) {
            foreach ($campos as $campo) {
                if (!empty($profileData->$campo)) {
                    $llenos++;
                }
            }
            $porcentaje = ($llenos / count($campos)) * 100;
        } else {
            $porcentaje = 0;
        }

        return view('profile.show', [
            'user' => $profileData, 
            'userType' => $userType,
            'porcentaje' => $porcentaje
        ]);
    }

    /**
     * Actualizar el perfil del usuario
     */
    public function update(Request $request)
    {
        $userType = session('user_type');
        $userId = session('user_id');

        $user = match ($userType) {
            'viajero' => \App\Models\Viajero::find($userId),
            'hotel'   => \App\Models\Hotel::find($userId),
            'admin'   => \App\Models\Admin::find($userId),
            default   => null,
        };

        if (!$user) return back()->withErrors(['Usuario no encontrado']);

        // Validamos
        $request->validate([
            'foto' => 'nullable|image|max:2048',
            'email' => 'required|string', // Este campo viene del input del form
            'new_password' => 'nullable|min:8|confirmed',
        ]);

        // 1. Manejo de la FOTO
        if ($request->hasFile('foto')) {
            if ($user->foto) {
                \Storage::disk('public')->delete($user->foto);
            }
            $user->foto = $request->file('foto')->store('avatars', 'public');
        }

        // 2. Manejo de Datos según tipo (Mapeo de columnas real)
        if ($userType === 'hotel') {
            $user->usuario = $request->email; // En hoteles la columna se llama 'usuario'
            $user->nombre_hotel = $request->nombre_hotel;
        } else {
            $user->email = $request->email; // En admin/viajero se llama 'email'
            $user->nombre = $request->nombre;
            if ($userType === 'viajero') {
                $user->apellido1 = $request->apellido1;
                $user->ciudad = $request->ciudad;
            }
        }

        // 3. Contraseña
        if ($request->filled('new_password')) {
            $user->password = \Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('profile.show')->with('success', 'Perfil actualizado');
    }
}