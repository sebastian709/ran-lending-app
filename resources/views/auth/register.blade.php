<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - RAN Lending</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/styles.css">
  <style>
    .form-step {
      display: none;
    }
    .form-step.active {
      display: block;
      animation: fadeInLeft 0.5s ease-in-out;
    }
    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-20px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
    }
    .progress-bar {
      height: 6px;
      background-color: var(--primary-color);
      transition: width 0.3s ease;
    }
  </style>
</head>
<body>
  <div class="d-flex flex-column min-vh-100">
    <header class="bg-white shadow-sm">
      <div class="container py-3">
        <div class="d-flex justify-content-between align-items-center">
          <a href="{{ url('/login') }}" class="d-flex align-items-center text-decoration-none text-muted">
            <i class="ri-arrow-left-line me-2"></i>
            <span>Back to Login</span>
          </a>
        </div>
      </div>
    </header>

    <main class="flex-grow-1 d-flex align-items-center justify-content-center py-5">
      <div class="w-100" style="max-width: 600px;">
        <div class="text-center mb-4">
          <a href="#" class="font-pacifico text-primary-custom text-decoration-none" style="font-size: 2.5rem;">RAN Lending</a>
          <h1 class="h2 fw-bold mb-2">Create Your Account</h1>
        </div>

        <div class="mb-3">
          <div class="progress" style="height: auto;">
            <div class="progress-bar" id="form-progress" role="progressbar" style="width: 25%;"></div>
          </div>
        </div>

        <!-- Step 1: Personal Information -->
        <div class="form-container rounded-custom p-4 form-step active" id="step-1">
          <form id="register-form" method="POST" action="{{ route('register') }}">
          @csrf
            <h5 class="mb-3">Personal Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Last Name" name="lastname" required pattern="\S+" />
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="First Name" name="firstname" required pattern="\S+" />
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Middle Name" name="middlename"  required pattern="\S+" />
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Contact Number" name="contactnumber" maxlength="11" required  />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="House No." name="house_no" required />
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" placeholder="Street Name" name="street"  required />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Barangay" name="barangay"  required />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="City" name="city"  required />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Province" name="province"  required />
              </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
              <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="nextStep(2)">
                Next <i class="ri-arrow-right-line"></i>
              </button>
            </div>
        </div>

        <!-- Step 2: Employment & Referral -->
        <div class="form-container rounded-custom p-4 form-step" id="step-2">
            <h5 class="mb-3">Employment</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Occupation/Source of Income" name="occupation" required />
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Monthly Income" name="income" required id="income">

              </div>
              <div class="col-md-6">
                <select class="form-select" name="employment_status" required>
                  <option value="0">Employment Status</option>
                  <option value="1">Employed</option>
                  <option value="2">Self employed</option>
                  <option value="3">None</option>
                </select>
              </div>
            </div>
            <hr class="my-4">
            <h5 class="mb-3">Where did you find us?</h5>
            <div class="row g-3">
              <div class="col-md-12">
                <select class="form-select" id="referral-source" name="referral_source" required>
                  <option value="0">Select</option>
                  <option value="1">Social Media</option>
                  <option value="2">Referral</option>
                </select>
              </div>
              <div class="col-md-12 d-none" id="referral-names">
                <select class="form-select" name="referral_names" required>
                  <option value="0">Select</option>
                  <option value="1">Ms. NC</option>
                  <option value="2">Ms. Riki</option>
                  <option value="3">Ms. Almira</option>
                </select>
              </div>
            </div>
            <div class="d-flex justify-content-between mt-4">
              <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="prevStep(1)">
                <i class="ri-arrow-left-line"></i>Previous
              </button>
              <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="nextStep(3)">
                Next <i class="ri-arrow-right-line"></i>
              </button>
            </div>
        </div>

        <!-- Step 3: Account Creation -->
        <div class="form-container rounded-custom p-4 form-step" id="step-3">
            <div class="mb-3">
              <input type="email" class="form-control reg_email" placeholder="Email Address" name="email" required />
              <center><div class="text-danger small mt-1 d-none" id="email-exists-error">Email Already Exist.</div></center>
              <div class="invalid-feedback d-none" id="email-error">Please enter a valid email address.</div>
            </div>
            <div class="mb-3">
              <div class="position-relative">
                <input type="password" class="form-control reg_password" placeholder="Password" name="password" id="password" required>
                <div class="password-toggle position-absolute top-50 end-0 translate-middle-y pe-3" style="cursor: pointer;">
                  <i class="ri-eye-line text-muted"></i>
                </div>
              </div>
              <div class="mt-3 bg-light p-3 rounded">
                <p class="small fw-medium text-dark mb-2">Password must include:</p>
                <ul class="list-unstyled small password-criteria mt-2">
                  <li data-rule="length" class="text-muted"></i>Minimum 8 characters</li>
                  <li data-rule="case" class="text-muted"></i>Uppercase and lowercase letters</li>
                  <li data-rule="number" class="text-muted"></i>At least one number</li>
                  <li data-rule="special" class="text-muted"></i>At least one special character</li>
                </ul>
              </div>
            </div>
            <!-- <input type="submit" class="btn btn-primary-custom w-100 mt-3" placeholder="Save and Verify"> -->
            <div class="d-flex justify-content-between mt-4">
              <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="prevStep(2)">
                <i class="ri-arrow-left-line"></i>Previous
              </button>
              <button type="button" id="authreggen" class="authreggen btn d-flex align-items-center gap-2 text-muted border-0" disabled>Save and Verify</button>

            </div>
        </div>
        <!-- Step 4: Verification -->
        <div class="form-container rounded-custom p-4 form-step" id="step-4">
          <div id="otp-alert" class="alert alert-success text-center d-none" role="alert">
            A new verification code has been sent to your email!
        </div>
          <div class="text-center mb-4">
            <h4 class="fw-bold">RAN Serenity Lending Account verification</h4>
            <p class="text-muted small">We've sent a 6-digit verification code to your email or phone.<br>This code is valid for 3 minutes.</p>
          </div>
          <!-- <form onsubmit="event.preventDefault(); nextStep(5);"> -->
            <div class="d-flex justify-content-center mb-3">
              <input type="text" maxlength="1" class="reg-otp-input otp-input" />
              <input type="text" maxlength="1" class="reg-otp-input otp-input" />
              <input type="text" maxlength="1" class="reg-otp-input otp-input" />
              <input type="text" maxlength="1" class="reg-otp-input otp-input" />
              <input type="text" maxlength="1" class="reg-otp-input otp-input" />
              <input type="text" maxlength="1" class="reg-otp-input otp-input" />
            </div>
            <div class="text-center">
              <div class="text-danger small mt-1 d-none" id="otp-error">Invalid OTP.</div>
            </div>
            <div class="text-center">
              <button type="button" id="regauthcheck" class="btn btn-primary-custom w-100">Verify Code</button>
              <p class="small text-muted mt-2 mb-0">Didn't receive the code? <button type="button" class="authreggen authreggenresend btn btn-link text-primary-custom p-0">Resend Code</button></p>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <!-- <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="prevStep(3)">
                  <i class="ri-arrow-left-line"></i>Previous
                </button> -->
                <button type="button" class="btn d-flex align-items-center gap-2 d-none text-muted " onclicks="nextStep(4)">
                  Save and Verify <i class="ri-arrow-right-line"></i>
                </button>
            </div>
          </form>
        </div>
        @if(session('success'))
            <div class="alert alert-success success_register d-none">
                {{ session('success') }}
            </div>
        @endif
        <!-- Step 5: Success Message -->
        <div class="form-container rounded-custom p-4 form-step text-center" id="step-5">
          <div class="success-icon mb-3">
            <i class="ri-check-line"></i>
          </div>
          <h4 class="fw-bold mb-2">Verification Successful!</h4>
          <p class="text-muted mb-4">You can now log in and proceed with your loan application.</p>
          <a href="login" class="btn btn-primary-custom w-100">Log In</a>
        </div>
        <div class="mt-4 text-center">
          <p class="text-muted mb-2">Already have an account?</p>
          <button onclick="window.location.href='login'" class="btn btn-outline-secondary-custom w-50 fw-medium">Login</button>
        </div>
      </div>
    </main>
    <!-- Footer -->
    <footer class="bg-white py-3 border-top">
        <div class="container">
            <div class="text-center text-muted small">
                &copy; 2025 RAN Lending. All rights reserved.
            </div>
        </div>
    </footer>
  </div>
  
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.4/jquery-confirm.min.js"></script>
  <script src="{{ asset('js/auth.js') }}"></script>
  <script>

    // CHECK IF REGISTERED > REDIRECT TO OTP
    $(document).ready(function() {
        let success = $('.success_register').text();
        if (success) {
            nextStep(5)
        }
    });

    const steps = document.querySelectorAll('.form-step');
    const progressBar = document.getElementById('form-progress');

    function nextStep(n) {
      if (n > 1 && !validateStep(n)) return; 

      steps.forEach(step => step.classList.remove('active'));
      document.getElementById(`step-${n}`).classList.add('active');
      progressBar.style.width = `${n * 20}%`;
    }

    function prevStep(n) {
      steps.forEach(step => step.classList.remove('active'));
      document.getElementById(`step-${n}`).classList.add('active');
      progressBar.style.width = `${n * 20}%`;
    }

    //OTP GENERATE
  $(document).on('click' , '.authreggen' , function (e) {
    let dis = $(this);
    let email = $('input[name="email"]').val();
    const emailField = $('input[name="email"]');
    const emailError = $('#email-error');

    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!email || !emailRegex.test(email)) {
      emailField.addClass('is-invalid');
      emailError.removeClass('d-none');
      return; 
    } else {
      emailField.removeClass('is-invalid');
      emailError.addClass('d-none');
    }
    
    $.ajax({
      url: '{{ route("reg.auth.send") }}',
        method: 'POST',
        data: {
            email : email,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
          if (res == 1) {
            if (dis.hasClass('authreggenresend')) {
                 $('#otp-alert')
                .removeClass('d-none')
                .fadeIn();
                 setTimeout(function () {
                // Hide alert
                $('#otp-alert').fadeOut(function () {
                    $(this).addClass('d-none');
                });}, 3000);
            }else{
              prevStep(4)
            }
          }else if(res == 2){
            console.log('test');
            $('#email-exists-error').removeClass('d-none');
          }else{
            alert('error contact admin')
          }
        },
        error: function (xhr) {
        }
    });
  });

  //OTP VALIDATE
  $(document).on('click' , '#regauthcheck' , function (e) {


    let rawValue = $('#income').val().replace(/,/g, '');
    $('#income').val(rawValue); // set raw numeric value
    let email = $('input[name="email"]').val();
    let otp = '';
    $('.otp-input').each(function () {
        otp += $(this).val();
    });

    $.ajax({
      url: '{{ route("reg.auth.check") }}',
        method: 'POST',
        data: {
            email : email,
            otp : otp,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function (res) {
          if (res == 1) {
            $('#register-form').submit();
          }else{
            $('#otp-error').removeClass('d-none');
          }
        },
        error: function (xhr) {
        }
    });
  });

  </script>
</body>
</html>
