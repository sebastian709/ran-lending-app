@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Prevent horizontal scroll */
        html,
        body {
            overflow-x: hidden;
        }

        .main-content {
            width: 100%;
        }

        .dashboard-card {
            background-color: #fff;
            border-radius: 8px;
            overflow-x: hidden;
        }

        @media (max-width: 767.98px) {
            .responsive-header {
                flex-direction: column !important;
                align-items: stretch !important;
            }

            .responsive-header .btn,
            .responsive-header h4 {
                width: 100%;
            }

            .responsive-header h4 {
                text-align: right;
                margin-top: 0.5rem;
            }

            .responsive-search {
                margin-top: 1rem;
            }

            .loan-item {
                flex-direction: column !important;
                align-items: flex-start !important;
            }

            .pagination-container {
                justify-content: center;
                flex-wrap: wrap;
                width: 100%;
            }

            .entries-info {
                width: 100%;
                text-align: center;
                margin-bottom: 0.5rem;
            }
        }
    </style>
@endsection

@section('content')
    <div class="d-flex">
        <!-- Sidebar -->
        @include('borrower.layouts.sidebar')

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            <div class="container-fluid p-4">
                <div class="row">
                    <div class="col-12">
                        <div class="dashboard-card">
                            <div class="my-4">
                                <!-- Row 1: Back (left) + Title (right) -->
                                <div class="loan-header-wrapper mx-4 my-4">
                                    <!-- Row 1: Back and Title -->
                                    <div
                                        class="d-flex justify-content-between align-items-center flex-wrap mb-2 loan-header-row">
                                        <!-- Back Button -->
                                        <button type="button" data-url="/profile"
                                            class="btn btn-outline-secondary btn-sm btn-back">
                                            <i class="ri-arrow-go-back-line"></i> Back
                                        </button>

                                        <!-- Title -->
                                        <h4 class="text-dark fw-bold m-0 loan-title text-md-center text-start pw-md-0 ps-5 pb-md-0 pb-3 flex-grow-1">
                                            Loan List</h4>

                                        <!-- Search (Desktop Only) -->
                                        <div class="input-group input-group-sm d-none d-md-flex ms-auto"
                                            style="max-width: 250px;">
                                            <input type="text" class="form-control" placeholder="Search loan...">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="ri-search-line"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Row 2: Search (Mobile Only) -->
                                    <div class="d-flex d-md-none px-1">
                                        <div class="input-group input-group-sm w-100">
                                            <input type="text" class="form-control" placeholder="Search loan...">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="ri-search-line"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr>

                            <div id="loanList" class="loan-list d-flex flex-column mx-3">
                                @forelse ($loans as $loan)
                                                        <div
                                                            class="loan-item px-4 py-3 mb-3 border shadow rounded-3 d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
                                                            <div class="flex-grow-1">
                                                                <div class="d-flex flex-column flex-md-row mb-2">
                                                                    <div class="me-5">
                                                                        <div class="fw-bold text-dark small">Loan ID</div>
                                                                        <div class="text-dark">LN-{{ str_pad($loan->id, 8, '0', STR_PAD_LEFT) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="me-5">
                                                                        <div class="fw-bold text-dark small">Date Applied</div>
                                                                        <div>{{ \Carbon\Carbon::parse($loan->applied_at)->format('M d, Y') }}</div>
                                                                    </div>
                                                                    <div class="me-5">
                                                                        <div class="fw-bold text-dark small">Amount</div>
                                                                        <div class="text-dark fw-medium">₱{{ number_format($loan->amount, 2) }}
                                                                        </div>
                                                                    </div>
                                                                    <div class="me-5">
                                                                        <div class="fw-bold text-dark small">Status</div>
                                                                        <span class="badge bg-{{ 
                                                                                                            $loan->status == 'Rejected' ? 'danger' :
                                    ($loan->status == 'Transferred and Processed' ? 'success' : 'warning text-dark') 
                                                                                                        }}">
                                                                            {{ $loan->status }}
                                                                        </span>
                                                                    </div>
                                                                </div>
                                                                <hr class="w-75">
                                                                <div class="d-flex flex-column flex-md-row flex-wrap text-muted small">
                                                                    <div class="me-3"><strong>Approved:</strong>
                                                                        {{ $loan->approved_at ? \Carbon\Carbon::parse($loan->approved_at)->format('M d, Y') : '–' }}
                                                                    </div>
                                                                    <div class="me-3"><strong>Disbursed:</strong>
                                                                        {{ $loan->disbursed_at ? \Carbon\Carbon::parse($loan->disbursed_at)->format('M d, Y') : '–' }}
                                                                    </div>
                                                                    <div class="me-3"><strong>Closed:</strong>
                                                                        {{ $loan->closed_at ? \Carbon\Carbon::parse($loan->closed_at)->format('M d, Y') : '–' }}
                                                                    </div>
                                                                    <div><strong>Remarks:</strong> {{ $loan->remarks ?? '–' }}</div>
                                                                </div>
                                                            </div>

                                                            <div class="mt-3 mt-md-0">
                                                                <button class="btn btn-outline-dark btn-sm view-loan-btn"
                                                                    data-id="{{ $loan->id }}">
                                                                    <i class="ri-eye-line"></i> View Details
                                                                </button>
                                                            </div>
                                                        </div>
                                @empty
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-clock-history display-4 d-block mb-3"></i>
                                        <p class="mb-0">No recent activity to show</p>
                                    </div>
                                @endforelse
                            </div>

                            <div
                                class="position-relative mx-4 my-4 d-flex justify-content-between align-items-center flex-wrap">
                                <!-- Left: Entries Info -->
                                <div class="entries-info text-muted small mb-2 mb-md-0">
                                    Showing 1 to 10 of 412 entries
                                </div>

                                <!-- Right: Pagination -->
                                <nav class="pagination-container">
                                    <ul class="pagination mb-0">
                                        <li class="page-item"><a class="page-link" href="#" data-page="prev">&lt;
                                                Previous</a></li>
                                        <li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>
                                        <li class="page-item disabled dots"><span class="page-link">...</span></li>
                                        <li class="page-item"><a class="page-link" href="#" data-page="46">46</a></li>
                                        <li class="page-item"><a class="page-link" href="#" data-page="47">47</a></li>
                                        <li class="page-item"><a class="page-link" href="#" data-page="48">48</a></li>
                                        <li class="page-item"><a class="page-link" href="#" data-page="49">49</a></li>
                                        <li class="page-item"><a class="page-link" href="#" data-page="50">50</a></li>
                                        <li class="page-item"><a class="page-link" href="#" data-page="next">Next &gt;</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
@endsection