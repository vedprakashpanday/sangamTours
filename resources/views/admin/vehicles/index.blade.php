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
                        <th>Image</th> {{-- 🔥 Naya Column add kiya image dikhane ke liye --}}
                        <th>Vendor (Type)</th>
                        <th>Seats</th>
                        <th>Charges per/KM</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vehicles as $v)
                    <tr>
                        <td class="fw-bold">{{ $v->vehicle_number }} <br><small class="text-muted">{{ $v->seat_type }}</small></td>
                        <td>
                            {{-- 🔥 Table mein Main Image ka preview --}}
                            @php
                                $mainImg = $v->images->where('image_type', 'main')->first();
                                $imagePath = ($mainImg && file_exists(public_path('uploads/vehicles/'.$mainImg->filename))) 
                                             ? asset('uploads/vehicles/'.$mainImg->filename) 
                                             : asset('no-image.png');
                            @endphp
                            <img src="{{ $imagePath }}" class="rounded border" style="width: 50px; height: 40px; object-fit: cover;">
                        </td>
                        <td>
                            {{ $v->vendor->name }} 
                            <span class="badge bg-light text-dark border ms-1">{{ $v->type }}</span>
                        </td>
                        <td><span class="badge bg-info">{{ $v->total_seats }} Seats</span></td>
                        <td class="fw-bold text-success">₹{{ number_format($v->charges_per_km) }}</td>
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

