<?php

use App\Models\Task;
use function Pest\Laravel\{getJson, postJson, patchJson, deleteJson};

beforeEach(function () {
    passport(); // Initialize passport for testing

});

test('it can create task', function () {
    signIn();

    $response = postJson(route('v1.tasks.store'), [
        'title' => 'Create API Documentation',
        'description' => 'Description 1',
        'due_at' => '2024/04/30',
    ]);


    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'uuid',
                'title',
                'description',
                'completed',
                'completed_at',
                'due_at',
                'user',
                'created_at',
                'updated_at',
            ],
        ]);

    $response->assertJsonFragment([
        'title' => 'Create API Documentation',
        'description' => 'Description 1',
        'completed' => false,
        'due_at' => '2024-04-30',
    ]);
})->group('task');


test('it can update task', function () {
    signIn();

    $task = Task::factory()->create();

    $response = patchJson(route('v1.tasks.update', ['task_uuid' => $task->uuid]), [
        'title' => 'Updated Title',
    ]);

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'uuid',
                'title',
                'description',
                'completed',
                'completed_at',
                'due_at',
                'user',
                'created_at',
                'updated_at',
            ],
        ]);

    $response->assertJsonFragment([
        'title' => 'Updated Title',
    ]);
})->group('task');


test('it can delete task', function () {
    signIn();

    $task = Task::factory()->create();

    $response = deleteJson(route('v1.tasks.destroy', ['task_uuid' => $task->uuid]));

    $response->assertOk();
})->group('task');


test('it can list tasks', function () {
    signIn();

    Task::factory()->count(5)->create();
    $response = getJson(route('v1.tasks.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'uuid',
                    'title',
                    'description',
                    'completed',
                    'completed_at',
                    'due_at',
                    'user',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
})->group('task');


test('it can show task', function () {
    signIn();

    $task = Task::factory()->create();

    $response = getJson(route('v1.tasks.show', ['task_uuid' => $task->uuid]));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'uuid',
                'title',
                'description',
                'completed',
                'completed_at',
                'due_at',
                'user',
                'created_at',
                'updated_at',
            ],
        ]);
})->group('task');
