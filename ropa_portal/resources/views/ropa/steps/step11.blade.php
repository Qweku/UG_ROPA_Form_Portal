<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-file-signature fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Consent & Storage Information</h5>
                <p class="mb-0 text-muted small">Capture consent records and data location details.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-check-double" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Consent Management</h5>
                    </div>

                    <label class="form-label fw-bold">Link to Record of Consent</label>
                    <div class="input-group mb-2">
                        <span class="input-group-text bg-transparent">
                            <i class="fas fa-link" style="color: #b69964;"></i>
                        </span>
                        <input type="url" name="consent_link" class="form-control"
                               value="{{ old('consent_link', $ropaForm->consent_link) }}"
                               placeholder="https://...">
                    </div>
                    <small class="text-muted d-block mb-3">
                        <i class="fas fa-info-circle me-1"></i>
                        Upload or link to consent forms, timestamped consent records, or audit trails
                    </small>

                    <div class="alert alert-light">
                        <i class="fas fa-gavel me-2" style="color: #b69964;"></i>
                        <strong>Consent Requirements (GDPR Article 7):</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Must be freely given, specific, informed, and unambiguous</li>
                            <li>Must be as easy to withdraw as to give</li>
                            <li>Records must demonstrate consent was obtained</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-database" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Storage Location</h5>
                    </div>

                    <label class="form-label fw-bold">Where is Personal Data Stored?</label>
                    <textarea name="data_location" class="form-control" rows="5"
                              placeholder="Example: 'Primary storage: University shared drive (G:\HR\Personnel Records). Cloud backup: Microsoft SharePoint with EU data residency. On-premise database: Oracle server in Data Center Room 204.'">{{ old('data_location', $ropaForm->data_location) }}</textarea>

                    <div class="alert alert-light mt-3">
                        <i class="fas fa-shield-alt me-2" style="color: #b69964;"></i>
                        <strong>Storage Security Considerations:</strong>
                        <ul class="mb-0 mt-2 small">
                            <li>Encryption at rest and in transit</li>
                            <li>Access controls and authentication</li>
                            <li>Regular backup and recovery procedures</li>
                            <li>Data residency compliance</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
