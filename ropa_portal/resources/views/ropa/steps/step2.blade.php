
<div class="form-section">
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i> This section captures external or shared data control relationships.
    </div>

    <div id="joint-controllers-container">
        @php $controllers = $ropaForm->joint_controllers ?? []; @endphp
        @if(empty($controllers))
            <div class="joint-controller-card card mb-3 p-3">
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Name of Joint Controller</label>
                        <input type="text" name="joint_controllers[0][name]" class="form-control" placeholder="e.g., Research Collaborator">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Contact Details</label>
                        <textarea name="joint_controllers[0][contact]" class="form-control" rows="2" placeholder="Name & Address of Research Collaborator"></textarea>
                    </div>
                </div>
            </div>
        @else
            @foreach($controllers as $idx => $controller)
            <div class="joint-controller-card card mb-3 p-3">
                <div class="d-flex justify-content-end mb-2">
                    <button type="button" class="btn-close remove-controller" aria-label="Remove"></button>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <label class="form-label">Name</label>
                        <input type="text" name="joint_controllers[{{ $idx }}][name]" class="form-control" value="{{ $controller['name'] ?? '' }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Contact Details</label>
                        <textarea name="joint_controllers[{{ $idx }}][contact]" class="form-control" rows="2">{{ $controller['contact'] ?? '' }}</textarea>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

    <button type="button" class="btn btn-outline-accent" id="add-joint-controller" style="color: #b69964; border-color: #b69964;">
        + Add Another Joint Controller
    </button>
</div>

<script>
document.getElementById('add-joint-controller')?.addEventListener('click', function() {
    const container = document.getElementById('joint-controllers-container');
    const idx = container.children.length;
    const newCard = document.createElement('div');
    newCard.className = 'joint-controller-card card mb-3 p-3';
    newCard.innerHTML = `
        <div class="d-flex justify-content-end mb-2">
            <button type="button" class="btn-close remove-controller" aria-label="Remove"></button>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="form-label">Name of Joint Controller</label>
                <input type="text" name="joint_controllers[${idx}][name]" class="form-control" placeholder="e.g., Research Collaborator">
            </div>
            <div class="col-md-6">
                <label class="form-label">Contact Details</label>
                <textarea name="joint_controllers[${idx}][contact]" class="form-control" rows="2" placeholder="Name & Address"></textarea>
            </div>
        </div>
    `;
    container.appendChild(newCard);
    newCard.querySelector('.remove-controller').addEventListener('click', () => newCard.remove());
});
document.querySelectorAll('.remove-controller').forEach(btn => btn.addEventListener('click', function() {
    this.closest('.joint-controller-card').remove();
}));
</script>
