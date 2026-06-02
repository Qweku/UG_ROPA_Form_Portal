@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="display-5 fw-bold" style="color: #153d6f;">
                <i class="fas fa-chart-line me-3" style="color: #b69964;"></i>
                Admin Dashboard
            </h1>
            <p class="text-muted">Welcome back, {{ Auth::user()->firstname }} {{ Auth::user()->surname }}! Here's an overview of the RoPA system.</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-5">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-file-alt fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $stats['total_forms'] }}</h3>
                    <p class="mb-0" style="opacity: 0.9;">Total Forms</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $stats['submitted_forms'] }}</h3>
                    <p class="mb-0" style="opacity: 0.9;">Pending Review</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $stats['approved_forms'] }}</h3>
                    <p class="mb-0" style="opacity: 0.9;">Approved</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100 text-white" style="background: linear-gradient(135deg, #fa709a 0%, #fee140 100%); border-radius: 20px;">
                <div class="card-body text-center">
                    <div class="rounded-circle bg-white bg-opacity-25 d-inline-flex p-3 mb-3">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h3 class="mb-0">{{ $stats['total_users'] }}</h3>
                    <p class="mb-0" style="opacity: 0.9;">Registered Users</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Secondary Stats -->
    <div class="row mb-5">
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2" style="color: #b69964;"></i>
                        Form Status Distribution
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart" height="100"></canvas>
                    <div class="row mt-3 text-center">
                        <div class="col-3">
                            <div class="small text-muted">Submitted</div>
                            <strong>{{ $stats['submitted_forms'] }}</strong>
                        </div>
                        <div class="col-3">
                            <div class="small text-muted">Approved</div>
                            <strong>{{ $stats['approved_forms'] }}</strong>
                        </div>
                        <div class="col-3">
                            <div class="small text-muted">Rejected</div>
                            <strong>{{ $stats['rejected_forms'] }}</strong>
                        </div>
                        <div class="col-3">
                            <div class="small text-muted">Draft</div>
                            <strong>{{ $stats['draft_forms'] }}</strong>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-calendar-alt me-2" style="color: #b69964;"></i>
                        Recent Activity
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <small class="text-muted">Forms submitted this month</small>
                        <h3 class="mb-0">{{ $stats['forms_this_month'] }}</h3>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">Active users (verified)</small>
                        <h3 class="mb-0">{{ $stats['active_users'] }}</h3>
                    </div>
                    <div class="progress mb-3" style="height: 10px;">
                        @php $verificationRate = $stats['total_users'] > 0 ? ($stats['active_users'] / $stats['total_users']) * 100 : 0; @endphp
                        <div class="progress-bar" role="progressbar" style="width: {{ $verificationRate }}%; background: #b69964;"></div>
                    </div>
                    <small class="text-muted">{{ round($verificationRate) }}% email verification rate</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Forms -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list me-2" style="color: #b69964;"></i>
                        Recent Submissions
                    </h5>
                    <a href="{{ route('admin.submitted-forms') }}" class="btn btn-sm btn-outline-accent">
                        View All <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th>ID</th>
                                    <th>Submitter</th>
                                    <th>Business Function</th>
                                    <th>Status</th>
                                    <th>Submitted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentForms as $form)
                                <tr>
                                    <td>#{{ $form->id }}</td>
                                    <td>
                                        <strong>{{ $form->firstname }} {{ $form->surname }}</strong><br>
                                        <small class="text-muted">{{ $form->user->email ?? 'N/A' }}</small>
                                    </td>
                                    <td>{{ $form->business_function ?? 'N/A' }}</td>
                                    <td>
                                        @php
                                        $statusColors = [
                                        'draft' => 'secondary',
                                        'submitted' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger'
                                        ];
                                        @endphp
                                        <span class="badge bg-{{ $statusColors[$form->status] }}">
                                            {{ ucfirst($form->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $form->submitted_at ? $form->submitted_at->diffForHumans() : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('admin.view-form', $form) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">No forms found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Users -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pt-4">
                    <h5 class="mb-0">
                        <i class="fas fa-user-plus me-2" style="color: #b69964;"></i>
                        New Users
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background: #f8f9fa;">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Registered</th>
                                    <th>Verified</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentUsers as $user)
                                @if($user->role === 'admin' )
                                @continue
                                <tr>
                                    <td colspan="6" class="text-center py-4">No users found</td>
                                </tr>

                                @endif
                                <tr>
                                    <td>{{ $user->firstname }} {{ $user->surname }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->created_at->diffForHumans() }}</td>
                                    <td>
                                        @if($user->is_verified)
                                        <span class="badge bg-success">Verified</span>
                                        @else
                                        <span class="badge bg-secondary">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">No users found</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const chartCanvas = document.getElementById('statusChart');

        if (!chartCanvas) {
            console.warn('statusChart canvas not found');
            return;
        }

        if (typeof Chart === 'undefined') {
            console.error('Chart.js failed to load');
            return;
        }

        const stats = {
            submitted_forms: {
                {
                    $stats['submitted_forms'] ?? 0
                }
            },
            approved_forms: {
                {
                    $stats['approved_forms'] ?? 0
                }
            },
            rejected_forms: {
                {
                    $stats['rejected_forms'] ?? 0
                }
            },
            draft_forms: {
                {
                    $stats['draft_forms'] ?? 0
                }
            },
        };

        const ctx = chartCanvas.getContext('2d');

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: [
                    'Submitted',
                    'Approved',
                    'Rejected',
                    'Draft'
                ],
                datasets: [{
                    data: [
                        stats.submitted_forms,
                        stats.approved_forms,
                        stats.rejected_forms,
                        stats.draft_forms
                    ],
                    backgroundColor: [
                        '#ffc107',
                        '#28a745',
                        '#dc3545',
                        '#6c757d'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
