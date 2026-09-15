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
        <div class="col-md-6">
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
                      <div class="multi-select-container" id="categoriesRecordsContainer">
                         <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                             @foreach($categoriesRecords as $record)
                             <span class="tag-chip">
                                 <i class="fas fa-file-alt me-1"></i>
                                 <span>{{ $record }}</span>
                                 <button type="button" onclick="this.parentElement.remove(); updateCategoriesRecords();">
                                     <i class="fas fa-times"></i>
                                 </button>
                             </span>
                             @endforeach
                         </div>
                         <label class="form-label fw-bold">List Categories of Records you Process</label>
                         <input type="text" class="form-control" id="categoriesRecordsInput"
                                placeholder="Type and press Enter (e.g., Paper, Electronic, Cloud backups)">
                         <input type="hidden" name="categories_records" id="categories_records_hidden" value="{{ json_encode($categoriesRecords) }}">
                      </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class=" h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-users" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Data Subjects</h5>
                    </div>
                    <div class="multi-select-container" id="dataSubjectsContainer">
                        <div class="chips-container mb-2">
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
                        <label class="form-label fw-bold">List Categories of Data Subjects (Individuals) you process</label>
                        <input type="text" class="form-control" id="dataSubjectsInput"
                               placeholder="Type and press Enter (e.g., Employees, Students, Consultants)"
                               autocomplete="off">
                        <div class="suggestions-dropdown" id="dataSubjectsSuggestions"></div>
                        <input type="hidden" name="data_subjects" id="data_subjects_hidden" value="{{ json_encode($dataSubjects) }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-database" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Personal Data</h5>
                    </div>
                    <div class="multi-select-container" id="personalDataContainer">
                        <div class="chips-container mb-2">
                            @php
                                $personalData = [];
                                if ($submission->personal_data_categories) {
                                    $personalData = is_array($submission->personal_data_categories)
                                        ? $submission->personal_data_categories
                                        : (json_decode($submission->personal_data_categories, true) ?? []);
                                }
                                $internalSharingCategories = implode(', ', $personalData);
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
                        <label class="form-label fw-bold">List Categories of Personal Data you Process</label>
                        <input type="text" class="form-control" id="personalDataInput"
                               placeholder="Type and press Enter e.g., Contact data, Medical data, IP addresses"
                               autocomplete="off">
                        <div class="suggestions-dropdown" id="personalDataSuggestions"></div>
                        <input type="hidden" name="personal_data_categories" id="personal_data_hidden" value="{{ json_encode($personalData) }}">
                        <input type="hidden" name="internal_sharing_categories" value="{{ $internalSharingCategories }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-heartbeat text-danger" style="color: #dc3545;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Special Category Data</h5>
                        <span class="badge bg-danger ms-2">Sensitive Information</span>
                    </div>
                    @php
                        $specialCategories = is_array($submission->special_category_documents)
                            ? $submission->special_category_documents
                            : (json_decode($submission->special_category_documents ?? '[]', true) ?: []);
                    @endphp
                    <div class="multi-select-container" id="specialCategoryContainer">
                        <div class="chips-container mb-2">
                            @foreach($specialCategories as $cat)
                            <span class="tag-chip">
                                <i class="fas fa-shield-alt me-1"></i>
                                <span>{{ $cat }}</span>
                                <button type="button" onclick="this.parentElement.remove(); updateSpecialCategories();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            @endforeach
                        </div>
                        <label class="form-label fw-bold">Types of Documents Containing Special Category Data</label>
                        <input type="text" class="form-control" id="specialCategoryInput"
                               placeholder="Type and press Enter (e.g., Medical reports, biometric data...)"
                               autocomplete="off">
                        <div class="suggestions-dropdown" id="specialCategorySuggestions"></div>
                        <input type="hidden" name="special_category_documents" id="special_category_hidden" value="{{ json_encode($specialCategories) }}">
                    </div>
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
    const container = document.querySelector('#dataSubjectsContainer .chips-container');
    if (container) {
        const values = Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
        document.getElementById('data_subjects_hidden').value = JSON.stringify(values);
    }
}

function updatePersonalData() {
    const container = document.querySelector('#personalDataContainer .chips-container');
    if (container) {
        const values = Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
        document.getElementById('personal_data_hidden').value = JSON.stringify(values);
    }
}

function updateCategoriesRecords() {
    const container = document.querySelector('#categoriesRecordsContainer .chips-container');
    if (container) {
        const values = Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
        document.getElementById('categories_records_hidden').value = JSON.stringify(values);
    }
}

function updateSpecialCategories() {
    const container = document.querySelector('#specialCategoryContainer .chips-container');
    if (container) {
        const values = Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
        document.getElementById('special_category_hidden').value = JSON.stringify(values);
    }
}

