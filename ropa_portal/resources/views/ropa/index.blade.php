
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 style="color: #153d6f;">Record of Processing Activities (RoPA)</h1>
        <a href="{{ route('ropa.create') }}" class="btn" style="background: #b69964; color: white; border: none;">
            + New RoPA Form
        </a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            @if($forms->isEmpty())
                <div class="text-center py-5">
                    <p class="text-muted">No RoPA forms yet. Click "New RoPA Form" to get started.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead style="background: #153d6f; color: white;">
                            <tr>
                                <th>ID</th>
                                <th>Process/Project</th>
                                <th>Last Updated</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($forms as $form)
                            <tr>
                                <td>#{{ $form->id }}</td>
                                <td>{{ implode(', ', $form->process_names ?? ['Untitled']) }}</td>
                                <td>{{ $form->updated_at->format('M d, Y') }}</td>
                                <td>
                                    <span class="badge bg-{{ $form->status === 'submitted' ? 'success' : ($form->status === 'approved' ? 'info' : 'warning') }}">
                                        {{ ucfirst($form->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('ropa.edit', $form) }}" class="btn btn-sm btn-outline-primary">Continue</a>
                                    <a href="{{ route('ropa.show', $form) }}" class="btn btn-sm btn-outline-secondary">View</a>
                                    <form action="{{ route('ropa.destroy', $form) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this form?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
