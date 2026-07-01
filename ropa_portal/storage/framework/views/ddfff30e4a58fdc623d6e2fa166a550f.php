<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-exchange-alt fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">External Sharing & Recipients</h5>
                <p class="mb-0 text-muted small">Capture sharing with third parties, service providers, and other external organizations.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                        <i class="fas fa-share-square" style="color: #153d6f;"></i>
                    </div>
                    <h5 class="card-title mb-0" style="color: #153d6f;">External Recipients</h5>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-recipient">
                    <i class="fas fa-plus me-1"></i> Add Recipient
                </button>
            </div>

            <div id="external-recipients-container">
                <?php
                    $recipients = [];
                    if ($submission->external_recipients) {
                        if (is_array($submission->external_recipients)) {
                            $recipients = $submission->external_recipients;
                        } elseif (is_string($submission->external_recipients)) {
                            $recipients = json_decode($submission->external_recipients, true) ?? [];
                        }
                    }
                ?>

                <?php $__empty_1 = true; $__currentLoopData = $recipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $idx => $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="recipient-card card mb-3 border">
                    <div class="card-body position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-recipient"></button>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-building me-1" style="color: #b69964;"></i> Recipient Name
                                </label>
                                <input type="text" name="external_recipients[<?php echo e($idx); ?>][name]" class="form-control"
                                       value="<?php echo e($recipient['name'] ?? ''); ?>" placeholder="e.g., IT system supplier, Cloud provider">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tag me-1" style="color: #b69964;"></i> Type of Recipient
                                </label>
                                <select name="external_recipients[<?php echo e($idx); ?>][type]" class="form-select">
                                    <option value="Public authority" <?php echo e(($recipient['type'] ?? '') == 'Public authority' ? 'selected' : ''); ?>>🏛️ Public authority</option>
                                    <option value="Service provider" <?php echo e(($recipient['type'] ?? '') == 'Service provider' ? 'selected' : ''); ?>>💼 Service provider</option>
                                    <option value="Commercial partner" <?php echo e(($recipient['type'] ?? '') == 'Commercial partner' ? 'selected' : ''); ?>>🤝 Commercial partner</option>
                                    <option value="Research collaborator" <?php echo e(($recipient['type'] ?? '') == 'Research collaborator' ? 'selected' : ''); ?>>🔬 Research collaborator</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-handshake me-1" style="color: #b69964;"></i> Relationship Type
                                </label>
                                <select name="external_recipients[<?php echo e($idx); ?>][relationship]" class="form-select">
                                    <option value="Data Controller" <?php echo e(($recipient['relationship'] ?? '') == 'Data Controller' ? 'selected' : ''); ?>>Data Controller</option>
                                    <option value="Data Processor" <?php echo e(($recipient['relationship'] ?? '') == 'Data Processor' ? 'selected' : ''); ?>>Data Processor</option>
                                    <option value="Joint Controller" <?php echo e(($recipient['relationship'] ?? '') == 'Joint Controller' ? 'selected' : ''); ?>>Joint Controller</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-file-signature me-1" style="color: #b69964;"></i> Contract in Place?
                                </label>
                                <select name="external_recipients[<?php echo e($idx); ?>][contract]" class="form-select contract-select">
                                    <?php if(($recipient['relationship'] ?? '') == 'Processor'): ?>
                                        <option value="yes" <?php echo e(($recipient['contract'] ?? '') == 'yes' ? 'selected' : ''); ?>>✅ Yes</option>
                                        <option value="no" <?php echo e(($recipient['contract'] ?? '') == 'no' ? 'selected' : ''); ?>>❌ No</option>
                                    <?php else: ?>
                                        <option value="na" <?php echo e(($recipient['contract'] ?? '') == 'na' ? 'selected' : ''); ?>>N/A</option>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="recipient-card card mb-3 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Recipient Name</label>
                                <input type="text" name="external_recipients[0][name]" class="form-control" placeholder="e.g., IT system supplier">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Type of Recipient</label>
                                <select name="external_recipients[0][type]" class="form-select">
                                    <option>Public authority</option>
                                    <option>Service provider</option>
                                    <option>Commercial partner</option>
                                    <option>Research collaborator</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Relationship Type</label>
                                <select name="external_recipients[0][relationship]" class="form-select">
                                    <option>Data Controller</option>
                                    <option>Data Processor</option>
                                    <option>Joint Controller</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">Contract in Place?</label>
                                <select name="external_recipients[0][contract]" class="form-select contract-select">
                                    <option value="na" selected>N/A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <div class="alert alert-light mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <small class="text-muted">
                    For each external recipient, specify the legal basis for sharing and any contractual safeguards in place (e.g., Data Processing Agreement).
                </small>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function updateContractOptions(contractSelect) {
    const card = contractSelect.closest('.recipient-card');
    if (!card) return;
    const relationshipSelect = card.querySelector('select[name$="[relationship]"]');
    if (!relationshipSelect) return;

    const currentValue = contractSelect.value;
    const isProcessor = relationshipSelect.value === 'Processor';

    contractSelect.innerHTML = '';

    if (isProcessor) {
        const optYes = document.createElement('option');
        optYes.value = 'yes';
        optYes.textContent = '✅ Yes';
        if (currentValue === 'yes') optYes.selected = true;
        contractSelect.appendChild(optYes);

        const optNo = document.createElement('option');
        optNo.value = 'no';
        optNo.textContent = '❌ No';
        if (currentValue === 'no') optNo.selected = true;
        contractSelect.appendChild(optNo);
    } else {
        const optNa = document.createElement('option');
        optNa.value = 'na';
        optNa.textContent = 'N/A';
        if (currentValue === 'na') optNa.selected = true;
        contractSelect.appendChild(optNa);
    }
}

function initContractSelect(card) {
    const contractSelect = card.querySelector('.contract-select');
    const relationshipSelect = card.querySelector('select[name$="[relationship]"]');
    if (!contractSelect || !relationshipSelect) return;

    updateContractOptions(contractSelect);
    relationshipSelect.addEventListener('change', () => updateContractOptions(contractSelect));
}

document.querySelectorAll('.recipient-card').forEach(card => initContractSelect(card));

document.getElementById('add-recipient')?.addEventListener('click', function() {
    const container = document.getElementById('external-recipients-container');
    const idx = container.children.length;
    const card = document.createElement('div');
    card.className = 'recipient-card card mb-3 border';
    card.innerHTML = `
        <div class="card-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-recipient"></button>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Recipient Name</label>
                    <input type="text" name="external_recipients[${idx}][name]" class="form-control" placeholder="e.g., IT system supplier">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Type of Recipient</label>
                    <select name="external_recipients[${idx}][type]" class="form-select">
                        <option>Public authority</option>
                        <option>Service provider</option>
                        <option>Commercial partner</option>
                        <option>Research collaborator</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Relationship Type</label>
                    <select name="external_recipients[${idx}][relationship]" class="form-select">
                        <option>Controller</option>
                        <option>Processor</option>
                        <option>Joint Controller</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">Contract in Place?</label>
                    <select name="external_recipients[${idx}][contract]" class="form-select contract-select">
                        <option value="yes">✅ Yes</option>
                        <option value="no">❌ No</option>
                        <option value="na" selected>N/A</option>
                    </select>
                </div>
            </div>
        </div>
    `;
    container.appendChild(card);
    initContractSelect(card);
    card.querySelector('.remove-recipient').onclick = () => card.remove();
});

document.querySelectorAll('.remove-recipient').forEach(btn => {
    btn.onclick = function() {
        this.closest('.recipient-card').remove();
    };
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step8.blade.php ENDPATH**/ ?>