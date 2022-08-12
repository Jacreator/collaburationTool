<?php

namespace App\Repositories;

use App\Exceptions\GeneralJsonException;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class UserRepository extends BaseRepository
{
    /**
     * Create new user
     * 
     * @param array $user - array of user data
     * 
     * @return mixed | Model
     */
    public function create(array $user): array | Model
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
     * @return Model
     */
    public function update($user, array $data): Model
    {
        return DB::transaction(
            function () use ($user, $data) {
                $user->update(
                    [
                        'name' => data_get($data, 'name', $user->name),
                        'email' => data_get($data, 'email', $user->email)
                    ]
                );

                throw_if(
                    !$user,
                    new GeneralJsonException('User could not be update')
                );

                return $user;
            }
        );
    }

    /**
     * Delete user
     * 
     * @param Object $user - user object for deletion
     * 
     * @return Model | Exception
     */
    public function delete($user): Model | Exception
    {
        return DB::transaction(
            function () use ($user) {
                $user->delete();
                throw_if(!$user, new GeneralJsonException('User could not be deleted'));
                return $user;
            }
        );
    }
}
