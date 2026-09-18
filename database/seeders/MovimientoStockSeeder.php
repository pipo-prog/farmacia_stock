<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MovimientoStockSeeder extends Seeder
{
    public function run(): void
    {
        $movimientos = [
            [
                'id_producto' => 1,
                'tipo' => 'ENTRADA',
                'cantidad' => 40,
                'observacion' => 'Ingreso inicial de stock',
            ],
            [
                'id_producto' => 3,
                'tipo' => 'ENTRADA',
                'cantidad' => 30,
                'observacion' => 'Ingreso inicial de stock',
            ],
            [
                'id_producto' => 5,
                'tipo' => 'ENTRADA',
                'cantidad' => 20,
                'observacion' => 'Ingreso inicial de stock',
            ],
            [
                'id_producto' => 1,
                'tipo' => 'SALIDA',
                'cantidad' => 2,
                'observacion' => 'Venta de producto',
            ],
        ];

        // Insertar únicamente si la tabla está vacía para mantener determinismo
        if (DB::table('movimiento_stock')->count() === 0) {
            foreach ($movimientos as $movimiento) {
                DB::table('movimiento_stock')->insert(array_merge($movimiento, [
                    'fecha_movimiento' => now(),
                ]));
            }
        }
    }
}
