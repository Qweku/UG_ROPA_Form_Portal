
<div class="form-section">
    <div class="row g-4">
        <div class="col-md-4">
            <label class="form-label fw-bold">Categories of Records Processed</label>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="categories_records[]" value="Paper" id="rec-paper" {{ in_array('Paper', $ropaForm->categories_records ?? []) ? 'checked' : '' }}>
                <label class="form-check-label" for="rec-paper">📄 Paper-based records</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="categories_records[]" value="Electronic" id="rec-electronic" {{ in_array('Electronic', $ropaForm->categories_records ?? []) ? 'checked' : '' }}>
                <label class="form-check-label" for="rec-electronic">💻 Electronic records</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="categories_records[]" value="Other" id="rec-other" {{ in_array('Other', $ropaForm->categories_records ?? []) ? 'checked' : '' }}>
                <label class="form-check-label" for="rec-other">📦 Other (physical assets, etc.)</label>
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Categories of Data Subjects</label>
            <div class="multi-select-container">
                <div class="chips-container">
                    @foreach(($ropaForm->data_subjects ?? []) as $subject)
                    <span class="tag-chip">{{ $subject }} <button type="button">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="Type and press Enter (e.g., Employees, Students, Consultants)">
                <input type="hidden" name="data_subjects" value="{{ json_encode($ropaForm->data_subjects ?? []) }}">
            </div>
        </div>

        <div class="col-md-4">
            <label class="form-label fw-bold">Categories of Personal Data <i class="help-tooltip fas fa-question-circle" data-bs-toggle="tooltip" title="What types of personal information are collected?"></i></label>
            <div class="multi-select-container">
                <div class="chips-container">
                    @foreach(($ropaForm->personal_data_categories ?? []) as $data)
                    <span class="tag-chip">{{ $data }} <button type="button">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="e.g., Contact data, Medical data, IP addresses">
                <input type="hidden" name="personal_data_categories" value="{{ json_encode($ropaForm->personal_data_categories ?? []) }}">
            </div>
        </div>

        <div class="col-12">
            <label class="form-label fw-bold">Types of Documents Containing Special Category Data <span class="badge bg-danger">Sensitive Data</span></label>
            <textarea name="special_category_documents" class="form-control" rows="3" placeholder="e.g., Medical reports, lab results, psychological assessments...">{{ old('special_category_documents', $ropaForm->special_category_documents) }}</textarea>
            <small class="text-muted">Special category data includes health information, biometric data, political opinions, religious beliefs, etc.</small>
        </div>
    </div>
</div>
