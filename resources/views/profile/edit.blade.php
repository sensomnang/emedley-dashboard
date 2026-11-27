@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('content')
    <div class="row g-4">

        {{-- LEFT: Profile card --}}
        <div class="col-lg-4">
            <div class="panel text-center h-100">
                <div class="profile-avatar mx-auto mb-3">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h5 class="fw-bold mb-1">{{ $user->name }}</h5>
                <p class="text-muted small mb-3">{{ $user->email }}</p>
                <span class="badge-soft">Manager</span>

                <hr class="my-4">

                <div class="text-start small">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Member since</span>
                        <span class="fw-semibold">{{ $user->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Status</span>
                        <span class="fw-semibold text-success">Active</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Forms --}}
        <div class="col-lg-8">

            {{-- Profile information --}}
            <div class="panel mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="stat-icon" style="background:#2f7d5b;"><i class="bi bi-person-fill"></i></div>
                    <h6 class="fw-bold mb-0">Profile Information</h6>
                </div>
                <p class="text-muted small mb-4">Update your account's name and email address.</p>

                <form method="POST" action="{{ route('profile.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Full name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                   class="form-control @error('email') is-invalid @enderror" required>
                            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <button class="btn btn-success rounded-pill px-4 mt-4">Save changes</button>
                </form>
            </div>

            {{-- Update password --}}
            <div class="panel mb-4">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="stat-icon" style="background:#f4a261;"><i class="bi bi-shield-lock-fill"></i></div>
                    <h6 class="fw-bold mb-0">Update Password</h6>
                </div>
                <p class="text-muted small mb-4">Use a long, random password to stay secure.</p>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('PUT')

                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label">Current password</label>
                            <input type="password" name="current_password"
                                   class="form-control @error('current_password', 'updatePassword') is-invalid @enderror">
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">New password</label>
                            <input type="password" name="password"
                                   class="form-control @error('password', 'updatePassword') is-invalid @enderror">
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Confirm password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>
                    </div>

                    <button class="btn btn-success rounded-pill px-4 mt-4">Update password</button>
                </form>
            </div>

            {{-- Delete account --}}
            <div class="panel danger-zone">
                <div class="d-flex align-items-center gap-2 mb-1">
                    <div class="stat-icon" style="background:#e5484d;"><i class="bi bi-exclamation-triangle-fill"></i></div>
                    <h6 class="fw-bold mb-0 text-danger">Danger Zone</h6>
                </div>
                <p class="text-muted small mb-4">
                    Once your account is deleted, all of its data will be permanently removed.
                </p>

                <button type="button" class="btn btn-outline-danger rounded-pill px-4"
                        data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                    Delete account
                </button>
            </div>
        </div>
    </div>

    {{-- Delete account modal --}}
    <div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content border-0 shadow-lg rounded-4 text-center p-3">
                <div class="modal-body">
                    <div class="modal-icon-danger mx-auto mb-3"><i class="bi bi-person-x-fill"></i></div>
                    <h5 class="fw-bold mb-1">Delete account?</h5>
                    <p class="text-muted small mb-3">Enter your password to confirm. This cannot be undone.</p>

                    <form method="POST" action="{{ route('profile.destroy') }}">
                        @csrf
                        @method('DELETE')

                        <input type="password" name="password" placeholder="Password"
                               class="form-control mb-3 @error('password', 'userDeletion') is-invalid @enderror">
                        @error('password', 'userDeletion')
                            <div class="text-danger small mb-2">{{ $message }}</div>
                        @enderror

                        <div class="d-flex gap-2 justify-content-center">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger rounded-pill px-4">Delete</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @if ($errors->userDeletion->isNotEmpty())
        <script>
            new bootstrap.Modal(document.getElementById('deleteAccountModal')).show();
        </script>
    @endif
@endpush