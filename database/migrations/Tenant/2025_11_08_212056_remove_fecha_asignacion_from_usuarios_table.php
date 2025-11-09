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
        if (Schema::hasColumn('usuarios', 'fecha_asignacion')) {
            Schema::table('usuarios', function (Blueprint $table) {
                $table->dropColumn('fecha_asignacion');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->timestamp('fecha_asignacion')->useCurrent();
        });
    }
};
