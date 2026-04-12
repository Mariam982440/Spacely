<?php

namespace App\Http\Controllers\Architect;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = auth()->user()
            ->architectProfile
            ->projects()
            ->with('tags', 'images')
            ->latest()
            ->paginate(10);

        return view('architect.projects.index', compact('projects'));
    }
    public function create()
    {
        $tags = Tag::orderBy('name')->get();

        return view('architect.projects.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'images'      => 'required|array|min:1',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
            'is_before'   => 'nullable|array',
            'is_after'    => 'nullable|array',
        ]);

        // créer le projet
        $project = auth()->user()->architectProfile->projects()->create([
            'title'       => $request->title,
            'description' => $request->description,
        ]);

        // attacher les tags
        if ($request->has('tags')) {
            $project->tags()->attach($request->tags);
        }

        // stocker les images
        foreach ($request->file('images') as $index => $image) {
            $path = $image->store('projects/images', 'public');

            $project->images()->create([
                'image_path' => $path,
                'is_before'  => in_array($index, $request->input('is_before', [])),
                'is_after'   => in_array($index, $request->input('is_after', [])),
            ]);
        }

        return redirect()
            ->route('architect.projects.index')
            ->with('success', 'Projet publié avec succès.');
    }
}
