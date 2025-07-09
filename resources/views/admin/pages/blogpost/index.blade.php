@extends('admin.container')

@section('content')

    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- 🔵 Sticky Tabs + Button (Desktop Only) -->
                <div
                    class="blog-tabs sticky-desktop d-none d-md-flex justify-content-between align-items-center px-3 py-2 mb-3 rounded shadow-sm">
                    <ul class="nav nav-pills mb-0">
                        <li class="nav-item">
                            <a class="nav-link active text-white bp-tab" href="#">All Posts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white bp-tab" href="#">Drafts</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white bp-tab" href="#">Published</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white bp-tab" href="#">Archived</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-white bp-tab" href="#">Deleted</a>
                        </li>
                    </ul>

                    <a href="#" class="btn btn-light fw-semibold text-primary" data-url="/admin/blogpost/createBlogpost">
                        <i class="bi bi-plus-lg"></i> Create Blogpost
                    </a>
                </div>

                <!-- 🔴 Floating Tabs + Button (Mobile Only) -->
                <div class="mobile-action d-md-none">
                    <div class="bg-primary text-white p-2 rounded-top d-flex flex-column align-items-center shadow">
                        <ul class="nav nav-pills mb-2 justify-content-center">
                            <li class="nav-item">
                                <a class="nav-link active text-white btn-sm me-1 bp-tab" href="#">All Posts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white btn-sm me-1 bp-tab" href="#">Drafts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white btn-sm bp-tab" href="#">Published</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white btn-sm bp-tab" href="#">Archived</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white btn-sm bp-tab" href="#">Deleted</a>
                            </li>
                        </ul>
                        <a href="#" class="btn btn-light fw-semibold text-primary w-100"
                            data-url="/admin/blogpost/createBlogpost">
                            <i class="bi bi-plus-lg"></i> Create Blogpost
                        </a>
                    </div>
                </div>

                <!-- Blog Card Grid -->
                <div id="blogPostList" class="row">
                    @include('admin.blogpost.partials.bloglist', ['posts' => $posts])
                </div>
            </div>
        </div>
    </div>

@endsection