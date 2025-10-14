@extends('admin.container')

@section('content')
    <style>
      .modal-backdrop {
        z-index: 0 !important;
        }

        .modal {
        z-index: 99999999 !important;
        }
    </style>
    <!-- DataTables Bootstrap 5 -->
    <link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" ></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" ></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js" defer></script>
    <div class="container">
        
        <div class="container vh-100 d-flex justify-content-center align-items-center">
            <div class="text-center">
            <!-- Example placeholder image -->
            <img src="https://cdn-icons-png.flaticon.com/512/4076/4076505.png" 
                alt="Empty State" 
                class="mb-4" 
                width="150">
            <h5 class="text-muted">You are all clean up! No pending payment as of the moment.</h5>
            </div>
        </div>
    </div>

    <div class="container py-5">
  <div class="text-center mb-5">
    <h2 class="fw-bold">💳 Payment Pages</h2>
    <p class="text-muted">Manage all payment transactions with clear status tracking</p>
  </div>

  <!-- Tabs Navigation -->
  <ul class="nav nav-pills justify-content-center mb-4 shadow-sm p-2 rounded bg-light" id="pills-tab" role="tablist">
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link active" id="pending-tab" data-bs-toggle="pill" data-bs-target="#pending" type="button" role="tab">
        ⏳ Pending
      </button>
    </li>
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link" id="verified-tab" data-bs-toggle="pill" data-bs-target="#verified" type="button" role="tab">
        ✅ Verified
      </button>
    </li>
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link" id="rejected-tab" data-bs-toggle="pill" data-bs-target="#rejected" type="button" role="tab">
        ❌ Rejected
      </button>
    </li>
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link" id="revision-tab" data-bs-toggle="pill" data-bs-target="#revision" type="button" role="tab">
        ✏️ For Revision
      </button>
    </li>
    <li class="nav-item px-2" role="presentation">
      <button class="nav-link" id="appeal-tab" data-bs-toggle="pill" data-bs-target="#appeal" type="button" role="tab">
        📩 For Appeal
      </button>
    </li>
  </ul>

  <!-- Tabs Content -->
  <div class="tab-content" id="pills-tabContent">

    <!-- Pending -->
    <div class="tab-pane fade show active" id="pending" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-warning"><i class="bi bi-hourglass-split"></i> Pending Transactions</h5>
          <div class="table-responsive">
            <table id="table_pending" class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Loan ID</th>
                  <th>Borrowers Name</th>
                  <th>Payment</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Verified -->
    <div class="tab-pane fade" id="verified" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-success"><i class="bi bi-check-circle-fill"></i> Verified Transactions</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Transaction ID</th>
                  <th>Borrower</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td><span class="fw-semibold">TXN002</span></td>
                  <td>Jane Smith</td>
                  <td>$300</td>
                  <td>2025-09-01</td>
                  <td><span class="badge bg-success px-3 py-2 rounded-pill">Verified</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Rejected -->
    <div class="tab-pane fade" id="rejected" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-danger"><i class="bi bi-x-circle-fill"></i> Rejected Transactions</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Transaction ID</th>
                  <th>Borrower</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td><span class="fw-semibold">TXN003</span></td>
                  <td>Michael Lee</td>
                  <td>$200</td>
                  <td>2025-09-01</td>
                  <td><span class="badge bg-danger px-3 py-2 rounded-pill">Rejected</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- For Revision -->
    <div class="tab-pane fade" id="revision" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-info"><i class="bi bi-pencil-square"></i> For Revision</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Transaction ID</th>
                  <th>Borrower</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td><span class="fw-semibold">TXN004</span></td>
                  <td>Sara Connor</td>
                  <td>$150</td>
                  <td>2025-09-01</td>
                  <td><span class="badge bg-info text-dark px-3 py-2 rounded-pill">For Revision</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- For Appeal -->
    <div class="tab-pane fade" id="appeal" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-primary"><i class="bi bi-envelope-fill"></i> For Appeal</h5>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Transaction ID</th>
                  <th>Borrower</th>
                  <th>Amount</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>1</td>
                  <td><span class="fw-semibold">TXN005</span></td>
                  <td>David Clark</td>
                  <td>$450</td>
                  <td>2025-09-01</td>
                  <td><span class="badge bg-primary px-3 py-2 rounded-pill">For Appeal</span></td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

  </div>
