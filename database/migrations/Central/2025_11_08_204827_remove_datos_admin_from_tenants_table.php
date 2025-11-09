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
        Schema::connection('central')->table('tenants', function (Blueprint $table) {
            // Migrar datos de datos_admin a datos si es necesario
            \DB::connection('central')->statement("
                UPDATE tenants
                SET datos = CASE
                    WHEN datos_admin IS NOT NULL THEN
                        json_build_object('admin', datos_admin::json)
                    ELSE datos
                END
                WHERE datos_admin IS NOT NULL
            ");

            // Eliminar la columna datos_admin
            $table->dropColumn('datos_admin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->table('tenants', function (Blueprint $table) {
            $table->json('datos_admin')->nullable();
        });
    }
};
