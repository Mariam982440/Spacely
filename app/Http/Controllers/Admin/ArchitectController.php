<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArchitectProfile;
use Illuminate\Http\Request;

class ArchitectController extends Controller
{
    public function index(Request $request)
    {
        $query = ArchitectProfile::query()
            ->with('user')
            ->withCount('projects')
            ->latest();

        if ($request->status === 'pending') {
            $query->where('is_verified', false);
        }

        if ($request->status === 'verified') {
            $query->where('is_verified', true);
        }

        $architects = $query->paginate(12)->withQueryString();

        return view('admin.architects.index', compact('architects'));
    }

    public function show(ArchitectProfile $architect)
    {
        $architect->load('user', 'projects.images', 'projects.tags');

        return view('admin.architects.show', compact('architect'));
    }

    public function approve(ArchitectProfile $architect)
    {
        $architect->update(['is_verified' => true]);

        return back()->with('success', 'Compte architecte validé.');
    }

    public function suspend(ArchitectProfile $architect)
    {
        $architect->update(['is_verified' => false]);

        return back()->with('success', 'Compte architecte remis en attente.');
    }
}
