# EXAMEN FINAL: SISTEMA DE CONTROL DE STOCK PARA UNA FARMACIA

**Estudiantes:** Camila Beltran, Felipe González  
**Asignatura:** Desarrollo de Software Web I  
**Sección:** 50  
**Docente:** Víctor Cofré  
**Framework:** Laravel 11 (PHP 8.3)  
**Base de Datos:** MySQL / SQLite  

---

## 1. Descripción del Proyecto

Este proyecto implementa la solución que permite gestionar de manera centralizada el inventario de medicamentos, artículos de aseo y productos de cuidado infantil de la farmacia, controlar existencias críticas mediante umbrales de stock mínimo, alertar sobre fechas de vencimiento y registrar transaccionalmente las entradas y salidas de mercadería.

---

## 2. Cumplimiento de Requerimientos.

### Requerimiento Central: Endpoint API para Registrar Productos

* **Método HTTP:** `POST`
* **Endpoint:** `/api/productos` (y versión `/api/v1/productos`)
* **Headers requeridos:**
  ```http
  Content-Type: application/json
  Accept: application/json
  ```
* **Cuerpo de la Petición (Ejemplo Oficial):**
  ```json
  {
    "codigo": "MED003",
    "nombre": "Vitamina C 500 mg",
    "descripcion": "Caja de 30 comprimidos",
    "precio": 5990,
    "stock_actual": 25,
    "stock_minimo": 5,
    "fecha_vencimiento": "2028-12-31",
    "id_categoria": 1,
    "id_proveedor": 1
  }
  ```

#### Validaciones Implementadas (`StoreProductoRequest`):
1. **`codigo`**: Obligatorio, texto de hasta 30 caracteres, **único en la tabla producto** (no admite duplicados).
2. **`nombre`**: Obligatorio, texto de hasta 150 caracteres.
3. **`descripcion`**: Opcional (nullable), texto de hasta 255 caracteres.
4. **`precio`**: Obligatorio, valor numérico decimal mayor o igual a 0.
5. **`stock_actual`**: Obligatorio, número entero mayor o igual a 0.
6. **`stock_minimo`**: Entero mayor o igual a 0 (por defecto 5 si no se especifica).
7. **`fecha_vencimiento`**: Opcional (nullable), formato fecha `YYYY-MM-DD`.
8. **`id_categoria`**: Obligatorio, entero con validación de existencia en la tabla `categoria`.
9. **`id_proveedor`**: Obligatorio, entero con validación de existencia en la tabla `proveedor`.

#### Respuesta Exitosa (`201 Created`) - Exacta al Requerimiento:
```json
{
  "codigo": 201,
  "mensaje": "Producto registrado correctamente",
  "producto": {
    "id_producto": 7,
    "codigo": "MED003",
    "nombre": "Vitamina C 500 mg"
  }
}
```

#### Respuesta ante Error de Validación (`422 Unprocessable Entity`):
```json
{
  "codigo": 422,
  "mensaje": "Error de validación en los datos proporcionados",
  "errores": {
    "codigo": [
      "El código de producto ya se encuentra registrado en el sistema."
    ]
  }
}
```

---

## 3. Matriz Completa de Endpoints de la API REST

| Operación / Requerimiento | Método | Endpoint | Código HTTP | Descripción |
| :--- | :--- | :--- | :--- | :--- |
| **Registrar Producto (Pauta)** | `POST` | `/api/productos` | **`201 Created`** / `422` | Inserta nuevo producto validando unicidad, claves foráneas y tipos. |
| **Inventario Completo** | `GET` | `/api/productos` | **`200 OK`** | Lista todos los productos con datos de categoría y proveedor. |
| **Filtro: Reposición de Stock** | `GET` | `/api/productos?reposicion=1` | **`200 OK`** | Identifica productos cuyo stock actual es menor o igual al mínimo. |
| **Filtro: Próximos a Vencer** | `GET` | `/api/productos?proximos_vencer=1` | **`200 OK`** | Lista productos con fecha de caducidad ordenados cronológicamente. |
| **Detalle de Producto por ID** | `GET` | `/api/productos/{id}` | **`200 OK`** / **`404 Not Found`** | Retorna la ficha técnica de un producto específico. |
| **Catálogo de Categorías** | `GET` | `/api/categorias` | **`200 OK`** | Lista las 3 categorías del sistema (Medicamentos, Aseo, Bebé). |
| **Catálogo de Proveedores** | `GET` | `/api/proveedores` | **`200 OK`** | Lista los proveedores registrados con teléfono y correo. |
| **Registrar Movimiento de Stock** | `POST` | `/api/movimientos` | **`201 Created`** / `422` | Registra `ENTRADA` o `SALIDA` actualizando el stock con transacción atómica. |
| **Historial de Movimientos** | `GET` | `/api/movimientos` | **`200 OK`** | Consulta cronológica de entradas y salidas de inventario. |

*Nota: Todas las rutas también están disponibles bajo el prefijo `/api/v1/...`.*

---

## 4. Modelo de Base de Datos y Persistencia

El sistema implementa estrictamente las 4 tablas definidas en `farmacia_stock_mysql.sql`:

