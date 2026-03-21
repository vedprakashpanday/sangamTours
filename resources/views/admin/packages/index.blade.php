@extends('admin.common_layout')

@section('admin_content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 fw-bold text-dark">Tour Packages</h5>
        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPackageModal">
            <i class='bx bx-plus'></i> Add New Package
        </button>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="packagesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Package Name</th>
                        <th>Country/State</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
    @foreach($packages as $package)
    <tr>
        <td>{{ $loop->iteration }}</td>
        <td>
            <div class="d-flex align-items-center">
             @php
    // Sirf 'main' type wali image uthao
    $mainImg = $package->images->where('image_type', 'main')->first();
    $imagePath = ($mainImg && file_exists(public_path('uploads/packages/'.$mainImg->filename))) 
                 ? asset('uploads/packages/'.$mainImg->filename) 
                 : asset('no-image.png'); // Ek default image rakhein
@endphp
<img src="{{ $imagePath }}" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                <div>
                    <span class="fw-bold d-block">{{ $package->title }}</span>
                    <small class="text-muted">{{ $package->package_id }}</small>
                </div>
            </div>
        </td>
        <td>
            {{ $package->location->country_name ?? 'N/A' }} / {{ $package->location->state_name ?? 'N/A' }}
        </td>
        <td>
            @if($package->discount_price)
                <del class="text-danger small">₹{{ number_format($package->price) }}</del><br>
                <span class="fw-bold text-success">₹{{ number_format($package->discount_price) }}</span>
            @else
                <span class="fw-bold">₹{{ number_format($package->price) }}</span>
            @endif
        </td>
        <td>
            @if($package->status)
                <span class="badge bg-success">Active</span>
            @else
                <span class="badge bg-danger">Inactive</span>
            @endif
        </td>
        <td>
            <div class="btn-group">
                <button class="btn btn-sm btn-outline-primary edit-package" data-id="{{ $package->id }}">
                    <i class='bx bx-edit'></i>
                </button>
                <button class="btn btn-sm btn-outline-danger delete-package" data-id="{{ $package->id }}">
                    <i class='bx bx-trash'></i>
                </button>
            </div>
        </td>
    </tr>
    @endforeach
</tbody>
            </table>
        </div>
    </div>
</div>


<hr class="my-5">

<div class="card border-0 shadow-sm border-top border-danger border-3">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 fw-bold text-danger">Trashed Packages (Deleted)</h5>
        <div>
            <button class="btn btn-outline-success btn-sm me-2" id="restoreAll">
                <i class='bx bx-undo'></i> Restore All
            </button>
            <button class="btn btn-outline-danger btn-sm" id="emptyTrash">
                <i class='bx bx-trash'></i> Empty Trash
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="deletedPackagesTable" class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Package Name</th>
                        <th>Price</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trashedPackages as $tp)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $tp->title }} <br><small class="text-muted">{{ $tp->package_id }}</small></td>
                        <td>₹{{ number_format($tp->price) }}</td>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-success restore-package" data-id="{{ $tp->id }}" title="Restore"><i class='bx bx-undo'></i></button>
                                <button class="btn btn-sm btn-danger force-delete-package" data-id="{{ $tp->id }}" title="Delete Permanently"><i class='bx bx-x-circle'></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



