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
        Schema::create('transfer_viajeros', function (Blueprint $table) {
            $table->id('id_viajero');
            $table->string('email')->unique();
            $table->string('nombre');
            $table->string('apellido1');
            $table->string('apellido2')->nullable();
            $table->string('direccion')->nullable();
            $table->string('codigoPostal')->nullable();
            $table->string('ciudad')->nullable();
            $table->string('pais')->nullable();
            $table->string('password');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_viajeros');
    }
};
