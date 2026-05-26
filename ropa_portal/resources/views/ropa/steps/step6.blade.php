
<div class="form-section">
    <div class="alert alert-light border-start border-4 border-warning mb-4" style="border-left-color: #b69964!important;">
        <strong><i class="fas fa-gavel me-2"></i> Legal Basis Information</strong><br>
        Under the Data Protection Act, you must have a valid legal basis for processing personal data. Common bases include: Consent, Contract, Legal Obligation, Vital Interests, Public Task, Legitimate Interests.
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Legal Basis for Processing</label>
            <div class="multi-select-container">
                <div class="chips-container">
                    @foreach(($ropaForm->legal_basis ?? []) as $basis)
                    <span class="tag-chip">{{ $basis }} <button type="button">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="e.g., Consent, Contract, Legal Obligation">
                <input type="hidden" name="legal_basis" value="{{ json_encode($ropaForm->legal_basis ?? []) }}">
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Legal Basis for Sensitive Personal Data</label>
            <div class="multi-select-container">
                <div class="chips-container">
                    @foreach(($ropaForm->sensitive_legal_basis ?? []) as $basis)
                    <span class="tag-chip">{{ $basis }} <button type="button">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="e.g., Medical purposes, Employment law, Public functions">
                <input type="hidden" name="sensitive_legal_basis" value="{{ json_encode($ropaForm->sensitive_legal_basis ?? []) }}">
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Legitimate Interest Assessment Documented?</label>
            <select name="lia_documented" class="form-select">
                <option value="">Select...</option>
                <option value="1" {{ $ropaForm->lia_documented === true ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $ropaForm->lia_documented === false ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Location of Legitimate Interest Assessment</label>
            <input type="text" name="lia_location" class="form-control" value="{{ old('lia_location', $ropaForm->lia_location) }}" placeholder="File path or URL">
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Retention Period</label>
            <input type="text" name="retention_period" class="form-control" value="{{ old('retention_period', $ropaForm->retention_period) }}" placeholder="e.g., 8 years after account closure">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Legitimately Required to Keep Data?</label>
            <select name="legally_required_retention" class="form-select">
                <option value="">Select...</option>
                <option value="1" {{ $ropaForm->legally_required_retention === true ? 'selected' : '' }}>Yes</option>
                <option value="0" {{ $ropaForm->legally_required_retention === false ? 'selected' : '' }}>No</option>
            </select>
        </div>

        <div class="col-12">
            <label class="form-label fw-bold">Legitimate Interests for Processing</label>
            <textarea name="legitimate_interests" class="form-control" rows="3" placeholder="Explain the legitimate interests pursued by the controller...">{{ old('legitimate_interests', $ropaForm->legitimate_interests) }}</textarea>
        </div>

        <div class="col-12">
            <label class="form-label fw-bold">Rights Available to Individuals</label>
            <div class="row">
                @foreach(['Access','Rectification','Erasure','Restriction','Portability','Object'] as $right)
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="individual_rights[]" value="{{ $right }}" id="right-{{ $right }}" {{ in_array($right, $ropaForm->individual_rights ?? []) ? 'checked' : '' }}>
                        <label class="form-check-label" for="right-{{ $right }}">{{ $right }}</label>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
