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

                    <a href="#" class="btn btn-light fw-semibold text-primary" data-url="/admin/blogpost/createBlogpost">
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
                        <a href="#" class="btn btn-light btn-sm fw-semibold text-primary w-100"
                            data-url="/admin/blogpost/createBlogpost">
                            <i class="bi bi-plus-lg"></i> Create Blogpost
                        </a>
                    </div>
                </div>

                <!-- Blog Card Grid -->
                <div class="row">
                    @php
                        $categoryIcons = [
                            'jewelry' => ['icon' => 'bi-gem', 'color' => 'text-warning'],
                            'travel and tours' => ['icon' => 'bi-airplane-engines', 'color' => 'text-primary'],
                            'charity' => ['icon' => 'bi-heart-fill', 'color' => 'text-danger'],
                            'shops' => ['icon' => 'bi-shop', 'color' => 'text-purple'],
                        ];
                    @endphp

                    @forelse ($posts as $post)
                        @php
                            $categoryKey = strtolower($post->category ?? 'uncategorized');
                            $iconData = $categoryIcons[$categoryKey] ?? ['icon' => 'bi-folder-fill', 'color' => 'text-secondary'];
                        @endphp

                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4">
                            <div class="card blog-card border-0 shadow-sm overflow-hidden h-100 rounded position-relative">

                                {{-- 🖼️ Featured Image --}}
                                <div class="position-relative">
                                    <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/600x400?text=No+Image' }}"
                                        class="img-fluid w-100" style="height: 180px; object-fit: cover;" alt="Blog Image">

                                    {{-- 🏷️ Category Badge --}}
                                    <span
                                        class="badge bg-white text-dark position-absolute top-0 start-0 m-2 d-flex align-items-center px-2 py-1 gap-1 shadow-sm"
                                        style="font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem;">
                                        <i class="bi {{ $iconData['icon'] }} {{ $iconData['color'] }}"
                                            style="font-size: 1.1rem;"></i>
                                        {{ $post->category ?? 'Uncategorized' }}
                                    </span>
                                </div>

                                {{-- 📄 Content --}}
                                <div class="p-3 bg-white d-flex flex-column justify-content-between h-100">
                                    <div class="flex-grow-1">
                                        <h5 class="fw-semibold text-dark text-truncate mb-1">{{ $post->title }}</h5>
                                        <p class="text-muted small mb-2">{{ Str::limit($post->excerpt, 80) }}</p>
                                    </div>

                                    {{-- 🏁 Status & Date --}}
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span
                                            class="badge {{ $post->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                                            {{ ucfirst($post->status) }}
                                        </span>
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event me-1"></i> {{ $post->created_at->format('M d, Y') }}
                                        </small>
                                    </div>

                                    {{-- 🔘 Footer Buttons --}}
                                    <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                                        {{-- Left: Icon Buttons --}}
                                        <div class="d-flex gap-3">
                                            <a href="#" class="text-primary action-icon" title="View"><i
                                                    class="bi bi-eye fs-5"></i></a>
                                            <a href="#" class="text-secondary action-icon" title="Edit"><i
                                                    class="bi bi-pencil-square fs-5"></i></a>
                                            @if ($post->status === 'draft')
                                                <a href="#" class="text-success action-icon" title="Publish"><i
                                                        class="bi bi-upload fs-5"></i></a>
                                            @endif
                                        </div>

                                        {{-- Right: Trash Icon --}}
                                        <a href="#" class="text-danger action-icon" title="Delete"><i
                                                class="bi bi-trash fs-5"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-info">No blog posts found.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

@endsection