<div class="form-section">
    <!-- Welcome Header -->
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-info-circle fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">Basic Information</h5>
                <p class="mb-0 text-muted small">
                    <?php if($basicInfoLocked ?? false): ?>
                        Add the next sub-process under <strong><?php echo e($parentForm->main_process_name); ?></strong>. Fields marked with <span class="text-danger">*</span> are required.
                    <?php else: ?>
                        Capture ownership and identification of the processing activity. Fields marked with <span class="text-danger">*</span> are required.
                    <?php endif; ?>
                </p>
            </div>
        </div>
    </div>

    <div class="row g-4">

        
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-user" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Personal Information</h5>
                    </div>
                    <div class="row">
                        <?php if($basicInfoLocked ?? false): ?>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Personnel ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-id-card" style="color: #b69964;"></i></span>
                                    <input type="text" class="form-control" value="<?php echo e($parentForm->personnel_id ?? ''); ?>" disabled>
                                    <input type="hidden" name="personnel_id" value="<?php echo e($parentForm->personnel_id); ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Role Responsible</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-user-tie" style="color: #b69964;"></i></span>
                                    <input type="text" class="form-control" value="<?php echo e($parentForm->role_responsible ?? ''); ?>" disabled>
                                    <input type="hidden" name="role_responsible" value="<?php echo e($parentForm->role_responsible); ?>">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Surname <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="<?php echo e($parentForm->surname ?? ''); ?>" disabled>
                                <input type="hidden" name="surname" value="<?php echo e($parentForm->surname); ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Firstname <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" value="<?php echo e($parentForm->firstname ?? ''); ?>" disabled>
                                <input type="hidden" name="firstname" value="<?php echo e($parentForm->firstname); ?>">
                            </div>
                        <?php else: ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Personnel ID</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-id-card" style="color: #b69964;"></i></span>
                                    <input type="text" name="personnel_id" class="form-control"
                                           value="<?php echo e(old('personnel_id', $parentForm->personnel_id ?? Auth::user()->personnel_id ?? '')); ?>"
                                           placeholder="e.g., 99999">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Role Responsible</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent"><i class="fas fa-user-tie" style="color: #b69964;"></i></span>
                                    <input type="text" name="role_responsible" class="form-control"
                                           value="<?php echo e(old('role_responsible', $parentForm->role_responsible ?? '')); ?>"
                                           placeholder="e.g., Senior Admin Registrar">
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Surname <span class="text-danger">*</span></label>
                                <input type="text" name="surname" class="form-control"
                                       value="<?php echo e(old('surname', $parentForm->surname ?? Auth::user()->surname ?? '')); ?>"
                                       placeholder="Last name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Firstname <span class="text-danger">*</span></label>
                                <input type="text" name="firstname" class="form-control"
                                       value="<?php echo e(old('firstname', $parentForm->firstname ?? Auth::user()->firstname ?? '')); ?>"
                                       placeholder="First name" required>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <?php if($basicInfoLocked ?? false): ?>
            
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-lock" style="color: #153d6f;"></i>
                            </div>
                            <h5 class="card-title mb-0" style="color: #153d6f;">Process Identity</h5>
                            <span class="badge bg-secondary ms-auto">Locked</span>
                        </div>
                        <p class="text-muted small mb-3">
                            College, Business Function, and Main Process Name were set when the first sub-process was created and apply to this whole RoPA.
                        </p>
                        <div class="row">
                            <input type="hidden" name="has_sub_processes" value="<?php echo e($parentForm->has_sub_processes ? 1 : 0); ?>">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">College</label>
                                <input type="text" class="form-control" value="<?php echo e($parentForm->college->name ?? ''); ?>" disabled>
                                <input type="hidden" name="college_id" value="<?php echo e($parentForm->college_id); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Business Function (School/Dept)</label>
                                <input type="text" class="form-control" value="<?php echo e($parentForm->business_function); ?>" disabled>
                                <input type="hidden" name="business_function" value="<?php echo e($parentForm->business_function); ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Main Process Name</label>
                                <input type="text" class="form-control" value="<?php echo e($parentForm->main_process_name); ?>" disabled>
                                <input type="hidden" name="main_process_name" value="<?php echo e($parentForm->main_process_name); ?>">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-sitemap" style="color: #153d6f;"></i>
                            </div>
                            <h5 class="card-title mb-0" style="color: #153d6f;">Sub-Process</h5>
                        </div>
                        <label class="form-label fw-bold">Sub-Process Name <span class="text-danger">*</span></label>
                        <input type="text" name="sub_process_name" class="form-control"
                               value="<?php echo e(old('sub_process_name', $submission->sub_process_name ?? '')); ?>"
                               placeholder="Enter the current sub-process name" required autofocus>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="col-md-6">
                <div class="h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-university" style="color: #153d6f;"></i>
                            </div>
                            <h5 class="card-title mb-0" style="color: #153d6f;">College & Business Function</h5>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">College <span class="text-danger">*</span></label>
                            <select name="college_id" id="collegeSelect" class="form-select" required>
                                <option value="">Select College</option>
                                <?php $__currentLoopData = $colleges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $college): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($college->id); ?>" <?php echo e(old('college_id', $parentForm->college_id ?? '') == $college->id ? 'selected' : ''); ?>>
                                        <?php echo e($college->name); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="mb-1 position-relative">
                            <label class="form-label fw-bold">
                                Business Function (School/Dept) <span class="text-danger">*</span>
                                <i class="fas fa-question-circle text-muted ms-1" data-bs-toggle="tooltip"
                                   title="Select a college first, then type to search or add a new school/department"></i>
                            </label>
                            <input type="text"
                                   name="business_function"
                                   id="businessFunctionInput"
                                   class="form-control"
                                   autocomplete="off"
                                   value="<?php echo e(old('business_function', $parentForm->business_function ?? '')); ?>"
                                   placeholder="Select a college first, then type to search or add">
                            <div id="businessFunctionSuggestions" class="list-group position-absolute w-100 shadow-sm" style="z-index: 1050; display: none; max-height: 220px; overflow-y: auto;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-tags" style="color: #153d6f;"></i>
                            </div>
                            <h5 class="card-title mb-0" style="color: #153d6f;">Main Process</h5>
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-bold">Main Process Name <span class="text-danger">*</span></label>
                            <input type="text" name="main_process_name" class="form-control"
                                   value="<?php echo e(old('main_process_name', $parentForm->main_process_name ?? '')); ?>"
                                   placeholder="e.g., Admissions, Payroll, Research" required>
                        </div>
                        <div class="form-check mb-1">
                            <input type="checkbox" name="has_sub_processes" id="hasSubProcesses" class="form-check-input" value="1"
                                   <?php echo e(old('has_sub_processes', $submission->sub_process_name ? 1 : 0) ? 'checked' : ''); ?>>
                            <label class="form-check-label fw-bold" for="hasSubProcesses">This process has sub-processes</label>
                        </div>
                        <small class="text-muted d-block mb-3">
                            <i class="fas fa-info-circle"></i> This choice applies to the whole process and cannot be changed after this first sub-process is saved.
                        </small>
                        <div id="subProcessContainer" style="display: <?php echo e(old('has_sub_processes', $submission->sub_process_name ? 1 : 0) ? 'block' : 'none'); ?>">
                            <label class="form-label fw-bold">Sub-Process Name</label>
                            <input type="text" name="sub_process_name" class="form-control"
                                   value="<?php echo e(old('sub_process_name', $submission->sub_process_name ?? '')); ?>"
                                   placeholder="Enter the current sub-process name">
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-file-alt" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Purpose</h5>
                    </div>
                    <label class="form-label fw-bold">
                        Purpose of Processing
                        <i class="fas fa-question-circle text-muted ms-1" data-bs-toggle="tooltip"
                           title="Describe why the data is being collected or used"></i>
                    </label>
                    <textarea name="purpose" class="form-control" rows="4"
                              placeholder="e.g., Recruitment process for new faculty members, Pension administration for retired staff..."><?php echo e(old('purpose', $submission->purpose ?? '')); ?></textarea>
                </div>
            </div>
        </div>

    </div>
