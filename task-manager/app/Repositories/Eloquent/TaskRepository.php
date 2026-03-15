<?php

namespace App\Repositories\Eloquent;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class TaskRepository implements TaskRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Task::with('assignedUser');

        if (!empty($filters['status'])) {
            $query->byStatus($filters['status']);
        }

        if (!empty($filters['priority'])) {
            $query->byPriority($filters['priority']);
        }

        if (!empty($filters['assigned_to'])) {
            $query->byAssignee($filters['assigned_to']);
        }

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['due_date'])) {
            $query->dueBefore($filters['due_date']);
        }

        $query->orderBy('due_date', 'asc')->orderBy('id', 'desc');

        return $query->paginate($perPage);
    }

    public function find(int $id): Task
    {
        return Task::with('assignedUser')->findOrFail($id);
    }

    public function create(array $data): Task
    {
        return Task::create($data);
    }

    public function update(int $id, array $data): Task
    {
        $task = $this->find($id);
        $task->update($data);
        return $task;
    }

    public function delete(int $id): bool
    {
        $task = $this->find($id);
        return $task->delete();
    }
}
