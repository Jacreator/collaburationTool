<?php

namespace App\Repositories;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Events\Models\User\UserCreated;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\GeneralJsonException;
use App\Events\Models\User\UserCreatedEvent;
use App\Events\Models\User\UserDeletedEvent;
use App\Events\Models\User\UserUpdatedEvent;

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
                $created = User::create(
                    [
                        'name' => data_get($user, 'name', 'Chief'),
                        'email' => data_get($user, 'email'),
                        'password' => bcrypt(data_get($user, 'password'))
                    ]
                );
                throw_if(
                    !$created,
                    new GeneralJsonException('User could not be created')
                );
                event(new UserCreatedEvent($created));
                return $created;
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
                event(new UserUpdatedEvent($user));
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
                $deleted = $user->delete();
                throw_if(
                    !$deleted, 
                    new GeneralJsonException('User could not be deleted')
                );
                event(new UserDeletedEvent($user));
                return $user;
            }
        );
    }
}