<div class="modal fade" id="addPackageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold">Create New Tour Package</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="packageForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Package Title</label>
                            <input type="text" class="form-control" name="title" placeholder="e.g. 5 Days Dubai Special">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Package Price (Starting From)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" name="price" placeholder="e.g. 15000">
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Offer/Discount Price (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" name="discount_price" placeholder="e.g. 12000">
                            </div>
                        </div>

                        <div class="col-md-12">
                            <label class="form-label fw-semibold">Package Overview / Details</label>
                            <textarea class="form-control" name="details" rows="3" placeholder="Describe the package highlights..."></textarea>
                        </div>

                        <div class="col-md-4">
    <label class="form-label fw-semibold">Country</label>
    <select class="form-select" id="country_select" name="country_name">
        <option value="">Select Country</option>
        @foreach($countries as $c)
            <option value="{{ $c->country_name }}">{{ $c->country_name }}</option>
        @endforeach
    </select>
</div>
<div class="col-md-4">
    <label class="form-label fw-semibold">State</label>
    <select class="form-select" id="state_select" name="state_name">
        <option value="">Select State</option>
    </select>
</div>
<div class="col-md-4">
    <label class="form-label fw-semibold">Location Name</label>
    <select class="form-select" id="location_id_select" name="state_id"> <option value="">Select Location</option>
    </select>
</div>

                        <div class="col-md-5">
    <label class="form-label fw-semibold">Main Featured Image</label>
    <input type="file" class="form-control" name="main_image" id="main_image_input" accept="image/*">
    <div id="main_image_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
</div>

<div class="col-md-5">
    <label class="form-label fw-semibold">Gallery Images (Multiple)</label>
    <input type="file" class="form-control" name="gallery_images[]" id="gallery_input" accept="image/*" multiple>
    <div id="gallery_preview" class="mt-2 d-flex flex-wrap gap-2"></div>
</div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold d-block">Status</label>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="status" id="packageStatus" checked style="width: 40px; height: 20px;">
                                <label class="form-check-label ms-2" for="packageStatus">Active</label>
                            </div>
                        </div>

                        <hr class="my-3">

                        <div class="col-12">
                            <h6 class="fw-bold mb-3 text-primary"><i class='bx bx-map-pin'></i> Stay & Itinerary Details</h6>
                            <div id="stay_container">
                                <div class="row g-2 mb-2 align-items-end stay-row">
                                    <div class="col-md-3">
                                        <label class="small text-muted">Days</label>
                                        <input type="number" name="days[]" class="form-control form-control-sm" placeholder="Days">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="small text-muted">Nights</label>
                                        <input type="number" name="nights[]" class="form-control form-control-sm" placeholder="Nights">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted">Place Description</label>
                                        <input type="text" name="place[]" class="form-control form-control-sm" placeholder="e.g. Luxury Hotel in Manali">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-success btn-sm add-stay-row"><i class='bx bx-plus'></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Package</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
$(document).ready(function() {
  // 1. DataTable Initialization (Active Table)
let table = $('#packagesTable').DataTable({
    "pageLength": 10,
    "dom": '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between"ip>',
    "buttons": [{
    extend: 'excelHtml5',
    text: '<i class="bx bxs-file-export"></i> Export Excel',
    className: 'btn btn-success btn-sm border-0',
    title: 'Active Tour Packages',
    exportOptions: {
        columns: ':not(:last-child)'
    },
    // 🔥 Blank data check logic
    action: function (e, dt, node, config) {
        if (dt.rows().count() === 0) {
            alert('Export failed: No data available in the table!');
            return false;
        }
        // Agar data hai toh default excel action call karo
        $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
    }
}]
});

// 1.1 Trashed Table Initialization
let trashedTable = $('#deletedPackagesTable').DataTable({
    "pageLength": 5,
    "dom": '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between"ip>',
   "buttons": [{
    extend: 'excelHtml5',
    text: '<i class="bx bxs-file-export"></i> Export Excel',
    className: 'btn btn-success btn-sm border-0',
    title: 'Trashed Tour Packages',
    exportOptions: {
        columns: ':not(:last-child)'
    },
    // 🔥 Blank data check logic
    action: function (e, dt, node, config) {
        if (dt.rows().count() === 0) {
            alert('Export failed: Trash is empty!');
            return false;
        }
        // Agar data hai toh default excel action call karo
        $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
    }
}],
    "language": {
        "search": "_INPUT_",
        "searchPlaceholder": "Search in trash..."
    }
});

    // --- HELPER: Stay/Itinerary Rows ---
    function appendStayRow(days, nights, place, isFirst) {
        let row = `
            <div class="row g-2 mb-2 align-items-end stay-row shadow-sm p-2 rounded bg-light border-start border-primary border-3">
                <div class="col-md-3"><label class="small text-muted">Days</label><input type="number" name="days[]" class="form-control form-control-sm" value="${days}"></div>
                <div class="col-md-3"><label class="small text-muted">Nights</label><input type="number" name="nights[]" class="form-control form-control-sm" value="${nights}"></div>
                <div class="col-md-4"><label class="small text-muted">Description</label><input type="text" name="place[]" class="form-control form-control-sm" value="${place}"></div>
                <div class="col-md-2">
                    ${isFirst ? 
                        `<button type="button" class="btn btn-success btn-sm add-stay-row"><i class='bx bx-plus'></i></button>` : 
                        `<button type="button" class="btn btn-danger btn-sm remove-stay-row"><i class='bx bx-minus'></i></button>`}
                </div>
            </div>`;
        $('#stay_container').append(row);
    }

    $(document).on('click', '.add-stay-row', function(e) {
        e.preventDefault();
        appendStayRow('', '', '', false);
    });

    $(document).on('click', '.remove-stay-row', function() {
        $(this).closest('.stay-row').remove();
    });

    // --- DROPDOWN LOGIC: Country -> State -> Location ---

    $('#country_select').on('change', function() {
        let country = $(this).val();
        let stateSelect = $('#state_select');
        let citySelect = $('#location_id_select');

        stateSelect.html('<option value="">Loading...</option>');
        citySelect.html('<option value="">Select Location</option>');

        if(country) {
            $.ajax({
                url: "{{ url('admin/get-states-by-country') }}/" + encodeURIComponent(country),
                method: 'GET',
                success: function(res) {
                    let html = '<option value="">Select State</option>';
                    if(res.length > 0) {
                        res.forEach(s => {
                            html += `<option value="${s.state_name}">${s.state_name}</option>`;
                        });
                    } else {
                        html = '<option value="">No States Found</option>';
                    }
                    stateSelect.html(html);

                    let pendingState = stateSelect.attr('data-pending');
                    if(pendingState) {
                        stateSelect.val(pendingState).trigger('change');
                        stateSelect.removeAttr('data-pending');
                    }
                },
                error: function(err) {
                    console.error("AJAX Error:", err);
                    stateSelect.html('<option value="">Error Loading</option>');
                }
            });
        } else {
            stateSelect.html('<option value="">Select State</option>');
        }
    });

    $('#state_select').on('change', function() {
        let state = $(this).val();
        let citySelect = $('#location_id_select');

        citySelect.html('<option value="">Loading...</option>');

        if(state) {
            $.ajax({
                url: "{{ url('admin/get-cities-by-state') }}/" + encodeURIComponent(state),
                method: 'GET',
                success: function(res) {
                    let html = '<option value="">Select Location</option>';
                    if(res.length > 0) {
                        res.forEach(c => {
                            html += `<option value="${c.id}">${c.city_location}</option>`;
                        });
                    } else {
                        html = '<option value="">No Cities Found</option>';
                    }
                    citySelect.html(html);

                    let pendingCity = citySelect.attr('data-pending');
                    if(pendingCity) {
                        citySelect.val(pendingCity);
                        citySelect.removeAttr('data-pending');
                    }
                },
                error: function(err) {
                    console.error("AJAX Error:", err);
                    citySelect.html('<option value="">Error Loading</option>');
                }
            });
        } else {
            citySelect.html('<option value="">Select Location</option>');
        }
    });

    // --- EDIT LOGIC ---
    $(document).on('click', '.edit-package', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        $('#packageForm')[0].reset();
        $('#stay_container').empty();
        $('#main_image_preview, #gallery_preview').empty();

        $.get("{{ url('admin/packages') }}/" + id + "/edit", function(p) {
            $('input[name="title"]').val(p.title);
            $('input[name="price"]').val(p.price);
            $('input[name="discount_price"]').val(p.discount_price);
            $('textarea[name="details"]').val(p.details);
            $('input[name="status"]').prop('checked', p.status == 1);

            if ($('#package_db_id').length === 0) {
                $('#packageForm').prepend(`<input type="hidden" name="id" id="package_db_id" value="${p.id}">`);
            } else {
                $('#package_db_id').val(p.id);
            }

            if(p.images && p.images.length > 0) {
                p.images.forEach(img => {
                    let folder = img.image_type === 'main' ? 'packages' : 'packages/gallery';
                    let path = `/uploads/${folder}/${img.filename}`;
                    if(img.image_type === 'main') {
                        createImagePreview(path, '#main_image_preview', true, img.id);
                    } else {
                        createImagePreview(path, '#gallery_preview', true, img.id);
                    }
                });
            }

            if (p.location) {
                $('#state_select').attr('data-pending', p.location.state_name);
                $('#location_id_select').attr('data-pending', p.location_id);
                $('#country_select').val(p.location.country_name).trigger('change');
            }

            if(p.stays && p.stays.length > 0) {
                p.stays.forEach((stay, index) => {
                    appendStayRow(stay.days, stay.nights, stay.place_description, index === 0);
                });
            } else {
                appendStayRow('', '', '', true);
            }

            $('#addPackageModal').modal('show');
        }).fail(function() {
            alert("Data fetch failed!");
        });
    });

    // --- DELETE LOGIC (Move to Trash) ---
    $(document).on('click', '.delete-package', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to move this package to trash?')) {
            $.ajax({
                url: "{{ url('admin/packages') }}/" + id,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    if(res.status === 'success') {
                        alert(res.message);
                        location.reload();
                    }
                }
            });
        }
    });

    // --- TRASH ACTIONS (New Logic Added) ---

    // 1. Restore Single
    $(document).on('click', '.restore-package', function() {
        let id = $(this).data('id');
        if (confirm('Are you sure you want to restore this package?')) {
            $.get("{{ url('admin/packages/restore') }}/" + id, function(res) {
                alert(res.message);
                location.reload();
            });
        }
    });

    // 2. Permanent Delete Single
    $(document).on('click', '.force-delete-package', function() {
        let id = $(this).data('id');
        if (confirm('CRITICAL: This will permanently delete the package and all images. Continue?')) {
            $.ajax({
                url: "{{ url('admin/packages/force-delete') }}/" + id,
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    alert(res.message);
                    location.reload();
                }
            });
        }
    });

    // 3. Restore All
    $('#restoreAll').on('click', function() {
        if (confirm('Restore all trashed packages?')) {
            $.get("{{ url('admin/packages/restore-all') }}", function(res) {
                alert(res.message);
                location.reload();
            });
        }
    });

    // 4. Empty Trash (Delete All Permanently)
    $('#emptyTrash').on('click', function() {
        if (confirm('CRITICAL: Permanently delete all packages in trash? This cannot be undone.')) {
            $.ajax({
                url: "{{ url('admin/packages/empty-trash') }}",
                method: 'DELETE',
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                success: function(res) {
                    alert(res.message);
                    location.reload();
                }
            });
        }
    });

    // --- RESET / ADD NEW MODAL ---
    $('[data-bs-target="#addPackageModal"]').on('click', function() {
        $('#packageForm')[0].reset();
        $('#package_db_id').remove();
        $('#addPackageModal .modal-title').text('Create New Tour Package');
        $('#packageForm button[type="submit"]').text('Save Package');
        $('#stay_container').empty();
        $('#main_image_preview, #gallery_preview').empty();
        $('#state_select').html('<option value="">Select State</option>');
        $('#location_id_select').html('<option value="">Select Location</option>');
        appendStayRow('', '', '', true);
    });

    // --- IMAGE PREVIEW LOGIC ---
    function createImagePreview(src, containerId, isServerImage = false, imageId = null) {
        let html = `
            <div class="image-preview-wrapper" data-id="${imageId}">
                <span class="remove-img-btn" data-type="${isServerImage ? 'server' : 'local'}">&times;</span>
                <img src="${src}" style="width:80px; height:80px; object-fit:cover;" class="rounded border">
                ${isServerImage ? `<input type="hidden" name="existing_images[]" value="${imageId}">` : ''}
            </div>`;
        $(containerId).append(html);
    }

    $('#main_image_input').on('change', function() {
        $('#main_image_preview').empty();
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = (e) => createImagePreview(e.target.result, '#main_image_preview');
            reader.readAsDataURL(file);
        }
    });

    $('#gallery_input').on('change', function() {
        $('#gallery_preview').empty();
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = (e) => createImagePreview(e.target.result, '#gallery_preview');
            reader.readAsDataURL(file);
        });
    });

    $(document).on('click', '.remove-img-btn', function() {
        const wrapper = $(this).closest('.image-preview-wrapper');
        if ($(this).data('type') === 'server') {
            let id = wrapper.data('id');
            $('#packageForm').append(`<input type="hidden" name="deleted_images[]" value="${id}">`);
        }
        wrapper.remove();
    });

    // --- FORM SUBMIT (Create & Update) ---
    $('#packageForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#package_db_id').val();
        let formData = new FormData(this);
        let ajaxUrl = id ? "{{ url('admin/packages') }}/" + id : "{{ route('admin.packages.store') }}";
        
        if(id) formData.append('_method', 'PUT');

        $.ajax({
            url: ajaxUrl,
            method: "POST",
            data: formData,
            contentType: false,
            processData: false,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: function(res) {
                if(res.status === 'success') {
                    alert(res.message);
                    location.reload();
                }
            },
            error: function(err) {
                alert("Something went wrong!");
            }
        });
    });
});
</script>
@endpush