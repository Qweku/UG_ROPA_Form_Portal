
<div class="form-section">
    <div class="row g-4">
        <div class="col-md-6">
            <label class="form-label fw-bold">Countries Data Is Transferred To</label>
            <select name="international_transfers[]" class="form-select" multiple size="4">
                <option value="Kenya" {{ in_array('Kenya', $ropaForm->international_transfers ?? []) ? 'selected' : '' }}>🇰🇪 Kenya</option>
                <option value="United Kingdom" {{ in_array('United Kingdom', $ropaForm->international_transfers ?? []) ? 'selected' : '' }}>🇬🇧 United Kingdom</option>
                <option value="USA" {{ in_array('USA', $ropaForm->international_transfers ?? []) ? 'selected' : '' }}>🇺🇸 USA</option>
                <option value="Germany" {{ in_array('Germany', $ropaForm->international_transfers ?? []) ? 'selected' : '' }}>🇩🇪 Germany</option>
                <option value="South Africa" {{ in_array('South Africa', $ropaForm->international_transfers ?? []) ? 'selected' : '' }}>🇿🇦 South Africa</option>
                <option value="India" {{ in_array('India', $ropaForm->international_transfers ?? []) ? 'selected' : '' }}>🇮🇳 India</option>
            </select>
            <small>Hold Ctrl/Cmd to select multiple countries</small>
        </div>

        <div class="col-md-6">
            <label class="form-label fw-bold">Transfer Mechanism</label>
            <div class="multi-select-container">
                <div class="chips-container">
                    @foreach(($ropaForm->transfer_mechanisms ?? []) as $mech)
                    <span class="tag-chip">{{ $mech }} <button type="button">×</button></span>
                    @endforeach
                </div>
                <input type="text" class="form-control" placeholder="e.g., Adequacy decision, Standard Contractual Clauses, BCRs">
                <input type="hidden" name="transfer_mechanisms" value="{{ json_encode($ropaForm->transfer_mechanisms ?? []) }}">
            </div>
        </div>
    </div>

    @if(($ropaForm->international_transfers ?? []) && !in_array('UK', $ropaForm->international_transfers ?? []))
    <div class="alert alert-warning mt-3"><i class="fas fa-exclamation-triangle"></i> Compliance Warning: Ensure appropriate safeguards are in place for transfers to non-adequate countries.</div>
    @endif
</div>
