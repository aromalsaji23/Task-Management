<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskStatusRequest;
use App\Http\Resources\TaskCollection;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use App\Services\TaskService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request): TaskCollection
    {
        Gate::authorize('viewAny', Task::class);

        $filters = $request->only(['status', 'priority', 'assigned_to', 'search']);
        
        // Non-admins can only see their own tasks
        if (!auth()->user()->isAdmin()) {
            $filters['assigned_to'] = auth()->id();
        }

        $tasks = $this->taskService->getAllTasks($filters, 20);

        return new TaskCollection($tasks);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        Gate::authorize('create', Task::class);

        $task = $this->taskService->createTask($request->validated());

        return response()->json([
            'message' => 'Task created successfully',
            'data'    => new TaskResource($task),
        ], 201);
    }

    public function updateStatus(UpdateTaskStatusRequest $request, Task $task): TaskResource
    {
        Gate::authorize('update', $task);

        $updatedTask = $this->taskService->updateStatus($task->id, $request->validated('status'));
        $updatedTask->load('assignedUser');

        return new TaskResource($updatedTask);
    }

    public function aiSummary(Task $task): JsonResponse
    {
        Gate::authorize('view', $task);

        if (!$task->ai_summary) {
            return response()->json(['message' => 'AI Summary not available for this task.'], 404);
        }

        return response()->json([
            'data' => [
                'task_id'     => $task->id,
                'ai_summary'  => $task->ai_summary,
                'ai_priority' => $task->ai_priority?->value,
            ]
        ]);
    }
}
