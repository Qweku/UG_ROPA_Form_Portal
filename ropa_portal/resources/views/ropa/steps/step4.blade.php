@php

function safeValue($value) {
if (is_array($value)) {
return implode(', ', $value);
}

return $value;
}

@endphp

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
                    {{ old('share_internally', $ropaForm->share_internally) == 1 ? 'checked' : '' }}>
                <label class="form-check-label" for="share-yes">
                    <i class="fas fa-check-circle text-success me-1"></i> Yes
                </label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="share_internally" value="0" id="share-no"
                    {{ old('share_internally', $ropaForm->share_internally) == 0 ? 'checked' : '' }}>
                <label class="form-check-label" for="share-no">
                    <i class="fas fa-times-circle text-danger me-1"></i> No
                </label>
            </div>
        </div>
    </div>

    <div id="internal-recipients-section" class="conditional-section mt-4"
        @if(old('share_internally', $ropaForm->share_internally) == 1)
        style="display:block;"
        @else
        style="display:none;"
        @endif>

        <div class="card bg-light">
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-database me-1"></i> Categories of Personal Data Shared Internally
                    </label>
                    <textarea name="internal_sharing_categories" class="form-control" rows="3"
                        placeholder="e.g., Personal data, health data, financial data, student records">{{ safeValue(old('internal_sharing_categories', $ropaForm->internal_sharing_categories)) }}</textarea>
                </div>

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-building me-1"></i> Which Units Receive Shared Data?
                        </label>
                        @php
                        // Decode internal_recipients properly
                        $internalRecipients = [];
                        if ($ropaForm->internal_recipients) {
                        if (is_array($ropaForm->internal_recipients)) {
                        $internalRecipients = $ropaForm->internal_recipients;
                        } elseif (is_string($ropaForm->internal_recipients)) {
                        $internalRecipients = json_decode($ropaForm->internal_recipients, true) ?? [];
                        }
                        }
                        @endphp
                        <select name="internal_recipients[]" class="form-select" multiple size="5">
                            <option value="Directorate of Academic Affairs"
                                {{ in_array('Directorate of Academic Affairs', $internalRecipients) ? 'selected' : '' }}>
                                📚 Directorate of Academic Affairs
                            </option>
                            <option value="Human Resources Division"
                                {{ in_array('Human Resources Division', $internalRecipients) ? 'selected' : '' }}>
                                👥 Human Resources Division
                            </option>
                            <option value="Finance Department"
                                {{ in_array('Finance Department', $internalRecipients) ? 'selected' : '' }}>
                                💰 Finance Department
                            </option>
                            <option value="Internal Audit"
                                {{ in_array('Internal Audit', $internalRecipients) ? 'selected' : '' }}>
                                🔍 Internal Audit
                            </option>
                            <option value="University Committees"
                                {{ in_array('University Committees', $internalRecipients) ? 'selected' : '' }}>
                                📋 University Committees
                            </option>
                            <option value="Research Office"
                                {{ in_array('Research Office', $internalRecipients) ? 'selected' : '' }}>
                                🔬 Research Office
                            </option>
                            <option value="Legal Counsel"
                                {{ in_array('Legal Counsel', $internalRecipients) ? 'selected' : '' }}>
                                ⚖️ Legal Counsel
                            </option>
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">
                            <i class="fas fa-shield-alt me-1"></i> Which Units Receive Special Category Data?
                            <span class="badge bg-danger">Sensitive</span>
                        </label>
                        @php
                        // Decode special_category_recipients properly
                        $specialRecipients = [];
                        if ($ropaForm->special_category_recipients) {
                        if (is_array($ropaForm->special_category_recipients)) {
                        $specialRecipients = $ropaForm->special_category_recipients;
                        } elseif (is_string($ropaForm->special_category_recipients)) {
                        $specialRecipients = json_decode($ropaForm->special_category_recipients, true) ?? [];
                        }
                        }
                        @endphp
                        <select name="special_category_recipients[]" class="form-select" multiple size="5">
                            <option value="School of Medicine and Dentistry"
                                {{ in_array('School of Medicine and Dentistry', $specialRecipients) ? 'selected' : '' }}>
                                🏥 School of Medicine and Dentistry
                            </option>
                            <option value="University Hospital"
                                {{ in_array('University Hospital', $specialRecipients) ? 'selected' : '' }}>
                                🏨 University Hospital
                            </option>
                            <option value="Counseling Services"
                                {{ in_array('Counseling Services', $specialRecipients) ? 'selected' : '' }}>
                                🗣️ Counseling Services
                            </option>
                            <option value="Research Ethics Committee"
                                {{ in_array('Research Ethics Committee', $specialRecipients) ? 'selected' : '' }}>
                                📜 Research Ethics Committee
                            </option>
                            <option value="Disability Support Services"
                                {{ in_array('Disability Support Services', $specialRecipients) ? 'selected' : '' }}>
                                ♿ Disability Support Services
                            </option>
                        </select>
                        <small class="text-muted">Hold Ctrl (Windows) or Cmd (Mac) to select multiple</small>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-bold">
                        <i class="fas fa-question-circle me-1"></i> Reasons for Sharing Data
                    </label>
                    <textarea name="sharing_reasons" class="form-control" rows="3"
                        placeholder="e.g., Investigative processes, reporting requirements, student support services...">{{ safeValue(old('sharing_reasons', $ropaForm->sharing_reasons)) }}</textarea>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
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
                document.querySelector('select[name="internal_recipients[]"]').selectedIndex = -1;
                document.querySelector('select[name="special_category_recipients[]"]').selectedIndex = -1;
                document.querySelector('textarea[name="sharing_reasons"]').value = '';
            }
        }

        if (shareYes && shareNo) {
            shareYes.addEventListener('change', toggleInternalSection);
            shareNo.addEventListener('change', toggleInternalSection);
            toggleInternalSection();
        }

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
@endpush
