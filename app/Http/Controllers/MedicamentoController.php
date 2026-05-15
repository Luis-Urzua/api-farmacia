<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Medicamento;

class MedicamentoController extends Controller
{
    public function index()
    {
        $medicamentos = Medicamento::all();

        return response()->json([
            'message' => 'Lista de medicamentos',
            'data' => $medicamentos
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required',
            'precio' => 'required|numeric',
            'id_categoria' => 'required'
        ]);

        $medicamento = Medicamento::create($request->all());

        return response()->json([
            'message' => 'Medicamento creado',
            'data' => $medicamento
        ]);
    }

    public function show($id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json([
                'message' => 'Medicamento no encontrado'
            ], 404);
        }

        return response()->json([
            'data' => $medicamento
        ]);
    }

    public function update(Request $request, $id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json([
                'message' => 'Medicamento no encontrado'
            ], 404);
        }

        $medicamento->update($request->all());

        return response()->json([
            'message' => 'Medicamento actualizado',
            'data' => $medicamento
        ]);
    }

    public function destroy($id)
    {
        $medicamento = Medicamento::find($id);

        if (!$medicamento) {
            return response()->json([
                'message' => 'Medicamento no encontrado'
            ], 404);
        }

        $medicamento->delete();

        return response()->json([
            'message' => 'Medicamento eliminado'
        ]);
    }
}
