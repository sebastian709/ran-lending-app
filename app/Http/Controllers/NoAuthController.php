<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\BlogPost;

class NoAuthController extends Controller
{
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


    public function viewBlogPost(Request $request)
    {
        $type = $request->type;
        $id = $request->id;

        // Fetch blog post
        $post = DB::table('blog_posts')
            ->where('id', $id)
            // ->where('type', $type)
            ->first();

        if (!$post) {
            abort(404, 'Blog post not found.');
        }

        switch ($type) {

            case 'charity':
                return view('main.charity.view-blogpost', compact('post'));

            case 'lending':
                return view('main.lending.view-blogpost', compact('post'));

            case 'jewelry':
                return view('main.jewelry.view-blogpost', compact('post'));

            case 'traveltours':
                return view('main.traveltours.view-blogpost', compact('post'));

            default:
                abort(404); // fallback is required
        }
    }

}
