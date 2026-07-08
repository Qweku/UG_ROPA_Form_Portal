<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold" style="color: #153d6f;">
                <i class="fas fa-chart-line me-3" style="color: #b69964;"></i>
                Admin Dashboard
            </h1>
            <p class="text-muted">Welcome back, <?php echo e(Auth::user()->firstname); ?> <?php echo e(Auth::user()->surname); ?>! Here's an overview of the RoPA system.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h3 class="mb-0"><?php echo e($stats['total_forms']); ?></h3>
                    <p class="mb-0" style="opacity: 0.9;">Total RoPA Processes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="mb-0"><?php echo e($stats['completed_forms']); ?></h3>
                    <p class="mb-0" style="opacity: 0.9;">Completed</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-spinner fa-2x"></i>
                    </div>
                    <h3 class="mb-0"><?php echo e($stats['in_progress_forms']); ?></h3>
                    <p class="mb-0" style="opacity: 0.9;">In Progress</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="mb-0"><?php echo e($stats['total_users']); ?></h3>
                    <p class="mb-0" style="opacity: 0.9;">Registered Users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2" style="color: #b69964;"></i>
                        Sub-process Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <div style="max-width: 450px; margin: 0 auto; height: 300px; position: relative;">
                        <canvas id="statusChart"></canvas>
                    </div>
                    <div class="row mt-3 text-center">
                        <div class="col-4">
                            <div class="small text-muted">Total Sub-processes</div>
                            <strong><?php echo e($stats['total_submissions']); ?></strong>
                        </div>
                        <div class="col-4">
                            <div class="small text-muted">Completed</div>
                            <strong><?php echo e($stats['completed_submissions']); ?></strong>
                        </div>
                        <div class="col-4">
                            <div class="small text-muted">Draft</div>
                            <strong><?php echo e($stats['draft_submissions']); ?></strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2" style="color: #b69964;"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">RoPA processes created this month</small>
                        <h3 class="mb-0"><?php echo e($stats['forms_this_month']); ?></h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Active users (verified)</small>
                        <h3 class="mb-0"><?php echo e($stats['active_users']); ?></h3>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        <?php $verificationRate = $stats['total_users'] > 0 ? ($stats['active_users'] / $stats['total_users']) * 100 : 0; ?>
                        <div class="progress-bar" role="progressbar" style="width: <?php echo e($verificationRate); ?>%; background: #b69964;"></div>
                    </div>
                    <small class="text-muted"><?php echo e(round($verificationRate)); ?>% email verification rate</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Forms -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2" style="color: #b69964;"></i>
                        Recent RoPA Processes
                    </h5>
                    <a href="<?php echo e(route('admin.submitted-forms')); ?>" class="btn btn-sm btn-outline-accent">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th>ID</th>
                                    <th>Submitter</th>
                                    <th>Main Process</th>
                                    <th>Business Function</th>
                                    <th>Sub-processes</th>
                                    <th>Status</th>
                                    <th>Last Updated</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentForms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $form): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td>#<?php echo e($form->id); ?></td>
                                    <td>
                                        <strong><?php echo e($form->user->firstname ?? ''); ?> <?php echo e($form->user->surname ?? ''); ?></strong><br>
                                        <small class="text-muted"><?php echo e($form->user->email ?? 'N/A'); ?></small>
                                    </td>
                                    <td><?php echo e($form->main_process_name ?: 'N/A'); ?></td>
                                    <td><?php echo e($form->business_function ?? 'N/A'); ?></td>
                                    <td>
                                        <span class="badge bg-secondary"><?php echo e($form->submissions->count()); ?></span>
                                    </td>
                                    <td>
                                        <span class="badge bg-<?php echo e($form->all_submissions_completed ? 'success' : 'warning'); ?>">
                                            <?php echo e($form->all_submissions_completed ? 'Completed' : 'In Progress'); ?>

                                        </span>
                                    </td>
                                    <td><?php echo e($form->updated_at->diffForHumans()); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.view-form', $form)); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="8" class="text-center py-4">No forms found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2" style="color: #b69964;"></i>
                        New Users
                    </h5>
                    <a href="<?php echo e(route('admin.users')); ?>" class="btn btn-sm btn-outline-accent">
                        Manage Users <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered</th>
                                    <th>Verified</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $recentUsers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                <tr>
                                    <td><?php echo e($user->firstname); ?> <?php echo e($user->surname); ?></td>
                                    <td><?php echo e($user->email); ?></td>
                                    <td><?php echo e($user->created_at->diffForHumans()); ?></td>
                                    <td>
                                        <?php if($user->is_verified): ?>
                                        <span class="badge bg-success">Verified</span>
                                        <?php else: ?>
                                        <span class="badge bg-secondary">Pending</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4">No users found</td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const chartCanvas = document.getElementById('statusChart');

        if (!chartCanvas) {
            console.warn('statusChart canvas not found');
            return;
        }

        if (typeof Chart === 'undefined') {
            console.error('Chart.js failed to load');
            return;
        }

        const stats = {
            completed_submissions: <?php echo e($stats['completed_submissions'] ?? 0); ?>,
            draft_submissions: <?php echo e($stats['draft_submissions'] ?? 0); ?>

        };

        const ctx = chartCanvas.getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Completed',
                    'Draft / In Progress'
                ],
                datasets: [{
                    data: [
                        stats.completed_submissions,
                        stats.draft_submissions
                    ],
                    backgroundColor: [
                        '#28a745',
                        '#ffc107'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                aspectRatio: 1.5,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>