{{-- MODAL --}}
<div class="modal fade" id="vehicleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title" id="modalTitle">Inventory Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            {{-- 🔥 enctype Zaroori Hai images ke liye --}}
            <form id="vehicleForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="vehicle_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Select Vendor</label>
                            <select name="vendor_id" id="vendor_select" class="form-select" required>
                                <option value="">-- Choose Vendor --</option>
                                @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}" data-type="{{ $vendor->vendorType->name ?? 'Flight' }}">
                                    {{ $vendor->name }} ({{ $vendor->vendorType->name ?? 'Unknown' }})
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

                        <div class="col-md-6">
                                <label class="form-label fw-bold">Vehicle Model Name</label>
                                <input type="text" name="model_name" id="model_name_input" class="form-control" placeholder="e.g. Swift Dzire, Innova">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-bold">Luggage Allowed</label>
                                <input type="text" name="luggage_allowed" id="luggage_input" class="form-control" placeholder="e.g. 2 Bags, 4 Bags">
                            </div>

                        <div class="col-md-3">
                            <label class="form-label fw-bold">Charges per/KM (₹)</label>
                            <input type="number" name="base_fare" id="charges_per_km" class="form-control" required placeholder="e.g. 15" step="0.01">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold">Seat Category</label>
                            <input list="seat_category_list" name="seat_type" id="seat_type_input" class="form-control" placeholder="Type or select category..." required>
                            <datalist id="seat_category_list">
                                @foreach($seatCategories as $cat)
                                    <option value="{{ $cat->category_name }}">
                                @endforeach
                            </datalist>
                        </div>

                        {{-- 🔥 NEW: Image Upload Section --}}
                        <div class="col-md-6 border-top pt-3 mt-3">
                            <label class="form-label fw-bold text-primary">Main Vehicle Image</label>
                            <input type="file" class="form-control" name="main_image" id="main_image_input" accept="image/*">
                            <div id="main_image_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>

                        <div class="col-md-6 border-top pt-3 mt-3">
                            <label class="form-label fw-bold text-primary">Gallery Images (Multiple)</label>
                            <input type="file" class="form-control" name="gallery_images[]" id="gallery_input" multiple accept="image/*">
                            <div id="gallery_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>

                        <div class="col-md-12 border-top pt-3 mt-3">
                            <label class="form-label fw-bold d-block text-primary">Available Amenities</label>
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

    let table = $('#vehicleTable').DataTable({
        "dom": '<"d-flex justify-content-between mb-3"Bf>rtip',
        "buttons": getButtons('Active Inventory')
    });

    let tTable = $('#trashTable').DataTable({
        "dom": '<"d-flex justify-content-between mb-3"Bf>rtip',
        "buttons": getButtons('Trashed Inventory')
    });

    // 2. Vendor Type Auto-Fill Logic
    $('#vendor_select').on('change', function() {
        let type = $(this).find(':selected').data('type');
        let mapType = "";
        if(type && type.includes("Airline") || type && type.includes("Flight")) mapType = "Flight";
        else if(type && type.includes("Bus")) mapType = "Bus";
        else if(type && type.includes("Train")) mapType = "Train";
        else mapType = type; // Fallback
        
        $('#vehicle_type_input').val(mapType);
    });

    // 🔥 Image Preview Logic Helper Function
    function createImagePreview(src, containerId, isServer = false, imgId = null) {
        let html = `
            <div class="position-relative d-inline-block m-1 shadow-sm rounded" style="width:70px; height:70px;" data-id="${imgId}">
                <span class="remove-img-btn position-absolute top-0 end-0 bg-danger text-white rounded-circle px-1" 
                      style="cursor:pointer; font-size:12px; z-index:10;" data-type="${isServer ? 'server' : 'local'}">&times;</span>
                <img src="${src}" style="width:100%; height:100%; object-fit:cover;" class="rounded border">
                ${isServer ? `<input type="hidden" name="existing_images[]" value="${imgId}">` : ''}
            </div>`;
        $(containerId).append(html);
    }

    $('#main_image_input').on('change', function() {
        $('#main_image_preview').empty();
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => createImagePreview(e.target.result, '#main_image_preview', false);
            reader.readAsDataURL(file);
        }
    });

    $('#gallery_input').on('change', function() {
        $('#gallery_preview').empty(); // Fresh upload pe purana preview clear karna
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => createImagePreview(e.target.result, '#gallery_preview', false);
            reader.readAsDataURL(file);
        });
    });

    $(document).on('click', '.remove-img-btn', function() {
        const type = $(this).data('type');
        if(type === 'server') {
            // Server image hatane par form mein hidden input daal dete hain taaki backend usko pakad sake
            $('#vehicleForm').append(`<input type="hidden" name="deleted_images[]" value="${$(this).parent().data('id')}">`);
        } else {
            // Local wale par click ho toh just DOM element hatao, backend nahi bhejna
        }
        $(this).parent().remove();
    });


    // 3. Add & Edit Handlers
    $('#addVehicleBtn').click(function() {
        $('#vehicleForm')[0].reset();
        $('#vehicle_id').val('');
        $('.form-check-input').prop('checked', false);
        
        // Reset image previews
        $('#main_image_preview, #gallery_preview').empty();
        $('#vehicleForm').find('input[name="deleted_images[]"]').remove();

        $('#modalTitle').text('Add New Inventory');
        $('#saveBtn').text('Save Inventory');
        $('#vehicleModal').modal('show');
    });

    $(document).on('click', '.edit-vehicle', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/vehicles') }}/" + id + "/edit", function(v) {
            $('#vehicleForm')[0].reset(); // form pehle reset karna theek rehta hai
            $('#vehicleForm').find('input[name="deleted_images[]"]').remove(); // clear old hidden inputs

            $('#vehicle_id').val(v.id);
            $('#vendor_select').val(v.vendor_id).trigger('change');
            $('input[name="vehicle_number"]').val(v.vehicle_number);
            $('input[name="total_seats"]').val(v.total_seats);
            $('input[name="base_fare"]').val(v.charges_per_km); 
            $('#seat_type_input').val(v.seat_type);
            $('input[name="model_name"]').val(v.model_name);
            $('input[name="luggage_allowed"]').val(v.luggage_allowed);

            // Amenities logic
            $('.form-check-input').prop('checked', false);
            if(v.amenities) {
                v.amenities.forEach(am => {
                    $(`#am_${am.id}`).prop('checked', true);
                });
            }

            // 🔥 Image Previews Load on Edit
            $('#main_image_preview, #gallery_preview').empty();
            if(v.images) {
                v.images.forEach(img => {
                    let folder = img.image_type == 'main' ? 'vehicles' : 'vehicles/gallery';
                    let path = `{{ asset('uploads') }}/${folder}/${img.filename}`;
                    createImagePreview(path, img.image_type == 'main' ? '#main_image_preview' : '#gallery_preview', true, img.id);
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
        
        // FormData is required to send files
        let formData = new FormData(this);
        if(id) formData.append('_method', 'PUT');

        $.ajax({
            url: url, 
            method: "POST", // Laravel spoofing used
            data: formData,
            contentType: false, 
            processData: false,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) { alert(res.message); location.reload(); },
            error: function(xhr) { 
                let errorMsg = 'Error occurred while saving!';
                if(xhr.responseJSON && xhr.responseJSON.message) errorMsg = xhr.responseJSON.message;
                alert(errorMsg); 
            }
        });
    });

    // 5. Trash Actions
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

    $(document).on('click', '.restore-vehicle', function() {
        $.get("{{ url('admin/vehicles/restore') }}/" + $(this).data('id'), function() {
            location.reload();
        });
    });

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

    $('#restoreAllBtn').on('click', function() {
        if (tTable.rows().count() === 0) { alert('Trash is already empty!'); return false; }
        if(confirm('Restore all items from trash?')) {
            $.get("{{ url('admin/vehicles/restore-all') }}", function() { location.reload(); });
        }
    });

    $('#emptyTrashBtn').on('click', function() {
        if (tTable.rows().count() === 0) { alert('Trash is already empty!'); return false; }
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