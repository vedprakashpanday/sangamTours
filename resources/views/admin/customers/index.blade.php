@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark">Customer KYC & Management</h4>
            <button class="btn btn-primary" id="addCustomerBtn">
                <i class='bx bx-user-plus'></i> Register New Customer
            </button>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="customerTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Contact Info</th>
                            <th>KYC Status</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $c)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $c->customer_image ? asset('uploads/customers/kyc/'.$c->customer_image) : asset('default-user.png') }}" 
                                         class="rounded-circle me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                    <div>
                                        <span class="fw-bold d-block">{{ $c->name }}</span>
                                        <small class="text-muted">ID: #CUST-{{ $c->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <i class='bx bx-envelope small'></i> {{ $c->email }}<br>
                                <i class='bx bx-phone small'></i> {{ $c->phone }}
                            </td>
                            <td>
                                @if($c->pan_number && $c->aadhar_number)
                                    <span class="badge bg-light text-success border border-success"><i class='bx bx-check-double'></i> Verified</span>
                                @else
                                    <span class="badge bg-light text-warning border border-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                @if($c->status == 1)
                                    <span class="badge bg-success">Active</span>
                                @elseif($c->status == 2)
                                    <span class="badge bg-danger">Blocked</span>
                                @else
                                    <span class="badge bg-warning text-dark">Restricted</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary edit-customer" data-id="{{ $c->id }}"><i class='bx bx-edit-alt'></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-customer" data-id="{{ $c->id }}"><i class='bx bx-trash'></i></button>
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
        <h6 class="m-0 fw-bold text-danger">Trashed Customers</h6>
        <div>
            <button class="btn btn-sm btn-outline-success me-2" id="restoreAllBtn">
                <i class='bx bx-undo'></i> Restore All
            </button>
            <button class="btn btn-sm btn-outline-danger" id="emptyTrashBtn">
                <i class='bx bx-trash'></i> Empty Trash
            </button>
        </div>
    </div>
    <div class="card-body"> <div class="table-responsive p-3">
            <table id="trashTable" class="table table-hover align-middle">
                <thead>
                    <tr class="table-light">
                        <th>Name</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trashedCustomers as $tc)
                    <tr>
                        <td>{{ $tc->name }}</td>
                        <td>{{ $tc->phone }}</td>
                        <td>
                            <button class="btn btn-sm btn-success restore-customer" data-id="{{ $tc->id }}"><i class='bx bx-undo'></i></button>
                            <button class="btn btn-sm btn-danger force-delete-customer" data-id="{{ $tc->id }}"><i class='bx bx-x-circle'></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

