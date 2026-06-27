<?php $__env->startSection('title', 'View RoPA Form #' . $ropaForm->id); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.dashboard')); ?>">Admin Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('admin.submitted-forms')); ?>">Manage RoPA Forms</a></li>
                            <li class="breadcrumb-item active">Form #<?php echo e($ropaForm->id); ?></li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i> Print
                    </button>
                    <a href="<?php echo e(route('admin.submitted-forms')); ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-<?php echo e($ropaForm->all_submissions_completed ? 'success' : 'warning'); ?> border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-<?php echo e($ropaForm->all_submissions_completed ? 'check-circle' : 'clock'); ?> fa-2x me-3"></i>
                    <div>
                        <strong>
                            <?php echo e($ropaForm->all_submissions_completed ? 'All sub-processes completed' : 'In progress'); ?>

                        </strong>
                        <br>
                        <small>
                            <?php echo e($ropaForm->submissions->where('status', 'completed')->count()); ?> of <?php echo e($ropaForm->submissions->count()); ?> sub-processes completed
                            &middot; Last updated <?php echo e($ropaForm->updated_at->diffForHumans()); ?>

                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #b69964;"></i>
                        Process Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="text-muted">Main Process Name</small>
                            <p class="mb-0 fw-bold"><?php echo e($ropaForm->main_process_name ?: 'N/A'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">College</small>
                            <p class="mb-0 fw-bold"><?php echo e($ropaForm->college->name ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Business Function / School</small>
                            <p class="mb-0 fw-bold"><?php echo e($ropaForm->business_function ?? 'N/A'); ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Submitted By</small>
                            <p class="mb-0 fw-bold"><?php echo e($ropaForm->user->firstname ?? ''); ?> <?php echo e($ropaForm->user->surname ?? ''); ?></p>
                            <small class="text-muted"><?php echo e($ropaForm->user->email ?? 'N/A'); ?></small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Created</small>
                            <p class="mb-0 fw-bold"><?php echo e($ropaForm->created_at->format('M d, Y H:i')); ?></p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Has Sub-processes?</small>
                            <p class="mb-0 fw-bold"><?php echo e($ropaForm->has_sub_processes ? 'Yes' : 'No'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-process Submissions Accordion -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-layer-group me-2" style="color: #b69964;"></i>
                        Sub-processes (<?php echo e($ropaForm->submissions->count()); ?>)
                    </h5>
                </div>
                <div class="card-body">
                    <?php if($ropaForm->submissions->isEmpty()): ?>
                        <p class="text-muted text-center py-4 mb-0">No sub-processes have been added to this form yet.</p>
                    <?php else: ?>
                        <div class="accordion" id="adminAccordion<?php echo e($ropaForm->id); ?>">
                            <?php $__currentLoopData = $ropaForm->submissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="adminHeading<?php echo e($ropaForm->id); ?>_<?php echo e($index); ?>">
                                        <button class="accordion-button <?php echo e($index > 0 ? 'collapsed' : ''); ?>" type="button" data-bs-toggle="collapse" data-bs-target="#adminCollapse<?php echo e($ropaForm->id); ?>_<?php echo e($index); ?>" aria-expanded="<?php echo e($index == 0 ? 'true' : 'false'); ?>" aria-controls="adminCollapse<?php echo e($ropaForm->id); ?>_<?php echo e($index); ?>">
                                            <i class="fas fa-file me-2"></i>
                                            <?php echo e($sub->sub_process_name ?: 'Main Process'); ?>

                                            <span class="badge bg-<?php echo e($sub->status == 'completed' ? 'success' : 'warning'); ?> ms-3">
                                                <?php echo e(ucfirst($sub->status)); ?>

                                            </span>
                                        </button>
                                    </h2>
                                    <div id="adminCollapse<?php echo e($ropaForm->id); ?>_<?php echo e($index); ?>" class="accordion-collapse collapse <?php echo e($index == 0 ? 'show' : ''); ?>" data-bs-parent="#adminAccordion<?php echo e($ropaForm->id); ?>">
                                        <div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Purpose:</strong> <?php echo e($sub->purpose ?? 'N/A'); ?></p>
                                                    <p><strong>Legal Basis:</strong> <?php echo e(implode(', ', $sub->legal_basis ?? []) ?: 'N/A'); ?></p>
                                                    <p><strong>Data Subjects:</strong> <?php echo e(implode(', ', $sub->data_subjects ?? []) ?: 'N/A'); ?></p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Personnel:</strong> <?php echo e($sub->firstname ?? ''); ?> <?php echo e($sub->surname ?? ''); ?> (<?php echo e($sub->personnel_id ?? 'N/A'); ?>)</p>
                                                    <p><strong>Role:</strong> <?php echo e($sub->role_responsible ?? 'N/A'); ?></p>
                                                    <p><strong>Completed:</strong> <?php echo e($sub->completed_at ? $sub->completed_at->format('M d, Y H:i') : 'N/A'); ?></p>
                                                </div>
                                            </div>
                                            <?php if($sub->status === 'completed'): ?>
                                                <a href="<?php echo e(route('ropa.view-submission', $sub)); ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i> View Full Details
                                                </a>
                                            <?php else: ?>
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-clock me-1"></i> Still in progress &mdash; currently on step <?php echo e($sub->current_step); ?>

                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/admin/view-form.blade.php ENDPATH**/ ?>