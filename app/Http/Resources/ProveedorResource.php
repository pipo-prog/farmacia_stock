<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProveedorResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id_proveedor' => $this->id_proveedor,
            'nombre' => $this->nombre,
            'telefono' => $this->telefono,
            'correo' => $this->correo,
        ];
    }
}
