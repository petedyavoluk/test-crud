<?php
namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_users_list(): void
    {
        User::factory()->count(3)->hasEmails(2)->create();

        $response = $this->getJson('/api/users');

        $response->assertStatus(200)
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'first_name', 'last_name', 'full_name', 'phone_number', 'emails']
                ]
            ]);
    }

    public function test_can_create_user_with_emails(): void
    {
        $userData = [
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'phone_number' => '123456789',
            'emails' => ['jan@example.com', 'work@example.com']
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(201)
            ->assertJsonPath('data.first_name', 'Jan')
            ->assertJsonCount(2, 'data.emails');

        $this->assertDatabaseHas('users', [
            'first_name' => 'Jan',
            'last_name' => 'Kowalski'
        ]);

        $this->assertDatabaseHas('user_emails', ['email' => 'jan@example.com']);
        $this->assertDatabaseHas('user_emails', ['email' => 'work@example.com']);
    }

    public function test_cannot_create_user_without_emails(): void
    {
        $userData = [
            'first_name' => 'Jan',
            'last_name' => 'Kowalski',
            'phone_number' => '123456789',
            'emails' => []
        ];

        $response = $this->postJson('/api/users', $userData);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['emails']);
    }
    public function test_can_show_user(): void
    {
        $user = User::factory()->hasEmails(1)->create();

        $this->getJson("/api/users/{$user->id}")
            ->assertStatus(200)
            ->assertJsonPath('data.id', $user->id);
    }

    public function test_can_update_user(): void
    {
        $user = User::factory()->hasEmails(1)->create();

        $updateData = [
            'first_name' => 'UpdatedName',
            'emails' => ['new@example.com']
        ];

        $this->putJson("/api/users/{$user->id}", $updateData)
            ->assertStatus(200)
            ->assertJsonPath('data.first_name', 'UpdatedName')
            ->assertJsonPath('data.emails.0', 'new@example.com');
    }

    public function test_can_delete_user(): void
    {
        $user = User::factory()->create();

        $this->deleteJson("/api/users/{$user->id}")
            ->assertStatus(204);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }
}
