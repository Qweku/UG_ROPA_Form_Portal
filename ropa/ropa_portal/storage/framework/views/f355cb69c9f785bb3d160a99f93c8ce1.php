<?php

function safeValue($value) {
if (is_array($value)) {
return implode(', ', $value);
}

return $value;
}

?>

<div class="form-section">
    <div class="alert alert-warning mb-4">
        <i class="fas fa-share-alt me-2"></i>
        <strong>Data Sharing Within University</strong> - Please specify if and how personal data is shared internally.
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Do You Share Personal Data Internally?</label>
        <div class="d-flex gap-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="share_internally" value="1" id="share-yes"
                    <?php echo e(old('share_internally', $submission->share_internally) == 1 ? 'checked' : ''); ?>>
                <label class="form-check-label" for="share-yes">
                    <i class="fas fa-check-circle text-success me-1"></i> Yes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="share_internally" value="0" id="share-no"
                    <?php echo e(old('share_internally', $submission->share_internally) == 0 ? 'checked' : ''); ?>>
                <label class="form-check-label" for="share-no">
                    <i class="fas fa-times-circle text-danger me-1"></i> No
                </label>
            </div>
        </div>
    </div>

    <div id="internal-recipients-section" class="conditional-section mt-4"
        <?php if(old('share_internally', $submission->share_internally) == 1): ?>
        style="display:block;"
        <?php else: ?>
        style="display:none;"
        <?php endif; ?>>

        <div class="card bg-light">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-database me-1"></i> Categories of Personal Data Shared Internally
                    </label>
                    <textarea name="internal_sharing_categories" class="form-control" rows="3"
                        placeholder="e.g., Personal data, health data, financial data, student records"><?php echo e(safeValue(old('internal_sharing_categories', $submission->internal_sharing_categories))); ?></textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label fw-bold">
                            <i class="fas fa-building me-1"></i> Which Units Receive Shared Data?
                        </label>
                        <?php
                        $internalRecipients = [];
                        if ($submission->internal_recipients) {
                            if (is_array($submission->internal_recipients)) {
                                $internalRecipients = $submission->internal_recipients;
                            } elseif (is_string($submission->internal_recipients)) {
                                $internalRecipients = json_decode($submission->internal_recipients, true) ?? [];
                            }
                        }
                        ?>
                        <div class="multi-select-container" id="internalRecipientsContainer">
                            <div class="chips-container mb-2">
                                <?php $__currentLoopData = $internalRecipients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recipient): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <span class="tag-chip">
                                    <span><?php echo e($recipient); ?></span>
                                    <button type="button" onclick="this.parentElement.remove(); updateInternalRecipients();">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                            <input type="text" class="form-control" id="internalRecipientsInput"
                                   placeholder="Type and press Enter (e.g., Finance Department, Research Office)"
                                   autocomplete="off">
                            <div class="suggestions-dropdown" id="internalRecipientsSuggestions"></div>
                            <input type="hidden" name="internal_recipients" id="internal_recipients_hidden" value="<?php echo e(json_encode($internalRecipients)); ?>">
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-question-circle me-1"></i> Reasons for Sharing Data 
                    </label>
                    <textarea name="sharing_reasons" class="form-control" rows="3"
                        placeholder="e.g., Investigative processes, reporting requirements, student support services..."><?php echo e(safeValue(old('sharing_reasons', $submission->sharing_reasons))); ?></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateInternalRecipients() {
    const container = document.querySelector('#internalRecipientsContainer .chips-container');
    if (container) {
        const values = Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
        document.getElementById('internal_recipients_hidden').value = JSON.stringify(values);
    }
}

