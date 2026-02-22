@extends('borrower.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/payment.css') }}">
    <style>
        .loan-status-card {
            max-width: 600px;
            margin: 0 auto;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.05);
            background: #fff;
        }

        .loan-status-icon {
            font-size: 80px;
        }

        .loan-status-card h2 {
            font-weight: 700;
        }

        .loan-status-card p {
            font-size: 1.1rem;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex">
        @include('borrower.layouts.sidebar')
        <div class="main-content flex-grow-1">
            <div class="container-fluid p-4">
                <!-- <input type="text" value="{{ $loanStatus }}"> -->
                @if ($loanStatus == 999 || $loanStatus == 0 || $loanStatus == 7 )
                    {{-- No Active Loan --}}
                    <div class="loan-status-card text-center text-primary mb-5">
                        <div class="loan-status-icon mb-4">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h2 class="mb-3">Looks like you don't have a loan yet</h2>
                        <p class="text-muted mb-4">
                            Once you apply and your loan is approved, all your details will show up here.<br>
                            Need help getting started?
                        </p>
                        <a href="{{ url('apply-loan') }}" class="btn btn-primary btn-lg">
                            Apply a Loan
                        </a>
                    </div>

                @elseif ($loanStatus == 1 || $loanStatus == 2)
                    {{-- Pending Loan --}}
                    <div class="loan-status-card text-center text-warning mb-5">
                        <div class="loan-status-icon mb-4">
                            <i class="bi bi-hourglass-split"></i>
                        </div>
                        <h2 class="mb-3">Your loan application is under review</h2>
                        <p class="text-muted">
                            We're reviewing your application. You'll be notified once it's approved.<br>
                            You can check the status in your <a href="{{ url('my-loan') }}">My Loan</a> section page.
                        </p>
                    </div>

                @elseif ($loanStatus == 6)
                    {{-- Rejected Loan --}}
                    <div class="loan-status-card text-center text-danger mb-5">
                        <div class="loan-status-icon mb-4">
                            <i class="bi bi-x-circle"></i>
                        </div>
                        <h2 class="mb-3">No Active Loans</h2>
                        <p class="text-muted">
                            You have no current loans. If you've applied recently and haven't seen an update,<br>
                            please check your loan history in Profile page and find <br> <a href="{{ url('loan-list') }}">Loan History</a> button.
                        </p>
                    </div>

                @elseif ($loanStatus == 4)
                    {{-- Approved Loan --}}
                    <div class="loan-status-card text-center text-success mb-5">
                        <div class="loan-status-icon mb-4">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h2>Approved</h2>
                        <h5 class="mb-3 text-muted">Waiting for Disbursement</h5>
                        <p class="text-muted">
                            Your loan application has been approved. Please wait - you will be notified once the funds have been
                            transferred and processed.
                        </p>
                    </div>
                @endif

            </div>
        </div>
    </div>
@endsection
