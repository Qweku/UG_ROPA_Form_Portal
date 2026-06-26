@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">

            <div class="card shadow mb-4">
                <div class="card-body text-center">
                    <i class="fas fa-check-circle text-success fa-2x mb-3"></i>
                    <h3>Sub‑process Completed</h3>
                    <p class="mb-1">
                        You have finished filling in data for
                        <strong>"{{ $currentSubmission->sub_process_name ?? $parentForm->main_process_name }}"</strong>.
                    </p>
                    <p class="text-muted mb-4">
                        {{ $completedSubmissions->count() }}
                        {{ Str::plural('sub‑process', $completedSubmissions->count()) }}
                        completed so far for <strong>{{ $parentForm->main_process_name }}</strong>.
                    </p>

                    <div class="mt-3">
                        <p class="fw-bold mb-3">Does this process have another sub‑process to add?</p>
                        <a href="{{ route('ropa.add-sub-process', $parentForm) }}" class="btn btn-primary px-4 me-2">
                            <i class="fas fa-plus me-2"></i> Yes, add another sub‑process
                        </a>
                        <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#confirmFinalizeModal">
                            <i class="fas fa-check me-2"></i> No, submit everything
                        </button>
                    </div>
                </div>
            </div>

            {{-- Accordion review of everything saved so far for this RoPA form --}}
            <div class="card shadow">
                <div class="card-header">
                    <i class="fas fa-list me-2"></i>
                    <strong>Review: {{ $parentForm->main_process_name }}</strong>
                    <span class="text-muted small ms-2">({{ $parentForm->college->name ?? 'No college' }} &middot; {{ $parentForm->business_function }})</span>
                </div>
                <div class="card-body">
                    <div class="accordion" id="subProcessAccordion">
                        @foreach($completedSubmissions as $index => $sub)
                            @php $panelId = 'subProcess' . $sub->id; @endphp
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button {{ $loop->last ? '' : 'collapsed' }}" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#{{ $panelId }}"
                                            aria-expanded="{{ $loop->last ? 'true' : 'false' }}" aria-controls="{{ $panelId }}">
                                        <i class="fas fa-folder-open me-2"></i>
                                        {{ $sub->sub_process_name ?? ('Sub-process ' . ($index + 1)) }}
                                        <span class="badge bg-success ms-3">Completed</span>
                                    </button>
                                </h2>
                                <div id="{{ $panelId }}" class="accordion-collapse collapse {{ $loop->last ? 'show' : '' }}"
                                     data-bs-parent="#subProcessAccordion">
                                    <div class="accordion-body">
                                        @forelse($sub->displayFields() as $groupLabel => $fields)
                                            <div class="mb-3">
                                                <h6 class="text-uppercase text-muted small fw-bold mb-2">{{ $groupLabel }}</h6>
                                                <dl class="row mb-0">
                                                    @foreach($fields as $label => $value)
                                                        <dt class="col-sm-4">{{ $label }}</dt>
                                                        <dd class="col-sm-8">{{ $value }}</dd>
                                                    @endforeach
                                                </dl>
                                            </div>
                                            @if(!$loop->last)
                                                <hr class="my-2">
                                            @endif
                                        @empty
                                            <p class="text-muted mb-0">No additional details recorded.</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

{{-- Final submit confirmation --}}
<div class="modal fade" id="confirmFinalizeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Submit RoPA Form?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>You've confirmed there are no more sub‑processes for <strong>{{ $parentForm->main_process_name }}</strong>.</p>
                <p class="mb-0">All {{ $completedSubmissions->count() }} sub‑process(es) above will be submitted as final. You won't be able to make further changes after this.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Go back</button>
                <form method="POST" action="{{ route('ropa.finalize', $parentForm) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i> Yes, submit everything
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
