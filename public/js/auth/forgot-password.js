(function ($) {
  const root = document.getElementById('forgot-password-page');
  if (!root) {
    return;
  }

  const routes = {
    send: root.dataset.sendUrl,
    check: root.dataset.checkUrl,
    change: root.dataset.changeUrl
  };

  function getOtpCode() {
    let otp = '';
    $('.otp-input').each(function () {
      otp += $(this).val();
    });
    return otp;
  }

  function toggleView(fromSelector, toSelector) {
    $(fromSelector).addClass('d-none');
    $(toSelector).removeClass('d-none');
  }

  $(document).on('click', '.authgen', function () {
    const trigger = $(this);
    const emailField = $('input[name="email"]');
    const email = emailField.val();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email || !emailRegex.test(email)) {
      emailField.addClass('is-invalid');
      $('#forgot-email-error').removeClass('d-none');
      return;
    }

    emailField.removeClass('is-invalid');
    $('#forgot-email-error').addClass('d-none');

    $.post(routes.send, {
      email,
      _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function (res) {
      if (parseInt(res, 10) === 1) {
        if (trigger.hasClass('resend-code-btn')) {
          const original = trigger.html();
          trigger.text('Code has been sent.');
          setTimeout(function () {
            trigger.html(original);
          }, 3000);
        } else {
          toggleView('#forgot-step1-container', '#forgot-step2-container');
        }
      } else {
        $('#no-email-error').removeClass('d-none');
      }
    });
  });

  $(document).on('click', '#authcheck', function () {
    $.post(routes.check, {
      email: $('input[name="email"]').val(),
      otp: getOtpCode(),
      _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function (res) {
      if (parseInt(res, 10) === 1) {
        toggleView('#forgot-step2-container', '#forgot-step3-container');
      }
    });
  });

  $(document).on('click', '#changepass', function () {
    const pass1 = $('#new-password').val();
    const pass2 = $('#confirm-password').val();

    if (pass1 !== pass2) {
      Swal.fire({
        icon: 'error',
        title: 'Password mismatch',
        text: 'Your new password and confirmation do not match.'
      });
      return;
    }

    $.post(routes.change, {
      email: $('input[name="email"]').val(),
      otp: getOtpCode(),
      pass: pass2,
      _token: $('meta[name="csrf-token"]').attr('content')
    }).done(function (res) {
      if (parseInt(res, 10) === 1) {
        toggleView('#forgot-step3-container', '#success-container');
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Update failed',
          text: 'Something went wrong while updating your password.'
        });
      }
    });
  });
})(window.jQuery);
