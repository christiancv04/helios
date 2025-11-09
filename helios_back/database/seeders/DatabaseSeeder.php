<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CompanySeed::class,
            DepartmentSeed::class,
            SubdepartmentSeed::class,
        ]);
    }
}
