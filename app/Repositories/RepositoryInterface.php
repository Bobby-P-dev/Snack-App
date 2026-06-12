<?php

namespace App\Repositories;

interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function all();

    /**
     * Get record by ID
     */
    public function find($id);

    /**
     * Create new record
     */
    public function create(array $data);

    /**
     * Update record
     */
    public function update($id, array $data);

    /**
     * Delete record
     */
    public function delete($id);

    /**
     * Get paginated records
     */
    public function paginate($perPage = 15);
}