</div>



<!-- Modal -->
<div class="modal fade" id="loanModal" tabindex="-1" aria-labelledby="loanModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-centered">
    <div class="modal-content shadow-lg border-0 rounded-3">
      
      <!-- Header -->
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title fw-bold" id="loanModalLabel">Loan Information</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      
      <!-- Body -->
      <div class="modal-body">
        <!-- Tabs -->
        <ul class="nav nav-tabs mb-3" id="loanTabs" role="tablist">
          <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">Profile</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="loan-tab" data-bs-toggle="tab" data-bs-target="#loan" type="button" role="tab">Loan Details</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment" type="button" role="tab">Payment Details</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status" type="button" role="tab">Status History</button>
          </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content">
          
          <!-- Profile Tab -->
          <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-striped table-bordered align-middle">
                <tbody>
                  <tr>
                      <td>Name</td>
                      <td class="profile_name"></td>
                  </tr>
                  <tr>
                      <td>Email</td>
                      <td class="profile_email"></td>
                  </tr>
                  <tr>
                      <td>Phone</td>
                      <td class="profile_phone"></td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Loan Details Tab -->
          <div class="tab-pane fade" id="loan" role="tabpanel">
            <div class="container my-3">
              <h5 class="text-center mb-4 text-primary border-bottom pb-2">Loan Details</h5>
              <div class="row justify-content-center mb-3">
                <div class="col-10">
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Loan Amount:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_amount">₱50,000</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Loan Term:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_term">12 Months</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Payment Progress:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_progress">1 of 12</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Next Payment Due:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_due">Every 5th</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Monthly Payment:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_monthly">₱4,274.42</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Interest Rate:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_rate">5%</div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Late Payment Penalty:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_penalty">₱200</div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Details Tab -->
          <div class="tab-pane fade" id="payment" role="tabpanel">
            <div class="container">
              <h3 class="mb-4">Payment Details</h3> 
              <div class="table-responsive">
                <table class="table table-bordered table-striped align-middle">
                  <tbody>
                    <tr>
                      <th>Date of Payment</th>
                      <td class="pt_date"></td>
                    </tr>
                    <tr>
                      <th>Time of Payment</th>
                      <td class="pt_time"></td>
                    </tr>
                    <tr>
                      <th>Payment Type</th>
                      <td class="pt_type"></td>
                    </tr>
                    <tr>
                      <th>Month Coverage</th>
                      <td class="pt_coverage"></td>
                    </tr>
                    <tr>
                      <th>Amount Paid</th>
                      <td><strong class="pt_amount"></strong></td>
                    </tr>
                    <tr class="table-primary">
                      <th colspan="2" class="text-center">Breakdown</th>
                    </tr>
                    <tr>
                      <th>Interest</th>
                      <td class="pt_interest"></td>
                    </tr>
                    <tr>
                      <th>Principal</th>
                      <td class="pt_principal"></td>
                    </tr>
                    <tr>
                      <th>Penalty</th>
                      <td class="pt_penalty">₱0.00</td>
                    </tr>
                    <tr class="table-warning hidden">
                      <th>Outstanding Balance</th>
                      <td class="pt_outstanding"></td>
                    </tr>
                    <tr>
                      <th>Reference Number</th>
                      <td class="pt_ref"></td>
                    </tr>
                    <tr>
                      <th>Proof of Payment</th>
                      <td><button class="btn btn-sm btn-outline-primary">View File</button></td>
                    </tr>
                    <tr>
                      <th>Remarks</th>
                      <td class="pt_rem"></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Status History Tab -->
          <div class="tab-pane fade" id="status" role="tabpanel">
            <div class="table-responsive">
              <table class="table table-bordered align-middle">
                <thead class="table-light">
                  <tr>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Remarks</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>2025-07-01</td>
                    <td><span class="badge bg-primary">Approved</span></td>
                    <td>Initial approval granted.</td>
                  </tr>
                  <tr>
                    <td>2025-08-01</td>
                    <td><span class="badge bg-success">Verified</span></td>
                    <td>All documents checked.</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

        </div>
      </div>
      
      <!-- Footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-primary rounded-pill" id="pending_verify">Verify</button>
        <button type="button" class="btn btn-danger rounded-pill" id="pending_reject">Reject</button>
        <button type="button" class="btn btn-warning rounded-pill" id="pending_revision">Revision</button>
        <button type="button" class="btn btn-secondary rounded-pill" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>





