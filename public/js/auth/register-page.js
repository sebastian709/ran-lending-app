(function () {
  const body = document.body;
  const regSendUrl = body.dataset.regSendUrl;
  const regCheckUrl = body.dataset.regCheckUrl;

  $(document).ready(function () {
    const success = $('.success_register').text();
    if (success) {
      nextStep(5);
    }
  });

  const steps = document.querySelectorAll('.form-step');
  const progressBar = document.getElementById('form-progress');

  window.nextStep = function (n) {
    if (n > 1 && !validateStep(n)) return;
    steps.forEach(step => step.classList.remove('active'));
    document.getElementById(`step-${n}`).classList.add('active');
    progressBar.style.width = `${n * 20}%`;
  };

  window.prevStep = function (n) {
    steps.forEach(step => step.classList.remove('active'));
    document.getElementById(`step-${n}`).classList.add('active');
    progressBar.style.width = `${n * 20}%`;
  };

  $(document).on('click', '.authreggen', function () {
    const dis = $(this);
    const email = $('input[name="email"]').val();
    const emailField = $('input[name="email"]');
    const emailError = $('#email-error');
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email || !emailRegex.test(email)) {
      emailField.addClass('is-invalid');
      emailError.removeClass('d-none');
      return;
    }

    emailField.removeClass('is-invalid');
    emailError.addClass('d-none');

    $.ajax({
      url: regSendUrl,
      method: 'POST',
      data: {
        email: email,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (res) {
        if (res == 1) {
          if (dis.hasClass('authreggenresend')) {
            $('#otp-alert').removeClass('d-none').fadeIn();
            setTimeout(function () {
              $('#otp-alert').fadeOut(function () {
                $(this).addClass('d-none');
              });
            }, 3000);
          } else {
            prevStep(4);
          }
        } else if (res == 2) {
          $('#email-exists-error').removeClass('d-none');
        } else {
          alert('error contact admin');
        }
      }
    });
  });

  $(document).on('click', '#regauthcheck', function () {
    const rawValue = $('#income').val().replace(/,/g, '');
    $('#income').val(rawValue);

    const email = $('input[name="email"]').val();
    let otp = '';
    $('.otp-input').each(function () {
      otp += $(this).val();
    });

    $.ajax({
      url: regCheckUrl,
      method: 'POST',
      data: {
        email: email,
        otp: otp,
        _token: $('meta[name="csrf-token"]').attr('content')
      },
      success: function (res) {
        if (res == 1) {
          $('#register-form').submit();
        } else {
          $('#otp-error').removeClass('d-none');
        }
      }
    });
  });
})();
