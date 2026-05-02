<?php

namespace Tests\Feature;

use App\Models\ClientProfile;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_then_log_in(): void
    {
        Role::create([
            'name' => 'Client',
            'slug' => 'client',
        ]);

        $this->post('/register', [
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client',
        ])->assertRedirect();

        auth()->logout();

        $this->post('/login', [
            'email' => 'jean@example.com',
            'password' => 'password123',
        ])->assertRedirect();

        $this->assertAuthenticated();
    }

    public function test_user_can_register_even_if_roles_are_missing(): void
    {
        $this->assertDatabaseCount('roles', 0);

        $response = $this->post('/register', [
            'name' => 'Nadia',
            'email' => 'nadia@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'client',
        ]);

        $response->assertRedirect(route('client.dashboard'));
        $this->assertDatabaseHas('roles', ['slug' => 'client']);
        $this->assertDatabaseHas('users', ['email' => 'nadia@example.com']);
        $this->assertDatabaseHas('client_profiles', ['user_id' => User::where('email', 'nadia@example.com')->value('id')]);
    }

    public function test_login_upgrades_legacy_plain_text_passwords(): void
    {
        $role = Role::create([
            'name' => 'Client',
            'slug' => 'client',
        ]);

        $user = User::create([
            'name' => 'Legacy User',
            'email' => 'legacy@example.com',
            'password' => Hash::make('temp-password'),
            'role_id' => $role->id,
        ]);

        $user->forceFill([
            'password' => 'legacy-pass',
        ])->saveQuietly();

        ClientProfile::create([
            'user_id' => $user->id,
        ]);

        $this->post('/login', [
            'email' => 'legacy@example.com',
            'password' => 'legacy-pass',
        ])->assertRedirect(route('client.dashboard'));

        $user->refresh();

        $this->assertAuthenticatedAs($user);
        $this->assertTrue(Hash::check('legacy-pass', $user->password));
        $this->assertNotSame('legacy-pass', $user->password);
    }
}
