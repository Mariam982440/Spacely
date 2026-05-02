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

        return redirect($this->dashboardByRole($user));
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        $credentials = [
            'email'    => strtolower(trim($validated['email'])),
            'password' => $validated['password'],
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {

            // Compatibilité anciens comptes mot de passe en clair
            $user = User::where('email', $credentials['email'])->first();

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

        // redirect()->intended() redirige vers la page voulue avant expiration.
        // Si cette page n'existe plus ou n'a jamais été définie,
        // on tombe sur le dashboard du rôle en fallback.
        return redirect()->intended(
            $this->dashboardByRole(auth()->user())
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    // ── URL de fallback par rôle ───────────────────────────
    // On retourne une URL string et non un redirect() object.
    // Comme ça redirect()->intended($url) garde le contrôle :
    // si une "intended URL" existe en session → il l'utilise,
    // sinon il utilise notre $url comme fallback.

    private function dashboardByRole(User $user): string
    {
        return match($user->role->slug) {
            UserRole::Architect => route('architect.profile.show'),
            UserRole::Client    => route('client.dashboard'),
            UserRole::Admin     => route('admin.dashboard'),
            default     => route('login'),
        };
    }
}
