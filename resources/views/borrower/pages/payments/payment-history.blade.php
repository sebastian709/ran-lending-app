@extends('borrower.app')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/payment.css') }}">
@endsection

@section('content')
<div class="d-flex">
    @include('borrower.layouts.sidebar')

    <div class="container py-4">
        <h2 class="text-center fw-bold mb-4">Payment History</h2>

        @php
            $statusColor = [
                'For Verification' => 'status-pending',   // Gray
                'For Correction'   => 'status-revision',  // Orange
                'Verified'         => 'status-success',   // Green
                'Rejected'         => 'status-failed',    // Dark Red
                'For Revision'     => 'status-warning',   // Yellow/Amber
                'For Appeal'       => 'status-appeal',    // Blue
            ];

            $textColor = [
                'For Verification' => 'text-secondary', // Gray text
                'For Correction'   => 'text-warning',   // Orange/amber text
                'Verified'         => 'text-success',   // Green text
                'Rejected'         => 'text-danger',    // Red text
                'For Revision'     => 'text-warning',   // Yellow/amber text
                'For Appeal'       => 'text-primary',   // Blue text
            ];
        @endphp

        <div class="accordion" id="paymentAccordion">
            @foreach ($paymentHistory as $index => $payment)
                @php 
                    $collapseId = "payment-" . ($index + 1);
                    $itemClass = $statusColor[$payment['status']] ?? 'status-pending';
                @endphp

                {{-- PAYMENT ITEM --}}
                <div data-tenure-id="{{ $payment['tenure_id'] }}" data-payment-id="{{ $payment['payment_id'] }}" class="payment-item {{ $itemClass }}" data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}">
                    <div class="d-flex justify-content-between align-items-center">
                        
                        <div class="d-flex align-items-center">
                            {{-- Status Badge --}}
                            <span class="status-badge me-2">{{ $payment['status'] }}</span>

                            <div>
                                <strong class="me-2">&#8369;{{ number_format($payment['amount_due'], 2) }}</strong>
                                <span class="text-muted">{{ $payment['month_coverage'] }} - {{ $payment['payment_category'] }}</span>
                            </div>
                        </div>

                        <i class="ri-arrow-right-s-line arrow-icon"></i>
                    </div>
                </div>

                {{-- COLLAPSIBLE DETAILS --}}
                <div id="{{ $collapseId }}" class="collapse" data-bs-parent="#paymentAccordion">
                    <div class="payment-details p-3 border rounded mb-3">
                        {{-- Status + Reference --}}
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <strong>Status:</strong> 
                                <span class="{{ $textColor[$payment['status']] ?? 'text-secondary' }}">{{ $payment['status'] }}</span>
                            </div>
                            <div class="col-md-6">
                                <strong>Reference:</strong> {{ $payment['reference_number'] ?? 'N/A' }}
                            </div>
                        </div>

                        {{-- Breakdown --}}
                        <div class="row mb-2">
                            <div class="col-md-3"><strong>Monthly Due:</strong> &#8369;{{ number_format($payment['breakdown']['monthly_due'], 2) }}</div>
                            <div class="col-md-3"><strong>Interest:</strong> &#8369;{{ number_format($payment['breakdown']['interest'], 2) }}</div>
                            <div class="col-md-3"><strong>Penalty:</strong> &#8369;{{ number_format($payment['breakdown']['penalty'], 2) }}</div>
                            <div class="col-md-3"><strong>Principal:</strong> &#8369;{{ number_format($payment['breakdown']['principal'], 2) }}</div>
                        </div>

                        <div class="mb-2"><strong>Amount Paid:</strong> &#8369;{{ number_format($payment['amount_paid'], 2) }}</div>

                        {{-- Attachment --}}
                        <div class="mb-2">
                            <strong>Attachment:</strong><br>
                            @if ($payment['attachment'])
                                <a href="{{ asset('storage/'.$payment['attachment']) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$payment['attachment']) }}" class="attachment-img mt-2" style="max-width:300px;">
                                </a>
                            @else
                                <span class="text-muted fst-italic">No attachment provided</span>
                            @endif
                        </div>

                        <div class="mb-3"><strong>Remarks:</strong> {{ $payment['remarks'] ?? 'None' }}</div>

                        {{-- Comments --}}
                        <!-- <div class="mb-3">
                            <strong>Comments:</strong>
                            @forelse ($payment['comments'] as $comment)
                                <div class="comment-box mb-2 p-2 border rounded">
                                    <div class="comment-author fw-bold">{{ $comment['author'] }}:</div>
                                    <div>{{ $comment['message'] }}</div>
                                    <small class="text-muted">{{ $comment['timestamp'] }}</small>
                                </div>
                            @empty
                                <div class="text-muted fst-italic">No comments yet.</div>
                            @endforelse
                        </div> -->

                        {{-- Comments --}}
                        <div class="mb-3">
                            <strong>Comments:</strong>
                            <div class="comments-container">
                                <div class="text-muted fst-italic">Click accordion to load comments...</div>
                            </div>
                        </div>
                        {{-- Add Comment --}}
                       
                        <div class="mb-3">
                            <label class="form-label fw-bold">Your Comment</label>
                            <textarea class="form-control" rows="3" placeholder="Enter your comment..."></textarea>
                        </div>
                        <button type="button" class="btn btn-primary btn-submit-comment" data-tenure-id="{{ $payment['tenure_id'] }}" data-payment-id="{{ $payment['payment_id'] }}">Submit Comment</button>

                        
                    </div>
                </div>

            @endforeach
        </div>

    </div>
</div>

<script src="{{ asset('js/payment.js') }}"></script>
@endsection
