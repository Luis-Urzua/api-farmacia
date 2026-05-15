<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Cliente;
use App\Models\Usuario;

class AuthClienteController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'correo' => 'required|email|unique:usuario,correo',
            'password' => 'required|min:6'
        ]);

        $usuario = Usuario::create([
            'nombre' => $request->nombre,
            'correo' => $request->correo,
            'contrasena' => Hash::make($request->password),
            'rol' => 'cliente'
        ]);

        $cliente = Cliente::create([
            'id_usuario' => $usuario->id_usuario
        ]);

        return response()->json([
            'message' => 'Cliente registrado',
            'data' => $cliente
        ]);
    }

    public function login(Request $request)
    {
        $usuario = Usuario::where('correo', $request->correo)->first();

        if (!$usuario || !Hash::check($request->password, $usuario->contrasena)) {
            return response()->json([
                'message' => 'Credenciales incorrectas'
            ], 401);
        }

        if ($usuario->rol !== 'cliente') {
            return response()->json([
                'message' => 'No autorizado'
            ], 403);
        }

        $cliente = Cliente::where('id_usuario', $usuario->id_usuario)->first();

        $token = $cliente->createToken('token_cliente')->plainTextToken;

        return response()->json([
            'token' => $token,
            'cliente' => $cliente
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json([
            'message' => 'Sesión cerrada'
        ]);
    }

    public function perfil(Request $request)
    {
        $cliente = $request->user();

        $usuario = Usuario::find($cliente->id_usuario);

        return response()->json([
            'data' => $usuario
        ]);
    }      

    public function updateProfile(Request $request)
    {
        $cliente = $request->user();

        $usuario = Usuario::find($cliente->id_usuario);

        if (!$usuario) {    
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $request->validate([
            'nombre' => 'required',
            'correo' => 'required|email'
        ]);

        $usuario->update([
            'nombre' => $request->nombre,
            'correo' => $request->correo
        ]);

        return response()->json([
            'message' => 'Perfil actualizado',
            'data' => $usuario
        ]);
    }

    public function cambiarPassword(Request $request)
    {
        $request->validate([
            'password_actual' => 'required',
            'password_nueva' => 'required|min:6'
        ]);

        $cliente = $request->user();

        $usuario = Usuario::find($cliente->id_usuario);

        if (!$usuario) {
            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if (!Hash::check($request->password_actual, $usuario->contrasena)) {

            return response()->json([
                'message' => 'La contraseña actual es incorrecta'
            ], 401);
        }

        $usuario->contrasena = Hash::make($request->password_nueva);

        $usuario->save();

        return response()->json([
            'message' => 'Contraseña actualizada correctamente'
        ]);
    }

    public function updateImage(Request $request)
    {
        $request->validate([
            'imagen' => 'required|image|mimes:jpg,jpeg,png|max:2048'
        ]);

        $cliente = $request->user();

        $usuario = Usuario::find($cliente->id_usuario);

        if (!$usuario) {

            return response()->json([
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $ruta = $request->file('imagen')
            ->store('perfiles', 'public');

        $usuario->imagen = asset('storage/' . $ruta);

        $usuario->save();

        return response()->json([
            'message' => 'Imagen actualizada',
            'imagen' => $usuario->imagen
        ]);
    }   
}

