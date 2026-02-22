@extends('borrower.app')

@section('styles')
    
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

            <!-- Sample Table with status -->
            <div class="dashboard-card p-4 shadow-sm rounded bg-white">
                <h4 class="mb-4 fw-bold ms-2 ">Repayment Schedule</h4>
                <div class="table-responsive ">
                <table class="table table-responsive  align-middle text-center">
                    <thead class="table-light">
                    <tr>
                        <th scope="col">Due Date</th>
                        <th scope="col">Amount Due</th>
                        <th scope="col">Payment Status</th>
                        <th scope="col">Paid Date</th>
                        <th scope="col">Late Fee</th>
                        <th scope="col">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $shownPayLink = false; @endphp
                    @foreach($results as $index => $data)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($data->date)->format('F j, Y') }}</td>
                            <td>&#8369; {{ number_format($data->total,2) }}</td>
                            <td>
                                @if($data->verification === 1)
                                    <span class="badge bg-primary">For Verification</span>
                                @else
                                    @if($data->partial === 'p')
                                        <span class="badge bg-primary">Partial</span>
                                    @else
                                        @if($data->payment_status == 1)
                                            <span class="badge bg-success">Paid</span>
                                        @elseif($data->payment_status == 2)
                                            <span class="badge bg-info text-dark">Upcoming</span>
                                        @else
                                            -
                                        @endif
                                    @endif
                                @endif
                            </td>
                            <td>{{ $data->paid_date }}</td>
                            <td>&#8369; {{ number_format($data->penalty,2) }}</td>
                            <td>
                                @if($data->payment_status == 2  && !$shownPayLink)
                                    <a href="payment" class="btn btn-sm btn-outline-primary">Pay</a>
                                    @php $shownPayLink = true; @endphp
                                @endif
                                
                            </td>
                        </tr>
                    @endforeach

                    </tr>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

