<?php

namespace AppSite\Infrastructure;

use App\Http\Requests\StoreTasksRequest;
use App\Models\Task;
use App\Models\User;
use App\Models\UserLabel;
use AppSite\Application\UseCase\CreateTaskInterface;
use Illuminate\Support\Collection;

final class CreateTask implements CreateTaskInterface
{
    private StoreTasksRequest $request;

    public function __construct(StoreTasksRequest $request)
    {
        $this->request = $request;
    }

    public function execute(): void
    {
        $currentUserId = $this->request->user()->id;
        $labelsSelected = $this->request->input('labels');
        $assignedUserId = $this->request->input('assigned_to_id');

        $userIdByLabels = $this->getUserIdByLabels($labelsSelected, $currentUserId);

        $taskAttributes = $this->request->validated();
        $taskAttributes['created_by_id'] = $currentUserId;

        if (!empty($userIdByLabels)) {
            $collection = new Collection($userIdByLabels);
            $taskAttributes['assigned_to_id'] = $collection->random();
        } elseif ($assignedUserId === null) {
            $randomUserId = $this->getRandomUserId($currentUserId);

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

    /** Получаем пользователей, у которых есть метки
     * @param string|int[] $labels
     * @param int $currentUserId
     * @return array
     */
    public function getUserIdByLabels(array $labels, int $currentUserId): array
    {
        if (empty($labels)) {
            return [];
        }

        $userIdLabels = UserLabel::query()
            ->whereIn('label_id', $labels)
            ->select('user_id')
            ->get()
            ->unique('user_id')
            ->pluck('user_id')
            ->filter(fn($value) => $value !== $currentUserId)
            ->toArray();

        return $userIdLabels;
    }

    /** Получаем случайного пользователя, исключая переданного
     * @param int $currentUserId
     * @return int
     */
    public function getRandomUserId(int $currentUserId): int
    {
        $userArr = User::query()
            ->where('id', '!=', $currentUserId)
            ->get()
            ->random()
            ->toArray();

        if (!empty($userArr)) {
            return $userArr['id'];
        }

        return 0;
    }
}
