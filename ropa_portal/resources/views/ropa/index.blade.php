@extends('layouts.app')

@section('title', 'Dashboard - RoPA Forms')

@section('content')
<div class="container">
    <!-- Welcome Section -->
    <div class="row mb-5" data-aos="fade-up">
        <div class="col-12 text-center">
            <h1 style="color: var(--primary); font-weight: 700;" class="display-5 mb-3">
                <i class="fas fa-clipboard-list me-3" style="color: var(--accent);"></i>
                Record of Processing Activities
            </h1>
            <p class="lead text-muted">Manage and track all your data processing activities in one place</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5" data-aos="fade-up" data-aos-delay="100">
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <h3 class="mb-2">{{ $forms->count() }}</h3>
                <p class="text-muted mb-0">Total Forms</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-pen"></i>
                </div>
                <h3 class="mb-2">{{ $forms->where('status', 'draft')->count() }}</h3>
                <p class="text-muted mb-0">Draft Forms</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <h3 class="mb-2">{{ $forms->where('status', 'submitted')->count() }}</h3>
                <p class="text-muted mb-0">Submitted</p>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="mb-2">{{ $forms->where('status', 'approved')->count() }}</h3>
                <p class="text-muted mb-0">Approved</p>
            </div>
        </div>
    </div>

    <!-- Action Button -->
    <div class="row mb-4" data-aos="fade-up" data-aos-delay="200">
        <div class="col-12 text-end">
            <a href="{{ route('ropa.create') }}" class="btn btn-accent btn-lg">
                <i class="fas fa-plus-circle me-2"></i> Create New RoPA Form
            </a>
        </div>
    </div>

    <!-- Forms Table -->
    <div class="row" data-aos="fade-up" data-aos-delay="300">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-table me-2"></i> Your RoPA Forms
                    </h4>
                </div>
                <div class="card-body p-0">
                    @if($forms->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="lead text-muted">No RoPA forms yet</p>
                        <a href="{{ route('ropa.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i> Create Your First Form
                        </a>
                    </div>
                    @else
                    <div class="table-responsive p-2">
                        <table class="table table-hover mb-0">
                            <thead class="table-primary">
                                <tr>
                                    <th class="border-0">ID</th>
                                    <th class="border-0">Process/Project</th>
                                    <th class="border-0">Last Updated</th>
                                    <th class="border-0">Status</th>
                                    <th class="border-0">Progress</th>
                                    <th class="border-0">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($forms as $form)
                                <tr>
                                    <td class="align-middle">#{{ $form->id }}</td>
                                    <td class="align-middle">
                                        <strong>{{ implode(', ', array_slice($form->process_names ?? ['Untitled'], 0, 2)) }}</strong>
                                        @if(count($form->process_names ?? []) > 2)
                                        <span class="badge bg-secondary ms-1">+{{ count($form->process_names) - 2 }}</span>
                                        @endif
                                    </td>
                                    <td class="align-middle">
                                        <i class="far fa-calendar-alt me-1 text-muted"></i>
                                        {{ $form->updated_at->format('M d, Y') }}
                                    </td>
                                    <td class="align-middle">
                                        @php
                                        $statusColors = [
                                        'draft' => 'warning',
                                        'submitted' => 'info',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$form->status] ?? 'secondary' }} px-3 py-2">
                                            <i class="fas fa-{{ $form->status === 'draft' ? 'pen' : ($form->status === 'submitted' ? 'clock' : 'check') }} me-1"></i>
                                            {{ ucfirst($form->status) }}
                                        </span>
                                    </td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar"
                                                    role="progressbar" aria-valuenow="{{ $form->current_step }}" aria-valuemin="0" aria-valuemax="14"
                                                    @style([ 'width: ' . (($form->current_step / 14) * 100) . '%',
                                                    'background: linear-gradient(90deg, var(--primary), var(--accent))',
                                                    ])>
                                                </div>

                                            </div>
                                        </div>
                                        <small class="text-muted">{{ $form->current_step }}/14</small>
                    </div>
                    </td>
                    <td class="align-middle">
                        <div class="btn-group" role="group">
                            <a href="{{ route('ropa.edit', $form) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="Continue Editing">
                                <i class="fas fa-edit"></i>
                            </a>
                            <a href="{{ route('ropa.show', $form) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="View Details">
                                <i class="fas fa-eye"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $form->id }}">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>

                        <!-- Delete Modal -->
                        <div class="modal fade" id="deleteModal{{ $form->id }}" tabindex="-1">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: var(--primary); color: white;">
                                        <h5 class="modal-title">Confirm Delete</h5>
                                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure you want to delete this RoPA form?</p>
                                        <p class="text-muted small">This action cannot be undone.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                         <form action="{{ route('ropa.destroy', $form) }}" method="POST" class="d-inline delete-form">
                                             @csrf @method('DELETE')
                                             <button type="submit" class="btn btn-danger">Delete Forever</button>
                                         </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                    </tr>
                    @endforeach
                    </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
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
