<?php

namespace Tests\Feature;

use App\Models\User;
use App\Mail\WelcomeUserMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class SendWelcomeEmailTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_sends_welcome_email_to_all_user_addresses(): void
    {
        Mail::fake();

        $user = User::factory()->hasEmails(3)->create();
        $emails = $user->emails()->pluck('email')->toArray();

        $response = $this->postJson("/api/users/{$user->id}/send-welcome");

        $response->assertStatus(200)
            ->assertJson(['message' => 'Emails sent successfully']);

        Mail::assertSent(WelcomeUserMail::class, function ($mail) use ($emails) {
            return $mail->hasTo($emails);
        });
    }

    public function test_it_returns_error_if_user_has_no_emails(): void
    {
        Mail::fake();

        $user = User::factory()->create();

        $response = $this->postJson("/api/users/{$user->id}/send-welcome");

        $response->assertStatus(422)
            ->assertJson(['message' => 'User has no email addresses']);

        Mail::assertNothingSent();
    }

    public function test_it_returns_404_for_non_existent_user(): void
    {
        $response = $this->postJson("/api/users/999/send-welcome");

        $response->assertStatus(404);
    }
}
