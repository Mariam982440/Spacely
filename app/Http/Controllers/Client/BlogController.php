<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\Tag;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('status', 'published')
            ->with('architectProfile.user')
            ->latest()
            ->paginate(9);

        return view('client.blog.index', compact('posts'));
    }

    public function show(BlogPost $post)
    {
        // refuser l'accès aux brouillons
        if ($post->status !== 'published') {
            abort(404);
        }

        $post->load('architectProfile.user');

        // articles suggérés du même architecte
        $related = BlogPost::where('status', 'published')
            ->where('architect_id', $post->architect_id)
            ->where('id', '!=', $post->id)
            ->with('architectProfile.user')
            ->latest()
            ->take(3)
            ->get();

        return view('client.blog.show', compact('post', 'related'));
    }
}