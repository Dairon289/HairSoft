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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('nombreEmpleado');
            $table->string('apellidoEmpleado');
            $table->string('especialidad');
            $table->string('correoEmpleado')->unique();
            $table->string('telefonoEmpleado');
            $table->enum('disponibilidad', ['disponible', 'no disponible']);
            $table->date('fechaIngreso');
            $table->time('horaIngreso');
            $table->time('horaSalida');
            $table->enum('estadoEmpleado', ['activo', 'inactivo']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empleados');
    }
};
