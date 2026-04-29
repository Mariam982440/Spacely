<?php

namespace App\Http\Controllers\Architect;

use App\Http\Controllers\Controller;
use App\Http\Requests\Architect\StoreProjectRequest;
use App\Http\Requests\Architect\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Tag;
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

    public function store(StoreProjectRequest $request)
    {
        $validated = $request->validated();

        // créer le projet
        $project = auth()->user()->architectProfile->projects()->create([
            'title'       => $validated['title'],
            'description' => $validated['description'],
        ]);

        // attacher les tags
        if (!empty($validated['tags'])) {
            $project->tags()->attach($validated['tags']);
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

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorizeProject($project);

        $validated = $request->validated();

        // mettre à jour les données
        $project->update([
            'title'       => $validated['title'],
            'description' => $validated['description'],
        ]);

        // synchroniser les tags
        $project->tags()->sync($validated['tags'] ?? []);

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

    public function destroy(Project $project)
    {
        $this->authorizeProject($project);

        // Supprimer les images du storage
        foreach ($project->images as $image) {
            Storage::disk('public')->delete($image->image_path);
        }

        $project->delete();

        return redirect()
            ->route('architect.projects.index')
            ->with('success', 'Projet supprimé.');
    }

    // sécurité 

    private function authorizeProject(Project $project): void
    {
        $profileId = auth()->user()->architectProfile->id;

        if ($project->architect_id !== $profileId) {
            abort(403);
        }
    }
}
