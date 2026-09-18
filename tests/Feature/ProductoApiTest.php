<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Database\Seeders\DatabaseSeeder;
use App\Models\Producto;

class ProductoApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Poblar base de datos con los datos oficiales de farmacia_stock_mysql.sql
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * Requerimiento Central (Caso_Sistema.pdf - Página 2 y 3):
     * Registrar un nuevo producto vía POST en formato JSON.
     * Debe retornar HTTP 201 y el formato exacto especificado en el enunciado.
     */
    public function test_puede_registrar_un_nuevo_producto_con_exito_codigo_201(): void
    {
        $payload = [
            'codigo' => 'MED003',
            'nombre' => 'Vitamina C 500 mg',
            'descripcion' => 'Caja de 30 comprimidos',
            'precio' => 5990,
            'stock_actual' => 25,
            'stock_minimo' => 5,
            'fecha_vencimiento' => '2028-12-31',
            'id_categoria' => 1,
            'id_proveedor' => 1,
        ];

        $response = $this->postJson('/api/productos', $payload);

        // Validar código HTTP 201 Created
        $response->assertStatus(201);

        // Validar estructura exacta requerida en la pauta del examen
        $response->assertJson([
            'codigo' => 201,
            'mensaje' => 'Producto registrado correctamente',
            'producto' => [
                'codigo' => 'MED003',
                'nombre' => 'Vitamina C 500 mg',
            ],
        ]);

        $this->assertArrayHasKey('id_producto', $response->json('producto'));

        // Verificar persistencia en base de datos
        $this->assertDatabaseHas('producto', [
            'codigo' => 'MED003',
            'nombre' => 'Vitamina C 500 mg',
            'precio' => 5990,
            'stock_actual' => 25,
            'stock_minimo' => 5,
            'id_categoria' => 1,
            'id_proveedor' => 1,
        ]);
    }

    /**
     * Validar que también funciona en la ruta versionada /api/v1/productos.
     */
    public function test_puede_registrar_producto_en_ruta_versionada_v1(): void
    {
        $payload = [
            'codigo' => 'ASE003',
            'nombre' => 'Toallitas Desinfectantes',
            'descripcion' => 'Paquete de 50 unidades',
            'precio' => 1990,
            'stock_actual' => 15,
            'stock_minimo' => 5,
            'fecha_vencimiento' => null,
            'id_categoria' => 2,
            'id_proveedor' => 2,
        ];

        $response = $this->postJson('/api/v1/productos', $payload);

        $response->assertStatus(201)
            ->assertJson([
                'codigo' => 201,
                'mensaje' => 'Producto registrado correctamente',
                'producto' => [
                    'codigo' => 'ASE003',
                    'nombre' => 'Toallitas Desinfectantes',
                ],
            ]);
    }

    /**
     * Requerimiento de Validación: No permitir registrar dos productos con el mismo código.
     * Retorna HTTP 422 con mensaje descriptivo.
     */
    public function test_falla_al_registrar_producto_con_codigo_duplicado_codigo_422(): void
    {
        // MED001 ya existe en los seeders
        $payload = [
            'codigo' => 'MED001',
            'nombre' => 'Paracetamol Genérico Duplicado',
            'descripcion' => 'Intento de duplicación',
            'precio' => 2000,
            'stock_actual' => 10,
            'stock_minimo' => 5,
            'fecha_vencimiento' => '2028-06-30',
            'id_categoria' => 1,
            'id_proveedor' => 1,
        ];

        $response = $this->postJson('/api/productos', $payload);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'codigo',
                'mensaje',
                'errores' => ['codigo'],
            ])
            ->assertJson([
                'codigo' => 422,
            ]);
    }

    /**
     * Requerimiento de Validación: Campos obligatorios faltantes.
     */
    public function test_falla_cuando_faltan_campos_obligatorios_codigo_422(): void
    {
        $response = $this->postJson('/api/productos', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'codigo',
                'mensaje',
                'errores' => [
                    'codigo',
                    'nombre',
                    'precio',
                    'stock_actual',
                    'id_categoria',
                    'id_proveedor',
                ],
            ]);
    }

    /**
     * Requerimiento de Validación: Claves foráneas inexistentes (categoría o proveedor).
     */
    public function test_falla_cuando_categoria_o_proveedor_no_existen_codigo_422(): void
    {
        $payload = [
            'codigo' => 'TEST999',
            'nombre' => 'Producto Test Inexistente',
            'precio' => 1000,
            'stock_actual' => 10,
            'id_categoria' => 9999,
            'id_proveedor' => 8888,
        ];

        $response = $this->postJson('/api/productos', $payload);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errores' => ['id_categoria', 'id_proveedor'],
            ]);
    }

    /**
     * Requerimiento de Validación: Precios y stocks negativos.
     */
    public function test_falla_cuando_precio_o_stock_son_negativos_codigo_422(): void
    {
        $payload = [
            'codigo' => 'NEG001',
            'nombre' => 'Producto Negativo',
            'precio' => -500,
            'stock_actual' => -10,
            'stock_minimo' => -2,
            'id_categoria' => 1,
            'id_proveedor' => 1,
        ];

        $response = $this->postJson('/api/productos', $payload);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'errores' => ['precio', 'stock_actual', 'stock_minimo'],
            ]);
    }

    /**
     * Requerimiento de Consulta: Listar el inventario completo con relaciones.
     */
    public function test_puede_listar_todos_los_productos_codigo_200(): void
    {
        $response = $this->getJson('/api/productos');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'codigo',
                'total',
                'datos' => [
                    '*' => [
                        'id_producto',
                        'codigo',
                        'nombre',
                        'descripcion',
                        'precio',
                        'stock_actual',
                        'stock_minimo',
                        'fecha_vencimiento',
                        'necesita_reposicion',
                        'categoria',
                        'proveedor',
                    ],
                ],
            ]);

        $this->assertGreaterThanOrEqual(6, $response->json('total'));
    }

    /**
     * Requerimiento de Consulta: Identificar productos cuyo stock sea <= stock mínimo.
     */
    public function test_puede_filtrar_productos_que_requieren_reposicion(): void
    {
        // En los seeders:
        // BEB002 tiene stock_actual 12 y stock_minimo 4 (no requiere reposición)
        // Modificamos un producto para asegurar caso de reposición
        $prod = Producto::find(1);
        $prod->stock_actual = 5; // menor que stock_minimo 10
        $prod->save();

        $response = $this->getJson('/api/productos?reposicion=1');

        $response->assertStatus(200);

        $datos = $response->json('datos');
        $this->assertNotEmpty($datos);

        foreach ($datos as $item) {
            $this->assertLessThanOrEqual($item['stock_minimo'], $item['stock_actual']);
            $this->assertTrue($item['necesita_reposicion']);
        }
    }

    /**
     * Requerimiento de Consulta: Revisar productos que tengan fecha de vencimiento.
     */
    public function test_puede_filtrar_productos_proximos_a_vencer(): void
    {
        $response = $this->getJson('/api/productos?proximos_vencer=1');

        $response->assertStatus(200);

        $datos = $response->json('datos');
        $this->assertNotEmpty($datos);

        foreach ($datos as $item) {
            $this->assertNotNull($item['fecha_vencimiento']);
        }
    }

    /**
     * Requerimiento de Consulta: Buscar producto por ID existente y no existente.
     */
    public function test_puede_obtener_un_producto_por_id(): void
    {
        $response = $this->getJson('/api/productos/1');

        $response->assertStatus(200)
            ->assertJson([
                'codigo' => 200,
                'producto' => [
                    'id_producto' => 1,
                    'codigo' => 'MED001',
                    'nombre' => 'Paracetamol 500 mg',
                ],
            ]);
    }

    public function test_retorna_404_cuando_producto_no_existe(): void
    {
        $response = $this->getJson('/api/productos/99999');

        $response->assertStatus(404)
            ->assertJson([
                'codigo' => 404,
            ]);
    }

    /**
     * Catálogos auxiliares: Categorías y Proveedores.
     */
    public function test_puede_listar_categorias_y_proveedores(): void
    {
        $resCat = $this->getJson('/api/categorias');
        $resCat->assertStatus(200)
            ->assertJsonStructure(['codigo', 'total', 'datos']);
        $this->assertEquals(3, $resCat->json('total'));

        $resProv = $this->getJson('/api/proveedores');
        $resProv->assertStatus(200)
            ->assertJsonStructure(['codigo', 'total', 'datos']);
        $this->assertEquals(3, $resProv->json('total'));
    }

    /**
     * Movimientos de Stock: Transacción atómica, actualización de stock y validación de saldo.
     */
    public function test_puede_registrar_movimiento_entrada_y_aumentar_stock(): void
    {
        $producto = Producto::find(1);
        $stockInicial = $producto->stock_actual;

        $response = $this->postJson('/api/movimientos', [
            'id_producto' => 1,
            'tipo' => 'ENTRADA',
            'cantidad' => 10,
            'observacion' => 'Compra a proveedor',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'codigo' => 201,
                'stock_actualizado' => [
                    'id_producto' => 1,
                    'nuevo_stock_actual' => $stockInicial + 10,
                ],
            ]);

        $this->assertEquals($stockInicial + 10, $producto->fresh()->stock_actual);
    }

    public function test_puede_registrar_movimiento_salida_y_disminuir_stock(): void
    {
        $producto = Producto::find(1);
        $stockInicial = $producto->stock_actual;

        $response = $this->postJson('/api/movimientos', [
            'id_producto' => 1,
            'tipo' => 'SALIDA',
            'cantidad' => 5,
            'observacion' => 'Venta en mostrador',
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'codigo' => 201,
                'stock_actualizado' => [
                    'id_producto' => 1,
                    'nuevo_stock_actual' => $stockInicial - 5,
                ],
            ]);

        $this->assertEquals($stockInicial - 5, $producto->fresh()->stock_actual);
    }

    public function test_falla_movimiento_salida_si_stock_es_insuficiente(): void
    {
        $response = $this->postJson('/api/movimientos', [
            'id_producto' => 1,
            'tipo' => 'SALIDA',
            'cantidad' => 99999,
            'observacion' => 'Intento de venta sin stock',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'codigo' => 422,
            ]);
    }
}
