<?php $__env->startSection('title', 'RoPA Form - Step ' . $step); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Progress Stepper -->
    <div class="step-wrapper" data-aos="fade-down">
        <div class="progress-stepper">
            <?php
            $steps = [
            1 => 'Basic Info',
            2 => 'Joint Controllers',
            3 => 'Data Categories',
            4 => 'Internal Sharing',
            5 => 'Data Source',
            6 => 'Legal Basis',
            7 => 'Security',
            8 => 'External Sharing',
            9 => 'Intl. Transfers',
            10 => 'Auto Decision',
            11 => 'Consent',
            12 => 'DPIA',
            13 => 'Breaches',
            14 => 'Compliance'
            ];
            $stepIcons = [
            1 => 'info-circle',
            2 => 'users',
            3 => 'database',
            4 => 'share-alt',
            5 => 'source',
            6 => 'gavel',
            7 => 'shield-alt',
            8 => 'exchange-alt',
            9 => 'globe',
            10 => 'robot',
            11 => 'file-signature',
            12 => 'chart-line',
            13 => 'exclamation-triangle',
            14 => 'check-circle'
            ];
            $currentIcon = $stepIcons[$step] ?? 'check-circle';
            $currentStep = $step;
            $submissionStatus = $submission->status ?? 'draft';
            $hasSubProcesses = $parentForm->has_sub_processes ?? 0;
            ?>

            <?php $__currentLoopData = $steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $num => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="step-item <?php echo e($currentStep > $num ? 'completed' : ''); ?> <?php echo e($currentStep == $num ? 'active' : ''); ?> <?php echo e($currentStep < $num ? 'locked' : ''); ?>"
                data-step="<?php echo e($num); ?>"
                <?php if($currentStep>= $num): ?> onclick="navigateToStep(<?php echo e($num); ?>)" <?php endif; ?>
                style="cursor: <?php echo e($currentStep >= $num ? 'pointer' : 'not-allowed'); ?>;">
                <div class="step-number">
                    <?php if($currentStep > $num): ?>
                    <i class="fas fa-check"></i>
                    <?php else: ?>
                    <?php echo e($num); ?>

                    <?php endif; ?>
                </div>
                <div class="step-label"><?php echo e($label); ?></div>
                <?php if($currentStep < $num): ?>
                    <div class="step-lock">
                    <i class="fas fa-lock"></i>
            </div>
            <?php endif; ?>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>

