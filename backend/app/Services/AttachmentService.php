<?php

namespace App\Services;

use App\DataTransferObjects\MapperResponseDto;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use App\Models\Task;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\QueryBuilder\QueryBuilder;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AttachmentService
{
    public function index()
    {
        $attachments = QueryBuilder::for(Attachment::class)
            ->allowedFilters(['display_name', 'mime_type'])
            ->allowedSorts(['id', 'display_name', 'mime_type', 'size'])
            ->paginate();

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Attachments fetched successfully',
            'data' => AttachmentResource::collection($attachments)->resolve(),
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
                message: 'No attachment found with the given UUID ' . $attachment_uuid,
                code: 404
            );
        }

        return response()->download(Storage::path('attachments/' . $attachment->hash_name), $attachment->display_name);
    }

    /**
     * Cargar archivo
     * @param UploadedFile $file
     * @return MapperResponseDto
     * @throws Exception
     */
    public function upload(UploadedFile $file): MapperResponseDto
    {
        $task_uuid = request('task_uuid');

        if ($task_uuid) {
            $task = Task::query()->where('uuid', $task_uuid)->first();
            if (!$task) {
                throw new Exception(
                    message: 'No task found with the given UUID ' . $task_uuid,
                    code: 404
                );
            }
        }

        $file->store('attachments');
        $attachment = Attachment::query()->create([
            'display_name' => $file->getClientOriginalName(),
            'hash_name' => $file->hashName(),
            'path' => Storage::path('attachments/' . $file->hashName()),
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
     * Eliminar archivo
     * @throws Exception
     */
    public function delete(string $attachment_uuid)
    {
        $attachment = Attachment::query()->where('uuid', $attachment_uuid)->first();

        if (!$attachment) {
            throw new Exception(
                message: 'No attachment found with the given UUID ' . $attachment_uuid,
                code: 404
            );
        }

        Storage::delete($attachment->path);
        $attachment->delete();


        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Attachment deleted successfully',
        ]);
    }


}
