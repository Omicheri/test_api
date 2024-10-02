<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return ['body' => $this->faker->paragraph,
        ];
    }
    public function forPost()
    {
        return $this->state([
            'commentable_id' => function () {
                return Post::factory()->create()->id;
            },
            'commentable_type' => 'App\Models\Post',
        ]);
    }

    public function forVideo()
    {
        return $this->state([
            'commentable_id' => function () {
                return Video::factory()->create()->id;
            },
            'commentable_type' => 'App\Models\Video',
        ]);
    }
}
