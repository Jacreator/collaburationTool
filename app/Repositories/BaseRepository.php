<?php

namespace App\Repositories;

use Exception;
use Illuminate\Database\Eloquent\Model;


abstract class BaseRepository
{
    /**
     * Create a new record.
     * 
     * @param array $data - The data to create a new record with.
     * 
     * @return mixed | Model
     */
    abstract public function create(array $data): array | Model;
    
    /**
     * Update a record.
     * 
     * @param int   $model - The id of the record to update.
     * @param array $data  - The data to update a record with.
     * 
     * @return Model
     */
    abstract public function update($model, array $data): Model;
    
    /**
     * Delete a record.
     * 
     * @param Model $model - The id of the record to delete.
     * 
     * @return Model | Exception 
     */
    abstract public function delete($model): Model | Exception;
}