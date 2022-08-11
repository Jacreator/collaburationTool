<?php

namespace App\Repositories;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    /**
     * Create new user
     * 
     * @param array $user - array of user data
     * 
     * @return mixed
     */
    public function create(array $user): array
    {
        return DB::transaction(
            function () use ($user) {
                return User::create(
                    [
                        'name' => data_get($user, 'name', 'Chief'),
                        'email' => data_get($user, 'email'),
                        'password' => bcrypt(data_get($user, 'password'))
                    ]
                );
            }
        );
    }

    /**
     * Update user
     * 
     * @param mixed $user - user object for update
     * @param array $data - data to update for user
     * 
     * @return mixed
     */
    public function update($user, array $data): mixed
    {
        return DB::transaction(
            function () use ($user, $data) {
                return $user->update(
                    [
                        'name' => data_get($data, 'name', $user->name),
                        'email' => data_get($data, 'email', $user->email)
                    ]
                ) ?? throw new Exception('User could not be update');
            }
        );
    }

    /**
     * Delete user
     * 
     * @param Object $user - user object for deletion
     * 
     * @return bool | Exception
     */
    public function delete($user): bool | Exception
    {
        return DB::transaction(
            function () use ($user) {
                return $user->delete() ?? throw new Exception('Could not delete user');
            }
        );
    }
}
