@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold" style="color: #153d6f;">
                        <i class="fas fa-users me-3" style="color: #b69964;"></i>
                        Manage Users
                    </h1>
                    <p class="text-muted">Add, edit, or remove user accounts</p>
                </div>
                <div>
                    <a href="{{ route('admin.users.create') }}" class="btn btn-accent btn-lg">
                        <i class="fas fa-user-plus me-2"></i> Add User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.users') }}" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, email, personnel ID..." value="{{ request('search') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>User</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.users') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Users Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Personnel ID</th>
                            <th>Role</th>
                            <th>Verified</th>
                            <th>RoPA Forms</th>
                            <th>Joined</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td class="fw-bold">{{ $user->firstname }} {{ $user->surname }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->personnel_id ?? 'N/A' }}</td>
                            <td>
                                <span class="badge bg-{{ $user->role === 'admin' ? 'primary' : 'secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td>
                                @if($user->is_verified)
                                    <span class="badge bg-success">Verified</span>
                                @else
                                    <span class="badge bg-secondary">Pending</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border">{{ $user->ropa_forms_count }}</span>
                            </td>
                            <td><small>{{ $user->created_at->format('M d, Y') }}</small></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Reset Password" data-bs-toggle="modal" data-bs-target="#resetPasswordModal{{ $user->id }}">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    @if($user->id !== auth()->id())
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteUserModal{{ $user->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    @endif
                                </div>

                                <!-- Reset Password Modal -->
                                <div class="modal fade" id="resetPasswordModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.users.reset-password', $user) }}">
                                                @csrf
                                                @method('PUT')
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reset Password &mdash; {{ $user->firstname }} {{ $user->surname }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label class="form-label">New Password</label>
                                                        <input type="password" name="password" class="form-control" minlength="8" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label class="form-label">Confirm New Password</label>
                                                        <input type="password" name="password_confirmation" class="form-control" minlength="8" required>
                                                    </div>
                                                    <small class="text-muted">The user will need this new password the next time they log in. They won't be notified automatically &mdash; share it with them directly.</small>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-accent">Set New Password</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>

                                <!-- Delete User Modal -->
                                @if($user->id !== auth()->id())
                                <div class="modal fade" id="deleteUserModal{{ $user->id }}" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                                @csrf
                                                @method('DELETE')
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">Delete {{ $user->firstname }} {{ $user->surname }}?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>This will <strong>permanently</strong> delete this user account.</p>
                                                    @if($user->ropa_forms_count > 0)
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            This will also permanently delete all <strong>{{ $user->ropa_forms_count }}</strong> RoPA form(s) and every sub-process submission under them. This cannot be undone.
                                                        </div>
                                                    @endif
                                                    <div class="mb-0">
                                                        <label class="form-label">Type <strong>DELETE</strong> to confirm</label>
                                                        <input type="text" class="form-control delete-confirm-input" data-confirm-word="DELETE" required>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                    <button type="submit" class="btn btn-danger delete-confirm-submit" disabled>Delete Forever</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No users found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($users->isNotEmpty())
            <div class="p-3 bg-light d-flex justify-content-end">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Require typing "DELETE" before the destructive delete button is enabled,
    // for every delete-user modal on the page.
    document.querySelectorAll('.delete-confirm-input').forEach(function(input) {
        const modal = input.closest('.modal-content');
        const submitBtn = modal.querySelector('.delete-confirm-submit');
        const confirmWord = input.getAttribute('data-confirm-word');

        input.addEventListener('input', function() {
            submitBtn.disabled = input.value.trim() !== confirmWord;
        });
    });
</script>
@endpush
@endsection