document.addEventListener('DOMContentLoaded', function() {
        // Toggle internal recipients section based on radio selection
        const shareYes = document.getElementById('share-yes');
        const shareNo = document.getElementById('share-no');
        const internalSection = document.getElementById('internal-recipients-section');

        function toggleInternalSection() {
            if (shareYes && shareYes.checked) {
                internalSection.style.display = 'block';
            } else if (shareNo && shareNo.checked) {
                internalSection.style.display = 'none';
                // Clear the fields when hidden to prevent validation issues
                document.querySelector('textarea[name="internal_sharing_categories"]').value = '';
                const chipsContainer = document.querySelector('#internalRecipientsContainer .chips-container');
                if (chipsContainer) {
                    chipsContainer.innerHTML = '';
                }
                document.getElementById('internal_recipients_hidden').value = '[]';
                document.querySelector('textarea[name="sharing_reasons"]').value = '';
            }
        }

        if (shareYes && shareNo) {
            shareYes.addEventListener('change', toggleInternalSection);
            shareNo.addEventListener('change', toggleInternalSection);
            toggleInternalSection();
        }

        // Internal recipients multi-select with autocomplete
        (function() {
            const input = document.getElementById('internalRecipientsInput');
            const suggestionsBox = document.getElementById('internalRecipientsSuggestions');
            const container = document.querySelector('#internalRecipientsContainer .chips-container');
            const hiddenInput = document.getElementById('internal_recipients_hidden');

            const PREDEFINED = [
                'Directorate of Academic Affairs',
                'Human Resources Division',
                'Finance Department',
                'Internal Audit',
                'University Committees',
                'Research Office',
                'Legal Counsel'
            ];

            function getCurrentValues() {
                if (!container) return [];
                return Array.from(container.querySelectorAll('.tag-chip span'))
                    .map(span => span.textContent.trim());
            }

            function updateHidden() {
                const values = getCurrentValues();
                hiddenInput.value = JSON.stringify(values);
            }

            function renderSuggestions(filter = '') {
                if (!suggestionsBox) return;
                const current = getCurrentValues();
                const filtered = PREDEFINED.filter(item =>
                    item.toLowerCase().includes(filter.toLowerCase()) && !current.includes(item)
                );

                let html = '';

                if (filtered.length === 0 && filter) {
                    html = `<div class="suggestion-item custom" data-value="${escapeHtml(filter)}">Add "${escapeHtml(filter)}"</div>`;
                } else if (filtered.length > 0) {
                    filtered.forEach(item => {
                        html += `<div class="suggestion-item" data-value="${escapeHtml(item)}">${escapeHtml(item)}</div>`;
                    });
                }

                suggestionsBox.innerHTML = html;

                if (html) {
                    suggestionsBox.classList.add('active');
                    suggestionsBox.querySelectorAll('.suggestion-item').forEach(item => {
                        item.addEventListener('mousedown', function(e) {
                            e.preventDefault();
                            addChip(this.getAttribute('data-value'));
                            input.value = '';
                            input.focus();
                            renderSuggestions('');
                        });
                    });
                } else {
                    suggestionsBox.classList.remove('active');
                }
            }

            function addChip(value) {
                if (!container) return;
                const trimmed = value.trim();
                if (!trimmed) return;
                if (getCurrentValues().includes(trimmed)) return;

                const chip = document.createElement('span');
                chip.className = 'tag-chip';
                chip.innerHTML = `<span>${escapeHtml(trimmed)}</span><button type="button" onclick="this.parentElement.remove(); updateInternalRecipients();"><i class="fas fa-times"></i></button>`;
                container.appendChild(chip);
                updateHidden();
            }

            if (input && suggestionsBox) {
                input.addEventListener('focus', function() {
                    renderSuggestions(this.value.trim());
                });

                input.addEventListener('input', function() {
                    renderSuggestions(this.value.trim());
                });

                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const value = this.value.trim();
                        if (value) {
                            addChip(value);
                            this.value = '';
                            renderSuggestions('');
                        }
                    } else if (e.key === 'Backspace' && !this.value && getCurrentValues().length > 0) {
                        const chips = container.querySelectorAll('.tag-chip');
                        if (chips.length) {
                            chips[chips.length - 1].remove();
                            updateHidden();
                            renderSuggestions('');
                        }
                    }
                });
            }

            document.addEventListener('click', function(e) {
                if (!input || !suggestionsBox) return;
                if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
                    suggestionsBox.innerHTML = '';
                    suggestionsBox.classList.remove('active');
                }
            });
        })();

        // Debug: Log when form is submitted
        const form = document.querySelector('#ropaForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                console.log('Form submitted - Step 4');
                console.log('share_internally value:', document.querySelector('input[name="share_internally"]:checked')?.value);
                console.log('internal_sharing_categories:', document.querySelector('textarea[name="internal_sharing_categories"]')?.value);
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step4.blade.php ENDPATH**/ ?>