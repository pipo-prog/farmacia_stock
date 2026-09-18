<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'sistema' => 'Sistema de Control de Stock para Farmacia',
        'estado' => 'Activo',
        'documentacion' => [
            'registrar_producto' => 'POST /api/productos',
            'listar_productos' => 'GET /api/productos',
            'detalle_producto' => 'GET /api/productos/{id}',
            'filtro_reposicion' => 'GET /api/productos?reposicion=1',
            'filtro_vencimiento' => 'GET /api/productos?proximos_vencer=1',
            'listar_categorias' => 'GET /api/categorias',
            'listar_proveedores' => 'GET /api/proveedores',
            'movimientos_stock' => 'GET /api/movimientos | POST /api/movimientos',
        ],
    ]);
});
