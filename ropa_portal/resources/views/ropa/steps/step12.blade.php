
<div class="form-section">
    <div class="mb-4">
        <label class="form-label fw-bold">DPIA Required? <i class="help-tooltip fas fa-question-circle" data-bs-toggle="tooltip" title="A Data Protection Impact Assessment is required for high-risk processing"></i></label>
        <div class="d-flex gap-4">
            <div class="form-check"><input type="radio" name="dpia_required" value="1" id="dpia-yes" {{ $ropaForm->dpia_required === true ? 'checked' : '' }}><label for="dpia-yes">Yes</label></div>
            <div class="form-check"><input type="radio" name="dpia_required" value="0" id="dpia-no" {{ $ropaForm->dpia_required === false ? 'checked' : '' }}><label for="dpia-no">No</label></div>
        </div>
    </div>

    @if($ropaForm->dpia_required === true)
    <div class="conditional-section p-3 bg-light rounded">
        <div class="row">
            <div class="col-md-6">
                <label class="form-label fw-bold">DPIA Progress</label>
                <select name="dpia_progress" class="form-select">
                    <option value="not_started" {{ $ropaForm->dpia_progress == 'not_started' ? 'selected' : '' }}>Not Started</option>
                    <option value="in_progress" {{ $ropaForm->dpia_progress == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    <option value="completed" {{ $ropaForm->dpia_progress == 'completed' ? 'selected' : '' }}>Completed</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Link to DPIA Document</label>
                <input type="url" name="dpia_link" class="form-control" value="{{ old('dpia_link', $ropaForm->dpia_link) }}" placeholder="URL or file path">
            </div>
        </div>
        <div class="mt-3">
            <span class="badge bg-{{ $ropaForm->dpia_progress == 'completed' ? 'success' : ($ropaForm->dpia_progress == 'in_progress' ? 'warning' : 'danger') }}">
                Risk Level: {{ $ropaForm->dpia_progress == 'completed' ? 'Managed' : ($ropaForm->dpia_progress == 'in_progress' ? 'Mitigation in progress' : 'High - Assessment required') }}
            </span>
        </div>
    </div>
    @endif
</div>
