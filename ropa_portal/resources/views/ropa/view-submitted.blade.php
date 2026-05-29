@extends('layouts.app')

@section('title', 'Submitted RoPA Form #' . $ropaForm->id)

@section('content')
<div class="container py-4">
    <!-- Header Section -->
    <div class="row mb-4" data-aos="fade-up">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('ropa.index') }}" class="text-decoration-none">Dashboard</a></li>
                            <li class="breadcrumb-item active">Submitted Form #{{ $ropaForm->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i> Print / Save as PDF
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
            <div class="alert alert-success border-0 shadow-sm" style="background: linear-gradient(135deg, #d4edda 0%, #ffffff 100%); border-left: 5px solid #28a745 !important;">
                <div class="d-flex align-items-center justify-content-between flex-wrap">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-2x me-3" style="color: #28a745;"></i>
                        <div>
                            <h5 class="mb-0" style="color: #155724;">Form Successfully Submitted</h5>
                            <p class="mb-0 text-muted small">
                                Submitted on: {{ $ropaForm->submitted_at ? $ropaForm->submitted_at->format('F d, Y \a\t H:i:s') : 'N/A' }}
                            </p>
                        </div>
                    </div>
                    <div>
                        <span class="badge bg-success px-3 py-2">
                            <i class="fas fa-check me-1"></i> {{ strtoupper($ropaForm->status) }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Content -->
    <div class="row">
        <div class="col-lg-3 mb-4" data-aos="fade-right">
            <!-- Navigation Cards -->
            <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                <div class="card-body p-3">
                    <div class="text-center mb-3">
                        <div class="rounded-circle bg-primary bg-opacity-10 d-inline-flex p-3 mb-2">
                            <i class="fas fa-file-alt fa-2x" style="color: #153d6f;"></i>
                        </div>
                        <h6 class="mb-1">Form #{{ $ropaForm->id }}</h6>
                        <small class="text-muted">Record of Processing Activities</small>
                    </div>

                    <hr>

                    <!-- Quick Navigation -->
                    <div class="mb-3">
                        <label class="fw-bold small text-muted mb-2">QUICK NAVIGATION</label>
                        <div class="list-group list-group-flush">
                            <a href="#basic-info" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-info-circle me-2" style="color: #b69964;"></i> Basic Information
                            </a>
                            <a href="#data-categories" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-database me-2" style="color: #b69964;"></i> Data Categories
                            </a>
                            <a href="#legal-basis" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-gavel me-2" style="color: #b69964;"></i> Legal Basis
                            </a>
                            <a href="#sharing" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-share-alt me-2" style="color: #b69964;"></i> Data Sharing
                            </a>
                            <a href="#security" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-shield-alt me-2" style="color: #b69964;"></i> Security Measures
                            </a>
                            <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                <i class="fas fa-check-double me-2" style="color: #b69964;"></i> Compliance
                            </a>
                        </div>
                    </div>

                    <hr>

                    <!-- Form Stats -->
                    <div class="small">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Created:</span>
                            <span class="fw-bold">{{ $ropaForm->created_at->format('M d, Y') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Last Updated:</span>
                            <span class="fw-bold">{{ $ropaForm->updated_at->diffForHumans() }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Submitted By:</span>
                            <span class="fw-bold">{{ $ropaForm->user->name ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9" data-aos="fade-left">
            <!-- Basic Information Section -->
            <div id="basic-info" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-info-circle" style="color: #153d6f;"></i>
                        </div>
                        <h4 class="mb-0" style="color: #153d6f;">Basic Information</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Personnel ID</small>
                                <p class="mb-0 fw-bold">{{ $ropaForm->personnel_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Surname</small>
                                <p class="mb-0 fw-bold">{{ $ropaForm->surname ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Firstname</small>
                                <p class="mb-0 fw-bold">{{ $ropaForm->firstname ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Business Function</small>
                                <p class="mb-0 fw-bold">{{ $ropaForm->business_function ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Process / Project Names</small>
                                <p class="mb-0">
                                    @php $processes = is_array($ropaForm->process_names) ? $ropaForm->process_names : json_decode($ropaForm->process_names ?? '[]', true); @endphp
                                    @forelse($processes as $process)
                                        <span class="badge bg-primary me-1 mb-1">{{ $process }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Purpose of Processing</small>
                                <p class="mb-0">{{ $ropaForm->purpose ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Role Responsible</small>
                                <p class="mb-0 fw-bold">{{ $ropaForm->role_responsible ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Joint Controllers Section -->
            @php
                $jointControllers = is_array($ropaForm->joint_controllers)
                    ? $ropaForm->joint_controllers
                    : (json_decode($ropaForm->joint_controllers ?? '[]', true) ?: []);
            @endphp
            @if(!empty($jointControllers))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-handshake" style="color: #153d6f;"></i>
                        </div>
                        <h4 class="mb-0" style="color: #153d6f;">Joint Controllers & Collaboration</h4>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($jointControllers as $idx => $controller)
                        <div class="border-bottom pb-3 mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">Name of Joint Controller</small>
                                    <p class="mb-0 fw-bold">{{ $controller['name'] ?? 'N/A' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">Contact Details</small>
                                    <p class="mb-0">{{ $controller['contact'] ?? 'N/A' }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Data Categories Section -->
            <div id="data-categories" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-database" style="color: #153d6f;"></i>
                        </div>
                        <h4 class="mb-0" style="color: #153d6f;">Data Categories & Subjects</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Categories of Records</small>
                                <p class="mb-0">
                                    @php $records = is_array($ropaForm->categories_records) ? $ropaForm->categories_records : json_decode($ropaForm->categories_records ?? '[]', true); @endphp
                                    @forelse($records as $record)
                                        <span class="badge bg-secondary me-1">{{ $record }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Data Subjects</small>
                                <p class="mb-0">
                                    @php $subjects = is_array($ropaForm->data_subjects) ? $ropaForm->data_subjects : json_decode($ropaForm->data_subjects ?? '[]', true); @endphp
                                    @forelse($subjects as $subject)
                                        <span class="badge bg-info me-1">{{ $subject }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Personal Data Categories</small>
                                <p class="mb-0">
                                    @php $personalData = is_array($ropaForm->personal_data_categories) ? $ropaForm->personal_data_categories : json_decode($ropaForm->personal_data_categories ?? '[]', true); @endphp
                                    @forelse($personalData as $data)
                                        <span class="badge bg-warning me-1">{{ $data }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                        @if($ropaForm->special_category_documents)
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted text-danger">Special Category Data Documents</small>
                                <p class="mb-0">{{ $ropaForm->special_category_documents }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Legal Basis Section -->
            <div id="legal-basis" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-gavel" style="color: #153d6f;"></i>
                        </div>
                        <h4 class="mb-0" style="color: #153d6f;">Legal Basis & Compliance</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Legal Basis for Processing</small>
                                <p class="mb-0">
                                    @php $legalBasis = is_array($ropaForm->legal_basis) ? $ropaForm->legal_basis : json_decode($ropaForm->legal_basis ?? '[]', true); @endphp
                                    @forelse($legalBasis as $basis)
                                        <span class="badge bg-success me-1">{{ $basis }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Retention Period</small>
                                <p class="mb-0 fw-bold">{{ $ropaForm->retention_period ?? 'N/A' }}</p>
                            </div>
                        </div>
                        @if($ropaForm->legitimate_interests)
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Legitimate Interests</small>
                                <p class="mb-0">{{ $ropaForm->legitimate_interests }}</p>
                            </div>
                        </div>
                        @endif
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Individual Rights</small>
                                <p class="mb-0">
                                    @php $rights = is_array($ropaForm->individual_rights) ? $ropaForm->individual_rights : json_decode($ropaForm->individual_rights ?? '[]', true); @endphp
                                    @forelse($rights as $right)
                                        <span class="badge bg-primary me-1">{{ $right }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Measures Section -->
            @if($ropaForm->security_measures)
            <div id="security" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-shield-alt" style="color: #153d6f;"></i>
                        </div>
                        <h4 class="mb-0" style="color: #153d6f;">Security & Protection Measures</h4>
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $ropaForm->security_measures }}</p>
                </div>
            </div>
            @endif

            <!-- Compliance Section -->
            <div id="compliance" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-0">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-check-double" style="color: #153d6f;"></i>
                        </div>
                        <h4 class="mb-0" style="color: #153d6f;">Compliance Information</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">DPA 2018 Conditions</small>
                                <p class="mb-0">
                                    @php $dpa = is_array($ropaForm->dpa_conditions) ? $ropaForm->dpa_conditions : json_decode($ropaForm->dpa_conditions ?? '[]', true); @endphp
                                    @forelse($dpa as $condition)
                                        <span class="badge bg-secondary me-1">{{ $condition }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">GDPR Articles</small>
                                <p class="mb-0">
                                    @php $gdpr = is_array($ropaForm->gdpr_articles) ? $ropaForm->gdpr_articles : json_decode($ropaForm->gdpr_articles ?? '[]', true); @endphp
                                    @forelse($gdpr as $article)
                                        <span class="badge bg-secondary me-1">{{ $article }}</span>
                                    @empty
                                        <span class="text-muted">N/A</span>
                                    @endforelse
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer Note -->
            <div class="alert alert-light text-center">
                <i class="fas fa-lock me-2"></i>
                This is an official record of processing activities under the University of Ghana Data Protection Framework.
                <br>
                <small class="text-muted">Form ID: {{ $ropaForm->id }} | Submitted: {{ $ropaForm->submitted_at ? $ropaForm->submitted_at->format('Y-m-d H:i:s') : 'N/A' }}</small>
            </div>
        </div>
    </div>
</div>

<style media="print">
    /* Print styles */
    .navbar, .btn, .breadcrumb, .sticky-top, .alert-success {
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
            anchor.addEventListener('click', function (e) {
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
    });
</script>
@endpush
@endsection
