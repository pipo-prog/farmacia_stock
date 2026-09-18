<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Resources\ProductoCreatedResource;
use App\Http\Resources\ProductoResource;
use App\Models\Producto;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Requerimiento Central (Caso_Sistema.pdf):
     * Registrar un nuevo producto en la base de datos vía POST JSON.
     * Retorna HTTP 201 Created con el formato exacto requerido por el enunciado.
     */
    public function store(StoreProductoRequest $request): JsonResponse
    {
        $producto = Producto::create($request->validated());

        return (new ProductoCreatedResource($producto))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Consultar inventario completo de productos con filtros de apoyo:
     * - ?reposicion=1 o ?necesita_reposicion=1: productos con stock_actual <= stock_minimo
     * - ?proximos_vencer=1 o ?por_vencer=1: productos con fecha de vencimiento ordenada
     * - ?id_categoria=1: productos por categoría
     * - ?buscar=texto: búsqueda por nombre o código
     */
    public function index(Request $request): JsonResponse
    {
        $query = Producto::with(['categoria', 'proveedor']);

        // Filtro: productos que requieren reposición de stock
        if ($request->boolean('reposicion') || $request->has('necesita_reposicion')) {
            $query->necesitaReposicion();
        }

        // Filtro: productos próximos a vencer
        if ($request->boolean('proximos_vencer') || $request->has('por_vencer')) {
            $query->proximosVencer();
        } else {
            $query->orderBy('nombre', 'asc');
        }

        // Filtro por categoría
        if ($request->filled('id_categoria')) {
            $query->where('id_categoria', $request->integer('id_categoria'));
        }

        // Filtro por proveedor
        if ($request->filled('id_proveedor')) {
            $query->where('id_proveedor', $request->integer('id_proveedor'));
        }

        // Filtro de búsqueda textual
        if ($request->filled('buscar')) {
            $buscar = $request->string('buscar');
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'like', "%{$buscar}%")
                  ->orWhere('codigo', 'like', "%{$buscar}%");
            });
        }

        $productos = $query->get();

        return response()->json([
            'codigo' => 200,
            'total' => $productos->count(),
            'datos' => ProductoResource::collection($productos),
        ], 200);
    }

    /**
     * Consultar detalle de un producto específico por su ID.
     */
    public function show(mixed $id): JsonResponse
    {
        $producto = Producto::with(['categoria', 'proveedor'])->find($id);

        if (! $producto) {
            return response()->json([
                'codigo' => 404,
                'mensaje' => "El producto con ID {$id} no existe en los registros de la farmacia.",
            ], 404);
        }

        return response()->json([
            'codigo' => 200,
            'producto' => new ProductoResource($producto),
        ], 200);
    }
}
