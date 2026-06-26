<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-robot fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Automated Decision Making & Profiling</h5>
                <p class="mb-0 text-muted small">Capture profiling or automated processing that has legal or significant effects on individuals.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-microchip" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Automated Processing Status</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3 p-3 border rounded" style="cursor: pointer;" onclick="document.getElementById('auto-yes').click(); document.getElementById('profiling-section').classList.remove('d-none')">
                                <input class="form-check-input" type="radio" name="auto_decision_making" value="1" id="auto-yes"
                                       <?php echo e($submission->auto_decision_making === true ? 'checked' : ''); ?>>
                                <label class="form-check-label d-block" for="auto-yes">
                                    <i class="fas fa-check-circle fa-lg me-2" style="color: #28a745;"></i>
                                    <strong>Yes - Automated decision-making is used</strong>
                                    <br>
                                    <small class="text-muted">Decisions are made by systems without meaningful human involvement</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3 p-3 border rounded" style="cursor: pointer;" onclick="document.getElementById('auto-no').click(); document.getElementById('profiling-section').classList.add('d-none')">
                                <input class="form-check-input" type="radio" name="auto_decision_making" value="0" id="auto-no"
                                       <?php echo e($submission->auto_decision_making === false ? 'checked' : ''); ?>>
                                <label class="form-check-label d-block" for="auto-no">
                                    <i class="fas fa-times-circle fa-lg me-2" style="color: #dc3545;"></i>
                                    <strong>No - No automated decision-making</strong>
                                    <br>
                                    <small class="text-muted">All decisions involve human review and intervention</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="profiling-section" class="mt-4 <?php echo e($submission->auto_decision_making ? '' : 'd-none'); ?>">
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>GDPR Article 22 Requirement:</strong>
                            Individuals have the right not to be subject to a decision based solely on automated processing, including profiling, which produces legal effects or significantly affects them.
                        </div>

                        <label class="form-label fw-bold mt-3">
                            <i class="fas fa-chart-line me-1" style="color: #b69964;"></i>
                            Description of Automated Decision Making / Profiling Logic
                        </label>
                        <textarea name="profiling_description" class="form-control" rows="5"
                                  placeholder="Example: 'Our recruitment system uses an AI algorithm to screen CVs based on keyword matching. Candidates below a threshold score are automatically rejected without human review. The algorithm considers factors such as years of experience, educational qualifications, and specific technical skills.'"><?php echo e(old('profiling_description', $submission->profiling_description)); ?></textarea>

                        <div class="alert alert-light mt-3">
                            <i class="fas fa-lightbulb me-2" style="color: #b69964;"></i>
                            <strong>Best Practices for Automated Decisions:</strong>
                            <ul class="mb-0 mt-2">
                                <li>Provide meaningful information about the logic involved</li>
                                <li>Explain the significance and potential consequences</li>
                                <li>Offer the right to human intervention and to contest the decision</li>
                                <li>Conduct a Data Protection Impact Assessment (DPIA)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step10.blade.php ENDPATH**/ ?>