<script>
function convertTo12HourFormat(time24) {
    var parts = time24.split(':');
    var hours = parseInt(parts[0], 10);
    var minutes = parts[1];
    var seconds = parts[2];
    
    var ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    hours = hours ? hours : 12; // 0 becomes 12

    return hours + ':' + minutes + ':' + seconds + ' ' + ampm;
}

function convertToReadableDate(dateStr) {
    var months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    var parts = dateStr.split("-");
    var year = parts[0];
    var month = parseInt(parts[1], 10) - 1; // Month is 0-indexed
    var day = parseInt(parts[2], 10);

    return months[month] + " " + day + ", " + year;
}


function formatMoney(amount) {
    return '₱' + amount
        .toFixed(2) // 2 decimals
        .replace(/\B(?=(\d{3})+(?!\d))/g, ","); // add commas
}

function convertdate(datetime){
  let date = new Date(datetime.replace(" ", "T"));
  let day = date.getDate();
  let month = date.toLocaleString('en-US', { month: 'long' });
  let suffix = (d => (d > 3 && d < 21) ? "th" : ["th","st","nd","rd"][d % 10] || "th")(day);
  let formatted = `${day}${suffix} of ${month}`;
  return formatted;
}




$(document).on('click', '#pending-tab', function () {
  $('#table_pending').DataTable().clear().destroy();
  $('#table_pending').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: "/get_pending",
    language: {
      search: "_INPUT_",
      searchPlaceholder: "Search users..."
    },
    columns: [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'type' },
      { data: 'action', orderable: false, searchable: false }
    ]
  });
});

$(document).on('click', '.pending_view', function () {
  var id = $(this).attr('data-id');
  var pay_id = $(this).attr('data-pay_id');

  //RESET TO 1st TAB
  $('#profile-tab').click()
  $('#payment-tab').attr('data-id',id).attr('data-pay_id',pay_id);

  $.ajax({
        url: '/get_pending_data',
        method: 'POST',
        data: {
            id : id,
            pay_id : pay_id,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          //PROFILE
          $('.profile_name').text(response.data.firstname + ' ' + response.data.lastname)
          $('.profile_email').text(response.data.email)
          $('.profile_phone').text(response.data.phone)
          
          let total = response.data.principal + response.data.interest;
          //LOAN DETAILS
          $('.loan_amount').text(response.loan_amount);
          $('.loan_term').text(response.data.loan_tenure + ' Month(s)');
          $('.loan_progress').text(response.due.count + ' Month(s)');
          $('.loan_due').text(convertdate(response.due.payment_date));
          $('.loan_monthly').text(formatMoney(total));
          $('.loan_rate').text((response.data.interest_rate * 100).toFixed(2) + "%");
          $('.loan_penalty').text(0);

        },
    });
  $('#loanModal').modal('show');
});


$(document).on('click', '#payment-tab', function () {
  var id = $(this).attr('data-id');
  var pay_id = $(this).attr('data-pay_id');
  

  $.ajax({
        url: '/get_pending_data_two',
        method: 'POST',
        data: {
            id : id,
            pay_id : pay_id,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

          var principal = 0;
          $.each(response.data.loan_tenure , function( k , v ){
              principal += v.principal;
          });

          var interest = 0;
          $.each(response.data.loan_tenure_interest , function( k , v ){
              interest += v.interest;
          });

          let [datePart, timePart] = (response.data.created_at).split(" ");
          $('.pt_date').text(convertToReadableDate(datePart));
          $('.pt_time').text( convertTo12HourFormat( timePart ) );
          $('.pt_type').text(response.data.type);

          if (response.data.payment_type_id === 2) {
            $('.pt_outstanding').closest('tr').removeClass('hidden')
          }else{
            $('.pt_outstanding').closest('tr').addClass('hidden')
          }

          $('.pt_amount').text(formatMoney(response.data.amount_sent));
          $('.pt_principal').text(formatMoney(principal));
          $('.pt_interest').text(formatMoney(interest));
          $('.pt_outstanding').text(formatMoney(response.data.total_balance));
          $('.pt_ref').text(response.data.reference_code);
          $('.pt_rem').text(response.data.remarks);

          
        },
    });
});




