<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    use HasFactory;

    protected $table = 'producto';
    protected $primaryKey = 'id_producto';
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'nombre',
        'descripcion',
        'precio',
        'stock_actual',
        'stock_minimo',
        'fecha_vencimiento',
        'id_categoria',
        'id_proveedor',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'stock_actual' => 'integer',
        'stock_minimo' => 'integer',
        'fecha_vencimiento' => 'date:Y-m-d',
        'id_categoria' => 'integer',
        'id_proveedor' => 'integer',
    ];

    /**
     * Relación: Un producto pertenece a una categoría.
     */
    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'id_categoria', 'id_categoria');
    }

    /**
     * Relación: Un producto pertenece a un proveedor.
     */
    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class, 'id_proveedor', 'id_proveedor');
    }

    /**
     * Relación: Un producto registra múltiples movimientos de stock.
     */
    public function movimientos(): HasMany
    {
        return $this->hasMany(MovimientoStock::class, 'id_producto', 'id_producto');
    }

    /**
     * Scope: Productos cuyo stock sea menor o igual al stock mínimo (necesitan reposición).
     */
    public function scopeNecesitaReposicion(Builder $query): Builder
    {
        return $query->whereColumn('stock_actual', '<=', 'stock_minimo');
    }

    /**
     * Scope: Productos con fecha de vencimiento registrada ordenados ascendentemente.
     */
    public function scopeProximosVencer(Builder $query): Builder
    {
        return $query->whereNotNull('fecha_vencimiento')
                     ->orderBy('fecha_vencimiento', 'asc');
    }
}
