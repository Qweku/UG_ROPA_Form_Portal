<?php $__env->startSection('title', 'Manage Users'); ?>

<?php $__env->startSection('content'); ?>
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
                    <a href="<?php echo e(route('admin.users.create')); ?>" class="btn btn-accent btn-lg">
                        <i class="fas fa-user-plus me-2"></i> Add User
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.users')); ?>" class="row g-3">
                <div class="col-md-5">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="Name, email, personnel ID..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="all" <?php echo e(request('role') == 'all' ? 'selected' : ''); ?>>All Roles</option>
                        <option value="user" <?php echo e(request('role') == 'user' ? 'selected' : ''); ?>>User</option>
                        <option value="admin" <?php echo e(request('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                        <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-secondary">
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
                        <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold"><?php echo e($user->firstname); ?> <?php echo e($user->surname); ?></td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->personnel_id ?? 'N/A'); ?></td>
                            <td>
                                <span class="badge bg-<?php echo e($user->role === 'admin' ? 'primary' : 'secondary'); ?>">
                                    <?php echo e(ucfirst($user->role)); ?>

                                </span>
                            </td>
                            <td>
                                <?php if($user->is_verified): ?>
                                    <span class="badge bg-success">Verified</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Pending</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border"><?php echo e($user->ropa_forms_count); ?></span>
                            </td>
                            <td><small><?php echo e($user->created_at->format('M d, Y')); ?></small></td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="<?php echo e(route('admin.users.edit', $user)); ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" title="Reset Password" data-bs-toggle="modal" data-bs-target="#resetPasswordModal<?php echo e($user->id); ?>">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <?php if($user->id !== auth()->id()): ?>
                                        <button type="button" class="btn btn-sm btn-outline-danger" title="Delete" data-bs-toggle="modal" data-bs-target="#deleteUserModal<?php echo e($user->id); ?>">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <!-- Reset Password Modal -->
                                <div class="modal fade" id="resetPasswordModal<?php echo e($user->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="<?php echo e(route('admin.users.reset-password', $user)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Reset Password &mdash; <?php echo e($user->firstname); ?> <?php echo e($user->surname); ?></h5>
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
                                <?php if($user->id !== auth()->id()): ?>
                                <div class="modal fade" id="deleteUserModal<?php echo e($user->id); ?>" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <form method="POST" action="<?php echo e(route('admin.users.destroy', $user)); ?>">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-danger">Delete <?php echo e($user->firstname); ?> <?php echo e($user->surname); ?>?</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <p>This will <strong>permanently</strong> delete this user account.</p>
                                                    <?php if($user->ropa_forms_count > 0): ?>
                                                        <div class="alert alert-warning">
                                                            <i class="fas fa-exclamation-triangle me-2"></i>
                                                            This will also permanently delete all <strong><?php echo e($user->ropa_forms_count); ?></strong> RoPA form(s) and every sub-process submission under them. This cannot be undone.
                                                        </div>
                                                    <?php endif; ?>
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
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-user-slash fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No users found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($users->isNotEmpty()): ?>
            <div class="p-3 bg-light d-flex justify-content-end">
                <?php echo e($users->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
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
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/admin/users.blade.php ENDPATH**/ ?>