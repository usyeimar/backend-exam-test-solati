<?php

namespace App\Services;

use App\DataTransferObjects\MapperResponseDto;
use App\DataTransferObjects\TaskDto;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Exception;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class TaskService
{
    /**
     * List all tasks
     * @return MapperResponseDto
     */
    public function index(): MapperResponseDto
    {
        // Get all tasks from the database
        $tasks = QueryBuilder::for(Task::class)
            ->allowedFilters([
                'title',
                'completed',
                'due_at',
                AllowedFilter::scope('completed')
            ])
            ->allowedSorts(['title', 'completed', 'due_at'])
            ->allowedIncludes(['user'])
            ->paginate(
                perPage: request()->get('limit', 50),
            );

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Tasks retrieved successfully',
            'data' => TaskResource::collection($tasks)
        ]);
    }

    /**
     * Show a task by UUID
     * @throws Exception
     */
    public function show(string $task_uuid)
    {

        //validamos que el $task_uuid sea un un uuid valido
        $this->isValidUuid($task_uuid, 'The task_uuid is not a valid UUID');
        // Get the task from the database
        $task = Task::query()->where('uuid', $task_uuid)->first();
        if (!$task) {
            throw new Exception(
                'No task found with the given UUID ' . $task_uuid
            );
        }

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Task retrieved successfully',
            'data' => TaskResource::make($task)->resolve()
        ]);
    }

    /**
     * Store a new task
     * @param TaskDto $data
     * @return MapperResponseDto
     */
    public function store(TaskDto $data): MapperResponseDto
    {
        // Store the task in the database
        $user_id = auth()->id();
        $task = Task::query()->create([
            'title' => $data->title(),
            'description' => $data->description(),
            'completed_at' => $data->completedAt(),
            'due_at' => $data->dueAt(),
            'completed' => $data->completed(),
            'user_id' => $user_id
        ]);

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Task created successfully',
            'data' => TaskResource::make($task)->resolve()
        ]);
    }


    /**
     * Update a task by UUID
     * @param string $task_uuid
     * @param TaskDto $data
     * @return MapperResponseDto
     * @throws Exception
     */
    public function update(string $task_uuid, TaskDto $data): MapperResponseDto
    {

        //validamos que el $task_uuid sea un un uuid valido
        $this->isValidUuid($task_uuid, 'The task_uuid is not a valid UUID');

        // Update the task in the database
        $task = Task::query()->where('uuid', $task_uuid)->first();

        if (!$task) {
            throw new Exception(
                'No task found with the given UUID' . $task_uuid
            );
        }

        $task->fill([
            'title' => $data->title(),
            'description' => $data->description() ?? $task->description,
            'completed_at' => $data->completedAt() ?? $task->completed_at,
            'due_at' => $data->dueAt() ?? $task->due_at,
            'completed' => $data->completed() ?? $task->completed,
        ]);

        $task->save();
        $task->refresh();


        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Task updated successfully',
            'data' => TaskResource::make($task)->resolve()
        ]);
    }

    /**
     * Delete a task by UUID
     * @throws Exception
     */
    public function delete(string $task_uuid)
    {
        //validamos que el $task_uuid sea un un uuid valido
        $this->isValidUuid($task_uuid, 'The task_uuid is not a valid UUID');
        // Delete the task from the database
        $task = Task::query()->where('uuid', $task_uuid)->first();

        if (!$task) {
            throw new Exception(
                'No task found with the given UUID' . $task_uuid
            );
        }

        $task->delete();

        return MapperResponseDto::create([
            'success' => true,
            'message' => 'Task deleted successfully',
            'data' => []
        ]);
    }


    /**
     * Valida que el UUID sea valido
     * @throws Exception
     */
    private function isValidUuid(string $uuid, string $message = 'The UUID is not valid.'): void
    {

        if (!preg_match('/^[a-f\d]{8}(-[a-f\d]{4}){4}[a-f\d]{8}$/i', $uuid)) {
            throw new Exception($message);
        }
    }

}
