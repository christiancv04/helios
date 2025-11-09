<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompanySeed extends Seeder
{
    public function run(): void
    {
        DB::table('tbl_company')->insert([
            [
                'ruc' => '20123456789',
                'name' => 'Helios S.A.C.',
                'address' => 'Av. Universitaria 123, Lima, Perú',
                'email' => 'helios@helios.com',
                'phone' => '987654321',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
