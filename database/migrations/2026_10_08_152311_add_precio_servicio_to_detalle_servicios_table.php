<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::table('detalleServicios', function (Blueprint $table) {
            $table->decimal('precioServicio', 10, 2)->nullable();
        });

    
        DB::statement('
            UPDATE detalleServicios
            SET precioServicio = (
                SELECT servicios.precioServicio
                FROM servicios
                WHERE servicios.id = detalleServicios.servicio_id
            )
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('detalleServicios', function (Blueprint $table) {
            $table->dropColumn('precioServicio');
        });
    }

};
