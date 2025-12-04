 <!-- Where Did You Find Us? -->
@if (!isset($hasFeedback) || !$hasFeedback)
<div class="row mt-4 engage_div">
    <div class="col-12">
        <div class="dashboard-card p-4 engagements">
            <h4 class="fw-bold mb-3">Where Did You Find Us?</h4>
            <p class="text-muted mb-4">Help us improve by letting us know how you discovered RAN Lending.</p>

            <div class="row g-4">

                <!-- Social Media -->
                <div class="col-md-6">
                    <div class="p-3 border rounded">
                        <div class="d-flex align-items-center mb-3">
                            <i class="ri-share-line fs-3 text-primary me-3"></i>
                            <h6 class="fw-bold mb-0">Social Media</h6>
                        </div>

                        <div class="ms-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="social_media_option" id="sm_facebook" value="Facebook">
                                <label class="form-check-label" for="sm_facebook">Facebook</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="social_media_option" id="sm_tiktok" value="TikTok">
                                <label class="form-check-label" for="sm_tiktok">TikTok</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="social_media_option" id="sm_instagram" value="Instagram">
                                <label class="form-check-label" for="sm_instagram">Instagram</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Referral -->
                <div class="col-md-6">
                    <div class="p-3 border rounded">
                        <div class="d-flex align-items-center mb-3">
                            <i class="ri-user-star-line fs-3 text-success me-3"></i>
                            <h6 class="fw-bold mb-0">Referral</h6>
                        </div>

                        <div class="ms-4">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="referral_option" id="ref_admin" value="Admin">
                                <label class="form-check-label" for="ref_admin">Admin</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="referral_option" id="ref_friends" value="Friends">
                                <label class="form-check-label" for="ref_friends">Friends</label>
                            </div>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="referral_option" id="ref_social_media" value="Social Media">
                                <label class="form-check-label" for="ref_social_media">Social Media</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-left">
                    <button class="btn btn-primary-custom btn-md w-auto submit_feedback">
                            <i class="ri-arrow-right-line me-2"></i> Submit
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endif