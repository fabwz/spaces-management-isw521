<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas_aulas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('aula_id')
                ->constrained('aulas')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_id')->nullable()
                ->constrained('users')->nullOnDelete()->cascadeOnUpdate();
            $table->enum('tipo', ['Reserva', 'Bloqueo administrativo'])->default('Reserva');
            $table->string('solicitante', 120)->nullable();
            $table->string('motivo', 255);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->set('dias_semana', [
                'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo',
            ])->nullable();
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->enum('estado', ['Solicitada', 'Aprobada', 'Rechazada', 'Cancelada'])
                ->default('Solicitada');
            $table->timestamps();

            $table->index(['aula_id', 'fecha_inicio', 'fecha_fin']);
            $table->index('estado');
        });

        DB::statement('ALTER TABLE reservas_aulas ADD CONSTRAINT chk_reservas_aulas_rango_horas CHECK (hora_fin > hora_inicio)');
        DB::statement('ALTER TABLE reservas_aulas ADD CONSTRAINT chk_reservas_aulas_rango_fechas CHECK (fecha_fin IS NULL OR fecha_fin >= fecha_inicio)');
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas_aulas');
    }
};