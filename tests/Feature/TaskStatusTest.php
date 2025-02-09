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

    public function testTaskStatusesIndexScreenRendered()
    {
        $this->get(route('task_statuses.index'))->assertStatus(200);
    }

    public function testTaskStatusesCreateScreenRenderedUnauthorized()
    {
        $this->get(route('task_statuses.create'))->assertStatus(403);
    }

    public function testTaskStatusesCreate()
    {
        $statusName = 'test_status_name';

        $response = $this->actingAs($this->user)
            ->post(
                route(
                    'task_statuses.store'),
                    ['name' => $statusName,]
                );

        $response
            ->assertValid()
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('task_statuses.index'));

        $taskStatuses = TaskStatus::query()->where('name', $statusName)->get()->toArray();

        $this->assertCount(1, $taskStatuses);
        $this->assertEquals($taskStatuses[0]['name'], $statusName);
    }
}
