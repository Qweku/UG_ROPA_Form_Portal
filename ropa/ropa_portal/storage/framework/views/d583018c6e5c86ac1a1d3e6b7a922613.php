<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-shield-alt fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Security & Protection Measures</h5>
                <p class="mb-0 text-muted small">Capture technical and organizational safeguards implemented to protect personal data.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center mb-3">
                <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                    <i class="fas fa-lock" style="color: #153d6f;"></i>
                </div>
                <h5 class="card-title mb-0" style="color: #153d6f;">Security Measures</h5>
            </div>

            <label class="form-label fw-bold">Describe Security Measures Protecting Personal Data</label>
            <textarea name="security_measures" class="form-control" rows="6"
                      placeholder="Describe technical and organizational security measures..."><?php echo e(old('security_measures', $submission->security_measures)); ?></textarea>

            <div class="mt-4">
                <label class="form-label fw-bold">Suggested Security Measures Checklist:</label>
                <div class="row g-3 mt-2">
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Encryption (at rest and in transit)" id="sec-encrypt">
                            <label class="form-check-label" for="sec-encrypt">
                                <i class="fas fa-key me-1 text-primary"></i> Encryption
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Multi-factor authentication (MFA)" id="sec-mfa">
                            <label class="form-check-label" for="sec-mfa">
                                <i class="fas fa-mobile-alt me-1 text-primary"></i> Multi-factor Authentication
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Role-based access controls (RBAC)" id="sec-rbac">
                            <label class="form-check-label" for="sec-rbac">
                                <i class="fas fa-users me-1 text-primary"></i> Role-based Access Controls
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Audit logs maintained" id="sec-audit">
                            <label class="form-check-label" for="sec-audit">
                                <i class="fas fa-history me-1 text-primary"></i> Audit Logs
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Secure backups (encrypted offsite)" id="sec-backup">
                            <label class="form-check-label" for="sec-backup">
                                <i class="fas fa-database me-1 text-primary"></i> Secure Backups
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Data minimization and pseudonymization" id="sec-minimize">
                            <label class="form-check-label" for="sec-minimize">
                                <i class="fas fa-chart-line me-1 text-primary"></i> Data Minimization
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Regular security awareness training" id="sec-training">
                            <label class="form-check-label" for="sec-training">
                                <i class="fas fa-chalkboard-user me-1 text-primary"></i> Staff Training
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Incident response plan" id="sec-incident">
                            <label class="form-check-label" for="sec-incident">
                                <i class="fas fa-exclamation-triangle me-1 text-primary"></i> Incident Response Plan
                            </label>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Regular vulnerability assessments" id="sec-vuln">
                            <label class="form-check-label" for="sec-vuln">
                                <i class="fas fa-search me-1 text-primary"></i> Vulnerability Assessments
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.querySelectorAll('.suggest-check').forEach(cb => {
    cb.addEventListener('change', function() {
        const textarea = document.querySelector('textarea[name="security_measures"]');
        let current = textarea.value;
        if (this.checked) {
            if (!current.includes(this.value)) {
                textarea.value = current ? current + "\n• " + this.value : "• " + this.value;
            }
        } else {
            textarea.value = current.replace(new RegExp("\n?• " + this.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'), 'g'), '');
            textarea.value = textarea.value.replace(new RegExp("^• " + this.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&') + "\n", 'g'), '');
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step7.blade.php ENDPATH**/ ?>