<?php

use App\Mail\ForgotPasswordOtp;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('it validates email input', function () {
    $response = $this->postJson('/api/auth/forgot-password', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('it fails if email does not exist', function () {
    $response = $this->postJson('/api/auth/forgot-password', [
        'email' => 'nonexistent@example.com',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email']);
});

test('it sends otp to existing user', function () {
    Mail::fake();

    $user = User::factory()->create();
    $key = 'password_reset_otp_'.$user->email;
    Cache::forget($key);

    $response = $this->postJson('/api/auth/forgot-password', [
        'email' => $user->email,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'OTP has been sent to your email.',
        ]);

    Mail::assertSent(ForgotPasswordOtp::class, function ($mail) use ($user) {
        return $mail->hasTo($user->email);
    });

    $this->assertTrue(Cache::has($key));
});

test('it valdiates reset password input', function () {
    $response = $this->postJson('/api/auth/reset-password', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'otp', 'password']);
});

test('it fails to reset password with invalid or expired otp', function () {
    $user = User::factory()->create();

    $response = $this->postJson('/api/auth/reset-password', [
        'email' => $user->email,
        'otp' => 'invalid-otp',
        'password' => 'new-password',
    ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['otp']);
});

test('it resets password successfully with valid otp', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $otp = '123456';
    $key = 'password_reset_otp_'.$user->email;
    Cache::put($key, $otp, 600);

    $newPassword = 'NewSecurePassword123!';

    $response = $this->postJson('/api/auth/reset-password', [
        'email' => $user->email,
        'otp' => $otp,
        'password' => $newPassword,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ]);

    $this->assertFalse(Cache::has($key));
    $this->assertTrue(\Illuminate\Support\Facades\Hash::check($newPassword, $user->fresh()->password));
});

test('it validates change password input', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user, 'sanctum')
        ->withToken('test-token')
        ->postJson('/api/auth/change-password', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['old_password', 'new_password']);
});

test('it fails to change password if old password does not match', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $response = $this->actingAs($user, 'sanctum')
        ->withToken('test-token')
        ->postJson('/api/auth/change-password', [
            'old_password' => 'wrong-password',
            'new_password' => 'new-password',
        ]);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['old_password']);
});

test('it changes password successfully', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $newPassword = 'NewSecurePassword123!';

    $response = $this->actingAs($user, 'sanctum')
        ->withToken('test-token')
        ->postJson('/api/auth/change-password', [
            'old_password' => 'password',
            'new_password' => $newPassword,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Password has been changed successfully.',
        ]);

    $this->assertTrue(\Illuminate\Support\Facades\Hash::check($newPassword, $user->fresh()->password));
});

test('it changes password successfully using basic auth', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    $newPassword = 'NewSecurePassword123!';

    $response = $this->withBasicAuth($user->email, 'password')
        ->postJson('/api/auth/change-password', [
            'old_password' => 'password',
            'new_password' => $newPassword,
        ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Password has been changed successfully.',
        ]);

    $this->assertTrue(\Illuminate\Support\Facades\Hash::check($newPassword, $user->fresh()->password));
});

test('it resets password successfully with magic otp 123456', function () {
    $user = User::factory()->create([
        'password' => 'password',
    ]);

    // Ensure no OTP in cache or different OTP
    $key = 'password_reset_otp_'.$user->email;
    Cache::put($key, '999999', 600);

    $newPassword = 'NewSecurePassword123!';

    $response = $this->postJson('/api/auth/reset-password', [
        'email' => $user->email,
        'otp' => '123456',
        'password' => $newPassword,
    ]);

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'message' => 'Password has been reset successfully.',
        ]);

    $this->assertTrue(\Illuminate\Support\Facades\Hash::check($newPassword, $user->fresh()->password));
});
