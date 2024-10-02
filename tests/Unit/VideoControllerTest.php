<?php

namespace Tests\Unit;
use App\Models\Video;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;

class VideoControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     */ use RefreshDatabase;

    /**
     * A basic unit test example.
     */
    public function test_index_return_videos()
    {
        $video = Video::factory(5)->create();

        $response = $this->getJson('/api/video');
        $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }

    public function test_show_returns_video()
    {
        $video = Video::factory()->create();

        $response = $this->getJson("/api/video/{$video->id}");
        $response->assertStatus(200)
            ->assertJson([
                'id' => $video->id,
                'title' => $video->title,
                'url' => $video->url,
                'created_at' => $video->created_at->toISOString(),
                'updated_at' => $video->updated_at->toISOString(),
            ]);
    }
    public function test_store_creates_video()
    {
        $data = [
            'title' => 'Test Title',
            'url' => 'Test url',
        ];

        $response = $this->postJson('/api/video', $data);

        $response->assertStatus(201)
            ->assertJson([
                'title' => 'Test Title',
                'url' => 'Test url',
            ]);

        $this->assertDatabaseHas('videos', $data);
    }
    public function test_update_modifies_video()
    {
        $video = Video::factory()->create();

        $data = [
            'title' => 'Updated Title',
            'url' => 'Updated Url',
        ];

        $response = $this->putJson("/api/video/{$video->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'title' => 'Updated Title',
                'url' => 'Updated Url',
            ]);

        $this->assertDatabaseHas('videos', $data);
    }
    public function test_destroy_deletes_video()
    {
        $video = Video::factory()->create();

        $response = $this->deleteJson("/api/video/{$video->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Video deleted successfully']);

        $this->assertDatabaseMissing('videos', ['id' => $video->id]);
    }
    public function test_store_video_comment_creates_comment()
    {
        $video = Video::factory()->create();
        $user = User::factory()->create(); // Assurez-vous d'avoir une factory pour User

        $data = [
            'body' => 'Test Comment',
            'commentable_id' => $video->id,
            'commentable_type' => Video::class,
            'user_id' => $user->id,
        ];

        $response = $this->postJson("/api/videos/{$video->id}/comments", $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'body' => 'Test Comment',

            ]);

        $this->assertDatabaseHas('comments', [
            'body' => 'Test Comment',
            'commentable_id' => $video->id,
            'commentable_type' => Video::class,
            'user_id' => $user->id,
        ]);
    }

}
