<?php

namespace Tests\Feature;

use App\Models\Label;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LabelTest extends TestCase
{
    protected User $user;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    public function testLabelIndexScreenRender()
    {
        $this->get(route('labels.index'))->assertStatus(200);
    }

    public function testLabelCreateScreenRenderUnauthorized()
    {
        $this->get(route('labels.create'))->assertStatus(403);
    }

    public function testLabelCreate()
    {
        $this->actingAs($this->user)
            ->post(
                route('labels.store'),
                [
                    'name' => 'Test task',
                ]
            )
            ->assertValid();
    }

    public function testLabelShowScreenRender()
    {
        $lastRecordLabel = Label::query()->latest('id')->first();

        $this->get(route('labels.show', ['label' => $lastRecordLabel->id]))->assertStatus(200);
    }
}
