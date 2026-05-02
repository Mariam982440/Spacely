<?php

namespace App\Http\Controllers\Architect;
 
use App\Http\Controllers\Controller;
use App\Http\Requests\Architect\StoreBlogPostRequest;
use App\Models\BlogPost;
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
        return view('architect.blog.create');
    }

     public function store(StoreBlogPostRequest $request)
    {
        $validated = $request->validated();
 
        $data = [
            'architect_id' => auth()->user()->architectProfile->id,
            'title'        => $validated['title'],
            'slug'         => Str::slug($validated['title']) . '-' . Str::random(5),
            'content'      => $validated['content'],
            'status'       => $validated['status'],
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
 
        return view('architect.blog.create', compact('post'));
    }
 
    public function update(StoreBlogPostRequest $request, BlogPost $post)
    {
        $this->authorizePost($post);
 
        $validated = $request->validated();
 
        $data = [
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'status'  => $validated['status'],
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
