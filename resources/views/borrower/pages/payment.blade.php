@extends('borrower.app')

@section('styles')
<link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

<style>
    .page-wrapper { display: flex; min-height: 100vh; }
    .container-payment { flex-grow: 1; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); margin: 30px auto; max-width: 1100px; }
    .header-title { font-size: 1.8rem; font-weight: bold; margin-bottom: 25px; color: #333; text-align: center; }
    .section-title { font-size: 1.3rem; font-weight: 600; margin-bottom: 15px; color: #0056b3; display: block; }
    .amount-display { font-size: 2rem; font-weight: bold; color: #198754; margin-bottom: 20px; }
    .card-section { background: #f9fafc; padding: 20px; border-radius: 10px; margin-bottom: 20px; }
    .btn-primary { background-color: #0056b3; border: none; font-size: 1.1rem; padding: 12px; border-radius: 8px; }
    .btn-primary:hover { background-color: #00409b; }
    /* List Group Styling */
    .list-group-item {
        border-radius: 8px;
        border: 1px solid #dee2e6;
        padding: 15px 20px;
        transition: background 0.3s ease, box-shadow 0.3s ease;
        cursor: pointer;
    }
    .list-group-item:hover {
        background: #f8f9fa;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }
    .list-group-item input {
        pointer-events: none; /* Prevent direct click on checkbox (handled by label click) */
    }
</style>
@endsection

@section('content')
<div class="d-flex">
    @include('borrower.layouts.sidebar')

    <div class="container container-payment">
        
        <h2 class="header-title">Loan Payment</h2>

        <!-- PAYMENT TYPE -->
        <div class="card-section">
            <label class="form-label">Payment Type</label>
            <select id="paymentType" class="form-select mb-3">
                <option value="monthly" selected>Monthly</option>
                <option value="full">Fully Paid</option>
            </select>

            <span class="section-title">Total Amount to Pay</span>
            <div class="amount-display" id="totalAmount">₱0.00</div>
        </div>

        <!-- NEXT PAYMENT -->
        <div class="card-section">
            <span class="section-title">Next Payment Due</span>
            <div class="list-group">
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0" type="checkbox" data-amount="5000" checked disabled>
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Due Date: 20 July 2025</span>
                            <small class="text-muted">1/12 Repayment</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱5,000</div>
                </label>
            </div>
        </div>

        <!-- ADVANCE PAYMENT -->
        <div class="card-section">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <span class="section-title">Advance Payment</span>
                <button id="toggleAdvance" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-chevron-down"></i>
                </button>
            </div>

            <div class="list-group advance-months" style="display: none;">
                <!-- Month 1 -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0" type="checkbox" data-amount="5000">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Due Date: 20 Aug 2025</span>
                            <small class="text-muted">2/12 Repayment</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱5,000</div>
                </label>
                <!-- Month 2 -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0" type="checkbox" data-amount="5000">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Due Date: 20 Sep 2025</span>
                            <small class="text-muted">3/12 Repayment</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱5,000</div>
                </label>
                <!-- Month 3 -->
                <label class="list-group-item d-flex align-items-center justify-content-between gap-3 mb-2 payment-option">
                    <div class="d-flex align-items-center gap-3">
                        <input class="form-check-input flex-shrink-0" type="checkbox" data-amount="5000">
                        <div class="d-flex flex-column">
                            <span class="fw-semibold">Due Date: 20 Oct 2025</span>
                            <small class="text-muted">4/12 Repayment</small>
                        </div>
                    </div>
                    <div class="fw-bold text-success">₱5,000</div>
                </label>
            </div>
        </div>


        <!-- PAYMENT BREAKDOWN -->
        <div class="card-section">
            <span class="section-title">Payment Breakdown</span>
            <ul class="breakdown-list" id="breakdownBody">
                <li class="text-center text-muted">No data available</li>
            </ul>
        </div>

        <!-- PARTIAL PAYMENTS -->
        <div class="card-section">
           
            <div id="partialSummary" class="border rounded p-3 bg-white">
                <strong>Category:</strong> <span id="summaryCategory">Interest</span><br>
                <strong>Total Amount:</strong> ₱<span id="summaryAmount">0.00</span><br>
                <strong>Covered Months:</strong> <span id="summaryMonths">0</span>
            </div>
        </div>

        <!-- PAY NOW BUTTON -->
        <button class="btn btn-primary w-100" id="payNowBtn">Pay Now</button>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function updateTotal() {
        let total = 0;
        $('.payment-option input[type="checkbox"]:checked').each(function() {
            total += parseFloat($(this).data('amount'));
        });
        $('#totalAmount').text('₱' + total.toLocaleString());
    }

    // Auto-check previous months when checking one
    $('.payment-option input[type="checkbox"]').on('change', function () {
        let index = $('.payment-option input[type="checkbox"]').index(this);

        if ($(this).is(':checked')) {
            $('.payment-option input[type="checkbox"]').slice(0, index + 1).prop('checked', true);
        } else {
            $('.payment-option input[type="checkbox"]').slice(index + 1).prop('checked', false);
        }

        updateTotal();
    });

    // Handle Payment Type change
    $('#paymentType').on('change', function () {
        if ($(this).val() === 'full') {
            $('.payment-option input[type="checkbox"]').prop('checked', true);
        } else {
            $('.payment-option input[type="checkbox"]').prop('checked', false);
        }
        updateTotal();
});

$('#toggleAdvance').on('click', function() {
    $('.advance-months').slideToggle();
    $(this).find('i').toggleClass('bi-chevron-down bi-chevron-up');
});
$(document).ready(function () {
    updateTotal(); // Show initial total when page loads
});
</script>


@endsection
