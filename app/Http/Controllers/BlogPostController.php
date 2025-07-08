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
        } elseif (!$status || $status === 'all') {
            $query->where('status', '!=', 'archived'); // ← exclude archived by default
        }

        $posts = $query->get();

        // 🟡 STEP 1: AJAX request → return only list
        if ($request->ajax() && $request->has('status')) {
            return view('admin.blogpost.partials.bloglist', compact('posts'))->render();
        }

        // 🟢 STEP 2: Normal page load → return full page
        return view('admin.blogpost.index', compact('posts'));
    }

    public function view($id)
    {
        $post = BlogPost::findOrFail($id);

        return response()->json([
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'tags' => $post->tags_json,
            'category' => $post->category,
            'featured_image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
        ]);
    }

    public function fetch($id)
    {
        $post = BlogPost::findOrFail($id);

        return response()->json([
            'id' => $post->id,
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'content' => $post->content,
            'status' => $post->status,
            'category' => $post->category,
            'tags' => $post->tags_json, // Make sure it's JSON array like [{ value: "tag1" }]
            'featured_image' => $post->featured_image ? asset('storage/' . $post->featured_image) : null,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:blog_posts,id',
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'tags' => 'nullable',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $post = BlogPost::findOrFail($validated['id']);

        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('uploads/blog_images', 'public');
            $post->featured_image = $imagePath;
        }

        $post->title = $validated['title'];
        $post->excerpt = $validated['excerpt'] ?? null;
        $post->content = $validated['content'] ?? null;
        $post->category = $validated['category'] ?? null;
        $post->status = $validated['status'];
        $post->tags_json = json_decode($validated['tags'] ?? '[]', true);

        $post->save();

        return response()->json(['message' => 'Blog post updated successfully.']);
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:blog_posts,id',
            'status' => 'required|in:draft,published,archived,deleted',
        ]);

        $post = BlogPost::findOrFail($request->id);

        if ($request->status === 'deleted') {
            $post->delete(); // soft delete ito, maglalagay ng date sa deleted_at
            return response()->json(['message' => 'Post soft deleted']);
        }

        $post->status = $request->status;
        $post->save();

        return response()->json(['message' => 'Status updated to ' . $request->status]);
    }



}

