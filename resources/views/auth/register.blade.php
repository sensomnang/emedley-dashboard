@extends('layouts.guest')

@section('title', 'Register')

@section('content')
    <h3 class="fw-bold mb-1">Create account ✨</h3>
    <p class="text-muted mb-4">Fill in the details to get started.</p>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Full name</label>
            <div class="input-icon">
                <i class="bi bi-person"></i>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="form-control @error('name') is-invalid @enderror"
                       placeholder="Your name" required autofocus>
            </div>
            @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-icon">
                <i class="bi bi-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="you@example.com" required>
            </div>
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label">Password</label>
                <div class="input-icon">
                    <i class="bi bi-lock"></i>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror"
                           placeholder="••••••••" required>
                </div>
                @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label">Confirm password</label>
                <div class="input-icon">
                    <i class="bi bi-shield-lock"></i>
                    <input type="password" name="password_confirmation"
                           class="form-control" placeholder="••••••••" required>
                </div>
            </div>
        </div>

        <button class="btn btn-success rounded-pill w-100 py-2 fw-semibold">Create account</button>
    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        Already have an account?
        <a href="{{ route('login') }}" class="auth-link fw-semibold">Sign in</a>
    </p>
@endsection