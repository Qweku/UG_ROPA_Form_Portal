@extends('layouts.app')

@section('title', 'View Form #' . $ropaForm->id)

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin Dashboard</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('admin.submitted-forms') }}">Submitted Forms</a></li>
                            <li class="breadcrumb-item active">Form #{{ $ropaForm->id }}</li>
                        </ol>
                    </nav>
                </div>
                <div class="d-flex gap-2">
                    <button onclick="window.print()" class="btn btn-outline-primary">
                        <i class="fas fa-print me-2"></i> Print
                    </button>
                    <a href="{{ route('admin.submitted-forms') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i> Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Banner -->
    <div class="row mb-4">
        <div class="col-12">
            @php
                $statusInfo = [
                    'submitted' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Pending Review'],
                    'approved' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Approved'],
                    'rejected' => ['class' => 'danger', 'icon' => 'times-circle', 'text' => 'Rejected']
                ][$ropaForm->status];
            @endphp
            <div class="alert alert-{{ $statusInfo['class'] }} border-0 shadow-sm">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <i class="fas fa-{{ $statusInfo['icon'] }} fa-2x me-3"></i>
                        <strong>Form Status: {{ $statusInfo['text'] }}</strong>
                        @if($ropaForm->submitted_at)
                            <br><small>Submitted: {{ $ropaForm->submitted_at->format('F d, Y H:i:s') }}</small>
                        @endif
                    </div>
                    @if($ropaForm->status == 'submitted')
                    <div>
                        <button class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#approveModal">
                            <i class="fas fa-check me-2"></i> Approve
                        </button>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fas fa-times me-2"></i> Reject
                        </button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Approve Modal -->
    <div class="modal fade" id="approveModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #28a745; color: white;">
                    <h5 class="modal-title">Approve Form #{{ $ropaForm->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.approve-form', $ropaForm) }}" method="POST">
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
    <div class="modal fade" id="rejectModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background: #dc3545; color: white;">
                    <h5 class="modal-title">Reject Form #{{ $ropaForm->id }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('admin.reject-form', $ropaForm) }}" method="POST">
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

    <!-- Form Details -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    @include('ropa.view-submitted', ['ropaForm' => $ropaForm])
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
