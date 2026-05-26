<div class="form-section">
    <div class="alert alert-info mb-4">
        <i class="fas fa-robot me-2"></i> <strong>What is Automated Decision Making?</strong><br>
        Automated decision-making refers to decisions made by systems without human involvement, such as credit scoring algorithms, automated hiring rejections, or AI-based profiling.
    </div>

    <div class="mb-4">
        <label class="form-label fw-bold">Does automated decision-making exist for this processing activity?</label>
        <div class="d-flex gap-4">
            <div class="form-check"><input type="radio" name="auto_decision_making" value="1" id="auto-yes" {{ $ropaForm->auto_decision_making === true ? 'checked' : '' }}><label for="auto-yes">Yes</label></div>
            <div class="form-check"><input type="radio" name="auto_decision_making" value="0" id="auto-no" {{ $ropaForm->auto_decision_making === false ? 'checked' : '' }}><label for="auto-no">No</label></div>
        </div>
    </div>

    <div id="profiling-section"  @style([
         'display: block' => $ropaForm->auto_decision_making,
         'display: none' => !$ropaForm->auto_decision_making,
     ])>
        <label class="form-label fw-bold">Description of Profiling / Automated Decision Logic</label>
        <textarea name="profiling_description" class="form-control" rows="4" placeholder="e.g., Algorithm screens CVs for keywords and automatically rejects candidates below a threshold score. No human review before rejection.">{{ old('profiling_description', $ropaForm->profiling_description) }}</textarea>
        <small class="text-muted">Provide details about the logic involved, significance, and potential consequences.</small>
    </div>
</div>
