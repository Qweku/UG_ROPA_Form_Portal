@extends('layouts.app')

@section('title', 'Dashboard - RoPA Forms')

@section('content')
<div class="container">
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12 text-center">
            <h1 style="color: var(--primary); font-weight: 700;" class="display-5 mb-3">
                <i class="fas fa-clipboard-list me-3" style="color: var(--accent);"></i>
                Record of Processing Activities
            </h1>
            <p class="lead text-muted">Manage and track all your data processing activities</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="mb-2">{{ $forms->count() }}</h3>
                <p class="text-muted mb-0">Total Processes</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="mb-2">{{ $forms->where('all_submissions_completed', true)->count() }}</h3>
                <p class="text-muted mb-0">Completed</p>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-spinner"></i>
                </div>
                <h3 class="mb-2">{{ $forms->where('all_submissions_completed', false)->count() }}</h3>
                <p class="text-muted mb-0">In Progress</p>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div class="row mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="col-12 text-end">
            <a href="{{ route('ropa.create') }}" class="btn btn-accent btn-lg">
                <i class="fas fa-plus-circle me-2"></i> Create New RoPA Process
            </a>
        </div>
    </div>

    <!-- Accordion Cards -->
    <div class="row" data-aos="fade-up" data-aos-delay="300">
        <div class="col-12">
            @if($forms->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="lead text-muted">No RoPA processes yet</p>
                    <a href="{{ route('ropa.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Create Your First Process
                    </a>
                </div>
            @else
                @foreach($forms as $form)
                    <div class="card mb-4 shadow-sm">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <div>
                                <strong class="h5">{{ $form->main_process_name }}</strong>
                                <span class="ms-3 text-white small">{{ $form->college?->name ?? 'N/A' }} / {{ $form->business_function }}</span>
                            </div>
                            <span class="badge {{ $form->all_submissions_completed ? 'bg-success' : 'bg-warning' }}">
                                {{ $form->all_submissions_completed ? 'Completed' : 'In Progress' }}
                            </span>
                        </div>
                        <div class="card-body">
                            <div class="accordion" id="accordion{{ $form->id }}">
                                @foreach($form->submissions->where('status', 'completed') as $index => $sub)
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="heading{{ $form->id }}_{{ $index }}">
                                            <button class="accordion-button {{ $index > 0 ? 'collapsed' : '' }}" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $form->id }}_{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse{{ $form->id }}_{{ $index }}">
                                                <i class="fas fa-file me-2"></i>
                                                {{ $sub->sub_process_name ?: 'Main Process' }}
                                                <span class="badge bg-{{ $sub->status == 'completed' ? 'success' : 'warning' }} ms-3">Completed</span>
                                            </button>
                                        </h2>
                                        <div id="collapse{{ $form->id }}_{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" data-bs-parent="#accordion{{ $form->id }}">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <p><strong>Purpose:</strong> {{ $sub->purpose }}</p>
                                                        <p><strong>Legal Basis:</strong> {{ implode(', ', $sub->legal_basis ?? []) }}</p>
                                                        <p><strong>Data Subjects:</strong> {{ implode(', ', $sub->data_subjects ?? []) }}</p>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <p><strong>Personnel:</strong> {{ $sub->firstname }} {{ $sub->surname }} ({{ $sub->personnel_id }})</p>
                                                        <p><strong>Role:</strong> {{ $sub->role_responsible }}</p>
                                                        <p><strong>Completed:</strong> {{ $sub->completed_at ? $sub->completed_at->format('M d, Y H:i') : 'N/A' }}</p>
                                                    </div>
                                                </div>
                                                <a href="{{ route('ropa.view-submission', $sub) }}" class="btn btn-sm btn-info">View Full Details</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                @if($form->submissions->where('status', 'draft')->isNotEmpty())
                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-clock me-2"></i>
                                        You have a draft sub-process in progress.
                                        <a href="{{ route('ropa.edit', ['step' => $form->submissions->where('status', 'draft')->first()->current_step]) }}" class="alert-link">Continue editing</a>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>


@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    // Ensure loading overlay is hidden on page load
    document.addEventListener('DOMContentLoaded', function() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    });

    // Also hide on beforeunload to ensure clean state during navigation
    window.addEventListener('beforeunload', function() {
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
    });

    // Fix for delete confirmation modals:
    // When the user clicks the actual "Delete Forever" button inside the modal,
    // first dismiss the modal, THEN show the loading overlay.
    // This prevents the high-z-index overlay from covering the still-visible modal.
    document.addEventListener('click', function(e) {
        const deleteConfirmBtn = e.target.closest('.modal .btn-danger');
        if (!deleteConfirmBtn) return;

        // Find the parent modal
        const modalEl = deleteConfirmBtn.closest('.modal');
        if (modalEl) {
            // Hide the modal immediately using Bootstrap API
            const modalInstance = bootstrap.Modal.getInstance(modalEl);
            if (modalInstance) {
                modalInstance.hide();
            } else {
                // Fallback
                modalEl.classList.remove('show');
                modalEl.style.display = 'none';
                document.body.classList.remove('modal-open');
                const backdrops = document.querySelectorAll('.modal-backdrop');
                backdrops.forEach(b => b.remove());
            }
        }

        // Now show the loading overlay (after modal is dismissed)
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) {
            overlay.style.display = 'flex';
        }

        // The form will continue to submit naturally
    });
</script>
@endpush
@endsection
