<?php

namespace App\Http\Controllers\API\Task;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Illuminate\Http\Request;
use Throwable;

class IndexController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(TaskService $service)
    {
        try {
            $tasks = $service->index();
            return $tasks->data();
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
