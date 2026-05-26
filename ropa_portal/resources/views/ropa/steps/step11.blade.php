
<div class="form-section">
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Link to Record of Consent (if applicable)</label>
            <input type="url" name="consent_link" class="form-control" value="{{ old('consent_link', $ropaForm->consent_link) }}" placeholder="https://...">
            <small class="text-muted">Upload or link to consent forms, timestamped consent records.</small>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Location of Personal Data</label>
            <textarea name="data_location" class="form-control" rows="3" placeholder="e.g., University shared drive: G:\HR\Personnel, Cloud storage: SharePoint, On-premise database">{{ old('data_location', $ropaForm->data_location) }}</textarea>
        </div>
    </div>
</div>