<div class="modal fade" id="customerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle">Register New Customer</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="customerForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="customer_id">
                <div class="modal-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4 border-end">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Basic Information</h6>
                            <div class="mb-3 text-center">
                                <div id="profile_preview" class="mb-2">
                                    <img src="{{ asset('default-user.png') }}" class="rounded-circle border" style="width: 100px; height: 100px; object-fit: cover;">
                                </div>
                                <input type="file" name="customer_image" class="form-control form-control-sm preview-trigger" data-preview="#profile_preview">
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Phone Number</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold">Account Status</label>
                                <select name="status" class="form-select border-primary shadow-sm">
                                    <option value="1">Active</option>
                                    <option value="2">Blocked (Suspended)</option>
                                    <option value="3">Restricted (View Only)</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">KYC Documents (ID Proofs)</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border">
                                        <label class="small fw-bold">PAN Card Number</label>
                                        <input type="text" name="pan_number" class="form-control mb-2" placeholder="ABCDE1234F">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="x-small text-muted">Front Side</label>
                                                <input type="file" name="pan_front" class="form-control form-control-sm preview-trigger" data-preview="#pan_f_prev">
                                                <div id="pan_f_prev" class="mt-1 small-preview"></div>
                                            </div>
                                            <div class="col-6">
                                                <label class="x-small text-muted">Back Side</label>
                                                <input type="file" name="pan_back" class="form-control form-control-sm preview-trigger" data-preview="#pan_b_prev">
                                                <div id="pan_b_prev" class="mt-1 small-preview"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="p-3 bg-light rounded border text-primary border-primary">
                                        <label class="small fw-bold">Aadhar Card Number</label>
                                        <input type="text" name="aadhar_number" class="form-control mb-2" placeholder="1234 5678 9012">
                                        <div class="row g-2">
                                            <div class="col-6">
                                                <label class="x-small text-muted">Front Side</label>
                                                <input type="file" name="aadhar_front" class="form-control form-control-sm preview-trigger" data-preview="#aa_f_prev">
                                                <div id="aa_f_prev" class="mt-1 small-preview"></div>
                                            </div>
                                            <div class="col-6">
                                                <label class="x-small text-muted">Back Side</label>
                                                <input type="file" name="aadhar_back" class="form-control form-control-sm preview-trigger" data-preview="#aa_b_prev">
                                                <div id="aa_b_prev" class="mt-1 small-preview"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <label class="small fw-bold">Residential Address</label>
                                    <textarea name="address" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold" id="saveBtn">Confirm Registration</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .small-preview img { width: 100%; height: 60px; object-fit: cover; border: 1px dashed #ccc; border-radius: 4px; }
    .x-small { font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
    .btn-xs { padding: 2px 5px; font-size: 14px; }
</style>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // 1. Tables Init
    let table = $('#customerTable').DataTable({
        "dom": '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between"ip>',
        "buttons": [{
            extend: 'excelHtml5',
            text: '<i class="bx bxs-file-export"></i> Export',
            className: 'btn btn-success btn-sm border-0',
            exportOptions: { columns: ':not(:last-child)' },
            action: function(e, dt, node, config) {
                if (dt.rows().count() === 0) { alert('No customer data to export!'); return false; }
                $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
            }
        }],
         "language": {
        "search": "_INPUT_",
        "searchPlaceholder": "Search in Customers.."
    }
    });

    // 1. Trashed Table Initialization
let trashTable = $('#trashTable').DataTable({
    "pageLength": 5,
    "dom": '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between"ip>',
    "buttons": [{
        extend: 'excelHtml5',
        text: '<i class="bx bxs-file-export"></i> Export Trash',
        className: 'btn btn-success btn-sm border-0',
        title: 'Trashed List',
        exportOptions: { columns: ':not(:last-child)' }, // Last column (Action) export nahi hogi
        action: function(e, dt, node, config) {
            if (dt.rows().count() === 0) {
                alert('Export failed: Trash is already empty!');
                return false;
            }
            $.fn.dataTable.ext.buttons.excelHtml5.action.call(this, e, dt, node, config);
        }
    }],
    "language": {
        "search": "_INPUT_",
        "searchPlaceholder": "Search in trash..."
    }
});



   // 1. Helper function to create preview with Remove Button
function renderPreview(container, src, fieldName, isServer = false) {
    let html = `
        <div class="preview-wrapper mt-1">
            <span class="remove-preview" data-input="input[name='${fieldName}']" data-container="${container}" data-server="${isServer}" data-field="${fieldName}">&times;</span>
            <img src="${src}">
        </div>`;
    $(container).html(html);
}

// 2. File Selection Preview
$('.preview-trigger').on('change', function() {
    const container = $(this).data('preview');
    const fieldName = $(this).attr('name');
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            renderPreview(container, e.target.result, fieldName, false);
        };
        reader.readAsDataURL(file);
    }
});

    // 3. Add & Edit Handlers
    $('#addCustomerBtn').click(function() {
        $('#customerForm')[0].reset();
        $('#customer_id').val('');
        $('.small-preview, #profile_preview').empty();
        $('#modalTitle').text('Register New Customer');
        $('#customerModal').modal('show');
    });

    // 3. Remove Button Click Handler
$(document).on('click', '.remove-preview', function() {
    const inputSelector = $(this).data('input');
    const container = $(this).data('container');
    const isServer = $(this).data('server');
    const fieldName = $(this).data('field');

    // Input clear karein
    $(inputSelector).val('');
    // Preview clear karein
    $(container).empty();

    // Agar server se aayi image delete ki hai, toh hidden input add karein taaki DB mein null ho jaye
    if(isServer) {
        $('#customerForm').append(`<input type="hidden" name="removed_docs[]" value="${fieldName}">`);
    }
    
    // Default image wapas lagana agar profile photo hai
    if(container == '#profile_preview') {
        $(container).html('<img src="{{ asset("default-user.png") }}" class="rounded-circle border" style="width: 100px; height: 100px;">');
    }
});

