<?php

namespace Database\Seeders;

use App\Models\TaskStatus;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TaskStatusSeeder extends Seeder
{
//    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            ['name' => 'Новый'],
            ['name' => 'В работе'],
            ['name' => 'На тестировании'],
            ['name' => 'Завершен'],
        ];


        foreach ($statuses as $status) {
            try {
                (new TaskStatus)->firstOrCreate($status);
            } catch (QueryException $exception) {
                Log::error('Seed ' . self::class . ' ' . $exception->getMessage());
            } catch (Exception $exception) {
                Log::critical('Seed ' . self::class . ' ' . $exception->getMessage());
            }
        }
    }
}
