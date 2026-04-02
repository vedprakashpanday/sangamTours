@extends('admin.common_layout')

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single { height: 40px; border: 1px solid #dee2e6; padding: 6px; }
    .select2-results__option i { font-size: 20px; vertical-align: middle; }
    .table-icon { font-size: 24px; width: 40px; text-align: center; }
</style>
@endpush

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-primary">Add New Amenity</div>
                <div class="card-body">
                    <form action="{{ route('admin.amenities.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Amenity Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Free WiFi, Spa" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Select Icon</label>
                            <select name="icon" class="form-select select2-icons" style="width: 100%">
                                <option value="">No Icon</option>
                                @foreach($icons as $icon)
                                    <option value="{{ $icon }}" data-icon="{{ $icon }}">{{ str_replace('bx-', '', $icon) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Save Amenity</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">All Amenities</div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Icon</th>
                                <th>Name</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($amenities as $amenity)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="table-icon">
                                    @if($amenity->icon)
                                        <i class="bx {{ $amenity->icon }} text-primary"></i>
                                    @else
                                        <i class="bx bx-check-circle text-muted"></i>
                                    @endif
                                </td>
                                <td><span class="fw-bold">{{ $amenity->name }}</span></td>
                                <td>
                                    <button class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $amenity->id }}">
                                        <i class='bx bx-edit'></i>
                                    </button>
                                    <form action="{{ route('admin.amenities.destroy', $amenity->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger" onclick="return confirm('Delete this amenity?')">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal{{ $amenity->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('admin.amenities.update', $amenity->id) }}" method="POST">
                                            @csrf @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Amenity</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label">Amenity Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $amenity->name }}" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Select Icon</label>
                                                    <select name="icon" class="form-select select2-icons-modal" style="width: 100%">
                                                        <option value="">No Icon</option>
                                                        @foreach($icons as $icon)
                                                            <option value="{{ $icon }}" data-icon="{{ $icon }}" {{ $amenity->icon == $icon ? 'selected' : '' }}>
                                                                {{ str_replace('bx-', '', $icon) }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Update Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    function formatIcon(icon) {
        if (!icon.id) { return icon.text; }
        var iconClass = $(icon.element).data('icon');
        if(!iconClass) return icon.text;
        return $('<span><i class="bx ' + iconClass + ' me-2"></i> ' + icon.text + '</span>');
    };

    $('.select2-icons').select2({
        templateResult: formatIcon,
        templateSelection: formatIcon,
        escapeMarkup: function(m) { return m; }
    });

    $('.modal').on('shown.bs.modal', function () {
        $(this).find('.select2-icons-modal').select2({
            dropdownParent: $(this),
            templateResult: formatIcon,
            templateSelection: formatIcon,
            escapeMarkup: function(m) { return m; }
        });
    });
});
</script>
@endpush