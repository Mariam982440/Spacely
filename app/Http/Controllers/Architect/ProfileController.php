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
}
