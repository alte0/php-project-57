<?php

namespace AppSite\Infrastructure;

use App\Http\Requests\StoreTasksRequest;
use App\Models\Task;
use App\Models\UserLabel;
use AppSite\Application\UseCase\CreateTaskInterface;
use Illuminate\Support\Collection;

final class CreateTask implements CreateTaskInterface
{
    private StoreTasksRequest $request;
    private RandomUserId $randomUserId;
    private UserIdByLabels $userIdByLabels;

    public function __construct(
        StoreTasksRequest $request,
        RandomUserId      $randomUserIdInstance,
        UserIdByLabels    $userIdByLabelsInstance
    )
    {
        $this->request = $request;
        $this->randomUserId = $randomUserIdInstance;
        $this->userIdByLabels = $userIdByLabelsInstance;
    }

    public function execute(): void
    {
        $currentUserId = $this->request->user()->id;
        $labelsSelected = $this->request->input('labels', []);
        $assignedUserId = $this->request->input('assigned_to_id');

        $userIdByLabels = $this->userIdByLabels->getUsersList($labelsSelected, $currentUserId);

        $taskAttributes = $this->request->validated();
        $taskAttributes['created_by_id'] = $currentUserId;

        if (!empty($userIdByLabels)) {
            $collection = new Collection($userIdByLabels);
            $taskAttributes['assigned_to_id'] = $collection->random();
        } elseif ($assignedUserId === null) {
            $randomUserId = $this->randomUserId->getId($currentUserId);

            if ($randomUserId > 0) {
                $taskAttributes['assigned_to_id'] = $assignedUserId;
            }
        }

        $task = new Task($taskAttributes);
        $task->save();
        $task->labels()->attach(
            $labelsSelected,
            [
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
