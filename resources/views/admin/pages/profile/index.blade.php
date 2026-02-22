@extends('admin.container')

@section('content')

    <div class="container-fluid p-4">
        <div class="section-title d-flex align-items-center">
            <i class="ri-user-settings-line me-2"></i> Profile
        </div>

        <div class="row my-2">
            <div class="col-lg-12 py-1">
                <div class="table-card">
                    <div class="card-body">
                        <div class="row">
                            <!-- Sidebar Profile -->
                            <div class="col-md-3 mb-2 mb-md-0">
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

                                <div class="mx-3">
                                    <div class="mt-1 text-center">
                                        <h4 class="fw-bold mb-0">{{Auth::user()->firstname . ' ' . Auth::user()->lastname}}
                                        </h4>
                                        <small class="text-muted">{{ '@'.$usersInformation->username }}</small>
                                    </div>
                                </div>
                                <!-- <hr> -->
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-secondary w-100" id="edit-profile-btn"><i
                                            class="ri-edit-line me-1"></i> Edit</button>

                                    <button class="btn btn-sm btn-outline-primary w-100 mt-1" data-url="/admin/profile/referral-management">
                                        <i class="ri-coupon-3-line"></i> Referral Code
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger w-100 mt-1" data-url="/admin/profile/change-password">
                                        <i class="ri-key-2-fill"></i> Change Password
                                    </button>
                                </div>
                            </div>

                            <!-- Profile Info -->
                            <div class="col-md-9 mb-5">
                                <div class="d-flex justify-content-between align-items-center mb-3 mx-3">
                                    <div>
                                        <h4 class="fw-bold mb-0">Personal Information</h4>
                                        <small class="text-muted">Here’s what we know about you.</small>
                                    </div>
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
