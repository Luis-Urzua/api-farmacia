<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MedicamentoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\AuthClienteController;
use App\Http\Controllers\UsuarioController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::apiResource('medicamento', MedicamentoController::class);
Route::apiResource('categoria', CategoriaController::class);
Route::apiResource('cliente', ClienteController::class);
Route::apiResource('usuario', UsuarioController::class);

//publicas
Route::post('registro', [AuthClienteController::class, 'register']);
Route::post('login', [AuthClienteController::class, 'login']);

//protegidas
Route::middleware('auth:sanctum')->group(function () {

    //autenticacion
    Route::post('logout', [AuthClienteController::class, 'logout']);
    Route::get('perfil', [AuthClienteController::class, 'perfil']);
    Route::put('perfil', [AuthClienteController::class, 'updateProfile']);

    //pedidos
    Route::get('pedido', [PedidoController::class, 'index']);
    Route::post('pedido', [PedidoController::class, 'store']);
    Route::get('pedido/{id}', [PedidoController::class, 'show']);
    Route::delete('pedido/{id}', [PedidoController::class, 'destroy']);

    //historial cliente
    Route::get('pedido/usuario/{id}', [PedidoController::class, 'historialUsuario']);
    Route::get('pedido/usuario/{id}/{id_pedido}', [PedidoController::class, 'detalleUsuario']);

    //cambiar contraseña
    Route::put('cambiar-password', [AuthClienteController::class, 'cambiarPassword']);

    Route::post('perfil/imagen', [AuthClienteController::class, 'updateImage']);

    Route::post('pedido/pagar/{id}', [PedidoController::class, 'pagar']);
});