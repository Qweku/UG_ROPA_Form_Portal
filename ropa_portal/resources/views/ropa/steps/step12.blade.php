<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-chart-line fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Data Protection Impact Assessment (DPIA)</h5>
                <p class="mb-0 text-muted small">Capture DPIA requirements and progress for high-risk processing activities.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-clipboard-list" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">DPIA Requirement</h5>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3 p-3 border rounded" style="cursor: pointer;" onclick="document.getElementById('dpia-yes').click()">
                                <input class="form-check-input" type="radio" name="dpia_required" value="1" id="dpia-yes"
                                       {{ $submission->dpia_required === true ? 'checked' : '' }}>
                                <label class="form-check-label d-block" for="dpia-yes">
                                    <i class="fas fa-exclamation-triangle fa-lg me-2" style="color: #ffc107;"></i>
                                    <strong>Yes - DPIA Required</strong>
                                    <br>
                                    <small class="text-muted">High-risk processing that requires formal assessment</small>
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3 p-3 border rounded" style="cursor: pointer;" onclick="document.getElementById('dpia-no').click()">
                                <input class="form-check-input" type="radio" name="dpia_required" value="0" id="dpia-no"
                                       {{ $submission->dpia_required === false ? 'checked' : '' }}>
                                <label class="form-check-label d-block" for="dpia-no">
                                    <i class="fas fa-check-circle fa-lg me-2" style="color: #28a745;"></i>
                                    <strong>No - DPIA Not Required</strong>
                                    <br>
                                    <small class="text-muted">Low-risk processing that doesn't require formal assessment</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div id="dpia-section" class="mt-4 {{ $submission->dpia_required !== true ? 'd-none' : '' }}">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-chart-simple me-1" style="color: #b69964;"></i>
                                    DPIA Progress
                                </label>
                                <select name="dpia_progress" class="form-select" id="dpia_progress">
                                    <option value="not_started" {{ $submission->dpia_progress == 'not_started' ? 'selected' : '' }}>
                                        ⚪ Not Started
                                    </option>
                                    <option value="in_progress" {{ $submission->dpia_progress == 'in_progress' ? 'selected' : '' }}>
                                        🟡 In Progress
                                    </option>
                                    <option value="completed" {{ $submission->dpia_progress == 'completed' ? 'selected' : '' }}>
                                        🟢 Completed
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-link me-1" style="color: #b69964;"></i>
                                    DPIA Document Link
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-file-pdf" style="color: #dc3545;"></i>
                                    </span>
                                    <input type="url" name="dpia_link" class="form-control"
                                           value="{{ old('dpia_link', $submission->dpia_link) }}"
                                           placeholder="URL to DPIA document">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 p-3 rounded" id="risk-indicator" style="background: #f8f9fa;">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="fas fa-chart-line me-2"></i>
                                    <strong>Risk Level Assessment:</strong>
                                </div>
                                <div>
                                     @php
                                         $riskLevel = 'Not Assessed';
                                         $riskClass = 'bg-secondary';
                                         if ($submission->dpia_progress == 'completed') {
                                             $riskLevel = 'Risk Managed';
                                             $riskClass = 'bg-success';
                                         } elseif ($submission->dpia_progress == 'in_progress') {
                                             $riskLevel = 'Mitigation in Progress';
                                             $riskClass = 'bg-warning';
                                         } elseif ($submission->dpia_required === true) {
                                             $riskLevel = 'High - Assessment Required';
                                             $riskClass = 'bg-danger';
                                         }
                                     @endphp
                                     <span class="badge {{ $riskClass }}" style="padding: 8px 16px;">
                                         <i class="fas fa-{{ $submission->dpia_progress == 'completed' ? 'check' : ($submission->dpia_progress == 'in_progress' ? 'clock' : 'exclamation') }} me-1"></i>
                                         {{ $riskLevel }}
                                     </span>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-light mt-3">
                            <i class="fas fa-info-circle me-2" style="color: #b69964;"></i>
                            <strong>When is a DPIA required?</strong>
                            <ul class="mb-0 mt-2 small">
                                <li>Systematic and extensive evaluation of personal aspects</li>
                                <li>Processing on a large scale of special categories of data</li>
                                <li>Systematic monitoring of publicly accessible areas</li>
                                <li>Use of new technologies</li>
                            </ul>
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
    const dpiaYes = document.getElementById('dpia-yes');
    const dpiaNo = document.getElementById('dpia-no');
    const dpiaSection = document.getElementById('dpia-section');
    const dpiaProgress = document.getElementById('dpia_progress');
    const riskIndicator = document.getElementById('risk-indicator');

    function toggleDpiaSection() {
        if (dpiaYes && dpiaYes.checked) {
            dpiaSection.style.display = 'block';
        } else if (dpiaNo && dpiaNo.checked) {
            dpiaSection.style.display = 'none';
        }
    }

    function updateRiskLevel() {
        if (!dpiaSection || dpiaSection.style.display === 'none') return;

        const progress = dpiaProgress?.value;
        let riskLevel = '';
        let riskColor = '';
        let riskIcon = '';

        if (progress === 'completed') {
            riskLevel = 'Risk Managed';
            riskColor = '#28a745';
            riskIcon = 'check';
        } else if (progress === 'in_progress') {
            riskLevel = 'Mitigation in Progress';
            riskColor = '#ffc107';
            riskIcon = 'clock';
        } else {
            riskLevel = 'High - Assessment Required';
            riskColor = '#dc3545';
            riskIcon = 'exclamation';
        }

        if (riskIndicator) {
            riskIndicator.innerHTML = `
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-chart-line me-2"></i>
                        <strong>Risk Level Assessment:</strong>
                    </div>
                    <div>
                        <span class="badge" style="background: ${riskColor}; padding: 8px 16px;">
                            <i class="fas fa-${riskIcon} me-1"></i>
                            ${riskLevel}
                        </span>
                    </div>
                </div>
            `;
        }
    }

    if (dpiaYes && dpiaNo) {
        dpiaYes.addEventListener('change', toggleDpiaSection);
        dpiaNo.addEventListener('change', toggleDpiaSection);
        toggleDpiaSection();
    }

    if (dpiaProgress) {
        dpiaProgress.addEventListener('change', updateRiskLevel);
    }
});
</script>
@endpush
