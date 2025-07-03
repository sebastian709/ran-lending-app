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
            {{-- Image --}}
            <div class="position-relative">
                <img src="{{ $post->featured_image ? asset('storage/' . $post->featured_image) : 'https://via.placeholder.com/600x400?text=No+Image' }}"
                    class="img-fluid w-100" style="height: 180px; object-fit: cover;" alt="Blog Image">

                <span
                    class="badge bg-white text-dark position-absolute top-0 start-0 m-2 d-flex align-items-center px-2 py-1 gap-1 shadow-sm"
                    style="font-size: 0.75rem; font-weight: 600; border-radius: 0.375rem;">
                    <i class="bi {{ $iconData['icon'] }} {{ $iconData['color'] }}" style="font-size: 1.1rem;"></i>
                    {{ $post->category ?? 'Uncategorized' }}
                </span>
            </div>

            <div class="p-3 bg-white d-flex flex-column justify-content-between h-100">
                <div class="flex-grow-1">
                    <h5 class="fw-semibold text-dark text-truncate mb-1">{{ $post->title }}</h5>
                    <p class="text-muted small mb-2">{{ Str::limit($post->excerpt, 80) }}</p>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge {{ $post->status === 'published' ? 'bg-success' : 'bg-warning text-dark' }}">
                        {{ ucfirst($post->status) }}
                    </span>
                    <small class="text-muted">
                        <i class="bi bi-calendar-event me-1"></i> {{ $post->created_at->format('M d, Y') }}
                    </small>
                </div>

                <div class="pt-2 border-top d-flex justify-content-between align-items-center">
                    {{-- LEFT: Status Actions --}}
                    <div class="d-flex gap-3">
                        {{-- Publish Button --}}
                        @if ($post->status === 'draft' || $post->status === 'archived')
                            <a href="#" class="text-success action-icon btn-bp-status" title="Publish"
                                data-bp_id="{{ $post->id }}" data-status="published">
                                <i class="bi bi-upload fs-5"></i>
                            </a>
                        @endif

                        {{-- Draft Button --}}
                        @if ($post->status === 'published' || $post->status === 'archived')
                            <a href="#" class="text-warning action-icon btn-bp-status" title="Draft"
                                data-bp_id="{{ $post->id }}" data-status="draft">
                                <i class="bi bi-file-earmark fs-5"></i>
                            </a>
                        @endif

                        {{-- Archive Button --}}
                        @if ($post->status === 'draft' || $post->status === 'published')
                            <a href="#" class="text-danger action-icon btn-bp-status" title="Archive"
                                data-bp_id="{{ $post->id }}" data-status="archived">
                                <i class="bi bi-archive fs-5"></i>
                            </a>
                        @endif
                    </div>

                    {{-- RIGHT: View & Edit --}}
                    <div class="d-flex gap-3">
                        <a href="#" class="text-primary action-icon btn-bpl-view" title="View" data-bp_id="{{ $post->id }}">
                            <i class="bi bi-eye fs-5"></i>
                        </a>

                        <a href="#" class="text-secondary action-icon btn-bpl-edit" title="Edit"
                            data-bp_id="{{ $post->id }}">
                            <i class="bi bi-pencil-square fs-5"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="alert alert-info">No blog posts found.</div>
    </div>
@endforelse