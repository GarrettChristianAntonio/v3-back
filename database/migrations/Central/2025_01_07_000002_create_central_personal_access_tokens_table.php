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
        Schema::connection('central')->create('tokens_acceso_personal', function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs('tokenable');
            $table->string('nombre');
            $table->string('token', 64)->unique();
            $table->text('habilidades')->nullable();
            $table->timestamp('ultimo_uso_en')->nullable();
            $table->timestamp('expira_en')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('central')->dropIfExists('tokens_acceso_personal');
    }
};
