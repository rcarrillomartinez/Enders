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
        Schema::create('tranfer_hotel', function (Blueprint $table) {
            $table->id('id_hotel');
            $table->string('usuario')->unique();
            $table->string('password');
            $table->string('nombre_hotel');
            $table->unsignedBigInteger('id_zona')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tranfer_hotel');
    }
};
