<div class="form-section">
    <div class="alert alert-info mb-4 shadow-sm" style="background: linear-gradient(135deg, #e8eef5 0%, #ffffff 100%); border-left: 5px solid #b69964; border-radius: 12px;">
        <div class="d-flex align-items-start">
            <div class="me-3">
                <i class="fas fa-globe fa-2x" style="color: #153d6f;"></i>
            </div>
            <div>
                <h5 class="mb-1" style="color: #153d6f;">International Transfers</h5>
                <p class="mb-0 text-muted small">Capture cross-border data transfer compliance requirements.</p>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-map-marker-alt" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Transfer Countries</h5>
                    </div>
                    @php
                        $transfers = [];
                        if ($submission->international_transfers) {
                            $transfers = is_array($submission->international_transfers)
                                ? $submission->international_transfers
                                : (json_decode($submission->international_transfers, true) ?? []);
                        }
                    @endphp
                    <select name="international_transfers[]" class="form-select" multiple size="6">
                        <option value="Kenya" {{ in_array('Kenya', $transfers) ? 'selected' : '' }}>🇰🇪 Kenya</option>
                        <option value="United Kingdom" {{ in_array('United Kingdom', $transfers) ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                        <option value="USA" {{ in_array('USA', $transfers) ? 'selected' : '' }}>🇺🇸 United States</option>
                        <option value="Germany" {{ in_array('Germany', $transfers) ? 'selected' : '' }}>🇩🇪 Germany</option>
                        <option value="South Africa" {{ in_array('South Africa', $transfers) ? 'selected' : '' }}>🇿🇦 South Africa</option>
                        <option value="India" {{ in_array('India', $transfers) ? 'selected' : '' }}>🇮🇳 India</option>
                        <option value="Netherlands" {{ in_array('Netherlands', $transfers) ? 'selected' : '' }}>🇳🇱 Netherlands</option>
                        <option value="Ireland" {{ in_array('Ireland', $transfers) ? 'selected' : '' }}>🇮🇪 Ireland</option>
                        <option value="Canada" {{ in_array('Canada', $transfers) ? 'selected' : '' }}>🇨🇦 Canada</option>
                        <option value="Australia" {{ in_array('Australia', $transfers) ? 'selected' : '' }}>🇦🇺 Australia</option>
                    </select>
                    <small class="text-muted mt-2 d-block">Hold Ctrl/Cmd to select multiple countries</small>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="rounded-circle p-2 me-2" style="background: #e8eef5;">
                            <i class="fas fa-handshake" style="color: #153d6f;"></i>
                        </div>
                        <h5 class="card-title mb-0" style="color: #153d6f;">Transfer Mechanism</h5>
                    </div>
                    <div class="multi-select-container">
                        <div class="chips-container mb-2" style="display: flex; flex-wrap: wrap; gap: 8px;">
                            @php
                                $mechanisms = [];
                                if ($submission->transfer_mechanisms) {
                                    $mechanisms = is_array($submission->transfer_mechanisms)
                                        ? $submission->transfer_mechanisms
                                        : (json_decode($submission->transfer_mechanisms, true) ?? []);
                                }
                            @endphp
                            @foreach($mechanisms as $mech)
                            <span class="tag-chip">
                                <i class="fas fa-check-circle me-1"></i>
                                <span>{{ $mech }}</span>
                                <button type="button" onclick="this.parentElement.remove(); updateMechanisms();">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            @endforeach
                        </div>
                        <input type="text" class="form-control" id="mechanismInput"
                               placeholder="Type and press Enter (e.g., Adequacy decision, SCCs, BCRs)">
                        <input type="hidden" name="transfer_mechanisms" id="transfer_mechanisms_hidden" value="{{ json_encode($mechanisms) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if(!empty($transfers))
    <div class="alert alert-warning mt-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Compliance Reminder:</strong> Ensure appropriate safeguards are in place for international transfers, including Standard Contractual Clauses (SCCs) or Binding Corporate Rules (BCRs) where applicable.
    </div>
    @endif
</div>

@push('scripts')
<script>
function updateMechanisms() {
    const chips = document.querySelectorAll('#step9 .chips-container .tag-chip span');
    const values = Array.from(chips).map(chip => chip.textContent.trim());
    document.getElementById('transfer_mechanisms_hidden').value = JSON.stringify(values);
}

document.getElementById('mechanismInput')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        const value = this.value.trim();
        if (value) {
            const container = document.querySelector('#step9 .chips-container');
            const chip = document.createElement('span');
            chip.className = 'tag-chip';
            chip.innerHTML = `<i class="fas fa-check-circle me-1"></i><span>${escapeHtml(value)}</span><button type="button" onclick="this.parentElement.remove(); updateMechanisms();"><i class="fas fa-times"></i></button>`;
            container.appendChild(chip);
            this.value = '';
            updateMechanisms();
        }
    }
});
</script>
@endpush
