<?php

namespace Tests\Unit;

use App\Http\Controllers\Api\PostController;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_index_return_posts()
    {
        $post = Post::factory(5)->create();

        $response = $this->getJson('/api/post');
        $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }

    public function test_show_returns_post()
    {
        $post = Post::factory()->create();

        $response = $this->getJson("/api/post/{$post->id}");
        $response->assertStatus(200)
            ->assertJson([
                    'id' => $post->id,
                    'body' => $post->body,

            ]);
    }
    public function test_store_creates_post()
    {
        $data = [
            'title' => 'Test Title',
            'body' => 'Test Body',
        ];

        $response = $this->postJson('/api/post', $data);

        $response->assertStatus(201)
            ->assertJson([
                    'title' => 'Test Title',
                    'body' => 'Test Body',
            ]);

        $this->assertDatabaseHas('posts', $data);
    }
    public function test_update_modifies_post()
    {
        $post = Post::factory()->create();

        $data = [
            'title' => 'Updated Title',
            'body' => 'Updated Body',
        ];

        $response = $this->putJson("/api/post/{$post->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                    'title' => 'Updated Title',
                    'body' => 'Updated Body',
            ]);

        $this->assertDatabaseHas('posts', $data);
    }
    public function test_destroy_deletes_post()
    {
        $post = Post::factory()->create();

        $response = $this->deleteJson("/api/post/{$post->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Post deleted']);

        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }
    public function test_store_post_comment_creates_comment()
    {
        $post = Post::factory()->create();
        $user = User::factory()->create(); // Assurez-vous d'avoir une factory pour User

        $data = [
            'body' => 'Test Comment',
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'user_id' => $user->id,
        ];

        $response = $this->postJson("/api/posts/{$post->id}/comments", $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                    'body' => 'Test Comment',
            ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'Test Comment',
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,
            'user_id' => $user->id,
        ]);
    }

}
