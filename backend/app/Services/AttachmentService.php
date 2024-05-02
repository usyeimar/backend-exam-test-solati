<?php

namespace App\Services;

use App\DataTransferObjects\MapperResponseDto;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use App\Models\Task;
use Exception;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentService
{
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => [
                [
                    'id' => 1,
                    'name' => 'Archivo 1',
                    'url' => 'https://www.google.com',
                ],
                [
                    'id' => 2,
                    'name' => 'Archivo 2',
                    'url' => 'https://www.google.com',
                ],
                [
                    'id' => 3,
                    'name' => 'Archivo 3',
                    'url' => 'https://www.google.com',
                ],
            ],
        ]);
    }


    public function store(array $data)
    {
        return response()->json([
            'success' => true,
            'message' => 'Archivo subido exitosamente.',
            'data' => [
                'id' => 1,
                'name' => 'Archivo 1',
                'url' => 'https://www.google.com',
            ],
        ], 201);
    }


    /**
     * @throws Exception
     */
    public function download(string $attachment_uuid): BinaryFileResponse
    {
        $attachment = Attachment::query()->where('uuid', $attachment_uuid)->first();

        if (!$attachment) {
            throw new Exception(
                message: 'No attachment found with the given UUID ' . $attachment_uuid
            );
        }

        return response()->download(
            file: Storage::disk('public')->path($attachment->path),
            name: $attachment->display_name,
            headers: [
                'Content-Type' => $attachment->mime_type,
            ]
        );
    }

    /**
     * @throws Exception
     */
    public function upload(?UploadedFile $file)
    {
        $task_uuid = request('task_uuid');

        if ($task_uuid) {
            $task = Task::query()->where('uuid', $task_uuid)->first();
            if (!$task) {
                throw new Exception(
                    message: 'No task found with the given UUID ' . $task_uuid
                );
            }
        }

        $attachment = Attachment::query()->create([
            'display_name' => $file->getClientOriginalName(),
            'hash_name' => $file->hashName(),
            'path' => $file->store('attachments'),
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
            'task_id' => $task->id ?? null,
            'user_id' => auth()->id(),
        ]);

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'File uploaded successfully',
            'data' => AttachmentResource::make($attachment)->resolve(),
        ]);
    }

    /**
     * @throws Exception
     */
    public function delete(string $attachment_uuid)
    {
        $attachment = Attachment::query()->where('uuid', $attachment_uuid)->first();

        if (!$attachment) {
            throw new Exception(
                message: 'No attachment found with the given UUID ' . $attachment_uuid
            );
        }

        Storage::disk('public')->delete($attachment->path);
        $attachment->delete();


        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Attachment deleted successfully',
        ]);
    }


}
