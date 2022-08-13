<?php

namespace Tests\Unit;



use App\Exceptions\GeneralJsonException;
use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use RefreshDatabase;

    /** 
     *  Create Test case for UserRepositoryTest
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
        $repository = $this->app->make(UserRepository::class);

        // 3. define the source of truth
        $payload = [
            'name' => 'heyaa',
            'email' => 'abc@example.com',
            'password' => 'secret',
        ];

        // 4. compare the result
        $result = $repository->create($payload);

        $this->assertSame($payload['name'], $result->name, 'User created does not have the same name.');
    }

    /** 
     *  Update Test case for UserRepositoryTest
     * 
     * @test 
     * 
     * @return void
     */
    public function testUpdate()
    {
        // Goal: make sure we can update a user using the update method

        // env
        $repository = $this->app->make(UserRepository::class);

        $dummyUser = User::factory(1)->create()->first();

        // source of truth
        $payload = [
            'name' => 'abc123',
        ];

        // compare
        $updated = $repository->update($dummyUser, $payload);
        $this->assertSame($payload['name'], $updated->name, 'User updated does not have the same name.');
    }

    /** 
     *  Delete Test case for UserRepositoryTest
     * 
     * @test 
     * 
     * @return void
     */
    public function testDeleteWillThrowExceptionWhenDeleteUserThatDoesNotExist()
    {
        // env
        $repository = $this->app->make(UserRepository::class);
        $dummy = User::factory(1)->make()->first();

        $this->expectException(GeneralJsonException::class);
        $deleted = $repository->delete($dummy);
    }

    /** 
     *  Delete Test case for UserRepositoryTest
     * 
     * @test 
     * 
     * @return void
     */
    public function testDelete()
    {
        // Goal: test if forceDelete() is working

        // env
        $repository = $this->app->make(UserRepository::class);
        $dummy = User::factory(1)->create()->first();

        // compare
        $deleted = $repository->delete($dummy);

        // verify if it is deleted
        $found = User::query()->find($dummy->id);

        $this->assertSame(null, $found, 'User is not deleted');
        
    }
}
