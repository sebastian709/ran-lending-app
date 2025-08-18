window.triggerNotif = function(table_id, target_type, level_id, user_id, group_user_id, icon, message, data_url) {
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
            level_id : level_id,
            user_id : user_id,
            group_user_id : group_user_id,
            icon : icon,
            message : message,
            data_url : data_url
        },
        success: function (response) {
            console.log("Notification sent:", response);
        },
        error: function (xhr) {
            console.error("Error:", xhr.responseText);
        }
    });
};
