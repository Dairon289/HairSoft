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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->restrictOnDelete();
            $table->foreignId('empleado_id')->constrained('empleados')->restrictOnDelete();
            $table->dateTime('fecha_programada');
            $table->dateTime('fecha_fin_programada');
            $table->dateTime('fecha_inicio')->nullable();
            $table->dateTime('fecha_fin')->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'iniciada', 'finalizada'])->default('pendiente');
            $table->datetime('fechaCancelacion')->nullable();
            $table->text('motivoCancelacion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
