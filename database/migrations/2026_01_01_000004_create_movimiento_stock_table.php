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
        Schema::create('movimiento_stock', function (Blueprint $table) {
            $table->increments('id_movimiento');
            $table->unsignedInteger('id_producto');
            $table->enum('tipo', ['ENTRADA', 'SALIDA']);
            $table->integer('cantidad');
            $table->dateTime('fecha_movimiento')->useCurrent();
            $table->string('observacion', 255)->nullable();

            $table->foreign('id_producto')
                ->references('id_producto')
                ->on('producto')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('movimiento_stock');
    }
};
