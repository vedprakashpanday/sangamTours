@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark">Route Management</h4>
        <button class="btn btn-primary btn-sm" id="addRouteBtn">
            <i class='bx bx-plus'></i> Create New Route
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="routeTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>From (Source)</th>
                            <th>To (Destination)</th>
                            <th>Distance</th>
                            <th>Duration</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $r)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="fw-bold text-primary">{{ $r->fromCity->city_location ?? 'N/A' }}</td>
                            <td class="fw-bold text-success">{{ $r->toCity->city_location ?? 'N/A' }}</td>
                            <td>{{ $r->distance ?? '--' }} KM</td>
                            <td>{{ $r->duration ?? '--' }}</td>
                            <td>
                                <span class="badge {{ $r->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $r->status ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary edit-route" data-id="{{ $r->id }}"><i class='bx bx-edit-alt'></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-route" data-id="{{ $r->id }}"><i class='bx bx-trash'></i></button>
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
            <h6 class="m-0 fw-bold text-danger">Trashed Routes</h6>
            <div>
                <button class="btn btn-sm btn-outline-success me-2" id="restoreAllBtn"><i class='bx bx-undo'></i> Restore All</button>
                <button class="btn btn-sm btn-outline-danger" id="emptyTrashBtn"><i class='bx bx-trash'></i> Empty Trash</button>
            </div>
        </div>
        <div class="card-body">
            <table id="trashRouteTable" class="table table-sm">
                <thead>
                    <tr><th>Route</th><th>Action</th></tr>
                </thead>
                <tbody>
                    @foreach($trashedRoutes as $tr)
                    <tr>
                        <td>{{ $tr->fromCity->city_location }} <i class='bx bx-right-arrow-alt'></i> {{ $tr->toCity->city_location }}</td>
                        <td>
                            <button class="btn btn-xs text-success restore-route" data-id="{{ $tr->id }}"><i class='bx bx-undo'></i></button>
                            <button class="btn btn-xs text-danger force-delete-route" data-id="{{ $tr->id }}"><i class='bx bx-x-circle'></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="routeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Set New Route</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="routeForm">
                @csrf
                <input type="hidden" name="id" id="route_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">From City</label>
                            <select name="from_city_id" class="form-select select2-city" required>
                                <option value="">Select Source</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->city_location }} ({{ $city->state_name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">To City</label>
                            <select name="to_city_id" class="form-select select2-city" required>
                                <option value="">Select Destination</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}">{{ $city->city_location }} ({{ $city->state_name }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Distance (KM)</label>
                            <input type="number" name="distance" class="form-control" placeholder="e.g. 450">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Estimated Duration</label>
                            <input type="text" name="duration" class="form-control" placeholder="e.g. 02h 30m">
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="status" id="rStatus" value="1" checked>
                                <label class="form-check-label ms-2" for="rStatus">Route Active</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary w-100" id="saveBtn">Save Route</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    let rTable = $('#routeTable').DataTable({ "dom": '<"d-flex justify-content-between mb-3"Bf>rtip', "buttons": ['excelHtml5'] });
    let tTable = $('#trashRouteTable').DataTable();

    // 1. Add Route
    $('#addRouteBtn').click(function() {
        $('#routeForm')[0].reset();
        $('#route_id').val('');
        $('#modalTitle').text('Set New Route');
        $('#routeModal').modal('show');
    });

    // 2. Edit Route
    $(document).on('click', '.edit-route', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/routes') }}/" + id + "/edit", function(r) {
            $('#route_id').val(r.id);
            $('select[name="from_city_id"]').val(r.from_city_id);
            $('select[name="to_city_id"]').val(r.to_city_id);
            $('input[name="distance"]').val(r.distance);
            $('input[name="duration"]').val(r.duration);
            $('#rStatus').prop('checked', r.status == 1);
            $('#modalTitle').text('Edit Route');
            $('#routeModal').modal('show');
        });
    });

    // 3. Form Submit
    $('#routeForm').on('submit', function(e) {
        e.preventDefault();
        
        // Client-side Validation: Cities same nahi honi chahiye
        if($('select[name="from_city_id"]').val() == $('select[name="to_city_id"]').val()) {
            alert("Error: Source and Destination cannot be the same city!");
            return false;
        }

        let id = $('#route_id').val();
        let url = id ? "{{ url('admin/routes') }}/" + id : "{{ route('admin.routes.store') }}";
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

    // Trash & Restore logic (Same as other masters)
    $('#restoreAllBtn').on('click', function() {
        if (tTable.rows().count() === 0) { alert('Trash is already empty!'); return; }
        $.get("{{ url('admin/routes/restore-all') }}", function() { location.reload(); });
    });

    $('#emptyTrashBtn').on('click', function() {
        if (tTable.rows().count() === 0) { alert('Trash is already empty!'); return; }
        if(confirm('Empty entire trash?')) {
            $.ajax({ url: "{{ url('admin/routes/empty-trash') }}", method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, success: function() { location.reload(); } });
        }
    });

    $(document).on('click', '.delete-route', function() {
        $.ajax({ url: "{{ url('admin/routes') }}/" + $(this).data('id'), method: 'DELETE', headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}, success: function() { location.reload(); } });
    });

    $(document).on('click', '.restore-route', function() {
        $.get("{{ url('admin/routes/restore') }}/" + $(this).data('id'), function() { location.reload(); });
    });
});
</script>
@endpush