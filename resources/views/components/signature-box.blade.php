@once
    @push('sb-styles')
        <link rel="stylesheet" href="{{ asset('css/components/signature-box.css') }}">
    @endpush

    @push('sb-scripts')
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script src="{{ asset('js/components/signature-box.js') }}"></script>
    @endpush
@endonce

@php
    $customStyleAttr = isset($customWidth) ? "style=width:{$customWidth}" : '';
@endphp

{{-- Signature Box --}}
<div class="signature-wrapper text-center position-relative" {!! $customStyleAttr !!}>
    <div class="signature-target mx-auto {{ isset($signature) ? 'filled' : '' }}">
        @if (isset($signature))
            <div class="position-relative d-inline-block">
                <img src="{{ $signature }}" alt="Signature">
            </div>
            <a href="{{ $signature }}" 
                download="signature.png" 
                class="signature-download-btn position-absolute"
                onclick="event.stopPropagation();" 
                style="top: 0.25rem; right: 0.25rem;" 
                data-bs-toggle="tooltip"
                title="Download Signature">
                <i class="bi bi-download fs-5"></i>
            </a>
        @else
            <span class="signature-mark-text">Click to Sign</span>
        @endif


    </div>

    <div class="signature-info mt-0 pt-4">
        <hr class="mb-2" style="width: 100%;">
        <p class="mb-0 fw-semibold">{{ $name ?? 'Name Here' }}</p>
        <small class="text-muted">{{ $position ?? 'Position Here' }}</small>
    </div>
</div>