</div>

<?php if (! ($basicInfoLocked ?? false)): ?>
<?php $__env->startPush('scripts'); ?>
<script>
    var routeSchoolsIndex = '/ropa/api/schools/';
    var routeSchoolsStore = '/ropa/api/schools';

    document.addEventListener('DOMContentLoaded', function() {
        var collegeSelect = document.getElementById('collegeSelect');
        var bfInput = document.getElementById('businessFunctionInput');
        var bfSuggestions = document.getElementById('businessFunctionSuggestions');
        var csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

        var currentSchools = [];
        var schoolsLoadedForCollege = null;
        var fetchTimer = null;

        function hideSuggestions() {
            bfSuggestions.style.display = 'none';
            bfSuggestions.innerHTML = '';
        }

        function renderSuggestions(items, query) {
            bfSuggestions.innerHTML = '';

            var filtered = items.filter(function(name) {
                return name.toLowerCase().includes((query || '').toLowerCase());
            });

            if (filtered.length === 0) {
                hideSuggestions();
                return;
            }

            filtered.forEach(function(name) {
                var item = document.createElement('button');
                item.type = 'button';
                item.className = 'list-group-item list-group-item-action';
                item.textContent = name;
                item.addEventListener('mousedown', function(e) {
                    e.preventDefault();
                    bfInput.value = name;
                    hideSuggestions();
                });
                bfSuggestions.appendChild(item);
            });

            bfSuggestions.style.display = 'block';
        }

        function fetchSchools(collegeId, callback) {
            if (!collegeId) {
                callback([]);
                return;
            }
            var url = routeSchoolsIndex + collegeId;
            fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'same-origin'
            })
                .then(function(res) {
                    if (!res.ok) {
                        console.error('GET ' + url + ' returned ' + res.status);
                        callback([]);
                        return null;
                    }
                    return res.json();
                })
                .then(function(data) {
                    if (data === null) return;
                    callback(Array.isArray(data) ? data : []);
                })
                .catch(function(err) {
                    console.error('Failed to load schools for college ' + collegeId + ':', err);
                    callback([]);
                });
        }

        function loadSchoolsForCurrentCollege(then) {
            var collegeId = collegeSelect ? collegeSelect.value : null;
            fetchSchools(collegeId, function(schools) {
                currentSchools = schools;
                schoolsLoadedForCollege = collegeId;
                if (then) then();
            });
        }

        if (collegeSelect && collegeSelect.value) {
            loadSchoolsForCurrentCollege();
        }

        if (collegeSelect) {
            collegeSelect.addEventListener('change', function() {
                bfInput.value = '';
                hideSuggestions();
                currentSchools = [];
                schoolsLoadedForCollege = null;
                loadSchoolsForCurrentCollege();
            });
        }

        bfInput.addEventListener('focus', function() {
            if (!collegeSelect || !collegeSelect.value) {
                return;
            }
            loadSchoolsForCurrentCollege(function() {
                renderSuggestions(currentSchools, bfInput.value);
            });
        });

        bfInput.addEventListener('input', function() {
            if (!collegeSelect || !collegeSelect.value) {
                hideSuggestions();
                return;
            }

            if (currentSchools.length > 0 || schoolsLoadedForCollege === collegeSelect.value) {
                renderSuggestions(currentSchools, bfInput.value);
                return;
            }

            clearTimeout(fetchTimer);
            fetchTimer = setTimeout(function() {
                loadSchoolsForCurrentCollege(function() {
                    renderSuggestions(currentSchools, bfInput.value);
                });
            }, 150);
        });

        bfInput.addEventListener('blur', function() {
            setTimeout(function() {
                var collegeId = collegeSelect ? collegeSelect.value : null;
                var typed = bfInput.value.trim();

                hideSuggestions();

                if (!typed || !collegeId) {
                    return;
                }

                var alreadyExists = currentSchools.some(function(name) {
                    return name.toLowerCase() === typed.toLowerCase();
                });

                if (alreadyExists) {
                    return;
                }

                fetch(routeSchoolsStore, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin',
                    body: new URLSearchParams({ college_id: collegeId, name: typed })
                })
                    .then(function(res) {
                        if (!res.ok) {
                            return res.text().then(function(body) {
                                console.error('POST ' + routeSchoolsStore + ' returned ' + res.status + ':', body);
                                throw new Error('Failed to add school (status ' + res.status + ')');
                            });
                        }
                        return res.json();
                    })
                    .then(function() {
                        currentSchools.push(typed);
                    })
                    .catch(function(err) {
                        console.error(err);
                        alert('Failed to save school name. Please try again.');
                    });
            }, 150);
        });

        document.addEventListener('click', function(e) {
            if (!bfSuggestions.contains(e.target) && e.target !== bfInput) {
                hideSuggestions();
            }
        });

        var hasSubProcesses = document.getElementById('hasSubProcesses');
        var subProcessContainer = document.getElementById('subProcessContainer');
        if (hasSubProcesses && subProcessContainer) {
            hasSubProcesses.addEventListener('change', function() {
                var subInput = subProcessContainer.querySelector('input');
                if (this.checked) {
                    subProcessContainer.style.display = 'block';
                    if (subInput) subInput.setAttribute('required', true);
                } else {
                    subProcessContainer.style.display = 'none';
                    if (subInput) {
                        subInput.value = '';
                        subInput.removeAttribute('required');
                    }
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>
<?php endif; ?>
<?php /**PATH C:\Users\Clement Aryee\Documents\UG Projects\UG_ROPA_Form_Portal\ropa_portal\resources\views/ropa/steps/step1.blade.php ENDPATH**/ ?>