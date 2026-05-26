<div class="form-section">
    <!-- Welcome Header -->
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-info-circle fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Basic Information</h5>
                <p class="mb-0 text-muted small">Capture ownership and identification of the processing activity. Fields marked with <span class="text-danger">*</span> are required.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-calendar-alt" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Creation Details</h5>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Date Created <span class="text-danger">*</span></label>
                        <input type="date" name="date_created" class="form-control" value="{{ date('Y-m-d') }}" readonly>
                        <small class="text-muted">Auto-filled with today's date</small>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Personnel ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent"><i class="fas fa-id-card" style="color: #b69964;"></i></span>
                                <input type="text" name="personnel_id" class="form-control"
                                       value="{{ old('personnel_id', $ropaForm->personnel_id ?? Auth::user()->personnel_id ?? '') }}"
                                       placeholder="e.g., 99999">
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Role Responsible</label>
                            <div class="input-group">
                                <span class="input-group-text bg-transparent"><i class="fas fa-user-tie" style="color: #b69964;"></i></span>
                                <input type="text" name="role_responsible" class="form-control"
                                       value="{{ old('role_responsible', $ropaForm->role_responsible) }}"
                                       placeholder="e.g., Senior Admin Registrar">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-user" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Personal Information</h5>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Surname</label>
                            <input type="text" name="surname" class="form-control"
                                   value="{{ old('surname', $ropaForm->surname ?? Auth::user()->surname ?? '') }}"
                                   placeholder="Last name">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Firstname</label>
                            <input type="text" name="firstname" class="form-control"
                                   value="{{ old('firstname', $ropaForm->firstname ?? Auth::user()->firstname ?? '') }}"
                                   placeholder="First name">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Business Function
                            <i class="fas fa-question-circle text-muted ms-1" data-bs-toggle="tooltip"
                               title="College/School/Department/Unit"></i>
                        </label>
                        <select name="business_function" class="form-select select2">
                            <option value="">-- Select Business Function --</option>
                            <option value="College of Health Sciences" {{ ($ropaForm->business_function ?? '') == 'College of Health Sciences' ? 'selected' : '' }}>
                                🏥 College of Health Sciences
                            </option>
                            <option value="College of Humanities" {{ ($ropaForm->business_function ?? '') == 'College of Humanities' ? 'selected' : '' }}>
                                📚 College of Humanities
                            </option>
                            <option value="College of Basic and Applied Sciences" {{ ($ropaForm->business_function ?? '') == 'College of Basic and Applied Sciences' ? 'selected' : '' }}>
                                🔬 College of Basic and Applied Sciences
                            </option>
                            <option value="School of Law" {{ ($ropaForm->business_function ?? '') == 'School of Law' ? 'selected' : '' }}>
                                ⚖️ School of Law
                            </option>
                            <option value="University Hospital" {{ ($ropaForm->business_function ?? '') == 'University Hospital' ? 'selected' : '' }}>
                                🏥 University Hospital
                            </option>
                            <option value="Directorate of Academic Affairs" {{ ($ropaForm->business_function ?? '') == 'Directorate of Academic Affairs' ? 'selected' : '' }}>
                                📖 Directorate of Academic Affairs
                            </option>
                            <option value="Human Resources Division" {{ ($ropaForm->business_function ?? '') == 'Human Resources Division' ? 'selected' : '' }}>
                                👥 Human Resources Division
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-tags" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Process & Purpose</h5>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Name of Process / Project / Activity</label>
                        <div class="multi-select-container">
                            <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                                @php
                                    $processes = [];
                                    if ($ropaForm->process_names) {
                                        $processes = is_array($ropaForm->process_names)
                                            ? $ropaForm->process_names
                                            : (json_decode($ropaForm->process_names, true) ?? []);
                                    }
                                @endphp
                                @foreach($processes as $tag)
                                <span class="tag-chip">
                                    <i class="fas fa-tag me-1"></i>
                                    <span>{{ $tag }}</span>
                                    <button type="button" onclick="this.parentElement.remove(); updateProcessNames();">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </span>
                                @endforeach
                            </div>
                            <div class="input-group">
                                <input type="text" class="form-control" id="processInput"
                                       placeholder="Type and press Enter to add (e.g., Admissions, Payroll, Research)">
                                <button class="btn btn-outline-primary" type="button" onclick="addProcess()">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                            <input type="hidden" name="process_names" id="process_names_hidden" value="{{ json_encode($processes) }}">
                            <small class="text-muted mt-2 d-block">
                                <i class="fas fa-info-circle"></i> Add all relevant processes or projects
                            </small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Purpose of Processing
                            <i class="fas fa-question-circle text-muted ms-1" data-bs-toggle="tooltip"
                               title="Describe why the data is being collected or used"></i>
                        </label>
                        <textarea name="purpose" class="form-control" rows="4"
                                  placeholder="e.g., Recruitment process for new faculty members, Pension administration for retired staff...">{{ old('purpose', $ropaForm->purpose) }}</textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateProcessNames() {
    const chips = document.querySelectorAll('#step1 .tag-chip span');
    const values = Array.from(chips).map(chip => chip.textContent.trim());
    document.getElementById('process_names_hidden').value = JSON.stringify(values);
}

function addProcess() {
    const input = document.getElementById('processInput');
    const value = input.value.trim();
    if (!value) return;

    const container = document.querySelector('#step1 .chips-container');
    const chip = document.createElement('span');
    chip.className = 'tag-chip';
    chip.innerHTML = `
        <i class="fas fa-tag me-1"></i>
        <span>${escapeHtml(value)}</span>
        <button type="button" onclick="this.parentElement.remove(); updateProcessNames();">
            <i class="fas fa-times"></i>
        </button>
    `;
    container.appendChild(chip);
    input.value = '';
    updateProcessNames();
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

document.getElementById('processInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        addProcess();
    }
});
</script>
@endpush