(function() {
    const input = document.getElementById('specialCategoryInput');
    const suggestionsBox = document.getElementById('specialCategorySuggestions');
    const container = document.querySelector('#specialCategoryContainer .chips-container');
    const hiddenInput = document.getElementById('special_category_hidden');

    const PREDEFINED = [
        'Medical certificates',
        'Grade vetting details',
        'Disability data relating to a person',
        'Extenuating circumstances documentation',
        'Health data including but not limited to sick leave records',
        'Visa/immigration data',
        'Data relating to a child (student under 18 for example)',
        'Accident data',
        'Personal specific trade union data'
    ];

    function getCurrentValues() {
        if (!container) return [];
        return Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
    }

    function updateHidden() {
        const values = getCurrentValues();
        hiddenInput.value = JSON.stringify(values);
    }

    function renderSuggestions(filter = '') {
        if (!suggestionsBox) return;
        const current = getCurrentValues();
        const filtered = PREDEFINED.filter(item =>
            item.toLowerCase().includes(filter.toLowerCase()) && !current.includes(item)
        );

        let html = '';

        if (filtered.length === 0 && filter) {
            html = `<div class="suggestion-item custom" data-value="${escapeHtml(filter)}">Add "${escapeHtml(filter)}"</div>`;
        } else if (filtered.length > 0) {
            filtered.forEach(item => {
                html += `<div class="suggestion-item" data-value="${escapeHtml(item)}">${escapeHtml(item)}</div>`;
            });
        }

        suggestionsBox.innerHTML = html;

        if (html) {
            suggestionsBox.classList.add('active');
            suggestionsBox.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    addChip(this.getAttribute('data-value'));
                    input.value = '';
                    input.focus();
                    renderSuggestions('');
                });
            });
        } else {
            suggestionsBox.classList.remove('active');
        }
    }

    function addChip(value) {
        if (!container) return;
        const trimmed = value.trim();
        if (!trimmed) return;
        if (getCurrentValues().includes(trimmed)) return;

        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.innerHTML = `<i class="fas fa-shield-alt me-1"></i><span>${escapeHtml(trimmed)}</span><button type="button" onclick="this.parentElement.remove(); updateSpecialCategories();"><i class="fas fa-times"></i></button>`;
        container.appendChild(chip);
        updateHidden();
    }

    if (input && suggestionsBox) {
        input.addEventListener('focus', function() {
            renderSuggestions(this.value.trim());
        });

        input.addEventListener('input', function() {
            renderSuggestions(this.value.trim());
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = this.value.trim();
                if (value) {
                    addChip(value);
                    this.value = '';
                    renderSuggestions('');
                }
            } else if (e.key === 'Backspace' && !this.value && getCurrentValues().length > 0) {
                const chips = container.querySelectorAll('.tag-chip');
                if (chips.length) {
                    chips[chips.length - 1].remove();
                    updateHidden();
                    renderSuggestions('');
                }
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (!input || !suggestionsBox) return;
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.remove('active');
        }
    });
})();

(function() {
    const input = document.getElementById('dataSubjectsInput');
    const suggestionsBox = document.getElementById('dataSubjectsSuggestions');
    const container = document.querySelector('#dataSubjectsContainer .chips-container');
    const hiddenInput = document.getElementById('data_subjects_hidden');

    const PREDEFINED = [
        'Employees',
        'PhD students',
        'Masters students',
        'Current students',
        'Former students',
        'Members of the public',
        'Persons that attended events on campus',
        'Research project collaborators',
        'Research project participants',
        'Agency workers',
        'Contractors'
    ];

    function getCurrentValues() {
        if (!container) return [];
        return Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
    }

    function updateHidden() {
        const values = getCurrentValues();
        hiddenInput.value = JSON.stringify(values);
    }

    function renderSuggestions(filter = '') {
        if (!suggestionsBox) return;
        const current = getCurrentValues();
        const filtered = PREDEFINED.filter(item =>
            item.toLowerCase().includes(filter.toLowerCase()) && !current.includes(item)
        );

        let html = '';

        if (filtered.length === 0 && filter) {
            html = `<div class="suggestion-item custom" data-value="${escapeHtml(filter)}">Add "${escapeHtml(filter)}"</div>`;
        } else if (filtered.length > 0) {
            filtered.forEach(item => {
                html += `<div class="suggestion-item" data-value="${escapeHtml(item)}">${escapeHtml(item)}</div>`;
            });
        }

        suggestionsBox.innerHTML = html;

        if (html) {
            suggestionsBox.classList.add('active');
            suggestionsBox.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    addChip(this.getAttribute('data-value'));
                    input.value = '';
                    input.focus();
                    renderSuggestions('');
                });
            });
        } else {
            suggestionsBox.classList.remove('active');
        }
    }

    function addChip(value) {
        if (!container) return;
        const trimmed = value.trim();
        if (!trimmed) return;
        if (getCurrentValues().includes(trimmed)) return;

        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.innerHTML = `<i class="fas fa-user me-1"></i><span>${escapeHtml(trimmed)}</span><button type="button" onclick="this.parentElement.remove(); updateDataSubjects();"><i class="fas fa-times"></i></button>`;
        container.appendChild(chip);
        updateHidden();
    }

    if (input && suggestionsBox) {
        input.addEventListener('focus', function() {
            renderSuggestions(this.value.trim());
        });

        input.addEventListener('input', function() {
            renderSuggestions(this.value.trim());
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = this.value.trim();
                if (value) {
                    addChip(value);
                    this.value = '';
                    renderSuggestions('');
                }
            } else if (e.key === 'Backspace' && !this.value && getCurrentValues().length > 0) {
                const chips = container.querySelectorAll('.tag-chip');
                if (chips.length) {
                    chips[chips.length - 1].remove();
                    updateHidden();
                    renderSuggestions('');
                }
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (!input || !suggestionsBox) return;
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.remove('active');
        }
    });
})();

