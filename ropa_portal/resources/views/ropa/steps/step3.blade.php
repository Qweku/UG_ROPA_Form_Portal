<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-database fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Data Categories & Subjects</h5>
                <p class="mb-0 text-muted small">Define what data is collected, who it belongs to, and identify sensitive information.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-file-alt" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Record Types</h5>
                    </div>
                    @php
                        $categoriesRecords = [];
                        if ($submission->categories_records) {
                            $categoriesRecords = is_array($submission->categories_records)
                                ? $submission->categories_records
                                : (json_decode($submission->categories_records ?? '[]', true) ?? []);
                        }
                    @endphp
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="categories_records[]" value="Paper" id="rec-paper"
                               {{ in_array('Paper', $categoriesRecords) ? 'checked' : '' }}>
                        <label class="form-check-label" for="rec-paper">
                            <i class="fas fa-file-pdf me-1 text-danger"></i> Paper-based records
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="categories_records[]" value="Electronic" id="rec-electronic"
                               {{ in_array('Electronic', $categoriesRecords) ? 'checked' : '' }}>
                        <label class="form-check-label" for="rec-electronic">
                            <i class="fas fa-laptop me-1 text-primary"></i> Electronic records
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" name="categories_records[]" value="Other" id="rec-other"
                               {{ in_array('Other', $categoriesRecords) ? 'checked' : '' }}>
                        <label class="form-check-label" for="rec-other">
                            <i class="fas fa-box me-1 text-warning"></i> Other physical assets
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-users" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Data Subjects</h5>
                    </div>
                    <div class="multi-select-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @php
                                $dataSubjects = [];
                                if ($submission->data_subjects) {
                                    $dataSubjects = is_array($submission->data_subjects)
                                        ? $submission->data_subjects
                                        : (json_decode($submission->data_subjects, true) ?? []);
                                }
                            @endphp
                            @foreach($dataSubjects as $subject)
                            <span class="tag-chip">
                                <i class="fas fa-user me-1"></i>
                                <span>{{ $subject }}</span>
                                <button type="button" onclick="this.parentElement.remove(); updateDataSubjects();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            @endforeach
                        </div>
                        <input type="text" class="form-control" id="dataSubjectsInput"
                               placeholder="Type and press Enter (e.g., Employees, Students, Consultants)">
                        <input type="hidden" name="data_subjects" id="data_subjects_hidden" value="{{ json_encode($dataSubjects) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-database" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Personal Data</h5>
                    </div>
                    <div class="multi-select-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @php
                                $personalData = [];
                                if ($submission->personal_data_categories) {
                                    $personalData = is_array($submission->personal_data_categories)
                                        ? $submission->personal_data_categories
                                        : (json_decode($submission->personal_data_categories, true) ?? []);
                                }
                            @endphp
                            @foreach($personalData as $data)
                            <span class="tag-chip">
                                <i class="fas fa-database me-1"></i>
                                <span>{{ $data }}</span>
                                <button type="button" onclick="this.parentElement.remove(); updatePersonalData();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            @endforeach
                        </div>
                        <input type="text" class="form-control" id="personalDataInput"
                               placeholder="Type and press Enter e.g., Contact data, Medical data, IP addresses">
                        <input type="hidden" name="personal_data_categories" id="personal_data_hidden" value="{{ json_encode($personalData) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-heartbeat text-danger" style="color: #dc3545;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Special Category Data</h5>
                        <span class="badge bg-danger ms-2">Sensitive Information</span>
                    </div>
                    <label class="form-label fw-bold">Types of Documents Containing Special Category Data</label>
                    <textarea name="special_category_documents" class="form-control" rows="3"
                              placeholder="e.g., Medical reports, lab results, psychological assessments, biometric data...">{{ old('special_category_documents', $submission->special_category_documents) }}</textarea>
                    <small class="text-muted mt-2 d-block">
                        <i class="fas fa-shield-alt me-1"></i>
                        Special category data includes health information, biometric data, political opinions, religious beliefs, trade union membership, etc.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function updateDataSubjects() {
    const container = document.querySelector('#step3 .multi-select-container:first-child .chips-container');
    if (container) {
        const values = Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
        document.getElementById('data_subjects_hidden').value = JSON.stringify(values);
    }
}

function updatePersonalData() {
    const container = document.querySelector('#step3 .multi-select-container:last-child .chips-container');
    if (container) {
        const values = Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
        document.getElementById('personal_data_hidden').value = JSON.stringify(values);
    }
}

document.getElementById('dataSubjectsInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value) {
            const container = document.querySelector('#step3 .multi-select-container:first-child .chips-container');
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `<i class="fas fa-user me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); updateDataSubjects();"><i class="fas fa-times"></i></button>`;
            container.appendChild(chip);
            this.value = '';
            updateDataSubjects();
        }
    }
});

document.getElementById('personalDataInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value) {
            const container = document.querySelector('#step3 .multi-select-container:last-child .chips-container');
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `<i class="fas fa-database me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); updatePersonalData();"><i class="fas fa-times"></i></button>`;
            container.appendChild(chip);
            this.value = '';
            updatePersonalData();
        }
    }
});
</script>
@endpush
