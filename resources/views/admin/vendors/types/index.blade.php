@extends('admin.common_layout')

{{-- 🔥 Select2 CSS Add Karein --}}
@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single { height: 40px; border: 1px solid #dee2e6; padding: 6px; }
    .select2-container--default .select2-selection--arrow { height: 38px; }
    .select2-results__option i { font-size: 20px; vertical-align: middle; }
    .table-icon { font-size: 24px; width: 40px; text-align: center; }
</style>
@endpush

@section('admin_content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold text-primary">Add New Vendor Type</div>
                <div class="card-body">
                    <form action="{{ route('admin.vendor-types.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Vendor Type Name</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Hotel, Transport" required>
                        </div>
                        
                        {{-- 🔥 Icon Select Field --}}
                        <div class="mb-3">
                            <label class="form-label">Select Icon</label>
                            <select name="icon" class="form-select select2-icons" style="width: 100%">
                                <option value="">No Icon</option>
                                @if(isset($icons))
                                    @foreach($icons as $icon)
                                        <option value="{{ $icon }}" data-icon="{{ $icon }}">
                                            {{ str_replace('bx-', '', $icon) }}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Save Type</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white fw-bold">Existing Vendor Types</div>
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Icon</th> {{-- 🔥 Icon Column --}}
                                <th>Name</th>
                                <th>Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($types as $type)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td class="table-icon">
                                    {{-- 🔥 Icon Display --}}
                                    <i class='bx {{ $type->icon ?? "bx-list-ul" }} text-primary'></i>
                                </td>
                                <td class="fw-bold">{{ $type->name }}</td>
                                <td>{{ $type->created_at->format('d M Y') }}</td>
                                <td>
                                    <button class="btn btn-sm btn-light border text-primary" data-bs-toggle="modal" data-bs-target="#editModal{{ $type->id }}">
                                        <i class='bx bx-edit'></i>
                                    </button>

                                    <form action="{{ route('admin.vendor-types.destroy', $type->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-light border text-danger">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>

                            <div class="modal fade" id="editModal{{ $type->id }}" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <form action="{{ route('admin.vendor-types.update', $type->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-header">
                                                <h5 class="modal-title fw-bold">Edit Vendor Type</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body text-start">
                                                <div class="mb-3">
                                                    <label class="form-label">Vendor Type Name</label>
                                                    <input type="text" name="name" class="form-control" value="{{ $type->name }}" required>
                                                </div>

                                                {{-- 🔥 Edit Modal Icon Field --}}
                                                <div class="mb-3">
                                                    <label class="form-label">Select Icon</label>
                                                    <select name="icon" class="form-select select2-icons-modal" style="width: 100%">
                                                        <option value="">No Icon</option>
                                                        @if(isset($icons))
                                                            @foreach($icons as $icon)
                                                                <option value="{{ $icon }}" data-icon="{{ $icon }}" {{ $type->icon == $icon ? 'selected' : '' }}>
                                                                    {{ str_replace('bx-', '', $icon) }}
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="modal-footer bg-light">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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

{{-- 🔥 Select2 Scripts --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    // Icon formatting function
    function formatIcon(icon) {
        if (!icon.id) { return icon.text; }
        var iconClass = $(icon.element).data('icon');
        if(!iconClass) return icon.text;

        return $('<span><i class="bx ' + iconClass + ' me-2"></i> ' + icon.text + '</span>');
    };

    // Main Page Select2 Init
    $('.select2-icons').select2({
        templateResult: formatIcon,
        templateSelection: formatIcon,
        escapeMarkup: function(m) { return m; }
    });

    // Modal Select2 Init (Needs dropdownParent to work inside Bootstrap Modal)
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