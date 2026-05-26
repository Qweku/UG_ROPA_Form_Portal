@extends('layouts.app')

@section('title', 'RoPA Form - Step ' . $ropaForm->current_step)

@section('content')
<div class="container">
    <!-- Progress Stepper -->
    <div class="step-wrapper mb-5" data-aos="fade-down">
        <div class="progress-stepper">
            @php
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
            @endphp

            @foreach($steps as $num => $label)
            <div class="step-item
                    {{ $ropaForm->current_step > $num ? 'completed' : '' }}
                    {{ $ropaForm->current_step == $num ? 'active' : '' }}"
                data-step="{{ $num }}"
                @if($ropaForm->current_step > $num)
                onclick="goToStep({{ $num }})"
                style="cursor:pointer;"
                @endif>
                {{ $num }}
                <div class="step-label">{{ $label }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Form -->
    <div class="row justify-content-center" data-aos="fade-up">
        <div class="col-lg-10">
            <form method="POST" action="{{ route('ropa.update', $ropaForm) }}" id="ropaForm" class="needs-validation" novalidate>
                @csrf
                @method('PUT')
                <input type="hidden" name="current_step" value="{{ $ropaForm->current_step }}">

                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <i class="fas fa-{{
                                    $ropaForm->current_step == 1 ? 'info-circle' :
                                    ($ropaForm->current_step == 2 ? 'users' :
                                    ($ropaForm->current_step == 3 ? 'database' :
                                    ($ropaForm->current_step == 4 ? 'share-alt' :
                                    ($ropaForm->current_step == 5 ? 'source' :
                                    ($ropaForm->current_step == 6 ? 'gavel' :
                                    ($ropaForm->current_step == 7 ? 'shield-alt' :
                                    ($ropaForm->current_step == 8 ? 'exchange-alt' :
                                    ($ropaForm->current_step == 9 ? 'globe' :
                                    ($ropaForm->current_step == 10 ? 'robot' :
                                    ($ropaForm->current_step == 11 ? 'file-signature' :
                                    ($ropaForm->current_step == 12 ? 'chart-line' :
                                    ($ropaForm->current_step == 13 ? 'exclamation-triangle' : 'check-circle'))))))))))))
                                }} fa-fw me-2"></i>
                                <strong>Step {{ $ropaForm->current_step }} of 14</strong>
                                <span class="ms-3 small">@yield('step-title', $steps[$ropaForm->current_step])</span>
                            </div>
                            <div>
                                <span class="badge bg-light text-dark px-3 py-2">
                                    <i class="fas fa-save me-1"></i> Auto-save enabled
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        @if ($errors->any())
                            <div class="alert alert-danger mb-3 py-2">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Cannot proceed:</strong> {{ $errors->first() }}
                                @if ($errors->count() > 1)
                                    <span class="small">({{ $errors->count() - 1 }} more)</span>
                                @endif
                            </div>
                        @endif
                        @include("ropa.steps.step{$ropaForm->current_step}")
                    </div>

                    <div class="card-footer bg-white p-4">
                        <div class="d-flex justify-content-between">
                            <div>
                                @if($ropaForm->current_step > 1)
                                <button type="submit" name="action" value="previous" class="btn btn-outline-primary px-4">
                                    <i class="fas fa-arrow-left me-2"></i> Previous
                                </button>
                                @endif
                                <button type="submit" name="action" value="save" class="btn btn-outline-accent ms-2 px-4">
                                    <i class="fas fa-save me-2"></i> Save Draft
                                </button>
                            </div>
                            <div>
                                @if($ropaForm->current_step < 14)
                                    <button type="submit" name="action" value="next" class="btn btn-primary px-5">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                    </button>
                                    @else
                                     <button type="submit" name="action" value="submit" class="btn btn-accent px-5">
                                         <i class="fas fa-check-circle me-2"></i> Submit Form
                                     </button>
                                    @endif
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
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
        // 2. FORM SUBMISSION HANDLER
        // ============================================
        const form = document.querySelector('#ropaForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"][name="action"]:focus');
                const actionValue = submitBtn ? submitBtn.value : null;

                // Special handling for final submission (step 14):
                // - Show confirmation modal WITHOUT showing loading overlay (prevents modal obstruction)
                // - Only show loading AFTER user confirms in the modal
                if (actionValue === 'submit' && form.dataset.finalSubmitConfirmed !== 'true') {
                    e.preventDefault();

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
                            // Show loading only after modal confirmation
                            document.getElementById('loadingOverlay').style.display = 'flex';

                            // Ensure the 'action=submit' value reaches the server even on programmatic submit
                            let actionInput = form.querySelector('input[name="action"][type="hidden"]');
                            if (!actionInput) {
                                actionInput = document.createElement('input');
                                actionInput.type = 'hidden';
                                actionInput.name = 'action';
                                form.appendChild(actionInput);
                            }
                            actionInput.value = 'submit';

                            // Mark for bypass on re-entrant submit and submit the form
                            form.dataset.finalSubmitConfirmed = 'true';
                            form.submit();
                        }
                        // If cancelled, do nothing (user stays on form)
                    });
                    return;
                }

                // Re-entry after modal confirmation (or normal submissions)
                if (form.dataset.finalSubmitConfirmed === 'true') {
                    delete form.dataset.finalSubmitConfirmed;
                }

                // Update all hidden fields before submission
                updateAllMultiSelects();

                // Show loading overlay for non-save / non-previous actions (but not final submit path here)
                if (actionValue && actionValue !== 'save' && actionValue !== 'previous' && actionValue !== 'submit') {
                    document.getElementById('loadingOverlay').style.display = 'flex';
                }
            });
        }

        // ============================================
        // 3. MULTI-SELECT CHIP HANDLERS
        // ============================================
        function initializeMultiSelect(container) {
            const input = container.querySelector('input[type="text"]');
            const chipsContainer = container.querySelector('.chips-container');
            const hiddenField = container.querySelector('input[type="hidden"][name]');

            if (!input || !chipsContainer || !hiddenField) return;

            // Add CSS class for identification
            input.classList.add('tag-input');

            // Function to add chip
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

            // Function to update hidden field
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

            // Initialize from existing hidden field value
            function initializeFromHidden() {
                try {
                    const existingValue = hiddenField.value;
                    if (existingValue && existingValue !== '[]' && existingValue !== 'null' && existingValue !== '') {
                        const items = JSON.parse(existingValue);
                        if (Array.isArray(items) && items.length > 0) {
                            // Clear existing chips
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

            // Handle Enter key - prevent form submission
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    e.stopPropagation();

                    const value = this.value.trim();
                    if (value) {
                        addChipToContainer(value, true);
                        this.value = '';
                        // Trigger change event for auto-save
                        this.dispatchEvent(new Event('change', {
                            bubbles: true
                        }));
                    }
                    return false;
                }
            });

            // Initialize
            initializeFromHidden();
        }

        // Initialize all multi-select containers
        document.querySelectorAll('.multi-select-container').forEach(initializeMultiSelect);

        // ============================================
        // 4. AUTO-SAVE FUNCTIONALITY
        // ============================================
        let autoSaveTimer;
        const autoSaveFields = document.querySelectorAll('#ropaForm input:not(.tag-input), #ropaForm select, #ropaForm textarea');

        function performAutoSave() {
            // Update all multi-select hidden fields before auto-save
            updateAllMultiSelects();

            const form = document.getElementById('ropaForm');
            if (!form) return;

            const formData = new FormData(form);
            formData.append('action', 'save');

            // Get CSRF token
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

        // Attach auto-save to form fields (excluding tag inputs to avoid excessive saves)
        autoSaveFields.forEach(element => {
            element.addEventListener('change', function() {
                clearTimeout(autoSaveTimer);
                autoSaveTimer = setTimeout(performAutoSave, 2000);
            });

            // Also save on input for text fields after typing stops
            if (element.tagName === 'TEXTAREA' || element.type === 'text') {
                element.addEventListener('input', function() {
                    clearTimeout(autoSaveTimer);
                    autoSaveTimer = setTimeout(performAutoSave, 3000);
                });
            }
        });

        // ============================================
        // 5. CHECKBOX PERSISTENCE HANDLER
        // ============================================
        document.querySelectorAll('input[type="checkbox"][name*="[]"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                // Ensure unchecked checkboxes are submitted as empty values
                setTimeout(() => {
                    if (this.form && !this.checked) {
                        let hidden = this.parentNode.querySelector('input[type="hidden"][name="' + this.name + '"]');
                        if (!hidden) {
                            hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = this.name;
                            hidden.value = '';
                            this.parentNode.appendChild(hidden);

                            // Remove after form submission
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
        // 6. CONDITIONAL FIELD HANDLERS
        // ============================================
        const toggleFields = () => {
            // Step 4: Internal Sharing conditional
            const shareInternally = document.querySelector('input[name="share_internally"]:checked');
            const internalSection = document.getElementById('internal-recipients-section');
            if (internalSection) {
                internalSection.style.display = shareInternally?.value === '1' ? 'block' : 'none';
            }

            // Step 10: Auto decision conditional
            const autoDecision = document.querySelector('input[name="auto_decision_making"]:checked');
            const profilingSection = document.getElementById('profiling-section');
            if (profilingSection) {
                if (autoDecision?.value === '1') {
                    profilingSection.classList.remove('d-none');
                } else {
                    profilingSection.classList.add('d-none');
                }
            }

            // Step 14: Retention non-adherence conditional
            const retainedPerPolicy = document.querySelector('input[name="retained_per_policy"]:checked');
            const reasonSection = document.getElementById('non-adherence-reason');
            if (reasonSection) {
                reasonSection.style.display = retainedPerPolicy?.value === '0' ? 'block' : 'none';
            }
        };

        // Attach toggle handlers to radio buttons
        document.querySelectorAll('input[type="radio"]').forEach(el => {
            el.addEventListener('change', toggleFields);
        });
        toggleFields();

        // ============================================
        // 7. REAL-TIME VALIDATION
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
        // 8. ANIMATION
        // ============================================
        const cardBody = document.querySelector('.card-body');
        if (cardBody) {
            cardBody.classList.add('fade-in-up');
        }

        console.log('RoPA Form initialized successfully');
    });

    function showToast(message, type = 'success') {
        // Create toast container if it doesn't exist
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

        // Remove toast element after hiding
        toastElement.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
</script>
@endpush
@endsection
