<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\UserLabel;
use Database\Seeders\StaffSeeder;
use Database\Seeders\TaskStatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTaskTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected int $currentUserId;
    protected int $labelIdSelectedWithUser;

    protected int $taskStatusId;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->currentUserId = $this->user->id;

        (new TaskStatusSeeder())->run();
        (new StaffSeeder())->run();

        $this->labelIdSelectedWithUser = UserLabel::query()
            ->where('user_id', '!=', $this->currentUserId)
            ->select(['label_id'])
            ->get()
            ->pluck('label_id')
            ->unique()
            ->random();
        $this->taskStatusId = TaskStatus::query()->get()->first()->id;
    }

    public function testCreateByUserLabelAndNotAssignedUserId()
    {
        $taskName = 'new task name';

        $response = $this->actingAs($this->user)
            ->post(
                route('tasks.store'),
                [
                    'name' => $taskName,
                    'description' => '',
                    'status_id' => $this->taskStatusId,
                    'assigned_to_id' => null,
                    'labels' => [$this->labelIdSelectedWithUser]
                ]
            );

        $response
            ->assertValid()
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $newTask = Task::query()
            ->where('name', '=', $taskName)
            ->select('assigned_to_id')
            ->get()
            ->first();

        // id пользователя, у которого есть выбранная метка
        $userIdByLabel = UserLabel::query()
            ->where('label_id', '=', $this->labelIdSelectedWithUser)
            ->where('user_id', '=', $newTask->assigned_to_id)
            ->select('user_id')
            ->get()
            ->first()
            ->user_id;

        // проверка, что назначен не текущий пользователь
        $this->assertNotEquals($this->currentUserId, $newTask->assigned_to_id);

        $this->assertIsNumeric($userIdByLabel);
    }

    public function testCreateNotLabelAndNotAssignedUserId()
    {
        $taskName = 'new task name 2';

        $response = $this->actingAs($this->user)
            ->post(
                route('tasks.store'),
                [
                    'name' => $taskName,
                    'description' => '',
                    'status_id' => $this->taskStatusId,
                    'assigned_to_id' => null,
                    'labels' => []
                ]
            );

        $response
            ->assertValid()
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tasks.index'));

        $newTask = Task::query()
            ->where('name', '=', $taskName)
            ->select('assigned_to_id')
            ->get()
            ->first();

        $this->assertNotEquals($this->currentUserId, $newTask->assigned_to_id);
    }
}
