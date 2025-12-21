<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Para viajeros
        if (!Schema::hasColumn('transfer_viajeros', 'foto')) {
            Schema::table('transfer_viajeros', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('email');
            });
        }

        // Para hoteles
        if (!Schema::hasColumn('transfer_hoteles', 'foto')) {
            Schema::table('tranfer_hotel', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('email');
            });
        }

        // Para admins
        if (!Schema::hasColumn('transfer_admins', 'foto')) {
            Schema::table('transfer_admin', function (Blueprint $table) {
                $table->string('foto')->nullable()->after('email');
            });
        }
    }

    public function down(): void
    {
        Schema::table('transfer_viajeros', function ($column) { $column->dropColumn('foto'); });
        Schema::table('tranfer_hotel', function ($column) { $column->dropColumn('foto'); });
        Schema::table('transfer_admin', function ($column) { $column->dropColumn('foto'); });
    }
};