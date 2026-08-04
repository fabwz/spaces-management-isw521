<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AulaEquipamientoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ahora = now();

        $aula101Id = DB::table('aulas')->where('nombre', 'Aula 101')->value('id');
        $aula205Id = DB::table('aulas')->where('nombre', 'Aula 205')->value('id');
        $laboratorioComputoId = DB::table('aulas')->where('nombre', 'Laboratorio de Cómputo 1')->value('id');
        $auditorioId = DB::table('aulas')->where('nombre', 'Auditorio Principal')->value('id');

        $proyectorId = DB::table('equipamientos')->where('nombre', 'Proyector')->value('id');
        $pantallaTactilId = DB::table('equipamientos')->where('nombre', 'Pantalla táctil')->value('id');
        $computadorasId = DB::table('equipamientos')->where('nombre', 'Computadoras de escritorio')->value('id');
        $sonidoId = DB::table('equipamientos')->where('nombre', 'Sistema de sonido')->value('id');

        DB::table('aula_equipamiento')->insert([
            [
                'aula_id' => $aula101Id,
                'equipamiento_id' => $proyectorId,
                'cantidad' => 1,
                'created_at' => $ahora,
            ],
            [
                'aula_id' => $aula205Id,
                'equipamiento_id' => $proyectorId,
                'cantidad' => 1,
                'created_at' => $ahora,
            ],
            [
                'aula_id' => $aula205Id,
                'equipamiento_id' => $pantallaTactilId,
                'cantidad' => 1,
                'created_at' => $ahora,
            ],
            [
                'aula_id' => $laboratorioComputoId,
                'equipamiento_id' => $computadorasId,
                'cantidad' => 20,
                'created_at' => $ahora,
            ],
            [
                'aula_id' => $auditorioId,
                'equipamiento_id' => $sonidoId,
                'cantidad' => 1,
                'created_at' => $ahora,
            ],
            [
                'aula_id' => $auditorioId,
                'equipamiento_id' => $proyectorId,
                'cantidad' => 1,
                'created_at' => $ahora,
            ],
        ]);
    }
}
