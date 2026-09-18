<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProveedorSeeder extends Seeder
{
    public function run(): void
    {
        $proveedores = [
            [
                'id_proveedor' => 1,
                'nombre' => 'Laboratorio Salud Chile',
                'telefono' => '+56223456789',
                'correo' => 'ventas@saludchile.cl',
            ],
            [
                'id_proveedor' => 2,
                'nombre' => 'Distribuidora Higiene Ltda.',
                'telefono' => '+56224567890',
                'correo' => 'contacto@higiene.cl',
            ],
            [
                'id_proveedor' => 3,
                'nombre' => 'Productos Bebé SpA',
                'telefono' => '+56225678901',
                'correo' => 'ventas@productosbebe.cl',
            ],
        ];

        foreach ($proveedores as $proveedor) {
            DB::table('proveedor')->updateOrInsert(
                ['id_proveedor' => $proveedor['id_proveedor']],
                $proveedor
            );
        }
    }
}
