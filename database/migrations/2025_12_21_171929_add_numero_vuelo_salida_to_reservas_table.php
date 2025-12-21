<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    
    Schema::table('transfer_reservas', function (Blueprint $table) {
        $table->string('numero_vuelo_salida')->nullable()->after('hora_partida');
    });
}

public function down(): void
{
    Schema::table('transfer_reservas', function (Blueprint $table) {
        $table->dropColumn('numero_vuelo_salida');
    });
}
};