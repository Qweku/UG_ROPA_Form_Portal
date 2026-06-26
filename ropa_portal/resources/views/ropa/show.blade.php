@extends('layouts.app')

@section('title', 'Submission #' . $submission->id . ' - ' . ($submission->sub_process_name ?? 'Main Process'))

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
                            <!-- Add more links as needed -->
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
            <!-- All sections displaying submission fields -->
            <!-- Use the same partials as in show.blade.php but replace $ropaForm with $submission -->
            <!-- For brevity, I'll show just the basic info section as an example -->
            <div id="basic-info" class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0 pt-4 pb-2">
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
                                <p class="mb-0 fw-bold">{{ $submission->personnel_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Surname</small>
                                <p class="mb-0 fw-bold">{{ $submission->surname ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Firstname</small>
                                <p class="mb-0 fw-bold">{{ $submission->firstname ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Purpose</small>
                                <p class="mb-0">{{ $submission->purpose ?? 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="border-bottom pb-2">
                                <small class="text-muted">Role Responsible</small>
                                <p class="mb-0 fw-bold">{{ $submission->role_responsible ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Repeat for other sections using $submission -->
        </div>
    </div>
</div>

<style media="print">
    /* Print styles */
    .navbar,
    .btn,
    .breadcrumb,
    .sticky-top,
    .alert-success {
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
    });
</script>
@endpush
@endsection
