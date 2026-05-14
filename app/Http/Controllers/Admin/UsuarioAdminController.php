<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioAdminController extends Controller
{
    public function index()
    {
        $usuarios = User::orderBy('name')->get();
        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'rol'      => 'required|in:admin,moderador,lector',
        ]);

        $usuario = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'activo'   => true,
        ]);

        $usuario->assignRole($request->rol);

        return back()->with('success', "Usuario \"{$request->name}\" creado.");
    }

    public function destroy(User $usuario)
    {
        // Solo admin puede desactivar admin
        if ($usuario->hasRole('admin') && !auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para desactivar administradores.');
        }

        $usuario->update(['activo' => false]);
        return back()->with('success', "Usuario \"{$usuario->name}\" desactivado.");
    }

    public function activar(User $usuario)
    {
        // Solo admin puede activar admin
        if ($usuario->hasRole('admin') && !auth()->user()->hasRole('admin')) {
            abort(403, 'No tienes permiso para activar administradores.');
        }

        $usuario->update(['activo' => true]);
        return back()->with('success', "Usuario \"{$usuario->name}\" reactivado.");
    }

    public function changePassword(Request $request, User $usuario)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $usuario->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', "Contraseña de \"{$usuario->name}\" cambiada exitosamente.");
    }
}
