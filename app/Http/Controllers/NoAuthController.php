<?php

namespace App\Http\Controllers;

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
}
