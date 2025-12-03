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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css">

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js" defer></script>
    <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js" defer></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.css" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>


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
    <!-- <li class="nav-item px-2" role="presentation">
      <button class="nav-link" id="revision-tab" data-bs-toggle="pill" data-bs-target="#revision" type="button" role="tab">
        ✏️ For Revision
      </button>
    </li> -->
  </ul>

  <!-- Tabs Content -->
  <div class="tab-content" id="pills-tabContent">

    <!-- Pending -->
    <div class="tab-pane fade show active" id="pending" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-warning"><i class="bi bi-hourglass-split"></i> Pending Transactions</h5>
          <div style="width:100%" class="table-responsive">
            <table style="width:100%" id="table_pending" class="table table-hover align-middle">
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
          <div style="width:100%" class="table-responsive">
            <table style="width:100%" id="table_verified" class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Loan ID</th>
                  <th>Borrowers Name</th>
                  <th>Payment Date</th>
                  <th>Verification Date</th>
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

    <!-- Rejected -->
    <div class="tab-pane fade" id="rejected" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-danger"><i class="bi bi-x-circle-fill"></i> Rejected Transactions</h5>
          <div style="width:100%" class="table-responsive">
            <table style="width:100%" id="table_rejected" class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Loan ID</th>
                  <th>Borrowers Name</th>
                  <th>Submission Date</th>
                  <th>Rejection Date</th>
                  <th>Reference Number</th>
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

    <!-- For Revision -->
    <!-- <div class="tab-pane fade" id="revision" role="tabpanel">
      <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body">
          <h5 class="mb-3 text-info"><i class="bi bi-pencil-square"></i> For Revision</h5>
          <div style="width:100%" class="table-responsive">
            <table style="width:100%" id="table_revision" class="table table-hover align-middle">
              <thead class="table-light">
                <tr>
                  <th>Loan ID</th>
                  <th>Borrowers Name</th>
                  <th>Submission Date</th>
                  <th>Revision Date</th>
                  <th>Reference Number</th>
                  <th>action</th>
                </tr>
              </thead>
              <tbody>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div> -->


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
                    <div class="col-6 text-end fw-bold text-dark loan_amount"></div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Total Interest:</div>
                    <div class="col-6 text-end fw-bold text-dark interest_amount"></div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Loan Term:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_term"></div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Payment Progress:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_progress"></div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Next Payment Due:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_due"></div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Monthly Payment:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_monthly"></div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Interest Rate:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_rate"></div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-6 text-secondary">Late Payment Penalty:</div>
                    <div class="col-6 text-end fw-bold text-dark loan_penalty"></div>
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
                <tbody class="approve_history">
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
        <!-- <button type="button" class="btn btn-warning rounded-pill" id="pending_revision">Revision</button> -->
        <button type="button" class="btn btn-secondary rounded-pill" id="pending_close" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>


<script>
$(document).ready(function () {
  $('#pending-tab').click();
});



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
    return '₱' + parseFloat(amount)
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
    ajax: "/get_pending_page_data",
    columns: [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'type' },
      { data: 'action', orderable: false, searchable: false }
    ]
  });
});


$(document).off('click', '#verified-tab').on('click', '#verified-tab', function () {
  $('#table_verified').DataTable().clear().destroy();
  $('#table_verified').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: "/get_verified_page_data",
    columns: [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'date_paid' },
      { data: 'date_triggered' },
      { data: 'action', orderable: false, searchable: false }
    ]
  });
});


$(document).on('click', '#rejected-tab', function () {
  $('#table_rejected').DataTable().clear().destroy();
  $('#table_rejected').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: "/get_rejected_page_data",
    columns: [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'date_paid' },
      { data: 'date_triggered' },
      { data: 'logid' },
      { data: 'action', orderable: false, searchable: false }
    ]
  });
});

// $(document).on('click', '#revision-tab', function () {
//   $('#table_revision').DataTable().clear().destroy();
//   $('#table_revision').DataTable({
//     processing: true,
//     serverSide: true,
//     responsive: true,
//     ajax: "/get_revision_page_data",
//     columns: [
//       { data: 'loan_application_id' },
//       { data: 'name' },
//       { data: 'date_paid' },
//       { data: 'date_triggered' },
//       { data: 'logid' },
//       { data: 'action', orderable: false, searchable: false }
//     ]
//   });
// });


