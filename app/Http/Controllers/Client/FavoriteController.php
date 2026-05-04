<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\StoreFavoriteRequest;
use App\Models\Favorite;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with('favoritable')
            ->latest()
            ->paginate(12);

        return view('client.favorites.index', compact('favorites'));
    }

    public function store(StoreFavoriteRequest $request)
    {
        // eviter les doublons
        $alreadyExists = Favorite::where('user_id', auth()->id())
            ->where('favoritable_id', $request->favoritable_id)
            ->where('favoritable_type', $request->favoritable_type)
            ->exists();

        if ($alreadyExists) {
            return back()->with('error', 'Cet élément est déjà dans votre moodboard.');
        }

        Favorite::create([
            'user_id'          => auth()->id(),
            'favoritable_id'   => $request->favoritable_id,
            'favoritable_type' => $request->favoritable_type,
        ]);

        return back()->with('success', 'Ajouté à votre moodboard.');
    }

    public function destroy(Favorite $favorite)
    {
        // vérifier que le favori appartient au client connecté
        if ($favorite->user_id !== auth()->id()) {
            abort(403);
        }

        $favorite->delete();

        return back()->with('success', 'Retiré du moodboard.');
    }
}