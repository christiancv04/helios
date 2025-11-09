<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DepartmentSeed extends Seeder
{
    public function run(): void
    {
        $departments = [
            'Dirección General',
            'Recursos Humanos',
            'Finanzas',
            'Operaciones',
            'Tecnología',
            'Marketing',
            'Ventas',
            'Legal',
            'Comercial',
            'Calidad',
            'Sostenibilidad',
            'Innovación',
            'Planeamiento Estratégico',
            'Administración',
            'Medio Ambiente',
        ];

        foreach ($departments as $dep) {
            DB::table('tbl_department')->insert([
                'name' => $dep,
                'n_employees' => rand(25, 100),
                'level' => 1,
                'ambassador' => fake()->name(),
                'status' => 1,
                'idcompany' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
