 <!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="p-3">
        <ul class="sidebar-nav">
            <li>
                <a href="{{ route('borrower.pages.home') }}" class="{{ request()->routeIs('borrower.pages.home') ? 'active' : '' }}">
                    <i class="ri-home-line"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('my-loan.repayment-schedule') }}" class="{{ request()->routeIs('my-loan.*') ? 'active' : '' }}">
                    <i class="ri-money-dollar-circle-line"></i> My Loans
                </a>
            </li>
            <li>
                <a href="{{ route('loan.payment') }}" class="{{ request()->routeIs('loan.*') ? 'active' : '' }}">
                    <i class="ri-calendar-line"></i> Payments
                </a>
            </li>
        </ul>
    </div>
</div>




















<!-- Payment Rejected Modal -->
<div class="modal fade" id="paymentRejectedModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="paymentRejectedLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title" id="paymentRejectedLabel">Payment Rejected</h5>
      </div>
      <div class="modal-body">
        Your payment has been rejected by the admin. You can make an appeal if you think this is a mistake.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" id="okayRejectBtn">Okay</button>
        <button type="button" class="btn btn-primary" id="openAppealModal">Make an Appeal</button>
      </div>
    </div>
  </div>
</div>

<!-- Appeal Modal -->
<div class="modal fade" id="appealModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="appealLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="appealLabel">Payment Appeal</h5>
      </div>
      <div class="modal-body">
        <!-- Admin Info (readonly) -->
        <div class="mb-3">
          <label class="form-label">Remarks from Admin</label>
          <input type="text" class="form-control" id="adminRemarks" value="Admin remarks here" readonly>
        </div>
        <div class="mb-3">
          <label class="form-label">Image from Admin</label>
          <div>
            <img id="adminImage" src="placeholder.jpg" alt="Admin Image" style="max-height:500px;width:auto" class="img-fluid rounded">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Payment Reference</label>
          <input type="text" class="form-control" id="paymentReference" value="REF123456" readonly>
        </div>

        <hr>

          <!-- User Inputs -->
          <form id="appealForm" enctype="multipart/form-data">
              <div class="mb-3">
                <label class="form-label">Reason for Appeal</label>
                <textarea class="form-control" id="appealReason" name="reason" rows="3" placeholder="Enter your reason"></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label">Upload Proof</label>
                <input type="file" class="form-control" name="upload" id="appealProof">
              </div>
          </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-success" id="submitAppeal">Submit Appeal</button>
      </div>
    </div>
  </div>
</div>

<!-- Confirmation Modal for Accepting Rejection -->
<div class="modal fade" id="confirmRejectModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="confirmRejectLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title" id="confirmRejectLabel">Confirm Rejection</h5>
      </div>
      <div class="modal-body">
        Are you sure you want to accept this payment rejection?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button type="button" class="btn btn-danger" id="confirmRejectBtn">Yes, Accept</button>
      </div>
    </div>
  </div>
</div>


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('sidebarToggle')?.addEventListener('click', function () {
        document.getElementById('sidebar').classList.toggle('show');
    });

    document.addEventListener('click', function (event) {
        const sidebar = document.getElementById('sidebar');
        const toggle = document.getElementById('sidebarToggle');
        if (window.innerWidth <= 991.98 && !sidebar.contains(event.target) && !toggle?.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });

    window.addEventListener('resize', function () {
        const sidebar = document.getElementById('sidebar');
        if (window.innerWidth > 991.98) {
            sidebar.classList.remove('show');
        }
    });

  const paymentRejectedModal = new bootstrap.Modal(document.getElementById('paymentRejectedModal'));
  const appealModal = new bootstrap.Modal(document.getElementById('appealModal'));
  const confirmRejectModal = new bootstrap.Modal(document.getElementById('confirmRejectModal'));

  // Show modal on login
      $.ajax({
        url: '/checkappeal',
        method: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
          //REJECTED 
          if (response.data?.paymentid?.payment_status_id === 4) {

            $('#appealModal').find('#adminRemarks').attr('value',response.data?.paymentlog?.reason)
            $('#appealModal').find('#adminImage').attr('src',response.image)
            $('#appealModal').find('#paymentReference').attr('value',response.data?.paymentlog?.id)

              paymentRejectedModal.show();            
          }



        },
    });


$(document).on('click', '#submitAppeal', function () {
      var form = document.getElementById('appealForm');
      var formData = new FormData(form);
      console.log(formData);
      $.ajax({
          url: 'appealuser',
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
                  content: 'Your appeal has been submitted successfully. Our team will review it and update you once a decision is made..',
                  type: 'green'
              });
              appealModal.hide();
          }
      });
      
  });

  // Open appeal modal
  document.getElementById('openAppealModal').addEventListener('click', () => {
    paymentRejectedModal.hide();
    appealModal.show();
  });

  // "Okay" button triggers confirmation
  document.getElementById('okayRejectBtn').addEventListener('click', () => {
    confirmRejectModal.show();
  });

  // Confirm rejection
  document.getElementById('confirmRejectBtn').addEventListener('click', () => {
    confirmRejectModal.hide();
    paymentRejectedModal.hide();
    // Add logic to update the status as rejected in your backend
    alert("You accepted the rejection.");
  });

  // Submit appeal
  // document.getElementById('submitAppeal').addEventListener('click', () => {
  //   const reason = document.getElementById('appealReason').value;
  //   const proof = document.getElementById('appealProof').files[0];
  //   console.log("Appeal reason:", reason);
  //   console.log("Proof file:", proof);
  //   appealModal.hide();
  //   alert("Your appeal has been submitted.");
  // });
</script>
@endsection