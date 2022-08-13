<?php

namespace Tests\Unit;



use App\Exceptions\GeneralJsonException;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use App\Repositories\CommentRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommentRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** 
     *  Create Test case for CommentRepositoryTest
     * 
     * @test 
     * 
     * @return void
     */
    public function testCreate()
    {
        // 1. Define the goal
        // test if create() will actually create a record in the DB

        // 2. Replicate the env / restriction
        $repository = $this->app->make(CommentRepository::class);

        $user = User::factory()->create();
        $post = Post::factory()->create();

        // 3. define the source of truth
        $payload = [
            'body' => ['something'],
            'user_id' => $user->id,
            'post_id' => $post->id,
        ];

        // 4. compare the result
        $result = $repository->create($payload);

        $this->assertSame(
            $payload['body'], 
            $result->body, 
            'Comment created does not have the same body.'
        );
    }

    /** 
     *  Update Test case for CommentRepositoryTest
     * 
     * @test 
     * 
     * @return void
     */
    public function testUpdate()
    {
        // Goal: make sure we can update a comment using the update method

        // env
        $repository = $this->app->make(CommentRepository::class);

        $dummyComment = Comment::factory()->create();

        // source of truth
        $payload = [
            'body' => ['abc123'],
            'post_id' => $dummyComment->post_id,
            'user_id' => $dummyComment->user_id,
        ];

        // compare
        $updated = $repository->update($dummyComment, $payload);
        $this->assertSame(
            $payload['body'], 
            $updated->body, 
            'Comment updated does not have the same body.'
        );
    }

    /** 
     *  Delete Test case for a delete retry CommentRepositoryTest
     * 
     * @test 
     * 
     * @return void
     */
    public function testDeleteWillThrowExceptionWhenDeleteCommentThatDoesNotExist()
    {
        // env
        $repository = $this->app->make(CommentRepository::class);
        $dummy = Comment::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->delete($dummy);
    }

    /** 
     *  Delete Test case for a delete retry CommentRepositoryTest
     * 
     * @test 
     * 
     * @return void
     */
    public function testDelete()
    {
        // Goal: test if forceDelete() is working

        // env
        $repository = $this->app->make(CommentRepository::class);
        $dummy = Comment::factory(1)->create()->first();

        // compare
        $deleted = $repository->delete($dummy);

        // verify if it is deleted
        $found = Comment::query()->find($dummy->id);

        $this->assertSame(null, $found, 'Comment is not deleted');
        
    }
}
