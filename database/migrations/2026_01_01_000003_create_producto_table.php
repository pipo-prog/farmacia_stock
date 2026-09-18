<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('producto', function (Blueprint $table) {
            $table->increments('id_producto');
            $table->string('codigo', 30)->unique();
            $table->string('nombre', 150);
            $table->string('descripcion', 255)->nullable();
            $table->decimal('precio', 10, 2);
            $table->integer('stock_actual')->default(0);
            $table->integer('stock_minimo')->default(5);
            $table->date('fecha_vencimiento')->nullable();
            $table->unsignedInteger('id_categoria');
            $table->unsignedInteger('id_proveedor');

            $table->foreign('id_categoria')
                ->references('id_categoria')
                ->on('categoria')
                ->onDelete('restrict')
                ->onUpdate('cascade');

            $table->foreign('id_proveedor')
                ->references('id_proveedor')
                ->on('proveedor')
                ->onDelete('restrict')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('producto');
    }
};
