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
        Schema::create('transfer_reservas', function (Blueprint $table) {
            $table->id('id_reserva');
            $table->string('localizador')->unique();
            $table->unsignedBigInteger('id_hotel')->nullable();
            $table->unsignedBigInteger('id_tipo_reserva')->nullable();
            $table->string('email_cliente');
            $table->timestamp('fecha_reserva')->useCurrent();
            $table->timestamp('fecha_modificacion')->useCurrent()->useCurrentOnUpdate();
            $table->date('fecha_entrada')->nullable();
            $table->time('hora_entrada')->nullable();
            $table->string('numero_vuelo_entrada')->nullable();
            $table->string('origen_vuelo_entrada')->nullable();
            $table->date('fecha_vuelo_salida')->nullable();
            $table->time('hora_partida')->nullable();
            $table->integer('num_viajeros')->nullable();
            $table->unsignedBigInteger('id_vehiculo')->nullable();
            $table->enum('estado', ['pendiente', 'confirmada', 'cancelada', 'completada'])->default('pendiente');
            $table->string('nombre_cliente')->nullable();
            $table->string('apellido1_cliente')->nullable();
            $table->string('apellido2_cliente')->nullable();
            
            $table->foreign('id_hotel')->references('id_hotel')->on('tranfer_hotel')->onDelete('set null');
            $table->foreign('id_tipo_reserva')->references('id_tipo_reserva')->on('tipo_reserva')->onDelete('set null');
            $table->foreign('id_vehiculo')->references('id_vehiculo')->on('transfer_vehiculo')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_reservas');
    }
};
