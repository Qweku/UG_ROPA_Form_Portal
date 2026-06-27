@extends('layouts.app')

@section('title', 'Something Went Wrong')

@section('content')
<div class="error-page-wrap">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-xl-6 text-center">

                <div class="error-card">
                    @include('errors.partials.error-illustration', ['code' => '500', 'stampColor' => 'var(--primary)', 'stampDark' => 'var(--primary-dark)'])

                    <p class="error-eyebrow">System error</p>
                    <h1 class="error-heading">Something broke on our end.</h1>
                    <p class="error-body">
                        We hit an unexpected error processing your request. Nothing on your end caused this &mdash;
                        try again in a moment, and if it keeps happening, let your administrator know.
                    </p>

                    <div class="error-actions">
                        <button onclick="location.reload()" class="btn btn-accent btn-lg">
                            <i class="fas fa-redo me-2"></i> Try Again
                        </button>
                        <a href="{{ url('/') }}" class="btn btn-outline-primary btn-lg">
                            <i class="fas fa-home me-2"></i> Back to Dashboard
                        </a>
                    </div>

                    <p class="error-code-tag">Error 500 &middot; Internal Server Error</p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
@include('errors.partials.error-page-styles')
@endpush
