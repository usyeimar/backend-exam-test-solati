<?php

namespace App\Http\Controllers\API\Task;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Throwable;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(StoreTaskRequest $request, TaskService $service)
    {
        try {
            $task = $service->store($request->dto());
            return response()->json([
                'success' => $task->success(),
                'message' => $task->message(),
                'data' => $task->data()
            ], 201);
        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'errors' => [
                    [
                        'title' => 'Algo salió mal',
                        'detail' => $e->getMessage()
                    ]
                ]
            ], 400);
        }
    }
}
