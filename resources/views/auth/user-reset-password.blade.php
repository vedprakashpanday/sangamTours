@extends('layouts.user_master')

@section('user_content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
                <div class="p-4 text-center text-white" style="background: var(--brand-blue);">
                    <h4 class="fw-bold mb-0">Set New Password</h4>
                    <p class="small opacity-75 mb-0">For {{ $email }}</p>
                </div>
                <div class="card-body p-4 p-md-5 bg-white">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="email" value="{{ $email }}">

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">New Password</label>
                            <input type="password" name="password" class="form-control auth-form-control @error('password') is-invalid @enderror" required placeholder="Min. 8 characters">
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Confirm New Password</label>
                            <input type="password" name="password_confirmation" class="form-control auth-form-control" required placeholder="Repeat password">
                        </div>

                        <button type="submit" class="btn w-100 auth-btn text-white" style="background: var(--brand-accent);">
                            Update Password & Login
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection