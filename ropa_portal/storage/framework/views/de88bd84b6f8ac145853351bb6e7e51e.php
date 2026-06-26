<div class="form-section">
    <!-- Enhanced Header Alert -->
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-gavel fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Legal Basis Information</h5>
                <p class="mb-0 text-muted small">
                    Under the Data Protection Act, you must have a valid legal basis for processing personal data.
                    Common bases include: <span class="badge bg-primary me-1">Consent</span>
                    <span class="badge bg-primary me-1">Contract</span>
                    <span class="badge bg-primary me-1">Legal Obligation</span>
                    <span class="badge bg-primary me-1">Vital Interests</span>
                    <span class="badge bg-primary me-1">Public Task</span>
                    <span class="badge bg-primary">Legitimate Interests</span>
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Legal Basis Cards -->
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-scale-balanced" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Legal Basis for Processing</h5>
                    </div>
                    <div class="multi-select-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                            $legalBasis = is_array($submission->legal_basis)
                            ? $submission->legal_basis
                            : (json_decode($submission->legal_basis ?? '[]', true) ?: []);
                            ?>
                            <?php $__currentLoopData = $legalBasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $basis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1" style="color: #28a745;"></i>
                                <span><?php echo e($basis); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateLegalBasis();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="legalBasisInput"
                                placeholder="Type and press Enter to add (e.g., Consent, Contract, Legal Obligation)">
                            <button class="btn btn-outline-primary" type="button" onclick="addLegalBasis()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <input type="hidden" name="legal_basis" id="legal_basis_hidden" value="<?php echo e(json_encode($legalBasis)); ?>">
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle"></i> Add all applicable legal bases
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-shield-heart" style="color: #b69964;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Sensitive Personal Data Basis</h5>
                    </div>
                    <div class="multi-select-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                            $sensitiveBasis = is_array($submission->sensitive_legal_basis)
                            ? $submission->sensitive_legal_basis
                            : (json_decode($submission->sensitive_legal_basis ?? '[]', true) ?: []);
                            ?>
                            <?php $__currentLoopData = $sensitiveBasis; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $basis): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip" style="background: #f5efe6; border-color: #b69964;">
                                <i class="fas fa-exclamation-triangle me-1" style="color: #b69964;"></i>
                                <span><?php echo e($basis); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateSensitiveBasis();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <div class="input-group">
                            <input type="text" class="form-control" id="sensitiveBasisInput"
                                placeholder="Type and press Enter (e.g., Medical, Employment, Public functions)">
                            <button class="btn btn-outline-warning" type="button" onclick="addSensitiveBasis()">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                        <input type="hidden" name="sensitive_legal_basis" id="sensitive_basis_hidden" value="<?php echo e(json_encode($sensitiveBasis)); ?>">
                        <small class="text-muted mt-2 d-block">
                            <i class="fas fa-info-circle"></i> Special categories require additional safeguards
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legitimate Interest Section -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                    <i class="fas fa-chart-line" style="color: #153d6f;"></i>
                </div>
                <h5 class="card-title mb-0" style="color: #153d6f;">Legitimate Interest Assessment</h5>
            </div>

            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-bold">
                        LIA Documented?
                        <i class="fas fa-question-circle text-muted ms-1" data-bs-toggle="tooltip"
                            title="Legitimate Interest Assessment"></i>
                    </label>
                    <select name="lia_documented" class="form-select" id="lia_documented">
                        <option value="">-- Select --</option>
                        <option value="1" <?php echo e($submission->lia_documented === true ? 'selected' : ''); ?>>
                            <i class="fas fa-check"></i> Yes
                        </option>
                        <option value="0" <?php echo e($submission->lia_documented === false ? 'selected' : ''); ?>>
                            <i class="fas fa-times"></i> No
                        </option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Document Location</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="fas fa-link" style="color: #b69964;"></i>
                        </span>
                        <input type="text" name="lia_location" class="form-control"
                            value="<?php echo e(old('lia_location', $submission->lia_location)); ?>"
                            placeholder="File path or URL">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">LIA Link</label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="fas fa-globe" style="color: #b69964;"></i>
                        </span>
                        <input type="url" name="lia_link" class="form-control"
                            value="<?php echo e(old('lia_link', $submission->lia_link)); ?>"
                            placeholder="https://...">
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label fw-bold">Legitimate Interests Explanation</label>
                    <textarea name="legitimate_interests" class="form-control" rows="4"
                        placeholder="Explain the legitimate interests pursued by the controller and why they override the interests of data subjects..."><?php echo e(old('legitimate_interests', $submission->legitimate_interests)); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Retention Information -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                    <i class="fas fa-clock" style="color: #153d6f;"></i>
                </div>
                <h5 class="card-title mb-0" style="color: #153d6f;">Retention & Disposal</h5>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-bold">
                        Retention Period
                        <i class="fas fa-question-circle text-muted ms-1" data-bs-toggle="tooltip"
                            title="How long is this data kept?"></i>
                    </label>
                    <div class="input-group">
                        <span class="input-group-text bg-transparent">
                            <i class="fas fa-calendar" style="color: #b69964;"></i>
                        </span>
                        <input type="text" name="retention_period" class="form-control"
                            value="<?php echo e(old('retention_period', $submission->retention_period)); ?>"
                            placeholder="e.g., 8 years after account closure, Indefinitely, 30 days">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-bold">Legally Required to Keep Data?</label>
                    <select name="legally_required_retention" class="form-select">
                        <option value="">-- Select --</option>
                        <option value="1" <?php echo e($submission->legally_required_retention === true ? 'selected' : ''); ?>>
                            <i class="fas fa-gavel"></i> Yes - Legal/Regulatory requirement
                        </option>
                        <option value="0" <?php echo e($submission->legally_required_retention === false ? 'selected' : ''); ?>>
                            <i class="fas fa-user-check"></i> No - Determined by organization
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Individual Rights Section -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                    <i class="fas fa-user-shield" style="color: #153d6f;"></i>
                </div>
                <h5 class="card-title mb-0" style="color: #153d6f;">Individual Rights</h5>
                <span class="ms-2 badge bg-info">GDPR Articles 15-22</span>
            </div>

            <p class="text-muted small mb-3">Select all rights that apply to data subjects under this processing activity:</p>

            <div class="row">
                <?php
                $rightsIcons = [
                'Access' => 'fa-eye',
                'Rectification' => 'fa-pen',
                'Erasure' => 'fa-trash-alt',
                'Restriction' => 'fa-pause',
                'Portability' => 'fa-download',
                'Object' => 'fa-thumbs-down'
                ];
                $rightsColors = [
                'Access' => 'primary',
                'Rectification' => 'info',
                'Erasure' => 'danger',
                'Restriction' => 'warning',
                'Portability' => 'success',
                'Object' => 'secondary'
                ];
                $individualRights = is_array($submission->individual_rights)
                ? $submission->individual_rights
                : (json_decode($submission->individual_rights ?? '[]', true) ?: []);
                ?>
                <?php $__currentLoopData = ['Access','Rectification','Erasure','Restriction','Portability','Object']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $right): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-md-4 col-lg-3 mb-2">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="individual_rights[]"
                            value="<?php echo e($right); ?>" id="right-<?php echo e($right); ?>"
                            <?php echo e(in_array($right, $individualRights) ? 'checked' : ''); ?>>
                        <label class="form-check-label d-flex align-items-center" for="right-<?php echo e($right); ?>">
                            <div class="rounded-circle p-1 me-2" style="background: #e8eef5; width: 28px; height: 28px; display: inline-flex; align-items: center; justify-content: center;">
                                <i class="fas <?php echo e($rightsIcons[$right]); ?> fa-sm" style="color: #153d6f;"></i>
                            </div>
                            <span><?php echo e($right); ?></span>
                        </label>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Legal Basis functions
    function updateLegalBasis() {
        const chips = document.querySelectorAll('#step6 .multi-select-container:first-child .tag-chip span');
        const values = Array.from(chips).map(chip => chip.textContent.trim());
        document.getElementById('legal_basis_hidden').value = JSON.stringify(values);
        console.log('Legal basis updated:', values);
    }

    function addLegalBasis() {
        const input = document.getElementById('legalBasisInput');
        const value = input.value.trim();
        if (!value) return;

        const container = document.querySelector('#step6 .multi-select-container:first-child .chips-container');
        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.innerHTML = `
        <i class="fas fa-check-circle me-1" style="color: #28a745;"></i>
        <span>${escapeHtml(value)}</span>
        <button type="button" onclick="this.parentElement.remove(); updateLegalBasis();">
            <i class="fas fa-times"></i>
        </button>
    `;
        container.appendChild(chip);
        input.value = '';
        updateLegalBasis();
    }

    // Sensitive Basis functions
    function updateSensitiveBasis() {
        const chips = document.querySelectorAll('#step6 .multi-select-container:last-child .tag-chip span');
        const values = Array.from(chips).map(chip => chip.textContent.trim());
        document.getElementById('sensitive_basis_hidden').value = JSON.stringify(values);
        console.log('Sensitive basis updated:', values);
    }

    function addSensitiveBasis() {
        const input = document.getElementById('sensitiveBasisInput');
        const value = input.value.trim();
        if (!value) return;

        const container = document.querySelector('#step6 .multi-select-container:last-child .chips-container');
        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.style.cssText = 'background: #f5efe6; border-color: #b69964; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; margin: 2px; display: inline-flex; align-items: center; gap: 6px;';
        chip.innerHTML = `
        <i class="fas fa-exclamation-triangle me-1" style="color: #b69964;"></i>
        <span>${escapeHtml(value)}</span>
        <button type="button" onclick="this.parentElement.remove(); updateSensitiveBasis();">
            <i class="fas fa-times"></i>
        </button>
    `;
        container.appendChild(chip);
        input.value = '';
        updateSensitiveBasis();
    }

    function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    // Initialize Enter key handlers
    document.addEventListener('DOMContentLoaded', function() {
        // Legal basis input
        const legalInput = document.getElementById('legalBasisInput');
        if (legalInput) {
            legalInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addLegalBasis();
                }
            });
        }

        // Sensitive basis input
        const sensitiveInput = document.getElementById('sensitiveBasisInput');
        if (sensitiveInput) {
            sensitiveInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addSensitiveBasis();
                }
            });
        }

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Log initial values for debugging
        console.log('Initial legal basis:', document.getElementById('legal_basis_hidden')?.value);
        console.log('Initial sensitive basis:', document.getElementById('sensitive_basis_hidden')?.value);
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step6.blade.php ENDPATH**/ ?>