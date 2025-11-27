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
    ajax: "/get_pending_page_data",
    columns: [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'type' },
      { data: 'action', orderable: false, searchable: false }
    ]
  });
});


$(document).on('click', '#verified-tab', function () {
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

$(document).on('click', '#revision-tab', function () {
  $('#table_revision').DataTable().clear().destroy();
  $('#table_revision').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: "/get_revision_page_data",
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


$(document).on('click', '#appeal-tab', function () {
  $('#table_appeal').DataTable().clear().destroy();
  $('#table_appeal').DataTable({
    processing: true,
    serverSide: true,
    responsive: true,
    ajax: "/get_appeal_page_data",
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

  //PUT IDS 
  $('#pending_verify').attr('data-id',pay_id);
  $('#pending_reject').attr('data-id',pay_id);
  $('#pending_revision').attr('data-id',pay_id);


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
                                                      $('#loanModal').modal('show');
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
                              btnClass: 'btn-secondary'
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



$(document).on('click', '#pending_revision', function () {
  $('#loanModal').modal('hide');
  var id = $(this).attr('data-id');

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

                  var form = document.getElementById('revisionForm');
                  var formData = new FormData(form);

                  $.ajax({
                        url: '/paymentpage/verify/' + id + '/5',
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
                                        $('#loanModal').modal('show');
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
});




$(document).on('click', '.verified_view', function () {

  $.confirm({
      title: 'Payment Confirmation',
      columnClass: 'col-md-6 col-md-offset-3',
      theme: 'modern',
      type: 'blue',
      icon: 'fa fa-credit-card',
      content: `
          <div style="text-align:left;">
              <p><strong>Payment Type:</strong> <span id="payment_type">Installment</span></p>
              <p><strong>Month Coverage:</strong> <span id="month_coverage">October 2025</span></p>
              <p><strong>Breakdown:</strong> <span id="breakdown">Principal: ₱2,000 | Interest: ₱150</span></p>
              <p><strong>Proof of Transaction:</strong><br>
                  <img id="proof_image" src="uploads/proof123.jpg" style="max-width:100%;border-radius:8px;border:1px solid #ddd;">
              </p>
              <p><strong>Penalty:</strong> <span id="penalty">₱50</span></p>
              <hr>
              <p><strong>Payment Behaviour:</strong></p>
              <ul style="margin-left:20px;">
                  <li>On time</li>
                  <li><span id="late_days">3</span> days of late</li>
                  <li><span id="advance_days">0</span> days advance</li>
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

});




$(document).on('click', '.rejected_view', function () {

  $.confirm({
      title: 'Payment Details Confirmation',
      columnClass: 'col-md-6 col-md-offset-3',
      theme: 'modern',
      type: 'blue',
      icon: 'fa fa-file-invoice-dollar',
      content: `
          <div style="text-align:left;">
              <p><strong>Payment Type:</strong> <span id="payment_type">Installment</span></p>
              <p><strong>Months Coverage:</strong> <span id="months_coverage">October - November 2025</span></p>
              <p><strong>Amount Submitted vs Actual Amount:</strong><br>
                <span id="amounts">₱2,000 submitted / ₱2,100 actual</span>
              </p>
              <p><strong>Rejection Reason:</strong><br>
                <span id="rejection_reason">Late payment submission</span>
              </p>
              <p><strong>Additional Remarks:</strong><br>
                <span id="remarks">Please submit the missing receipt next time.</span>
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


});




$(document).on('click', '.revision_view', function () {

$.confirm({
    title: 'Payment Revision Confirmation',
    columnClass: 'col-md-6 col-md-offset-3',
    theme: 'modern',
    type: 'orange',
    icon: 'fa fa-pen-to-square',
    content: `
        <div style="text-align:left;">
            <p><strong>Payment Type:</strong> <span id="payment_type">Installment</span></p>
            <p><strong>Months Coverage:</strong> <span id="months_coverage">October - November 2025</span></p>
            <p><strong>Amount Submitted vs Actual Amount:</strong><br>
               <span id="amounts">₱2,000 submitted / ₱2,100 actual</span>
            </p>
            <p><strong>Revision Reason:</strong><br>
               <span id="revision_reason">Incorrect computation of interest</span>
            </p>
            <p><strong>Additional Remarks:</strong><br>
               <span id="remarks">Adjusted to reflect accurate payment schedule.</span>
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
            btnClass: 'btn-warning',
            action: function () {
                // your confirm logic here
                console.log('Revision confirmed');
            }
        }
    }
});

});





  </script>

@endsection