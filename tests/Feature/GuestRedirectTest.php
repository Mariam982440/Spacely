<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestRedirectTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_visiting_login_goes_to_role_dashboard(): void
    {
        $role = Role::create([
            'name' => 'Client',
            'slug' => 'client',
        ]);

        $user = User::create([
            'name' => 'Client User',
            'email' => 'client@example.com',
            'password' => 'password',
            'role_id' => $role->id,
        ]);

        $this->actingAs($user)
            ->get(route('login'))
            ->assertRedirect(route('client.dashboard'));
    }
}
