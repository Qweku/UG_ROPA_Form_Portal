@extends('layouts.app')

@section('title', 'Manage RoPA Forms')

@section('content')
<div class="container py-4">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div>
                    <h1 class="display-5 fw-bold" style="color: #153d6f;">
                        <i class="fas fa-clipboard-list me-3" style="color: #b69964;"></i>
                        Manage RoPA Forms
                    </h1>
                    <p class="text-muted">Monitor every RoPA process and its sub-processes across the university</p>
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
                        <small class="text-muted">Total Processes</small>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="rounded-circle bg-primary bg-opacity-10 p-3">
                        <i class="fas fa-file-alt fa-2x" style="color: #153d6f;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div>
                        <small class="text-muted">Completed</small>
                        <h3 class="mb-0">{{ $stats['completed'] }}</h3>
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
                        <small class="text-muted">In Progress</small>
                        <h3 class="mb-0">{{ $stats['in_progress'] }}</h3>
                    </div>
                    <div class="rounded-circle bg-warning bg-opacity-10 p-3">
                        <i class="fas fa-spinner fa-2x" style="color: #ffc107;"></i>
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
                    <input type="text" name="search" class="form-control" placeholder="ID, process name, submitter..." value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Created From</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Created To</label>
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
                <table class="table table-hover mb-0">
                    <thead style="background: #f8f9fa;">
                        <tr>
                            <th>ID</th>
                            <th>Submitter</th>
                            <th>Business Function</th>
                            <th>Main Process</th>
                            <th>Sub-processes</th>
                            <th>Status</th>
                            <th>Last Updated</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($forms as $form)
                        <tr>
                            <td class="fw-bold">#{{ $form->id }}</td>
                            <td>
                                <strong>{{ $form->user->firstname ?? '' }} {{ $form->user->surname ?? '' }}</strong><br>
                                <small class="text-muted">{{ $form->user->email ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $form->business_function ?? 'N/A' }}</td>
                            <td>{{ $form->main_process_name ?: 'N/A' }}</td>
                            <td>
                                @php
                                    $completedCount = $form->submissions->where('status', 'completed')->count();
                                    $totalCount = $form->submissions->count();
                                @endphp
                                <span class="badge bg-secondary">{{ $completedCount }} / {{ $totalCount }} completed</span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $form->all_submissions_completed ? 'success' : 'warning' }} px-3 py-2">
                                    <i class="fas fa-{{ $form->all_submissions_completed ? 'check' : 'clock' }} me-1"></i>
                                    {{ $form->all_submissions_completed ? 'Completed' : 'In Progress' }}
                                </span>
                            </td>
                            <td>
                                <small>{{ $form->updated_at->format('M d, Y H:i') }}</small>
                            </td>
                            <td>
                                <a href="{{ route('admin.view-form', $form) }}" class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No RoPA forms found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($forms->isNotEmpty())
            <div class="p-3 bg-light d-flex justify-content-end">
                {{ $forms->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
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
