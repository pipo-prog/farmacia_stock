<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovimientoStock extends Model
{
    use HasFactory;

    protected $table = 'movimiento_stock';
    protected $primaryKey = 'id_movimiento';
    public $timestamps = false;

    protected $fillable = [
        'id_producto',
        'tipo',
        'cantidad',
        'fecha_movimiento',
        'observacion',
    ];

    protected $casts = [
        'id_producto' => 'integer',
        'cantidad' => 'integer',
        'fecha_movimiento' => 'datetime',
    ];

    /**
     * Relación: Cada movimiento corresponde a un producto.
     */
    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'id_producto', 'id_producto');
    }
}
