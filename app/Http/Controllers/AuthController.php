<?php

namespace App\Http\Controllers;

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

        if ($validated['role'] === 'architect') {
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

        return $this->redirectByRole();
        // return redirect()->route('architect.');

    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $credentials = [
            'email' => strtolower(trim($validated['email'])),
            'password' => $validated['password'],
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = User::where('email', $credentials['email'])->first();

            // Migration de compatibilite pour les anciens comptes dont
            // le mot de passe a pu etre enregistre en clair.
            if ($user && $user->password === $validated['password']) {
                $user->forceFill([
                    'password' => Hash::make($validated['password']),
                ])->save();

                Auth::login($user, $request->boolean('remember'));
            } else {
                return back()->withErrors([
                    'email' => 'Email ou mot de passe incorrect.',
                ]);
            }
        }

        $request->session()->regenerate();

        return $this->redirectByRole();

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole()
    {
        return match(auth()->user()->role->slug) {
            'architect' => redirect()->route('architect.profile.show'),
            'client'    => redirect()->route('client.dashboard'),
            'admin'     => redirect()->route('admin.dashboard'),
        };
    }
}
