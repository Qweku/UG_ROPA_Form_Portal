<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-handshake fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Joint Controllers & Collaboration</h5>
                <p class="mb-0 text-muted small">Capture external or shared data control relationships with other organizations.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                        <i class="fas fa-building" style="color: #153d6f;"></i>
                    </div>
                    <h5 class="card-title mb-0" style="color: #153d6f;">Joint Controller Details</h5>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-joint-controller">
                    <i class="fas fa-plus me-1"></i> Add Another
                </button>
            </div>

            <div id="joint-controllers-container">
                <?php
                    $controllers = [];
                    if ($submission->joint_controllers) {
                        if (is_array($submission->joint_controllers)) {
                            $controllers = $submission->joint_controllers;
                        } elseif (is_string($submission->joint_controllers)) {
                            $controllers = json_decode($submission->joint_controllers, true) ?? [];
                        }
                    }
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $controllers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $controller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="joint-controller-card card mb-3 border">
                        <div class="card-body position-relative">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-controller" aria-label="Remove"></button>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-signature me-1" style="color: #b69964;"></i> Name of Joint Controller
                                    </label>
                                    <input type="text" name="joint_controllers[<?php echo e($idx); ?>][name]"
                                           class="form-control" value="<?php echo e($controller['name'] ?? ''); ?>"
                                           placeholder="e.g., Research Collaborator, External Partner">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-address-card me-1" style="color: #b69964;"></i> Contact Details
                                    </label>
                                    <textarea name="joint_controllers[<?php echo e($idx); ?>][contact]"
                                              class="form-control" rows="2"
                                              placeholder="Name, Address, Email, Phone"><?php echo e($controller['contact'] ?? ''); ?></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="joint-controller-card card mb-3 border">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-signature me-1" style="color: #b69964;"></i> Name of Joint Controller
                                    </label>
                                    <input type="text" name="joint_controllers[0][name]" class="form-control"
                                           placeholder="e.g., Research Collaborator">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">
                                        <i class="fas fa-address-card me-1" style="color: #b69964;"></i> Contact Details
                                    </label>
                                    <textarea name="joint_controllers[0][contact]" class="form-control" rows="2"
                                              placeholder="Name, Address, Email, Phone"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="alert alert-light mt-3" style="background: #f8f9fa;">
                <small class="text-muted">
                    <i class="fas fa-info-circle me-1"></i>
                    A joint controller exists when two or more organizations jointly determine the purposes and means of processing.
                </small>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.getElementById('add-joint-controller')?.addEventListener('click', function() {
    const container = document.getElementById('joint-controllers-container');
    const idx = container.children.length;
    const newCard = document.createElement('div');
    newCard.className = 'joint-controller-card card mb-3 border';
    newCard.innerHTML = `
        <div class="card-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-controller" aria-label="Remove"></button>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-signature me-1" style="color: #b69964;"></i> Name of Joint Controller
                    </label>
                    <input type="text" name="joint_controllers[${idx}][name]" class="form-control" placeholder="e.g., Research Collaborator">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-address-card me-1" style="color: #b69964;"></i> Contact Details
                    </label>
                    <textarea name="joint_controllers[${idx}][contact]" class="form-control" rows="2" placeholder="Name, Address, Email, Phone"></textarea>
                </div>
            </div>
        </div>
    `;
    container.appendChild(newCard);
    newCard.querySelector('.remove-controller').addEventListener('click', () => newCard.remove());
});

document.querySelectorAll('.remove-controller').forEach(btn => {
    btn.addEventListener('click', function() {
        this.closest('.joint-controller-card').remove();
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step2.blade.php ENDPATH**/ ?>