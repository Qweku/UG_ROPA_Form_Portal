<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-exchange-alt fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">External Sharing & Recipients</h5>
                <p class="mb-0 text-muted small">Capture sharing with third parties, service providers, and other external organizations.</p>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                        <i class="fas fa-share-square" style="color: #153d6f;"></i>
                    </div>
                    <h5 class="card-title mb-0" style="color: #153d6f;">External Recipients</h5>
                </div>
                <button type="button" class="btn btn-sm btn-outline-primary" id="add-recipient">
                    <i class="fas fa-plus me-1"></i> Add Recipient
                </button>
            </div>

            <div id="external-recipients-container">
                @php
                    $recipients = [];
                    if ($submission->external_recipients) {
                        if (is_array($submission->external_recipients)) {
                            $recipients = $submission->external_recipients;
                        } elseif (is_string($submission->external_recipients)) {
                            $recipients = json_decode($submission->external_recipients, true) ?? [];
                        }
                    }
                @endphp

                @forelse($recipients as $idx => $recipient)
                <div class="recipient-card card mb-3 border">
                    <div class="card-body position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-recipient"></button>
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-building me-1" style="color: #b69964;"></i> Recipient Name
                                </label>
                                <input type="text" name="external_recipients[{{ $idx }}][name]" class="form-control"
                                       value="{{ $recipient['name'] ?? '' }}" placeholder="e.g., IT system supplier, Cloud provider">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-tag me-1" style="color: #b69964;"></i> Type of Recipient
                                </label>
                                <select name="external_recipients[{{ $idx }}][type]" class="form-select">
                                    <option value="Public authority" {{ ($recipient['type'] ?? '') == 'Public authority' ? 'selected' : '' }}>🏛️ Public authority</option>
                                    <option value="Service provider" {{ ($recipient['type'] ?? '') == 'Service provider' ? 'selected' : '' }}>💼 Service provider</option>
                                    <option value="Commercial partner" {{ ($recipient['type'] ?? '') == 'Commercial partner' ? 'selected' : '' }}>🤝 Commercial partner</option>
                                    <option value="Research collaborator" {{ ($recipient['type'] ?? '') == 'Research collaborator' ? 'selected' : '' }}>🔬 Research collaborator</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-handshake me-1" style="color: #b69964;"></i> Relationship Type
                                </label>
                                <select name="external_recipients[{{ $idx }}][relationship]" class="form-select">
                                    <option value="Controller" {{ ($recipient['relationship'] ?? '') == 'Controller' ? 'selected' : '' }}>Controller</option>
                                    <option value="Processor" {{ ($recipient['relationship'] ?? '') == 'Processor' ? 'selected' : '' }}>Processor</option>
                                    <option value="Joint Controller" {{ ($recipient['relationship'] ?? '') == 'Joint Controller' ? 'selected' : '' }}>Joint Controller</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">
                                    <i class="fas fa-file-signature me-1" style="color: #b69964;"></i> Contract in Place?
                                </label>
                                <select name="external_recipients[{{ $idx }}][contract]" class="form-select">
                                    <option value="yes" {{ ($recipient['contract'] ?? '') == 'yes' ? 'selected' : '' }}>✅ Yes</option>
                                    <option value="no" {{ ($recipient['contract'] ?? '') == 'no' ? 'selected' : '' }}>❌ No</option>
                                    <option value="na" {{ ($recipient['contract'] ?? '') == 'na' ? 'selected' : '' }}>N/A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @empty
                <div class="recipient-card card mb-3 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold">Recipient Name</label>
                                <input type="text" name="external_recipients[0][name]" class="form-control" placeholder="e.g., IT system supplier">
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Type of Recipient</label>
                                <select name="external_recipients[0][type]" class="form-select">
                                    <option>Public authority</option>
                                    <option>Service provider</option>
                                    <option>Commercial partner</option>
                                    <option>Research collaborator</option>
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label fw-bold">Relationship Type</label>
                                <select name="external_recipients[0][relationship]" class="form-select">
                                    <option>Controller</option>
                                    <option>Processor</option>
                                    <option>Joint Controller</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-3">
                                <label class="form-label fw-bold">Contract in Place?</label>
                                <select name="external_recipients[0][contract]" class="form-select">
                                    <option>Yes</option>
                                    <option>No</option>
                                    <option>N/A</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                @endforelse
            </div>

            <div class="alert alert-light mt-3">
                <i class="fas fa-info-circle me-2"></i>
                <small class="text-muted">
                    For each external recipient, specify the legal basis for sharing and any contractual safeguards in place (e.g., Data Processing Agreement).
                </small>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('add-recipient')?.addEventListener('click', function() {
    const container = document.getElementById('external-recipients-container');
    const idx = container.children.length;
    const card = document.createElement('div');
    card.className = 'recipient-card card mb-3 border';
    card.innerHTML = `
        <div class="card-body position-relative">
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-recipient"></button>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Recipient Name</label>
                    <input type="text" name="external_recipients[${idx}][name]" class="form-control" placeholder="e.g., IT system supplier">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Type of Recipient</label>
                    <select name="external_recipients[${idx}][type]" class="form-select">
                        <option>Public authority</option>
                        <option>Service provider</option>
                        <option>Commercial partner</option>
                        <option>Research collaborator</option>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Relationship Type</label>
                    <select name="external_recipients[${idx}][relationship]" class="form-select">
                        <option>Controller</option>
                        <option>Processor</option>
                        <option>Joint Controller</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">Contract in Place?</label>
                    <select name="external_recipients[${idx}][contract]" class="form-select">
                        <option>Yes</option>
                        <option>No</option>
                        <option>N/A</option>
                    </select>
                </div>
            </div>
        </div>
    `;
    container.appendChild(card);
    card.querySelector('.remove-recipient').onclick = () => card.remove();
});

document.querySelectorAll('.remove-recipient').forEach(btn => {
    btn.onclick = function() {
        this.closest('.recipient-card').remove();
    };
});
</script>
@endpush
