<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cliente;

class ClienteController extends Controller
{
    public function index()
    {
        return response()->json(\DB::table('cliente')->get());
    }

    public function store(Request $request)
    {
        $cliente = Cliente::create($request->all());

        return response()->json([
            'message' => 'Cliente creado',
            'data' => $cliente
        ]);
    }

    public function show($id)
    {
        return response()->json(Cliente::find($id));
    }

    public function update(Request $request, $id)
    {
        $cliente = Cliente::find($id);
        $cliente->update($request->all());

        return response()->json([
            'message' => 'Cliente actualizado'
        ]);
    }

    public function destroy($id)
    {
        Cliente::destroy($id);

        return response()->json([
            'message' => 'Cliente eliminado'
        ]);
    }
}
