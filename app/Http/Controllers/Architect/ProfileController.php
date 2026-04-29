<?php

namespace App\Http\Controllers\Architect;
use App\Http\Controllers\Controller;
use App\Http\Requests\Architect\UpdateProfileRequest;
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
    public function update(UpdateProfileRequest $request)
    {
        $profile = auth()->user()->architectProfile;

        $data = $request->safe()->only(['bio', 'city', 'experience_years']);

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
