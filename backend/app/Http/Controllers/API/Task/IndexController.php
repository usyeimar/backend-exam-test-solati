<?php

namespace App\Http\Controllers\API\Task;

use App\Http\Controllers\Controller;
use App\Services\TaskService;
use Throwable;

class IndexController extends Controller
{
    /**
     * Listar todas las tareas.
     *
     * Este endpoint permite listar todas las tareas.
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
                        'detail' => $e->getMessage(),
                    ],
                ],
            ], 400);
        }
    }
}
