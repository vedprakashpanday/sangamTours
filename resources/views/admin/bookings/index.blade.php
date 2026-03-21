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
                <h5 class="modal-title fw-bold"><i class='bx bxs-plane-take-off me-2'></i> <span id="modalTitle">Create New Booking</span></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="bookingForm">
                @csrf
                <input type="hidden" name="id" id="booking_id">
                <div class="modal-body p-4">
                    
                    <div class="row g-3 mb-4">
                       <div class="col-md-7">
    <label class="form-label fw-bold text-secondary small">CUSTOMER DETAILS</label>
    <div class="input-group flex-nowrap"> <span class="input-group-text bg-light border-end-0"><i class='bx bx-user'></i></span>
        <div class="flex-grow-1"> <select name="customer_id" class="form-select select2-modal" required>
                <option value="">Search Customer...</option>
                @foreach($customers as $c)
                    <option value="{{ $c->id }}">{{ $c->name }} ({{ $c->phone }})</option>
                @endforeach
            </select>
        </div>
    </div>
</div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold text-secondary small">SERVICE TYPE</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class='bx bx-category'></i></span>
                                <select name="service_type" id="service_type" class="form-select fw-bold text-primary" required>
                                    <option value="">-- Choose --</option>
                                    <option value="Flight">Flight</option>
                                    <option value="Bus">Bus</option>
                                    <option value="Train">Train</option>
                                    <option value="Tour Package">Tour Package</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="p-3 rounded-3 bg-light border mb-4">
                        <h6 class="fw-bold mb-3 text-dark small"><i class='bx bx-map-pin me-1'></i> JOURNEY DETAILS</h6>
                        
                        <div id="transportFields" class="row g-3 d-none">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Select Route</label>
                                <select name="route_id" class="form-select select2-modal">
                                    <option value="">-- Choose Route --</option>
                                    @foreach($routes as $r)
                                        <option value="{{ $r->id }}">{{ $r->fromCity->city_location }} to {{ $r->toCity->city_location }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Service/Vehicle</label>
                                <select name="vehicle_id" id="vehicle_id" class="form-select select2-modal">
                                    <option value="">-- Select Service --</option>
                                    @foreach($vehicles as $veh)
                                       <option value="{{ $veh->id }}" data-type="{{ $veh->type }}" data-fare="{{ $veh->base_fare }}">
                                            {{ $veh->vendor->name }} - {{ $veh->vehicle_number }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div id="packageFields" class="row g-3 d-none">
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Select Tour Package</label>
                                <select name="package_id" class="form-select select2-modal">
                                    <option value="">-- Choose Package --</option>
                                    @foreach($packages as $p)
                                        <option value="{{ $p->id }}" data-price="{{ $p->price }}">{{ $p->title }} (₹{{ number_format($p->price) }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Travel Date</label>
                                <input type="date" name="travel_date" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">No. of Passengers (PAX)</label>
                                <input type="number" name="pax_count" class="form-control" value="1" min="1">
                            </div>

                            <div id="passengerSection" class="p-3 rounded-3 bg-white border mb-4">
    <h6 class="fw-bold mb-3 text-primary small"><i class='bx bx-group me-1'></i> PASSENGER DETAILS</h6>
    <div id="passengerInputs">
        <div class="row g-2 mb-2 p-2 border rounded bg-light align-items-center">
            <div class="col-md-5">
                <input type="text" name="p_name[]" class="form-control form-control-sm" placeholder="Full Name" required>
            </div>
            <div class="col-md-3">
                <input type="number" name="p_age[]" class="form-control form-control-sm" placeholder="Age" required>
            </div>
            <div class="col-md-4">
                <select name="p_gender[]" class="form-select form-select-sm" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>
    </div>
</div>
                        </div>
                    </div>

                    <div class="card border-dashed border-primary bg-light mb-4">
                        <div class="card-body p-3">
                            <div class="row align-items-center">
                                <div class="col-md-7">
                                    <label class="form-label fw-bold text-primary small"><i class='bx bxs-offer me-1'></i> APPLY OFFER / COUPON</label>
                                    <select name="offer_id" id="offer_select" class="form-select select2-modal">
                                        <option value="" data-apply-to="All" data-content-id="" data-val="0">No Offer / Skip</option>
                                        @foreach($offers as $off)
                                            <option value="{{ $off->id }}" 
                                                    data-type="{{ $off->discount_type }}" 
                                                    data-val="{{ $off->discount_value }}"
                                                    data-min="{{ $off->min_booking_amount }}"
                                                    data-apply-to="{{ $off->apply_to }}" 
                                                    data-content-id="{{ $off->content_id }}">
                                                {{ $off->offer_code }} - ({{ $off->discount_type == 'Percentage' ? $off->discount_value.'%' : '₹'.$off->discount_value }} OFF)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-5 text-end">
                                    <span class="text-muted small d-block">Base Price</span>
                                    <h5 class="fw-bold mb-0">₹ <span id="display_base_price">0.00</span></h5>
                                    <input type="hidden" id="base_price_hidden" value="0">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 bg-white p-3 rounded shadow-sm border">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small">FINAL AMOUNT</label>
                            <div class="input-group">
                                <span class="input-group-text bg-primary text-white border-primary">₹</span>
                                <input type="number" name="total_amount" id="total_amount" class="form-control fw-bold border-primary bg-white" readonly>
                            </div>
                            <small class="text-success small"><i class='bx bx-check-circle'></i> Discount Applied</small>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small">PAID AMOUNT</label>
                            <div class="input-group">
                                <span class="input-group-text bg-success text-white border-success">₹</span>
                                <input type="number" name="paid_amount" id="paid_amount" class="form-control fw-bold border-success" value="0">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark small">BALANCE DUE</label>
                            <div class="input-group">
                                <span class="input-group-text bg-danger text-white border-danger">₹</span>
                                <input type="number" name="due_amount" id="due_amount" class="form-control fw-bold border-danger bg-light" readonly>
                            </div>
                        </div>
                    </div>

                </div>
                
                <div class="modal-footer bg-light p-3">
                    <button type="button" class="btn btn-link text-muted fw-bold text-decoration-none" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm" id="saveBtn">
                        <i class='bx bx-check-double me-1'></i> Confirm & Generate Invoice
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

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
    // 1. Initialize DataTables
    $('#bookingTable').DataTable({ "dom": '<"d-flex justify-content-between mb-3"Bf>rtip' });

    // 2. Initialize Select2 for Modal (Special fix for Bootstrap Modals)
    $('.select2-modal').select2({
    dropdownParent: $('#bookingModal'),
    width: '100%' // Ye bachi hui poori width cover kar lega
});

    $('#service_type').on('change', function() {
    let type = $(this).val(); // e.g., Flight, Bus, or Train
    $('#transportFields, #packageFields').addClass('d-none');
    
    if(type === 'Tour Package') {
        $('#packageFields').removeClass('d-none');
    } else if(['Flight', 'Bus', 'Train'].includes(type)) {
        $('#transportFields').removeClass('d-none');

        // 🔥 Magic Filtering Logic
        // Pehle dropdown ko reset karo
        $('#vehicle_id option').hide(); // Saare options chhupao
        $('#vehicle_id option[value=""]').show(); // Default dikhao

        // Sirf wahi dikhao jo selected Service Type se match karein
        $(`#vehicle_id option[data-type="${type}"]`).show();
        
        // Dropdown ko reset karke Select2 refresh karo
        $('#vehicle_id').val('').trigger('change');
    }
});

    // 4. Automatic Due Calculation
    $('#total_amount, #paid_amount').on('input', function() {
        let total = parseFloat($('#total_amount').val()) || 0;
        let paid = parseFloat($('#paid_amount').val()) || 0;
        let due = total - paid;
        $('#due_amount').val(due.toFixed(2));
    });

    // 5. Package selection se price auto-fill
    $('select[name="package_id"]').on('change', function() {
        let price = $(this).find(':selected').data('price');
        if(price) {
            $('#total_amount').val(price).trigger('input');
        }
    });

    // 1. Function to Calculate Final Price
    function calculateFinalPrice() {
        let basePrice = parseFloat($('#base_price_hidden').val()) || 0;
        let selectedOffer = $('#offer_select').find(':selected');
        let discType = selectedOffer.data('type');
        let discVal = parseFloat(selectedOffer.data('val')) || 0;
        let minAmt = parseFloat(selectedOffer.data('min')) || 0;

        let finalAmount = basePrice;

        if (basePrice >= minAmt) {
            if (discType === 'Percentage') {
                finalAmount = basePrice - (basePrice * (discVal / 100));
            } else {
                finalAmount = basePrice - discVal;
            }
        } else if (discVal > 0) {
            alert('This offer requires a minimum booking of ₹' + minAmt);
            $('#offer_select').val('').trigger('change');
            return;
        }

        $('#total_amount').val(finalAmount.toFixed(2)).trigger('input');
        $('#display_base_price').text(basePrice.toLocaleString());
    }

    // 2. Fetch Base Price when Vehicle/Flight is selected
    $('#vehicle_id').on('change', function() {
        // Option mein fare data add karna hoga: data-fare="{{ $veh->base_fare }}"
        let fare = $(this).find(':selected').data('fare') || 0;
        $('#base_price_hidden').val(fare);
        calculateFinalPrice();
    });

    // 3. Fetch Base Price when Package is selected
    $('select[name="package_id"]').on('change', function() {
        let price = $(this).find(':selected').data('price') || 0;
        $('#base_price_hidden').val(price);
        calculateFinalPrice();
    });

    // 4. Trigger calculation when Offer is changed
    $('#offer_select').on('change', function() {
        calculateFinalPrice();
    });

    // 5. Due Amount calculation (Purana wala logic)
    $('#total_amount, #paid_amount').on('input', function() {
        let total = parseFloat($('#total_amount').val()) || 0;
        let paid = parseFloat($('#paid_amount').val()) || 0;
        $('#due_amount').val((total - paid).toFixed(2));
    });

    // 6. Add Booking
    $('#addBookingBtn').click(function() {
        $('#bookingForm')[0].reset();
        $('#booking_id').val('');
        $('.select2-modal').val(null).trigger('change');
        $('#modalTitle').text('Create New Booking');
        $('#bookingModal').modal('show');
    });

    // 7. Form Submit Logic
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
            error: function(xhr) { alert('Error processing booking!'); }
        });
    });

   $('#service_type').on('change', function() {
    let selectedService = $(this).val(); // Jaise 'Flight' ya 'Bus'
    
    // 1. Offer dropdown ko reset karo
    $('#offer_select').val('').trigger('change');
    
    // 2. Loop chalao saare options par
    $('#offer_select option').each(function() {
        let applyTo = $(this).data('apply-to'); // Offer ki category
        
        // Logic: Agar category 'All' hai YA selected service se match karti hai
        if (applyTo === 'All' || applyTo === selectedService) {
            $(this).prop('disabled', false); // Show/Enable
            $(this).show(); 
        } else {
            $(this).prop('disabled', true); // Hide/Disable
            $(this).hide();
        }
    });

    // Select2 ko refresh karna zaroori hai taaki changes dikhen
    $('#offer_select').select2({
        dropdownParent: $('#bookingModal'),
        width: '100%'
    });
});

