//LOGIN
$(document).on('click', '.password-toggle', function () {
    const input = $('#password');
    const icon = $(this).find('i');

    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
    } else {
        input.attr('type', 'password');
        icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
    }
});

//REGISTER
function validateStep(n) {
  let isValid = true;

  const currentStep = $('#step-' + (n - 1));

  currentStep.find('input[required], select[required]').each(function () {
    const $input = $(this);
    const value = $input.val()?.trim();

    const isSelect = $input.is('select');
    const isInvalid = !value || (isSelect && value === '0');

    if (isInvalid) {
      isValid = false;
      $input.addClass('is-invalid');

      if ($input.next('.invalid-feedback').length === 0) {
        $input.after('<div class="invalid-feedback">This field is required</div>');
      }
    } else {
      $input.removeClass('is-invalid');
      $input.next('.invalid-feedback').remove();
    }
  });

  return isValid;
}

$('#referral-source').on('change', function () {
  const showReferral = $(this).val() === '2'; 
  $('#referral-names').toggleClass('d-none', !showReferral);

  if (showReferral) {
    $('#referral-names select').attr('required', true);
  } else {
    $('#referral-names select')
    .removeAttr('required')
    .removeClass('is-invalid')
    .next('.invalid-feedback').remove();
  }
});

//Create Email and Password
$('.reg_password').on('input', function () {
  const password = $(this).val();

  const hasLength = password.length >= 8;
  const hasUpper = /[A-Z]/.test(password);
  const hasLower = /[a-z]/.test(password);
  const hasNumber = /[0-9]/.test(password);
  const hasSpecial = /[^A-Za-z0-9]/.test(password);

  toggleRule('length', hasLength);
  toggleRule('case', hasUpper && hasLower);
  toggleRule('number', hasNumber);
  toggleRule('special', hasSpecial);

  // Disable/enable submit button
  const allValid = hasLength && hasUpper && hasLower && hasNumber && hasSpecial;
  $('#authreggen').prop('disabled', !allValid);
});

function toggleRule(rule, isValid) {
  const $rule = $('.password-criteria li[data-rule="' + rule + '"]');
  const $icon = $rule.find('i');

  if (isValid) {
    $rule.addClass('valid');
    // $icon.removeClass('ri-close-line').addClass('ri-check-line');
  } else {
    $rule.removeClass('valid');
    // $icon.removeClass('ri-check-line').addClass('ri-close-line');
  }
}


//OTP pasting andd typing
$(document).ready(function () {
  const inputs = $('.otp-input');

  // Move to next input on keyup
  inputs.on('input', function () {
    const input = $(this);
    const val = input.val();

    if (val.length === 1) {
      input.next('.otp-input').focus();
    }
  });

  inputs.on('paste', function (e) {
    e.preventDefault();

    const pasteData = (e.originalEvent || e).clipboardData.getData('text').replace(/\D/g, '').slice(0, inputs.length);

    inputs.each(function (index) {
      $(this).val(pasteData.charAt(index));
    });

  
    for (let i = 0; i < inputs.length; i++) {
      if (!$(inputs[i]).val()) {
        $(inputs[i]).focus();
        break;
      }
    }
  });

  inputs.on('keydown', function (e) {
    if (e.key === 'Backspace' && !$(this).val()) {
      $(this).prev('.otp-input').focus();
    }
  });
});
