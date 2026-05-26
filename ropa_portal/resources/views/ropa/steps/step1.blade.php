
<div class="form-section">
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Date Created <span class="required-field"></span></label>
            <input type="date" name="date_created" class="form-control" value="{{ date('Y-m-d') }}" readonly>
            <small class="text-muted">Auto-filled with today's date</small>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Personnel ID</label>
            <input type="text" name="personnel_id" class="form-control" value="{{ old('personnel_id', $ropaForm->personnel_id ?? Auth::user()->employee_id ?? '') }}" placeholder="e.g., 99999">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Surname</label>
            <input type="text" name="surname" class="form-control" value="{{ old('surname', $ropaForm->surname ?? Auth::user()->surname ?? '') }}">
        </div>
        <div class="col-md-3">
            <label class="form-label fw-bold">Firstname</label>
            <input type="text" name="firstname" class="form-control" value="{{ old('firstname', $ropaForm->firstname ?? Auth::user()->name ?? '') }}">
        </div>
        <div class="col-md-9">
            <label class="form-label fw-bold">Business Function <i class="help-tooltip fas fa-question-circle" data-bs-toggle="tooltip" title="College/School/Department/Unit"></i></label>
            <select name="business_function" class="form-select searchable-dropdown">
                <option value="">Select Business Function...</option>
                <option value="College of Health Sciences" {{ ($ropaForm->business_function ?? '') == 'College of Health Sciences' ? 'selected' : '' }}>College of Health Sciences</option>
                <option value="College of Humanities" {{ ($ropaForm->business_function ?? '') == 'College of Humanities' ? 'selected' : '' }}>College of Humanities</option>
                <option value="College of Basic and Applied Sciences" {{ ($ropaForm->business_function ?? '') == 'College of Basic and Applied Sciences' ? 'selected' : '' }}>College of Basic and Applied Sciences</option>
                <option value="School of Law" {{ ($ropaForm->business_function ?? '') == 'School of Law' ? 'selected' : '' }}>School of Law</option>
                <option value="University Hospital" {{ ($ropaForm->business_function ?? '') == 'University Hospital' ? 'selected' : '' }}>University Hospital</option>
                <option value="Directorate of Academic Affairs" {{ ($ropaForm->business_function ?? '') == 'Directorate of Academic Affairs' ? 'selected' : '' }}>Directorate of Academic Affairs</option>
                <option value="Human Resources Division" {{ ($ropaForm->business_function ?? '') == 'Human Resources Division' ? 'selected' : '' }}>Human Resources Division</option>
            </select>
        </div>
        <div class="col-12">
            <label class="form-label fw-bold">Name of Process / Project / Activity</label>
            <div class="multi-select-container">
                <div class="chips-container" style="display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                    @foreach(($ropaForm->process_names ?? []) as $tag)
                    <span class="tag-chip">{{ $tag }} <button type="button" onclick="this.parentElement.remove()">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="Type and press Enter to add (e.g., Admissions, Payroll, Research)">
                <input type="hidden" name="process_names" value="{{ json_encode($ropaForm->process_names ?? []) }}">
            </div>
            <small class="text-muted">Add all relevant processes or projects associated with this data processing activity.</small>
        </div>
        <div class="col-12">
            <label class="form-label fw-bold">Purpose of Processing <i class="help-tooltip fas fa-question-circle" data-bs-toggle="tooltip" title="Describe why the data is being collected or used."></i></label>
            <textarea name="purpose" class="form-control" rows="3" placeholder="e.g., Recruitment process for new faculty members, Pension administration for retired staff...">{{ old('purpose', $ropaForm->purpose) }}</textarea>
        </div>
        <div class="col-md-6">
            <label class="form-label fw-bold">Role Responsible for the Processing</label>
            <input type="text" name="role_responsible" class="form-control" value="{{ old('role_responsible', $ropaForm->role_responsible) }}" placeholder="e.g., Senior Admin Registrar, Data Protection Officer">
        </div>
    </div>
</div>
