<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-globe fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">International Transfers</h5>
                <p class="mb-0 text-muted small">Capture cross-border data transfer compliance requirements.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-map-marker-alt" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Transfer Countries</h5>
                    </div>
                    <?php
                        $transfers = [];
                        if ($submission->international_transfers) {
                            $transfers = is_array($submission->international_transfers)
                                ? $submission->international_transfers
                                : (json_decode($submission->international_transfers, true) ?? []);
                        }
                    ?>
                    <div class="multi-select-container" id="transfersContainer">
                        <div class="chips-container mb-2">
                            <?php $__currentLoopData = $transfers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transfer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip">
                                <span><?php echo e($transfer); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateTransfers();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <input type="text" class="form-control" id="transfersInput"
                               placeholder="Type and press Enter (e.g., Kenya, USA, Germany)"
                               autocomplete="off">
                        <div class="suggestions-dropdown" id="transfersSuggestions"></div>
                        <input type="hidden" name="international_transfers" id="transfers_hidden" value="<?php echo e(json_encode($transfers)); ?>">
                    </div>
                    <small class="text-muted mt-2 d-block">Type a country and press Enter, or select from suggestions</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-handshake" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Transfer Mechanism</h5>
                    </div>
                    <div class="multi-select-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            <?php
                                $mechanisms = [];
                                if ($submission->transfer_mechanisms) {
                                    $mechanisms = is_array($submission->transfer_mechanisms)
                                        ? $submission->transfer_mechanisms
                                        : (json_decode($submission->transfer_mechanisms, true) ?? []);
                                }
                            ?>
                            <?php $__currentLoopData = $mechanisms; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $mech): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span><?php echo e($mech); ?></span>
                                <button type="button" onclick="this.parentElement.remove(); updateMechanisms();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <input type="text" class="form-control" id="mechanismInput"
                               placeholder="Type and press Enter (e.g., Email, Cloud Storage, API)">
                        <input type="hidden" name="transfer_mechanisms" id="transfer_mechanisms_hidden" value="<?php echo e(json_encode($mechanisms)); ?>">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php if(!empty($transfers)): ?>
    <div class="alert alert-warning mt-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Compliance Reminder:</strong> Ensure appropriate safeguards are in place for international transfers, including Standard Contractual Clauses (SCCs) or Binding Corporate Rules (BCRs) where applicable.
    </div>
    <?php endif; ?>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function updateTransfers() {
    const container = document.querySelector('#transfersContainer .chips-container');
    if (!container) return;
    const values = Array.from(container.querySelectorAll('.tag-chip span'))
        .map(span => span.textContent.trim());
    document.getElementById('transfers_hidden').value = JSON.stringify(values);
}

(function() {
    const input = document.getElementById('transfersInput');
    const suggestionsBox = document.getElementById('transfersSuggestions');
    const container = document.querySelector('#transfersContainer .chips-container');
    const hiddenInput = document.getElementById('transfers_hidden');

    const PREDEFINED = [
        'Kenya',
        'United Kingdom',
        'United States',
        'Germany',
        'South Africa',
        'India',
        'Netherlands',
        'Ireland',
        'Canada',
        'Australia'
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
                    addTransfer(this.getAttribute('data-value'));
                    input.value = '';
                    input.focus();
                    renderSuggestions('');
                });
            });
        } else {
            suggestionsBox.classList.remove('active');
        }
    }

    function addTransfer(value) {
        if (!container) return;
        const trimmed = value.trim();
        if (!trimmed) return;
        if (getCurrentValues().includes(trimmed)) return;

        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.innerHTML = `<span>${escapeHtml(trimmed)}</span><button type="button" onclick="this.parentElement.remove(); updateTransfers();"><i class="fas fa-times"></i></button>`;
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
                    addTransfer(value);
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

function updateMechanisms() {
    const chips = document.querySelectorAll('#step9 .chips-container .tag-chip span');
    const values = Array.from(chips).map(chip => chip.textContent.trim());
    document.getElementById('transfer_mechanisms_hidden').value = JSON.stringify(values);
}

document.getElementById('mechanismInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value) {
            const container = document.querySelector('#step9 .chips-container');
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `<i class="fas fa-check-circle me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); updateMechanisms();"><i class="fas fa-times"></i></button>`;
            container.appendChild(chip);
            this.value = '';
            updateMechanisms();
        }
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step9.blade.php ENDPATH**/ ?>