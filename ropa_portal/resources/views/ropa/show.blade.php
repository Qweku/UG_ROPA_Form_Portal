div@extends('layouts.app')

@section('title', 'RoPA Form #' . $ropaForm->id)

@section('content')
<div class="container" data-aos="fade-up">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('ropa.index') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Form #{{ $ropaForm->id }}</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-4" data-aos="fade-right">
            <div class="card sticky-top" style="top: 100px;">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="stat-icon mx-auto mb-3">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                        <h5>Form #{{ $ropaForm->id }}</h5>
                        <span class="badge bg-{{ $ropaForm->status === 'submitted' ? 'success' : 'warning' }} px-3 py-2">
                            {{ strtoupper($ropaForm->status) }}
                        </span>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted">Created</small>
                        <p class="mb-0">{{ $ropaForm->created_at->format('F d, Y H:i') }}</p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Last Updated</small>
                        <p class="mb-0">{{ $ropaForm->updated_at->diffForHumans() }}</p>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted">Progress</small>
                        <div class="progress mt-1" style="height: 8px;">
                            <div class="progress-bar"
                                role="progressbar"
                                @style([ 'width: ' . (($ropaForm->current_step / 14) * 100) . '%',
                                'background: linear-gradient(90deg, var(--primary), var(--accent))',
                                ])>
                            </div>
                        </div>
                        <small class="text-muted">Step {{ $ropaForm->current_step }}/14</small>
                    </div>

                    <hr>

                    <div class="d-grid gap-2">
                        @if($ropaForm->status === 'draft')
                        <a href="{{ route('ropa.edit', $ropaForm) }}" class="btn btn-primary">
                            <i class="fas fa-edit me-2"></i> Continue Editing
                        </a>
                        @endif
                        <button onclick="window.print()" class="btn btn-outline-secondary">
                            <i class="fas fa-print me-2"></i> Print Report
                        </button>
                        <a href="{{ route('ropa.index') }}" class="btn btn-outline-primary">
                            <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-9" data-aos="fade-left">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-clipboard-list me-2"></i>
                        Record of Processing Activities (RoPA)
                    </h4>
                </div>
                <div class="card-body">
                    <!-- Basic Information -->
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2" style="color: var(--primary);">
                            <i class="fas fa-info-circle me-2"></i> Basic Information
                        </h5>
                        <div class="row mt-3">
                            <div class="col-md-6">
                                <small class="text-muted">Personnel ID</small>
                                <p><strong>{{ $ropaForm->personnel_id ?? 'N/A' }}</strong></p>
                            </div>
                            <div class="col-md-6">
                                <small class="text-muted">Name</small>
                                <p><strong>{{ $ropaForm->surname ?? '' }} {{ $ropaForm->firstname ?? '' }}</strong></p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Business Function</small>
                                <p><strong>{{ $ropaForm->business_function ?? 'N/A' }}</strong></p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Process / Project Names</small>
                                <p>
                                    @php $processes = is_array($ropaForm->process_names) ? $ropaForm->process_names : json_decode($ropaForm->process_names ?? '[]', true); @endphp
                                    @foreach($processes as $process)
                                    <span class="badge bg-primary me-1">{{ $process }}</span>
                                    @endforeach
                                </p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Purpose</small>
                                <p>{{ $ropaForm->purpose ?? 'N/A' }}</p>
                            </div>
                            <div class="col-12">
                                <small class="text-muted">Role Responsible</small>
                                <p>{{ $ropaForm->role_responsible ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Data Categories -->
                    @if($ropaForm->data_subjects || $ropaForm->personal_data_categories)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2" style="color: var(--primary);">
                            <i class="fas fa-database me-2"></i> Data Categories & Subjects
                        </h5>
                        <div class="row mt-3">
                            @if($ropaForm->data_subjects)
                            <div class="col-12">
                                <small class="text-muted">Data Subjects</small>
                                <p>
                                    @php $subjects = is_array($ropaForm->data_subjects) ? $ropaForm->data_subjects : json_decode($ropaForm->data_subjects ?? '[]', true); @endphp
                                    @foreach($subjects as $subject)
                                    <span class="badge bg-info me-1">{{ $subject }}</span>
                                    @endforeach
                                </p>
                            </div>
                            @endif
                            @if($ropaForm->personal_data_categories)
                            <div class="col-12">
                                <small class="text-muted">Personal Data Categories</small>
                                <p>
                                    @php $categories = is_array($ropaForm->personal_data_categories) ? $ropaForm->personal_data_categories : json_decode($ropaForm->personal_data_categories ?? '[]', true); @endphp
                                    @foreach($categories as $category)
                                    <span class="badge bg-warning me-1">{{ $category }}</span>
                                    @endforeach
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Legal Basis -->
                    @if($ropaForm->legal_basis)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2" style="color: var(--primary);">
                            <i class="fas fa-gavel me-2"></i> Legal Basis & Compliance
                        </h5>
                        <div class="row mt-3">
                            <div class="col-12">
                                <small class="text-muted">Legal Basis</small>
                                <p>
                                    @php $basis = is_array($ropaForm->legal_basis) ? $ropaForm->legal_basis : json_decode($ropaForm->legal_basis ?? '[]', true); @endphp
                                    @foreach($basis as $item)
                                    <span class="badge bg-success me-1">{{ $item }}</span>
                                    @endforeach
                                </p>
                            </div>
                            @if($ropaForm->retention_period)
                            <div class="col-12">
                                <small class="text-muted">Retention Period</small>
                                <p>{{ $ropaForm->retention_period }}</p>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Security Measures -->
                    @if($ropaForm->security_measures)
                    <div class="mb-4">
                        <h5 class="border-bottom pb-2" style="color: var(--primary);">
                            <i class="fas fa-shield-alt me-2"></i> Security Measures
                        </h5>
                        <div class="mt-3">
                            <p>{{ $ropaForm->security_measures }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Submission Info -->
                    @if($ropaForm->submitted_at)
                    <div class="alert alert-success">
                        <i class="fas fa-check-circle me-2"></i>
                        <strong>Submitted on:</strong> {{ $ropaForm->submitted_at->format('F d, Y H:i:s') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style media="print">
    .navbar,
    .footer,
    .breadcrumb,
    .col-md-3,
    .btn,
    .alert {
        display: none !important;
    }

    .col-md-9 {
        width: 100% !important;
    }

    .card {
        box-shadow: none !important;
    }

    body {
        background: white !important;
    }
</style>
@endsection
