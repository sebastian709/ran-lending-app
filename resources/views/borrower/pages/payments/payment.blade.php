@extends('borrower.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
 <link rel="stylesheet" href="css/payment.css">
<style>
   
</style>
@endsection

@section('content')
<div class="d-flex">
    @include('borrower.layouts.sidebar')

    <div class="container container-payment">
        <!-- <button type="button" data-url="/profile" class="btn small  start-0 top-50 translate-middle-y p-0">
            <i class="ri-home-line me-2"></i> Back to home
        </button> -->
        <h2 class="header-title">Repayment</h2>

        <!-- PAYMENT TYPE -->
        <div class="card-section">
            <span class="section-title">Total Amount to Pay</span>
            <div class="amount-display" id="totalAmount">₱ {{ number_format($data['loan_tenure_next_pay']->total,2) }}</div>
        </div>

        <!-- NEXT PAYMENT -->
        <div class="card-section">
            <span class="section-title">Next Payment Due</span>
            <div class="list-group next_payment">
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option position-relative">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0 due_payment" type="checkbox" data-amount="{{ $data['loan_tenure_next_pay']->total }}" checked disabled>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Due Date: {{ \Carbon\Carbon::parse($data['loan_tenure_next_pay']->date)->format('F j, Y') }}</span>
                            <small class="text-muted">1/12 Repayment</small>
                        </div>
                    </div>
                     <div class="d-flex align-items-center gap-2">
                        <div class="fw-bold text-success">₱ {{ number_format($data['loan_tenure_next_pay']->total,2) }}</div>
                        <!-- <span class="badge bg-danger">Delay</span> -->
                    </div>
                </label>
            </div>
            <button class="btn btn-sm btn-outline-primary pay-partial-btn">Pay Partial</button>
        </div>


        <!-- PARTIAL PAYMENT -->
        <div class="card-section partial_section d-none">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="section-title">Partial Payment</span>
                <button type="button" class="btn-close close-partial" aria-label="Close"></button>
            </div>

            <div class="list-group partial-months">
                <!-- Interest -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-partial partial-option">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0 partial-interest" type="checkbox" data-amount="{{ $data['loan_tenure_next_pay']->interest }}" data-category="Interest">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Interest</span>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($data['loan_tenure_next_pay']->date)->format('F j, Y') }}</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱ {{ number_format($data['loan_tenure_next_pay']->interest,2) }}</div>
                </label>
                <!-- Penalty -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-partial partial-option">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0 partial-penalty" type="checkbox" data-amount="{{ $data['loan_tenure_next_pay']->penalty }}" data-category="Penalty">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Penalty</span>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($data['loan_tenure_next_pay']->date)->format('F j, Y') }}</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱ {{ number_format($data['loan_tenure_next_pay']->penalty,2) }}</div>
                </label>
                <!-- Principal -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-partial partial-option">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0 partial-principal" type="checkbox" data-amount="{{ $data['loan_tenure_next_pay']->principal }}" data-category="Principal">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Principal</span>
                            <small class="text-muted">{{ \Carbon\Carbon::parse($data['loan_tenure_next_pay']->date)->format('F j, Y') }}</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱ {{ number_format($data['loan_tenure_next_pay']->principal,2) }}</div>
                </label>
            </div>
        </div>


        <!-- ADVANCE PAYMENT -->
        <div class="card-section advance_section">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="section-title">Advance Payment</span>
                <button id="toggleAdvance" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>

            <div class="list-group advance-months" style="display: none;">
                <!-- Month -->
            @foreach($data['records']['loan'] as $record)
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option payment-option-advance">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0 advance_payment_month" type="checkbox" data-tenure_id="{{ $record['tenure_id'] }}" data-amount="{{ $record['interest'] + $record['principal'] }}" data-principal="{{ $record['principal'] }}" data-interest="{{ $record['interest'] }}">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Due Date: {{ \Carbon\Carbon::parse(is_array($record) ? $record['date'] : $record->date)->format('F j, Y') }}</span>
                            <small class="text-muted">2/12 Repayment</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱ {{ number_format($record['interest'] + $record['principal'],2) }}</div>
                </label>
                @endforeach
                <!-- All -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input" type="checkbox" id="payAllCheck">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">All</span>
                        </div>
                    </div>
                </label>
            </div>
        </div>


        <!-- PAYMENT BREAKDOWN -->
        <div class="card-section">
            <span class="section-title">Payment Breakdown</span>
            <ul class="breakdown-list" id="breakdownBody">
                <li class="d-flex justify-content-between">
                    <span>Interest</span>
                    <span class="payment-interest">₱ {{ number_format($data['loan_tenure_next_pay']->interest,2) }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Penalty</span>
                    <span class="payment-penalty">₱ {{ number_format($data['loan_tenure_next_pay']->penalty,2) }}</span>
                </li>
                <li class="d-flex justify-content-between">
                    <span>Principal</span>
                    <span class="payment-principal">₱ {{ number_format($data['loan_tenure_next_pay']->principal,2) }}</span>
                </li>
                <li class="d-flex justify-content-between fw-bold border-top mt-2 pt-2 text-muted">
                    <span>Total</span>
                    <span class="payment-total">₱ {{ number_format($data['loan_tenure_next_pay']->total,2) }}</span>
                </li>
            </ul>
        </div>

        <!-- PARTIAL PAYMENTS -->
        <div class="card-section d-none">
            <div id="partialSummary" class="border rounded p-3 bg-white">
                <strong>Category:</strong> <span id="summaryCategory">Interest</span><br>
                <strong>Total Amount:</strong> ₱<span id="summaryAmount">0.00</span><br>
                <strong>Covered Months:</strong> <span id="summaryMonths">0</span>
            </div>
        </div>

        <!-- PAY NOW BUTTON -->
        <button class="btn btn-primary w-100 loan_payment_confirm"  id="payNowBtn" data-urlssss="/confirm-payment">Pay Now</button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/payment.js') }}"></script>


@endsection
