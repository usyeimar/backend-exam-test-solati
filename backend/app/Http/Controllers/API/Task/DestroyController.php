<?php

namespace App\Http\Controllers\API\Task;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Throwable;

class DestroyController extends Controller
{
    /**
     * Eliminar una tarea por su UUID
     *
     * Este endpoint permite eliminar una tarea por su UUID.
     */
    public function __invoke(string $task_uuid, TaskService $service)
    {
        try {
            $task = $service->delete(task_uuid: $task_uuid);

            return response()->json([
                'success' => $task->success(),
                'message' => $task->message(),
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
