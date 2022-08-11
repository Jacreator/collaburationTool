<?php

namespace App\Repositories;

use App\Models\Comment;
use Exception;
use Illuminate\Support\Facades\DB;

class CommentRepository
{
    /**
     * Create comment
     * 
     * @param array $comment - an array of comment to story
     * 
     * @return mixed
     */
    public function create(array $comment)
    {
        return DB::transaction(
            function () use ($comment) {
                return Comment::create(
                    [
                        'body' => data_get($comment, 'body', []),
                        'user_id' => data_get($comment, 'user_id'),
                        'post_id' => data_get($comment, 'post_id')
                    ]
                );
            }
        );
    }

    /**
     * Update a comment
     * 
     * @param mixed $comment - the id of the comment
     * @param array $data    - the comment payload for update
     * 
     * @return mixed
     */
    public function update($comment, array $data)
    {

        return DB::transaction(
            function () use ($comment, $data) {
                return $comment->update(
                    [
                        'body' => data_get($data, 'body'),
                        'user_id' => data_get($data, 'user_id'),
                        'post_id' => data_get($data, 'post_id')
                    ]
                );
            }
        );
    }

    /**
     * Delete a comment
     * 
     * @param Comment $comment - an object of comment 
     * 
     * @return bool | Exception
     */
    public function delete($comment): bool | Exception
    {
        return DB::transaction(
            function () use ($comment) {
                return $comment->delete() ?? throw new Exception(
                    'Could not delete Comment'
                );
            }
        );
    }
}