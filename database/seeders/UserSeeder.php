<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Database\Factories\Helpers\FactoryHelper;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->truncate();
        $users = User::factory(10)->create();

        // seeder for many to many relationship hitting pivot table
        $users->each(
            function (User $user) {
                $user->posts()->sync([FactoryHelper::getRandomModelId(Post::class)]);
            }
        );
    }
}
