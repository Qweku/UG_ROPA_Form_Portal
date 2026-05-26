@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #153d6f;
        --accent: #b69964;
        --primary-light: #e8eef5;
    }

    .step-indicator {
        display: flex;
        justify-content: space-between;
        margin-bottom: 2rem;
        position: relative;
    }

    .step-indicator::before {
        content: '';
        position: absolute;
        top: 20px;
        left: 0;
        right: 0;
        height: 2px;
        background: #dee2e6;
        z-index: 1;
    }

    .step {
        background: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        border: 2px solid #dee2e6;
        z-index: 2;
        background: white;
        transition: all 0.3s;
    }

    .step.active {
        border-color: var(--primary);
        background: var(--primary);
        color: white;
    }

    .step.completed {
        border-color: var(--accent);
        background: var(--accent);
        color: white;
    }

    .step-label {
        font-size: 0.7rem;
        margin-top: 0.5rem;
        text-align: center;
        max-width: 80px;
    }

    .form-section {
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateX(10px);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .help-tooltip {
        color: var(--accent);
        cursor: help;
        margin-left: 5px;
    }

    .card-header-custom {
        background: var(--primary);
        color: white;
        border-radius: 8px 8px 0 0 !important;
    }

    .btn-next {
        background: var(--primary);
        color: white;
        border: none;
    }

    .btn-next:hover {
        background: #0e2d52;
        color: white;
    }

    .btn-accent {
        background: var(--accent);
        color: white;
        border: none;
    }

    .btn-accent:hover {
        background: #9e7d4a;
        color: white;
    }

    .tag-chip {
        background: var(--primary-light);
        color: var(--primary);
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 0.85rem;
        margin: 2px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .tag-chip button {
        background: none;
        border: none;
        color: #999;
        cursor: pointer;
    }

    .conditional-section {
        border-left: 3px solid var(--accent);
        padding-left: 1rem;
        margin-top: 1rem;
    }

    .required-field::after {
        content: '*';
        color: red;
        margin-left: 4px;
    }
</style>

<div class="container py-4">
    {{-- Progress Header --}}
    <div class="step-indicator mb-5">
        @foreach(range(1,14) as $i)
        <div style="text-align: center; flex: 1;">
            <div class="step {{ $ropaForm->current_step == $i ? 'active' : ($ropaForm->current_step > $i ? 'completed' : '') }}">{{ $i }}</div>
            <div class="step-label">{{ ['Basic Info','Joint Controllers','Data Categories','Internal Sharing','Data Source','Legal Basis','Security','External Sharing','International Transfers','Auto Decision','Consent & Storage','DPIA','Breaches','DPA Compliance'][$i-1] }}</div>
        </div>
        @endforeach
    </div>

    {{-- Form --}}
    <form method="POST" action="{{ route('ropa.update', $ropaForm) }}" id="ropaForm">
        @csrf
        @method('PUT')
        <input type="hidden" name="current_step" value="{{ $ropaForm->current_step }}">

        <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
            <div class="card-header-custom p-4">
                <h3 class="mb-0 fw-bold">Step {{ $ropaForm->current_step }}:
                    {{ ['Basic Information','Joint Controllers & Collaboration','Data Categories & Subjects','Data Sharing Within University','Source & Updating of Data','Legal Basis & Compliance','Security & Protection Measures','External Sharing & Recipients','International Transfers','Automated Decision Making','Consent & Storage Information','Data Protection Impact Assessment','Personal Data Breaches','DPA & GDPR Compliance'][$ropaForm->current_step-1] }}
                </h3>
            </div>
            <div class="card-body p-4">
                @include("ropa.steps.step{$ropaForm->current_step}")
            </div>
            <div class="card-footer bg-white p-4 d-flex justify-content-between">
                <div>
                    @if($ropaForm->current_step > 1)
                    <button type="submit" name="action" value="previous" class="btn btn-outline-secondary px-4">← Previous</button>
                    @endif
                    <button type="submit" name="action" value="save" class="btn btn-outline-warning ms-2 px-4">Save Draft</button>
                </div>
                <div>
                    @if($ropaForm->current_step < 14)
                        <button type="submit" name="action" value="next" class="btn btn-next px-5">Next Step →</button>
                        @else
                        <button type="submit" name="action" value="submit" class="btn btn-accent px-5" onclick="return confirm('Submit this RoPA form for review?')">Submit Form ✓</button>
                        @endif
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Conditional show/hide logic for various steps
        const toggleFields = () => {
            // Step 4: Internal Sharing conditional
            const shareInternally = document.querySelector('input[name="share_internally"]:checked');
            const internalSection = document.getElementById('internal-recipients-section');
            if (internalSection) internalSection.style.display = shareInternally?.value === '1' ? 'block' : 'none';

            // Step 10: Auto decision conditional
            const autoDecision = document.querySelector('input[name="auto_decision_making"]:checked');
            const profilingSection = document.getElementById('profiling-section');
            if (profilingSection) profilingSection.style.display = autoDecision?.value === '1' ? 'block' : 'none';

            // Step 14: Retention non-adherence conditional
            const retainedPerPolicy = document.querySelector('input[name="retained_per_policy"]:checked');
            const reasonSection = document.getElementById('non-adherence-reason');
            if (reasonSection) reasonSection.style.display = retainedPerPolicy?.value === '0' ? 'block' : 'none';
        };

        document.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(el => {
            el.addEventListener('change', toggleFields);
        });
        toggleFields();

        // FIXED: Multi-select chip input handlers with specific hidden field selector
        document.querySelectorAll('.multi-select-container').forEach(container => {
            const input = container.querySelector('input[type="text"]');
            const chipsContainer = container.querySelector('.chips-container');
            // FIXED: More specific selector - look for hidden field with a name attribute
            const hiddenField = container.querySelector('input[type="hidden"][name]');

            // Skip if missing required elements
            if (!input || !chipsContainer || !hiddenField) {
                console.warn('Multi-select container missing required elements', container);
                return;
            }

            // FIXED: Initialize existing chips from hidden field value
            function initializeFromHidden() {
                try {
                    const existingValue = hiddenField.value;
                    if (existingValue && existingValue !== '[]' && existingValue !== '') {
                        const items = JSON.parse(existingValue);
                        if (Array.isArray(items)) {
                            // Clear existing chips
                            chipsContainer.innerHTML = '';
                            // Add chips for each item
                            items.forEach(item => {
                                if (item && item.trim()) {
                                    addChipToContainer(item, false); // Don't update hidden again
                                }
                            });
                        }
                    }
                } catch(e) {
                    console.error('Error parsing existing value:', e);
                }
            }

            function addChipToContainer(value, shouldUpdateHidden = true) {
                const chip = document.createElement('span');
                chip.className = 'tag-chip';
                chip.style.cssText = 'background: #e8eef5; color: #153d6f; padding: 4px 12px; border-radius: 20px; font-size: 0.85rem; margin: 2px; display: inline-flex; align-items: center; gap: 6px;';

                const textSpan = document.createElement('span');
                textSpan.textContent = value;

                const removeBtn = document.createElement('button');
                removeBtn.type = 'button';
                removeBtn.textContent = '×';
                removeBtn.style.cssText = 'background: none; border: none; color: #999; cursor: pointer; font-size: 1.1rem; margin-left: 4px;';
                removeBtn.onclick = function() {
                    chip.remove();
                    updateHidden();
                };

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
                    // FIXED: Get the text content of the span, not including the button text
                    const textSpan = chip.querySelector('span');
                    if (textSpan && textSpan.textContent.trim()) {
                        values.push(textSpan.textContent.trim());
                    }
                });
                hiddenField.value = JSON.stringify(values);
            }

            // Handle Enter key to add new chip
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter' && this.value.trim()) {
                    e.preventDefault();
                    const value = this.value.trim();
                    addChipToContainer(value, true);
                    this.value = '';
                }
            });

            // Initialize from existing hidden value
            initializeFromHidden();
        });

        // Real-time validation
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
                }
            });
        });
    });
</script>
@endsection
