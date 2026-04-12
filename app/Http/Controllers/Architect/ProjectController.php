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
    public function show(Project $project)
    {
        $this->authorizeProject($project);

        $project->load('tags', 'images');

        return view('architect.projects.show', compact('project'));
    }

    public function edit(Project $project)
    {
        $this->authorizeProject($project);

        $tags        = Tag::orderBy('name')->get();
        $projectTags = $project->tags->pluck('id')->toArray();

        return view('architect.projects.edit', compact('project', 'tags', 'projectTags'));
    }

    public function update(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'tags'        => 'nullable|array',
            'tags.*'      => 'exists:tags,id',
            'images'      => 'nullable|array',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:4096',
        ]);

        // mettre à jour les données
        $project->update($request->only(['title', 'description']));

        // synchroniser les tags
        $project->tags()->sync($request->input('tags', []));

        // ajouter les nouvelles images si envoyées
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('projects/images', 'public');

                $project->images()->create([
                    'image_path' => $path,
                    'is_before'  => false,
                    'is_after'   => false,
                ]);
            }
        }

        return redirect()
            ->route('architect.projects.show', $project)
            ->with('success', 'Projet mis à jour avec succès.');
    }
}
