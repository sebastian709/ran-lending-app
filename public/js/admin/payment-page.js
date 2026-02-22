(function ($) {
  const dtInstances = {
    pending: null,
    verified: null,
    rejected: null
  };

  let paymentHandlersBound = false;

  function formatMoney(amount) {
    const value = Number(amount || 0);
    return 'PHP ' + value.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
  }

  function toPhtTime(timeStr) {
    const utcDate = new Date(timeStr + 'Z');
    return utcDate.toLocaleTimeString('en-US', {
      hour: 'numeric',
      minute: 'numeric',
      hour12: true,
      timeZone: 'Asia/Manila'
    });
  }

  function tableConfig(ajaxUrl, columns) {
    return {
      processing: true,
      serverSide: true,
      responsive: true,
      autoWidth: false,
      ajax: ajaxUrl,
      columns,
      dom: '<"row g-2 align-items-center mb-3"<"col-md-6 d-flex align-items-center"l><"col-md-6 d-flex justify-content-md-end"f>>rt<"row g-2 align-items-center mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-md-end"p>>',
      pagingType: 'simple_numbers',
      language: {
        search: '',
        searchPlaceholder: 'Search records...'
      }
    };
  }

  function rebuildDataTable(key, selector, ajaxUrl, columns) {
    const $table = $(selector);
    if (!$table.length || !$.fn.DataTable) {
      return;
    }

    if ($.fn.DataTable.isDataTable($table)) {
      $table.DataTable().clear().destroy();
    }

    dtInstances[key] = $table.DataTable(tableConfig(ajaxUrl, columns));
  }

  function renderPendingTable() {
    rebuildDataTable('pending', '#table_pending', '/get_pending_page_data', [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'type' },
      { data: 'action', orderable: false, searchable: false }
    ]);
  }

  function renderVerifiedTable() {
    rebuildDataTable('verified', '#table_verified', '/get_verified_page_data', [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'date_paid' },
      { data: 'date_triggered' },
      { data: 'action', orderable: false, searchable: false }
    ]);
  }

  function renderRejectedTable() {
    rebuildDataTable('rejected', '#table_rejected', '/get_rejected_page_data', [
      { data: 'loan_application_id' },
      { data: 'name' },
      { data: 'date_paid' },
      { data: 'date_triggered' },
      { data: 'logid' },
      { data: 'action', orderable: false, searchable: false }
    ]);
  }

  function initPaymentPage() {
    if (!$('#pills-tab').length) {
      return;
    }

    if (!paymentHandlersBound) {
      bindPaymentHandlers();
      paymentHandlersBound = true;
    }

    $('#pending-tab').trigger('click');
  }

  function bindPaymentHandlers() {
    $(document).off('click.payment', '#pending-tab').on('click.payment', '#pending-tab', renderPendingTable);
    $(document).off('click.payment', '#verified-tab').on('click.payment', '#verified-tab', renderVerifiedTable);
    $(document).off('click.payment', '#rejected-tab').on('click.payment', '#rejected-tab', renderRejectedTable);

    $(document).off('click.payment', '.pending_view').on('click.payment', '.pending_view', function () {
      const id = $(this).attr('data-id');
      const payId = $(this).attr('data-pay_id');

      $('#pending_verify').attr('data-id', payId);
      $('#pending_reject').attr('data-id', payId);
      $('#profile-tab').trigger('click');
      $('#payment-tab').attr('data-id', id).attr('data-pay_id', payId);

      $.ajax({
        url: '/get_pending_data',
        method: 'POST',
        data: { id, pay_id: payId }
      }).done(function (response) {
        $('.profile_name').text(response.data.firstname + ' ' + response.data.lastname);
        $('.profile_email').text(response.data.email);
        $('.profile_phone').text(response.data.phone);

        const monthlyTotal = Number(response.data.principal || 0) + Number(response.data.interest || 0);

        $('.loan_amount').text(formatMoney(response.data.loan_amount));
        $('.interest_amount').text(formatMoney(response.total_interest));
        $('.loan_term').text(response.date.total_tenure + ' Month(s)');
        $('.loan_progress').text(response.date.count + ' Month(s)');
        $('.loan_due').text(response.date.date);
        $('.loan_monthly').text(formatMoney(monthlyTotal));
        $('.loan_rate').text((Number(response.data.interest_rate || 0) * 100).toFixed(2) + '%');
        $('.loan_penalty').text('0');

        const historyRows = (response.history || []).map(function (item) {
          const statusBadge = Number(item.payment_status_id) === 3
            ? `<span class="badge bg-success">${item.type}</span>`
            : `<span class="badge bg-primary">${item.type}</span>`;

          return `
            <tr>
              <td>${item.date}</td>
              <td>${statusBadge}</td>
              <td>${item.remarks}</td>
            </tr>
          `;
        }).join('');

        $('.approve_history').empty().append(historyRows);
      });

      $('#loanModal').modal('show');
      $('#customBackdrop').fadeIn(150);
    });

    $(document).off('click.payment', '#pending_close').on('click.payment', '#pending_close', function () {
      $('#customBackdrop').fadeOut(150);
    });

    $(document).off('hidden.bs.modal.payment', '#loanModal').on('hidden.bs.modal.payment', '#loanModal', function () {
      $('#customBackdrop').fadeOut(150);
    });

    $(document).off('click.payment', '#payment-tab').on('click.payment', '#payment-tab', function () {
      const id = $(this).attr('data-id');
      const payId = $(this).attr('data-pay_id');
      $('.view_payment_attachment').attr('data-id', payId);

      $.ajax({
        url: '/get_pending_data_two',
        method: 'POST',
        data: { id, pay_id: payId }
      }).done(function (response) {
        const behavior = response?.data?.behavior || {};
        $('.pt_time').text(toPhtTime(behavior.created_at));
        $('.pt_date').text(behavior.paid_date);
        $('.pt_type').text(behavior.payment_type);
        $('.pt_coverage').text(response.data.from + ' - ' + response.data.to);
        $('.pt_amount').text(formatMoney(behavior.amount_sent));
        $('.pt_interest').text(formatMoney(response.data.total_interest));
        $('.pt_principal').text(formatMoney(response.data.total_principal));
        $('.pt_penalty').text(formatMoney(response.data.total_penalty));
        $('.pt_ref').text(behavior.reference_code);
        $('.pt_rem').text(behavior.remarks);
      });
    });

    $(document).off('click.payment', '#pending_verify').on('click.payment', '#pending_verify', function () {
      const id = $(this).attr('data-id');
      const content = `
        Are you sure you want to verify this payment?<br><br>
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
        content,
        type: 'green',
        buttons: {
          confirm: {
            text: 'Yes, Approve',
            btnClass: 'btn-success',
            action: function () {
              const form = document.getElementById('approveForm');
              const formData = new FormData(form);
              formData.append('loan_id', id);

              $.ajax({
                url: '/paymentpage/verify/' + id + '/3',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false
              }).done(function () {
                $.alert({
                  title: 'Success',
                  content: 'The loan request has been approved successfully.',
                  type: 'green'
                });
                $('#pending_close').trigger('click');
                $('#pending-tab').trigger('click');
              });
            }
          },
          cancel: { text: 'Cancel', btnClass: 'btn-secondary' }
        }
      });
    });

    $(document).off('click.payment', '#pending_reject').on('click.payment', '#pending_reject', function () {
      $('#loanModal').modal('hide');
      const id = $(this).attr('data-id');

      const content = `
        <form id="rejectForm" enctype="multipart/form-data">
          <div class="form-group">
            <label for="reason">Reason for Rejection</label>
            <textarea id="reason" name="reason" class="form-control" rows="3" required></textarea>
          </div>
          <div class="form-group">
            <label for="received">Actual Received</label>
            <input type="number" id="received" name="received" class="form-control" required>
          </div>
          <div class="form-group">
            <label for="imageFile">Attach Image (optional)</label>
            <input type="file" id="imageFile" name="imageFile" accept="image/*" capture="camera" class="form-control">
          </div>
          <div class="form-group">
            <label for="remarks">Internal remarks</label>
          </div>
        </form>
      `;

      $.confirm({
        title: 'Confirm Rejection',
        content,
        type: 'red',
        boxWidth: '600px',
        useBootstrap: false,
        buttons: {
          confirm: {
            text: 'Yes',
            btnClass: 'btn-danger',
            action: function () {
              const form = document.getElementById('rejectForm');
              const formData = new FormData(form);
              formData.append('loan_id', id);

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
                        contentType: false
                      }).done(function () {
                        $.alert({
                          title: 'Success',
                          content: 'The payment has been rejected successfully.',
                          type: 'green',
                          buttons: {
                            ok: function () {
                              $('#pending-tab').trigger('click');
                              $('#customBackdrop').fadeOut(150);
                            }
                          }
                        });
                      }).fail(function () {
                        $.alert({
                          title: 'Error',
                          content: 'Something went wrong while saving your rejection details.',
                          type: 'red'
                        });
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

    $(document).off('click.payment', '.verified_view').on('click.payment', '.verified_view', function () {
      const id = $(this).attr('data-id');
      const payId = $(this).attr('data-pay_id');

      $.ajax({
        url: '/get_verified_page_data_view_more',
        method: 'POST',
        data: { id, pay_id: payId }
      }).done(function (response) {
        $.confirm({
          title: 'Payment Confirmation',
          columnClass: 'col-md-6 col-md-offset-3',
          theme: 'modern',
          type: 'blue',
          icon: 'fa fa-credit-card',
          content: `
            <div style="text-align:left;">
              <p><strong>Payment Type:</strong> <span>${response.data.behavior[0].payment_type}</span></p>
              <p><strong>Month Coverage:</strong> <span>${response.data.from} - ${response.data.to}</span></p>
              <p><strong>Breakdown:</strong> <span>Principal: PHP ${response.data.total_principal} | Interest: PHP ${response.data.total_interest}</span></p>
              <p><strong>Proof of Transaction:</strong></p>
              <p><strong>Penalty:</strong> <span>PHP ${response.data.total_penalty}</span></p>
              <hr>
              <p><strong>Payment Behaviour:</strong></p>
              <ul style="margin-left:20px;">
                <li>${response.data.behavior[0].payment_status}</li>
                <li>${response.data.behavior[0].days_late} days of late</li>
                <li>${response.data.behavior[0].days_advance} days advance</li>
              </ul>
            </div>
          `,
          buttons: {
            cancel: { text: 'Cancel', btnClass: 'btn-secondary' },
            confirm: {
              text: 'Confirm',
              btnClass: 'btn-success',
              action: function () { return true; }
            }
          }
        });
      });
    });

    $(document).off('click.payment', '.rejected_view').on('click.payment', '.rejected_view', function () {
      const id = $(this).attr('data-id');
      const payId = $(this).attr('data-pay_id');

      $.ajax({
        url: '/get_rejected_page_data_view_more',
        method: 'POST',
        data: { id, pay_id: payId }
      }).done(function (response) {
        $.confirm({
          title: 'Payment Details Confirmation',
          columnClass: 'col-md-6 col-md-offset-3',
          theme: 'modern',
          type: 'blue',
          icon: 'fa fa-file-invoice-dollar',
          content: `
            <div style="text-align:left;">
              <p><strong>Payment Type:</strong> <span>${response.data.behavior[0].payment_type}</span></p>
              <p><strong>Month Coverage:</strong> <span>${response.data.from} - ${response.data.to}</span></p>
              <p><strong>Amount Submitted vs Actual Amount:</strong><br>
              <span>PHP ${Number(response.data.totalpaid).toFixed(2)} submitted / PHP ${Number(response.data.behavior[0].actual_amount).toFixed(2)} actual</span></p>
              <p><strong>Rejection Reason:</strong><br><span>${response.data.behavior[0].reason}</span></p>
              <p><strong>Additional Remarks:</strong><br><span>${response.data.behavior[0].remarks}</span></p>
            </div>
          `,
          buttons: {
            cancel: { text: 'Cancel', btnClass: 'btn-secondary' },
            confirm: {
              text: 'Confirm',
              btnClass: 'btn-success',
              action: function () { return true; }
            }
          }
        });
      });
    });

    $(document).off('click.payment', '.view_payment_attachment').on('click.payment', '.view_payment_attachment', function () {
      const paymentId = $(this).attr('data-id');

      $.ajax({
        url: '/payment/get-attachment',
        method: 'POST',
        data: { payment_id: paymentId }
      }).done(function (response) {
        if (!response.attachment) {
          Swal.fire('No Attachment', 'This payment has no uploaded file.', 'warning');
          return;
        }

        const fileUrl = 'storage/' + response.attachment;
        const isImage = /\.(jpg|jpeg|png)$/i.test(fileUrl);
        const isPdf = /\.pdf$/i.test(fileUrl);

        let content = '';
        if (isImage) {
          content = `<a href="${fileUrl}" target="_blank" rel="noopener noreferrer"><img src="${fileUrl}" style="width:100%;border-radius:6px;" /></a>`;
        } else if (isPdf) {
          content = `<iframe src="${fileUrl}" width="100%" height="600px"></iframe>`;
        }

        Swal.fire({
          title: 'Payment Attachment',
          html: content,
          width: '800px',
          showCloseButton: true,
          showConfirmButton: false
        });
      });
    });
  }

  $(initPaymentPage);
  $(document).on('admin:content-loaded', initPaymentPage);
})(window.jQuery);
