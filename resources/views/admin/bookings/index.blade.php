@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Booking Management</h4>
        <button class="btn btn-primary" id="addBookingBtn"><i class='bx bx-plus'></i> New Booking</button>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <table id="bookingTable" class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Booking #</th>
                            <th>Customer</th>
                            <th>Service</th>
                            <th>Details</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $b)
                        <tr>
                            <td class="fw-bold text-primary">{{ $b->booking_no }}</td>
                            <td>{{ $b->customer->name }}<br><small>{{ $b->customer->phone }}</small></td>
                            <td><span class="badge bg-secondary">{{ $b->service_type }}</span></td>
                            <td>
                                @if($b->service_type == 'Tour Package')
                                    {{ $b->package->title ?? 'N/A' }}
                                @else
                                    {{ $b->route->fromCity->city_location ?? '' }} <i class='bx bx-right-arrow-alt'></i> {{ $b->route->toCity->city_location ?? '' }}
                                    <br><small class="text-muted">{{ $b->vehicle->vehicle_number ?? '' }}</small>
                                @endif
                            </td>
                            <td>
                                <b>₹{{ number_format($b->total_amount) }}</b><br>
                                <small class="text-danger">Due: ₹{{ number_format($b->due_amount) }}</small>
                            </td>
                            <td>
                                <span class="badge {{ $b->payment_status == 'Paid' ? 'bg-success' : 'bg-warning' }}">
                                    {{ $b->payment_status }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button class="btn btn-sm btn-outline-info view-booking" data-id="{{ $b->id }}"><i class='bx bx-show'></i></button>
                                    <button class="btn btn-sm btn-outline-primary edit-booking" data-id="{{ $b->id }}"><i class='bx bx-edit-alt'></i></button>
                                    <button class="btn btn-sm btn-outline-danger delete-booking" data-id="{{ $b->id }}"><i class='bx bx-trash'></i></button>
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
<div class="modal fade" id="bookingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-title fw-bold"><i class='bx bxs-edit-location me-2'></i> New Booking Search</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="bookingForm">
                @csrf
                <input type="hidden" name="id" id="booking_id">
                <div class="modal-body p-4">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-7">
                            <label class="form-label fw-bold text-secondary small">CUSTOMER / PRIMARY PASSENGER</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text bg-light"><i class='bx bx-user'></i></span>
                                <div class="flex-grow-1">
                                    <select name="customer_id" class="form-select select2-modal" required>
                                        <option value="">Search Registered Customer...</option>
                                        @foreach($customers as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-secondary small">SERVICE TYPE</label>
                            <select name="service_type" id="service_type" class="form-select fw-bold border-primary" required>
                                <option value="Flight">Flight</option>
                                <option value="Bus" selected>Bus</option>
                                <option value="Train">Train</option>
                                <option value="Tour Package">Tour Package</option>
                            </select>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 border mb-4">
                        <h6 class="fw-bold mb-3 text-dark small"><i class='bx bx-search-alt me-1'></i> SEARCH AVAILABILITY</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">From</label>
                                <select id="boarding_from" name="boarding_from" class="form-select select2-modal">
                                    <option value="">Select Origin</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->city_location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">To</label>
                                <select id="destination_to" name="destination_to" class="form-select select2-modal">
                                    <option value="">Select Destination</option>
                                    @foreach($locations as $loc)
                                        <option value="{{ $loc->id }}">{{ $loc->city_location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-bold">Travel Date</label>
                                <input type="date" name="travel_date" id="travel_date" class="form-control border-primary" required>
                            </div>
                        </div>
                    </div>

                    <div id="availabilitySection" class="mb-4 d-none">
                        <label class="form-label fw-bold text-primary small">AVAILABLE SERVICES & FARES</label>
                        <div id="scheduleResults" class="list-group shadow-sm">
                            </div>
                        <div id="noServiceMsg" class="alert alert-warning py-2 small d-none">
                            <i class='bx bx-info-circle'></i> No services found for this route and date.
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="fw-bold text-secondary small">CO-PASSENGERS DETAILS</label>
                        <button type="button" class="btn btn-sm btn-outline-primary" id="addPaxBtn"><i class='bx bx-plus'></i> Add More</button>
                    </div>
                    <div id="passengerSection" class="p-3 bg-white border rounded mb-4 d-none">
                        <div id="passengerInputs"></div>
                    </div>

                    <div class="row align-items-center mb-4">
                        <div class="col-md-6">
                            <div class="p-2 bg-light rounded border text-center">
                                <span class="small text-muted">Total Pax: </span>
                                <span class="fw-bold text-primary" id="pax_display">1</span>
                                <input type="hidden" name="pax_count" id="pax_count" value="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold text-primary">APPLY OFFER</label>
                            <select name="offer_id" id="offer_select" class="form-select select2-modal">
                                <option value="">No Offer / Remove Offer</option>
                                @foreach($offers as $off)
                                    <option value="{{ $off->id }}" data-type="{{ $off->discount_type }}" data-val="{{ $off->discount_value }}">
                                        {{ $off->offer_code }} (-{{ $off->discount_type == 'Percentage' ? $off->discount_value.'%' : '₹'.$off->discount_value }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row g-0 bg-dark text-white p-3 rounded shadow">
                        <div class="col-md-6 border-end border-secondary text-center">
                            <small class="text-secondary d-block">TOTAL PAYABLE</small>
                            <h3 class="fw-bold mb-0">₹ <span id="display_final_amount">0.00</span></h3>
                            <input type="hidden" name="total_amount" id="total_amount">
                            <input type="hidden" id="base_price_hidden" value="0">
                        </div>
                        <div class="col-md-6 ps-4">
                            <label class="small text-secondary fw-bold mb-1">CASH RECEIVED</label>
                            <input type="number" name="paid_amount" id="paid_amount" class="form-control form-control-lg bg-transparent text-white border-secondary fw-bold" placeholder="0.00">
                        </div>
                    </div>

                </div>
                
                <div class="modal-footer bg-light">
                    <button type="submit" class="btn btn-primary px-5 fw-bold w-100" id="saveBtn">
                        <i class='bx bx-check-double me-1'></i> Confirm Booking
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<style>
    .list-group-item-action { cursor: pointer; transition: 0.2s; }
    .list-group-item-action:hover { background-color: #f8f9fa; border-left: 4px solid #0d6efd; }
    .form-check-input:checked + .fw-bold { color: #0d6efd; }
    #paid_amount::placeholder { color: #6c757d; }
</style>
<style>
    .border-dashed { border-style: dashed !important; border-width: 2px !important; }
    .select2-container--bootstrap-5 .select2-selection { border-radius: 0.375rem; }
    .modal-content { border-radius: 15px; overflow: hidden; }
    /* Select2 inside input-group fix */
.input-group > .flex-grow-1 > .select2-container .select2-selection--single {
    height: 38px !important; /* Bootstrap standard height */
    border-top-left-radius: 0 !important;
    border-bottom-left-radius: 0 !important;
    border-color: #dee2e6 !important;
    display: flex;
    align-items: center;
}

.input-group-text {
    border-top-right-radius: 0 !important;
    border-bottom-right-radius: 0 !important;
}
</style>
@endsection


@push('scripts')
<script>
$(document).ready(function() {
    
    // --- 1. INITIALIZATION ---
    $('#bookingTable').DataTable({ 
        "dom": '<"d-flex justify-content-between mb-3"Bf>rtip',
        "buttons": ['excel', 'pdf', 'print']
    });

    // Master Function: Select2 ko initialize karne ke liye
    function initSelect2() {
        // Pehle check karo agar pehle se init hai toh destroy karo
        if ($('.select2-modal').data('select2')) {
            $('.select2-modal').select2('destroy');
        }

        $('.select2-modal').select2({
            dropdownParent: $('#bookingModal'),
            width: '100%',
            placeholder: "Search..."
        });
    }

    // Modal fully open hone par hi Select2 load hoga
    $('#bookingModal').on('shown.bs.modal', function () {
        initSelect2();
    });

// --- 1. SEARCH & AVAILABILITY ENGINE ---
$('#boarding_from, #destination_to, #travel_date, #service_type').on('change', function() {
    let from = $('#boarding_from').val();
    let to = $('#destination_to').val();
    let date = $('#travel_date').val();
    let type = $('#service_type').val();

    if(from && to && date) {
        $('#availabilitySection').removeClass('d-none');
        $('#scheduleResults').html('<div class="text-center p-3 small text-muted"><div class="spinner-border spinner-border-sm me-2 text-primary"></div>Calculating Route Distance...</div>');
        
        $.ajax({
            url: "{{ url('admin/check-availability') }}",
            method: "GET",
            data: {from: from, to: to, date: date, type: type},
            success: function(res) {
            console.table(res);
                let container = $('#scheduleResults').empty();
                if(res.schedules && res.schedules.length > 0) {
                    $('#noServiceMsg').addClass('d-none');
                    
                  // AJAX Success ke andar loop wala part
res.schedules.forEach(function(s) {
    // Agar fare 0 hai toh red warning, warna green price
    let fareStatus = s.fare > 0 ? 'text-success' : 'text-danger';
    let distVal = s.distance > 0 ? s.distance + ' km' : 'API Pending';

    container.append(`
        <label class="list-group-item list-group-item-action p-3 mb-2 border rounded shadow-sm">
            <div class="row align-items-center">
                <div class="col-md-1">
                    <input type="radio" name="schedule_id" value="${s.id}" data-fare="${s.fare}" class="form-check-input schedule-radio" required>
                </div>
                <div class="col-md-8 border-start">
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">${s.vendor} <small class="text-muted">(${s.vehicle_name})</small></span>
                        <span class="badge bg-light text-dark border">Distance: ${distVal}</span>
                    </div>
                    <div class="mt-2 bg-light p-2 rounded d-flex justify-content-between align-items-center">
                        <div class="small"><b>${s.from_name}</b><br><span class="text-muted">${s.departure}</span></div>
                        <i class='bx bx-right-arrow-alt fs-4 text-secondary'></i>
                        <div class="text-end small"><b>${s.to_name}</b><br><span class="text-muted">${s.arrival}</span></div>
                    </div>
                    <div class="mt-1 small italic text-muted">Rate: ₹${s.per_km}/km</div>
                </div>
                <div class="col-md-3 text-end">
                    <div class="text-muted small">COST PER HEAD</div>
                    <div class="h4 fw-bold ${fareStatus} mb-0">₹${s.fare}</div>
                </div>
            </div>
        </label>
    `);
});
                } else {
                    $('#noServiceMsg').removeClass('d-none');
                }
            }
        });
    }
});

function calculateFinalPrice() {
    let pax = parseInt($('#pax_count').val()) || 1;
    let selectedRadio = $('input[name="schedule_id"]:checked');
    let farePerPerson = parseFloat(selectedRadio.data('fare')) || 0;
    
    let totalBase = farePerPerson * pax;

    let selectedOffer = $('#offer_select').find(':selected');
    let discType = selectedOffer.data('type');
    let discVal = parseFloat(selectedOffer.data('val')) || 0;

    let finalAmount = totalBase;
    if (discVal > 0) {
        if (discType === 'Percentage') {
            finalAmount = totalBase - (totalBase * (discVal / 100));
        } else {
            finalAmount = totalBase - discVal;
        }
    }

    $('#total_amount').val(finalAmount.toFixed(2));
    $('#display_final_amount').text(finalAmount.toLocaleString('en-IN', {minimumFractionDigits: 2}));
}

// Ye trigger ensures calculation happens immediately after radio select
$(document).on('change', '.schedule-radio', function() {
    calculateFinalPrice();
});

// B. Jab passenger add ya remove ho (Aapke updatePaxCount function ke andar)
function updatePaxCount() {
    let count = 1 + $('#passengerInputs .row').length; 
    $('#pax_count').val(count);
    $('#pax_display').text(count);
    
    // 🔥 Automatic recalculate when pax count changes
    calculateFinalPrice(); 
}

// C. Jab promo code select ya clear ho
$('#offer_select').on('change', function() {
    calculateFinalPrice();
});

// D. Jab Cash Received change ho (Due amount nikalne ke liye agar zaroorat ho)
$('#paid_amount').on('input', function() {
    // Yahan aap Due calculation ka logic bhi daal sakte hain
});

// --- 3. SELECT2 WITH CLEAR OPTION ---
function initSelect2() {
    $('.select2-modal').select2({
        dropdownParent: $('#bookingModal'),
        width: '100%',
        placeholder: "Select an option",
        allowClear: true // Ye promo code remove karne ke liye 'x' button dega
    });
}

    // --- 5. MODAL & FORM CONTROL ---
    $('#addBookingBtn').click(function() {
        $('#bookingForm')[0].reset();
        $('#booking_id').val('');
        
        // Select2 ko destroy karo taaki Fresh modal pe reset ho
        if ($('.select2-modal').data('select2')) {
            $('.select2-modal').val(null).trigger('change');
        }

        $('#availabilitySection, #passengerSection').addClass('d-none');
        $('#passengerInputs').empty();
        $('#pax_count').val(1);
        $('#pax_display').text(1);
        
        $('#modalTitle').text('New Booking Search');
        $('#bookingModal').modal('show');
    });

    $('#bookingForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#booking_id').val();
        let url = id ? "{{ url('admin/bookings') }}/" + id : "{{ route('admin.bookings.store') }}";
        
        $.ajax({
            url: url,
            method: "POST",
            data: new FormData(this),
            contentType: false, processData: false,
            headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
            success: function(res) { 
                alert(res.message); 
                location.reload(); 
            },
            error: function(xhr) { 
                let err = xhr.responseJSON ? xhr.responseJSON.message : 'Error processing booking!';
                alert(err); 
            }
        });
    });
});
</script>
@endpush