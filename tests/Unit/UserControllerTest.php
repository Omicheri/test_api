<?php

namespace Tests\Unit;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_index_return_users()
    {
        $user = User::factory(5)->create();

        $response = $this->getJson('/api/user');
        $response->assertStatus(\Symfony\Component\HttpFoundation\Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }

    public function test_show_returns_user()
    {
        $user = User::factory()->create();

        $response = $this->getJson("/api/user/{$user->id}");
        $response->assertStatus(200)
            ->assertJson([
                'id' => $user->id,
                'name' => $user->name,
            ]);
    }

    public function test_store_creates_user()
    {
        $data = [
            'name' => 'Test Name',
            'email' => 'Email@exmaple.com',
            'password' => 'Test Password',
        ];

        $response = $this->postJson('/api/user', $data);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test Name',
                'email' => 'Email@exmaple.com',

            ]);

        $user = User::where('name', 'Test Name')->first();
        $this->assertTrue(Hash::check('Test Password', $user->password));
        $this->assertDatabaseHas('users', [
            'name' => 'Test name',
            'email' => 'Email@exmaple.com',]);
    }
    public function test_update_modifies_user()
    {
        $user = User::factory()->create();

        $data = [
            'name' => 'Updated Name',
            'email' => 'Email@exmaple.com',
            'password' => 'Test Password',
        ];

        $response = $this->putJson("/api/user/{$user->id}", $data);

        $response->assertStatus(200)
            ->assertJson([
                'name' => 'Updated Name',
                'email' => 'Email@exmaple.com',

            ]);
        $this->assertTrue(Hash::check('Test Password', $user->fresh()->password));

        // Vérifiez les autres champs dans la base de données
        $this->assertDatabaseHas('users', [
            'name' => 'Updated Name',
            'email' => 'Email@exmaple.com',
        ]);
    }
    public function test_destroy_deletes_user()
    {
        $user = User::factory()->create();

        $response = $this->deleteJson("/api/user/{$user->id}");

        $response->assertStatus(200)
            ->assertJson(['message' => 'User deleted']);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
