<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Mostrar el perfil del usuario
     */
    public function show()
    {
        $user = Auth::user();
        $userType = session('user_type');
        $userId = session('user_id');

        // Obtener el modelo de usuario apropiado según el tipo
        $profileData = match ($userType) {
            'viajero' => \App\Models\Viajero::find($userId),
            'hotel' => \App\Models\Hotel::find($userId),
            'admin' => \App\Models\Admin::find($userId),
            default => null,
        };

        return view('profile.show', ['user' => $profileData, 'userType' => $userType]);
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
            'hotel' => \App\Models\Hotel::find($userId),
            'admin' => \App\Models\Admin::find($userId),
            default => null,
        };

        if (!$user) {
            return back()->withErrors(['Usuario no encontrado']);
        }

        $validated = $request->validate([
            'email' => 'nullable|email|unique:transfer_viajeros,email,' . $userId . ',id_viajero',
            'nombre' => 'nullable|string',
            'nombre_hotel' => 'nullable|string',
            'new_password' => 'nullable|min:6',
            'password_confirmation' => 'nullable|same:new_password',
        ]);

        $updateData = [];

        if ($request->filled('nombre')) {
            $updateData['nombre'] = $validated['nombre'];
        }

        if ($request->filled('nombre_hotel')) {
            $updateData['nombre_hotel'] = $validated['nombre_hotel'];
        }

        if ($request->filled('email')) {
            $updateData['email'] = $validated['email'];
            session(['user_email' => $validated['email']]);
        }

        if ($request->filled('new_password')) {
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        return redirect()->route('profile.show')->with('success', '\u00a1Perfil actualizado correctamente!');
    }
}
