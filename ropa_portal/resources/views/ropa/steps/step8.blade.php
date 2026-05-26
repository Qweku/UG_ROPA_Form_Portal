
<div class="form-section">
    <div id="external-recipients-container">
        @php $recipients = $ropaForm->external_recipients ?? []; @endphp
        @forelse($recipients as $idx => $recipient)
        <div class="recipient-card card mb-3 p-3">
            <div class="d-flex justify-content-end"><button type="button" class="btn-close remove-recipient"></button></div>
            <div class="row">
                <div class="col-md-4"><label>Recipient Name</label><input type="text" name="external_recipients[{{ $idx }}][name]" class="form-control" value="{{ $recipient['name'] ?? '' }}"></div>
                <div class="col-md-3"><label>Type of Recipient</label>
                    <select name="external_recipients[{{ $idx }}][type]" class="form-select"><option value="Public authority" {{ ($recipient['type'] ?? '')=='Public authority' ? 'selected' : '' }}>Public authority</option><option value="Service provider" {{ ($recipient['type'] ?? '')=='Service provider' ? 'selected' : '' }}>Service provider</option></select>
                </div>
                <div class="col-md-3"><label>Relationship Type</label>
                    <select name="external_recipients[{{ $idx }}][relationship]" class="form-select"><option value="Controller" {{ ($recipient['relationship'] ?? '')=='Controller' ? 'selected' : '' }}>Controller</option><option value="Processor" {{ ($recipient['relationship'] ?? '')=='Processor' ? 'selected' : '' }}>Processor</option><option value="Joint Controller" {{ ($recipient['relationship'] ?? '')=='Joint Controller' ? 'selected' : '' }}>Joint Controller</option></select>
                </div>
                <div class="col-md-2"><label>Contract in Place?</label>
                    <select name="external_recipients[{{ $idx }}][contract]" class="form-select"><option value="yes">Yes</option><option value="no">No</option><option value="na">N/A</option></select>
                </div>
            </div>
        </div>
        @empty
        <div class="recipient-card card mb-3 p-3">
            <div class="row">
                <div class="col-md-4"><label>Recipient Name</label><input type="text" name="external_recipients[0][name]" class="form-control" placeholder="e.g., IT system supplier"></div>
                <div class="col-md-3"><label>Type of Recipient</label><select name="external_recipients[0][type]" class="form-select"><option>Public authority</option><option>Service provider</option></select></div>
                <div class="col-md-3"><label>Relationship Type</label><select name="external_recipients[0][relationship]" class="form-select"><option>Controller</option><option>Processor</option><option>Joint Controller</option></select></div>
                <div class="col-md-2"><label>Contract in Place?</label><select name="external_recipients[0][contract]" class="form-select"><option>Yes</option><option>No</option><option>N/A</option></select></div>
            </div>
        </div>
        @endforelse
    </div>
    <button type="button" class="btn btn-outline-secondary" id="add-recipient">+ Add Another Recipient</button>
</div>

<script>
document.getElementById('add-recipient')?.addEventListener('click', function() {
    const container = document.getElementById('external-recipients-container');
    const idx = container.children.length;
    const card = document.createElement('div');
    card.className = 'recipient-card card mb-3 p-3';
    card.innerHTML = `<div class="d-flex justify-content-end"><button type="button" class="btn-close remove-recipient"></button></div><div class="row"><div class="col-md-4"><label>Recipient Name</label><input type="text" name="external_recipients[${idx}][name]" class="form-control"></div><div class="col-md-3"><label>Type</label><select name="external_recipients[${idx}][type]" class="form-select"><option>Public authority</option><option>Service provider</option></select></div><div class="col-md-3"><label>Relationship</label><select name="external_recipients[${idx}][relationship]" class="form-select"><option>Controller</option><option>Processor</option><option>Joint Controller</option></select></div><div class="col-md-2"><label>Contract?</label><select name="external_recipients[${idx}][contract]" class="form-select"><option>Yes</option><option>No</option><option>N/A</option></select></div></div>`;
    container.appendChild(card);
    card.querySelector('.remove-recipient').onclick = () => card.remove();
});
document.querySelectorAll('.remove-recipient').forEach(btn => btn.onclick = () => btn.closest('.recipient-card').remove());
</script>