1. **`categoria`**: `id_categoria` (PK), `nombre`, `descripcion`.
2. **`proveedor`**: `id_proveedor` (PK), `nombre`, `telefono`, `correo`.
3. **`producto`**: `id_producto` (PK), `codigo` (UNIQUE), `nombre`, `descripcion`, `precio`, `stock_actual`, `stock_minimo`, `fecha_vencimiento`, `id_categoria` (FK), `id_proveedor` (FK).
4. **`movimiento_stock`**: `id_movimiento` (PK), `id_producto` (FK), `tipo` (`ENTRADA`/`SALIDA`), `cantidad`, `fecha_movimiento`, `observacion`.

### Compatibilidad Dual (MySQL / SQLite)
* **SQLite (Por defecto para portabilidad inmediata):** El archivo `database/database.sqlite` ya se encuentra configurado y migrado con los seeders deterministas oficiales. Permite ejecutar el proyecto y pasar todas las pruebas sin requerir dependencias externas.
* **MySQL:** Para utilizar MySQL, basta con descomentar las líneas correspondientes en el archivo `.env`:
  ```env
  DB_CONNECTION=mysql
  DB_HOST=127.0.0.1
  DB_PORT=3306
  DB_DATABASE=farmacia_stock
  DB_USERNAME=root
  DB_PASSWORD=
  ```
  Y ejecutar:
  ```bash
  php artisan migrate:fresh --seed
  ```

---

## 5. Instrucciones para Ejecutar y Probar

### 1. Iniciar el Servidor de Desarrollo
Puedes hacer doble clic en el archivo `iniciar_servidor.bat` o ejecutar en terminal dentro de `farmacia_stock`:
```bash
php artisan serve
```
El servidor quedará disponible en `http://127.0.0.1:8000`.

### 2. Ejecutar la Suite de Pruebas Automatizadas
Puedes hacer doble clic en `ejecutar_pruebas.bat` o ejecutar en terminal:
```bash
php artisan test --testdox
```

#### Resultados de las Pruebas (17 Tests / 138 Aserciones - 100% Exitosas):
```text
Producto Api (Tests\Feature\ProductoApi)
  ✔ Puede registrar un nuevo producto con exito codigo 201
  ✔ Puede registrar producto en ruta versionada v1
  ✔ Falla al registrar producto con codigo duplicado codigo 422
  ✔ Falla cuando faltan campos obligatorios codigo 422
  ✔ Falla cuando categoria o proveedor no existen codigo 422
  ✔ Falla cuando precio o stock son negativos codigo 422
  ✔ Puede listar todos los productos codigo 200
  ✔ Puede filtrar productos que requieren reposicion
  ✔ Puede filtrar productos proximos a vencer
  ✔ Puede obtener un producto por id
  ✔ Retorna 404 cuando producto no existe
  ✔ Puede listar categorias y proveedores
  ✔ Puede registrar movimiento entrada y aumentar stock
  ✔ Puede registrar movimiento salida y disminuir stock
  ✔ Falla movimiento salida si stock es insuficiente

```

### 3. Probar mediante Postman
Se incluye el archivo oficial de colección:
`Farmacia_Stock_API.postman_collection.json`

1. Abre **Postman**.
2. Presiona el botón **Import** en la esquina superior izquierda.
3. Arrastra o selecciona el archivo `Farmacia_Stock_API.postman_collection.json`.
4. La colección incluye 13 peticiones organizadas por número que prueban:
   - Registro exitoso con código 201.
   - Validaciones negativas (código duplicado, campos nulos, claves foráneas inválidas).
   - Consultas de inventario, stock crítico y vencimientos.
   - Movimientos de entrada y salida con control de saldo.

---

## 6. Arquitectura del Código Fuente

Siguiendo las pautas de arquitectura senior de `AgentLaravel.md`:

```text
farmacia_stock/
├── app/
│   ├── Http/
│   │   ├── Controllers/Api/
│   │   │   ├── ProductoController.php        # Controlador HTTP delgado para productos
│   │   │   ├── CategoriaController.php       # Controlador catálogo categorías
│   │   │   ├── ProveedorController.php       # Controlador catálogo proveedores
│   │   │   └── MovimientoStockController.php # Transacciones atómicas de stock
│   │   ├── Requests/
│   │   │   ├── StoreProductoRequest.php      # Validación estricta y respuesta JSON 422
│   │   │   └── StoreMovimientoStockRequest.php # Validación de movimientos de inventario
│   │   └── Resources/
│   │       ├── ProductoCreatedResource.php   # Formato exacto 201 solicitado en el examen
│   │       ├── ProductoResource.php          # Recurso con relaciones y datos calculados
│   │       ├── CategoriaResource.php
│   │       ├── ProveedorResource.php
│   │       └── MovimientoStockResource.php
│   └── Models/
│       ├── Categoria.php                     # Modelo relacional categoria
│       ├── Proveedor.php                     # Modelo relacional proveedor
│       ├── Producto.php                      # Modelo relacional producto con scopes
│       └── MovimientoStock.php               # Modelo relacional movimiento_stock
├── database/
│   ├── migrations/                           # Migraciones de las 4 tablas
│   └── seeders/                              # Seeders con los datos de farmacia_stock_mysql.sql
├── routes/
│   └── api.php                               # Definición de rutas RESTful
├── tests/
│   └── Feature/
│       └── ProductoApiTest.php               # 17 Feature tests de integración
├── Farmacia_Stock_API.postman_collection.json # Colección de Postman lista para importar
├── iniciar_servidor.bat                      # Script de arranque rápido
└── ejecutar_pruebas.bat                      # Script de verificación rápida
```
