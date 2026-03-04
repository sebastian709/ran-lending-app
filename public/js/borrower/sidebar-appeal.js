(function () {
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');

    if (sidebarToggle && sidebar) {
        sidebarToggle.addEventListener('click', function () {
            sidebar.classList.toggle('show');
        });
    }

    document.addEventListener('click', function (event) {
        if (!sidebar) {
            return;
        }

        const toggle = document.getElementById('sidebarToggle');
        if (window.innerWidth <= 991.98 && !sidebar.contains(event.target) && !toggle?.contains(event.target)) {
            sidebar.classList.remove('show');
        }
    });

    window.addEventListener('resize', function () {
        if (!sidebar) {
            return;
        }

        if (window.innerWidth > 991.98) {
            sidebar.classList.remove('show');
        }
    });

    const paymentRejectedModalElement = document.getElementById('paymentRejectedModal');
    const appealModalElement = document.getElementById('appealModal');
    const confirmRejectModalElement = document.getElementById('confirmRejectModal');

    if (!paymentRejectedModalElement || !appealModalElement || !confirmRejectModalElement || typeof bootstrap === 'undefined') {
        return;
    }

    const paymentRejectedModal = new bootstrap.Modal(paymentRejectedModalElement);
    const appealModal = new bootstrap.Modal(appealModalElement);
    const confirmRejectModal = new bootstrap.Modal(confirmRejectModalElement);

    $.ajax({
        url: '/checkappeal',
        method: 'POST',
        dataType: 'json',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            if (response.data?.paymentid?.payment_status_id !== 4) {
                return;
            }

            $('#appealModal').find('#adminRemarks').attr('value', response.data?.paymentlog?.reason);
            $('#appealModal').find('#adminImage').attr('src', response.image);
            $('#appealModal').find('#adminImageLink').attr('href', response.image);
            $('#appealModal').find('#paymentReference').attr('value', response.data?.paymentlog?.id);
            $('#confirmRejectBtn').attr('value', response.data?.paymentlog?.id);

            paymentRejectedModal.show();
        },
    });

    $(document).off('click.sidebarAppealSubmit', '#submitAppeal').on('click.sidebarAppealSubmit', '#submitAppeal', function () {
        const form = document.getElementById('appealForm');
        if (!form) {
            return;
        }

        const formData = new FormData(form);
        $.ajax({
            url: 'appealuser',
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                $.alert({
                    title: 'Success',
                    content: 'Your appeal has been submitted successfully. Our team will review it and update you once a decision is made.',
                    type: 'green'
                });
                appealModal.hide();
            }
        });
    });

    const openAppealModal = document.getElementById('openAppealModal');
    if (openAppealModal) {
        openAppealModal.addEventListener('click', function () {
            paymentRejectedModal.hide();
            appealModal.show();
        });
    }

    const okayRejectBtn = document.getElementById('okayRejectBtn');
    if (okayRejectBtn) {
        okayRejectBtn.addEventListener('click', function () {
            confirmRejectModal.show();
        });
    }

    $(document).off('click.sidebarRejectConfirm', '#confirmRejectBtn').on('click.sidebarRejectConfirm', '#confirmRejectBtn', function () {
        const logId = parseInt($(this).attr('value'), 10);
        if (!logId) {
            return;
        }

        $.ajax({
            url: '/rejectaccept',
            method: 'POST',
            dataType: 'json',
            data: { log_id: logId },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function () {
                $.alert({
                    title: 'Success',
                    content: 'Appeal Accepted',
                    type: 'green'
                });

                confirmRejectModal.hide();
                paymentRejectedModal.hide();
            }
        });
    });
})();
