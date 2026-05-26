{{-- File: resources/views/ropa/steps/step4.blade.php --}}
<div class="form-section">
    <div class="alert alert-warning mb-4">
        <i class="fas fa-share-alt me-2"></i> Please specify if and how personal data is shared within University departments.
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Do You Share Personal Data Internally?</label>
        <div class="d-flex gap-4">
            <div class="form-check">
                <input class="form-check-input" type="radio" name="share_internally" value="1" id="share-yes" {{ $ropaForm->share_internally === true ? 'checked' : '' }}>
                <label class="form-check-label" for="share-yes">Yes</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="share_internally" value="0" id="share-no" {{ $ropaForm->share_internally === false ? 'checked' : '' }}>
                <label class="form-check-label" for="share-no">No</label>
            </div>
        </div>
    </div>

    <div id="internal-recipients-section"
     class="conditional-section"
     @style([
         'display: block' => $ropaForm->share_internally,
         'display: none' => !$ropaForm->share_internally,
     ])>
        <div class="mb-3">
            <label class="form-label fw-bold">Categories of Personal Data Shared Internally</label>
            <textarea name="internal_sharing_categories" class="form-control" rows="2" placeholder="e.g., Personal data, health data, financial data">{{ old('internal_sharing_categories', $ropaForm->internal_sharing_categories) }}</textarea>
        </div>

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label fw-bold">Which Units Receive Shared Data?</label>
                <select name="internal_recipients[]" class="form-select" multiple size="3">
                    <option value="Directorate of Academic Affairs" {{ in_array('Directorate of Academic Affairs', $ropaForm->internal_recipients ?? []) ? 'selected' : '' }}>Directorate of Academic Affairs</option>
                    <option value="Human Resources Division" {{ in_array('Human Resources Division', $ropaForm->internal_recipients ?? []) ? 'selected' : '' }}>Human Resources Division</option>
                    <option value="Finance Department" {{ in_array('Finance Department', $ropaForm->internal_recipients ?? []) ? 'selected' : '' }}>Finance Department</option>
                    <option value="Internal Audit" {{ in_array('Internal Audit', $ropaForm->internal_recipients ?? []) ? 'selected' : '' }}>Internal Audit</option>
                    <option value="University Committees" {{ in_array('University Committees', $ropaForm->internal_recipients ?? []) ? 'selected' : '' }}>University Committees</option>
                </select>
                <small class="text-muted">Hold Ctrl/Cmd to select multiple</small>
            </div>

            <div class="col-md-6">
                <label class="form-label fw-bold">Which Units Receive Special Category Data? <span class="badge bg-danger">Sensitive</span></label>
                <select name="special_category_recipients[]" class="form-select" multiple size="3">
                    <option value="School of Medicine" {{ in_array('School of Medicine', $ropaForm->special_category_recipients ?? []) ? 'selected' : '' }}>School of Medicine</option>
                    <option value="University Hospital" {{ in_array('University Hospital', $ropaForm->special_category_recipients ?? []) ? 'selected' : '' }}>University Hospital</option>
                    <option value="Counseling Services" {{ in_array('Counseling Services', $ropaForm->special_category_recipients ?? []) ? 'selected' : '' }}>Counseling Services</option>
                    <option value="Research Ethics Committee" {{ in_array('Research Ethics Committee', $ropaForm->special_category_recipients ?? []) ? 'selected' : '' }}>Research Ethics Committee</option>
                </select>
            </div>
        </div>

        <div class="mt-3">
            <label class="form-label fw-bold">Reasons for Sharing Data</label>
            <textarea name="sharing_reasons" class="form-control" rows="2" placeholder="e.g., Investigative processes, reporting requirements...">{{ old('sharing_reasons', $ropaForm->sharing_reasons) }}</textarea>
        </div>
    </div>
</div>
