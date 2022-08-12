<?php

namespace App\Repositories;

use Exception;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\PostResource;
use App\Repositories\BaseRepository;
use GuzzleHttp\Psr7\Request;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Response;

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
                throw_if(!$updated, new \Exception('Could not update the post.'));

                if ($userId = data_get($data, 'user_ids')) {
                    $post->users()->sync($userId);
                }

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
                $model->delete();
                throw_if(!$model, new \Exception('Could not delete the post.'));
                return $model;
            }
        );
    }
}
