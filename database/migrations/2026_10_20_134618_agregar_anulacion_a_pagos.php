<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        
        Schema::table('pagos', function (Blueprint $table) {
            $table->enum('estado_pago', ['pendiente', 'pagado', 'anulado'])
                ->default('pendiente')
                ->change();
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->text('motivo_anulacion')->nullable();
            $table->dateTime('fecha_anulacion')->nullable();
            $table->foreignId('anulado_por')->nullable()
                ->constrained('users')->restrictOnDelete();
        });

        if (Schema::hasIndex('pagos', ['cita_id'], 'unique')) {
            Schema::table('pagos', function (Blueprint $table) {
                $table->dropUnique(['cita_id']);
            });
        }

        DB::statement(
            "CREATE UNIQUE INDEX pagos_cita_activa_unique ON pagos (cita_id) WHERE estado_pago != 'anulado'"
        );
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS pagos_cita_activa_unique');

        Schema::table('pagos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('anulado_por');
            $table->dropColumn(['motivo_anulacion', 'fecha_anulacion']);
        });

        Schema::table('pagos', function (Blueprint $table) {
            $table->enum('estado_pago', ['pendiente', 'pagado'])
                ->default('pendiente')
                ->change();
        });
    }
};