@extends('admin.container')

@section('content')

    <div class="container sticky-desktop">
        <div class="row">
            <div class="col-lg-12">
                <!-- 🔵 Sticky Tabs + Button (Desktop Only) -->
                <div
                    class="blog-tabs d-none d-md-flex justify-content-between align-items-center pe-3 py-2 mb-3 rounded shadow-sm">
                    <div class="d-none d-md-flex justify-content-between align-items-center">
                        <a href="#" class="btn btn-light fw-semibold text-primary me-3 px-5" data-url="/admin/blogpost">
                            <i class="bi bi-card-list"></i> Blog list
                        </a>
                    </div>
                    <div class="d-none d-md-flex justify-content-between align-items-center">
                        <a href="#" class="btn btn-light fw-semibold text-primary mx-2 px-5 btn-cbp-preview">
                            <i class="bi bi-eye-fill"></i> Preview
                        </a>
                        <a href="#" class="btn btn-light fw-semibold text-primary mx-2 px-5 btn-cbp-publish">
                            <i class="bi bi-file-earmark-medical-fill"></i> Publish
                        </a>
                    </div>
                </div>

                <!-- 🌟 Floating Action Bar (Mobile Only) -->
                <div class="mobile-action d-md-none">
                    <div class="mobile-bar shadow-lg">
                        <a href="#" class="mobile-item" data-url="/admin/blogpost">
                            <i class="bi bi-card-list"></i>
                            <span>Blog</span>
                        </a>
                        <a href="#" class="mobile-item btn-cbp-preview">
                            <i class="bi bi-eye-fill"></i>
                            <span>Preview</span>
                        </a>
                        <a href="#" class="mobile-item primary btn-cbp-publish">
                            <i class="bi bi-file-earmark-medical-fill"></i>
                            <span>Publish</span>
                        </a>
                    </div>
                </div>


            </div>
        </div>
    </div>

    <form id="blogForm" method="POST" enctype="multipart/form-data"></form>
    <div class="container">
        <div class="row">
            <!-- Left: Blog Content Editor -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <!-- Title Input -->
                        <div class="mb-3">
                            <label for="blogTitle" class="form-label fw-semibold">Blog Title</label>
                            <input type="text" class="form-control" id="blogTitle" placeholder="Enter blog title">
                        </div>

                        <!-- Short Description -->
                        <div class="mb-3">
                            <label for="blogExcerpt" class="form-label fw-semibold">Short Description</label>
                            <textarea class="form-control" id="blogExcerpt" rows="2"
                                placeholder="Enter short description..."></textarea>
                        </div>

                        <!-- Rich Content Area (or textarea) -->
                        <div class="mb-3">
                            <label for="blogContent" class="form-label fw-semibold">Blog Content</label>
                            <textarea class="form-control" id="blogContent" rows="10" placeholder="Write your blog post..."
                                name="content"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right: Settings Panel Accordion -->
            <div class="col-lg-4 mb-4">
                <!-- 🔘 Post Settings Header -->
                <div class="d-flex align-items-center mb-3 bg-light px-3 py-2 rounded shadow-sm border-bottom">
                    <i class="bi bi-sliders2-vertical fs-4 text-primary me-2"></i>
                    <h5 class="mb-0 fw-bold text-dark">Post Settings</h5>
                </div>


                <div class="accordion shadow-sm" id="blogSettingsAccordion">
                    <!-- Status Panel -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingStatus">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseStatus" aria-expanded="false" aria-controls="collapseStatus">
                                <i class="bi bi-info-circle-fill me-2"></i> Post Status
                            </button>
                        </h2>
                        <div id="collapseStatus" class="accordion-collapse collapse" aria-labelledby="headingStatus"
                            data-bs-parent="#blogSettingsAccordion">
                            <div class="accordion-body">
                                @php
                                    $statuses = [
                                        ['label' => 'Draft', 'value' => 'draft', 'icon' => 'bi-pencil-square', 'color' => 'text-secondary'],
                                        ['label' => 'Published', 'value' => 'published', 'icon' => 'bi-check-circle-fill', 'color' => 'text-success'],
                                    ];
                                @endphp

                                @foreach ($statuses as $status)
                                    <label
                                        class="status-option form-check mb-3 d-flex align-items-center border rounded px-3 py-2">
                                        <input class="form-check-input me-3" type="radio" name="status"
                                            value="{{ $status['value'] }}" {{ $loop->first ? 'checked' : '' }}>
                                        <i class="bi {{ $status['icon'] }} me-2 fs-5 {{ $status['color'] }}"></i>
                                        <span class="fs-6 fw-semibold text-dark">{{ $status['label'] }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Featured Image Panel -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingFeaturedImage">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseFeaturedImage" aria-expanded="false"
                                aria-controls="collapseFeaturedImage">
                                <i class="bi bi-image-fill me-2"></i> Featured Image
                            </button>
                        </h2>
                        <div id="collapseFeaturedImage" class="accordion-collapse collapse"
                            aria-labelledby="headingFeaturedImage" data-bs-parent="#blogSettingsAccordion">
                            <div class="accordion-body">

                                <!-- Drop Zone -->
                                <div class="featured-drop-area text-center border border-dashed rounded p-4 mb-2"
                                    id="dropZone">
                                    <i class="bi bi-cloud-arrow-up fs-1 text-muted"></i>
                                    <p class="text-muted mb-1">Drag & drop image here or click to upload</p>
                                    <p class="text-muted small mb-0">Accepted: JPG, PNG, WEBP (Max: 2MB)</p>
                                    <input type="file" id="featuredImageInput" name="featured_image" accept="image/*"
                                        hidden>
                                </div>

                                <!-- Preview -->
                                <div id="previewContainer" class="position-relative d-none">
                                    <img id="previewImage" src="#" alt="Preview"
                                        class="img-fluid rounded border shadow-sm w-100 object-fit-cover"
                                        style="height: 200px;">
                                    <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 m-2"
                                        id="removeImageBtn" title="Remove Image">
                                        <i class="bi bi-x-lg"></i>
                                    </button>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- Categories Panel -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingCategories">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseCategories" aria-expanded="false"
                                aria-controls="collapseCategories">
                                <i class="bi bi-folder-fill me-2"></i> Categories
                            </button>
                        </h2>
                        <div id="collapseCategories" class="accordion-collapse collapse" aria-labelledby="headingCategories"
                            data-bs-parent="#blogSettingsAccordion">
                            <div class="accordion-body">

                                @php
                                    $categories = [
                                        ['label' => 'Jewelry', 'icon' => 'bi-gem'],
                                        ['label' => 'Travel and Tours', 'icon' => 'bi-airplane-engines'],
                                        ['label' => 'Charity', 'icon' => 'bi-heart-fill'],
                                        ['label' => 'Shops', 'icon' => 'bi-shop'],
                                    ];
                                @endphp

                                @foreach ($categories as $category)
                                    <label
                                        class="category-option form-check mb-3 d-flex align-items-center border rounded px-3 py-2">
                                        <input class="form-check-input me-3" type="radio" name="category"
                                            value="{{ strtolower($category['label']) }}">
                                        <i class="bi {{ $category['icon'] }} me-2 fs-5 text-primary"></i>
                                        <span class="fs-6 fw-semibold text-dark">{{ $category['label'] }}</span>
                                    </label>
                                @endforeach

                            </div>
                        </div>
                    </div>

                    <!-- Tags Panel -->
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTags">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapseTags" aria-expanded="false" aria-controls="collapseTags">
                                <i class="bi bi-tags-fill me-2"></i> Tags
                            </button>
                        </h2>
                        <div id="collapseTags" class="accordion-collapse collapse" aria-labelledby="headingTags"
                            data-bs-parent="#blogSettingsAccordion">
                            <div class="accordion-body">
                                <input id="tagsInput" name="tags" placeholder="Enter tags">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </form>
@endsection