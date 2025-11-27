@extends('admin.container')

@section('content')
<div class="container">
    <div class="card shadow border-0 rounded-4">
        <div class="card-header bg-primary text-white rounded-top-4 d-flex align-items-center">
            <i class="ri-file-warning-line me-2"></i>
            <h5 class="mb-0">Appeal Requests</h5>
        </div>
        <div class="card-body p-4">
            @if($appeals->isEmpty())
                <div class="alert alert-info text-center">
                    <i class="ri-information-line me-1"></i> No appeal requests found.
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Loan ID</th>
                                <th>Borrower</th>
                                <th>Reason</th>
                                <th>Date of Appeal</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($appeals as $appeal)
                                <tr>
                                    <td><span class="badge bg-secondary">LN-{{ str_pad($appeal->loan_id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                    <td>{{ $appeal->borrower_name }}</td>
                                    <td>{{ $appeal->reason }}</td>
                                    <td>{{ $appeal->formatted_date }}</td>
                                    <td>
                                        <span class="badge {{ $appeal->receive_status == 0 ? 'bg-danger' : 'bg-success' }}">
                                            {{ $appeal->receive_status == 0 ? 'Pending' : 'Received' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button 
                                            class="btn btn-sm btn-outline-primary view-appeal" 
                                            data-url="{{ '/admin/view-appeal/' . $appeal->id }}">
                                            <i class="ri-eye-line"></i> View Appeal
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Remix Icon CDN -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css" rel="stylesheet">
@endsection
