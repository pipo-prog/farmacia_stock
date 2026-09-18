<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        $categorias = [
            [
                'id_categoria' => 1,
                'nombre' => 'Medicamentos',
                'descripcion' => 'Medicamentos y productos farmacéuticos',
            ],
            [
                'id_categoria' => 2,
                'nombre' => 'Artículos de Aseo',
                'descripcion' => 'Productos de higiene y cuidado personal',
            ],
            [
                'id_categoria' => 3,
                'nombre' => 'Bebé',
                'descripcion' => 'Artículos y alimentos destinados a bebés',
            ],
        ];

        foreach ($categorias as $categoria) {
            DB::table('categoria')->updateOrInsert(
                ['id_categoria' => $categoria['id_categoria']],
                $categoria
            );
        }
    }
}
