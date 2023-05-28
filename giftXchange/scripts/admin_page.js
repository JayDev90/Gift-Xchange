function resetPassword(username) {
    location.href = 'admin_reset_password_form.php?username=' + username;
}

$(document).ready(function() {
    $('button[id^="deleteUserBtn_"]').click(function() {
        var username = $(this).data('username');
        $.ajax({
            url: 'delete_user.php',
            type: 'POST',
            data: { 'username': username },
            success: function(response) {
                $('#row_' + username).remove();
            },
            error: function() {
                alert('Error deleting user');
            }
        });
    });

    $('#userStatus').change(function() {
        var status = $(this).val();
        $.ajax({
            url: 'fetch_users_admin_page.php',
            type: 'POST',
            data: { 'status': status },
            success: function(response) {
                $('.view-users-body').html(response);
                changeButtonBasedOnStatus($('#userStatus').val());
            },
            error: function() {
                alert('Error fetching users');
            }
        });
    });

    changeButtonBasedOnStatus($('#userStatus').val());

    $('#userStatus').change(function() {
        changeButtonBasedOnStatus($(this).val());
    });
});

function activateUser(username) {
    $.ajax({
        url: 'activate_user.php',
        type: 'POST',
        data: { 'username': username },
        success: function(response) {
            changeButtonBasedOnStatus('active');
        },
        error: function() {
            alert('Error activating user');
        }
    });
}

function changeButtonBasedOnStatus(status) {
    if (status === 'inactive') {
        $('.deleteUserBtn').each(function() {
            $(this).text('Activate');
            $(this).removeClass('deleteUserBtn').addClass('activateUserBtn');
            $(this).attr('id', function(i, old) {
                return old == 'deleteUserBtn_' + $(this).data('username') ? 'activateUserBtn_' + $(this).data('username') : old;
            });
            $(this).attr('onclick', function(i, old) {
                return 'activateUser(\'' + $(this).data('username') + '\')';
            });
        });
    } else {
        $('.activateUserBtn').each(function() {
            $(this).text('Delete');
            $(this).removeClass('activateUserBtn').addClass('deleteUserBtn');
            $(this).attr('id', function(i, old) {
                return old == 'activateUserBtn_' + $(this).data('username') ? 'deleteUserBtn_' + $(this).data('username') : old;
            });
            $(this).attr('onclick', function(i, old) {
                return 'deleteUser(\'' + $(this).data('username') + '\')';
            });
        });
    }
}
