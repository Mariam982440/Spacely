<?php

namespace App\Http\Controllers\Architect;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $profile = auth()->user()->architectProfile;

        return view('architect.profile.show', compact('profile'));
    }
    public function edit()
    {
        $profile = auth()->user()->architectProfile;

        return view('architect.profile.edit', compact('profile'));
    }
    public function update(Request $request)
    {
        $request->validate([
            'bio'              => 'nullable|string|max:1000',
            'city'             => 'required|string|max:100',
            'experience_years' => 'required|integer|min:0|max:60',
            'profile_picture'  => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $profile = auth()->user()->architectProfile;

        $data = $request->only(['bio', 'city', 'experience_years']);

        // gestion de la photo
        if ($request->hasFile('profile_picture')) {

            // supprimer l'ancienne photo si elle existe
            if ($profile->profile_picture) {
                Storage::disk('public')->delete($profile->profile_picture);
            }

            $data['profile_picture'] = $request->file('profile_picture')
                ->store('architects/photos', 'public');
        }

        $profile->update($data);

        return redirect()
            ->route('architect.profile.show')
            ->with('success', 'Profil mis à jour avec succès.');
    }
}
