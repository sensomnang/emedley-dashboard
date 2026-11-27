@php
    $successMessage = session('success');

    if (session('status') === 'profile-updated') {
        $successMessage = 'Profile updated successfully.';
    }
    if (session('status') === 'password-updated') {
        $successMessage = 'Password updated successfully.';
    }

    $errorMessage = session('error');
@endphp

@if ($successMessage || $errorMessage)
    <div class="toast-container position-fixed top-0 end-0 p-4" style="z-index: 1100;">
        <div id="appToast"
             class="toast align-items-center border-0 shadow-lg rounded-4 overflow-hidden
                    {{ $successMessage ? 'toast-success' : 'toast-error' }}"
             role="alert" data-bs-delay="3500">
            <div class="d-flex align-items-center p-2">
                <div class="toast-icon me-2">
                    <i class="bi {{ $successMessage ? 'bi-check-circle-fill' : 'bi-x-circle-fill' }}"></i>
                </div>
                <div class="toast-body fw-medium">
                    {{ $successMessage ?? $errorMessage }}
                </div>
                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        new bootstrap.Toast(document.getElementById('appToast')).show();
    </script>
    @endpush
@endif