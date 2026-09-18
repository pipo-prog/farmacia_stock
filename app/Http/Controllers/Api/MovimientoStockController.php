<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMovimientoStockRequest;
use App\Http\Resources\MovimientoStockResource;
use App\Models\MovimientoStock;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MovimientoStockController extends Controller
{
    /**
     * Listar movimientos de stock registrados.
     */
    public function index(Request $request): JsonResponse
    {
        $query = MovimientoStock::with('producto')->orderBy('id_movimiento', 'desc');

        if ($request->filled('id_producto')) {
            $query->where('id_producto', $request->integer('id_producto'));
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', strtoupper($request->string('tipo')));
        }

        $movimientos = $query->get();

        return response()->json([
            'codigo' => 200,
            'total' => $movimientos->count(),
            'datos' => MovimientoStockResource::collection($movimientos),
        ], 200);
    }

    /**
     * Registrar un nuevo movimiento de stock (ENTRADA o SALIDA)
     * Ejecuta una transacción atómica para garantizar la consistencia del inventario.
     */
    public function store(StoreMovimientoStockRequest $request): JsonResponse
    {
        return DB::transaction(function () use ($request) {
            $producto = Producto::where('id_producto', $request->integer('id_producto'))
                ->lockForUpdate()
                ->firstOrFail();

            $cantidad = $request->integer('cantidad');
            $tipo = strtoupper($request->string('tipo'));

            // Validar que exista suficiente stock en caso de salida
            if ($tipo === 'SALIDA' && $producto->stock_actual < $cantidad) {
                return response()->json([
                    'codigo' => 422,
                    'mensaje' => 'Stock insuficiente para realizar la salida.',
                    'errores' => [
                        'cantidad' => [
                            "El stock actual ({$producto->stock_actual}) no permite una salida de {$cantidad} unidades.",
                        ],
                    ],
                ], 422);
            }

            // Actualizar el stock del producto
            if ($tipo === 'ENTRADA') {
                $producto->stock_actual += $cantidad;
            } else {
                $producto->stock_actual -= $cantidad;
            }
            $producto->save();

            // Registrar el movimiento
            $movimiento = MovimientoStock::create([
                'id_producto' => $producto->id_producto,
                'tipo' => $tipo,
                'cantidad' => $cantidad,
                'fecha_movimiento' => now(),
                'observacion' => $request->input('observacion'),
            ]);

            $movimiento->load('producto');

            return response()->json([
                'codigo' => 201,
                'mensaje' => 'Movimiento de stock registrado con éxito',
                'movimiento' => new MovimientoStockResource($movimiento),
                'stock_actualizado' => [
                    'id_producto' => $producto->id_producto,
                    'codigo' => $producto->codigo,
                    'nuevo_stock_actual' => $producto->stock_actual,
                ],
            ], 201);
        });
    }
}
