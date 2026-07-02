@extends('layouts.app')

@section('title', 'Submission #' . $submission->id . ' - ' . ($submission->sub_process_name ?? $parentForm->main_process_name ?? 'Main Process'))

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('ropa.index') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active">Submission #{{ $submission->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i> Print / Save as PDF
                    </button>
                    <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        <i class="fas fa-trash-alt me-2"></i> Delete
                    </button>
                    <a href="{{ route('ropa.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4" data-aos="fade-up" data-aos-delay="100">
        <div class="col-12">
            <div class="alert alert-{{ $submission->status == 'completed' ? 'success' : 'warning' }} border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-{{ $submission->status == 'completed' ? 'check-circle' : 'clock' }} fa-2x me-3" style="color: {{ $submission->status == 'completed' ? '#28a745' : '#ffc107' }};"></i>
                        <div>
                            <h5 class="mb-0">{{ $submission->status == 'completed' ? 'Submitted' : 'In Progress' }}</h5>
                            <p class="mb-0 text-muted small">
                                @if($submission->completed_at)
                                Completed on: {{ $submission->completed_at->format('F d, Y \a\t H:i:s') }}
                                @else
                                Last updated: {{ $submission->updated_at->diffForHumans() }}
                                @endif
                            </p>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-{{ $submission->status == 'completed' ? 'success' : 'warning' }} px-3 py-2">
                            {{ strtoupper($submission->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submission Details -->
    <div class="row">
        <div class="col-lg-3 mb-4" data-aos="fade-right">
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-3">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-3 mb-2">
                            <i class="fas fa-file-alt fa-2x" style="color: #153d6f;"></i>
                        </div>
                        <h6 class="mb-1">Submission #{{ $submission->id }}</h6>
                        <small class="text-muted">{{ $submission->sub_process_name ?? 'Main Process' }}</small>
                        <p class="small text-muted mb-0 mt-1">{{ $parentForm->main_process_name }}</p>
                    </div>
                    <hr>
                    <!-- Quick Navigation -->
                    <div class="mb-3">
                        <label class="fw-bold small text-muted mb-2">QUICK NAVIGATION</label>
                        <div class="list-group list-group-flush">
                            <a href="#process-identity" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-signature me-2" style="color: #b69964;"></i> Process Identity
                            </a>
                            <a href="#basic-info" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-info-circle me-2" style="color: #b69964;"></i> Basic Information
                            </a>
                            <a href="#joint-controllers" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-users me-2" style="color: #b69964;"></i> Joint Controllers
                            </a>
                            <a href="#data-categories" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-database me-2" style="color: #b69964;"></i> Data Categories
                            </a>
                            <a href="#data-sharing" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-share-alt me-2" style="color: #b69964;"></i> Data Sharing
                            </a>
                            <a href="#data-source" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-cloud-download-alt me-2" style="color: #b69964;"></i> Data Source
                            </a>
                            <a href="#legal-basis" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-gavel me-2" style="color: #b69964;"></i> Legal Basis
                            </a>
                            <a href="#security" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-shield-alt me-2" style="color: #b69964;"></i> Security Measures
                            </a>
                            <a href="#external-recipients" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-paper-plane me-2" style="color: #b69964;"></i> External Recipients
                            </a>
                            <a href="#transfers" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-globe me-2" style="color: #b69964;"></i> International Transfers
                            </a>
                            <a href="#automated-decisions" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-robot me-2" style="color: #b69964;"></i> Automated Decisions
                            </a>
                            <a href="#consent" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-check-square me-2" style="color: #b69964;"></i> Consent &amp; Location
                            </a>
                            <a href="#dpia" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-clipboard-check me-2" style="color: #b69964;"></i> DPIA
                            </a>
                            <a href="#breach" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-exclamation-triangle me-2" style="color: #b69964;"></i> Breach History
                            </a>
                            <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-check-double me-2" style="color: #b69964;"></i> Compliance
                            </a>
                        </div>
                    </div>
                    <hr>
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Created:</span>
                            <span class="fw-bold">{{ $submission->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Last Updated:</span>
                            <span class="fw-bold">{{ $submission->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9" data-aos="fade-left">

            @php
                /**
                 * Safely coerce a value to a PHP array, handling three possible
                 * states that can come out of the DB for array-cast fields:
                 *   1. Already a PHP array (Eloquent cast worked correctly)  → return as-is
                 *   2. A JSON-encoded string (double-encoded on save)         → json_decode it
                 *   3. null / empty                                           → return []
                 */
                $toArray = function ($value): array {
                    if (is_array($value)) return $value;
                    if (is_string($value) && $value !== '') {
                        $decoded = json_decode($value, true);
                        return is_array($decoded) ? $decoded : [$value];
                    }
                    return [];
                };

                // Normalize every array-typed field on this submission so
                // @forelse and implode() never receive a string.
                $jointControllers        = $toArray($submission->joint_controllers);
                $categoriesRecords       = $toArray($submission->categories_records);
                $dataSubjects            = $toArray($submission->data_subjects);
                $personalDataCategories  = $toArray($submission->personal_data_categories);
                $internalRecipients      = $toArray($submission->internal_recipients);
                $specialCategoryRecipients = $toArray($submission->special_category_recipients);
                $legalBasis              = $toArray($submission->legal_basis);
                $sensitiveLegalBasis     = $toArray($submission->sensitive_legal_basis);
                $individualRights        = $toArray($submission->individual_rights);
                $externalRecipients      = $toArray($submission->external_recipients);
                $internationalTransfers  = $toArray($submission->international_transfers);
                $transferMechanisms      = $toArray($submission->transfer_mechanisms);
                $dpaConditions           = $toArray($submission->dpa_conditions);
                $gdprArticles            = $toArray($submission->gdpr_articles);

                // special_category_documents is a plain string column (not array-cast),
                // but some submissions have it stored as a JSON-encoded array string.
                // Decode it for display so it renders sensibly either way.
                $specialCategoryDocuments = $submission->special_category_documents;
                if (is_string($specialCategoryDocuments) && str_starts_with(trim($specialCategoryDocuments), '[')) {
                    $decoded = json_decode($specialCategoryDocuments, true);
                    $specialCategoryDocuments = is_array($decoded) ? implode(', ', $decoded) : $specialCategoryDocuments;
                }
            @endphp

            {{-- ============================================================ --}}
            {{-- SECTION: Process Identity (main + sub-process names)         --}}
            {{-- ============================================================ --}}
            <div id="process-identity" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-process-identity', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-signature" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white">Process Identity</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">Main Process Name</small>
                                <p class="mb-0 fw-bold view-mode">{{ $parentForm->main_process_name ?: 'N/A' }}</p>
                                <input type="text" name="main_process_name" class="form-control edit-mode d-none" maxlength="255" value="{{ $parentForm->main_process_name }}" required>
                                <small class="text-muted edit-mode d-none">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Shared across all sub-processes under this RoPA process. Changing this renames it everywhere.
                                </small>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Sub-process Name</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->sub_process_name ?? 'N/A (main process)' }}</p>
                                <input type="text" name="sub_process_name" class="form-control edit-mode d-none" maxlength="255" value="{{ $submission->sub_process_name }}" placeholder="Leave blank if this is the main process">
                                <small class="text-muted edit-mode d-none">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Only applies to this submission (#{{ $submission->id }}).
                                </small>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Basic Information (Step 1)                          --}}
            {{-- ============================================================ --}}
            <div id="basic-info" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-info-circle" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white">Basic Information</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <small class="text-muted">Personnel ID</small>
                                <p class="mb-0 fw-bold view-mode">{{ $parentForm->personnel_id ?? 'N/A' }}</p>
                                <input type="text" name="personnel_id" class="form-control edit-mode d-none" maxlength="50" value="{{ $parentForm->personnel_id }}">
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Surname</small>
                                <p class="mb-0 fw-bold view-mode">{{ $parentForm->surname ?? 'N/A' }}</p>
                                <input type="text" name="surname" class="form-control edit-mode d-none" maxlength="100" value="{{ $parentForm->surname }}" required>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">Firstname</small>
                                <p class="mb-0 fw-bold view-mode">{{ $parentForm->firstname ?? 'N/A' }}</p>
                                <input type="text" name="firstname" class="form-control edit-mode d-none" maxlength="100" value="{{ $parentForm->firstname }}" required>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Purpose</small>
                                <p class="mb-0 view-mode">{{ $submission->purpose ?? 'N/A' }}</p>
                                <textarea name="purpose" rows="3" class="form-control edit-mode d-none">{{ $submission->purpose }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Role Responsible</small>
                                <p class="mb-0 fw-bold view-mode">{{ $parentForm->role_responsible ?? 'N/A' }}</p>
                                <input type="text" name="role_responsible" class="form-control edit-mode d-none" maxlength="255" value="{{ $parentForm->role_responsible }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Joint Controllers (Step 2)                          --}}
            {{-- ============================================================ --}}
            <div id="joint-controllers" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-users" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Joint Controllers</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="view-mode">
                            @forelse($jointControllers as $controller)
                                @php $c = is_array($controller) ? $controller : []; @endphp
                                <div class="card border mb-3 p-3" style="border-radius: 12px;">
                                    <div class="row g-2">
                                        <div class="col-md-6">
                                            <small class="text-muted">Organisation / Name</small>
                                            <p class="mb-0 fw-bold">{{ $c['name'] ?? 'N/A' }}</p>
                                        </div>
                                        @if(!empty($c['contact_name']))
                                        <div class="col-md-6">
                                            <small class="text-muted">Contact Name</small>
                                            <p class="mb-0">{{ $c['contact_name'] }}</p>
                                        </div>
                                        @endif
                                        @if(!empty($c['contact_email']))
                                        <div class="col-md-6">
                                            <small class="text-muted">Contact Email</small>
                                            <p class="mb-0">
                                                <a href="mailto:{{ $c['contact_email'] }}">{{ $c['contact_email'] }}</a>
                                            </p>
                                        </div>
                                        @endif
                                        @if(!empty($c['contact_phone']))
                                        <div class="col-md-6">
                                            <small class="text-muted">Contact Phone</small>
                                            <p class="mb-0">{{ $c['contact_phone'] }}</p>
                                        </div>
                                        @endif
                                        @if(!empty($c['contact_address']))
                                        <div class="col-12">
                                            <small class="text-muted">Contact Address</small>
                                            <p class="mb-0">{{ $c['contact_address'] }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <span class="text-muted">N/A</span>
                            @endforelse
                        </div>
                        <textarea name="joint_controllers_raw" rows="6" class="form-control edit-mode d-none" data-json-field="joint_controllers" placeholder='[{"name":"Organisation name","contact_name":"Full name","contact_email":"email@example.com","contact_phone":"0201234567","contact_address":"City, Country"}]'>{{ json_encode($jointControllers, JSON_PRETTY_PRINT) }}</textarea>
                        <small class="text-muted edit-mode d-none">
                            <i class="fas fa-info-circle me-1"></i>
                            Edit as JSON. Each entry supports: <code>name</code>, <code>contact_name</code>, <code>contact_email</code>, <code>contact_phone</code>, <code>contact_address</code>.
                        </small>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Data Categories (Step 3)                            --}}
            {{-- ============================================================ --}}
            <div id="data-categories" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-database" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Data Categories</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <small class="text-muted">Categories of Records</small>
                                <p class="mb-0 view-mode">
                                    @forelse($categoriesRecords as $item)
                                        <span class="badge bg-primary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="categories_records_raw" rows="2" class="form-control edit-mode d-none" data-array-field="categories_records" placeholder="Comma-separated list">{{ implode(', ', $categoriesRecords) }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Data Subjects</small>
                                <p class="mb-0 view-mode">
                                    @forelse($dataSubjects as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="data_subjects_raw" rows="2" class="form-control edit-mode d-none" data-array-field="data_subjects" placeholder="Comma-separated list">{{ implode(', ', $dataSubjects) }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Personal Data Categories</small>
                                <p class="mb-0 view-mode">
                                    @forelse($personalDataCategories as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="personal_data_categories_raw" rows="2" class="form-control edit-mode d-none" data-array-field="personal_data_categories" placeholder="Comma-separated list">{{ implode(', ', $personalDataCategories) }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Special Category Documents</small>
                                <p class="mb-0 view-mode">{{ $specialCategoryDocuments ?? 'N/A' }}</p>
                                <textarea name="special_category_documents" rows="2" class="form-control edit-mode d-none">{{$specialCategoryDocuments}}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Data Sharing (Step 4)                               --}}
            {{-- ============================================================ --}}
            <div id="data-sharing" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-share-alt" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Data Sharing</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">Shared Internally?</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->share_internally ? 'Yes' : 'No' }}</p>
                                <select name="share_internally" class="form-select edit-mode d-none">
                                    <option value="1" @selected($submission->share_internally)>Yes</option>
                                    <option value="0" @selected(!$submission->share_internally)>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Internal Sharing Categories</small>
                                <p class="mb-0 view-mode">{{ $submission->internal_sharing_categories ?? 'N/A' }}</p>
                                <textarea name="internal_sharing_categories" rows="2" class="form-control edit-mode d-none">{{ $submission->internal_sharing_categories }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Internal Recipients</small>
                                <p class="mb-0 view-mode">
                                    @forelse($internalRecipients as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="internal_recipients_raw" rows="2" class="form-control edit-mode d-none" data-array-field="internal_recipients" placeholder="Comma-separated list">{{ implode(', ', $internalRecipients) }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Special Category Recipients</small>
                                <p class="mb-0 view-mode">
                                    @forelse($specialCategoryRecipients as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="special_category_recipients_raw" rows="2" class="form-control edit-mode d-none" data-array-field="special_category_recipients" placeholder="Comma-separated list">{{ implode(', ', $specialCategoryRecipients) }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Sharing Reasons</small>
                                <p class="mb-0 view-mode">{{ $submission->sharing_reasons ?? 'N/A' }}</p>
                                <textarea name="sharing_reasons" rows="2" class="form-control edit-mode d-none">{{ $submission->sharing_reasons }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Data Source (Step 5)                                --}}
            {{-- ============================================================ --}}
            <div id="data-source" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-cloud-download-alt" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Data Source</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">Data Source</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->data_source ? ucfirst(str_replace('_', ' ', $submission->data_source)) : 'N/A' }}</p>
                                <select name="data_source" class="form-select edit-mode d-none">
                                    <option value="individual" @selected($submission->data_source === 'individual')>Individual</option>
                                    <option value="third_party" @selected($submission->data_source === 'third_party')>Third Party</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Data Update Method</small>
                                <p class="mb-0 view-mode">{{ $submission->data_update_method ?? 'N/A' }}</p>
                                <input type="text" name="data_update_method" class="form-control edit-mode d-none" value="{{ $submission->data_update_method }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Legal Basis (Step 6)                                --}}
            {{-- ============================================================ --}}
            <div id="legal-basis" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-gavel" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Legal Basis</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <small class="text-muted">Legal Basis</small>
                                <p class="mb-0 view-mode">
                                    @forelse($legalBasis as $item)
                                        <span class="badge bg-primary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="legal_basis_raw" rows="2" class="form-control edit-mode d-none" data-array-field="legal_basis" placeholder="Comma-separated list">{{ implode(', ', $legalBasis) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">LIA Documented?</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->lia_documented ? 'Yes' : 'No' }}</p>
                                <select name="lia_documented" class="form-select edit-mode d-none">
                                    <option value="1" @selected($submission->lia_documented)>Yes</option>
                                    <option value="0" @selected(!$submission->lia_documented)>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">LIA Location</small>
                                <p class="mb-0 view-mode">{{ $submission->lia_location ?? 'N/A' }}</p>
                                <input type="text" name="lia_location" class="form-control edit-mode d-none" maxlength="500" value="{{ $submission->lia_location }}">
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Sensitive Legal Basis</small>
                                <p class="mb-0 view-mode">
                                    @forelse($sensitiveLegalBasis as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="sensitive_legal_basis_raw" rows="2" class="form-control edit-mode d-none" data-array-field="sensitive_legal_basis" placeholder="Comma-separated list">{{ implode(', ', $sensitiveLegalBasis) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Retention Period</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->retention_period ?? 'N/A' }}</p>
                                <input type="text" name="retention_period" class="form-control edit-mode d-none" maxlength="255" value="{{ $submission->retention_period }}">
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Legally Required Retention?</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->legally_required_retention ? 'Yes' : 'No' }}</p>
                                <select name="legally_required_retention" class="form-select edit-mode d-none">
                                    <option value="1" @selected($submission->legally_required_retention)>Yes</option>
                                    <option value="0" @selected(!$submission->legally_required_retention)>No</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Special Category Condition</small>
                                <p class="mb-0 view-mode">{{ $submission->special_category_condition ?? 'N/A' }}</p>
                                <textarea name="special_category_condition" rows="2" class="form-control edit-mode d-none">{{ $submission->special_category_condition }}</textarea>
                            </div>
                            @if($submission->legitimate_interests)
                            <div class="col-12">
                                <small class="text-muted">Legitimate Interests</small>
                                <p class="mb-0 view-mode">{{ $submission->legitimate_interests }}</p>
                                <textarea name="legitimate_interests" rows="2" class="form-control edit-mode d-none">{{ $submission->legitimate_interests }}</textarea>
                            </div>
                            @endif
                            <div class="col-12">
                                <small class="text-muted">LIA Link</small>
                                <p class="mb-0 view-mode">
                                    @if($submission->lia_link)
                                        <a href="{{ $submission->lia_link }}" target="_blank" rel="noopener">{{ $submission->lia_link }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <input type="url" name="lia_link" class="form-control edit-mode d-none" maxlength="500" value="{{ $submission->lia_link }}">
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Individual Rights</small>
                                <p class="mb-0 view-mode">
                                    @forelse($individualRights as $item)
                                        <span class="badge bg-primary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="individual_rights_raw" rows="2" class="form-control edit-mode d-none" data-array-field="individual_rights" placeholder="Comma-separated list">{{ implode(', ', $individualRights) }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Security Measures (Step 7)                          --}}
            {{-- ============================================================ --}}
            <div id="security" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-shield-alt" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Security &amp; Protection Measures</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <p class="mb-0 view-mode">{{ $submission->security_measures ?? 'N/A' }}</p>
                        <textarea name="security_measures" rows="3" class="form-control edit-mode d-none">{{ $submission->security_measures }}</textarea>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: External Recipients (Step 8)                       --}}
            {{-- ============================================================ --}}
            <div id="external-recipients" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-paper-plane" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >External Recipients</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="view-mode">
                            @forelse($externalRecipients as $recipient)
                                <div class="tag-chip d-inline-flex me-2 mb-2 align-items-start">
                                    <span>
                                        <strong>{{ is_array($recipient) ? ($recipient['name'] ?? 'N/A') : $recipient }}</strong>
                                        @if(is_array($recipient))
                                            <br>
                                            <small class="text-muted">
                                                {{ $recipient['type'] ?? '' }}
                                                @if(!empty($recipient['relationship'])) &middot; {{ $recipient['relationship'] }} @endif
                                                @if(!empty($recipient['contract'])) &middot; Contract: {{ $recipient['contract'] }} @endif
                                            </small>
                                        @endif
                                    </span>
                                </div>
                            @empty
                                <span class="text-muted">N/A</span>
                            @endforelse
                        </div>
                        <textarea name="external_recipients_raw" rows="3" class="form-control edit-mode d-none" data-json-field="external_recipients" placeholder='JSON array, e.g. [{"name":"...","type":"...","contract":"Yes","relationship":"..."}]'>{{ json_encode($externalRecipients, JSON_PRETTY_PRINT) }}</textarea>
                        <small class="text-muted edit-mode d-none">Each recipient needs name, type, contract, and relationship — edit as JSON.</small>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: International Transfers (Step 9)                   --}}
            {{-- ============================================================ --}}
            <div id="transfers" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-globe" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >International Transfers</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <small class="text-muted">International Transfers</small>
                                <p class="mb-0 view-mode">
                                    @forelse($internationalTransfers as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="international_transfers_raw" rows="2" class="form-control edit-mode d-none" data-array-field="international_transfers" placeholder="Comma-separated list">{{ implode(', ', $internationalTransfers) }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Transfer Mechanisms</small>
                                <p class="mb-0 view-mode">
                                    @forelse($transferMechanisms as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="transfer_mechanisms_raw" rows="2" class="form-control edit-mode d-none" data-array-field="transfer_mechanisms" placeholder="Comma-separated list">{{ implode(', ', $transferMechanisms) }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Automated Decision-Making (Step 10)                --}}
            {{-- ============================================================ --}}
            <div id="automated-decisions" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-robot" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Automated Decision-Making</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">Automated Decision-Making Used?</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->auto_decision_making ? 'Yes' : 'No' }}</p>
                                <select name="auto_decision_making" class="form-select edit-mode d-none">
                                    <option value="1" @selected($submission->auto_decision_making)>Yes</option>
                                    <option value="0" @selected(!$submission->auto_decision_making)>No</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Profiling Description</small>
                                <p class="mb-0 view-mode">{{ $submission->profiling_description ?? 'N/A' }}</p>
                                <textarea name="profiling_description" rows="2" class="form-control edit-mode d-none">{{ $submission->profiling_description }}</textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Consent & Data Location (Step 11)                  --}}
            {{-- ============================================================ --}}
            <div id="consent" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-check-square" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Consent &amp; Data Location</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">Consent Link</small>
                                <p class="mb-0 view-mode">
                                    @if($submission->consent_link)
                                        <a href="{{ $submission->consent_link }}" target="_blank" rel="noopener">{{ $submission->consent_link }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <input type="url" name="consent_link" class="form-control edit-mode d-none" maxlength="500" value="{{ $submission->consent_link }}">
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Data Location</small>
                                <p class="mb-0 view-mode">{{ $submission->data_location ?? 'N/A' }}</p>
                                <input type="text" name="data_location" class="form-control edit-mode d-none" value="{{ $submission->data_location }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: DPIA (Step 12)                                      --}}
            {{-- ============================================================ --}}
            <div id="dpia" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-clipboard-check" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Data Protection Impact Assessment</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <small class="text-muted">DPIA Required?</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->dpia_required ? 'Yes' : 'No' }}</p>
                                <select name="dpia_required" class="form-select edit-mode d-none">
                                    <option value="1" @selected($submission->dpia_required)>Yes</option>
                                    <option value="0" @selected(!$submission->dpia_required)>No</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">DPIA Progress</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->dpia_progress ? ucwords(str_replace('_', ' ', $submission->dpia_progress)) : 'N/A' }}</p>
                                <select name="dpia_progress" class="form-select edit-mode d-none">
                                    <option value="not_started" @selected($submission->dpia_progress === 'not_started')>Not Started</option>
                                    <option value="in_progress" @selected($submission->dpia_progress === 'in_progress')>In Progress</option>
                                    <option value="completed" @selected($submission->dpia_progress === 'completed')>Completed</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <small class="text-muted">DPIA Link</small>
                                <p class="mb-0 view-mode">
                                    @if($submission->dpia_link)
                                        <a href="{{ $submission->dpia_link }}" target="_blank" rel="noopener">View</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <input type="url" name="dpia_link" class="form-control edit-mode d-none" maxlength="500" value="{{ $submission->dpia_link }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Breach History (Step 13)                            --}}
            {{-- ============================================================ --}}
            <div id="breach" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-exclamation-triangle" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Breach History</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">Breach Occurred?</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->breach_occurred ? 'Yes' : 'No' }}</p>
                                <select name="breach_occurred" class="form-select edit-mode d-none">
                                    <option value="1" @selected($submission->breach_occurred)>Yes</option>
                                    <option value="0" @selected(!$submission->breach_occurred)>No</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Breach Link</small>
                                <p class="mb-0 view-mode">
                                    @if($submission->breach_link)
                                        <a href="{{ $submission->breach_link }}" target="_blank" rel="noopener">View</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <input type="url" name="breach_link" class="form-control edit-mode d-none" maxlength="500" value="{{ $submission->breach_link }}">
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- ============================================================ --}}
            {{-- SECTION: Compliance (Step 14)                                --}}
            {{-- ============================================================ --}}
            <div id="compliance" class="card border-0 shadow-sm mb-4 editable-section">
                <form method="POST" action="{{ route('ropa.update-submission', $submission) }}" class="section-form">
                    @csrf
                    @method('PATCH')
                    <div class="card-header bg-white border-0 pt-4 pb-2 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                                <i class="fas fa-check-double" style="color: #153d6f;"></i>
                            </div>
                            <h4 class="mb-0 text-white" >Compliance Information</h4>
                        </div>
                        @include('ropa.partials.section-edit-controls')
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <small class="text-muted">DPA 2018 Conditions</small>
                                <p class="mb-0 view-mode">
                                    @forelse($dpaConditions as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="dpa_conditions_raw" rows="2" class="form-control edit-mode d-none" data-array-field="dpa_conditions" placeholder="Comma-separated list">{{ implode(', ', $dpaConditions) }}</textarea>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">GDPR Articles</small>
                                <p class="mb-0 view-mode">
                                    @forelse($gdprArticles as $item)
                                        <span class="badge bg-secondary me-1 mb-1">{{ $item }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                                <textarea name="gdpr_articles_raw" rows="2" class="form-control edit-mode d-none" data-array-field="gdpr_articles" placeholder="Comma-separated list">{{ implode(', ', $gdprArticles) }}</textarea>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Retention Policy Link</small>
                                <p class="mb-0 view-mode">
                                    @if($submission->retention_policy_link)
                                        <a href="{{ $submission->retention_policy_link }}" target="_blank" rel="noopener">{{ $submission->retention_policy_link }}</a>
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <input type="url" name="retention_policy_link" class="form-control edit-mode d-none" maxlength="500" value="{{ $submission->retention_policy_link }}">
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Retained Per Policy?</small>
                                <p class="mb-0 fw-bold view-mode">{{ $submission->retained_per_policy ? 'Yes' : 'No' }}</p>
                                <select name="retained_per_policy" class="form-select edit-mode d-none">
                                    <option value="1" @selected($submission->retained_per_policy)>Yes</option>
                                    <option value="0" @selected(!$submission->retained_per_policy)>No</option>
                                </select>
                            </div>
                            @if($submission->retention_non_adherence_reason)
                            <div class="col-md-6">
                                <small class="text-muted">Non-Adherence Reason</small>
                                <p class="mb-0 view-mode">{{ $submission->retention_non_adherence_reason }}</p>
                                <textarea name="retention_non_adherence_reason" rows="2" class="form-control edit-mode d-none">{{ $submission->retention_non_adherence_reason }}</textarea>
                            </div>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            <!-- Footer Note -->
            <div class="alert alert-light text-center">
                <i class="fas fa-lock me-2"></i>
                This is an official record of processing activities under the University of Ghana Data Protection Framework.
                <br>
                <small class="text-muted">
                    Submission ID: {{ $submission->id }} | RoPA Process: {{ $parentForm->main_process_name }}
                    @if($submission->completed_at)
                        | Completed: {{ $submission->completed_at->format('Y-m-d H:i:s') }}
                    @endif
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('ropa.destroy-submission', $submission) }}">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete RoPA Record</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>What would you like to delete?</p>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="scope" id="scopeSubmission" value="submission" checked>
                        <label class="form-check-label" for="scopeSubmission">
                            <strong>Just this sub-process</strong>
                            <br>
                            <small class="text-muted">{{ $submission->sub_process_name ?? 'Main Process' }} (Submission #{{ $submission->id }}) only. Other sub-processes under "{{ $parentForm->main_process_name }}" are kept.</small>
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="scope" id="scopeForm" value="form">
                        <label class="form-check-label" for="scopeForm">
                            <strong>The entire RoPA process</strong>
                            <br>
                            <small class="text-muted">"{{ $parentForm->main_process_name }}" and all of its sub-processes will be permanently deleted.</small>
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete Forever</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style media="print">
    /* Print styles */
    .navbar,
    .btn,
    .breadcrumb,
    .sticky-top,
    .alert-success,
    .edit-mode,
    .section-edit-btn {
        display: none !important;
    }

    .col-lg-3 {
        display: none !important;
    }

    .col-lg-9 {
        width: 100% !important;
    }

    .card {
        box-shadow: none !important;
        border: 1px solid #ddd !important;
        break-inside: avoid;
    }

    body {
        background: white !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .container {
        max-width: 100% !important;
        padding: 0 !important;
    }

    .alert-success {
        display: none !important;
    }
</style>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Per-section Edit / Cancel / Save toggling.
        // Each .editable-section has its own form with a single
        // "Edit" button that reveals .edit-mode inputs and hides
        // .view-mode text, plus "Cancel" (reverts, no request) and
        // the form's normal submit ("Save") which actually persists.
        document.querySelectorAll('.editable-section').forEach(function(section) {
            const editBtn = section.querySelector('.section-edit-btn');
            const cancelBtn = section.querySelector('.section-cancel-btn');
            const saveBtn = section.querySelector('.section-save-btn');
            const form = section.querySelector('.section-form');

            function setEditing(isEditing) {
                section.querySelectorAll('.view-mode').forEach(el => el.classList.toggle('d-none', isEditing));
                section.querySelectorAll('.edit-mode').forEach(el => el.classList.toggle('d-none', !isEditing));
                editBtn?.classList.toggle('d-none', isEditing);
                cancelBtn?.classList.toggle('d-none', !isEditing);
                saveBtn?.classList.toggle('d-none', !isEditing);
            }

            editBtn?.addEventListener('click', function() {
                setEditing(true);
            });

            cancelBtn?.addEventListener('click', function() {
                form.reset();
                setEditing(false);
            });

            // Before submitting, convert any comma-separated "_raw"
            // textareas back into proper array inputs the backend expects
            // (the validation rules expect arrays for these fields).
            form?.addEventListener('submit', function(e) {
                // Structured fields (joint_controllers, external_recipients):
                // edited as JSON text, submitted as nested array inputs.
                let jsonError = false;
                form.querySelectorAll('[data-json-field]').forEach(function(textarea) {
                    const fieldName = textarea.getAttribute('data-json-field');

                    form.querySelectorAll('input[type="hidden"][data-generated-for="' + fieldName + '"]').forEach(el => el.remove());

                    let parsed;
                    try {
                        parsed = JSON.parse(textarea.value || '[]');
                    } catch (err) {
                        alert('The ' + fieldName.replace(/_/g, ' ') + ' field isn\'t valid JSON. Please fix it before saving (e.g. check for missing commas or quotes).');
                        jsonError = true;
                        return;
                    }

                    if (!Array.isArray(parsed)) {
                        parsed = [];
                    }

                    parsed.forEach(function(obj, index) {
                        if (obj && typeof obj === 'object') {
                            Object.keys(obj).forEach(function(key) {
                                const hidden = document.createElement('input');
                                hidden.type = 'hidden';
                                hidden.name = fieldName + '[' + index + '][' + key + ']';
                                hidden.value = obj[key];
                                hidden.setAttribute('data-generated-for', fieldName);
                                form.appendChild(hidden);
                            });
                        }
                    });

                    textarea.disabled = true;
                });

                if (jsonError) {
                    e.preventDefault();
                    return;
                }

                // Simple list fields (legal_basis, data_subjects, etc.):
                // edited as comma-separated text, submitted as flat array inputs.
                form.querySelectorAll('[data-array-field]').forEach(function(textarea) {
                    const fieldName = textarea.getAttribute('data-array-field');
                    const values = textarea.value
                        .split(',')
                        .map(v => v.trim())
                        .filter(v => v.length > 0);

                    // Remove any previously injected hidden inputs for this field
                    form.querySelectorAll('input[type="hidden"][data-generated-for="' + fieldName + '"]').forEach(el => el.remove());

                    values.forEach(function(value) {
                        const hidden = document.createElement('input');
                        hidden.type = 'hidden';
                        hidden.name = fieldName + '[]';
                        hidden.value = value;
                        hidden.setAttribute('data-generated-for', fieldName);
                        form.appendChild(hidden);
                    });

                    // Disable the textarea itself so its raw value isn't
                    // also submitted under a name the backend doesn't expect.
                    textarea.disabled = true;
                });
            });
        });
    });
</script>
@endpush
@endsection
