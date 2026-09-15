<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-gavel fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">DPA & GDPR Compliance</h5>
                <p class="mb-0 text-muted small">Capture statutory legal references and policy adherence for final compliance verification.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-book" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Legal Frameworks</h5>
                    </div>

                    <label class="form-label fw-bold">
                        <i class="fas fa-balance-scale me-1" style="color: #b69964;"></i>
                        Data Protection Act 2018 Schedule 1 Condition
                    </label>
                    <div class="multi-select-container mb-3" id="dpa-conditions-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                                $dpaConditions = [];
                                if ($submission->dpa_conditions) {
                                    $dpaConditions = is_array($submission->dpa_conditions)
                                        ? $submission->dpa_conditions
                                        : (json_decode($submission->dpa_conditions, true) ?? []);
                                }
                            ?>
                            <?php $__currentLoopData = $dpaConditions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cond): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span><?php echo e($cond); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateDpaConditions();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <input type="text" class="form-control" id="dpaConditionInput"
                               placeholder="Type and press Enter (e.g., Legal requirement, Employment, Health, Research)">
                        <input type="hidden" name="dpa_conditions" id="dpa_conditions_hidden" value="<?php echo e(json_encode($dpaConditions)); ?>">
                    </div>

                    <label class="form-label fw-bold">
                        <i class="fas fa-shield-alt me-1" style="color: #b69964;"></i>
                        Cybersecurity Act 1038
                    </label>
                    <div class="multi-select-container mb-3" id="cyber-articles-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                                $cyberArticles = [];
                                if ($submission->cybersecurity_articles) {
                                    $cyberArticles = is_array($submission->cybersecurity_articles)
                                        ? $submission->cybersecurity_articles
                                        : (json_decode($submission->cybersecurity_articles, true) ?? []);
                                }
                            ?>
                            <?php $__currentLoopData = $cyberArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span><?php echo e($art); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateCyberArticles();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
                         <input type="text" class="form-control" id="cyberArticleInput"
                                placeholder="Type and press Enter (e.g., Section 3 - Cybersecurity Measures, Section 5 - Data Breach Notification)">
                         <input type="hidden" name="cybersecurity_articles" id="cybersecurity_articles_hidden" value="<?php echo e(json_encode($cyberArticles)); ?>">
                     </div>

                     <label class="form-label fw-bold">
                         <i class="fab fa-internet-explorer me-1" style="color: #b69964;"></i>
                         GDPR Article 6 Lawful Basis
                     </label>
                     <div class="multi-select-container mb-3" id="gdpr-articles-container">
                         <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                                $gdprArticles = [];
                                if ($submission->gdpr_articles) {
                                    $gdprArticles = is_array($submission->gdpr_articles)
                                        ? $submission->gdpr_articles
                                        : (json_decode($submission->gdpr_articles, true) ?? []);
                                }
                            ?>
                            <?php $__currentLoopData = $gdprArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span><?php echo e($art); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateGdprArticles();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
                         <input type="text" class="form-control" id="gdprArticleInput"
                                placeholder="Type and press Enter (e.g., Article 6(1)(b) - Contract, Article 6(1)(c) - Legal Obligation)">
                         <input type="hidden" name="gdpr_articles" id="gdpr_articles_hidden" value="<?php echo e(json_encode($gdprArticles)); ?>">
                     </div>

                     <label class="form-label fw-bold">
                         <i class="fas fa-shield-alt me-1" style="color: #b69964;"></i>
                         Other Legal References
                     </label>
                     <div class="multi-select-container mb-3" id="other-articles-container">
                         <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                                $otherArticles = [];
                                if ($submission->other_articles) {
                                    $otherArticles = is_array($submission->other_articles)
                                        ? $submission->other_articles
                                        : (json_decode($submission->other_articles, true) ?? []);
                                }
                            ?>
                            <?php $__currentLoopData = $otherArticles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $art): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span><?php echo e($art); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateOtherArticles();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <input type="text" class="form-control" id="otherArticleInput"
                               placeholder="Type and press Enter (e.g., Other relevant legal references)">
                        <input type="hidden" name="other_articles" id="other_articles_hidden" value="<?php echo e(json_encode($otherArticles)); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-trash-alt" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Retention Policy Compliance</h5>
                    </div>

                    <label class="form-label fw-bold">Link to Retention & Erasure Policy</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text bg-transparent">
                            <i class="fas fa-link" style="color: #b69964;"></i>
                        </span>
                        <input type="url" name="retention_policy_link" class="form-control"
                               value="<?php echo e(old('retention_policy_link', $submission->retention_policy_link)); ?>"
                               placeholder="URL to policy document">
                    </div>

                    <label class="form-label fw-bold">Is Data Retained According to Policy?</label>
                    <select name="retained_per_policy" class="form-select" id="retained_per_policy">
                        <option value="">-- Select --</option>
                        <option value="1" <?php echo e($submission->retained_per_policy === true ? 'selected' : ''); ?>>
                            ✅ Yes - Data is retained per established schedule
                        </option>
                        <option value="0" <?php echo e($submission->retained_per_policy === false ? 'selected' : ''); ?>>
                            ❌ No - Exceptions exist
                        </option>
                    </select>

                    <div id="non-adherence-reason" class="mt-3 <?php echo e((string) $submission->retained_per_policy !== '0' ? 'd-none' : ''); ?>">
                        <label class="form-label fw-bold">
                            <i class="fas fa-exclamation-triangle me-1" style="color: #dc3545;"></i>
                            Reasons for Non-Adherence
                        </label>
                        <textarea name="retention_non_adherence_reason" class="form-control" rows="4"
                                  placeholder="Explain why data is not being retained according to the official policy..."><?php echo e(old('retention_non_adherence_reason', $submission->retention_non_adherence_reason)); ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Legal Reference Panel -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                    <i class="fas fa-info-circle" style="color: #153d6f;"></i>
                </div>
                <h5 class="card-title mb-0" style="color: #153d6f;">Legal Reference Guide</h5>
                <button class="btn btn-sm btn-link ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#legalReference">
                    <i class="fas fa-chevron-down"></i> Expand
                </button>
            </div>

            <div class="collapse" id="legalReference">
                <div class="row mt-3">
                    <div class="col-md-6">
                        <h6 class="fw-bold" style="color: #b69964;">Data Protection Act 2018 (Ghana)</h6>
                        <ul class="small">
                            <li><strong>Section 24:</strong> Conditions for processing personal data</li>
                            <li><strong>Section 25:</strong> Conditions for processing sensitive personal data</li>
                            <li><strong>Section 26:</strong> Right to object to processing</li>
                            <li><strong>Section 27:</strong> Automated decision-making</li>
                            <li><strong>Section 43-44:</strong> Data protection impact assessment</li>
                            <li><strong>Section 46-48:</strong> Data breach notification</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold" style="color: #b69964;">GDPR Articles (EU Reference)</h6>
                        <ul class="small">
                            <li><strong>Article 5:</strong> Principles relating to processing</li>
                            <li><strong>Article 6:</strong> Lawfulness of processing</li>
                            <li><strong>Article 9:</strong> Processing of special categories</li>
                            <li><strong>Article 13-14:</strong> Information to be provided</li>
                            <li><strong>Article 15-22:</strong> Data subject rights</li>
                            <li><strong>Article 32:</strong> Security of processing</li>
                            <li><strong>Article 35:</strong> Data protection impact assessment</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Completion Message -->
    <div class="alert alert-success mt-4">
        <div class="d-flex align-items-center">
            <div class="me-3">
                <i class="fas fa-check-circle fa-2x"></i>
            </div>
            <div>
                <h6 class="mb-1">Ready for Submission!</h6>
                <p class="mb-0 small">Please review all information carefully before submitting. Once submitted, the form will be locked for further edits and sent for review.</p>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function updateHiddenInput(hiddenId, chipsSelector) {
    const chips = document.querySelectorAll(chipsSelector);
    const values = Array.from(chips).map(chip => chip.textContent.trim());
    document.getElementById(hiddenId).value = JSON.stringify(values);
}

