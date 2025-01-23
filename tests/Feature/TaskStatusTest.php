<?php

namespace Tests\Feature;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TaskStatusTest extends TestCase
{
    protected User $user;
    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function test_task_statuses_index_screen_rendered()
    {
        $this->get(route('task_statuses.index'))->assertStatus(200);
    }

    public function test_task_statuses_create_screen_rendered_unauthorized()
    {
        $this->get(route('task_statuses.create'))->assertStatus(403);
    }

    public function test_task_statuses_create()
    {
        $statusName = 'test_status_name';

        $this->post(
            route('login'),
            [
                'email' => $this->user->email,
                'password' => $this->user->password,
            ]
        );

        $response = $this->post(
            route('task_statuses.store'),
            [
                'name' => $statusName,
            ]
        );

        $taskStatus = TaskStatus::where('name', $statusName)->first();

        $this->assertEquals($taskStatus->name, $statusName);
    }
}
