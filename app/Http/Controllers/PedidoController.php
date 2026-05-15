<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pedido;
use App\Models\Detalle;
use App\Models\Medicamento;

class PedidoController extends Controller
{
    public function index()
    {
        $pedidos = Pedido::with('detalles.medicamento')->get();

        return response()->json([
            'message' => 'Lista de pedidos',
            'data' => $pedidos,
        ]);
    }

    public function show($id)
    {
        $pedido = Pedido::with('detalles.medicamento')->find($id);

        if (!$pedido) {
            return response()->json(['message' => 'Pedido no encontrado'], 404);
        }

        return response()->json([
            'data' => $pedido,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'productos' => 'required|array|min:1',
            'productos.*.id_medicamento' => 'required|integer|exists:medicamentos,id_medicamento',
            'productos.*.cantidad' => 'required|integer|min:1',
            'productos.*.precio_unitario' => 'required|numeric|min:0',
        ]);

        $total = 0;

        foreach ($request->productos as $producto) {
            $total += $producto['cantidad'] * $producto['precio_unitario'];
        }

        $pedido = DB::transaction(function () use ($request, $total) {
            $pedido = Pedido::create([
                'fecha' => now(),
                'total' => $total,
                'estado' => 'pendiente',
                'id_usuario' => auth()->user()->id_usuario,
            ]);

            foreach ($request->productos as $producto) {
                Detalle::create([
                    'id_pedido' => $pedido->id_pedido,
                    'id_medicamento' => $producto['id_medicamento'],
                    'cantidad' => $producto['cantidad'],
                    'precio_unitario' => $producto['precio_unitario'],
                ]);

                $medicamento = Medicamento::find($producto['id_medicamento']);
                $medicamento->existencia -= $producto['cantidad'];
                $medicamento->save();
            }

            return $pedido;
        });

        return response()->json([
            'message' => 'Pedido creado correctamente',
            'pedido' => $pedido,
        ], 201);
    }

    public function destroy($id)
    {
        $pedido = Pedido::with('detalles')->find($id);

        if (!$pedido) {
            return response()->json([
                'message' => 'Pedido no encontrado',
            ], 404);
        }

        if ($pedido->estado === 'cancelado') {
            return response()->json([
                'message' => 'Pedido ya cancelado',
            ], 409);
        }

        foreach ($pedido->detalles as $detalle) {
            $medicamento = Medicamento::find($detalle->id_medicamento);

            if ($medicamento) {
                $medicamento->existencia += $detalle->cantidad;
                $medicamento->save();
            }
        }

        $pedido->estado = 'cancelado';
        $pedido->save();

        return response()->json([
            'message' => 'Pedido cancelado correctamente',
        ]);
    }

    public function historialUsuario($id_usuario)
    {
        $pedidos = Pedido::with('detalles.medicamento')
            ->where('id_usuario', $id_usuario)
            ->get();

        return response()->json([
            'data' => $pedidos,
        ]);
    }

    public function detalleUsuario($id_usuario, $id_pedido)
    {
        $pedido = Pedido::with('detalles.medicamento')
            ->where('id_usuario', $id_usuario)
            ->where('id_pedido', $id_pedido)
            ->first();

        if (!$pedido) {
            return response()->json(['message' => 'No encontrado'], 404);
        }

        return response()->json([
            'data' => $pedido,
        ]);
    }

    public function pagar(Request $request, $id)
    {
        $pedido = Pedido::find($id);

        if (!$pedido) {

            return response()->json([
                'message' => 'Pedido no encontrado'
            ], 404);
        }

        $pedido->transaction_id = $request->transaction_id;

        $pedido->estado_pago = 'pagado';

        $pedido->fecha_pago = now();

        $pedido->save();

        return response()->json([
            'message' => 'Pago registrado',
            'data' => $pedido
        ]);
    }   
}
