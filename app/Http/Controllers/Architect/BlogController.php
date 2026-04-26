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
}
