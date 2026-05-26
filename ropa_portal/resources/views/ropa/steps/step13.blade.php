
<div class="form-section">
    <div class="alert alert-danger mb-4">
        <i class="fas fa-exclamation-triangle"></i> <strong>Data Breach Documentation</strong><br>
        If a personal data breach has occurred, you must document it and report to the Data Protection Commission within 72 hours.
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Has a Personal Data Breach Occurred for this Processing Activity?</label>
        <div class="d-flex gap-4">
            <div class="form-check"><input type="radio" name="breach_occurred" value="1" id="breach-yes" {{ $ropaForm->breach_occurred === true ? 'checked' : '' }}><label for="breach-yes">Yes</label></div>
            <div class="form-check"><input type="radio" name="breach_occurred" value="0" id="breach-no" {{ $ropaForm->breach_occurred === false ? 'checked' : '' }}><label for="breach-no">No</label></div>
        </div>
    </div>

    @if($ropaForm->breach_occurred === true)
    <div class="alert alert-warning">
        <strong>⚠️ Action Required:</strong> Link to the breach record documentation.
    </div>
    <div class="mb-3">
        <label class="form-label fw-bold">Link to Breach Record / Incident Report</label>
        <input type="url" name="breach_link" class="form-control" value="{{ old('breach_link', $ropaForm->breach_link) }}" placeholder="https://..." required>
        <small class="text-muted">Include the incident number, root cause analysis, and remediation actions.</small>
    </div>
    @endif
</div>
