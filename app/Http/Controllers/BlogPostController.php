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
            'excerpt' => 'required|string',
            'content' => 'required|string',
            'category' => 'required|string|max:100',
            'tags' => 'required',
            'status' => 'required|in:draft,published',
            'featured_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
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

    public function index(Request $request)
    {
        $status = $request->input('status');
        $query = BlogPost::latest();

        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $posts = $query->get();

        // 🟡 STEP 1: AJAX request → return only list
        if ($request->ajax() && $request->has('status')) {
            return view('admin.blogpost.partials.bloglist', compact('posts'))->render();
        }

        // 🟢 STEP 2: Normal page load → return full page
        return view('admin.blogpost.index', compact('posts'));
    }



}

