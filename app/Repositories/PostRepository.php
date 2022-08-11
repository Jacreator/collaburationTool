<?php

namespace App\Repositories;

use App\Models\Post;
use Exception;
use Illuminate\Support\Facades\DB;

class PostRepository extends BaseRepository
{
    /**
     * Create a new record.
     * 
     * @param array $data - The data to create a new record with.
     * 
     * @return Model
     */
    public function create(array $data)
    {
        return
        DB::transaction(
            function () use ($data) {
                $post = Post::query()->create(
                    [
                        'title' => data_get($data, 'title', 'Untitled'),
                        'body' => data_get($data, 'body', []),
                    ]
                );

                if ($userId = data_get($data, 'user_ids')) {
                    $post->users()->sync($userId);
                }

                return $post;
            }
        );
    }
    
    /**
     * Update a record.
     * 
     * @param Post  $post - The id of the record to update.
     * @param array $data - The data to update a record with.
     * 
     * @return mixed
     */
    public function update($post, array $data)
    {
        return DB::transaction(
            function () use ($post, $data) {
                $updated = $post->update(
                    [
                        'title' => data_get($data, 'title', 'Untitled'),
                        'body' => data_get($data, 'body', []),
                    ]
                );

                if (!$updated) {
                    throw new \Exception('Could not update the post.');
                }
                
                if ($userId = data_get($data, 'user_ids')) {
                    $post->users()->sync($userId);
                }
                
                return $updated;
            }
        );
    }
    
    /**
     * Delete a record.
     * 
     * @param Model $model - The id of the record to delete.
     * 
     * @return bool | Exception
     */
    public function delete($model): bool | Exception
    {
        return DB::transaction(
            function () use ($model) {
                return $model->delete() ??
                throw new \Exception('Could not delete the post.');;
            }
        );
    }
}