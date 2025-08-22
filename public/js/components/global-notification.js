window.triggerNotif = function (table_id, target_type, level_id, user_id, group_user_id, icon, message, data_url) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '/send-notification',
        method: 'POST',
        data: {
            table_id: table_id,
            target_type: target_type,
            level_id: level_id,
            user_id: user_id,
            group_user_id: group_user_id,
            icon: icon,
            message: message,
            data_url: data_url
        },
        success: function (response) {
            console.log("Notification sent:", response);
        },
        error: function (xhr) {
            console.error("Error:", xhr.responseText);
        }
    });
};

window.general_notification_count = function () {
    $.ajax({
        url: '/get-notification-data',
        type: 'GET',
        dataType: 'json',
        success: function (response) {
            let count = response.total;

            if (count > 0) {
                $('#general_notification_count').removeClass('d-none').text(count);
            } else {
                $('#general_notification_count').addClass('d-none').text('0');
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching notification count:", error);
        }
    });
}

window.general_notification_data = function (limit, offset, append) {
    $.ajax({
        url: '/get-notification-data',
        type: 'GET',
        data: { limit: limit, offset: offset },
        dataType: 'json',
        success: function (response) {
            let container = $(".notification-items");

            if (!append) {
                container.empty(); // clear lang pag first load
                notifOffset = 0;   // reset offset
            }

            if (response.data.length === 0 && !append) {
                container.append(`
                    <div class="px-3 py-2 text-center text-muted small">
                        No notifications
                    </div>
                `);
                return;
            }

            response.data.forEach(function (item, index) {
                let readClass = item.is_read == 0 ? "unread" : "read";
                let dataUrlAttr = item.data_url ? `data-url="${item.data_url}"` : "";

                let notifHtml = `
                    <div class="px-3 py-2 border-bottom items ${readClass} position-relative" ${dataUrlAttr}>
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-start">
                                ${item.icon}
                                <div>
                                    <p class="mb-1 small">${item.message}</p>
                                    <small class="text-muted">${item.time_ago}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                container.append(notifHtml);
            });

            // update offset for next load
            // notifOffset += response.length;

            // toggle "See more" visibility
            if (response.data.length < limit) {
                $(".dropdown-menu .text-center").hide(); // hide footer if wala nang load
            } else {
                $(".dropdown-menu .text-center").show(); // show pag may natira pa
            }
        },
        error: function (xhr, status, error) {
            console.error("Error fetching notifications:", error);
        }
    });
};


window.mark_all_as_read = function () {
    $.confirm({
        title: 'Confirm',
        content: 'Are you sure you want to mark all notifications as read?',
        type: 'blue',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'Yes',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/mark-all-read',
                        method: 'POST',
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Marked All As Read',
                                text: '',
                                timer: 1000,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                didClose: () => {
                                    window.general_notification_data(10,0,false);
                                    window.general_notification_count();
                                }
                            });
                        }
                    });
                }
            },
            cancel: {
                text: 'Cancel',
                btnClass: 'btn-red'
            }
        }
    });
};

window.clear_all_notifications = function () {
    $.confirm({
        title: 'Confirm',
        content: 'Are you sure you want to mark all notifications as read?',
        type: 'blue',
        typeAnimated: true,
        buttons: {
            confirm: {
                text: 'Yes',
                btnClass: 'btn-blue',
                action: function () {
                    $.ajax({
                        url: '/clear-all-notifications',
                        method: 'POST',
                        data: { _token: $('meta[name="csrf-token"]').attr('content') },
                        success: function (res) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Cleared notifications!',
                                text: '',
                                timer: 1000,
                                showConfirmButton: false,
                                timerProgressBar: true,
                                didClose: () => {
                                    window.general_notification_data(10,0,false);
                                    window.general_notification_count();
                                }
                            });
                        }
                    });
                }
            },
            cancel: {
                text: 'Cancel',
                btnClass: 'btn-red'
            }
        }
    });
};





