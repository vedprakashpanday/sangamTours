@extends('admin.common_layout')

@section('admin_content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 fw-bold text-dark">Manage Accommodations (Hotels)</h5>
        <button class="btn btn-primary btn-sm" id="addAccBtn">
            <i class='bx bx-plus'></i> Add New Hotel
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="accTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hotel Name</th>
                        <th>Location</th>
                        <th>Price/Night</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accommodations as $acc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                @php
                                    $mainImg = $acc->images->where('image_type', 'main')->first();
                                    $imagePath = ($mainImg && file_exists(public_path('uploads/accommodations/'.$mainImg->filename))) 
                                                 ? asset('uploads/accommodations/'.$mainImg->filename) 
                                                 : asset('no-image.png');
                                @endphp
                                <img src="{{ $imagePath }}" class="rounded me-2" style="width: 45px; height: 45px; object-fit: cover;">
                                <div>
                                    <span class="fw-bold d-block">{{ $acc->name }}</span>
                                    <small class="badge bg-light text-dark border">{{ $acc->hotel_type }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $acc->location->country_name ?? 'N/A' }} / {{ $acc->location->state_name ?? 'N/A' }}</td>
                        <td>₹{{ number_format($acc->price_per_night) }}</td>
                        <td>
                            <span class="badge {{ $acc->status ? 'bg-success' : 'bg-danger' }}">
                                {{ $acc->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary edit-acc" data-id="{{ $acc->id }}"><i class='bx bx-edit'></i></button>
                                <button class="btn btn-sm btn-outline-danger delete-acc" data-id="{{ $acc->id }}"><i class='bx bx-trash'></i></button>
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
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 fw-bold text-danger">Trash / Deleted Hotels</h5>
        <div>
            <button class="btn btn-outline-success btn-sm me-2" id="restoreAllAcc"><i class='bx bx-undo'></i> Restore All</button>
            <button class="btn btn-outline-danger btn-sm" id="emptyAccTrash"><i class='bx bx-trash'></i> Empty Trash</button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="trashedAccTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hotel Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trashedAccommodations as $tacc)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tacc->name }} <br><small class="text-muted">{{ $tacc->hotel_type }}</small></td>
                        <td>₹{{ number_format($tacc->price_per_night) }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-success restore-acc" data-id="{{ $tacc->id }}"><i class='bx bx-undo'></i></button>
                                <button class="btn btn-sm btn-danger force-delete-acc" data-id="{{ $tacc->id }}"><i class='bx bx-x-circle'></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="accModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="accModalTitle">Add New Accommodation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="accForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="acc_db_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold">Hotel Name</label>
                            <input type="text" class="form-control" name="name" required placeholder="e.g. Hotel Sangam Grand">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Hotel Type/Rating</label>
                            <select class="form-select" name="hotel_type" required>
                                <option value="">Select Type</option>
                                <option value="5 Star">5 Star</option>
                                <option value="4 Star">4 Star</option>
                                <option value="3 Star">3 Star</option>
                                <option value="Resort">Resort</option>
                                <option value="Guest House">Guest House</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Price Per Night</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" name="price" required placeholder="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="status" id="accStatus" checked>
                                <label class="form-check-label ms-2" for="accStatus">Available for Booking</label>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Country</label>
                            <select class="form-select" id="country_select" name="country_name" required>
                                <option value="">Select Country</option>
                                @foreach($countries as $c)
                                    <option value="{{ $c->country_name }}">{{ $c->country_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">State</label>
                            <select class="form-select" id="state_select" name="state_name" required>
                                <option value="">Select State</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">City/Location</label>
                            <select class="form-select" id="location_id_select" name="state_id" required>
                                <option value="">Select City</option>
                            </select>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" name="description" rows="3" placeholder="Hotel amenities, rules, etc."></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Main Image</label>
                            <input type="file" class="form-control" name="main_image" id="main_image_input" accept="image/*">
                            <div id="main_image_preview" class="mt-2"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Gallery Images</label>
                            <input type="file" class="form-control" name="gallery_images[]" id="gallery_input" multiple accept="image/*">
                            <div id="gallery_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Accommodation</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    // Helper function for Excel Export blank check
    function exportWithCheck(e, dt, node, config, alertMsg) {
        if (dt.rows().count() === 0) {
            alert(alertMsg);
            return false;
        }
        $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
    }

    // 1. DataTables Init
    let table = $('#accTable').DataTable({
        "pageLength": 10,
        "dom": '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between"ip>',
        "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="bx bxs-file-export"></i> Export Excel',
            className: 'btn btn-success btn-sm border-0',
            exportOptions: { columns: ':not(:last-child)' },
            action: function(e, dt, node, config) {
                exportWithCheck.call(this, e, dt, node, config, 'Export failed: No hotels found!');
            }
        }]
    });

    let trashedTable = $('#trashedAccTable').DataTable({
        "pageLength": 5,
        "dom": '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between"ip>',
        "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="bx bxs-file-export"></i> Export Excel',
            className: 'btn btn-success btn-sm border-0',
            exportOptions: { columns: ':not(:last-child)' },
            action: function(e, dt, node, config) {
                exportWithCheck.call(this, e, dt, node, config, 'Export failed: Trash is already empty!');
            }
        }]
    });

    // 2. Dependent Dropdowns
    $('#country_select').on('change', function() {
        let country = $(this).val();
        let stateSelect = $('#state_select');
        stateSelect.html('<option value="">Loading...</option>');
        $('#location_id_select').html('<option value="">Select City</option>');
        
        if(country) {
            $.get("{{ url('admin/get-states-by-country') }}/" + encodeURIComponent(country), function(res) {
                let html = '<option value="">Select State</option>';
                res.forEach(s => html += `<option value="${s.state_name}">${s.state_name}</option>`);
                stateSelect.html(html);
                if(stateSelect.attr('data-pending')) {
                    stateSelect.val(stateSelect.attr('data-pending')).trigger('change');
                    stateSelect.removeAttr('data-pending');
                }
            });
        }
    });

    $('#state_select').on('change', function() {
        let state = $(this).val();
        let citySelect = $('#location_id_select');
        citySelect.html('<option value="">Loading...</option>');
        
        if(state) {
            $.get("{{ url('admin/get-cities-by-state') }}/" + encodeURIComponent(state), function(res) {
                let html = '<option value="">Select City</option>';
                res.forEach(c => html += `<option value="${c.id}">${c.city_location}</option>`);
                citySelect.html(html);
                if(citySelect.attr('data-pending')) {
                    citySelect.val(citySelect.attr('data-pending'));
                    citySelect.removeAttr('data-pending');
                }
            });
        }
    });

    // --- NEW: Local File Selection Preview Logic ---
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
        $('#gallery_preview').empty();
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => createImagePreview(e.target.result, '#gallery_preview', false);
            reader.readAsDataURL(file);
        });
    });

    // 3. Add & Edit Actions
    $('#addAccBtn').click(function() {
        $('#accForm')[0].reset();
        $('#acc_db_id').val('');
        $('#main_image_preview, #gallery_preview').empty();
        $('#accModalTitle').text('Add New Accommodation');
        $('#accModal').modal('show');
    });

    $(document).on('click', '.edit-acc', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/accommodations') }}/" + id + "/edit", function(p) {
            $('#acc_db_id').val(p.id);
            $('input[name="name"]').val(p.name);
            $('select[name="hotel_type"]').val(p.hotel_type);
            $('input[name="price"]').val(p.price_per_night);
            $('textarea[name="description"]').val(p.description);
            $('#accStatus').prop('checked', p.status == 1);
            $('#accModalTitle').text('Edit Accommodation');

            $('#main_image_preview, #gallery_preview').empty();
            if(p.images) {
                p.images.forEach(img => {
                    let folder = img.image_type == 'main' ? 'accommodations' : 'accommodations/gallery';
                    let path = `{{ asset('uploads') }}/${folder}/${img.filename}`;
                    createImagePreview(path, img.image_type == 'main' ? '#main_image_preview' : '#gallery_preview', true, img.id);
                });
            }

            if(p.location) {
                $('#state_select').attr('data-pending', p.location.state_name);
                $('#location_id_select').attr('data-pending', p.location_id);
                $('#country_select').val(p.location.country_name).trigger('change');
            }
            $('#accModal').modal('show');
        });
    });

    // 4. Trash & Restore Logic
    $(document).on('click', '.delete-acc', function() {
        if(confirm('Move this hotel to trash?')) {
            $.ajax({
                url: "{{ url('admin/accommodations') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(res) { alert(res.message); location.reload(); }
            });
        }
    });

    // --- 4.1 Bulk Trash Actions (Missing Logic) ---

