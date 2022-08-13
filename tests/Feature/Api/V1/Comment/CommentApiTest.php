<?php

namespace Tests\Feature\Api\V1\Comment;

use Tests\TestCase;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Testing\WithFaker;
use App\Events\Models\Comment\CommentCreated;
use App\Events\Models\Comment\CommentCreatedEvent;
use App\Events\Models\Comment\CommentDeleted;
use App\Events\Models\Comment\CommentDeletedEvent;
use App\Events\Models\Comment\CommentUpdated;
use App\Events\Models\Comment\CommentUpdatedEvent;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Response;


class CommentApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/comments';

    /**
     * Set up tests
     * 
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    /**
     * Index test case for comment
     * 
     * @return void
     */
    public function testIndex()
    {
        // load data in db
        $comments = Comment::factory(10)->create();
        $commentIds = $comments->map(fn ($comment) => $comment->id);

        // call index endpoint
        $response = $this->json('get', $this->uri);

        // assert status
        $response->assertStatus(200);
        // verify records
        $data = $response->json('data');
        collect($data)->each(
            fn ($comment) => $this->assertTrue(
                in_array(
                    $comment['id'],
                    $commentIds->toArray()
                )
            )
        );
    }

    /**
     * Show test case for comment
     * 
     * @return void
     */
    public function testShow()
    {
        $dummy = Comment::factory()->create();
        $response = $this->json('get', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals(
            data_get($result, 'id'),
            $dummy->id,
            'Response ID not the same as model id.'
        );
    }


    /**
     * Test Create method for comment
     * 
     * @return void
     */
    public function testCreate()
    {
        Event::fake();
        $dummy = Comment::factory()->make();

        $response = $this->json('post', $this->uri, $dummy->toArray());

        $result = $response->assertStatus(Response::HTTP_CREATED)->json('data');
        Event::assertDispatched(CommentCreatedEvent::class);
        $result = collect($result)->only(array_keys($dummy->getAttributes()));

        $result->each(
            function ($value, $field) use ($dummy) {
                $this->assertSame(
                    data_get($dummy, $field),
                    $value,
                    'Fillable is not the same.'
                );
            }
        );
    }

    /**
     * Test Update method for comment
     * 
     * @return void
     */
    public function testUpdate()
    {
        $dummy = Comment::factory()->create();
        $dummy2 = Comment::factory()->make();
        Event::fake();
        $fillables = collect((new Comment())->getFillable());

        $fillables->each(
            function ($toUpdate) use ($dummy, $dummy2) {

                $response = $this->json(
                    'put',
                    $this->uri . '/' . $dummy->id,
                    [
                        'body' => data_get($dummy2, 'body'),
                        'user_id' => $dummy2->user_id,
                        'post_id' => $dummy2->post_id,
                    ]
                );

                $result = $response->assertStatus(200)->json('data');
                Event::assertDispatched(CommentUpdatedEvent::class);
                $this->assertEquals(
                    data_get($dummy2, $toUpdate),
                    data_get($dummy->refresh(), $toUpdate),
                    'Failed to update model.'
                );
            }
        );
    }

    /**
     * Test Delete method for comment
     * 
     * @return void
     */
    public function testDelete()
    {
        Event::fake();
        $dummy = Comment::factory()->create();

        $response = $this->json('delete', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(Response::HTTP_OK);
        Event::assertDispatched(CommentDeletedEvent::class);
        $this->expectException(ModelNotFoundException::class);
        Comment::query()->findOrFail($dummy->id);
    }
}
