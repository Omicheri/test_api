<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Video;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $posts = Post::factory(10)->create();
        $user = User::factory()->create([
            'name' => 'Omer Akay',
            'email' => 'omer@example.com',
            'password' => Hash::make('omer'),]);

        $videos = Video::factory(10)->create();

        foreach ($posts as $post) {
            Comment::factory()->for($post, 'commentable')->create([
                'user_id' => $user->id,
            ]);
        }

        foreach ($videos as $video) {
            Comment::factory()->for($video, 'commentable')->create([
                'user_id' => $user->id,
            ]);
        }
    }}
