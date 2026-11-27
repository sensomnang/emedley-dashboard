@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <h3 class="fw-bold mb-1">Welcome back 👋</h3>
    <p class="text-muted mb-4">Please sign in to continue.</p>

    @if (session('status'))
        <div class="alert alert-success py-2 small">{{ session('status') }}</div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="mb-3">
            <label class="form-label">Email</label>
            <div class="input-icon">
                <i class="bi bi-envelope"></i>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="form-control @error('email') is-invalid @enderror"
                       placeholder="you@example.com" required autofocus>
            </div>
            @error('email') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Password</label>
            <div class="input-icon">
                <i class="bi bi-lock"></i>
                <input type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="••••••••" required>
            </div>
            @error('password') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                <label class="form-check-label small text-muted" for="remember">Remember me</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="small auth-link">Forgot password?</a>
            @endif
        </div>

        <button class="btn btn-success rounded-pill w-100 py-2 fw-semibold">Sign in</button>
    </form>

    <p class="text-center text-muted small mt-4 mb-0">
        Don't have an account?
        <a href="{{ route('register') }}" class="auth-link fw-semibold">Register</a>
    </p>
@endsection