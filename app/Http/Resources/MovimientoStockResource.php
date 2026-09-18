<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MovimientoStockResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_movimiento' => $this->id_movimiento,
            'id_producto' => $this->id_producto,
            'producto' => $this->relationLoaded('producto') && $this->producto ? [
                'codigo' => $this->producto->codigo,
                'nombre' => $this->producto->nombre,
            ] : null,
            'tipo' => $this->tipo,
            'cantidad' => (int) $this->cantidad,
            'fecha_movimiento' => $this->fecha_movimiento ? $this->fecha_movimiento->format('Y-m-d H:i:s') : null,
            'observacion' => $this->observacion,
        ];
    }
}
