<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriorityEnum;
use App\Enums\TaskStatusEnum;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Models\User;
use App\Services\TaskService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function __construct(
        protected TaskService $taskService
    ) {}

    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Task::class);

        $filters = $request->only(['status', 'priority', 'assigned_to', 'search']);
        
        // Non-admins can only see their own tasks
        if (!auth()->user()->isAdmin()) {
            $filters['assigned_to'] = auth()->id();
        }

        $tasks = $this->taskService->getAllTasks($filters, 15);
        $users = User::where('role', 'user')->orderBy('name')->get();

        return view('tasks.index', compact('tasks', 'filters', 'users'));
    }

    public function create(): View
    {
        Gate::authorize('create', Task::class);

        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('tasks.create', compact('users'));
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        Gate::authorize('create', Task::class);

        $this->taskService->createTask($request->validated());

        return redirect()->route('tasks.index')->with('success', 'Task created successfully and AI summary generated.');
    }

    public function show(Task $task): View
    {
        Gate::authorize('view', $task);

        $task->load('assignedUser');
        return view('tasks.show', compact('task'));
    }

    public function edit(Task $task): View
    {
        Gate::authorize('update', $task);

        $users = User::where('role', 'user')->orderBy('name')->get();
        return view('tasks.edit', compact('task', 'users'));
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $this->taskService->updateTask($task->id, $request->validated());

        return redirect()->route('tasks.show', $task)->with('success', 'Task updated successfully.');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $this->taskService->deleteTask($task->id);

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