$(document).on('click', '#pending_verify', function () {
  $.confirm({
      title: 'Confirm Approval',
      content: 'Are you sure you want to verify this payment?<br><small style="color:red">This action cannot be undone.</small>',
      type: 'green',
      buttons: {
          confirm: {
              text: 'Yes, Approve',
              btnClass: 'btn-success',
              action: function () {
                  // $.ajax({
                  //     url: '/admin/loan-request/loan/' + loan_id + '/reject',
                  //     method: 'POST',
                  //     data: {
                  //         admin_id: admin_id
                  //     },
                  //     success: function (response) {
                  //         $.alert({
                  //             title: 'Success',
                  //             content: 'The loan request has been rejected successfully.',
                  //             type: 'green'
                  //         });
                  //     }
                  // });
              }
          },
          cancel: {
              text: 'Cancel',
              btnClass: 'btn-secondary'
          }
      }
  });
});


$(document).on('click', '#pending_reject', function () {

  var content = `
    <form id="rejectForm" enctype="multipart/form-data">

          <!-- Reason for rejection -->
          <div class="form-group">
            <label for="reason">Reason for Rejection</label>
            <textarea id="reason" name="reason" class="form-control" rows="3" required></textarea>
          </div>

          <!-- Image file capture -->
          <div class="form-group">
            <label for="imageFile">Attach Image (optional)</label>
            <input type="file" id="imageFile" name="imageFile" accept="image/*" capture="camera" class="form-control">
          </div>

          <!-- Internal remarks -->
          <div class="form-group">
            <label for="remarks">Internal remarks</label>
            <input type="text" id="remarks" name="remarks" class="form-control">
          </div>

        </form>
    `;
  $.confirm({
      title: 'Confirm Rejection',
      content: content,
      type: 'red',
      buttons: {
          confirm: {
              text: 'Yes, Reject',
              btnClass: 'btn-danger',
              action: function () {
                  // $.ajax({
                  //     url: '/admin/loan-request/loan/' + loan_id + '/reject',
                  //     method: 'POST',
                  //     data: {
                  //         admin_id: admin_id
                  //     },
                  //     success: function (response) {
                  //         $.alert({
                  //             title: 'Success',
                  //             content: 'The loan request has been rejected successfully.',
                  //             type: 'green'
                  //         });
                  //     }
                  // });
              }
          },
          cancel: {
              text: 'Cancel',
              btnClass: 'btn-secondary'
          }
      }
  });
});


$(document).on('click', '#pending_revision', function () {

var content = `
    <form id="revisionForm">
        <!-- Reason for revision -->
        <div class="form-group">
          <label for="reasonRevision">Reason for revision</label>
          <textarea id="reasonRevision" name="reasonRevision" class="form-control" rows="3" required></textarea>
        </div>

        <!-- Instructions -->
        <div class="form-group">
          <label for="instructions">Instructions</label>
          <textarea id="instructions" name="instructions" class="form-control" rows="3"></textarea>
        </div>

        <!-- Internal remarks -->
        <div class="form-group">
          <label for="revisionRemarks">Internal remarks</label>
          <input type="text" id="revisionRemarks" name="revisionRemarks" class="form-control">
          <p class="help-block">These remarks will reflect on the revision page &gt; Status History.</p>
        </div>

      </form>
  `;
  $.confirm({
      title: 'Confirm Revision',
      content: content,
      type: 'orange',
      buttons: {
          confirm: {
              text: 'Yes, Revision',
              btnClass: 'btn-warning',
              action: function () {
                  // $.ajax({
                  //     url: '/admin/loan-request/loan/' + loan_id + '/reject',
                  //     method: 'POST',
                  //     data: {
                  //         admin_id: admin_id
                  //     },
                  //     success: function (response) {
                  //         $.alert({
                  //             title: 'Success',
                  //             content: 'The loan request has been rejected successfully.',
                  //             type: 'green'
                  //         });
                  //     }
                  // });
              }
          },
          cancel: {
              text: 'Cancel',
              btnClass: 'btn-secondary'
          }
      }
  });
});














  </script>

@endsection