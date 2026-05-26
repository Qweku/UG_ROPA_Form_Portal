
<div class="form-section">
    <div class="row">
        <div class="col-12">
            <label class="form-label fw-bold">Security Measures Protecting Personal Data</label>
            <textarea name="security_measures" class="form-control" rows="6" placeholder="Describe technical and organizational security measures...">{{ old('security_measures', $ropaForm->security_measures) }}</textarea>

            <div class="mt-3 p-3 bg-light rounded">
                <strong class="d-block mb-2">Suggested Security Measures Checklist:</strong>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Encryption (at rest and in transit)">
                            <label class="form-check-label">🔐 Encryption</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Password protection with MFA">
                            <label class="form-check-label">🔑 Password protection / MFA</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Role-based access controls (RBAC)">
                            <label class="form-check-label">👥 Access controls (RBAC)</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Audit logs maintained">
                            <label class="form-check-label">📝 Audit logs</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Secure backups (encrypted offsite)">
                            <label class="form-check-label">💾 Secure backups</label>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-check">
                            <input class="form-check-input suggest-check" type="checkbox" value="Data minimization and pseudonymization">
                            <label class="form-check-label">🛡️ Data minimization</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
        }
    });
});
</script>
