<?php

namespace Database\Seeders;

use App\Http\Requests\StoreTasksRequest;
use App\Models\Label;
use AppSite\Infrastructure\RandomUserId;
use AppSite\Infrastructure\UserIdByLabels;
use Illuminate\Http\Request;
use App\Models\User;
use AppSite\Infrastructure\CreateTask;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class TaskSeder extends Seeder
{
    //use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::factory()->create(
            [
                'name' => 'User (creator)',
                'email' => 'user@test.loc',
                'password' => 'UserPswdMain1234'
            ]
        );

        $labelNameList = Collection::make((new LabelStaffSeeder())->getLabels())->pluck('name')->toArray();
        $labels = Label::query()->whereIn('name', $labelNameList)->get()->pluck('id')->toArray();
        $collectionLabels = Collection::make($labels);

        if (!empty($labels)) {
            for ($i = 1; $i <= rand(35, 100); $i++) {
                $request = Request::create(
                    route('tasks.store'),
                    'POST',
                    [
                        'name' => 'Task ' . $i,
                        'description' => 'desc Task ' . $i,
                        'status_id' => '1',
                        'assigned_to_id' => null,
                        'labels' => [$collectionLabels->random()],
                    ]
                );
                $request->setUserResolver(fn() => $user);

                $formRequest = StoreTasksRequest::createFrom($request);
                $formRequest->setContainer(app());
                $formRequest->setRedirector(app('redirect'));
                $formRequest->validateResolved();

                (new CreateTask($formRequest, new RandomUserId(), new UserIdByLabels()))->execute();
            }
        }
    }
}
