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
                            <i class="fas fa-file-alt fa-2x" ></i>
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
                                 <i class="fas fa-share-alt me-2" style="color: #b69964;"></i> Internal Sharing
                             </a>
                             <a href="#security" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                 <i class="fas fa-shield-alt me-2" style="color: #b69964;"></i> Security Measures
                             </a>
                             <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                 <i class="fas fa-exchange-alt me-2" style="color: #b69964;"></i> External Recipients
                             </a>
                             <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                 <i class="fas fa-globe me-2" style="color: #b69964;"></i> Intl. Transfers
                             </a>
                             <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                 <i class="fas fa-robot me-2" style="color: #b69964;"></i> Auto Decision
                             </a>
                             <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                 <i class="fas fa-file-signature me-2" style="color: #b69964;"></i> Consent
                             </a>
                             <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                 <i class="fas fa-chart-line me-2" style="color: #b69964;"></i> DPIA
                             </a>
                             <a href="#compliance" class="list-group-item list-group-item-action py-2 px-2 border-0">
                                 <i class="fas fa-exclamation-triangle me-2" style="color: #b69964;"></i> Breaches
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
                            <span class="fw-bold">{{ $ropaForm->user->firstname ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9" data-aos="fade-left">
            <!-- Basic Information Section -->
            <div id="basic-info" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-info-circle" ></i>
                        </div>
                        <h4 class="mb-0" >Basic Information</h4>
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
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-handshake" ></i>
                        </div>
                        <h4 class="mb-0" >Joint Controllers & Collaboration</h4>
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
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-database" ></i>
                        </div>
                        <h4 class="mb-0" >Data Categories & Subjects</h4>
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
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-gavel" ></i>
                        </div>
                        <h4 class="mb-0" >Legal Basis & Compliance</h4>
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
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-shield-alt" ></i>
                        </div>
                        <h4 class="mb-0" >Security & Protection Measures</h4>
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-0">{{ $ropaForm->security_measures }}</p>
                </div>
            </div>
            @endif

            <!-- Internal Sharing Section -->
            @if($ropaForm->share_internally)
            <div id="sharing" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-share-alt" ></i>
                        </div>
                        <h4 class="mb-0" >Internal Data Sharing</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if($ropaForm->internal_sharing_categories)
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">Categories Shared Internally</small>
                        <p class="mb-0">{{ $ropaForm->internal_sharing_categories }}</p>
                    </div>
                    @endif
                    @php $internalRecipients = is_array($ropaForm->internal_recipients) ? $ropaForm->internal_recipients : json_decode($ropaForm->internal_recipients ?? '[]', true); @endphp
                    @if(!empty($internalRecipients))
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">Internal Recipients</small>
                        <p class="mb-0">
                            @foreach($internalRecipients as $recipient)
                                <span class="badge bg-primary me-1">{{ $recipient }}</span>
                            @endforeach
                        </p>
                    </div>
                    @endif
                    @php $specialRecipients = is_array($ropaForm->special_category_recipients) ? $ropaForm->special_category_recipients : json_decode($ropaForm->special_category_recipients ?? '[]', true); @endphp
                    @if(!empty($specialRecipients))
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted text-danger">Special Category Recipients</small>
                        <p class="mb-0">
                            @foreach($specialRecipients as $recipient)
                                <span class="badge bg-warning me-1">{{ $recipient }}</span>
                            @endforeach
                        </p>
                    </div>
                    @endif
                    @if($ropaForm->sharing_reasons)
                    <div class="border-bottom pb-2">
                        <small class="text-muted">Reasons for Sharing</small>
                        <p class="mb-0">{{ $ropaForm->sharing_reasons }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Data Source Section -->
            @if($ropaForm->data_source)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-code-commit" ></i>
                        </div>
                        <h4 class="mb-0" >Data Source</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">Source Type</small>
                        <p class="mb-0">{{ $ropaForm->data_source === 'individual' ? 'Data collected from individuals' : 'Third party data source' }}</p>
                    </div>
                    @if($ropaForm->data_update_method)
                    <div class="border-bottom pb-2">
                        <small class="text-muted">Data Update Method</small>
                        <p class="mb-0">{{ $ropaForm->data_update_method }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- External Recipients Section -->
            @php $externalRecipients = is_array($ropaForm->external_recipients) ? $ropaForm->external_recipients : json_decode($ropaForm->external_recipients ?? '[]', true); @endphp
            @if(!empty($externalRecipients))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-exchange-alt" ></i>
                        </div>
                        <h4 class="mb-0" >External Recipients & Sharing</h4>
                    </div>
                </div>
                <div class="card-body">
                    @foreach($externalRecipients as $idx => $recipient)
                    <div class="border-bottom pb-3 mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <small class="text-muted">Recipient Name</small>
                                <p class="mb-0 fw-bold">{{ $recipient['name'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Type</small>
                                <p class="mb-0">{{ $recipient['type'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-3">
                                <small class="text-muted">Relationship</small>
                                <p class="mb-0">{{ $recipient['relationship'] ?? 'N/A' }}</p>
                            </div>
                            <div class="col-md-2">
                                <small class="text-muted">Contract</small>
                                <p class="mb-0">{{ $recipient['contract'] === 'yes' ? '✅ Yes' : ($recipient['contract'] === 'no' ? '❌ No' : 'N/A') }}</p>
                            </div>
                        </div>
                        @if(!empty($recipient['contact']))
                        <div class="mt-2">
                            <small class="text-muted">Contact Details</small>
                            <p class="mb-0">{{ $recipient['contact'] }}</p>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- International Transfers Section -->
            @php $internationalTransfers = is_array($ropaForm->international_transfers) ? $ropaForm->international_transfers : json_decode($ropaForm->international_transfers ?? '[]', true); @endphp
            @if(!empty($internationalTransfers))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-globe" ></i>
                        </div>
                        <h4 class="mb-0" >International Transfers</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">Transfer Countries</small>
                        <p class="mb-0">
                            @foreach($internationalTransfers as $country)
                                <span class="badge bg-info me-1">{{ $country }}</span>
                            @endforeach
                        </p>
                    </div>
                    @php $transferMechanisms = is_array($ropaForm->transfer_mechanisms) ? $ropaForm->transfer_mechanisms : json_decode($ropaForm->transfer_mechanisms ?? '[]', true); @endphp
                    @if(!empty($transferMechanisms))
                    <div class="border-bottom pb-2">
                        <small class="text-muted">Transfer Mechanisms</small>
                        <p class="mb-0">
                            @foreach($transferMechanisms as $mech)
                                <span class="badge bg-secondary me-1">{{ $mech }}</span>
                            @endforeach
                        </p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Automated Decision Making Section -->
            @if($ropaForm->auto_decision_making)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-robot" ></i>
                        </div>
                        <h4 class="mb-0" >Automated Decision Making</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if($ropaForm->profiling_description)
                    <div class="border-bottom pb-2">
                        <small class="text-muted">Profiling Description</small>
                        <p class="mb-0">{{ $ropaForm->profiling_description }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Consent & Storage Section -->
            @if($ropaForm->consent_link || $ropaForm->data_location)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-file-signature" ></i>
                        </div>
                        <h4 class="mb-0" >Consent & Storage</h4>
                    </div>
                </div>
                <div class="card-body">
                    @if($ropaForm->consent_link)
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">Consent Link</small>
                        <p class="mb-0"><a href="{{ $ropaForm->consent_link }}" target="_blank" class="text-primary">{{ $ropaForm->consent_link }}</a></p>
                    </div>
                    @endif
                    @if($ropaForm->data_location)
                    <div class="border-bottom pb-2">
                        <small class="text-muted">Data Location</small>
                        <p class="mb-0">{{ $ropaForm->data_location }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- DPIA Section -->
            @if($ropaForm->dpia_required !== null)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-chart-line" ></i>
                        </div>
                        <h4 class="mb-0" >Data Protection Impact Assessment</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">DPIA Required</small>
                        <p class="mb-0">{{ $ropaForm->dpia_required ? 'Yes' : 'No' }}</p>
                    </div>
                    @if($ropaForm->dpia_progress)
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">DPIA Progress</small>
                        <p class="mb-0">{{ ucfirst(str_replace('_', ' ', $ropaForm->dpia_progress)) }}</p>
                    </div>
                    @endif
                    @if($ropaForm->dpia_link)
                    <div class="border-bottom pb-2">
                        <small class="text-muted">DPIA Document Link</small>
                        <p class="mb-0"><a href="{{ $ropaForm->dpia_link }}" target="_blank" class="text-primary">{{ $ropaForm->dpia_link }}</a></p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Breach Section -->
            @if($ropaForm->breach_occurred !== null)
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-exclamation-triangle" ></i>
                        </div>
                        <h4 class="mb-0" >Personal Data Breach</h4>
                    </div>
                </div>
                <div class="card-body">
                    <div class="border-bottom pb-2 mb-2">
                        <small class="text-muted">Breach Occurred</small>
                        <p class="mb-0">{{ $ropaForm->breach_occurred ? 'Yes' : 'No' }}</p>
                    </div>
                    @if($ropaForm->breach_link)
                    <div class="border-bottom pb-2">
                        <small class="text-muted">Breach Link</small>
                        <p class="mb-0"><a href="{{ $ropaForm->breach_link }}" target="_blank" class="text-primary">{{ $ropaForm->breach_link }}</a></p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Compliance Section -->
            <div id="compliance" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-check-double" ></i>
                        </div>
                        <h4 class="mb-0" >Compliance Information</h4>
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
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Retention Policy Link</small>
                                <p class="mb-0">
                                    @if($ropaForm->retention_policy_link)
                                        <a href="{{ $ropaForm->retention_policy_link }}" target="_blank" class="text-primary">{{ $ropaForm->retention_policy_link }}</a>
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Retained Per Policy</small>
                                <p class="mb-0">{{ $ropaForm->retained_per_policy ? 'Yes' : 'No' }}</p>
                            </div>
                        </div>
                        @if($ropaForm->retention_non_adherence_reason)
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted text-danger">Retention Non-Adherence Reason</small>
                                <p class="mb-0">{{ $ropaForm->retention_non_adherence_reason }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sensitive Legal Basis Section -->
            @php $sensitiveBasis = is_array($ropaForm->sensitive_legal_basis) ? $ropaForm->sensitive_legal_basis : json_decode($ropaForm->sensitive_legal_basis ?? '[]', true); @endphp
            @if(!empty($sensitiveBasis))
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
                    <div class="d-flex align-items-center">
                        <div class="rounded-circle p-2 me-2" style="background: var(--accent);">
                            <i class="fas fa-exclamation-triangle" ></i>
                        </div>
                        <h4 class="mb-0" >Sensitive Personal Data Basis</h4>
                    </div>
                </div>
                <div class="card-body">
                    <p class="mb-0">
                        @foreach($sensitiveBasis as $basis)
                            <span class="badge bg-warning me-1">{{ $basis }}</span>
                        @endforeach
                    </p>
                </div>
            </div>
            @endif

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
