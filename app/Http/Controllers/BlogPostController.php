<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BlogPost;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BlogPostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
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
            'added_by' => '1',
            'updated_by' => '0',
            'deleted_by' => '0'
        ]);

        return response()->json(['message' => 'Blog post saved successfully.']);
    }

    public function index(Request $request)
    {
        $status = $request->input('status');

        // Default query
        $query = BlogPost::latest();

        // Gamitin lang ang withTrashed() kapag status is 'deleted'
        if ($status === 'deleted') {
            $query = BlogPost::withTrashed()->where('status', 'deleted')->latest();
        } elseif ($status && $status !== 'all') {
            $query->where('status', $status);
        } elseif (!$status || $status === 'all') {
            $query->where('status', '!=', 'archived');
            $query->where('status', '!=', 'draft');
        }

        $posts = $query->get();

        if ($request->ajax() && $request->has('status')) {
            return view('admin.pages.blogpost.partials.bloglist', compact('posts'))->render();
        }

        return view('admin.pages.blogpost.index', compact('posts'));
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

        $post = BlogPost::withTrashed()->findOrFail($request->id); // ← FIXED

        // If restoring from soft-delete and setting status back to archived
        if ($post->trashed() && $request->status === 'archived') {
            $post->restore(); // remove deleted_at
            $post->status = 'archived';
            $post->save();

            return response()->json(['message' => 'Post restored and status set to archived']);
        }

        // If marking as deleted (soft delete)
        if ($request->status === 'deleted') {
            $post->status = 'deleted';
            $post->save();
            $post->delete(); // soft delete
            return response()->json(['message' => 'Post soft deleted']);
        }

        // Normal status update
        $post->status = $request->status;
        $post->save();

        return response()->json(['message' => 'Status updated to ' . $request->status]);
    }

    public function landingView()
    {
        $query = BlogPost::latest();
        $query->where('status', "published");
        $posts = $query->take(4)->get();

        return view('main.index', compact('posts'));
    }

    public function landingJewelry()
    {

        $query = BlogPost::latest();
        $query->where('status', "published");
        $query->where('category', "jewelry");
        $posts = $query->take(3)->get();

        return view('main.jewelry', compact('posts'));
    }

    public function landingHub()
    {

        $query = BlogPost::latest();
        $query->where('status', "published");
        $query->where('category', "hub");

        $posts = $query->take(3)->get();

        return view('main.charity', compact('posts'));
    }

    public function landingTAT()
    {
        $query = BlogPost::latest();
        $query->where('status', "published");
        $query->where('category', "travel and tours");

        $posts = $query->take(3)->get();

        return view('main.travel-and-tours', compact('posts'));
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = $request->file('upload')->store('uploads', 'public');

            return response()->json([
                'url' => asset('storage/' . $path),
            ]);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }


}

