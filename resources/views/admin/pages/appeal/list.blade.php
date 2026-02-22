@extends('admin.container')

@section('content')
<div class="container-fluid p-4 appeal-page">
    <div class="section-title d-flex align-items-center">
        <i class="ri-file-warning-line me-2"></i> Appeal Requests
    </div>

    <div class="table-card">
        <div class="table-header d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">Appeal Inbox</h3>
                <p class="mb-0">Review borrower appeal submissions and mark them as received.</p>
            </div>
            <span class="badge text-bg-light border">Total: {{ $appeals->count() }}</span>
        </div>

        <div class="card-body p-0">
            @if($appeals->isEmpty())
                <div class="appeal-empty-state text-center py-5">
                    <i class="ri-inbox-line fs-2 d-block mb-2 text-muted"></i>
                    <p class="mb-0 text-muted">No appeal requests found.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table id="appealTable" class="table table-hover align-middle mb-0">
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
                                    <td><span class="badge bg-secondary-subtle text-secondary-emphasis border">LN-{{ str_pad($appeal->loan_id, 5, '0', STR_PAD_LEFT) }}</span></td>
                                    <td>{{ $appeal->borrower_name }}</td>
                                    <td class="appeal-reason-cell">{{ $appeal->reason }}</td>
                                    <td>{{ $appeal->formatted_date }}</td>
                                    <td>
                                        <span class="badge {{ $appeal->receive_status == 0 ? 'bg-warning-subtle text-warning-emphasis border' : 'bg-success-subtle text-success-emphasis border' }}">
                                            {{ $appeal->receive_status == 0 ? 'Pending' : 'Received' }}
                                        </span>
                                    </td>
                                    <td>
                                        <button 
                                            class="btn btn-sm btn-outline-primary-custom view-appeal" 
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
@endsection
