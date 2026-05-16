@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-center" style="min-height: calc(100vh - 56px); padding: 2rem 1rem;">
    <div style="width: 100%; max-width: 420px;">

        {{-- Brand mark --}}
        <div class="text-center mb-4">
            <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                 style="width:56px;height:56px;background:#eff6ff;border:2px solid #bfdbfe;">
                <i class="bi bi-people-fill fs-4" style="color:#2563eb;"></i>
            </div>
            <h1 style="font-size:1.35rem;font-weight:700;letter-spacing:-0.02em;color:#0f172a;" class="mb-1">
                {{ config('app.name') }}
            </h1>
            <p style="font-size:0.85rem;color:#64748b;">Sign in to manage captured people</p>
        </div>

        <div class="card" style="border-radius:16px;">
            <div class="card-body p-4">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;border-color:#cbd5e1;">
                                <i class="bi bi-envelope" style="color:#94a3b8;"></i>
                            </span>
                            <input id="email" type="email"
                                   class="form-control border-start-0 ps-0 @error('email') is-invalid @enderror"
                                   style="border-radius:0 8px 8px 0;"
                                   name="email" value="{{ old('email') }}"
                                   placeholder="you@example.com"
                                   required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius:8px 0 0 8px;border-color:#cbd5e1;">
                                <i class="bi bi-lock" style="color:#94a3b8;"></i>
                            </span>
                            <input id="password" type="password"
                                   class="form-control border-start-0 ps-0 @error('password') is-invalid @enderror"
                                   style="border-radius:0 8px 8px 0;"
                                   name="password"
                                   placeholder="••••••••"
                                   required autocomplete="current-password">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember" style="font-size:0.83rem;color:#475569;">
                                Remember me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" style="font-size:0.83rem;color:#2563eb;text-decoration:none;">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2" style="font-size:0.9rem;font-weight:600;letter-spacing:0.01em;">
                        <i class="bi bi-box-arrow-in-right me-2"></i>Sign In
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>
@endsection