<!-- Form -->
<div class="row justify-content-center mt-5" data-aos="fade-up">
    <div class="col-lg-10">
        <form method="POST" action="<?php echo e(route('ropa.update')); ?>" id="ropaForm" class="needs-validation" novalidate>
            <?php echo csrf_field(); ?>
            <input type="hidden" name="current_step" value="<?php echo e($step); ?>">
            <input type="hidden" name="parent_form_id" value="<?php echo e($parentForm->id); ?>">
            <input type="hidden" name="submission_id" value="<?php echo e($submission->id); ?>">
            <!-- This hidden field will be set by the modal before submission -->
            <input type="hidden" name="next_action" id="nextActionInput" value="">

            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-<?php echo e($currentIcon); ?> fa-fw me-2"></i>
                            <strong>Step <?php echo e($step); ?> of 14</strong>
                            <span class="ms-3 small"><?php echo e($steps[$step]); ?></span>
                        </div>
                        <div>
                            <span class="badge bg-light text-dark px-3 py-2">
                                <i class="fas fa-save me-1"></i> Auto-save enabled
                            </span>
                        </div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <?php if($errors->any()): ?>
                    <div class="alert alert-danger mb-3 py-2">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Cannot proceed:</strong> <?php echo e($errors->first()); ?>

                        <?php if($errors->count() > 1): ?>
                        <span class="small">(<?php echo e($errors->count() - 1); ?> more)</span>
                        <?php endif; ?>
                    </div>
                    <?php endif; ?>
                    <?php echo $__env->make("ropa.steps.step{$step}", array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
                </div>

                <div class="card-footer bg-white p-4">
                    <div class="d-flex justify-content-between">
                        <div>
                            <?php if($step > 1): ?>
                            <button type="submit" name="action" value="previous" class="btn btn-outline-primary px-4">
                                <i class="fas fa-arrow-left me-2"></i> Previous
                            </button>
                            <?php endif; ?>
                            <button type="submit" name="action" value="save" class="btn btn-outline-accent ms-2 px-4">
                                <i class="fas fa-save me-2"></i> Save Draft
                            </button>
                        </div>
                        <div>
                            <?php if($step < 14): ?>
                                <button type="submit" name="action" value="next" class="btn btn-primary px-5">
                                Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                                <?php else: ?>
                                <button type="submit" name="action" value="submit" class="btn btn-accent px-5" id="finalSubmitBtn">
                                    <i class="fas fa-check-circle me-2"></i> Submit This Sub‑process
                                </button>
                                <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
    // Single DOMContentLoaded event with all functionality
    document.addEventListener('DOMContentLoaded', function() {

        // ============================================
        // 1. UPDATE ALL MULTI-SELECT HIDDEN FIELDS
        // ============================================
        function updateAllMultiSelects() {
            document.querySelectorAll('.multi-select-container').forEach(container => {
                const chipsContainer = container.querySelector('.chips-container');
                const hiddenField = container.querySelector('input[type="hidden"][name]');

                if (chipsContainer && hiddenField) {
                    const values = [];
                    const chips = chipsContainer.querySelectorAll('.tag-chip');
                    chips.forEach(chip => {
                        const textSpan = chip.querySelector('span');
                        if (textSpan && textSpan.textContent.trim()) {
                            values.push(textSpan.textContent.trim());
                        }
                    });
                    hiddenField.value = JSON.stringify(values);
                }
            });
        }

        // ============================================
        // STEP-SPECIFIC REQUIRED FIELDS CONFIG
        // ============================================
        const requiredFieldsByStep = {
            1: [{
                    name: 'surname',
                    type: 'text'
                },
                {
                    name: 'firstname',
                    type: 'text'
                },
                {
                    name: 'business_function',
                    type: 'text'
                }
            ],
            2: [{
                name: 'joint_controllers',
                type: 'array'
            }],
            3: [{
                name: 'categories_records[]',
                type: 'checkbox'
            }],
            4: null,
            5: [{
                name: 'data_source',
                type: 'radio'
            }],
            6: null,
            7: null,
            8: [{
                name: 'external_recipients',
                type: 'array'
            }],
            9: null,
            10: null,
            11: null,
            12: null,
            13: null,
            14: [{
                    name: 'dpa_conditions',
                    type: 'multiselect'
                },
                {
                    name: 'gdpr_articles',
                    type: 'multiselect'
                },
                {
                    name: 'retention_policy_link',
                    type: 'text'
                },
                {
                    name: 'retained_per_policy',
                    type: 'select'
                }
            ]
        };

        // ============================================
        // 2. VALIDATION FUNCTION
        // ============================================
        function validateStep(step) {
            const requiredFields = requiredFieldsByStep[step];
            if (!requiredFields) return {
                valid: true
            };

            const missingFields = [];

            requiredFields.forEach(fieldConfig => {
                const {
                    name,
                    type
                } = fieldConfig;

                if (type === 'checkbox') {
                    const checkboxes = document.querySelectorAll(`input[name="${name}"]`);
                    if (checkboxes.length > 0) {
                        const anyChecked = Array.from(checkboxes).some(cb => cb.checked);
                        if (!anyChecked) {
                            missingFields.push(name.replace('[]', '').replace(/_/g, ' '));
                        }
                    }
                } else if (type === 'radio') {
                    const radioName = name.replace('[]', '');
                    const radios = document.querySelectorAll(`input[name="${radioName}"]:checked`);
                    if (radios.length === 0) {
                        const label = document.querySelector(`label[for^="${radioName}"]`)?.textContent?.trim() ||
                            document.querySelector(`[name="${radioName}"]`)?.closest('.form-check')?.querySelector('.form-check-label')?.textContent?.trim() ||
                            name;
                        missingFields.push(label);
                    }
                } else if (type === 'select') {
                    const el = document.querySelector(`select[name="${name}"]`);
                    if (el) {
                        const value = el.value?.trim();
                        if (!value) {
                            const label = el.closest('.mb-3')?.querySelector('.form-label')?.textContent?.trim() ||
                                el.closest('.col-*')?.querySelector('.form-label')?.textContent?.trim() ||
                                name;
                            missingFields.push(label);
                        }
                    }
                } else if (type === 'multiselect') {
                    const hiddenInput = document.querySelector(`input[type="hidden"][name="${name}"]`);
                    if (hiddenInput) {
                        const value = hiddenInput.value?.trim();
                        let isEmpty = false;
                        if (!value || value === '[]' || value === 'null' || value === '') {
                            isEmpty = true;
                        } else {
                            try {
                                const parsed = JSON.parse(value);
                                isEmpty = Array.isArray(parsed) && parsed.length === 0;
                            } catch (e) {
                                isEmpty = true;
                            }
                        }
                        if (isEmpty) {
                            const label = hiddenInput.closest('.card-body')?.querySelector('.card-title')?.textContent?.trim() ||
                                hiddenInput.closest('.mb-3')?.querySelector('.form-label')?.textContent?.trim() ||
                                name;
                            missingFields.push(label);
                        }
                    }
                } else if (type === 'array') {
                    const cssName = name.replace(/_/g, '-');
                    const cards = document.querySelectorAll(`[id="${cssName}-container"] .card`);
                    if (cards.length === 0) {
                        const label = name.replace(/_/g, ' ');
                        missingFields.push(label);
                    }
                } else {
                    const el = document.querySelector(`[name="${name}"]`);
                    if (el) {
                        const value = el.value?.trim();
                        if (!value) {
                            const label = el.closest('.mb-3')?.querySelector('.form-label')?.textContent?.trim() ||
                                el.closest('label')?.textContent?.trim() ||
                                el.getAttribute('placeholder') || name;
                            missingFields.push(label);
                        }
                    }
                }
            });

            return {
                valid: missingFields.length === 0,
                missingFields: missingFields
            };
        }

        // ============================================
        // 3. FORM SUBMISSION HANDLER
        // ============================================
        const form = document.querySelector('#ropaForm');
        const hasSubProcesses = <?php echo json_encode($hasSubProcesses, 15, 512) ?>;

        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"][name="action"]:focus');
                const actionValue = submitBtn ? submitBtn.value : null;

                // Validate required fields when clicking "Next Step"
                if (actionValue === 'next') {
                    const currentStep = <?php echo e($step); ?>;
                    const validation = validateStep(currentStep);

                    if (!validation.valid) {
                        e.preventDefault();
                        document.getElementById('loadingOverlay').style.display = 'none';
                        Swal.fire({
                            title: 'Missing Required Fields',
                            html: '<p class="mb-2">Please complete the following required fields:</p><ul class="text-start mb-0">' +
                                validation.missingFields.map(f => `<li class="text-danger">${f}</li>`).join('') +
                                '</ul>',
                            icon: 'warning',
                            confirmButtonColor: '#b69964',
                            confirmButtonText: 'Fill Required Fields'
                        });
                        return;
                    }
                }

                // Step 14 completion modal
                if (actionValue === 'submit' && form.dataset.finalSubmitConfirmed !== 'true') {
                    e.preventDefault();

                    // Validate required fields for step 14
                    const validation = validateStep(14);
                    if (!validation.valid) {
                        document.getElementById('loadingOverlay').style.display = 'none';
                        Swal.fire({
                            title: 'Missing Required Fields',
                            html: '<p class="mb-2">Please complete all required fields before submission:</p><ul class="text-start mb-0">' +
                                validation.missingFields.map(f => `<li class="text-danger">${f}</li>`).join('') +
                                '</ul>',
                            icon: 'warning',
                            confirmButtonColor: '#b69964',
                            confirmButtonText: 'Fill Required Fields'
                        });
                        return;
                    }

// If this parent process has sub-processes, ask the user what to do next
                    if (hasSubProcesses) {
                        const subProcessName = document.querySelector('input[name="sub_process_name"]')?.value || <?php echo json_encode($submission->sub_process_name ?? '', 15, 512) ?> || 'this sub-process';
                        Swal.fire({
                            title: 'Sub-process Completed!',
                            html: `<p>You have completed data for <strong>"${subProcessName}"</strong>.</p>
                                   <p class="text-muted small">What would you like to do next?</p>`,
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#b69964',
                            cancelButtonColor: '#28a745',
                            confirmButtonText: '➕ Add Another Sub-process',
                            cancelButtonText: '✅ No, Submit Everything',
                            showDenyButton: true,
                            denyButtonText: '👀 Review again',
                            denyButtonColor: '#6c757d'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Add another sub-process
                                document.getElementById('nextActionInput').value = 'add_more';
                                finalizeSubmit();
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                // Finalize everything
                                document.getElementById('nextActionInput').value = 'finalize';
                                finalizeSubmit();
                            }
                            // If 'Review again', do nothing (stay on form)
                        });
                    } else {
                        // No sub-processes → just finalize directly
                        Swal.fire({
                            title: 'Submit RoPA Form?',
                            text: "Please review all information before submitting. Once submitted, you cannot make further changes.",
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonColor: '#b69964',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Yes, submit it!',
                            cancelButtonText: 'Review again'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                document.getElementById('nextActionInput').value = 'finalize';
                                finalizeSubmit();
                            }
                        });
                    }
                    return;
                }

                // Re-entry after modal confirmation
                if (form.dataset.finalSubmitConfirmed === 'true') {
                    delete form.dataset.finalSubmitConfirmed;
                }

                // Update all hidden fields before submission
                updateAllMultiSelects();

                // Show loading overlay for non-save / non-previous actions
                if (actionValue && actionValue !== 'save' && actionValue !== 'previous' && actionValue !== 'submit') {
                    document.getElementById('loadingOverlay').style.display = 'flex';
                }
            });
        }

        function finalizeSubmit() {
            document.getElementById('loadingOverlay').style.display = 'flex';
            let actionInput = form.querySelector('input[name="action"][type="hidden"]');
            if (!actionInput) {
                actionInput = document.createElement('input');
                actionInput.type = 'hidden';
                actionInput.name = 'action';
                form.appendChild(actionInput);
            }
            actionInput.value = 'submit';
            form.dataset.finalSubmitConfirmed = 'true';
            form.submit();
        }

        // ============================================
        // 4. MULTI-SELECT CHIP HANDLERS
        // ============================================
        function initializeMultiSelect(container) {
            const input = container.querySelector('input[type="text"]');
            const chipsContainer = container.querySelector('.chips-container');
            const hiddenField = container.querySelector('input[type="hidden"][name]');

            if (!input || !chipsContainer || !hiddenField) return;

            input.classList.add('tag-input');

            function addChipToContainer(value, shouldUpdateHidden = true) {
                const chip = document.createElement('span');
                chip.className = 'tag-chip';
                chip.style.cssText = 'background: #e8eef5; color: #153d6f; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; margin: 2px; display: inline-flex; align-items: center; gap: 6px;';

                const icon = document.createElement('i');
                icon.className = 'fas fa-tag me-1';

                const textSpan = document.createElement('span');
                textSpan.textContent = value;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.innerHTML = '×';
                removeBtn.style.cssText = 'background: none; border: none; color: #999; cursor: pointer; font-size: 1.1rem; margin-left: 4px;';
                removeBtn.onclick = function(e) {
                    e.preventDefault();
                    chip.remove();
                    if (shouldUpdateHidden) updateHidden();
                };

                chip.appendChild(icon);
                chip.appendChild(textSpan);
                chip.appendChild(removeBtn);
                chipsContainer.appendChild(chip);

                if (shouldUpdateHidden) {
                    updateHidden();
                }
            }

            function updateHidden() {
                const values = [];
                const chips = chipsContainer.querySelectorAll('.tag-chip');
                chips.forEach(chip => {
                    const textSpan = chip.querySelector('span');
                    if (textSpan && textSpan.textContent.trim()) {
                        values.push(textSpan.textContent.trim());
                    }
                });
                hiddenField.value = JSON.stringify(values);
            }

            function initializeFromHidden() {
                try {
                    const existingValue = hiddenField.value;
                    if (existingValue && existingValue !== '[]' && existingValue !== 'null' && existingValue !== '') {
                        const items = JSON.parse(existingValue);
                        if (Array.isArray(items) && items.length > 0) {
                            chipsContainer.innerHTML = '';
                            items.forEach(item => {
                                if (item && item.trim()) {
                                    addChipToContainer(item, false);
                                }
                            });
                            updateHidden();
                        }
                    }
                } catch (e) {
                    console.error('Error parsing existing value:', e);
                }
            }

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();

                    const value = this.value.trim();
                    if (value) {
                        addChipToContainer(value, true);
                        this.value = '';
                        this.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                    return false;
                }
            });

            initializeFromHidden();
        }

        document.querySelectorAll('.multi-select-container').forEach(initializeMultiSelect);

        // ============================================
        // 5. AUTO-SAVE FUNCTIONALITY
        // ============================================
        let autoSaveTimer;
        const autoSaveFields = document.querySelectorAll('#ropaForm input:not(.tag-input), #ropaForm select, #ropaForm textarea');

        function performAutoSave() {
            updateAllMultiSelects();

            const form = document.getElementById('ropaForm');
            if (!form) return;

            const formData = new FormData(form);
            formData.append('action', 'save');

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            }).then(response => {
                if (response.ok) {
                    showToast('Draft auto-saved!', 'success');
                }
            }).catch(error => {
                console.error('Auto-save failed:', error);
            });
        }

        autoSaveFields.forEach(element => {
            element.addEventListener('change', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(performAutoSave, 2000);
            });

            if (element.tagName === 'TEXTAREA' || element.type === 'text') {
                element.addEventListener('input', function() {
                    clearTimeout(autoSaveTimer);
                    autoSaveTimer = setTimeout(performAutoSave, 3000);
                });
            }
        });

        // ============================================
        // 6. CHECKBOX PERSISTENCE HANDLER
        // ============================================
        document.querySelectorAll('input[type="checkbox"][name*="[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                setTimeout(() => {
                    if (this.form && !this.checked) {
                        let hidden = this.parentNode.querySelector('input[type="hidden"][name="' + this.name + '"]');
                        if (!hidden) {
                            hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = this.name;
                            hidden.value = '';
                            this.parentNode.appendChild(hidden);
                            this.form.addEventListener('submit', function() {
                                if (hidden.parentNode) hidden.remove();
                            }, {
                                once: true
                            });
                        }
                    }
                }, 10);
            });
        });

        // ============================================
        // 7. CONDITIONAL FIELD HANDLERS
        // ============================================
        const toggleFields = () => {
            const shareInternally = document.querySelector('input[name="share_internally"]:checked');
            const internalSection = document.getElementById('internal-recipients-section');
            if (internalSection) {
                internalSection.style.display = shareInternally?.value === '1' ? 'block' : 'none';
            }

            const autoDecision = document.querySelector('input[name="auto_decision_making"]:checked');
            const profilingSection = document.getElementById('profiling-section');
            if (profilingSection) {
                if (autoDecision?.value === '1') {
                    profilingSection.classList.remove('d-none');
                } else {
                    profilingSection.classList.add('d-none');
                }
            }

            const retainedPerPolicy = document.querySelector('input[name="retained_per_policy"]:checked');
            const reasonSection = document.getElementById('non-adherence-reason');
            if (reasonSection) {
                reasonSection.style.display = retainedPerPolicy?.value === '0' ? 'block' : 'none';
            }
        };

        document.querySelectorAll('input[type="radio"]').forEach(el => {
            el.addEventListener('change', toggleFields);
        });
        toggleFields();

        // ============================================
        // 8. REAL-TIME VALIDATION
        // ============================================
        const requiredFields = document.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            field.addEventListener('blur', function() {
                if (!this.value) {
                    this.classList.add('is-invalid');
                    let errorMsg = this.nextElementSibling;
                    if (!errorMsg?.classList.contains('invalid-feedback')) {
                        errorMsg = document.createElement('div');
                        errorMsg.className = 'invalid-feedback';
                        this.parentNode.insertBefore(errorMsg, this.nextSibling);
                    }
                    errorMsg.innerText = 'This field is required.';
                } else {
                    this.classList.remove('is-invalid');
                    const errorMsg = this.nextElementSibling;
                    if (errorMsg?.classList.contains('invalid-feedback')) {
                        errorMsg.remove();
                    }
                }
            });
        });

        // ============================================
        // 9. ANIMATION
        // ============================================
        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.classList.add('fade-in-up');
        }

        console.log('RoPA Form initialized successfully');
    });

    // ============================================
    // TOAST NOTIFICATION HELPER
    // ============================================
    function showToast(message, type = 'success') {
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'position-fixed bottom-0 end-0 p-3';
            toastContainer.style.zIndex = '9999';
            document.body.appendChild(toastContainer);
        }

        const toastId = 'toast-' + Date.now();
        const toastHtml = `
        <div id="${toastId}" class="toast align-items-center text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true" data-bs-autohide="true" data-bs-delay="3000">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;

        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement, {
            autohide: true,
            delay: 3000
        });
        toast.show();

        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }

    // ============================================
    // STEP NAVIGATION
    // ============================================
    function navigateToStep(stepNumber) {
        const currentStep = <?php echo e($step); ?>;

        if (stepNumber > currentStep) {
            Swal.fire({
                title: 'Step Locked',
                text: 'Please complete the current step first before moving forward.',
                icon: 'info',
                confirmButtonColor: '#b69964',
                confirmButtonText: 'OK'
            });
            return;
        }

        if (stepNumber === currentStep) {
            return;
        }

        if (stepNumber < currentStep) {
            Swal.fire({
                title: 'Navigate to Step ' + stepNumber + '?',
                text: 'You will lose any unsaved changes on the current step. Make sure to save your progress first.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#b69964',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, navigate',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    performNavigation(stepNumber);
                }
            });
        } else {
            const validation = validateStep(currentStep);
            if (!validation.valid) {
                document.getElementById('loadingOverlay').style.display = 'none';
                Swal.fire({
                    title: 'Missing Required Fields',
                    html: '<p class="mb-2">Please complete the following required fields before moving to the next step:</p><ul class="text-start mb-0">' +
                        validation.missingFields.map(f => `<li class="text-danger">${f}</li>`).join('') +
                        '</ul>',
                    icon: 'warning',
                    confirmButtonColor: '#b69964',
                    confirmButtonText: 'Fill Required Fields'
                });
                return;
            }
            performNavigation(stepNumber);
        }
    }

    function performNavigation(stepNumber) {
        document.getElementById('loadingOverlay').style.display = 'flex';

        const formData = new FormData();
        formData.append('_token', '<?php echo e(csrf_token()); ?>');
        formData.append('_method', 'PUT');
        formData.append('action', 'navigate');
        formData.append('target_step', stepNumber);
        formData.append('current_step', <?php echo e($step); ?>);

        const currentForm = document.getElementById('ropaForm');
        if (currentForm) {
            const currentFormData = new FormData(currentForm);
            for (let pair of currentFormData.entries()) {
                if (pair[0] !== '_token' && pair[0] !== '_method' && pair[0] !== 'action' && pair[0] !== 'target_step') {
                    formData.append(pair[0], pair[1]);
                }
            }
        }

        fetch('<?php echo e(route("ropa.update")); ?>', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '',
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => {
                        throw err;
                    });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    document.getElementById('loadingOverlay').style.display = 'none';
                    let errorList = '';
                    if (data.errors && typeof data.errors === 'object') {
                        errorList = '<ul class="text-start mb-0">' + Object.entries(data.errors)
                            .map(([field, msgs]) => `<li class="text-danger">${Array.isArray(msgs) ? msgs.join(', ') : msgs}</li>`)
                            .join('') + '</ul>';
                    }
                    Swal.fire({
                        title: 'Error',
                        html: (data.message || 'Failed to navigate.') + errorList,
                        icon: 'error',
                        confirmButtonColor: '#b69964'
                    });
                }
            })
            .catch(error => {
                document.getElementById('loadingOverlay').style.display = 'none';
                Swal.fire({
                    title: 'Error',
                    text: 'An error occurred. Please try again.',
                    icon: 'error',
                    confirmButtonColor: '#b69964'
                });
            });
    }
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/form.blade.php ENDPATH**/ ?>