(function() {
    const input = document.getElementById('personalDataInput');
    const suggestionsBox = document.getElementById('personalDataSuggestions');
    const container = document.querySelector('#personalDataContainer .chips-container');
    const hiddenInput = document.getElementById('personal_data_hidden');

    const PREDEFINED = [
        'Full name',
        'Home address',
        'Email address',
        'Phone number',
        'Date of birth',
        'National identification number (e.g., PPS number)',
        'Passport number',
        'Driver\'s license number',
        'Person\'s photo',
        'Person\'s voice recording',
        'Person\'s video',
        'IP address',
        'Cookie identifiers',
        'Device identifiers (e.g., IMEI, MAC address)',
    ];

    function getCurrentValues() {
        if (!container) return [];
        return Array.from(container.querySelectorAll('.tag-chip span'))
            .map(span => span.textContent.trim());
    }

    function updateHidden() {
        const values = getCurrentValues();
        hiddenInput.value = JSON.stringify(values);
    }

    function renderSuggestions(filter = '') {
        if (!suggestionsBox) return;
        const current = getCurrentValues();
        const filtered = PREDEFINED.filter(item =>
            item.toLowerCase().includes(filter.toLowerCase()) && !current.includes(item)
        );

        let html = '';

        if (filtered.length === 0 && filter) {
            html = `<div class="suggestion-item custom" data-value="${escapeHtml(filter)}">Add "${escapeHtml(filter)}"</div>`;
        } else if (filtered.length > 0) {
            filtered.forEach(item => {
                html += `<div class="suggestion-item" data-value="${escapeHtml(item)}">${escapeHtml(item)}</div>`;
            });
        }

        suggestionsBox.innerHTML = html;

        if (html) {
            suggestionsBox.classList.add('active');
            suggestionsBox.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    addChip(this.getAttribute('data-value'));
                    input.value = '';
                    input.focus();
                    renderSuggestions('');
                });
            });
        } else {
            suggestionsBox.classList.remove('active');
        }
    }

    function addChip(value) {
        if (!container) return;
        const trimmed = value.trim();
        if (!trimmed) return;
        if (getCurrentValues().includes(trimmed)) return;

        const chip = document.createElement('span');
        chip.className = 'tag-chip';
        chip.innerHTML = `<i class="fas fa-database me-1"></i><span>${escapeHtml(trimmed)}</span><button type="button" onclick="this.parentElement.remove(); updatePersonalData();"><i class="fas fa-times"></i></button>`;
        container.appendChild(chip);
        updateHidden();
    }

    if (input) {
        input.addEventListener('focus', function() {
            renderSuggestions(this.value.trim());
        });

        input.addEventListener('input', function() {
            renderSuggestions(this.value.trim());
        });

        input.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const value = this.value.trim();
                if (value) {
                    addChip(value);
                    this.value = '';
                    renderSuggestions('');
                }
            } else if (e.key === 'Backspace' && !this.value && getCurrentValues().length > 0) {
                const chips = container.querySelectorAll('.tag-chip');
                if (chips.length) {
                    chips[chips.length - 1].remove();
                    updateHidden();
                    renderSuggestions('');
                }
            }
        });
    }

    document.addEventListener('click', function(e) {
        if (!input || !suggestionsBox) return;
        if (!input.contains(e.target) && !suggestionsBox.contains(e.target)) {
            suggestionsBox.innerHTML = '';
            suggestionsBox.classList.remove('active');
        }
    });
})();

document.getElementById('categoriesRecordsInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value) {
            const container = document.querySelector('#categoriesRecordsContainer .chips-container');
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `<i class="fas fa-file-alt me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); updateCategoriesRecords();"><i class="fas fa-times"></i></button>`;
            container.appendChild(chip);
            this.value = '';
            updateCategoriesRecords();
        }
    }
});

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>
@endpush

