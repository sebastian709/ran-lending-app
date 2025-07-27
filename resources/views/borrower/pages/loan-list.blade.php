@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

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
                            <h4 class="mb-4 fw-semibold text-dark m-4 fw-bold">
                                Loan List
                            </h4>

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
                                                </div class="me-3">
                                                <div><strong>Remarks:</strong> {{ $loan->remarks ?? '–' }}</div>
                                            </div>
                                        </div>

                                        <div class="mt-3 mt-md-0">
                                            <button class="btn btn-outline-dark btn-sm view-loan-btn"
                                                data-id="LN-{{ str_pad($loan->id, 8, '0', STR_PAD_LEFT) }}">
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
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @stack('scripts')
@endsection