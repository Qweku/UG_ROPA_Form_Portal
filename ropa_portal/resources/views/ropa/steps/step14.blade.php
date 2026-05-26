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
                    <div class="multi-select-container mb-3">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @php
                                $dpaConditions = [];
                                if ($ropaForm->dpa_conditions) {
                                    $dpaConditions = is_array($ropaForm->dpa_conditions)
                                        ? $ropaForm->dpa_conditions
                                        : (json_decode($ropaForm->dpa_conditions, true) ?? []);
                                }
                            @endphp
                            @foreach($dpaConditions as $cond)
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span>{{ $cond }}</span>
                                <button type="button" onclick="this.parentElement.remove(); updateDpaConditions();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            @endforeach
                        </div>
                        <input type="text" class="form-control" id="dpaConditionInput"
                               placeholder="Type and press Enter (e.g., Legal requirement, Employment, Health, Research)">
                        <input type="hidden" name="dpa_conditions" id="dpa_conditions_hidden" value="{{ json_encode($dpaConditions) }}">
                    </div>

                    <label class="form-label fw-bold">
                        <i class="fab fa-internet-explorer me-1" style="color: #b69964;"></i>
                        GDPR Article 6 Lawful Basis
                    </label>
                    <div class="multi-select-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @php
                                $gdprArticles = [];
                                if ($ropaForm->gdpr_articles) {
                                    $gdprArticles = is_array($ropaForm->gdpr_articles)
                                        ? $ropaForm->gdpr_articles
                                        : (json_decode($ropaForm->gdpr_articles, true) ?? []);
                                }
                            @endphp
                            @foreach($gdprArticles as $art)
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span>{{ $art }}</span>
                                <button type="button" onclick="this.parentElement.remove(); updateGdprArticles();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            @endforeach
                        </div>
                        <input type="text" class="form-control" id="gdprArticleInput"
                               placeholder="Type and press Enter (e.g., Article 6(1)(b) - Contract, Article 6(1)(c) - Legal Obligation)">
                        <input type="hidden" name="gdpr_articles" id="gdpr_articles_hidden" value="{{ json_encode($gdprArticles) }}">
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
                               value="{{ old('retention_policy_link', $ropaForm->retention_policy_link) }}"
                               placeholder="URL to policy document">
                    </div>

                    <label class="form-label fw-bold">Is Data Retained According to Policy?</label>
                    <select name="retained_per_policy" class="form-select" id="retained_per_policy">
                        <option value="">-- Select --</option>
                        <option value="1" {{ $ropaForm->retained_per_policy === true ? 'selected' : '' }}>
                            ✅ Yes - Data is retained per established schedule
                        </option>
                        <option value="0" {{ $ropaForm->retained_per_policy === false ? 'selected' : '' }}>
                            ❌ No - Exceptions exist
                        </option>
                    </select>

                    <div id="non-adherence-reason" class="mt-3 {{ (string) $ropaForm->retained_per_policy !== '0' ? 'd-none' : '' }}">
                        <label class="form-label fw-bold">
                            <i class="fas fa-exclamation-triangle me-1" style="color: #dc3545;"></i>
                            Reasons for Non-Adherence
                        </label>
                        <textarea name="retention_non_adherence_reason" class="form-control" rows="4"
                                  placeholder="Explain why data is not being retained according to the official policy...">{{ old('retention_non_adherence_reason', $ropaForm->retention_non_adherence_reason) }}</textarea>
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

@push('scripts')
<script>
function updateDpaConditions() {
    const chips = document.querySelectorAll('#step14 .multi-select-container:first-child .chips-container .tag-chip span');
    const values = Array.from(chips).map(chip => chip.textContent.trim());
    document.getElementById('dpa_conditions_hidden').value = JSON.stringify(values);
}

function updateGdprArticles() {
    const chips = document.querySelectorAll('#step14 .multi-select-container:last-child .chips-container .tag-chip span');
    const values = Array.from(chips).map(chip => chip.textContent.trim());
    document.getElementById('gdpr_articles_hidden').value = JSON.stringify(values);
}

document.getElementById('dpaConditionInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value) {
            const container = document.querySelector('#step14 .multi-select-container:first-child .chips-container');
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `<i class="fas fa-check-circle me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); updateDpaConditions();"><i class="fas fa-times"></i></button>`;
            container.appendChild(chip);
            this.value = '';
            updateDpaConditions();
        }
    }
});

document.getElementById('gdprArticleInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value) {
            const container = document.querySelector('#step14 .multi-select-container:last-child .chips-container');
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `<i class="fas fa-check-circle me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); updateGdprArticles();"><i class="fas fa-times"></i></button>`;
            container.appendChild(chip);
            this.value = '';
            updateGdprArticles();
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const retainedPerPolicy = document.getElementById('retained_per_policy');
    const reasonSection = document.getElementById('non-adherence-reason');

    function toggleReasonSection() {
        if (retainedPerPolicy && retainedPerPolicy.value === '0') {
            reasonSection.style.display = 'block';
        } else {
            reasonSection.style.display = 'none';
        }
    }

    if (retainedPerPolicy) {
        retainedPerPolicy.addEventListener('change', toggleReasonSection);
        toggleReasonSection();
    }

    // Legal Reference collapse - dynamic text and icon
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
@endpush
