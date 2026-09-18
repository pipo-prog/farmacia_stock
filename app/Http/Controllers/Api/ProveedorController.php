<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProveedorResource;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;

class ProveedorController extends Controller
{
    /**
     * Listar todos los proveedores registrados.
     */
    public function index(): JsonResponse
    {
        $proveedores = Proveedor::orderBy('nombre')->get();

        return response()->json([
            'codigo' => 200,
            'total' => $proveedores->count(),
            'datos' => ProveedorResource::collection($proveedores),
        ], 200);
    }
}
