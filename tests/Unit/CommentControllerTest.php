<?php

namespace Tests\Unit;

use http\Env\Response;
use Tests\TestCase;
use App\Models\User;
use App\Models\Post;
use App\Models\Video;
use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_comments()
    {   $user = User::factory()->createOne();
        $post = Post::factory()->createOne();
        $comments = Comment::factory()->count(5)->for($user,"user")->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,]);

        $response = $this->getJson('/api/comment');

        $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }

    public function test_show_returns_comment()
    {
        $user = User::factory()->createOne();
        $post = Post::factory()->createOne();
        $comment = Comment::factory()->for($user, "user")->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,]);

        $response = $this->getJson("/api/comment/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson([
                'id' => $comment->id,
                'body' => $comment->body,


            ]);
    }

    public function test_update_updates_comment()
    {
        $user = User::factory()->createOne();
        $post = Post::factory()->createOne();
        $comment = Comment::factory()->for($user, 'user')->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,]);
        $data = ['body' => 'Updated comment body'];

        $response = $this->putJson("/api/comment/{$comment->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'id' => $comment->id,
                'body' => 'Updated comment body',
            ]);
        $this->assertDatabaseHas('comments', $data);
    }

    public function test_destroy_deletes_comment()
    {
        $post = Post::factory()->createOne();
        $user = User::factory()->createOne();
        $comment = Comment::factory()->for($user, 'user')->create([
            'commentable_id' => $post->id,
            'commentable_type' => Post::class,]);

        $response = $this->deleteJson("/api/comment/{$comment->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Comment deleted']);

        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
