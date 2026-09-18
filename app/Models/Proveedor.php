<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Proveedor extends Model
{
    use HasFactory;

    protected $table = 'proveedor';
    protected $primaryKey = 'id_proveedor';
    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'telefono',
        'correo',
    ];

    /**
     * Relación: Un proveedor provee muchos productos.
     */
    public function productos(): HasMany
    {
        return $this->hasMany(Producto::class, 'id_proveedor', 'id_proveedor');
    }
}
