<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TaskStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
    }
    public static function runManual(): void
    {
        DB::table('task_statuses')->insert([
            ['name' => 'Новый', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'В работе', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'На тестировании', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Завершен', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
