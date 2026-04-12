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
}
