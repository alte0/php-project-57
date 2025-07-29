<?php

namespace Database\Seeders;

use App\Models\Label;
use App\Models\User;
use App\Models\UserLabel;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\QueryException;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class StaffSeeder extends Seeder
{
    //use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $LabelStaffSeeder = new LabelStaffSeeder();
        $LabelStaffSeeder->run();

        $managers = [
            [
                'user' => [
                    'name' => 'manager-1',
                    'email' => 'manager1@test.loc',
                    'password' => Hash::make('manager-1'),
                ],
                'linked' => $LabelStaffSeeder->getSalesLabel()
            ],
            [
                'user' => [
                    'name' => 'manager-2',
                    'email' => 'manager2@test.loc',
                    'password' => Hash::make('manager-2'),
                ],
                'linked' => $LabelStaffSeeder->getSalesLabel()
            ],
            [
                'user' => [
                    'name' => 'technical-support-1',
                    'email' => 'technical-support-1@test.loc',
                    'password' => Hash::make('technical-support-1'),
                ],
                'linked' => $LabelStaffSeeder->getTechnicalSupport()
            ],
            [
                'user' => [
                    'name' => 'technical-support-2',
                    'email' => 'technical-support-2@test.loc',
                    'password' => Hash::make('technical-support-2'),
                ],
                'linked' => $LabelStaffSeeder->getTechnicalSupport()
            ],
            [
                'user' => [
                    'name' => 'user-1',
                    'email' => 'user-1@test.loc',
                    'password' => Hash::make('user-1'),
                ],
            ],
        ];

        foreach ($managers as $manager) {
            try {
                if (isset($manager['user'])) {
                    DB::beginTransaction();

                    $user = new User;
                    $userId = $user->firstOrCreate($manager['user'])->id;

                    if (isset($manager['linked']['name'])) {
                        $LabelId = Label::query()
                            ->where('name', ['=' => $manager['linked']['name']])
                            ->firstOrFail()
                            ->id;
                        $arrUserLabel = ['user_id' => $userId, 'label_id' => $LabelId];

                        (new UserLabel($arrUserLabel))->saveOrFail();

                        Log::info(self::class, $arrUserLabel);
                    }

                    DB::commit();
                }
            } catch (QueryException $exception) {
                DB::rollBack();
                Log::error('Seed ' . self::class . ' ' . $exception->getMessage());
            } catch (Exception $exception) {
                DB::rollBack();
                Log::critical('Seed ' . self::class . ' ' . $exception->getMessage());
            }
        }
    }
}
