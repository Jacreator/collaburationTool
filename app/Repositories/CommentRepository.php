<?php

namespace App\Repositories;

use Exception;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\GeneralJsonException;
use App\Events\Models\Comment\CommentCreatedEvent;
use App\Events\Models\Comment\CommentDeletedEvent;
use App\Events\Models\Comment\CommentUpdatedEvent;

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
                $created = Comment::create(
                    [
                        'body' => data_get($comment, 'body', []),
                        'user_id' => data_get($comment, 'user_id'),
                        'post_id' => data_get($comment, 'post_id')
                    ]
                );
                throw_if(
                    !$created, 
                    new GeneralJsonException('Comment could not be created')
                );

                event(new CommentCreatedEvent($created));
                return $created;
            }
        );
    }

    /**
     * Update a comment
     * 
     * @param mixed $comment - the id of the comment
     * @param array $data    - the comment payload for update
     * 
     * @return Model
     */
    public function update($comment, array $data): Model
    {

        return DB::transaction(
            function () use ($comment, $data) {
                $updated = $comment->update(
                    [
                        'body' => data_get($data, 'body'),
                        'user_id' => data_get($data, 'user_id'),
                        'post_id' => data_get($data, 'post_id')
                    ]
                );

                throw_if(
                    !$updated,
                    new GeneralJsonException('Comment could not be update')
                );
                event(new CommentUpdatedEvent($comment));
                return $comment;
            }
        );
    }

    /**
     * Delete a comment
     * 
     * @param Comment $comment - an object of comment 
     * 
     * @return Model | Exception
     */
    public function delete($comment): Model | Exception
    {
        return DB::transaction(
            function () use ($comment) {
                $deleted = $comment->delete();
                throw_if(
                    !$deleted,
                    new GeneralJsonException('Could not delete Comment')
                );
                event(new CommentDeletedEvent($comment));
                return $comment;
            }
        );
    }
}
