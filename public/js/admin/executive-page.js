(function ($) {
  let executiveHandlersBound = false;

  function formatMoney(value, locale = 'en-PH') {
    if (value === null || value === undefined || Number.isNaN(Number(value))) {
      return '0.00';
    }

    return new Intl.NumberFormat(locale, {
      minimumFractionDigits: 2,
      maximumFractionDigits: 2
    }).format(Number(value));
  }

  function initExecutivePage() {
    const $page = $('.executive-page');
    if (!$page.length || !$.fn.DataTable) {
      return;
    }

    if ($page.data('executiveInit') !== 1) {
      $page.data('executiveInit', 1);
      pullExecutiveData();
    }

    if (!executiveHandlersBound) {
      bindExecutiveHandlers();
      executiveHandlersBound = true;
    }
  }

  function pullExecutiveData() {
    const $table = $('#investmentTable');
    if (!$table.length) {
      return;
    }

    if ($.fn.DataTable.isDataTable($table)) {
      $table.DataTable().clear().destroy();
    }

    $table.find('tbody').empty();

    $.ajax({
      url: '/executive/pull_data',
      type: 'POST',
      dataType: 'json'
    }).done(function (res) {
      const payload = res?.data || {};
      const hideMoney = parseInt(payload?.hide_money?.hide_money, 10) === 1;

      if (hideMoney) {
        $('.money_status').removeClass('hidden').text('Show Money');
        $('.dividend,.total_fund,.shared_fund,.remaining_fund,.tithes,.ongoing_fund,.misc_fund,.div_percent').text('***.***');
      } else {
        const totalFund = Number(payload?.total_fund?.amount || 0);
        const sharedFund = Number(payload?.shared_fund?.amount || 0);
        const withrawnFund = Number(payload?.withrawn_fund?.amount || 0);
        const data = payload?.data || {};
        const gain = (Number(data.interest || 0) + Number(data.penalty || 0) + withrawnFund) - Number(data.tithes || 0);
        const ratio = totalFund > 0 ? sharedFund / totalFund : 0;

        $('.money_status').removeClass('hidden').text('Hide Money');
        $('.total_fund').text(formatMoney(totalFund + Number(data.misc || 0)));
        $('.shared_fund').text(formatMoney(sharedFund));
        $('.remaining_fund').text(formatMoney(Number(data.remaining_money || 0)));
        $('.tithes').text(formatMoney(Number(data.tithes || 0)));
        $('.ongoing_fund').text(formatMoney(Number(data.remaining || 0)));
        $('.misc_fund').text(formatMoney(gain));
        $('.misc_fund').closest('.card-body').attr('title', formatMoney(Number(data.interest || 0) + Number(data.penalty || 0)));
        $('.penalty_fund').text(formatMoney(Number(data.penalty || 0)));
        $('.dividend').text(formatMoney(ratio * gain));
        $('.div_percent').text((ratio * 100).toFixed(0) + '%');
      }

      const rows = (payload.fund_management || []).map(function (item) {
        const badge = Number(item.category) === 1
          ? '<span class="btn btn-sm btn-primary" style="pointer-events:none">Add Investment</span>'
          : '<span class="btn btn-sm btn-danger" style="pointer-events:none">Withdraw Fund</span>';

        return `
          <tr>
            <td>${item.id}</td>
            <td>${item.name}</td>
            <td><b>PHP ${formatMoney(item.amount)}</b></td>
            <td>${badge}</td>
            <td>${item.remarks || 'N/A'}</td>
            <td>${item.readable_date}</td>
          </tr>
        `;
      }).join('');

      $table.find('tbody').append(rows);
      $table.DataTable({
        responsive: true,
        pageLength: 10,
        dom: '<"row g-2 align-items-center mb-3"<"col-md-6 d-flex align-items-center gap-2"B><"col-md-6 d-flex justify-content-md-end"f>>rt<"row g-2 align-items-center mt-3"<"col-md-6"i><"col-md-6 d-flex justify-content-md-end"p>>',
        order: [[1, 'desc']],
        columnDefs: [{ className: 'dt-center', targets: '_all' }],
        language: {
          search: '',
          searchPlaceholder: 'Search records...'
        },
        buttons: [
          { extend: 'excelHtml5', title: 'Loan Report', className: 'btn btn-sm btn-outline-primary-custom dt-export-btn' },
          { extend: 'pdfHtml5', title: 'Loan Report', pageSize: 'A4', className: 'btn btn-sm btn-outline-primary-custom dt-export-btn' }
        ]
      });
    });
  }

  function bindExecutiveHandlers() {
    $(document).on('click', '.openAddInvestment', function () {
      const $page = $('.executive-page').first();
      const adminName = $page.data('adminName') || '';
      const currentDate = $page.data('currentDate') || '';

      $.confirm({
        title: 'Add Investment',
        columnClass: 'large',
        type: 'blue',
        content: `
          <div class="mb-2">
            <label>Admin Name</label>
            <input class="form-control" value="${adminName}" disabled>
          </div>
          <div class="mb-2">
            <label>Amount</label>
            <input type="number" step="0.01" class="form-control executive_add_amount">
          </div>
          <div class="mb-2">
            <label>Date</label>
            <input class="form-control" value="${currentDate}" disabled>
          </div>
          <div class="mb-2">
            <label>Remarks</label>
            <textarea class="form-control executive_add_remarks"></textarea>
          </div>
        `,
        buttons: {
          add: { text: 'Add', btnClass: 'btn-primary executive_add_investment', action: function () { return false; } },
          cancel: { btnClass: 'btn-secondary' }
        }
      });
    });

    $(document).on('click', '.executive_add_investment', function (e) {
      e.preventDefault();
      const $btn = $(this);
      const $amount = $('.executive_add_amount');
      const $remarks = $('.executive_add_remarks');

      $amount.add($remarks).removeClass('is-invalid');
      if (!$amount.val().trim()) {
        $amount.addClass('is-invalid');
        $.confirm({
          title: 'Validation Error',
          content: 'Please complete all required fields.',
          type: 'red',
          buttons: { ok: { btnClass: 'btn-danger' } }
        });
        return;
      }

      $.confirm({
        title: 'Confirm Investment',
        content: 'Are you sure you want to add this investment?',
        type: 'blue',
        buttons: {
          confirm: {
            text: 'Yes, Add',
            btnClass: 'btn-primary',
            action: function () {
              $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Saving...');

              $.ajax({
                url: '/executive/add_investment',
                type: 'POST',
                dataType: 'json',
                data: { amount: $amount.val(), remarks: $remarks.val() }
              }).done(function () {
                $.confirm({ title: 'Success', content: 'Investment has been added successfully.', type: 'green', buttons: { ok: { btnClass: 'btn-success' } } });
                pullExecutiveData();
              }).fail(function () {
                $.confirm({ title: 'Error', content: 'Something went wrong. Please try again.', type: 'red', buttons: { ok: { btnClass: 'btn-danger' } } });
              }).always(function () {
                $btn.prop('disabled', false).html('Add Investment');
              });
            }
          },
          cancel: { text: 'Cancel', btnClass: 'btn-secondary' }
        }
      });
    });

    $(document).on('click', '.openWithdraw', function () {
      const $page = $('.executive-page').first();
      const adminName = $page.data('adminName') || '';

      $.confirm({
        title: 'Withdraw Funds',
        type: 'red',
        boxWidth: '400px',
        useBootstrap: true,
        content: `
          <input class="form-control mb-2" value="${adminName}" disabled>
          <input type="number" class="form-control mb-2 withdraw_amount" placeholder="Amount Deducted">
          <input class="form-control mb-2 withdraw_reason" placeholder="Reason">
        `,
        buttons: {
          submit: {
            text: 'Submit',
            btnClass: 'btn-danger',
            action: function () {
              const amount = parseFloat(this.$content.find('.withdraw_amount').val());
              const reason = this.$content.find('.withdraw_reason').val().trim();

              if (Number.isNaN(amount)) {
                $.alert('Amount is required');
                return false;
              }
              if (!reason) {
                $.alert('Reason is required');
                return false;
              }

              $.confirm({
                title: 'Confirm Withdrawal',
                type: 'red',
                content: `<b>Amount:</b> ${amount}<br><b>Reason:</b> ${reason}<br><br>Are you sure you want to proceed?`,
                buttons: {
                  confirm: {
                    text: 'Yes, Withdraw',
                    btnClass: 'btn-danger',
                    action: function () {
                      $.ajax({
                        url: '/executive/withraw_investment',
                        method: 'POST',
                        data: { amount, reason }
                      }).done(function () {
                        $.confirm({ title: 'Success', type: 'green', content: 'Withdrawal submitted successfully', buttons: { ok: { btnClass: 'btn-success' } } });
                        pullExecutiveData();
                      }).fail(function (xhr) {
                        $.confirm({ title: 'Error', type: 'red', content: xhr.responseJSON?.message || 'Something went wrong', buttons: { ok: { btnClass: 'btn-danger' } } });
                      });
                    }
                  },
                  cancel: { btnClass: 'btn-secondary' }
                }
              });

              return false;
            }
          },
          cancel: { btnClass: 'btn-secondary' }
        }
      });
    });

    $(document).on('click', '.money_status', function (e) {
      e.preventDefault();
      $.ajax({
        url: '/executive/money_status',
        type: 'POST',
        dataType: 'json'
      }).always(function () {
        pullExecutiveData();
      });
    });
  }

  $(initExecutivePage);
  $(document).on('admin:content-loaded', initExecutivePage);
})(window.jQuery);
