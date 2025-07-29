<?php

namespace Database\Seeders;

use App\Models\Label;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class LabelStaffSeeder extends Seeder
{
//    use WithoutModelEvents;

    public function getSaleGoodsLabels(): array
    {
        return [
            [
                'name' => 'Успешно продано',
                'description' => 'Факт успешной продажи'
            ],
            [
                'name' => 'Не удачная продажа',
                'description' => 'Факт не успешной продажи'
            ],
        ];
    }

    public function getSalesLabel(): array
    {
        return [
            'name' => 'Продажи',
            'description' => 'Для назначения менеджера продаж'
        ];
    }

    public function getTechnicalSupport(): array
    {
        return [
            'name' => 'Техподдержка',
            'description' => 'Для назначения специалиста техподдержки'
        ];
    }

    public function getLabels(): array
    {
        return \array_merge([$this->getSalesLabel()], $this->getSaleGoodsLabels(), [$this->getTechnicalSupport()]);
    }

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $labels = $this->getLabels();

        foreach ($labels as $label) {
            try {
                (new Label())->firstOrCreate($label);
            } catch (QueryException $exception) {
                Log::error('Seed ' . self::class . ' ' . $exception->getMessage());
            } catch (Exception $exception) {
                Log::critical('Seed ' . self::class . ' ' . $exception->getMessage());
            }
        }
    }
}
