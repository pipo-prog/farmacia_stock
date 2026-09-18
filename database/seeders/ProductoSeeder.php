<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductoSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            [
                'id_producto' => 1,
                'codigo' => 'MED001',
                'nombre' => 'Paracetamol 500 mg',
                'descripcion' => 'Caja de 16 comprimidos',
                'precio' => 2490.00,
                'stock_actual' => 40,
                'stock_minimo' => 10,
                'fecha_vencimiento' => '2028-06-30',
                'id_categoria' => 1,
                'id_proveedor' => 1,
            ],
            [
                'id_producto' => 2,
                'codigo' => 'MED002',
                'nombre' => 'Ibuprofeno 400 mg',
                'descripcion' => 'Caja de 10 comprimidos',
                'precio' => 3190.00,
                'stock_actual' => 25,
                'stock_minimo' => 8,
                'fecha_vencimiento' => '2028-04-30',
                'id_categoria' => 1,
                'id_proveedor' => 1,
            ],
            [
                'id_producto' => 3,
                'codigo' => 'ASE001',
                'nombre' => 'Alcohol Gel 500 ml',
                'descripcion' => 'Alcohol gel para higiene de manos',
                'precio' => 3990.00,
                'stock_actual' => 30,
                'stock_minimo' => 5,
                'fecha_vencimiento' => null,
                'id_categoria' => 2,
                'id_proveedor' => 2,
            ],
            [
                'id_producto' => 4,
                'codigo' => 'ASE002',
                'nombre' => 'Jabón Líquido 750 ml',
                'descripcion' => 'Jabón líquido antibacterial',
                'precio' => 2890.00,
                'stock_actual' => 18,
                'stock_minimo' => 5,
                'fecha_vencimiento' => null,
                'id_categoria' => 2,
                'id_proveedor' => 2,
            ],
            [
                'id_producto' => 5,
                'codigo' => 'BEB001',
                'nombre' => 'Pañales Talla M',
                'descripcion' => 'Paquete de 30 pañales',
                'precio' => 10990.00,
                'stock_actual' => 20,
                'stock_minimo' => 5,
                'fecha_vencimiento' => null,
                'id_categoria' => 3,
                'id_proveedor' => 3,
            ],
            [
                'id_producto' => 6,
                'codigo' => 'BEB002',
                'nombre' => 'Fórmula Infantil Etapa 1',
                'descripcion' => 'Leche en polvo para lactantes',
                'precio' => 15490.00,
                'stock_actual' => 12,
                'stock_minimo' => 4,
                'fecha_vencimiento' => '2027-11-30',
                'id_categoria' => 3,
                'id_proveedor' => 3,
            ],
        ];

        foreach ($productos as $producto) {
            DB::table('producto')->updateOrInsert(
                ['codigo' => $producto['codigo']],
                $producto
            );
        }
    }
}
