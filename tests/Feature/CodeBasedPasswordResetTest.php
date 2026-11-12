<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeBasedPasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_reset_password_using_name_and_contact_code(): void
    {
        $user = User::factory()->create([
            'name' => 'John Smith',
            'email' => 'john@example.com',
            'contact' => '9876543210',
            'password' => bcrypt('old-password'),
        ]);

        $code = 'JO3210';

        $response = $this->from('/login')->post(route('password.email.by_question'), [
            'email' => $user->email,
            'recovery_code' => $code,
        ]);

        $response->assertRedirect(route('password.reset.by_question.form', ['email' => $user->email]));

        $resetPage = $this->get(route('password.reset.by_question.form', ['email' => $user->email]));
        $resetPage->assertOk();

        $resetResponse = $this->from(route('password.reset.by_question.form', ['email' => $user->email]))
            ->post(route('password.reset.by_question'), [
                'email' => $user->email,
                'password' => 'NewPassword123',
                'password_confirmation' => 'NewPassword123',
            ]);

        $resetResponse->assertRedirect(route('login'));
        $this->assertTrue(password_verify('NewPassword123', $user->fresh()->password));
    }
}