// // 1. Restore All Hotels
// $('#restoreAllAcc').on('click', function() {
//     if (confirm('Are you sure you want to restore all hotels from trash?')) {
//         $.get("{{ url('admin/accommodations/restore-all') }}", function(res) {
//             if(res.status === 'success') {
//                 alert(res.message);
//                 location.reload();
//             }
//         }).fail(function() {
//             alert('Restore all failed!');
//         });
//     }
// });

// // 2. Empty Trash (Permanent Delete All)
// $('#emptyAccTrash').on('click', function() {
//     if (confirm('CRITICAL: This will permanently delete ALL hotels in trash. This cannot be undone. Continue?')) {
//         $.ajax({
//             url: "{{ url('admin/accommodations/empty-trash') }}",
//             method: 'DELETE',
//             headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
//             success: function(res) {
//                 if(res.status === 'success') {
//                     alert(res.message);
//                     location.reload();
//                 }
//             },
//             error: function() {
//                 alert('Empty trash failed!');
//             }
//         });
//     }
// });


    // --- 4.1 Restore All Logic (With Blank Check) ---
$('#restoreAllAcc').on('click', function() {
    // 🔥 Check if table is empty
    if (trashedTable.rows().count() === 0) {
        alert('Trash is already empty. Nothing to restore!');
        return false;
    }

    if(confirm('Are you sure you want to restore ALL customers from trash?')) {
        $.get("{{ url('admin/accommodations/restore-all') }}", function(res) {
            if(res.status === 'success') {
                alert(res.message);
                location.reload();
            }
        }).fail(function() {
            alert('Something went wrong while restoring!');
        });
    }
});

