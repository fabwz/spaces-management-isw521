<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AulaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ahora = now();

        $sedeCentralId = DB::table('recintos')->where('nombre', 'Sede Central')->value('id');
        $edificioLaboratoriosId = DB::table('recintos')->where('nombre', 'Edificio de Laboratorios')->value('id');
        $convenioUnedId = DB::table('recintos')->where('nombre', 'Convenio UNED Santa Fe')->value('id');

        DB::table('aulas')->insert([
            [
                'recinto_id' => $sedeCentralId,
                'nombre' => 'Aula 101',
                'piso' => '1',
                'tipo' => 'Aula regular',
                'capacidad' => 30,
                'no_disponible_desde' => null,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'recinto_id' => $sedeCentralId,
                'nombre' => 'Aula 102',
                'piso' => '1',
                'tipo' => 'Aula regular',
                'capacidad' => 25,
                'no_disponible_desde' => null,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'recinto_id' => $sedeCentralId,
                'nombre' => 'Aula 205',
                'piso' => '2',
                'tipo' => 'Aula regular',
                'capacidad' => 40,
                'no_disponible_desde' => null,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'recinto_id' => $edificioLaboratoriosId,
                'nombre' => 'Laboratorio de Cómputo 1',
                'piso' => '1',
                'tipo' => 'Laboratorio de cómputo',
                'capacidad' => 20,
                'no_disponible_desde' => null,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'recinto_id' => $edificioLaboratoriosId,
                'nombre' => 'Laboratorio de Ciencias 1',
                'piso' => '2',
                'tipo' => 'Laboratorio de ciencias',
                'capacidad' => 18,
                'no_disponible_desde' => null,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
            [
                'recinto_id' => $convenioUnedId,
                'nombre' => 'Auditorio Principal',
                'piso' => '1',
                'tipo' => 'Auditorio',
                'capacidad' => 120,
                'no_disponible_desde' => null,
                'created_at' => $ahora,
                'updated_at' => $ahora,
            ],
        ]);
    }
}
