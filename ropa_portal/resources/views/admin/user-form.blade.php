@extends('layouts.app')

@section('title', $user ? 'Edit User' : 'Add User')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="mb-4">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.users') }}">Manage Users</a></li>
                        <li class="breadcrumb-item active">{{ $user ? 'Edit User' : 'Add User' }}</li>
                    </ol>
                </nav>
                <h1 class="display-6 fw-bold" style="color: #153d6f;">
                    <i class="fas fa-{{ $user ? 'user-edit' : 'user-plus' }} me-2" style="color: #b69964;"></i>
                    {{ $user ? 'Edit User' : 'Add New User' }}
                </h1>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <form method="POST" action="{{ $user ? route('admin.users.update', $user) : route('admin.users.store') }}">
                        @csrf
                        @if($user)
                            @method('PUT')
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $user->firstname ?? '') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Surname</label>
                                <input type="text" name="surname" class="form-control" value="{{ old('surname', $user->surname ?? '') }}" required>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Personnel ID</label>
                                <input type="text" name="personnel_id" class="form-control" value="{{ old('personnel_id', $user->personnel_id ?? '') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="user" {{ old('role', $user->role ?? 'user') === 'user' ? 'selected' : '' }}>User</option>
                                    <option value="admin" {{ old('role', $user->role ?? '') === 'admin' ? 'selected' : '' }}>Admin</option>
                                </select>
                            </div>

                            @if($user)
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="form-check form-switch mt-4">
                                        <input class="form-check-input" type="checkbox" name="is_verified" value="1" id="isVerifiedSwitch" {{ old('is_verified', $user->is_verified) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="isVerifiedSwitch">Email Verified</label>
                                    </div>
                                </div>
                            @endif

                            @unless($user)
                                <div class="col-md-6">
                                    <label class="form-label">Password</label>
                                    <input type="password" name="password" class="form-control" minlength="8" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                                </div>
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-info-circle me-1"></i>
                                        This user will receive an OTP verification email and must confirm it before they can log in &mdash; the same flow as self-registration.
                                    </small>
                                </div>
                            @endunless
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-accent btn-lg">
                                <i class="fas fa-save me-2"></i> {{ $user ? 'Save Changes' : 'Create User' }}
                            </button>
                            <a href="{{ route('admin.users') }}" class="btn btn-secondary btn-lg">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>

            @if($user)
                <div class="card border-0 shadow-sm mt-4">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="fas fa-key me-2"></i> Need to reset this user's password instead?
                        </h6>
                        <p class="text-muted small mb-3">Password changes are handled separately from profile edits to avoid accidentally clearing it. Go to the Manage Users list and use the reset-password action for this user.</p>
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Manage Users
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
