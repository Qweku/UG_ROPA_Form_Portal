@extends('layouts.app')

@section('title', 'View RoPA Form #' . $ropaForm->id)

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.submitted-forms') }}">Manage RoPA Forms</a></li>
                            <li class="breadcrumb-item active">Form #{{ $ropaForm->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i> Print
                    </button>
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="alert alert-{{ $ropaForm->all_submissions_completed ? 'success' : 'warning' }} border-0 shadow-sm">
                <div class="d-flex align-items-center">
                    <i class="fas fa-{{ $ropaForm->all_submissions_completed ? 'check-circle' : 'clock' }} fa-2x me-3"></i>
                    <div>
                        <strong>
                            {{ $ropaForm->all_submissions_completed ? 'All sub-processes completed' : 'In progress' }}
                        </strong>
                        <br>
                        <small>
                            {{ $ropaForm->submissions->where('status', 'completed')->count() }} of {{ $ropaForm->submissions->count() }} sub-processes completed
                            &middot; Last updated {{ $ropaForm->updated_at->diffForHumans() }}
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Overview -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2" style="color: #b69964;"></i>
                        Process Overview
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <small class="text-muted">Main Process Name</small>
                            <p class="mb-0 fw-bold">{{ $ropaForm->main_process_name ?: 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">College</small>
                            <p class="mb-0 fw-bold">{{ $ropaForm->college->name ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Business Function / School</small>
                            <p class="mb-0 fw-bold">{{ $ropaForm->business_function ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Submitted By</small>
                            <p class="mb-0 fw-bold">{{ $ropaForm->user->firstname ?? '' }} {{ $ropaForm->user->surname ?? '' }}</p>
                            <small class="text-muted">{{ $ropaForm->user->email ?? 'N/A' }}</small>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Created</small>
                            <p class="mb-0 fw-bold">{{ $ropaForm->created_at->format('M d, Y H:i') }}</p>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted">Has Sub-processes?</small>
                            <p class="mb-0 fw-bold">{{ $ropaForm->has_sub_processes ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sub-process Submissions Accordion -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-layer-group me-2" style="color: #b69964;"></i>
                        Sub-processes ({{ $ropaForm->submissions->count() }})
                    </h5>
                </div>
                <div class="card-body">
                    @if($ropaForm->submissions->isEmpty())
                        <p class="text-muted text-center py-4 mb-0">No sub-processes have been added to this form yet.</p>
                    @else
                        <div class="accordion" id="adminAccordion{{ $ropaForm->id }}">
                            @foreach($ropaForm->submissions as $index => $sub)
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="adminHeading{{ $ropaForm->id }}_{{ $index }}">
                                        <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#adminCollapse{{ $ropaForm->id }}_{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="adminCollapse{{ $ropaForm->id }}_{{ $index }}">
                                            <i class="fas fa-file me-2"></i>
                                            {{ $sub->sub_process_name ?: 'Main Process' }}
                                            <span class="badge bg-{{ $sub->status == 'completed' ? 'success' : 'warning' }} ms-3">
                                                {{ ucfirst($sub->status) }}
                                            </span>
                                        </button>
                                    </h2>
                                    <div id="adminCollapse{{ $ropaForm->id }}_{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#adminAccordion{{ $ropaForm->id }}">
<div class="accordion-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <p><strong>Purpose:</strong> {{ $sub->purpose ?? 'N/A' }}</p>
                                                    <p><strong>Legal Basis:</strong> {{ implode(', ', $sub->legal_basis ?? []) ?: 'N/A' }}</p>
                                                    <p><strong>Data Subjects:</strong> {{ implode(', ', $sub->data_subjects ?? []) ?: 'N/A' }}</p>
                                                </div>
                                                <div class="col-md-6">
                                                    <p><strong>Personnel:</strong> {{ $ropaForm->firstname ?? '' }} {{ $ropaForm->surname ?? '' }} ({{ $ropaForm->personnel_id ?? 'N/A' }})</p>
                                                    <p><strong>Role:</strong> {{ $ropaForm->role_responsible ?? 'N/A' }}</p>
                                                    <p><strong>Completed:</strong> {{ $sub->completed_at ? $sub->completed_at->format('M d, Y H:i') : 'N/A' }}</p>
                                                </div>
                                            </div>
                                            @if($sub->status === 'completed')
                                                <a href="{{ route('ropa.view-submission', $sub) }}" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye me-1"></i> View Full Details
                                                </a>
                                            @else
                                                <span class="badge bg-secondary">
                                                    <i class="fas fa-clock me-1"></i> Still in progress &mdash; currently on step {{ $sub->current_step }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
