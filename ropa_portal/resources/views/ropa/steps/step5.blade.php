
<div class="form-section">
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Source of Personal Data</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="data_source" value="individual" id="src-individual" {{ ($ropaForm->data_source ?? '') == 'individual' ? 'checked' : '' }}>
                <label class="form-check-label" for="src-individual">👤 Individual (Data subject provides directly)</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="data_source" value="third_party" id="src-third" {{ ($ropaForm->data_source ?? '') == 'third_party' ? 'checked' : '' }}>
                <label class="form-check-label" for="src-third">🏢 Third-party (External source, e.g., reference checks, background verification)</label>
            </div>
        </div>

        <div class="col-12">
            <label class="form-label fw-bold">Update of Personal Data</label>
            <textarea name="data_update_method" class="form-control" rows="4" placeholder="e.g., Data is updated annually via HR self-service portal. Staff must submit change requests within 30 days of change.">{{ old('data_update_method', $ropaForm->data_update_method) }}</textarea>
            <small class="text-muted">Example: "Data is verified and updated every semester during registration. Students can update contact details via the portal anytime."</small>
        </div>
    </div>
</div>
