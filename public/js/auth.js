//LOGIN
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
});

$(document).on('click', '.password-toggle', function () {
    const input = $(this).siblings('input'); // Get the input next to the toggle
    const icon = $(this).find('i');
    const isHidden = input.attr('type') === 'password';

    if (isHidden) {
        input.attr('type', 'text');
        icon.removeClass('ri-eye-line').addClass('ri-eye-off-line');
        $(this).attr('aria-label', 'Hide password').attr('aria-pressed', 'true');
    } else {
        input.attr('type', 'password');
        icon.removeClass('ri-eye-off-line').addClass('ri-eye-line');
        $(this).attr('aria-label', 'Show password').attr('aria-pressed', 'false');
    }
});


//REGISTER
function validateStep(n) {
  let isValid = true;

  const currentStep = $('#step-' + (n - 1));

  currentStep.find('input[required], select[required]').each(function () {
    const $input = $(this);
    const value = $input.val()?.trim();
    const name = $input.attr('name');

    const isSelect = $input.is('select');
    const isEmpty = !value || (isSelect && value === '0');

    let customError = null;

    // Validation for contactnumber field
    if (name === 'contactnumber') {
      const phRegex = /^09\d{9}$/;

      if (isEmpty) {
        customError = 'This field is required';
      } else if (!phRegex.test(value)) {
        customError = 'Enter a valid number';
      }
    } 
    // Validation for middlename minimum 2 characters
    else if (name === 'middlename') {
      if (isEmpty) {
        customError = 'This field is required';
      } else if (value.length < 2) {
        customError = 'Middle name must be at least 2 characters';
      }
    } 
     else if (isEmpty) {
      customError = 'This field is required';
    }

    if (customError) {
      isValid = false;
      $input.addClass('is-invalid');
      $input.next('.invalid-feedback').remove();
      $input.after(`<div class="invalid-feedback">${customError}</div>`);
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
$('.reg_password, #new-password').on('input', function () {
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
  $('#changepass').prop('disabled', !allValid);
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

$(document).on('input', '#income', function () {
  let value = $(this).val().replace(/,/g, ''); // remove commas
  if (!isNaN(value) && value !== '') {
    // format with commas for display
    $(this).val(Number(value).toLocaleString());
  }
});

$(document).on('change', '.empStatusLis', function(){
  let $this = $(this);
  let status_val = $this.val();

  if(parseInt(status_val) == 4){
    $('.specifyOthers').closest('div.form-group').removeAttr('hidden');
    $('.specifyOthers').attr('required', 'true')
  } else {
    $('.specifyOthers').closest('div.form-group').attr('hidden', 'true');
    $('.specifyOthers').removeAttr('required')
  }
});
