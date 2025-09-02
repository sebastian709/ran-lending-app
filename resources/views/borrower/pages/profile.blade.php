@extends('borrower.app')

@section('styles')
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }
    </style>

@endsection

@section('content')
    <div class="d-flex">
        @include('borrower.layouts.sidebar')

        <div class="main-content flex-grow-1">
            <div class="container pt-4">
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
            <div class="container">
                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-5">
                        <div class="row">
                            <!-- Sidebar Profile -->
                            <div class="col-md-3 mb-4 mb-md-0">
                                <div class="text-center">
                                    {{-- Avatar initials shown only if no profile picture --}}
                                    <p 
                                        class="user-avatar-profile-view mb-3 mx-auto" 
                                        {{ empty($usersInformation->profile_src) ? '' : 'hidden' }}
                                    >
                                        {{ strtoupper(substr(Auth::user()->firstname, 0, 1) . substr(Auth::user()->lastname, 0, 1)) }}
                                    </p>

                                    {{-- Profile picture shown only if it exists --}}
                                    <img 
                                        src="{{ !empty($usersInformation->profile_src) ? asset('storage/' . $usersInformation->profile_src) : '' }}" 
                                        class="shadow-sm mb-3 rounded-3" 
                                        alt="Profile"
                                        width="200" 
                                        height="200" 
                                        id="profile-picture-preview"
                                        {{ !empty($usersInformation->profile_src) ? '' : 'hidden' }}
                                    >

                                    

                                    <button class="btn btn-sm btn-outline-primary mt-1" id="change-picture-btn" hidden><i
                                            class="ri-camera-line me-1"></i> Change Photo</button>
                                </div>

                                <div class="mx-3 mt-2">

                                    <div class="mt-3 text-center">
                                        <h4 class="fw-bold mb-0">{{Auth::user()->firstname . ' ' . Auth::user()->lastname}}
                                        </h4>
                                        <small class="text-muted">{{ '@'.$usersInformation->username }}</small>
                                    </div>
                                    <hr>
                                    <!-- <div class="d-flex justify-content-between align-items-center"> -->
                                    <div class="text-center">
                                        <label class="small text-muted mb-0 w-100">Number of Loans:</label>
                                        <label class="fs-5 fw-semibold text-muted mb-0 w-100">10</label>
                                    </div>
                                    <div class="text-center">
                                        <label class="small text-muted mb-0 w-100">Max Credit Limit:</label>
                                        <label class="fs-5 fw-semibold text-muted mb-0 w-10">₱100,000</label>
                                    </div>
                                    <!-- </div> -->

                                </div>
                                <!-- <hr> -->
                                <div class="mt-5">
                                    <button class="btn btn-sm btn-outline-secondary w-100" id="edit-profile-btn">
                                        <i class="ri-edit-line me-1"></i> Edit
                                    </button>
                                    <button class="btn btn-sm btn-outline-primary w-100 mt-1" data-url="/loan-list">
                                        <i class="ri-file-list-line"></i> Loan List
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger w-100 mt-1" data-url="/borrower/change-password">
                                        <i class="ri-key-2-fill"></i> Change Password
                                    </button>
                                </div>
                            </div>

                            <!-- Profile Info -->
                            <div class="col-md-9">
                                <div class="d-flex justify-content-between align-items-center mb-3 mx-3">
                                    <div>
                                        <h4 class="fw-bold mb-0">Personal Information</h4>
                                        <small class="text-muted">Here’s what we know about you.</small>
                                    </div>
                                    <!-- <button class="btn btn-outline-secondary btn-sm rounded-pill" id="edit-profile-btn">
                                                        <i class="ri-edit-line me-1"></i> Edit
                                                    </button> -->
                                </div>

                                <form id="profile-form" class="mx-3" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row g-3">
                                        @php
                                            $fields = [
                                                ['colSize' => 'col-lg-6', 'label' => 'Username', 'name' => 'username', 'value' => $usersInformation->username],
                                                ['colSize' => 'col-lg-6', 'label' => 'First Name', 'name' => 'first_name', 'value' => $usersInformation->firstname],
                                                ['colSize' => 'col-lg-6', 'label' => 'Middle Name', 'name' => 'middle_name', 'value' => $usersInformation->middlename],
                                                ['colSize' => 'col-lg-6', 'label' => 'Last Name', 'name' => 'last_name', 'value' => $usersInformation->lastname],

                                                ['colSize' => 'col-lg-6', 'label' => 'House Number', 'name' => 'house_no', 'value' => $usersInformation->house_no],
                                                ['colSize' => 'col-lg-6', 'label' => 'Street', 'name' => 'street', 'value' => $usersInformation->street],
                                                ['colSize' => 'col-lg-4', 'label' => 'Barangay', 'name' => 'barangay', 'value' => $usersInformation->barangay],
                                                ['colSize' => 'col-lg-4', 'label' => 'City', 'name' => 'city', 'value' => $usersInformation->city],
                                                ['colSize' => 'col-lg-4', 'label' => 'Province', 'name' => 'province', 'value' => $usersInformation->province],

                                                ['colSize' => 'col-lg-6', 'label' => 'Occupation / Source of Income', 'name' => 'occupation', 'value' => $usersInformation->occupation],
                                                ['colSize' => 'col-lg-6', 'label' => 'Monthly Income', 'name' => 'income', 'value' => '₱' . $usersInformation->income],
                                            ];
                                        @endphp
                                        @foreach($fields as $field)
                                            <div class="{{ $field['colSize'] }}">
                                                <label class="form-label">{{ $field['label'] }}</label>
                                                <input type="text" name="{{ $field['name'] }}"
                                                    class="form-control profile-input {{ $field['name'] }}"
                                                    value="{{ $field['value'] }}" disabled>
                                            </div>
                                        @endforeach
                                        {{-- Employment Status dropdown --}}
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
                                        {{-- Employment Status dropdown --}}
                                        <div class="col-lg-6">
                                            <label class="form-label">Where did you find us?</label>
                                            <select class="form-select profile-input" name="referral_source_id" disabled>
                                                <option value="0" {{ $usersInformation->referral_source_id == 0 ? 'selected' : '' }}>Select</option>
                                                <option value="1" {{ $usersInformation->referral_source_id == 1 ? 'selected' : '' }}>Social Media</option>
                                                <option value="2" {{ $usersInformation->referral_source_id == 2 ? 'selected' : '' }}>Referral</option>
                                            </select>
                                        </div>

                                        <div class="col-lg-12 form-group" {{ $usersInformation->employment_status == 4 ? '' : 'hidden' }}>
                                            <label for="">if Others, Please specify</label>
                                            <input type="text" name="specify_others" value="{{ $usersInformation->specified_others }}" class="form-control specifyOthers" disabled>
                                        </div>
                                    </div>
                                    <input type="file" id="profile-picture-input" name="profile_picture" hidden>
                                    <div class="text-end mt-4 d-none" id="update-controls">
                                        <button type="button" class="btn btn-secondary me-2"
                                            id="cancel-edit-btn">Cancel</button>
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
@endsection