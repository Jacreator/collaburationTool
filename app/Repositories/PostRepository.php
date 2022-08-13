<?php

namespace App\Repositories;

use Exception;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\GeneralJsonException;
use App\Events\Models\Post\PostCreatedEvent;
use App\Events\Models\Post\PostDeletedEvent;
use App\Events\Models\Post\PostUpdatedEvent;

class PostRepository extends BaseRepository
{
    /**
     * Create a new record.
     * 
     * @param array $data - The data to create a new record with.
     * 
     * @return Model
     */
    public function create(array $data): Model
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
                    event(new PostCreatedEvent($post));
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
     * @return Model
     */
    public function update($post, array $data): Model
    {
        return DB::transaction(
            function () use ($post, $data) {
                $updated = $post->update(
                    [
                        'title' => data_get($data, 'title', 'Untitled'),
                        'body' => data_get($data, 'body', []),
                    ]
                );
                throw_if(
                    !$updated, 
                    new GeneralJsonException('Could not update the post.')
                );

                if ($userId = data_get($data, 'user_ids')) {
                    $post->users()->sync($userId);
                }
                event(new PostUpdatedEvent($post));
                return $post;
            }
        );
    }

    /**
     * Delete a record.
     * 
     * @param Model $model - The id of the record to delete.
     * 
     * @return Model | Exception
     */
    public function delete($model): Model | Exception
    {
        return DB::transaction(
            function () use ($model) {
                $deleted = $model->delete();
                throw_if(
                    !$deleted, 
                    new GeneralJsonException('Could not delete the post.')
                );
                event(new PostDeletedEvent($model));
                return $model;
            }
        );
    }
}