// 4. Edit Handler Update (Server images load karne ke liye)
$(document).on('click', '.edit-customer', function() {
    let id = $(this).data('id');
    $.get("{{ url('admin/customers') }}/" + id + "/edit", function(c) {
        $('#customer_id').val(c.id);
        $('input[name="name"]').val(c.name);
        $('input[name="email"]').val(c.email);
        $('input[name="phone"]').val(c.phone);
        $('input[name="pan_number"]').val(c.pan_number);
        $('input[name="aadhar_number"]').val(c.aadhar_number);
        $('textarea[name="address"]').val(c.address);
        $('select[name="status"]').val(c.status);

        // Remove any existing "removed_docs" inputs
        $('input[name="removed_docs[]"]').remove();

        // Profile Preview
        if(c.customer_image) {
            renderPreview('#profile_preview', `/uploads/customers/kyc/${c.customer_image}`, 'customer_image', true);
        } else {
            $('#profile_preview').html('<img src="{{ asset("default-user.png") }}" class="rounded-circle border" style="width: 100px; height: 100px;">');
        }

        // KYC Previews
        if(c.pan_front) renderPreview('#pan_f_prev', `/uploads/customers/kyc/${c.pan_front}`, 'pan_front', true);
        if(c.pan_back) renderPreview('#pan_b_prev', `/uploads/customers/kyc/${c.pan_back}`, 'pan_back', true);
        if(c.aadhar_front) renderPreview('#aa_f_prev', `/uploads/customers/kyc/${c.aadhar_front}`, 'aadhar_front', true);
        if(c.aadhar_back) renderPreview('#aa_b_prev', `/uploads/customers/kyc/${c.aadhar_back}`, 'aadhar_back', true);

        $('#modalTitle').text('Update Customer Profile');
        $('#saveBtn').text('Update Customer');
        $('#customerModal').modal('show');
    });
});

    // 4. Form Submit (Store & Update)
    $('#customerForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#customer_id').val();
        let formData = new FormData(this);
        if(id) formData.append('_method', 'PUT');

        $.ajax({
            url: id ? "{{ url('admin/customers') }}/" + id : "{{ route('admin.customers.store') }}",
            method: "POST",
            data: formData,
            contentType: false, processData: false,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) { alert(res.message); location.reload(); },
            error: function(xhr) { alert(xhr.responseJSON.message || 'Error occurred!'); }
        });
    });

    // 5. Delete & Trash Actions
    $(document).on('click', '.delete-customer', function() {
        if(confirm('Move customer to trash?')) {
            $.ajax({
                url: "{{ url('admin/customers') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(res) { location.reload(); }
            });
        }
    });

    $(document).on('click', '.restore-customer', function() {
        $.get("{{ url('admin/customers/restore') }}/" + $(this).data('id'), function(res) {
            location.reload();
        });
    });

    // --- 4.1 Restore All Logic (With Blank Check) ---
$('#restoreAllBtn').on('click', function() {
    // 🔥 Check if table is empty
    if (trashTable.rows().count() === 0) {
        alert('Trash is already empty. Nothing to restore!');
        return false;
    }

    if(confirm('Are you sure you want to restore ALL customers from trash?')) {
        $.get("{{ url('admin/customers/restore-all') }}", function(res) {
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
$('#emptyTrashBtn').on('click', function() {
    // 🔥 Check if table is empty
    if (trashTable.rows().count() === 0) {
        alert('Trash is already empty!');
        return false;
    }

    if(confirm('CRITICAL: Permanently delete ALL items in trash? This cannot be undone!')) {
        $.ajax({
            url: "{{ url('admin/customers/empty-trash') }}",
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
    $(document).on('click', '.force-delete-customer', function() {
        if(confirm('PERMANENT DELETE: This will delete all KYC documents!')) {
            $.ajax({
                url: "{{ url('admin/customers/force-delete') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(res) { location.reload(); }
            });
        }
    });
});
</script>
@endpush