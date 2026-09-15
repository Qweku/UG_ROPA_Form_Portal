@extends('layouts.app')

@section('title', 'Page Not Found')

@section('content')
<div class="error-page-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6 text-center">

                <div class="error-card">
                    @include('errors.partials.error-illustration', ['code' => '404', 'stampColor' => 'var(--accent)', 'stampDark' => 'var(--accent-dark)'])

                    <p class="error-eyebrow">Record not found</p>
                    <h1 class="error-heading">This page isn't on file.</h1>
                    <p class="error-body">
                        The page you're looking for may have been moved, renamed, or never existed.
                        Check the link, or head back to your dashboard to find what you need.
                    </p>

                    <div class="error-actions">
                        <a href="{{ url('/') }}" class="btn btn-accent btn-lg">
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </a>
                        <button onclick="history.back()" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-arrow-left me-2"></i> Go Back
                        </button>
                    </div>

                    <p class="error-code-tag">Error 404 &middot; Not Found</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@include('errors.partials.error-page-styles')
@endpush
