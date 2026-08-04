<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReservaAulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ahora = now();

        $auditorioId = DB::table('aulas')->where('nombre', 'Auditorio Principal')->value('id');
        $aula101Id = DB::table('aulas')->where('nombre', 'Aula 101')->value('id');
        $usuarioId = DB::table('users')->value('id');

        DB::table('reservas_aulas')->insert([
            [
                'aula_id' => $auditorioId,
                'user_id' => $usuarioId,
                'tipo' => 'Reserva',
                'solicitante' => 'Asociación de Estudiantes',
                'motivo' => 'Acto de graduación',
                'fecha_inicio' => '2026-11-20',
                'fecha_fin' => null,
                'dias_semana' => null,
                'hora_inicio' => '18:00:00',
                'hora_fin' => '21:00:00',
                'estado' => 'Aprobada',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'aula_id' => $aula101Id,
                'user_id' => $usuarioId,
                'tipo' => 'Bloqueo administrativo',
                'solicitante' => null,
                'motivo' => 'Mantenimiento eléctrico programado',
                'fecha_inicio' => '2026-09-01',
                'fecha_fin' => '2026-09-05',
                'dias_semana' => null,
                'hora_inicio' => '07:00:00',
                'hora_fin' => '12:00:00',
                'estado' => 'Aprobada',
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
        ]);
    }
}