function updateDpaConditions() {
    updateHiddenInput('dpa_conditions_hidden', '#dpa-conditions-container .chips-container .tag-chip span');
}

function updateCyberArticles() {
    updateHiddenInput('cybersecurity_articles_hidden', '#cyber-articles-container .chips-container .tag-chip span');
}

function updateGdprArticles() {
    updateHiddenInput('gdpr_articles_hidden', '#gdpr-articles-container .chips-container .tag-chip span');
}

function updateOtherArticles() {
    updateHiddenInput('other_articles_hidden', '#other-articles-container .chips-container .tag-chip span');
}

function setupMultiSelect(inputId, containerId, updateFn) {
    const input = document.getElementById(inputId);
    if (!input) return;

    input.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            const value = this.value.trim();
            if (value) {
                const container = document.querySelector(`#${containerId} .chips-container`);
                const chip = document.createElement('span');
                chip.className = 'tag-chip';
                chip.innerHTML = `<i class="fas fa-check-circle me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); ${updateFn}();"><i class="fas fa-times"></i></button>`;
                container.appendChild(chip);
                this.value = '';
                updateFn();
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    setupMultiSelect('dpaConditionInput', 'dpa-conditions-container', updateDpaConditions);
    setupMultiSelect('cyberArticleInput', 'cyber-articles-container', updateCyberArticles);
    setupMultiSelect('gdprArticleInput', 'gdpr-articles-container', updateGdprArticles);
    setupMultiSelect('otherArticleInput', 'other-articles-container', updateOtherArticles);

    document.querySelectorAll('.remove-controller').forEach(btn => {
        btn.addEventListener('click', function() {
            this.closest('.joint-controller-card').remove();
        });
    });

    const retainedPerPolicy = document.getElementById('retained_per_policy');
    const reasonSection = document.getElementById('non-adherence-reason');

    function toggleReasonSection() {
        if (retainedPerPolicy && reasonSection) {
            reasonSection.style.display = retainedPerPolicy.value === '0' ? 'block' : 'none';
        }
    }

    if (retainedPerPolicy) {
        retainedPerPolicy.addEventListener('change', toggleReasonSection);
        toggleReasonSection();
    }

    const legalCollapse = document.getElementById('legalReference');
    const legalButton = document.querySelector('[data-bs-target="#legalReference"]');

    if (legalCollapse && legalButton) {
        legalCollapse.addEventListener('show.bs.collapse', function () {
            legalButton.innerHTML = '<i class="fas fa-chevron-up"></i> Hide';
        });

        legalCollapse.addEventListener('hide.bs.collapse', function () {
            legalButton.innerHTML = '<i class="fas fa-chevron-down"></i> Expand';
        });
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step14.blade.php ENDPATH**/ ?>