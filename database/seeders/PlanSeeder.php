<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('plans')->insert([
            [
            'name' => 'Basic',
            ],
            [
            'name' => 'Standart',
            ],
            [
            'name' => 'Pro',
            ],


        ]);
    }
}
