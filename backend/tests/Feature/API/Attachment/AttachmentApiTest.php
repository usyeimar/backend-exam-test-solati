<?php

use App\Models\Attachment;
use App\Models\Task;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use function Pest\Laravel\{deleteJson, getJson, postJson};


test('it can upload attachment', function () {
    signIn();

    $task = Task::factory()->create();

    $response = postJson(route('v1.attachments.upload'), [
        'attachment' => UploadedFile::fake()->image('avatar.jpg'),
        'task_uuid' => $task->uuid,
    ]);

    $response->assertStatus(201)
        ->assertJsonStructure([
            'data' => [
                'id',
                'uuid',
                'hash_name',
                'display_name',
                'path',
                'url',
                'mime_type',
                'size',
            ],
        ]);

    $response->assertJsonFragment([
        'display_name' => 'avatar.jpg',
        'mime_type' => 'image/jpeg',
    ]);
})->group('attachment');


test('it can download attachment', function () {
    signIn();

    $image = UploadedFile::fake()->image('avatar.jpg');

    Storage::fake('public');

    $task = Task::factory()->create();
    $attachment = Attachment::factory()->create([
        'display_name' => $image->getClientOriginalName(),
        'hash_name' => $image->hashName(),
        'path' => null,
        'base_64' => base64_encode(file_get_contents($image->getRealPath())), // 'data:image/png;base64,
        'mime_type' => $image->getClientMimeType(),
        'size' => $image->getSize(),
        'task_id' => $task->id,
    ]);

    $response = getJson(route('v1.attachments.download', ['attachment_uuid' => $attachment->uuid]));

    $response->dump()->assertStatus(200);
    $response->assertHeader('Content-Type', $attachment->mime_type);


})->group('attachment');


test('it can not download attachment with invalid uuid', function () {
    signIn();

    $response = getJson(route('v1.attachments.download', ['attachment_uuid' => 'invalid-uuid']));

    $response->assertStatus(400);
})->group('attachment');


test('it can delete attachment', function () {
    signIn();

    $image = UploadedFile::fake()->image('avatar.jpg');

    Storage::fake('public');

    $task = Task::factory()->create();
    $attachment = Attachment::factory()->create([
        'display_name' => $image->getClientOriginalName(),
        'hash_name' => $image->hashName(),
        'path' => $image->store('attachments', 'public'),
        'mime_type' => $image->getClientMimeType(),
        'size' => $image->getSize(),
        'task_id' => $task->id,
    ]);

    $response = deleteJson(route('v1.attachments.delete', ['attachment_uuid' => $attachment->uuid]));
    $response->assertStatus(200);
})->group('attachment');


test('it can not delete attachment with invalid uuid', function () {
    signIn();

    $response = deleteJson(route('v1.attachments.delete', ['attachment_uuid' => 'invalid-uuid']));

    $response->assertStatus(404);
})->group('attachment');


test('it can list all attachments', function () {
    signIn();

    $task = Task::factory()->create();

    Attachment::factory()->count(5)->create([
        'task_id' => $task->id,
    ]);

    $response = getJson(route('v1.attachments.index'));

    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'uuid',
                    'hash_name',
                    'display_name',
                    'path',
                    'url',
                    'mime_type',
                    'size',
                ],
            ],
        ]);
})->group('attachment');
