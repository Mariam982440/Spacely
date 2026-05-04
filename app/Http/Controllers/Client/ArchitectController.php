<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ArchitectProfile;
use App\Models\Tag;
use Illuminate\Http\Request;

class ArchitectController extends Controller
{
    public function index(Request $request)
    {
        $query = ArchitectProfile::query()
            ->where('is_verified', true)
            ->with('user', 'projects.images', 'projects.tags')
            ->withCount('projects');

        // filtre par ville
        if ($request->filled('city')) {
            $query->where('city', 'like', '%' . $request->city . '%');
        }

        // filtre par tag / style
        if ($request->filled('tag')) {
            $query->whereHas('projects.tags', function ($q) use ($request) {
                $q->where('tags.id', $request->tag);
            });
        }

        // filtre par expérience minimale
        if ($request->filled('experience')) {
            $query->where('experience_years', '>=', $request->experience);
        }

        $architects = $query->paginate(9)->withQueryString();
        $tags       = Tag::orderBy('name')->get();

        return view('client.architects.index', compact('architects', 'tags'));
    }

    public function show(ArchitectProfile $profile)
    {
        // Charger toutes les données nécessaires à la vue en une seule requête
        $profile->load([
            'user',
            'projects.images',
            'projects.tags',
            'availabilities.timeSlots' => fn($q) => $q->where('is_booked', false)
                                                       ->where('start_at', '>', now())
                                                       ->orderBy('start_at'),
        ]);

        return view('client.architects.show', compact('profile'));
    }
}