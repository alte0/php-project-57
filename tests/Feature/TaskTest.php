<?php

namespace Tests\Feature;

use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskTest extends TestCase
{
    protected User $user;
    protected TaskStatus $taskStatus;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->taskStatus = TaskStatus::factory()->create();
    }

    public function test_task_index_screen_render()
    {
        $this->get(route('tasks.index'))->assertStatus(200);
    }

    public function test_task_create_screen_render_unauthorized()
    {
        $this->get(route('tasks.create'))->assertStatus(403);
    }

    public function test_task_create()
    {
        $response = $this->actingAs($this->user)
            ->post(
                route('tasks.store'),
                [
                    'name' => 'Test task',
                    'description' => '',
                    'status_id' => $this->taskStatus->id,
                ]
            );

        $response
            ->assertValid()
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('tasks.index'));
    }

    public function test_task_show_screen_render()
    {
        $lastRecordTask = Task::query()->latest('id')->first();

        $this->get(route('tasks.show', ['task' => $lastRecordTask->id]))->assertStatus(200);
    }
}
