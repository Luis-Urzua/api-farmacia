<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    public function index()
    {
        return response()->json(Categoria::all());
    }

    public function store(Request $request)
    {
        $categoria = Categoria::create($request->all());

        return response()->json([
            'message' => 'Categoría creada',
            'data' => $categoria
        ]);
    }

    public function show($id)
    {
        return response()->json(Categoria::find($id));
    }

    public function update(Request $request, $id)
    {
        $categoria = Categoria::find($id);
        $categoria->update($request->all());

        return response()->json([
            'message' => 'Categoría actualizada'
        ]);
    }

    public function destroy($id)
    {
        Categoria::destroy($id);

        return response()->json([
            'message' => 'Categoría eliminada'
        ]);
    }
}
