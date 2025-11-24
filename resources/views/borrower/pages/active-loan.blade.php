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
            <!-- Welcome Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="dashboard-card p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex ">
                                <h1 class="h3 fw-bold mb-0 me-2">Good Day,</h1>
                                <h1 class="h3 fw-bold mb-0 welcome-text">{{ Auth::user()->firstname }}!</h1>
                            </div>

                            <div class="d-none d-md-block">
                                <div class="text-end">
                                    <div class="small text-muted">Today</div>
                                    <div class="fw-medium" id="current-date"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cards -->
            <div class="row mb-4">
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-primary-custom mb-2">
                            <i class="ri-bank-card-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">₱ {{ number_format($data->total_all_raw,2) }}</div>
                        <div class="small text-muted">Total Loan Amount</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-success mb-2">
                            <i class="ri-calendar-schedule-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">{{ $data->months }} Months</div>
                        <div class="small text-muted">Loan Tenure</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-warning mb-2">
                            <i class="ri-wallet-3-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">₱ {{ number_format($data->raw_total,2) }}</div>
                        <div class="small text-muted">Monthly Due Amount</div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-6 mb-3">
                    <div class="dashboard-card p-3 text-center">
                        <div class="text-info mb-2">
                            <i class="ri-calendar-event-line" style="font-size: 2rem;"></i>
                        </div>
                        <div class="h4 fw-bold mb-1">Every {{ \Carbon\Carbon::parse($data->date)->day . (\Carbon\Carbon::parse($data->date)->day % 100 >= 11 && \Carbon\Carbon::parse($data->date)->day % 100 <= 13 ? 'th' : ['th','st','nd','rd','th','th','th','th','th','th'][\Carbon\Carbon::parse($data->date)->day % 10]) }}                         of the month</div>
                        <div class="small text-muted">Monthly Due Date</div>
                    </div>
                </div>
            </div>

           <!-- Upcoming Payment Box -->
            <div class="dashboard-card shadow-sm rounded bg-white mb-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap p-4">
                    <div class="d-flex align-items-center mb-2 mb-md-0">
                        <!-- <i class="ri-alarm-warning-line text-primary" style="font-size: 2rem;"></i> -->
                        <div class="ms-3">
                            <h5 class="fw-bold mb-0">
                                Next Payment:
                                <h4 class="fw-bold text-primary mb-1">
                                @if(!$nextPayment && $isnew === 0)
                                No Due Date Yet
                                @elseif(!$nextPayment && $isnew > 0)
                                Current Month is Already Paid
                                @else
                                    ₱ {{ number_format($nextPayment->total_all, 2) }}
                                @endif
                                </h4>
                            </h5>
                            @if($nextPayment)
                            <small class="text-muted">Due on <strong>{{ \Carbon\Carbon::parse($nextPayment->date)->format('F j, Y') }}</strong></small>
                            @endif
                            </div>
                    </div>
                    <div class="text-end">
                        <a href="/payment" class="btn btn-primary btn-md">
                            <i class="ri-wallet-line me-1"></i> 
                                @if(!$nextPayment)
                                Pay Advanced
                            @else
                                Pay Now
                            @endif
                        </a>
                    </div>
                </div>

                <!-- Repayment Schedule Button -->
                <div class="mt-3 text-center bg-light p-2">
                    <a href="/repayment-schedule" class="btn rep_btn">
                        <i class="ri-calendar-schedule-line me-1"></i>Repayment Schedule<i class="ri-arrow-right-line ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Congrats Modal -->
<div class="modal fade" id="congratsModal" tabindex="-1" aria-labelledby="congratsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content text-center p-5" style="border-radius:20px;">
      <div class="modal-body">
        <h1 class="mb-4" style="color:#28a745; font-weight:bold; font-size:2.5rem;">
            Congratulations!
        </h1>
        <p class="mb-4" style="font-size:1.3rem; font-weight:500;">
          Your loan has been successfully transferred.
        </p>
        <p class="mb-4" style="font-size:1.1rem;">
        Having any trouble? 
        <a href="javascript:void(0)" id="makeAppealBtn" class="fw-bold text-primary">Make an appeal</a>.
        </p>

        <!-- Do not show again checkbox -->
        <div class="form-check mb-4 d-flex justify-content-center">
          <input class="form-check-input me-2" type="checkbox" value="" id="dontShowCongrats">
          <label class="form-check-label" for="dontShowCongrats" style="font-size:1rem;">
            Do not show this again
          </label>
        </div>

        <button type="button" class="btn btn-success btn-lg px-5 appeal_close" data-bs-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
@endsection
