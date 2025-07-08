<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Register - RAN Lending</title>
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
          <a href="login.html" class="d-flex align-items-center text-decoration-none text-muted">
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
          <form id="register-form">
            <h5 class="mb-3">Personal Information</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Last Name" required pattern="\S+" />
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="First Name" required pattern="\S+" />
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Middle Name" required pattern="\S+" />
              </div>
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Contact Number" required pattern="\d{4}-\d{3}-\d{4}" />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="House No." required />
              </div>
              <div class="col-md-8">
                <input type="text" class="form-control" placeholder="Street Name" required />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Barangay" required />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="City" required />
              </div>
              <div class="col-md-4">
                <input type="text" class="form-control" placeholder="Province" required />
              </div>
            </div>
            <div class="d-flex justify-content-end mt-4">
              <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="nextStep(2)">
                Next <i class="ri-arrow-right-line"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Step 2: Employment & Referral -->
        <div class="form-container rounded-custom p-4 form-step" id="step-2">
          <form>
            <h5 class="mb-3">Employment</h5>
            <div class="row g-3">
              <div class="col-md-6">
                <input type="text" class="form-control" placeholder="Occupation/Source of Income" required />
              </div>
              <div class="col-md-6">
                <input type="number" class="form-control" placeholder="Monthly Income" required />
              </div>
              <div class="col-md-6">
                <select class="form-select" required>
                  <option value="">Employment Status</option>
                  <option>Employed</option>
                  <option>Self employed</option>
                  <option>None</option>
                </select>
              </div>
            </div>
            <hr class="my-4">
            <h5 class="mb-3">Where did you find us?</h5>
            <div class="row g-3">
              <div class="col-md-12">
                <select class="form-select" id="referral-source" required>
                  <option value="">Select</option>
                  <option>Social Media</option>
                  <option>Referral</option>
                </select>
              </div>
              <div class="col-md-12 d-none" id="referral-names">
                <select class="form-select">
                  <option>Ms. NC</option>
                  <option>Ms. Riki</option>
                  <option>Ms. Almira</option>
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
          </form>
        </div>

        <!-- Step 3: Account Creation -->
        <div class="form-container rounded-custom p-4 form-step" id="step-3">
          <form>
            <div class="mb-3">
              <input type="email" class="form-control" placeholder="Email Address or Mobile Number" required />
              <div class="invalid-feedback d-none" id="email-error">Please enter a valid email address.</div>
            </div>
            <div class="mb-3">
              <input type="password" class="form-control" placeholder="Password" required />
              <ul class="list-unstyled small text-muted password-criteria mt-2">
                <li>Minimum 8 characters</li>
                <li>Uppercase and lowercase letters</li>
                <li>At least one number</li>
                <li>At least one special character</li>
              </ul>
            </div>
            <div class="d-flex justify-content-between mt-4">
              <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="prevStep(2)">
                <i class="ri-arrow-left-line"></i>Previous
              </button>
              <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="nextStep(4)">
                Save and Verify <i class="ri-arrow-right-line"></i>
              </button>
            </div>
          </form>
        </div>

        <!-- Step 4: Verification -->
        <div class="form-container rounded-custom p-4 form-step" id="step-4">
          <div class="text-center mb-4">
            <h4 class="fw-bold">RAN Serenity Lending Account verification</h4>
            <p class="text-muted small">We've sent a 6-digit verification code to your email or phone.<br>This code is valid for 5 minutes.</p>
          </div>
          <form onsubmit="event.preventDefault(); nextStep(5);">
            <div class="d-flex justify-content-center mb-3">
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
              <input type="text" maxlength="1" class="otp-input" />
            </div>
            <div class="text-center">
              <button type="submit" class="btn btn-primary-custom w-100">Verify Code</button>
              <p class="small text-muted mt-2 mb-0">Didn't receive the code? <button type="button" class="btn btn-link text-primary-custom p-0">Resend Code</button></p>
            </div>
            <div class="d-flex justify-content-between mt-4">
                <button type="button" class="btn d-flex align-items-center gap-2 text-muted" onclick="prevStep(3)">
                  <i class="ri-arrow-left-line"></i>Previous
                </button>
                <button type="button" class="btn d-flex align-items-center gap-2 d-none text-muted" onclick="nextStep(4)">
                  Save and Verify <i class="ri-arrow-right-line"></i>
                </button>
            </div>
          </form>
        </div>

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
  </div>
  <script>
    const steps = document.querySelectorAll('.form-step');
    const progressBar = document.getElementById('form-progress');
    function nextStep(n) {
      steps.forEach(step => step.classList.remove('active'));
      document.getElementById(`step-${n}`).classList.add('active');
      progressBar.style.width = `${n * 20}%`;
    }

    function prevStep(n) {
      steps.forEach(step => step.classList.remove('active'));
      document.getElementById(`step-${n}`).classList.add('active');
      progressBar.style.width = `${n * 20}%`;
    }

    document.getElementById('referral-source').addEventListener('change', function() {
      const referralNames = document.getElementById('referral-names');
      referralNames.classList.toggle('d-none', this.value !== 'Referral');
    });
  </script>
</body>
</html>
