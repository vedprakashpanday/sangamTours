@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold text-dark">Offers & Discounts</h4>
        <button class="btn btn-primary btn-sm" id="addOfferBtn">
            <i class='bx bx-plus'></i> Create New Offer
        </button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="offerTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Code</th>
                            <th>Offer Name</th>
                            <th>Discount</th>
                            <th>Min. Booking</th>
                            <th>Valid Until</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($offers as $o)
                        <tr>
                            <td><span class="badge bg-primary px-3">{{ $o->offer_code }}</span></td>
                            <td class="fw-bold">{{ $o->offer_name }}</td>
                            <td>
                                @if($o->discount_type == 'Percentage')
                                    <span class="text-success fw-bold">{{ $o->discount_value }}% OFF</span>
                                @else
                                    <span class="text-success fw-bold">₹{{ number_format($o->discount_value) }} OFF</span>
                                @endif
                            </td>
                            <td>₹{{ number_format($o->min_booking_amount) }}</td>
                            <td>{{ date('d M, Y', strtotime($o->valid_until)) }}</td>
                            <td>
                                <span class="badge {{ $o->status ? 'bg-success' : 'bg-danger' }}">
                                    {{ $o->status ? 'Active' : 'Expired/Inactive' }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-primary edit-offer" data-id="{{ $o->id }}"><i class='bx bx-edit-alt'></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-offer" data-id="{{ $o->id }}"><i class='bx bx-trash'></i></button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="offerModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Create New Offer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="offerForm">
                @csrf
                <input type="hidden" name="id" id="offer_id">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Offer Name</label>
                            <input type="text" name="offer_name" class="form-control" required placeholder="e.g. Summer Sale">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Offer Code</label>
                            <input type="text" name="offer_code" class="form-control text-uppercase" required placeholder="e.g. SUMMER10">
                        </div>
                        <div class="col-md-6">
    <label class="form-label fw-bold">Apply To (Category)</label>
    <select name="apply_to" id="apply_to" class="form-select" required>
        <option value="All">All Services</option>
        <option value="Flight">Flights Only</option>
        <option value="Bus">Buses Only</option>
        <option value="Train">Trains Only</option>
        <option value="Tour Package">Tour Packages Only</option>
    </select>
</div>

<div class="col-md-6 d-none" id="specific_item_div">
    <label class="form-label fw-bold">Select Service/Package</label>
    <select name="content_id" id="content_id" class="form-select select2-modal">
        <option value="">-- Apply to All in Category --</option>
        </select>
</div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Discount Type</label>
                            <select name="discount_type" class="form-select" required>
                                <option value="Fixed">Fixed Amount (₹)</option>
                                <option value="Percentage">Percentage (%)</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Discount Value</label>
                            <input type="number" name="discount_value" class="form-control" required placeholder="e.g. 500 or 10">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Min. Booking Amount</label>
                            <input type="number" name="min_booking_amount" class="form-control" value="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Valid Until</label>
                            <input type="date" name="valid_until" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="status" id="oStatus" value="1" checked>
                                <label class="form-check-label ms-2" for="oStatus">Active & Visible in Booking</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary w-100" id="saveBtn">Save Offer</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#offerTable').DataTable({ "dom": '<"d-flex justify-content-between mb-3"Bf>rtip', "buttons": ['excelHtml5'] });

    // Add Offer
    $('#addOfferBtn').click(function() {
        $('#offerForm')[0].reset();
        $('#offer_id').val('');
        $('#modalTitle').text('Create New Offer');
        $('#offerModal').modal('show');
    });

    // Edit Offer
    $(document).on('click', '.edit-offer', function() {
        let id = $(this).data('id');
        $.get("{{ url('admin/offers') }}/" + id + "/edit", function(o) {
            $('#offer_id').val(o.id);
            $('input[name="offer_name"]').val(o.offer_name);
            $('input[name="offer_code"]').val(o.offer_code);
            $('select[name="discount_type"]').val(o.discount_type);
            $('input[name="discount_value"]').val(o.discount_value);
            $('input[name="min_booking_amount"]').val(o.min_booking_amount);
            $('input[name="valid_until"]').val(o.valid_until);
            $('#oStatus').prop('checked', o.status == 1);
            $('#modalTitle').text('Update Offer');
            $('#offerModal').modal('show');
        });
    });

    // Form Submit
    $('#offerForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#offer_id').val();
        let url = id ? "{{ url('admin/offers') }}/" + id : "{{ route('admin.offers.store') }}";
        let formData = new FormData(this);
        if(id) formData.append('_method', 'PUT');

        $.ajax({
            url: url, method: "POST", data: formData,
            contentType: false, processData: false,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) { alert(res.message); location.reload(); },
            error: function(xhr) { alert('Error occurred!'); }
        });
    });

    // Delete Offer
    $(document).on('click', '.delete-offer', function() {
        if(confirm('Delete this offer?')) {
            $.ajax({
                url: "{{ url('admin/offers') }}/" + $(this).data('id'),
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function() { location.reload(); }
            });
        }
    });

   $('#apply_to').on('change', function() {
    let category = $(this).val();
    let itemDropdown = $('#content_id');

    if(category === 'All') {
        $('#specific_item_div').addClass('d-none');
        itemDropdown.val('').trigger('change');
    } else {
        $('#specific_item_div').removeClass('d-none');
        
        // --- AJAX Start ---
        $.ajax({
            url: "{{ url('admin/offers/get-items') }}/" + category,
            method: "GET",
            beforeSend: function() {
                itemDropdown.html('<option value="">Loading...</option>');
            },
            success: function(data) {
                let html = '<option value="">-- Apply to All ' + category + 's --</option>';
                data.forEach(function(item) {
                    html += `<option value="${item.id}">${item.name}</option>`;
                });
                itemDropdown.html(html);
                
                // Agar Select2 use kar rahe ho toh refresh zaroori hai
                itemDropdown.select2({
                    dropdownParent: $('#offerModal'),
                    width: '100%'
                });
            },
            error: function() {
                alert('Failed to fetch items. Please try again.');
            }
        });
    }
});
});
</script>
@endpush