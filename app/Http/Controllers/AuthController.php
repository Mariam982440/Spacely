<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\ArchitectProfile;
use App\Models\ClientProfile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $validated = $request->validated();

        $role = Role::firstOrCreate(
            ['slug' => $validated['role']],
            ['name' => ucfirst($validated['role'])]
        );

        $user = User::create([
            'name'     => trim($validated['name']),
            'email'    => strtolower(trim($validated['email'])),
            'password' => Hash::make($validated['password']),
            'role_id'  => $role->id,
        ]);

        if ($validated['role'] === UserRole::Architect->value) {
            ArchitectProfile::create([
                'user_id'          => $user->id,
                'city'             => '',
                'experience_years' => 0,
            ]);
        } else {
            ClientProfile::create([
                'user_id' => $user->id,
            ]);
        }

        Auth::login($user);

        return redirect($this->dashboardUrl($user));
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $credentials = [
            'email'    => strtolower(trim($request->email)),
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        $request->session()->regenerate();

        return redirect()->intended($this->dashboardUrl(auth()->user()));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function dashboardUrl(User $user): string
    {
        return route($user->dashboardRouteName());
    }
}
