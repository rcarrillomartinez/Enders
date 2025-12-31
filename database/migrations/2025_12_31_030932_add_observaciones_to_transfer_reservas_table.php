<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transfer_reservas', function (Blueprint $table) {
            // Usamos text en lugar de string por si el cliente quiero escribir mucha informacion
            $table->text('observaciones')->nullable()->after('estado'); 
        });
    }

    public function down(): void
    {
        Schema::table('transfer_reservas', function (Blueprint $table) {
            $table->dropColumn('observaciones');
        });
    }
};