@extends('admin.common_layout')

@section('admin_content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="m-0 fw-bold">Manage Locations</h5>
        <button class="btn btn-primary btn-sm" id="addLocationBtn">
            <i class='bx bx-plus'></i> Add New Location
        </button>
    </div>
    <div class="card-body">
        <table id="locationsTable" class="table table-hover w-100">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Country</th>
                    <th>State</th>
                    <th>Location (City)</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
        @foreach($locations as $loc)
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ $loc->country_name }}</td>
            <td>{{ $loc->state_name }}</td>
            <td>{{ $loc->city_location }}</td>
            <td>
                <div class="btn-group">
                    <button class="btn btn-sm btn-outline-primary edit-loc" data-id="{{ $loc->id }}">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="btn btn-sm btn-outline-danger delete-loc" data-id="{{ $loc->id }}">
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

<div class="modal fade" id="locationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="modalTitle">Add Location</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="locationForm">
                @csrf
                <input type="hidden" id="location_db_id" name="id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Country</label>
                        <select class="form-select" name="country_name" id="country_api" required>
                            <option value="">Select Country</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">State</label>
                        <select class="form-select" name="state_name" id="state_api" required disabled>
                            <option value="">Select State</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">City/Location Name</label>
                        <input type="text" class="form-control" name="city_location" id="city_location" placeholder="e.g. Manali, Dubai Marina" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Save Location</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
$(document).ready(function() {
    // 1. Fetch Countries on Load
    fetchCountries();

    function fetchCountries() {
        $.get('https://countriesnow.space/api/v0.1/countries/iso', function(res) {
            let options = '<option value="">Select Country</option>';
            res.data.forEach(c => {
                // India ko default select karne ke liye condition
                let selected = (c.name === 'India') ? 'selected' : '';
                options += `<option value="${c.name}" ${selected}>${c.name}</option>`;
            });
            $('#country_api').html(options);

            // Agar hum naya record add kar rahe hain (Edit mode nahi hai), 
            // toh India ke states load karne ke liye change trigger karein
            if($('#location_db_id').val() === '') {
                $('#country_api').trigger('change');
            }
        });
    }

    // 2. Fetch States when Country changes
    $('#country_api').on('change', function() {
        let country = $(this).val();
        let stateSelect = $('#state_api');
        
        if(!country) {
            stateSelect.html('<option value="">Select State</option>').prop('disabled', true);
            return;
        }

        stateSelect.html('<option value="">Loading...</option>').prop('disabled', false);

        $.ajax({
            url: 'https://countriesnow.space/api/v0.1/countries/states',
            method: 'POST',
            data: JSON.stringify({ country: country }),
            contentType: 'application/json',
            success: function(res) {
                let options = '<option value="">Select State</option>';
                res.data.states.forEach(s => {
                    options += `<option value="${s.name}">${s.name}</option>`;
                });
                stateSelect.html(options);

                // Edit mode support
                let pendingState = stateSelect.attr('data-pending');
                if(pendingState) {
                    stateSelect.val(pendingState);
                    stateSelect.removeAttr('data-pending');
                }
            }
        });
    });

    let table = $('#locationsTable').DataTable({
        "pageLength": 10,
        "dom": '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between align-items-center"ip>',
        "buttons": [
            {
                extend: 'excelHtml5',
                text: '<i class="bx bxs-file-export"></i> Export Excel',
                className: 'btn btn-success btn-sm border-0',
                title: 'Locations Data'
            }
        ],
        "language": {
            "search": "_INPUT_",
            "searchPlaceholder": "Search records..."
        }
    });

    $('.dataTables_filter input').addClass('form-control form-control-sm');

    // 4. Add/Edit Handlers
    $('#addLocationBtn').click(function() {
        $('#locationForm')[0].reset();
        $('#location_db_id').val('');
        $('#modalTitle').text('Add Location');
        
        // Modal reset karte waqt India select karke change trigger karein
        $('#country_api').val('India').trigger('change');
        
        $('#locationModal').modal('show');
    });

    // Edit Logic
    $(document).on('click', '.edit-loc', function() {
        let id = $(this).data('id');
        $.get(`/admin/locations/${id}/edit`, function(data) {
            $('#location_db_id').val(data.id);
            $('#modalTitle').text('Edit Location');
            $('#city_location').val(data.city_location);
            
            // Country set karke states trigger karo
            $('#country_api').val(data.country_name).trigger('change');
            // State ko attribute mein save karo taaki AJAX ke baad set ho sake
            $('#state_api').attr('data-pending', data.state_name);
            
            $('#locationModal').modal('show');
        });
    });

    // 5. Submit Form
    $('#locationForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#location_db_id').val();
        let url = id ? `/admin/locations/update/${id}` : "{{ route('admin.locations.store') }}";
        let method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(res) {
                alert(res.message);
                location.reload();
            }
        });
    });
});

// Delete Logic
$(document).on('click', '.delete-loc', function() {
    if (confirm('Are you sure you want to delete this location?')) {
        let id = $(this).data('id');
        $.ajax({
            url: `/admin/locations/delete/${id}`,
            method: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(res) {
                if(res.status === 'success') {
                    alert(res.message);
                    location.reload();
                }
            },
            error: function() {
                alert('Delete failed!');
            }
        });
    }
});
</script>
@endpush