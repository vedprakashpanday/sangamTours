@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark">Vendor Management</h4>
            <button class="btn btn-primary btn-sm" id="addVendorBtn">
                <i class='bx bx-plus'></i> Add New Vendor
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="vendorTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Vendor Name</th>
                            <th>Type</th>
                            <th>Contact</th>
                            <th>Source</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vendors as $v)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold text-primary">{{ $v->name }}</td>
                            <td><span class="badge bg-light text-dark border">{{ $v->type }}</span></td>
                            <td>{{ $v->phone }}<br><small class="text-muted">{{ $v->email }}</small></td>
                            <td>
                                <span class="badge {{ $v->is_api ? 'bg-info' : 'bg-secondary' }}">
                                    {{ $v->is_api ? 'API Integrated' : 'Manual Entry' }}
                                </span>
                            </td>
                            <td>
                                <span class="badge {{ $v->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $v->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary edit-vendor" data-id="{{ $v->id }}"><i class='bx bx-edit-alt'></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-vendor" data-id="{{ $v->id }}"><i class='bx bx-trash'></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm border-top border-danger border-3">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h6 class="m-0 fw-bold text-danger">Trashed Vendors</h6>
            <div>
                <button class="btn btn-sm btn-outline-success me-2" id="restoreAllBtn"><i class='bx bx-undo'></i> Restore All</button>
                <button class="btn btn-sm btn-outline-danger" id="emptyTrashBtn"><i class='bx bx-trash'></i> Empty Trash</button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="trashVendorTable" class="table table-hover align-middle">
                    <thead>
                        <tr class="table-light">
                            <th>Name</th>
                            <th>Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trashedVendors as $tv)
                        <tr>
                            <td>{{ $tv->name }}</td>
                            <td>{{ $tv->type }}</td>
                            <td>
                                <button class="btn btn-sm btn-success restore-vendor" data-id="{{ $tv->id }}"><i class='bx bx-undo'></i></button>
                                <button class="btn btn-sm btn-danger force-delete-vendor" data-id="{{ $tv->id }}"><i class='bx bx-x-circle'></i></button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="vendorModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Add New Vendor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="vendorForm">
                @csrf
                <input type="hidden" name="id" id="vendor_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Vendor Name</label>
                            <input type="text" name="name" class="form-control" required placeholder="e.g. Indigo, VRL Travels">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Vendor Type</label>
                            <select name="type" class="form-select" required>
                                <option value="">Select Type</option>
                                <option value="Airline">Airline</option>
                                <option value="Bus Operator">Bus Operator</option>
                                <option value="Train Dept">Train Dept</option>
                                <option value="Hotel Partner">Hotel Partner</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Phone Number</label>
                            <input type="text" name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email (Optional)</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="is_api" id="isApi">
                                <label class="form-check-label ms-2" for="isApi">API Integrated</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="status" id="vStatus" checked>
                                <label class="form-check-label ms-2" for="vStatus">Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Vendor</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 1. Common Excel Button Logic with Blank Check
    const getButtons = (title) => [{
        extend: 'excelHtml5',
        text: '<i class="bx bxs-file-export"></i> Excel',
        className: 'btn btn-success btn-sm border-0',
        title: title,
        exportOptions: { columns: ':not(:last-child)' },
        action: function(e, dt, node, config) {
            if (dt.rows().count() === 0) { alert('No data available to export!'); return false; }
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
        }
    }];

    // 2. DataTables Init
    let vTable = $('#vendorTable').DataTable({
        "dom": '<"d-flex justify-content-between mb-3"Bf>rtip',
        "buttons": getButtons('Active Vendors')
    });

    let tTable = $('#trashVendorTable').DataTable({
        "dom": '<"d-flex justify-content-between mb-3"Bf>rtip',
        "buttons": getButtons('Trashed Vendors')
    });

    // 3. CRUD AJAX Handlers
    $('#addVendorBtn').click(function() {
        $('#vendorForm')[0].reset();
        $('#vendor_id').val('');
        $('#modalTitle').text('Add New Vendor');
        $('#vendorModal').modal('show');
    });

    $(document).on('click', '.edit-vendor', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/vendors') }}/" + id + "/edit", function(v) {
            $('#vendor_id').val(v.id);
            $('input[name="name"]').val(v.name);
            $('select[name="type"]').val(v.type);
            $('input[name="phone"]').val(v.phone);
            $('input[name="email"]').val(v.email);
            $('#isApi').prop('checked', v.is_api == 1);
            $('#vStatus').prop('checked', v.status == 1);
            $('#modalTitle').text('Update Vendor');
            $('#vendorModal').modal('show');
        });
    });

    $('#vendorForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#vendor_id').val();
        let url = id ? "{{ url('admin/vendors') }}/" + id : "{{ route('admin.vendors.store') }}";
        let formData = new FormData(this);
        if(id) formData.append('_method', 'PUT');

        $.ajax({
            url: url, method: "POST", data: formData,
            contentType: false, processData: false,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) { alert(res.message); location.reload(); },
            error: function(xhr) { alert(xhr.responseJSON.message || 'Error!'); }
        });
    });

    // 4. Trash Actions with Blank Checks
    $(document).on('click', '.delete-vendor', function() {
        if(confirm('Move to trash?')) {
            $.ajax({
                url: "{{ url('admin/vendors') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function() { location.reload(); }
            });
        }
    });

    $('#restoreAllBtn').on('click', function() {
        if (tTable.rows().count() === 0) { alert('Trash is already empty!'); return; }
        if(confirm('Restore all vendors?')) {
            $.get("{{ url('admin/vendors/restore-all') }}", function(res) { location.reload(); });
        }
    });

    $('#emptyTrashBtn').on('click', function() {
        if (tTable.rows().count() === 0) { alert('Trash is already empty!'); return; }
        if(confirm('Permanently delete everything in trash?')) {
            $.ajax({
                url: "{{ url('admin/vendors/empty-trash') }}",
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function() { location.reload(); }
            });
        }
    });

    $(document).on('click', '.restore-vendor', function() {
        $.get("{{ url('admin/vendors/restore') }}/" + $(this).data('id'), function() { location.reload(); });
    });

    $(document).on('click', '.force-delete-vendor', function() {
        if(confirm('PERMANENT DELETE?')) {
            $.ajax({
                url: "{{ url('admin/vendors/force-delete') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function() { location.reload(); }
            });
        }
    });
});
</script>
@endpush