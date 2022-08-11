<?php

namespace App\Repositories;

use Exception;

abstract class BaseRepository
{
    /**
     * Create a new record.
     * 
     * @param array $data - The data to create a new record with.
     * 
     * @return Model
     */
    abstract public function create(array $data): array;
    
    /**
     * Update a record.
     * 
     * @param int   $model - The id of the record to update.
     * @param array $data  - The data to update a record with.
     * 
     * @return mixed
     */
    abstract public function update($model, array $data): mixed;
    
    /**
     * Delete a record.
     * 
     * @param Model $model - The id of the record to delete.
     * 
     * @return bool | Exception 
     */
    abstract public function delete($model): bool | Exception;
}