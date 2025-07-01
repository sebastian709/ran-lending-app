@extends('admin')

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
                    </ul>

                    <a href="#" class="btn btn-light fw-semibold text-primary">
                        <i class="bi bi-plus-lg"></i> Create Blogpost
                    </a>
                </div>

                <!-- 🔴 Floating Tabs + Button (Mobile Only) -->
                <div class="mobile-action d-md-none">
                    <div class="bg-primary text-white p-2 rounded-top d-flex flex-column align-items-center shadow">
                        <ul class="nav nav-pills mb-2 justify-content-center">
                            <li class="nav-item">
                                <a class="nav-link active text-white btn-sm me-1" href="#">All Posts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white btn-sm me-1" href="#">Drafts</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-white btn-sm" href="#">Published</a>
                            </li>
                        </ul>
                        <a href="#" class="btn btn-light btn-sm fw-semibold text-primary w-100">
                            <i class="bi bi-plus-lg"></i> Create Blogpost
                        </a>
                    </div>
                </div>

                <!-- Blog Card Grid -->
                <div class="row">
                    @for ($i = 1; $i <= 20; $i++)
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-3">
                            <div class="card h-100 shadow-sm border-0">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-truncate">Sample Blog Title {{ $i }}</h5>
                                    <p class="card-text text-muted small flex-grow-1">
                                        This is a short excerpt or intro from the blog post to show in preview.
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-2">
                                        <span class="badge bg-success">Published</span>
                                        <div class="btn-group btn-group-sm">
                                            <a href="#" class="btn btn-outline-primary">View</a>
                                            <a href="#" class="btn btn-outline-secondary">Edit</a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer small text-muted">
                                    <i class="bi bi-calendar-event"></i> Jul 1, 2025
                                </div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>

@endsection