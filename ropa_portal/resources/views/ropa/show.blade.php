{{-- File: resources/views/ropa/show.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="card shadow-lg border-0 rounded-4">
        <div class="card-header" style="background: #153d6f; color: white;">
            <h2 class="mb-0">RoPA Form Summary #{{ $ropaForm->id }}</h2>
            <p class="mb-0">Status: <span class="badge bg-{{ $ropaForm->status === 'submitted' ? 'success' : 'warning' }}">{{ strtoupper($ropaForm->status) }}</span></p>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6"><strong>Personnel:</strong> {{ $ropaForm->surname ?? 'N/A' }}, {{ $ropaForm->firstname ?? 'N/A' }} (ID: {{ $ropaForm->personnel_id ?? 'N/A' }})</div>
                <div class="col-md-6"><strong>Business Function:</strong> {{ $ropaForm->business_function ?? 'N/A' }}</div>
                <div class="col-12 mt-2"><strong>Processes:</strong> {{ implode(', ', $ropaForm->process_names ?? ['None']) }}</div>
                <div class="col-12 mt-2"><strong>Purpose:</strong> {{ $ropaForm->purpose ?? 'Not specified' }}</div>
                <div class="col-12 mt-2"><strong>Legal Basis:</strong> {{ implode(', ', $ropaForm->legal_basis ?? ['None']) }}</div>
                <div class="col-12 mt-2"><strong>Security Measures:</strong> {{ $ropaForm->security_measures ?? 'Not specified' }}</div>
                <div class="col-12 mt-2"><strong>Submitted:</strong> {{ $ropaForm->submitted_at ? $ropaForm->submitted_at->format('F d, Y H:i') : 'Not submitted' }}</div>
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('ropa.edit', $ropaForm) }}" class="btn" style="background: #b69964; color: white;">Edit Form</a>
            <a href="{{ route('ropa.index') }}" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
</div>
@endsection
