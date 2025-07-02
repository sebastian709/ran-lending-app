<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;

class BlogPostController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('uploads/blog_images', 'public');
        }

        $post = BlogPost::create([
            'title' => $validated['title'],
            'excerpt' => $validated['excerpt'] ?? null,
            'content' => $validated['content'] ?? null,
            'category' => $validated['category'] ?? null,
            'status' => $validated['status'], // ⬅️ dito mo isinasama ang status
            'featured_image' => $imagePath,
            'tags_json' => json_decode($validated['tags'] ?? '[]', true),
        ]);

        return response()->json(['message' => 'Blog post saved successfully.']);
    }

    public function index()
    {
        $posts = BlogPost::latest()->get(); // you can paginate later

        return view('admin.blogpost.index', compact('posts'));
    }
}

