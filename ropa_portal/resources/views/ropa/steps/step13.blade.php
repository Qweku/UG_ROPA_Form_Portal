<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-exclamation-triangle fa-2x" style="color: #dc3545;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Personal Data Breach Documentation</h5>
                <p class="mb-0 text-muted small">Capture breach incidents and reporting for this processing activity.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-shield-virus" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Breach Status</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3 p-3 border rounded" style="cursor: pointer;" onclick="document.getElementById('breach-yes').click()">
                                <input class="form-check-input" type="radio" name="breach_occurred" value="1" id="breach-yes"
                                    {{ old('breach_occurred', $submission->breach_occurred) == '1' ? 'checked' : '' }}>
                                <label class="form-check-label d-block" for="breach-yes">
                                    <i class="fas fa-exclamation-circle fa-lg me-2" style="color: #dc3545;"></i>
                                    <strong>Yes - A breach has occurred</strong>
                                    <br>
                                    <small class="text-muted">Document the incident and remediation actions</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3 p-3 border rounded" style="cursor: pointer;" onclick="document.getElementById('breach-no').click()">
                                <input class="form-check-input" type="radio" name="breach_occurred" value="0" id="breach-no"
                                    {{ old('breach_occurred', $submission->breach_occurred) == '0' ? 'checked' : '' }}>
                                <label class="form-check-label d-block" for="breach-no">
                                    <i class="fas fa-shield-alt fa-lg me-2" style="color: #28a745;"></i>
                                    <strong>No - No breach has occurred</strong>
                                    <br>
                                    <small class="text-muted">No incidents to report for this processing activity</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="breach-section"
                        class="mt-4"
                        style="display: {{ old('breach_occurred', $submission->breach_occurred) == '1' ? 'block' : 'none' }};">
                        <div class="alert alert-danger">
                            <i class="fas fa-bell me-2"></i>
                            <strong>GDPR Breach Notification Requirements (Article 33-34):</strong>
                            <ul class="mb-0 mt-2">
                                <li>Notify supervisory authority within 72 hours</li>
                                <li>Communicate breach to affected individuals without undue delay</li>
                                <li>Document all breaches regardless of notification requirement</li>
                            </ul>
                        </div>

                        <label class="form-label fw-bold mt-3">
                            <i class="fas fa-link me-1" style="color: #b69964;"></i>
                            Link to Breach Record / Incident Report
                        </label>
                        <div class="input-group mb-2">
                            <span class="input-group-text bg-transparent">
                                <i class="fas fa-file-alt" style="color: #dc3545;"></i>
                            </span>
                            <input type="url" name="breach_link" class="form-control"
                                value="{{ old('breach_link', $submission->breach_link) }}"
                                placeholder="https://... (Link to incident report, root cause analysis, and remediation actions)"
                                required>
                        </div>
                        <small class="text-muted d-block">
                            <i class="fas fa-info-circle me-1"></i>
                            Include incident number, root cause analysis, affected individuals, and remediation actions
                        </small>

                        <div class="alert alert-light mt-3">
                            <i class="fas fa-clipboard-list me-2" style="color: #b69964;"></i>
                            <strong>Breach Documentation Checklist:</strong>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li>Date and time of breach discovery</li>
                                        <li>Description of the breach</li>
                                        <li>Categories and approximate number of affected individuals</li>
                                        <li>Categories and approximate number of affected records</li>
                                    </ul>
                                </div>
                                <div class="col-md-6">
                                    <ul class="mb-0 small">
                                        <li>Name and contact details of Data Protection Officer</li>
                                        <li>Likely consequences of the breach</li>
                                        <li>Measures taken to address the breach</li>
                                        <li>Measures to mitigate possible adverse effects</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const breachYes = document.getElementById('breach-yes');
        const breachNo = document.getElementById('breach-no');
        const breachSection = document.getElementById('breach-section');

        function toggleBreachSection() {

            if (!breachSection) return;

            const breachLink = document.querySelector('input[name="breach_link"]');

            if (breachYes.checked) {
                breachSection.style.display = 'block';

                if (breachLink) {
                    breachLink.required = true;
                }

            } else {

                breachSection.style.display = 'none';

                if (breachLink) {
                    breachLink.required = false;
                    breachLink.value = '';
                }
            }
        }

        if (breachYes && breachNo) {
            breachYes.addEventListener('change', toggleBreachSection);
            breachNo.addEventListener('change', toggleBreachSection);
            toggleBreachSection();
        }
    });
</script>
@endpush
