<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id_producto' => $this->id_producto,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'precio' => (float) $this->precio,
            'stock_actual' => (int) $this->stock_actual,
            'stock_minimo' => (int) $this->stock_minimo,
            'fecha_vencimiento' => $this->fecha_vencimiento ? $this->fecha_vencimiento->format('Y-m-d') : null,
            'necesita_reposicion' => (bool) ($this->stock_actual <= $this->stock_minimo),
            'categoria' => $this->relationLoaded('categoria') && $this->categoria ? [
                'id_categoria' => $this->categoria->id_categoria,
                'nombre' => $this->categoria->nombre,
                'descripcion' => $this->categoria->descripcion,
            ] : [
                'id_categoria' => $this->id_categoria,
            ],
            'proveedor' => $this->relationLoaded('proveedor') && $this->proveedor ? [
                'id_proveedor' => $this->proveedor->id_proveedor,
                'nombre' => $this->proveedor->nombre,
                'telefono' => $this->proveedor->telefono,
                'correo' => $this->proveedor->correo,
            ] : [
                'id_proveedor' => $this->id_proveedor,
            ],
        ];
    }
}
