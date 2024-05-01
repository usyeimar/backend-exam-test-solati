<?php

namespace App\Http\Controllers\API\Task;

use App\DataTransferObjects\TaskDto;
use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Throwable;

class UpdateController extends Controller
{
    /**
     * Actualizar una tarea por su UUID
     *
     * Este endpoint permite actualizar una tarea.
     */
    public function __invoke(Request $request, $task_uuid, TaskService $service)
    {
        try {
            $task = $service->update(
                task_uuid: $task_uuid,
                data: TaskDto::fromArray($request->all())
            );

            return response()->json([
                'success' => $task->success(),
                'message' => $task->message(),
                'data' => $task->data(),
            ]);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Algo salió mal',
                        'detail' => $e->getMessage(),
                    ],
                ],
            ], 400);
        }
    }
}
