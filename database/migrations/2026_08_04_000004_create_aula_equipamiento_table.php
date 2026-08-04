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
        Schema::create('aula_equipamiento', function (Blueprint $table) {
            $table->foreignId('aula_id')->constrained('aulas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('equipamiento_id')->constrained('equipamientos')->cascadeOnDelete()->cascadeOnUpdate();
            $table->smallInteger('cantidad')->unsigned()->default(1);
            $table->timestamp('created_at')->nullable();

            $table->primary(['aula_id', 'equipamiento_id']);
            $table->index('equipamiento_id', 'aula_equipamiento_equipamiento_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aula_equipamiento');
    }
};