$(document).on('click', '.pending_view', function () {
  var id = $(this).attr('data-id');
  var pay_id = $(this).attr('data-pay_id');

  //PUT IDS 
  $('#pending_verify').attr('data-id',pay_id);
  $('#pending_reject').attr('data-id',pay_id);
  // $('#pending_revision').attr('data-id',pay_id);


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
          $('.loan_amount').text('₱'+(parseFloat(response.data.loan_amount).toFixed(2)));
          $('.interest_amount').text('₱'+(parseFloat(response.total_interest).toFixed(2)));
          $('.loan_term').text(response.date.total_tenure + ' Month(s)');
          $('.loan_progress').text(response.date.count + ' Month(s)');
          $('.loan_due').text(response.date.date);
          $('.loan_monthly').text(formatMoney(total));
          $('.loan_rate').text((response.data.interest_rate * 100).toFixed(2) + "%");
          $('.loan_penalty').text(0);

          //HISTORY
          var history = ''
          console.log(response.history)
          $.each(response.history , function( k , v ){

            var type = '';
            if (parseInt(v.payment_status_id) === 3) {
              type = '<span class="badge bg-success">'+v.type+'</span>'
            }else{
              type = '<span class="badge bg-primary">'+v.type+'</span>'
            }
              history += `
                <tr>
                    <td>${v.date}</td>
                    <td>${type}</td>
                    <td>${v.remarks}</td>
                </tr>
              `;
          });
          console.log(history)
          $(".approve_history").empty().append(history);

        },
    });
  $('#loanModal').modal('show');
});

function convertToPHT(timeStr) {
    let utcDate = new Date(timeStr + "Z"); // "Z" = UTC

    // Convert to Philippine Time
    let options = {
        hour: 'numeric',
        minute: 'numeric',
        hour12: true,
        timeZone: 'Asia/Manila'
    };

    return utcDate.toLocaleTimeString('en-US', options); // e.g., "11:50 PM"
}


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

          // var principal = 0;
          // $.each(response.data.loan_tenure , function( k , v ){
          //     principal += v.principal;
          // });

          // var interest = 0;
          // $.each(response.data.loan_tenure_interest , function( k , v ){
          //     interest += v.interest;
          // });

          // let [datePart, timePart] = (response.data.created_at).split(" ");
          // $('.pt_date').text(convertToReadableDate(datePart));
          // $('.pt_time').text( convertTo12HourFormat( timePart ) );
          // $('.pt_type').text(response.data.type);

          // if (response.data.payment_type_id === 2) {
          //   $('.pt_outstanding').closest('tr').removeClass('hidden')
          // }else{
          //   $('.pt_outstanding').closest('tr').addClass('hidden')
          // }

          // $('.pt_amount').text(formatMoney(response.data.amount_sent));
          // $('.pt_principal').text(formatMoney(principal));
          // $('.pt_interest').text(formatMoney(interest));
          // $('.pt_outstanding').text(formatMoney(response.data.total_balance));
          // $('.pt_ref').text(response.data.reference_code);
          // $('.pt_rem').text(response.data.remarks);

          
          console.log(response.data.behavior)
          // let [datePart, timePart] = (response.data.behavior.created_at).split(" ");
          // $('.pt_time').text( convertTo12HourFormat( timePart ) );

          let phtTime = convertToPHT(response.data.behavior.created_at);
          $('.pt_time').text(phtTime);


          $('.pt_date').text(response.data.behavior.paid_date);
          $('.pt_type').text(response.data.behavior.payment_type);
          $('.pt_coverage').text(response.data.from + ' - ' + response.data.to);
          $('.pt_amount').text('₱'+response.data.behavior.amount_sent);
          $('.pt_interest').text('₱'+response.data.total_interest);
          $('.pt_principal').text('₱'+response.data.total_principal);
          $('.pt_penalty').text('₱'+response.data.total_penalty);
          $('.pt_ref').text(response.data.behavior.reference_code);
          $('.pt_rem').text(response.data.behavior.remarks);

          
        },
    });
});




