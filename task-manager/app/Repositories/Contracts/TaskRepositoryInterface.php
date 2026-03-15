<?php

namespace App\Repositories\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use App\Models\Task;

interface TaskRepositoryInterface
{
    /**
     * Get all tasks with optional filters and pagination.
     *
     * @param array $filters
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function all(array $filters = [], int $perPage = 15): LengthAwarePaginator;

    /**
     * Find a task by its ID.
     *
     * @param int $id
     * @return Task
     */
    public function find(int $id): Task;

    /**
     * Create a new task.
     *
     * @param array $data
     * @return Task
     */
    public function create(array $data): Task;

    /**
     * Update an existing task.
     *
     * @param int $id
     * @param array $data
     * @return Task
     */
    public function update(int $id, array $data): Task;

    /**
     * Delete a task.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;
}
