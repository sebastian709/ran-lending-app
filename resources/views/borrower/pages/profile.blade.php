@extends('borrower.app')

@section('styles')
    <style>
        .profile-page .form-control:disabled,
        .profile-page .form-select:disabled {
            background-color: #f3f6fb;
            color: #3a4556;
            border-color: #d8e1ef;
            opacity: 1;
        }
    </style>
@endsection

@section('content')
    <div class="d-flex profile-page">
        @include('borrower.layouts.sidebar')

        <div class="main-content flex-grow-1">
            <div class="container pt-4" hidden>
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="dashboard-card bg-pp-notif-warning p-4 text-muted">
                            <div class="position-relative">
                                <div class="d-flex flex-column flex-md-row justify-content-between">
                                    <!-- LEFT TEXT -->
                                    <div class="pe-md-4">
                                        <h3 class="h5 fw-bold mb-1 welcome-text">Your Account at risk</h3>
                                        <p class="mb-0 small">You've missed some due dates. Stay on track by managing your loans today.</p>
                                    </div>

                                    <!-- CLOSE BUTTON (positioned top-right) -->
                                    <div class="position-absolute top-0 end-0 mt-2 me-2">
                                        <p style="cursor:pointer;" class="close-profile-notif mb-0"><i class="ri-close-large-line"></i></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="container py-4">
                <div class="card shadow-sm border-0 rounded-4 profile-shell">
                    <div class="card-body p-4 p-xl-5">
                        <div class="row g-4">
                            <div class="col-lg-4 col-xl-3">
                                <div class="profile-summary-card h-100">
                                    <div class="text-center profile-photo-zone">
                                        <p id="profile-initial-avatar" class="user-avatar-profile-view mb-3 mx-auto" {{ empty($usersInformation->profile_src) ? '' : 'hidden' }}>
                                            {{ strtoupper(substr(Auth::user()->firstname, 0, 1) . substr(Auth::user()->lastname, 0, 1)) }}
                                        </p>

                                        <img src="{{ !empty($usersInformation->profile_src) ? asset('storage/' . $usersInformation->profile_src) : '' }}"
                                            class="shadow-sm mb-3 rounded-3" alt="Profile" width="200" height="200"
                                            id="profile-picture-preview" {{ !empty($usersInformation->profile_src) ? '' : 'hidden' }}
                                            onerror="this.setAttribute('hidden','hidden'); var fallback=document.getElementById('profile-initial-avatar'); if(fallback){ fallback.removeAttribute('hidden'); }">

                                        <button class="btn btn-sm btn-outline-primary mt-1" id="change-picture-btn" type="button">
                                            <i class="ri-camera-line me-1"></i> Change Photo
                                        </button>
                                    </div>

                                    <div class="text-center mt-3">
                                        <h4 class="fw-bold mb-0">{{ Auth::user()->firstname . ' ' . Auth::user()->lastname }}</h4>
                                        <small class="text-muted">
                                            @if (!empty($usersInformation->username))
                                                {{ '@' . $usersInformation->username }}
                                            @endif
                                        </small>
                                    </div>

                                    <div class="profile-stat mt-4">
                                        <span class="label">Number of Loans</span>
                                        <span class="value">{{ $loanApplication ?? 0 }}</span>
                                    </div>

                                    <div class="d-grid gap-2 mt-4">
                                        <button class="btn btn-sm btn-outline-secondary" id="edit-profile-btn">
                                            <i class="ri-edit-line me-1"></i> Edit Profile
                                        </button>
                                        <button class="btn btn-sm btn-outline-primary" data-url="/loan-list">
                                            <i class="ri-file-list-line me-1"></i> Loan History
                                        </button>
                                        <button class="btn btn-sm btn-outline-warning" data-url="/payment-history">
                                            <i class="ri-money-dollar-circle-line me-1"></i> Payment History
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger" data-url="/borrower/change-password">
                                            <i class="ri-key-2-fill me-1"></i> Change Password
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-8 col-xl-9">
                                <div class="profile-form-card">
                                    <div class="profile-section-title">
                                        <h4 class="fw-bold mb-1">Personal Information</h4>
                                        <small class="text-muted">Review and update your account details.</small>
                                    </div>

                                    <form id="profile-form" enctype="multipart/form-data">
                                        @csrf

                                        <div class="profile-section-block">
                                            <h6 class="block-title">Account</h6>
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Username</label>
                                                    <input type="text" name="username" class="form-control profile-input username"
                                                        value="{{ $usersInformation->username }}" disabled>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" name="first_name" class="form-control profile-input first_name"
                                                        value="{{ $usersInformation->firstname }}" disabled>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Middle Name</label>
                                                    <input type="text" name="middle_name" class="form-control profile-input middle_name"
                                                        value="{{ $usersInformation->middlename }}" disabled>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" name="last_name" class="form-control profile-input last_name"
                                                        value="{{ $usersInformation->lastname }}" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="profile-section-block">
                                            <h6 class="block-title">Address</h6>
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label">House Number</label>
                                                    <input type="text" name="house_no" class="form-control profile-input house_no"
                                                        value="{{ $usersInformation->house_no }}" disabled>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Street</label>
                                                    <input type="text" name="street" class="form-control profile-input street"
                                                        value="{{ $usersInformation->street }}" disabled>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="form-label">Barangay</label>
                                                    <input type="text" name="barangay" class="form-control profile-input barangay"
                                                        value="{{ $usersInformation->barangay }}" disabled>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="form-label">City</label>
                                                    <input type="text" name="city" class="form-control profile-input city"
                                                        value="{{ $usersInformation->city }}" disabled>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label class="form-label">Province</label>
                                                    <input type="text" name="province" class="form-control profile-input province"
                                                        value="{{ $usersInformation->province }}" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="profile-section-block">
                                            <h6 class="block-title">Employment & Income</h6>
                                            <div class="row g-3">
                                                <div class="col-lg-6">
                                                    <label class="form-label">Occupation / Source of Income</label>
                                                    <input type="text" name="occupation" class="form-control profile-input occupation"
                                                        value="{{ $usersInformation->occupation }}" disabled>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Monthly Income</label>
                                                    <input type="text" name="income" class="form-control profile-input income"
                                                        value="{{ number_format((float) ($usersInformation->income ?? 0), 2) }}" disabled>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Employment Status</label>
                                                    <select class="form-select profile-input pEmploymentStatus" name="employment_status" disabled>
                                                        <option value="0" {{ $usersInformation->employment_status == 0 ? 'selected' : '' }}>Employment Status</option>
                                                        <option value="1" {{ $usersInformation->employment_status == 1 ? 'selected' : '' }}>Employed</option>
                                                        <option value="2" {{ $usersInformation->employment_status == 2 ? 'selected' : '' }}>Self employed</option>
                                                        <option value="3" {{ $usersInformation->employment_status == 3 ? 'selected' : '' }}>None</option>
                                                        <option value="4" {{ $usersInformation->employment_status == 4 ? 'selected' : '' }}>Others</option>
                                                    </select>
                                                </div>
                                                <div class="col-lg-6">
                                                    <label class="form-label">Where did you find us?</label>
                                                    <select class="form-select profile-input" name="referral_source_id" disabled>
                                                        <option value="0" {{ $usersInformation->referral_source_id == 0 ? 'selected' : '' }}>Select</option>
                                                        <option value="1" {{ $usersInformation->referral_source_id == 1 ? 'selected' : '' }}>Social Media</option>
                                                        <option value="2" {{ $usersInformation->referral_source_id == 2 ? 'selected' : '' }}>Referral</option>
                                                    </select>
                                                </div>

                                                <div class="col-lg-12 form-group" {{ $usersInformation->employment_status == 4 ? '' : 'hidden' }}>
                                                    <label class="form-label">If Others, Please specify</label>
                                                    <input type="text" name="specify_others" value="{{ $usersInformation->specified_others }}"
                                                        class="form-control specifyOthers" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <input type="file" id="profile-picture-input" name="profile_picture" hidden>

                                        <div class="text-end mt-4 d-none" id="update-controls">
                                            <button type="button" class="btn btn-secondary me-2" id="cancel-edit-btn">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
