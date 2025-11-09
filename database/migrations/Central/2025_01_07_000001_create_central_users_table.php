<?php

declare(strict_types=1);

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
        Schema::connection('central')->create('usuarios', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nombre_usuario')->unique();
            $table->string('contrasena');
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('usuarios');
    }
};
