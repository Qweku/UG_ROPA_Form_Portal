
<div class="form-section">
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Data Protection Act 2018 Schedule 1 Condition</label>
            <div class="multi-select-container">
                <div class="chips-container">
                    @foreach(($ropaForm->dpa_conditions ?? []) as $cond)
                    <span class="tag-chip">{{ $cond }} <button type="button">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="e.g., Legal requirement, Employment, Health, Research">
                <input type="hidden" name="dpa_conditions" value="{{ json_encode($ropaForm->dpa_conditions ?? []) }}">
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">GDPR Article 6 Lawful Basis</label>
            <div class="multi-select-container">
                <div class="chips-container">
                    @foreach(($ropaForm->gdpr_articles ?? []) as $art)
                    <span class="tag-chip">{{ $art }} <button type="button">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="e.g., Article 6(1)(b) - Contract, Article 6(1)(c) - Legal Obligation">
                <input type="hidden" name="gdpr_articles" value="{{ json_encode($ropaForm->gdpr_articles ?? []) }}">
            </div>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Link to Retention & Erasure Policy</label>
            <input type="url" name="retention_policy_link" class="form-control" value="{{ old('retention_policy_link', $ropaForm->retention_policy_link) }}" placeholder="URL to policy document">
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Is Data Retained According to Policy?</label>
            <select name="retained_per_policy" class="form-select">
                <option value="">Select...</option>
                <option value="1" {{ $ropaForm->retained_per_policy === true ? 'selected' : '' }}>Yes - Data is retained per established schedule</option>
                <option value="0" {{ $ropaForm->retained_per_policy === false ? 'selected' : '' }}>No - Exceptions exist</option>
            </select>
        </div>

        <div id="non-adherence-reason" class="col-12" @style([
         'display: block' => !$ropaForm->retained_per_policy,
         'display: none' => $ropaForm->retained_per_policy,
     ])>
            <label class="form-label fw-bold">Reasons for Non-Adherence to Retention Policy</label>
            <textarea name="retention_non_adherence_reason" class="form-control" rows="3" placeholder="Explain why data is not being retained according to the official policy...">{{ old('retention_non_adherence_reason', $ropaForm->retention_non_adherence_reason) }}</textarea>
        </div>
    </div>

    <div class="alert alert-success mt-4">
        <i class="fas fa-check-circle me-2"></i> Thank you for completing the RoPA form. Please review all information before submitting.
    </div>
</div>
