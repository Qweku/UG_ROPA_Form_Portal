@extends('layouts.app')

@section('title', 'Access Denied')

@section('content')
<div class="error-page-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6 text-center">

                <div class="error-card">
                    @include('errors.partials.error-illustration', ['code' => '403', 'stampColor' => '#c0392b', 'stampDark' => '#962d22'])

                    <p class="error-eyebrow" style="color: #c0392b;">Access restricted</p>
                    <h1 class="error-heading">This record isn't yours to view.</h1>
                    <p class="error-body">
                        You don't have permission to access this page or record. If you believe
                        this is a mistake, contact your administrator, or return to your dashboard.
                    </p>

                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn btn-accent btn-lg">
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i> Go Back
                        </button>
                    </div>

                    <p class="error-code-tag">Error 403 &middot; Forbidden</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@include('errors.partials.error-page-styles')
@endpush