// --- 4.2 Empty Trash Logic (With Blank Check) ---
$('#emptyAccTrash').on('click', function() {
    // 🔥 Check if table is empty
    if (trashedTable.rows().count() === 0) {
        alert('Trash is already empty!');
        return false;
    }

    if(confirm('CRITICAL: Permanently delete ALL items in trash? This cannot be undone!')) {
        $.ajax({
            url: "{{ url('admin/accommodations/empty-trash') }}",
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) {
                if(res.status === 'success') {
                    alert(res.message);
                    location.reload();
                }
            },
            error: function() {
                alert('Failed to clear trash!');
            }
        });
    }
});

    $(document).on('click', '.restore-acc', function() {
        if(confirm('Restore this record?')) {
            $.get("{{ url('admin/accommodations/restore') }}/" + $(this).data('id'), function(res) {
                alert(res.message); location.reload();
            });
        }
    });

    $(document).on('click', '.force-delete-acc', function() {
        if(confirm('PERMANENT DELETE: This cannot be undone! Continue?')) {
            $.ajax({
                url: "{{ url('admin/accommodations/force-delete') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(res) { alert(res.message); location.reload(); }
            });
        }
    });

    // 5. Image Preview Helper
    function createImagePreview(src, containerId, isServer = false, imgId = null) {
        let html = `
            <div class="image-preview-wrapper d-inline-block position-relative m-1" data-id="${imgId}">
                <span class="remove-img-btn position-absolute top-0 end-0 bg-danger text-white rounded-circle px-1" style="cursor:pointer; font-size:12px;" data-type="${isServer ? 'server' : 'local'}">&times;</span>
                <img src="${src}" style="width:80px; height:80px; object-fit:cover;" class="rounded border">
                ${isServer ? `<input type="hidden" name="existing_images[]" value="${imgId}">` : ''}
            </div>`;
        $(containerId).append(html);
    }

    $(document).on('click', '.remove-img-btn', function() {
        const type = $(this).data('type');
        if(type === 'server') {
            $('#accForm').append(`<input type="hidden" name="deleted_images[]" value="${$(this).parent().data('id')}">`);
        }
        $(this).parent().remove();
    });

    // 6. Form Submit
    $('#accForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#acc_db_id').val();
        let formData = new FormData(this);
        
        // Laravel specific method spoofing for update
        if(id) formData.append('_method', 'PUT');

        let ajaxUrl = id ? "{{ url('admin/accommodations') }}/" + id : "{{ route('admin.accommodations.store') }}";

        $.ajax({
            url: ajaxUrl,
            method: "POST", // POST hi rakhein, _method PUT handle kar lega
            data: formData,
            contentType: false, 
            processData: false,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) { 
                if(res.status === 'success') {
                    alert(res.message); 
                    location.reload(); 
                } else {
                    alert(res.message || 'Something went wrong');
                }
            },
            error: function(xhr) { 
                // Detailed error for debugging
                let errorMsg = 'Operation failed!';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                alert(errorMsg); 
                console.log(xhr.responseText);
            }
        });
    });
});
</script>
@endpush