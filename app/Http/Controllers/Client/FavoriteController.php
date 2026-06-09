<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreFavoriteRequest;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    // Moodboard — seulement les projets
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', 'App\Models\Project')
            ->with('favoritable.images', 'favoritable.tags')
            ->latest()
            ->paginate(12);

        return view('client.favorites.index', compact('favorites'));
    }

    // Articles favoris — dans la section blog
    public function blogFavorites()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->where('favoritable_type', 'App\Models\BlogPost')
            ->with('favoritable.architectProfile.user')
            ->latest()
            ->paginate(9);

        return view('client.blog.favorites', compact('favorites'));
    }

    public function store(StoreFavoriteRequest $request)
    {
        $validated = $request->validated();

        // Si _remove → supprimer
        if ($request->boolean('_remove')) {
            Favorite::where('user_id', auth()->id())
                ->where('favoritable_id', $validated['favoritable_id'])
                ->where('favoritable_type', $validated['favoritable_type'])
                ->delete();

            if ($request->expectsJson()) {
                return response()->json(['status' => 'removed']);
            }

            return back()->with('success', 'Retiré de vos favoris.');
        }

        // Éviter les doublons
        $exists = Favorite::where('user_id', auth()->id())
            ->where('favoritable_id', $validated['favoritable_id'])
            ->where('favoritable_type', $validated['favoritable_type'])
            ->exists();

        if ($exists) {
            if ($request->expectsJson()) {
                return response()->json(['status' => 'already_exists'], 422);
            }
            return back()->with('error', 'Déjà dans vos favoris.');
        }

        Favorite::create([
            'user_id'          => auth()->id(),
            'favoritable_id'   => $validated['favoritable_id'],
            'favoritable_type' => $validated['favoritable_type'],
        ]);

        if ($request->expectsJson()) {
            return response()->json(['status' => 'added']);
        }

        return back()->with('success', 'Ajouté à vos favoris.');
    }

    public function destroy(Favorite $favorite)
    {
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $favorite->delete();

        return back()->with('success', 'Retiré de vos favoris.');
    }
}
