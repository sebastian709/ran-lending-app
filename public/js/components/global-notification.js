window.triggerNotif = function (table_id, target_type, level_id, user_id, group_user_id, icon, message, data_url) {
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
    const escapeHtml = function (value) {
        return String(value ?? '')
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#39;');
    };

    const sanitizeIconMarkup = function (iconMarkup) {
        const icon = String(iconMarkup ?? '').trim();
        const match = icon.match(/^<i\s+class="([\w\s\-_]+)"><\/i>$/i);
        if (!match) {
            return '';
        }

        const classes = match[1]
            .split(/\s+/)
            .filter(Boolean)
            .join(' ');

        return classes ? `<i class="${classes}"></i>` : '';
    };

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
                let safeIcon = sanitizeIconMarkup(item.icon);
                let safeMessage = escapeHtml(item.message);
                let safeTimeAgo = escapeHtml(item.time_ago);

                let notifHtml = `
                    <div class="px-3 py-2 border-bottom items ${readClass} position-relative">
                        <div class="d-flex align-items-start justify-content-between">
                            <div class="d-flex align-items-start">
                                ${safeIcon}
                                <div>
                                    <p class="mb-1 small">${safeMessage}</p>
                                    <small class="text-muted">${safeTimeAgo}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;

                const $notif = $(notifHtml);
                if (item.data_url && String(item.data_url).startsWith('/')) {
                    $notif.attr('data-url', String(item.data_url));
                }
                container.append($notif);
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