function validateSpecificOffer() {
    let selectedOffer = $('#offer_select').find(':selected');
    let restrictedId = selectedOffer.data('content-id'); // Offer kis ID ke liye hai
    let applyTo = selectedOffer.data('apply-to');
    
    let currentItemId = "";
    if(applyTo === 'Tour Package') {
        currentItemId = $('select[name="package_id"]').val();
    } else {
        currentItemId = $('#vehicle_id').val();
    }

    // Agar offer kisi specific item ke liye hai aur wo match nahi kar raha
    if (restrictedId && restrictedId != currentItemId) {
        alert("Sorry! This offer is only valid for a specific service/package.");
        $('#offer_select').val('').trigger('change');
        return false;
    }
    return true;
}

// Jab bhi offer badle, ye check chalao
$('#offer_select').on('change', function() {
    if($(this).val() != "") {
        validateSpecificOffer();
        calculateFinalPrice(); // Price update karne wala purana function
    }
});

// 1. Function: Passenger Fields Update Karna
    function updatePassengerFields() {
        let pax = parseInt($('input[name="pax_count"]').val()) || 1;
        let container = $('#passengerInputs');
        let currentRows = container.find('.row').length;

        if (pax > currentRows) {
            // Nayi rows add karo
            for (let i = currentRows; i < pax; i++) {
                container.append(`
                    <div class="row g-2 mb-2 p-2 border rounded bg-light align-items-center animate__animated animate__fadeIn">
                        <div class="col-md-5">
                            <input type="text" name="p_name[]" class="form-control form-control-sm" placeholder="Full Name" required>
                        </div>
                        <div class="col-md-3">
                            <input type="number" name="p_age[]" class="form-control form-control-sm" placeholder="Age" required>
                        </div>
                        <div class="col-md-4">
                            <select name="p_gender[]" class="form-select form-select-sm" required>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>
                `);
            }
        } else if (pax < currentRows) {
            // Extra rows hatao
            for (let i = currentRows; i > pax; i--) {
                container.find('.row').last().remove();
            }
        }
    }

    // 2. Function: Master Price Calculation
    function calculateFinalPrice() {
        let pax = parseInt($('input[name="pax_count"]').val()) || 1;
        let basePricePerPax = parseFloat($('#base_price_hidden').val()) || 0;
        
        // Total Base Price (Price * People)
        let totalBasePrice = basePricePerPax * pax;
        $('#display_base_price').text(totalBasePrice.toLocaleString('en-IN'));

        // Offer Logic
        let selectedOffer = $('#offer_select').find(':selected');
        let discType = selectedOffer.data('type');
        let discVal = parseFloat(selectedOffer.data('val')) || 0;
        let minAmt = parseFloat(selectedOffer.data('min')) || 0;

        let finalAmount = totalBasePrice;

        if (totalBasePrice >= minAmt) {
            if (discType === 'Percentage') {
                finalAmount = totalBasePrice - (totalBasePrice * (discVal / 100));
            } else {
                finalAmount = totalBasePrice - discVal;
            }
        }

        $('#total_amount').val(finalAmount.toFixed(2)).trigger('input');
    }

    // --- Triggers ---

    // PAX change hone par
    $('input[name="pax_count"]').on('input change', function() {
        updatePassengerFields();
        calculateFinalPrice();
    });

    // Vehicle select hone par
    $('#vehicle_id').on('change', function() {
        let fare = $(this).find(':selected').data('fare') || 0;
        $('#base_price_hidden').val(fare);
        calculateFinalPrice();
    });

    // Package select hone par
    $('select[name="package_id"]').on('change', function() {
        let price = $(this).find(':selected').data('price') || 0;
        $('#base_price_hidden').val(price);
        calculateFinalPrice();
    });

    // Offer select hone par
    $('#offer_select').on('change', function() {
        calculateFinalPrice();
    });

    // Due calculation
    $('#total_amount, #paid_amount').on('input', function() {
        let total = parseFloat($('#total_amount').val()) || 0;
        let paid = parseFloat($('#paid_amount').val()) || 0;
        $('#due_amount').val((total - paid).toFixed(2));
    });
});
</script>
@endpush