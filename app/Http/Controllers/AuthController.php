<?php

namespace App\Http\Controllers;

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

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'role'     => 'required|in:client,architect',
        ]);

        $role = Role::firstOrCreate(
            ['slug' => $request->role],
            ['name' => ucfirst($request->role)]
        );

        $user = User::create([
            'name'     => trim($request->name),
            'email'    => strtolower(trim($request->email)),
            'password' => Hash::make($request->password),
            'role_id'  => $role->id,
        ]);

        if ($request->role === 'architect') {
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
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = [
            'email' => strtolower(trim($request->email)),
            'password' => $request->password,
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = User::where('email', $credentials['email'])->first();

            // Migration de compatibilite pour les anciens comptes dont
            // le mot de passe a pu etre enregistre en clair.
            if ($user && $user->password === $request->password) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
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
            'architect' => redirect()->route('architect.dashboard'),
            'client'    => redirect()->route('client.dashboard'),
            'admin'     => redirect()->route('admin.dashboard'),
        };
    }
}
