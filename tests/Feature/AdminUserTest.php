<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminUserTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_create_admin_form(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/users/create');

        $response->assertOk();
        $response->assertSee('Create admin account');
    }

    public function test_non_admin_cannot_view_create_admin_form(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get('/admin/users/create');

        $response->assertForbidden();
    }

    public function test_admin_can_create_another_admin(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'New Admin',
            'email'                 => 'newadmin@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertRedirect(route('admin.dashboard'));
        $this->assertDatabaseHas('users', [
            'name'     => 'New Admin',
            'email'    => 'newadmin@example.com',
            'is_admin' => true,
        ]);

        $newAdmin = User::where('email', 'newadmin@example.com')->first();
        $this->assertTrue(Hash::check('Password123!', $newAdmin->password));
    }

    public function test_non_admin_cannot_create_admin(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->post('/admin/users', [
            'name'                  => 'Hacker',
            'email'                 => 'hacker@example.com',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ]);

        $response->assertForbidden();
        $this->assertDatabaseMissing('users', ['email' => 'hacker@example.com']);
    }

    public function test_create_admin_requires_valid_email_and_password(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->post('/admin/users', [
            'name'                  => 'Bad Admin',
            'email'                 => 'not-an-email',
            'password'              => 'short',
            'password_confirmation' => 'different',
        ]);

        $response->assertSessionHasErrors(['email', 'password']);
    }
}
