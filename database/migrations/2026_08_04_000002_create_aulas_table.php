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
        Schema::create('aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recinto_id')->nullable()->constrained('recintos')->nullOnDelete()->cascadeOnUpdate();
            $table->string('nombre', 30);
            $table->string('piso', 10)->nullable();
            $table->enum('tipo', [
                'Aula regular',
                'Laboratorio de cómputo',
                'Laboratorio de ciencias',
                'Laboratorio de idiomas',
                'Auditorio',
                'Otro',
            ])->default('Aula regular');
            $table->smallInteger('capacidad')->unsigned()->nullable();
            $table->date('no_disponible_desde')->nullable();
            $table->timestamps();

            $table->unique('nombre', 'aulas_nombre_unique');
            $table->index(['tipo', 'capacidad'], 'aulas_tipo_capacidad_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aulas');
    }
};
