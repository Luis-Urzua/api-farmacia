<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json([
            'data' => Usuario::all()
        ]);
    }

    public function store(Request $request)
    {
        $usuario = Usuario::create($request->all());

        return response()->json([
            'message' => 'Usuario creado',
            'data' => $usuario
        ]);
    }

    public function show($id)
    {
        return response()->json([
            'data' => Usuario::findOrFail($id)
        ]);
    }

    public function update(Request $request, $id)
    {
        $usuario = Usuario::findOrFail($id);

        $usuario->update($request->all());

        return response()->json([
            'message' => 'Usuario actualizado',
            'data' => $usuario
        ]);
    }

    public function destroy($id)
    {
        Usuario::destroy($id);

        return response()->json([
            'message' => 'Usuario eliminado'
        ]);
    }
}
