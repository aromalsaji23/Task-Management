<?php

namespace App\Services;

use App\Models\Task;
use App\Repositories\Contracts\TaskRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskService
{
    public function __construct(
        protected TaskRepositoryInterface $repository,
        protected AIService $aiService
    ) {}

    /**
     * Get paginated tasks based on filters
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAllTasks(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        return $this->repository->all($filters, $perPage);
    }

    /**
     * Get a specific task
     *
     * @param int $id
     * @return Task
     */
    public function getTask(int $id): Task
    {
        return $this->repository->find($id);
    }

    /**
     * Create a task and trigger AI enrichment
     *
     * @param array $data
     * @return Task
     */
    public function createTask(array $data): Task
    {
        return DB::transaction(function () use ($data) {
            // 1. Create the task via repository
            $task = $this->repository->create($data);

            // 2. Call AI Service to generate summary and priority
            try {
                $aiData = $this->aiService->generateSummary($task);
                
                // 3. Update task with AI data
                $this->repository->update($task->id, [
                    'ai_summary'  => $aiData['ai_summary'],
                    'ai_priority' => $aiData['ai_priority'],
                ]);

                // Reload task to return fresh data
                $task->refresh();
            } catch (\Exception $e) {
                // If AI fails during transaction, log it but let the task creation succeed
                Log::error('AI task generation failed during creation: ' . $e->getMessage());
            }

            return $task;
        });
    }

    /**
     * Update an existing task
     *
     * @param int $id
     * @param array $data
     * @return Task
     */
    public function updateTask(int $id, array $data): Task
    {
        // By default, we do NOT re-trigger AI on every update to save tokens,
        // unless explicitly requested in a more complex setup.
        return $this->repository->update($id, $data);
    }

    /**
     * Delete a task
     *
     * @param int $id
     * @return bool
     */
    public function deleteTask(int $id): bool
    {
        return $this->repository->delete($id);
    }

    /**
     * Update task status only
     *
     * @param int $id
     * @param string $status
     * @return Task
     */
    public function updateStatus(int $id, string $status): Task
    {
        return $this->repository->update($id, ['status' => $status]);
    }
}
