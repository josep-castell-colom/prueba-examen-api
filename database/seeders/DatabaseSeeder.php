<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Comment;
use App\Models\Community;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $josep = User::factory()->create([
            'name' => 'Josep',
            'email' => 'josep@hola.com',
            'password' => Hash::make('hola'),
        ]);

        $user1 = User::factory()->create();
        $user2 = User::factory()->create();
        $user3 = User::factory()->create();

        Community::factory()->hasAttached([$josep, $user1, $user2])->has(Post::factory(3)->has(Comment::factory(2)->for($user1))->for($user2))->create();
        Community::factory()->hasAttached([$josep, $user1, $user3])->has(Post::factory(3)->has(Comment::factory(2)->for($user1))->for($user3))->create();
    }
}