$(document).on('click', '#pending_verify', function () {
  var id = $(this).attr('data-id');
  
  var data = `
        Are you sure you want to verify this payment?<br><br>
        <!-- Image file capture -->
            <form id="approveForm" enctype="multipart/form-data">
              <div class="form-group">
                <label for="imageFile">Attach Image</label>
                <input type="file" id="imageFile" name="imageFile" accept="image/*" capture="camera" class="form-control">
              </div><br>
            </form>
        <small style="color:red">This action cannot be undone.</small>
  `;
  $.confirm({
      title: 'Confirm Approval',
      content: data,
      type: 'green',
      buttons: {
          confirm: {
              text: 'Yes, Approve',
              btnClass: 'btn-success',
              action: function () {
                  var form = document.getElementById('approveForm');
                  var formData = new FormData(form);
                  formData.append('loan_id', id); // add the loan ID if needed

                  $.ajax({
                      url: '/paymentpage/verify/'+id+'/3',
                      method: 'POST',
                      data: formData,
                      processData: false,
                      contentType: false,
                      headers: {
                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                      },
                      success: function (response) {
                          $.alert({
                              title: 'Success',
                              content: 'The loan request has been Approved successfully.',
                              type: 'green'
                          });
                          $('#pending_close').click()
                          $('#pending-tab').click()
                      }
                  });
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
  $('#loanModal').modal('hide');
  var id = $(this).attr('data-id');

  var content = `
    <form id="rejectForm" enctype="multipart/form-data">
        <!-- Reason for rejection -->
        <div class="form-group">
          <label for="reason">Reason for Rejection</label>
          <textarea id="reason" name="reason" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
          <label for="received">Actual Received</label>
          <input type="number"  id="received" name="received" class="form-control" required>
        </div>

        <!-- Image file capture -->
        <div class="form-group">
          <label for="imageFile">Attach Image (optional)</label>
          <input type="file" id="imageFile" name="imageFile" accept="image/*" capture="camera" class="form-control">
        </div>

        <!-- Internal remarks -->
        <div class="form-group">
          <label for="remarks">Internal remarks</label>
        </div>
    </form>
  `;

  $.confirm({
      title: 'Confirm Rejection',
      content: content,
      type: 'red',
      boxWidth: '600px',
      useBootstrap: false,
      buttons: {
          confirm: {
              text: 'Yes',
              btnClass: 'btn-danger',
              action: function () {

                  // Get form data
                  var form = document.getElementById('rejectForm');
                  var formData = new FormData(form);
                  formData.append('loan_id', id); // add the loan ID if needed

                  // Second confirmation
                  $.confirm({
                      title: 'Warning',
                      content: 'Are you sure you want to reject this payment?<br>Borrower will automatically be notified.',
                      type: 'red',
                      buttons: {
                          confirm: {
                              text: 'Yes',
                              btnClass: 'btn-danger',
                              action: function () {

                                  $.ajax({
                                      url: '/paymentpage/verify/' + id + '/4',
                                      method: 'POST',
                                      data: formData,
                                      processData: false,
                                      contentType: false,
                                      headers: {
                                          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                      },
                                      success: function (response) {
                                          $.alert({
                                              title: 'Success',
                                              content: 'The payment has been rejected successfully.',
                                              type: 'green',
                                              buttons: {
                                                  ok: function () {
                                                        $('#pending-tab').click();
                                                  }
                                              }
                                          });
                                      },
                                      error: function (xhr) {
                                          $.alert({
                                              title: 'Error',
                                              content: 'Something went wrong while saving your rejection details.',
                                              type: 'red'
                                          });
                                      }
                                  });

                              }
                          },
                          cancel: {
                              text: 'Cancel',
                              btnClass: 'btn-secondary',
                              action: function () {
                                $('#loanModal').modal('show');
                              }
                          }
                      }
                  });
              }
          },
          cancel: {
              text: 'Cancel',
              btnClass: 'btn-secondary',
              action: function () {
                $('#loanModal').modal('show');
              }
          }
      }
  });
});



// $(document).on('click', '#pending_revision', function () {
//   $('#loanModal').modal('hide');
//   var id = $(this).attr('data-id');

//   var content = `
//     <form id="revisionForm">
//         <!-- Reason for revision -->
//         <div class="form-group">
//           <label for="reasonRevision">Reason for revision</label>
//           <textarea id="reasonRevision" name="reasonRevision" class="form-control" rows="3" required></textarea>
//         </div>

//         <div class="form-group">
//           <label for="received">Actual Received</label>
//           <input type="number"  id="received" name="received" class="form-control" required>
//         </div>

//         <!-- Instructions -->
//         <div class="form-group">
//           <label for="instructions">Instructions</label>
//           <textarea id="instructions" name="instructions" class="form-control" rows="3"></textarea>
//         </div>

//         <!-- Internal remarks -->
//         <div class="form-group">
//           <label for="revisionRemarks">Internal remarks</label>
//           <input type="text" id="revisionRemarks" name="revisionRemarks" class="form-control">
//           <p class="help-block">These remarks will reflect on the revision page &gt; Status History.</p>
//         </div>

//       </form>
//   `;
//   $.confirm({
//       title: 'Confirm Revision',
//       content: content,
//       type: 'orange',
//       buttons: {
//           confirm: {
//               text: 'Yes, Revision',
//               btnClass: 'btn-warning',
//               action: function () {


//                   var form = document.getElementById('revisionForm');
//                   var formData = new FormData(form);

//                       // Second confirmation
//                         $.confirm({
//                             title: 'Warning',
//                             content: 'Are you sure you want to Revise this payment?<br>Borrower will automatically be notified.',
//                             type: 'red',
//                             buttons: {
//                                 confirm: {
//                                     text: 'Yes',
//                                     btnClass: 'btn-danger',
//                                     action: function () {

//                                         $.ajax({
//                               url: '/paymentpage/verify/' + id + '/5',
//                               method: 'POST',
//                               data: formData,
//                               processData: false,
//                               contentType: false,
//                               headers: {
//                                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//                               },
//                               success: function (response) {
//                                   $.alert({
//                                       title: 'Success',
//                                       content: 'The payment has been For Revisioned successfully.',
//                                       type: 'green',
//                                       buttons: {
//                                           ok: function () {
//                                               $('#pending-tab').click();
//                                           }
//                                       }
//                                   });
//                               },
//                               error: function (xhr) {
//                                   $.alert({
//                                       title: 'Error',
//                                       content: 'Something went wrong while saving your rejection details.',
//                                       type: 'red'
//                                   });
//                               }
//                           });

//                               }
//                           },
//                           cancel: {
//                               text: 'Cancel',
//                               btnClass: 'btn-secondary',
//                               action: function () {
//                                 $('#loanModal').modal('show');
//                               }
//                           }
//                       }
//                   });

                  

//               }
//           },
//           cancel: {
//               text: 'Cancel',
//               btnClass: 'btn-secondary',
//               action: function () {
//                 $('#loanModal').modal('show');
//               }
//           }
//       }
//   });
// });




$(document).on('click', '.verified_view', function () {
    
  var id = $(this).attr('data-id');
  var pay_id = $(this).attr('data-pay_id');

    $.ajax({
        url: '/get_verified_page_data_view_more',
        method: 'POST',
        data: {
            id : id,
            pay_id : pay_id,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {

          $.confirm({
              title: 'Payment Confirmation',
              columnClass: 'col-md-6 col-md-offset-3',
              theme: 'modern',
              type: 'blue',
              icon: 'fa fa-credit-card',
              content: `
                  <div style="text-align:left;">
                      <p><strong>Payment Type:</strong> <span id="payment_type">${response.data.behavior[0].payment_type}</span></p>
                      <p><strong>Month Coverage:</strong> <span id="month_coverage">${response.data.from} - ${response.data.to}</span></p>
                      <p><strong>Breakdown:</strong> <span id="breakdown">Principal: ₱${response.data.total_principal} | Interest: ₱${response.data.total_interest}</span></p>
                      <p><strong>Proof of Transaction:</strong><br>
                          <img id="proof_image" src="uploads/proof123.jpg" style="max-width:100%;border-radius:8px;border:1px solid #ddd;">
                      </p>
                      <p><strong>Penalty:</strong> <span id="penalty">₱${response.data.total_penalty}</span></p>
                      <hr>
                      <p><strong>Payment Behaviour:</strong></p>
                      <ul style="margin-left:20px;">
                        <li>${response.data.behavior[0].payment_status}</li>
                        <li><span id="late_days">${response.data.behavior[0].days_late}</span> days of late</li>
                        <li><span id="advance_days">${response.data.behavior[0].days_advance}</span> days advance</li>
                    </ul>
                  </div>
              `,
              buttons: {
                  cancel: {
                      text: 'Cancel',
                      btnClass: 'btn-secondary'
                  },
                  confirm: {
                      text: 'Confirm',
                      btnClass: 'btn-success',
                      action: function () {
                          // your confirm logic here
                          console.log('Payment confirmed');
                      }
                  }
              }
          });
          
        },
    });



});




$(document).on('click', '.rejected_view', function () {

    var id = $(this).attr('data-id');
  var pay_id = $(this).attr('data-pay_id');

    $.ajax({
        url: '/get_rejected_page_data_view_more',
        method: 'POST',
        data: {
            id : id,
            pay_id : pay_id,
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            $.confirm({
                  title: 'Payment Details Confirmation',
                  columnClass: 'col-md-6 col-md-offset-3',
                  theme: 'modern',
                  type: 'blue',
                  icon: 'fa fa-file-invoice-dollar',
                  content: `
                      <div style="text-align:left;">
                          <p><strong>Payment Type:</strong> <span id="payment_type">${response.data.behavior[0].payment_type}</span></p>
                          <p><strong>Month Coverage:</strong> <span id="month_coverage">${response.data.from} - ${response.data.to}</span></p>
                          <p><strong>Amount Submitted vs Actual Amount:</strong><br>
                            <span id="amounts">₱${(parseFloat(response.data.totalpaid)).toFixed(2)} submitted / ₱${(parseFloat(response.data.behavior[0].actual_amount)).toFixed(2)} actual</span>
                          </p>
                          <p><strong>Rejection Reason:</strong><br>
                            <span id="rejection_reason">${response.data.behavior[0].reason}</span>
                          </p>
                          <p><strong>Additional Remarks:</strong><br>
                            <span id="remarks">${response.data.behavior[0].remarks}</span>
                          </p>
                      </div>
                  `,
                  buttons: {
                      cancel: {
                          text: 'Cancel',
                          btnClass: 'btn-secondary'
                      },
                      confirm: {
                          text: 'Confirm',
                          btnClass: 'btn-success',
                          action: function () {
                              // your confirm logic here
                              console.log('Confirmed payment details');
                          }
                      }
                  }
              });
            
          },
      });


});




// $(document).on('click', '.revision_view', function () {

//     var id = $(this).attr('data-id');
//   var pay_id = $(this).attr('data-pay_id');

//     $.ajax({
//         url: '/get_rejected_page_data_view_more',
//         method: 'POST',
//         data: {
//             id : id,
//             pay_id : pay_id,
//         },
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (response) {
            
//               $.confirm({
//                   title: 'Payment Revision Confirmation',
//                   columnClass: 'col-md-6 col-md-offset-3',
//                   theme: 'modern',
//                   type: 'orange',
//                   icon: 'fa fa-pen-to-square',
//                   content: `
//                       <div style="text-align:left;">
//                           <p><strong>Payment Type:</strong> <span id="payment_type">${response.data.behavior[0].payment_type}</span></p>
//                           <p><strong>Month Coverage:</strong> <span id="month_coverage">${response.data.from} - ${response.data.to}</span></p>
//                           <p><strong>Amount Submitted vs Actual Amount:</strong><br>
//                             <span id="amounts">₱${(parseFloat(response.data.totalpaid)).toFixed(2)} submitted / ₱${(parseFloat(response.data.behavior[0].actual_amount)).toFixed(2)} actual</span>
//                           </p>
//                           <p><strong>Revision Reason:</strong><br>
//                                 <span id="rejection_reason">${response.data.behavior[0].reason}</span>
//                           </p>
//                           <p><strong>Additional Remarks:</strong><br>
//                                 <span id="remarks">${response.data.behavior[0].remarks}</span>
//                           </p>
//                       </div>
//                   `,
//                   buttons: {
//                       cancel: {
//                           text: 'Cancel',
//                           btnClass: 'btn-secondary'
//                       },
//                       confirm: {
//                           text: 'Confirm',
//                           btnClass: 'btn-warning',
//                           action: function () {
//                               // your confirm logic here
//                               console.log('Revision confirmed');
//                           }
//                       }
//                   }
//               });

            
//           },
//       });

// });


  </script>

@endsection