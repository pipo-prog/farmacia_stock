<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductoController;
use App\Http\Controllers\Api\CategoriaController;
use App\Http\Controllers\Api\ProveedorController;
use App\Http\Controllers\Api\MovimientoStockController;

/*
|--------------------------------------------------------------------------
| API Routes - Sistema de Control de Stock para Farmacia
|--------------------------------------------------------------------------
|
| Endpoints RESTful para el examen según Caso_Sistema.pdf y AgentLaravel.md:
|
| 1. POST /api/productos      -> Registrar un nuevo producto (Requerimiento Pauta HTTP 201)
| 2. GET  /api/productos      -> Listar inventario (con filtros: reposicion, proximos_vencer, categoria)
| 3. GET  /api/productos/{id} -> Consultar detalle de un producto específico (HTTP 200 / 404)
| 4. GET  /api/categorias     -> Listar catálogo de categorías
| 5. GET  /api/proveedores    -> Listar catálogo de proveedores
| 6. GET  /api/movimientos    -> Listar histórico de movimientos de stock
| 7. POST /api/movimientos    -> Registrar entrada o salida con actualización atómica de stock
|
*/

// Grupo de rutas principales directas (/api/...)
Route::post('productos', [ProductoController::class, 'store'])->name('api.productos.store');
Route::get('productos', [ProductoController::class, 'index'])->name('api.productos.index');
Route::get('productos/{id}', [ProductoController::class, 'show'])->name('api.productos.show');

Route::get('categorias', [CategoriaController::class, 'index'])->name('api.categorias.index');
Route::get('proveedores', [ProveedorController::class, 'index'])->name('api.proveedores.index');

Route::get('movimientos', [MovimientoStockController::class, 'index'])->name('api.movimientos.index');
Route::post('movimientos', [MovimientoStockController::class, 'store'])->name('api.movimientos.store');

// Compatibilidad con versionado v1 (/api/v1/...)
Route::prefix('v1')->name('api.v1.')->group(function () {
    Route::post('productos', [ProductoController::class, 'store'])->name('productos.store');
    Route::get('productos', [ProductoController::class, 'index'])->name('productos.index');
    Route::get('productos/{id}', [ProductoController::class, 'show'])->name('productos.show');

    Route::get('categorias', [CategoriaController::class, 'index'])->name('categorias.index');
    Route::get('proveedores', [ProveedorController::class, 'index'])->name('proveedores.index');

    Route::get('movimientos', [MovimientoStockController::class, 'index'])->name('movimientos.index');
    Route::post('movimientos', [MovimientoStockController::class, 'store'])->name('movimientos.store');
});
