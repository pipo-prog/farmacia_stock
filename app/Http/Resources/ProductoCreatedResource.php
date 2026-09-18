<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoCreatedResource extends JsonResource
{
    /**
     * Desactivar el envoltorio 'data' por defecto para cumplir con la especificación del examen.
     */
    public static $wrap = null;

    /**
     * Transform the resource into an array matching the exact requirement of Caso_Sistema.pdf.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'codigo' => 201,
            'mensaje' => 'Producto registrado correctamente',
            'producto' => [
                'id_producto' => $this->id_producto,
                'codigo' => $this->codigo,
                'nombre' => $this->nombre,
            ],
        ];
    }
}
