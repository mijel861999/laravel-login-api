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
        Schema::table('carrito_compras', function (Blueprint $table) {
            // Agregar campos necesarios
            $table->unsignedBigInteger('detalle_pedido_id')->nullable();
            $table->unsignedBigInteger('producto_id')->nullable();

            // Definir las claves foráneas
            $table->foreign('detalle_pedido_id')->references('id')->on('detalle_pedidos');
            $table->foreign('producto_id')->references('id')->on('productos');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('carrito_compras', function (Blueprint $table) {
            // Revertir los cambios realizados en el método up
            $table->dropForeign(['detalle_pedido_id']);
            $table->dropForeign(['producto_id']);
            
            $table->dropColumn('detalle_pedido_id');
            $table->dropColumn('producto_id');
        });
    }
};
