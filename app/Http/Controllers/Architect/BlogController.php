<?php

namespace App\Http\Controllers\Architect;
 
use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::where('architect_id', auth()->user()->architectProfile->id)
            ->latest()
            ->paginate(10);
 
        return view('architect.blog.index', compact('posts'));
    }
    public function create()
    {
        return view('architect.blog.form');
    }

     public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'      => 'required|in:draft,published',
        ]);
 
        $data = [
            'architect_id' => auth()->user()->architectProfile->id,
            'title'        => $request->title,
            'slug'         => Str::slug($request->title) . '-' . Str::random(5),
            'content'      => $request->content,
            'status'       => $request->status,
        ];
 
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')
                ->store('blog/covers', 'public');
        }
 
        BlogPost::create($data);
 
        return redirect()
            ->route('architect.blog.index')
            ->with('success', 'Article publié avec succès.');
    }
    public function edit(BlogPost $post)
    {
        $this->authorizePost($post);
 
        return view('architect.blog.form', compact('post'));
    }
 
    public function update(Request $request, BlogPost $post)
    {
        $this->authorizePost($post);
 
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required|string',
            'cover_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
            'status'      => 'required|in:draft,published',
        ]);
 
        $data = [
            'title'   => $request->title,
            'content' => $request->content,
            'status'  => $request->status,
        ];
 
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image
            if ($post->cover_image) {
                Storage::disk('public')->delete($post->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')
                ->store('blog/covers', 'public');
        }
 
        $post->update($data);
 
        return redirect()
            ->route('architect.blog.index')
            ->with('success', 'Article mis à jour.');
    }
    public function destroy(BlogPost $post)
    {
        $this->authorizePost($post);
 
        if ($post->cover_image) {
            Storage::disk('public')->delete($post->cover_image);
        }
 
        $post->delete();
 
        return redirect()
            ->route('architect.blog.index')
            ->with('success', 'Article supprimé.');
    }
 
    // sécurité 
 
    private function authorizePost(BlogPost $post): void
    {
        if ($post->architect_id !== auth()->user()->architectProfile->id) {
            abort(403);
        }
    }
}
