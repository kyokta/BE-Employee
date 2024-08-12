<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $divisi = [
            ['name' => 'Mobile Apps'],
            ['name' => 'QA'],
            ['name' => 'Full Stack'],
            ['name' => 'Backend'],
            ['name' => 'Frontend'],
            ['name' => 'UI/UX Designer'],
        ];

        DB::table('divisions')->insert($divisi);
    }
}