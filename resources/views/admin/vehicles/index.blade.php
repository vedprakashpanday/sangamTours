@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Vehicles & Inventory</h4>
        <button class="btn btn-primary" id="addVehicleBtn"><i class='bx bx-plus'></i> Add Inventory</button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <table id="vehicleTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Vehicle/Flight #</th>
                        <th>Vendor (Type)</th>
                        <th>Seats</th>
                        <th>Base Fare</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $v)
                    <tr>
                        <td class="fw-bold">{{ $v->vehicle_number }} <br><small class="text-muted">{{ $v->seat_type }}</small></td>
                        <td>
                            {{ $v->vendor->name }} 
                            <span class="badge bg-light text-dark border ms-1">{{ $v->type }}</span>
                        </td>
                        <td><span class="badge bg-info">{{ $v->total_seats }} Seats</span></td>
                        <td class="fw-bold text-success">₹{{ number_format($v->base_fare) }}</td>
                        <td>
                            <span class="badge {{ $v->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $v->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary edit-vehicle" data-id="{{ $v->id }}"><i class='bx bx-edit-alt'></i></button>
                                <button class="btn btn-sm btn-outline-danger delete-vehicle" data-id="{{ $v->id }}"><i class='bx bx-trash'></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="card border-0 shadow-sm border-top border-danger border-3">
        <div class="card-header bg-white d-flex justify-content-between py-3">
            <h6 class="m-0 fw-bold text-danger">Trash Bin</h6>
            <div>
                <button class="btn btn-xs btn-outline-success me-2" id="restoreAllBtn">Restore All</button>
                <button class="btn btn-xs btn-outline-danger" id="emptyTrashBtn">Empty Trash</button>
            </div>
        </div>
        <div class="card-body">
            <table id="trashTable" class="table table-sm">
                <thead><tr><th>Vehicle #</th><th>Vendor</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($trashedVehicles as $tv)
                    <tr>
                        <td>{{ $tv->vehicle_number }}</td>
                        <td>{{ $tv->vendor->name }}</td>
                        <td>
                            <button class="btn btn-xs text-success restore-vehicle" data-id="{{ $tv->id }}"><i class='bx bx-undo'></i></button>
                            <button class="btn btn-xs text-danger force-delete-vehicle" data-id="{{ $tv->id }}"><i class='bx bx-x-circle'></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="vehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTitle">Inventory Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="vehicleForm">
                @csrf
                <input type="hidden" name="id" id="vehicle_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Select Vendor</label>
                            <select name="vendor_id" id="vendor_select" class="form-select" required>
                                <option value="">-- Choose Vendor --</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" data-type="{{ $vendor->type }}">
                                    {{ $vendor->name }} ({{ $vendor->type }})
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vehicle/Service Type</label>
                            <input type="text" name="type" id="vehicle_type_input" class="form-control bg-light" readonly placeholder="Auto-selected">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Vehicle/Flight Number</label>
                            <input type="text" name="vehicle_number" class="form-control" required placeholder="e.g. 6E-542 or BR01-4432">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Total Seats</label>
                            <input type="number" name="total_seats" class="form-control" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Base Fare (₹)</label>
                            <input type="number" name="base_fare" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Seat Category</label>
                            <select name="seat_type" class="form-select">
                                <option value="Economy">Economy</option>
                                <option value="Business">Business</option>
                                <option value="Sleeper">Sleeper</option>
                                <option value="Seater (AC)">Seater (AC)</option>
                            </select>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="form-label fw-bold d-block">Available Amenities</label>
                            <div class="d-flex flex-wrap gap-3 p-3 border rounded bg-light">
                                @foreach($amenities as $am)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="amenity_ids[]" value="{{ $am->id }}" id="am_{{ $am->id }}">
                                    <label class="form-check-label" for="am_{{ $am->id }}">
                                        {{ $am->name }}
                                    </label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary w-100" id="saveBtn">Save Inventory</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
   $(document).ready(function() {
    // 1. DataTables Init
    let table = $('#vehicleTable').DataTable({
        "dom": '<"d-flex justify-content-between mb-3"Bf>rtip',
        "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="bx bxs-file-export"></i> Excel',
            className: 'btn btn-success btn-sm border-0',
            exportOptions: { columns: ':not(:last-child)' },
            action: function(e, dt, node, config) {
                if (dt.rows().count() === 0) { alert('No inventory data to export!'); return false; }
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
            }
        }]
    });

    let tTable = $('#trashTable').DataTable();

    // 2. Vendor Type Auto-Fill Logic
    $('#vendor_select').on('change', function() {
        let type = $(this).find(':selected').data('type');
        let mapType = "";
        if(type == "Airline") mapType = "Flight";
        else if(type == "Bus Operator") mapType = "Bus";
        else if(type == "Train Dept") mapType = "Train";
        
        $('#vehicle_type_input').val(mapType);
    });

    // 3. Add & Edit Handlers
    $('#addVehicleBtn').click(function() {
        $('#vehicleForm')[0].reset();
        $('#vehicle_id').val('');
        $('.form-check-input').prop('checked', false);
        $('#modalTitle').text('Add New Inventory');
        $('#saveBtn').text('Save Inventory');
        $('#vehicleModal').modal('show');
    });

    $(document).on('click', '.edit-vehicle', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/vehicles') }}/" + id + "/edit", function(v) {
            $('#vehicle_id').val(v.id);
            $('#vendor_select').val(v.vendor_id).trigger('change');
            $('input[name="vehicle_number"]').val(v.vehicle_number);
            $('input[name="total_seats"]').val(v.total_seats);
            $('input[name="base_fare"]').val(v.base_fare);
            $('select[name="seat_type"]').val(v.seat_type);

            $('.form-check-input').prop('checked', false);
            if(v.amenities) {
                v.amenities.forEach(am => {
                    $(`#am_${am.id}`).prop('checked', true);
                });
            }

            $('#modalTitle').text('Edit Inventory');
            $('#saveBtn').text('Update Inventory');
            $('#vehicleModal').modal('show');
        });
    });

    // 4. Form Submit
    $('#vehicleForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#vehicle_id').val();
        let url = id ? "{{ url('admin/vehicles') }}/" + id : "{{ route('admin.vehicles.store') }}";
        let formData = new FormData(this);
        if(id) formData.append('_method', 'PUT');

        $.ajax({
            url: url, method: "POST", data: formData,
            contentType: false, processData: false,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) { alert(res.message); location.reload(); },
            error: function(xhr) { alert('Error occurred while saving!'); }
        });
    });

    // --- 5. Trash Actions (With Blank Checks) ---

    // Single Soft Delete
    $(document).on('click', '.delete-vehicle', function() {
        if(confirm('Move this inventory to trash?')) {
            $.ajax({
                url: "{{ url('admin/vehicles') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function() { location.reload(); }
            });
        }
    });

    // Single Restore
    $(document).on('click', '.restore-vehicle', function() {
        $.get("{{ url('admin/vehicles/restore') }}/" + $(this).data('id'), function() {
            location.reload();
        });
    });

    // Permanent Delete
    $(document).on('click', '.force-delete-vehicle', function() {
        if(confirm('PERMANENT DELETE: This cannot be undone!')) {
            $.ajax({
                url: "{{ url('admin/vehicles/force-delete') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function() { location.reload(); }
            });
        }
    });

    // Restore All Logic
    $('#restoreAllBtn').on('click', function() {
        if (tTable.rows().count() === 0) {
            alert('Trash is already empty!');
            return false;
        }
        if(confirm('Restore all items from trash?')) {
            $.get("{{ url('admin/vehicles/restore-all') }}", function() {
                location.reload();
            });
        }
    });

    // Empty Trash Logic
    $('#emptyTrashBtn').on('click', function() {
        if (tTable.rows().count() === 0) {
            alert('Trash is already empty!');
            return false;
        }
        if(confirm('CRITICAL: Permanently delete all items in trash?')) {
            $.ajax({
                url: "{{ url('admin/vehicles/empty-trash') }}",
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function() { location.reload(); }
            });
        }
    });
});
</script>

@endpush