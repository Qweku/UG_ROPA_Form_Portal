<?php $__env->startSection('title', 'Manage RoPA Forms'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold" style="color: #153d6f;">
                        <i class="fas fa-clipboard-list me-3" style="color: #b69964;"></i>
                        Manage RoPA Forms
                    </h1>
                    <p class="text-muted">Monitor every RoPA process and its sub-processes across the university</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success" onclick="exportForms()">
                        <i class="fas fa-download me-2"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Total Processes</small>
                        <h3 class="mb-0"><?php echo e($stats['total']); ?></h3>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-file-alt fa-2x" style="color: #153d6f;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Completed</small>
                        <h3 class="mb-0"><?php echo e($stats['completed']); ?></h3>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-check-circle fa-2x" style="color: #28a745;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">In Progress</small>
                        <h3 class="mb-0"><?php echo e($stats['in_progress']); ?></h3>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-spinner fa-2x" style="color: #ffc107;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.submitted-forms')); ?>" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="ID, process name, submitter..." value="<?php echo e(request('search')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" <?php echo e(request('status') == 'all' ? 'selected' : ''); ?>>All Status</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed</option>
                        <option value="in_progress" <?php echo e(request('status') == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Created From</label>
                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Created To</label>
                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                        <a href="<?php echo e(route('admin.submitted-forms')); ?>" class="btn btn-secondary">
                            <i class="fas fa-undo me-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Forms Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th>ID</th>
                            <th>Submitter</th>
                            <th>Business Function</th>
                            <th>Main Process</th>
                            <th>Sub-processes</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $forms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="fw-bold">#<?php echo e($form->id); ?></td>
                            <td>
                                <strong><?php echo e($form->user->firstname ?? ''); ?> <?php echo e($form->user->surname ?? ''); ?></strong><br>
                                <small class="text-muted"><?php echo e($form->user->email ?? 'N/A'); ?></small>
                            </td>
                            <td><?php echo e($form->business_function ?? 'N/A'); ?></td>
                            <td><?php echo e($form->main_process_name ?: 'N/A'); ?></td>
                            <td>
                                <?php
                                    $completedCount = $form->submissions->where('status', 'completed')->count();
                                    $totalCount = $form->submissions->count();
                                ?>
                                <span class="badge bg-secondary"><?php echo e($completedCount); ?> / <?php echo e($totalCount); ?> completed</span>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo e($form->all_submissions_completed ? 'success' : 'warning'); ?> px-3 py-2">
                                    <i class="fas fa-<?php echo e($form->all_submissions_completed ? 'check' : 'clock'); ?> me-1"></i>
                                    <?php echo e($form->all_submissions_completed ? 'Completed' : 'In Progress'); ?>

                                </span>
                            </td>
                            <td>
                                <small><?php echo e($form->updated_at->format('M d, Y H:i')); ?></small>
                            </td>
                            <td>
                                <a href="<?php echo e(route('admin.view-form', $form)); ?>" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No RoPA forms found</p>
                            </td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($forms->isNotEmpty()): ?>
            <div class="p-3 bg-light d-flex justify-content-end">
                <?php echo e($forms->links()); ?>

            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    function exportForms() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '<?php echo e(route("admin.export-forms")); ?>';

        // Add current filters
        const search = document.querySelector('input[name="search"]')?.value;
        const status = document.querySelector('select[name="status"]')?.value;
        const dateFrom = document.querySelector('input[name="date_from"]')?.value;
        const dateTo = document.querySelector('input[name="date_to"]')?.value;

        if (search) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'search';
            input.value = search;
            form.appendChild(input);
        }
        if (status && status !== 'all') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = status;
            form.appendChild(input);
        }
        if (dateFrom) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_from';
            input.value = dateFrom;
            form.appendChild(input);
        }
        if (dateTo) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_to';
            input.value = dateTo;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/admin/submitted-forms.blade.php ENDPATH**/ ?>