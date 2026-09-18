<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriaResource;
use App\Models\Categoria;
use Illuminate\Http\JsonResponse;

class CategoriaController extends Controller
{
    /**
     * Listar todas las categorías disponibles.
     */
    public function index(): JsonResponse
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return response()->json([
            'codigo' => 200,
            'total' => $categorias->count(),
            'datos' => CategoriaResource::collection($categorias),
        ], 200);
    }
}
