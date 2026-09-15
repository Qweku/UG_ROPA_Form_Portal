<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-chart-line fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Data Source & Maintenance</h5>
                <p class="mb-0 text-muted small">Understand where the data comes from and how it is maintained over time.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-code-commit" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Source of Data</h5>
                    </div>

                    <div class="form-check mb-3 p-3 border rounded" style="cursor: pointer;">
<input class="form-check-input" type="radio" name="data_source" value="individual" id="src-individual"
                                {{ ($submission->data_source ?? '') == 'individual' ? 'checked' : '' }}>
                        <label class="form-check-label d-block" for="src-individual">
                            <i class="fas fa-user-circle fa-lg me-2" style="color: #153d6f;"></i>
                            <strong>Individual (Data Subject)</strong>
                            <br>
                            <small class="text-muted">Data is provided directly by the individual through forms, applications, or online portals.</small>
                        </label>
                    </div>

                    <div class="form-check p-3 border rounded" style="cursor: pointer;">
                        <input class="form-check-input" type="radio" name="data_source" value="third_party" id="src-third"
                               {{ ($submission->data_source ?? '') == 'third_party' ? 'checked' : '' }}>
                        <label class="form-check-label d-block" for="src-third">
                            <i class="fas fa-building fa-lg me-2" style="color: #b69964;"></i>
                            <strong>Third-Party</strong>
                            <br>
                            <small class="text-muted">Data is obtained from external sources such as reference checks, background verification, or partner institutions.</small>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-sync-alt" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Data Update Process</h5>
                    </div>

                    <label class="form-label fw-bold">How is Personal Data Updated?</label>
                    <textarea name="data_update_method" class="form-control" rows="6"
                              placeholder="Example: Data is updated annually during registration through the self-service portal. Staff must submit change requests within 30 days of any change. Verification is done through official documentation.">{{ old('data_update_method', $submission->data_update_method) }}</textarea>

                    <div class="alert alert-light mt-3">
                        <i class="fas fa-lightbulb me-2" style="color: #b69964;"></i>
                        <small class="text-muted">
                            <strong>Best Practice:</strong> Establish regular review cycles and clear procedures for data updates. Consider automated verification where possible.
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
