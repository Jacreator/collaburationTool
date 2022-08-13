<?php

namespace Tests\Feature\Api\V1\User;

use App\Events\Models\User\UserCreated;
use App\Events\Models\User\UserCreatedEvent;
use App\Events\Models\User\UserDeleted;
use App\Events\Models\User\UserDeletedEvent;
use App\Events\Models\User\UserUpdated;
use App\Events\Models\User\UserUpdatedEvent;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserApiTest extends TestCase
{
    use RefreshDatabase;

    protected $uri = '/api/v1/users';

    //    public function tearDown(): void
    //    {
    //        parent::tearDown();
    //        dump('heyyaa');
    //    }

    public function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->make();
        $this->actingAs($user);
    }

    /**
     * Index test case for user
     *
     * @return void
     */
    public function testIndex()
    {

        // load data in db
        $users = User::factory(10)->create();
        $userIds = $users->map(fn ($user) => $user->id);

        // call index endpoint
        $response = $this->json('get', $this->uri);

        // assert status
        $response->assertStatus(200);
        // verify records
        $data = $response->json('data');
        collect($data)->each(
            fn ($user) => $this->assertTrue(
                in_array(
                    $user['id'],
                    $userIds->toArray()
                )
            )
        );
    }

    /**
     * Show test case for user
     *
     * @return void
     */
    public function testShow()
    {
        $dummy = User::factory()->create();
        $response = $this->json('get', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200)->json('data');

        $this->assertEquals(
            data_get($result, 'id'),
            $dummy->id,
            'Response ID not the same as model id.'
        );
    }

    /**
     * Create test case for user
     *
     * @return void
     */
    public function testCreate()
    {
        Event::fake();
        $dummy = User::factory()->make();
        $data['name'] = $dummy->name;
        $data['email'] = $dummy->email;
        $data['password'] = 'password';
        $data['password_confirmation'] = 'password';
        $data['email_verified_at'] = $dummy->email_verified_at;
        $response = $this->json('post', $this->uri, $data);

        $result = $response->assertStatus(201)->json('data');
        Event::assertDispatched(UserCreatedEvent::class);
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
     * Update test case for user
     *
     * @return void
     */
    public function testUpdate()
    {
        $dummy = User::factory()->create();
        $dummy2 = User::factory()->make();
        Event::fake();
        $fillables = collect((new User())->getFillable());

        $fillables->each(
            function ($toUpdate) use ($dummy, $dummy2) {
                $response = $this->json(
                    'put',
                    $this->uri . '/' . $dummy->id,
                    [
                        'name' => data_get($dummy2, 'name'),
                        'email' => data_get($dummy2, 'email'),
                    ]
                );

                $result = $response->assertStatus(200)->json('data');
                Event::assertDispatched(UserUpdatedEvent::class);
                $this->assertEquals(
                    data_get($dummy2, $toUpdate),
                    data_get($dummy->refresh(), $toUpdate),
                    'Failed to update model.'
                );
            }
        );
    }

    /**
     * Delete test case for user
     *
     * @return void
     */
    public function testDelete()
    {
        Event::fake();
        $dummy = User::factory()->create();

        $response = $this->json('delete', $this->uri . '/' . $dummy->id);

        $result = $response->assertStatus(200);
        Event::assertDispatched(UserDeletedEvent::class);
        $this->expectException(ModelNotFoundException::class);
        User::query()->findOrFail($dummy->id);
    }
}
