@extends('layouts.app')

@section('title', 'Manage Submitted Forms')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold" style="color: #153d6f;">
                        <i class="fas fa-clipboard-list me-3" style="color: #b69964;"></i>
                        Manage Submitted Forms
                    </h1>
                    <p class="text-muted">Review, approve, or reject RoPA form submissions</p>
                </div>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-success" onclick="exportForms()">
                        <i class="fas fa-download me-2"></i> Export CSV
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Pending Review</small>
                        <h3 class="mb-0">{{ $stats['submitted'] }}</h3>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-clock fa-2x" style="color: #ffc107;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Approved</small>
                        <h3 class="mb-0">{{ $stats['approved'] }}</h3>
                    </div>
                    <div class="rounded-circle bg-success bg-opacity-10 p-3">
                        <i class="fas fa-check-circle fa-2x" style="color: #28a745;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Rejected</small>
                        <h3 class="mb-0">{{ $stats['rejected'] }}</h3>
                    </div>
                    <div class="rounded-circle bg-danger bg-opacity-10 p-3">
                        <i class="fas fa-times-circle fa-2x" style="color: #dc3545;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.submitted-forms') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" name="search" class="form-control" placeholder="ID, name, email..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Pending Review</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="d-flex gap-2 w-100">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-search me-2"></i> Filter
                        </button>
                        <a href="{{ route('admin.submitted-forms') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-2"></i> Reset
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Forms Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <form id="bulkActionForm" method="POST" action="{{ route('admin.bulk-action') }}">
                    @csrf
                    <table class="table table-hover mb-0">
                        <thead style="background: #f8f9fa;">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>ID</th>
                                <th>Submitter</th>
                                <th>Business Function</th>
                                <th>Processes</th>
                                <th>Status</th>
                                <th>Submitted At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($forms as $form)
                            <tr>
                                <td>
                                    <input type="checkbox" name="form_ids[]" value="{{ $form->id }}" class="form-check-input">
                                </td>
                                <td class="fw-bold">#{{ $form->id }}</td>
                                <td>
                                    <strong>{{ $form->firstname }} {{ $form->surname }}</strong><br>
                                    <small class="text-muted">{{ $form->personnel_id ?? 'No ID' }}</small>
                                </td>
                                <td>{{ $form->business_function ?? 'N/A' }}</td>
                                <td>
                                    @php $processes = json_decode($form->process_names ?? '[]', true); @endphp
                                    <small>{{ implode(', ', array_slice($processes, 0, 2)) }}</small>
                                    @if(count($processes) > 2)
                                        <span class="badge bg-secondary">+{{ count($processes) - 2 }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'submitted' => 'warning',
                                            'approved' => 'success',
                                            'rejected' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$form->status] }} px-3 py-2">
                                        @if($form->status == 'submitted')
                                            <i class="fas fa-clock me-1"></i>
                                        @elseif($form->status == 'approved')
                                            <i class="fas fa-check me-1"></i>
                                        @else
                                            <i class="fas fa-times me-1"></i>
                                        @endif
                                        {{ ucfirst($form->status) }}
                                    </span>
                                </td>
                                <td>
                                    <small>{{ $form->submitted_at ? $form->submitted_at->format('M d, Y H:i') : 'N/A' }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.view-form', $form) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($form->status == 'submitted')
                                            <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#approveModal{{ $form->id }}">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal{{ $form->id }}">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <!-- Approve Modal -->
                                    @if($form->status == 'submitted')
                                    <div class="modal fade" id="approveModal{{ $form->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: #28a745; color: white;">
                                                    <h5 class="modal-title">Approve Form #{{ $form->id }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.approve-form', $form) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p>Are you sure you want to approve this RoPA form?</p>
                                                        <div class="mb-3">
                                                            <label class="form-label">Approval Notes (Optional)</label>
                                                            <textarea name="approval_notes" class="form-control" rows="3" placeholder="Add any notes or comments..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-success">Approve Form</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Reject Modal -->
                                    <div class="modal fade" id="rejectModal{{ $form->id }}" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: #dc3545; color: white;">
                                                    <h5 class="modal-title">Reject Form #{{ $form->id }}</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('admin.reject-form', $form) }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <p class="text-danger">Please provide a reason for rejection:</p>
                                                        <div class="mb-3">
                                                            <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why this form is being rejected..."></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-danger">Reject Form</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No submitted forms found</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    @if($forms->isNotEmpty())
                    <div class="p-3 bg-light d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <select name="bulk_action" class="form-select w-auto" required>
                                <option value="">Bulk Actions</option>
                                <option value="approve">Approve Selected</option>
                                <option value="reject">Reject Selected</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Apply</button>
                        </div>
                        <div>
                            {{ $forms->links() }}
                        </div>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Select All checkbox functionality
    document.getElementById('selectAll')?.addEventListener('change', function(e) {
        document.querySelectorAll('input[name="form_ids[]"]').forEach(checkbox => {
            checkbox.checked = e.target.checked;
        });
    });

    function exportForms() {
        const form = document.createElement('form');
        form.method = 'GET';
        form.action = '{{ route("admin.export-forms") }}';

        // Add current filters
        const search = document.querySelector('input[name="search"]')?.value;
        const status = document.querySelector('select[name="status"]')?.value;
        const dateFrom = document.querySelector('input[name="date_from"]')?.value;
        const dateTo = document.querySelector('input[name="date_to"]')?.value;

        if (search) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'search';
            input.value = search;
            form.appendChild(input);
        }
        if (status && status !== 'all') {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'status';
            input.value = status;
            form.appendChild(input);
        }
        if (dateFrom) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_from';
            input.value = dateFrom;
            form.appendChild(input);
        }
        if (dateTo) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'date_to';
            input.value = dateTo;
            form.appendChild(input);
        }

        document.body.appendChild(form);
        form.submit();
        document.body.removeChild(form);
    }
</script>
@endpush
@endsection
