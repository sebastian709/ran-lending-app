@push('styles')
    <link rel="stylesheet" href="{{ asset('css/components/signature-box.css') }}">
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="{{ asset('js/components/signature-box.js') }}"></script>
@endpush

{{-- Signature Box --}}
<div class="signature-wrapper text-center position-relative">
    <div class="signature-target mx-auto">
        @if (isset($signature))
            <img src="{{ $signature }}" alt="Signature">
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
