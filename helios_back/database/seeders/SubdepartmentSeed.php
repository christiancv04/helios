<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubdepartmentSeed extends Seeder
{
    public function run(): void
    {
        $subdepartments = [
            ['Dirección General', 'Asesoría Corporativa'],
            ['Dirección General', 'Gestión Estratégica'],

            ['Recursos Humanos', 'Capacitación'],
            ['Recursos Humanos', 'Gestión de Talento'],
            ['Recursos Humanos', 'Clima Laboral'],
            ['Recursos Humanos', 'Bienestar y Cultura'],

            ['Finanzas', 'Contabilidad'],
            ['Finanzas', 'Tesorería'],
            ['Finanzas', 'Control Presupuestal'],

            ['Operaciones', 'Producción'],
            ['Operaciones', 'Logística'],
            ['Operaciones', 'Mantenimiento'],
            ['Operaciones', 'Distribución'],

            ['Tecnología', 'Soporte Técnico'],
            ['Tecnología', 'Infraestructura'],
            ['Tecnología', 'Desarrollo de Software'],
            ['Tecnología', 'Ciberseguridad'],

            ['Marketing', 'Publicidad'],
            ['Marketing', 'Relaciones Públicas'],
            ['Marketing', 'Análisis de Mercado'],

            ['Ventas', 'Ventas Corporativas'],
            ['Ventas', 'Atención al Cliente'],
            ['Ventas', 'Postventa'],
            ['Ventas', 'Canales Digitales'],

            ['Legal', 'Contratos'],
            ['Legal', 'Auditoría Legal'],

            ['Comercial', 'Compras'],
            ['Comercial', 'Proveedores'],

            ['Calidad', 'Control de Procesos'],
            ['Calidad', 'Normativas y Cumplimiento'],

            ['Sostenibilidad', 'Responsabilidad Social'],
            ['Sostenibilidad', 'Seguridad Industrial'],

            ['Innovación', 'Proyectos Estratégicos'],
            ['Innovación', 'Investigación y Desarrollo'],

            ['Medio Ambiente', 'Reciclaje y Energía'],
            ['Medio Ambiente', 'Evaluación de Impacto'],
        ];

        foreach ($subdepartments as [$parent, $child]) {
            $idParent = DB::table('tbl_department')->where('name', $parent)->value('id');

            $idChild = DB::table('tbl_department')->insertGetId([
                'name' => $child,
                'n_employees' => rand(10, 60),
                'level' => 2,
                'ambassador' => fake()->name(),
                'status' => 1,
                'idcompany' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

            if ($idParent && $idChild) {
                DB::table('tbl_subdepartment')->insert([
                    'iddepartment' => $idParent,
                    'idsubdepartment' => $idChild,
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
