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
        Schema::create('transfer_vehiculo', function (Blueprint $table) {
            $table->id('id_vehiculo');
            $table->unsignedBigInteger('id_hotel');
            $table->string('tipo_vehiculo');
            $table->string('matricula')->unique();
            $table->integer('capacidad');
            $table->timestamps();
            
            $table->foreign('id_hotel')->references('id_hotel')->on('tranfer_hotel')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_vehiculo');
    }
};
