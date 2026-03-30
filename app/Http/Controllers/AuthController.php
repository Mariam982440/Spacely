<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\ArchitectProfile;
use App\Models\ClientProfile;
use Illuminate\Support\Facades\Auth;

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

        $role = Role::where('slug', $request->role)->firstOrFail();

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => $request->password,
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

        if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return back()->withErrors([
                'email' => 'Email ou mot de passe incorrect.',
            ]);
        }

        $request->session()->regenerate();

        return $this->redirectByRole();
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
