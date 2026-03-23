@extends('admin.common_layout')

@section('admin_content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold">Vehicle Scheduling (Time-Table)</h4>
        <button class="btn btn-primary" id="addScheduleBtn"><i class='bx bx-calendar-plus'></i> Add New Schedule</button>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <table id="scheduleTable" class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Vehicle / Type</th>
                        <th>Route Summary</th>
                        <th>Departure</th>
                        <th>Arrival</th>
                        <th>Availability</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $s)
                    <tr>
                        <td>
                            <span class="fw-bold text-dark">{{ $s->vehicle->vehicle_number }}</span><br>
                            <small class="badge bg-info text-dark">{{ $s->vehicle->type }}</small>
                        </td>
                        <td>
                            @if($s->stoppages->count() > 0)
                                <span class="text-success fw-bold">{{ $s->stoppages->first()->location->city_location }}</span>
                                <i class='bx bx-right-arrow-alt mx-1'></i>
                                <span class="text-danger fw-bold">{{ $s->stoppages->last()->location->city_location }}</span>
                                <br>
                                <small class="text-muted">({{ $s->stoppages->count() - 2 }} intermediate stops)</small>
                            @else
                                <span class="text-muted italic">Path not set</span>
                            @endif
                        </td>
                        <td class="fw-bold text-success">
                            {{ $s->stoppages->first() ? date('h:i A', strtotime($s->stoppages->first()->departure_time)) : 'N/A' }}
                        </td>
                        <td class="fw-bold text-danger">
                            {{ $s->stoppages->last() ? date('h:i A', strtotime($s->stoppages->last()->arrival_time)) : 'N/A' }}
                        </td>
                        <td>
                            @if($s->specific_date)
                                <span class="badge bg-warning text-dark">On {{ date('d M, Y', strtotime($s->specific_date)) }}</span>
                            @else
                                <span class="badge bg-secondary">{{ $s->days_of_week }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="btn-group shadow-sm">
                                <button class="btn btn-sm btn-light view-path text-primary" data-id="{{ $s->id }}" title="View Path"><i class='bx bx-map-alt'></i></button>
                                <button class="btn btn-sm btn-light edit-schedule text-warning" data-id="{{ $s->id }}" title="Edit"><i class='bx bx-edit-alt'></i></button>
                                <button class="btn btn-sm btn-light delete-schedule text-danger" data-id="{{ $s->id }}" title="Delete"><i class='bx bx-trash'></i></button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="scheduleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class='bx bx-calendar-plus'></i> Detailed Journey Scheduler</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="scheduleForm">
                @csrf
                <input type="hidden" name="id" id="schedule_id">
                <div class="modal-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">SERVICE TYPE</label>
                            <select id="filter_vendor_type" class="form-select border-primary fw-bold">
                                <option value="">-- Select Type --</option>
                                <option value="Flight">Flight</option>
                                <option value="Bus">Bus</option>
                                <option value="Train">Train</option>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <label class="form-label fw-bold small text-muted">SELECT VEHICLE</label>
                            <select name="vehicle_id" id="vehicle_select" class="form-select select2-schedule" required>
                                <option value="">-- Choose Vehicle --</option>
                                @foreach($vehicles as $v)
                                    <option value="{{ $v->id }}" data-type="{{ $v->type }}">
                                        {{ $v->vendor->name }} - {{ $v->vehicle_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="p-4 bg-light rounded-3 border">
                        <h6 class="fw-bold text-dark mb-4 border-bottom pb-2"><i class='bx bxs-map-pin text-primary'></i> JOURNEY PATH SETUP</h6>
                        
                        <div class="row g-2 mb-3 align-items-end" id="startPointRow">
                            <div class="col-md-7">
                                <label class="small fw-bold text-success text-uppercase">Starting Point (Origin)</label>
                                <select name="stop_location[]" class="form-select select2-schedule" required>
                                    <option value="">Select Origin City</option>
                                    @foreach($locations as $loc) <option value="{{ $loc->id }}">{{ $loc->city_location }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-5">
                                <label class="small fw-bold">Departure Time</label>
                                <input type="time" name="stop_departure[]" class="form-control border-success" required>
                                <input type="hidden" name="stop_arrival[]" value="00:00">
                            </div>
                        </div>

                        <div id="stoppageContainer"></div>

                        <div class="row g-2 mt-2 align-items-end border-top pt-4" id="destinationPointRow">
                            <div class="col-md-5">
                                <label class="small fw-bold text-danger text-uppercase">Final Destination</label>
                                <select name="stop_location[]" class="form-select select2-schedule" required>
                                    <option value="">Select Destination City</option>
                                    @foreach($locations as $loc) <option value="{{ $loc->id }}">{{ $loc->city_location }}</option> @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="small fw-bold">Final Arrival</label>
                                <input type="time" name="stop_arrival[]" class="form-control border-danger" required>
                                <input type="hidden" name="stop_departure[]" value="00:00">
                            </div>
                            <div class="col-md-4 text-start">
                                <button type="button" class="btn btn-dark w-100 shadow-sm fw-bold" id="addStoppageBtn" style="height: 38px;">
                                    <i class='bx bx-plus-circle'></i> Add Stoppage
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="row g-3 mt-4">
                        <div class="col-md-12">
                            <label class="form-label fw-bold small text-muted">RECURRENCE</label>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach(['Mon','Tue','Wed','Thu','Fri','Sat','Sun'] as $day)
                                    <div class="form-check border p-2 rounded bg-white">
                                        <input class="form-check-input ms-1" type="checkbox" name="days[]" value="{{ $day }}" id="day{{ $day }}">
                                        <label class="form-check-label ms-1 small" for="day{{ $day }}">{{ $day }}</label>
                                    </div>
                                @endforeach
                                <button type="button" class="btn btn-sm btn-outline-primary" id="setDaily">Select All (Daily)</button>
                            </div>
                        </div>
                        <div class="col-md-12 mt-3">
                            <label class="small fw-bold text-muted">OR SPECIFIC DATE</label>
                            <input type="date" name="specific_date" id="specific_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="submit" class="btn btn-primary px-5 py-2 fw-bold shadow" id="saveBtn">SAVE SCHEDULE</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="viewPathModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">Full Journey Timeline</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4" id="pathTimelineBody">
                </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // 1. DataTables with Excel Buttons
    var table = $('#scheduleTable').DataTable({ 
        "dom": '<"d-flex justify-content-between mb-3"Bf>rtip', 
        "buttons": [
            { extend: 'excel', className: 'btn btn-sm btn-success px-3', text: '<i class="bx bxs-file-export"></i> Excel' }
        ]
    });

    // 2. Re-indexing function for Stoppages
    function reindexStoppages() {
        $('#stoppageContainer .stoppage-row').each(function(index) {
            $(this).find('.stoppage-label').text('Stoppage ' + (index + 1));
        });
    }

    // 3. Add Stoppage Row
    $('#addStoppageBtn').click(function() {
        let stoppageHtml = `
            <div class="stoppage-row row g-2 mb-2 align-items-end p-3 border-start border-primary border-4 bg-white shadow-sm mt-3 animate__animated animate__fadeIn">
                <div class="col-md-5">
                    <label class="small text-primary fw-bold stoppage-label">Stoppage</label>
                    <select name="stop_location[]" class="form-select select2-stop" required>
                        <option value="">Select City</option>
                        @foreach($locations as $loc) <option value="{{ $loc->id }}">{{ $loc->city_location }}</option> @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Arrival</label>
                    <input type="time" name="stop_arrival[]" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="small fw-bold">Departure</label>
                    <input type="time" name="stop_departure[]" class="form-control" required>
                </div>
                <div class="col-md-1 text-end">
                    <button type="button" class="btn btn-sm btn-outline-danger remove-stop border-0"><i class='bx bx-trash'></i></button>
                </div>
            </div>`;
        $('#stoppageContainer').append(stoppageHtml);
        $('.select2-stop').last().select2({ dropdownParent: $('#scheduleModal'), width: '100%' });
        reindexStoppages();
    });

    // 4. Remove Stoppage
    $(document).on('click', '.remove-stop', function() {
        $(this).closest('.stoppage-row').remove();
        reindexStoppages();
    });

    // 5. Open Create Modal
    $('#addScheduleBtn').click(function() {
        $('#scheduleForm')[0].reset();
        $('#schedule_id').val('');
        $('#stoppageContainer').empty();
        $('.select2-schedule').val(null).trigger('change');
        $('#modalTitle').html('<i class="bx bx-calendar-plus"></i> Detailed Journey Scheduler');
        $('#scheduleModal').modal('show');
    });

    // 6. Select2 Modal Fix
    $('#scheduleModal').on('shown.bs.modal', function () {
        $('.select2-schedule').select2({ dropdownParent: $('#scheduleModal'), width: '100%' });
    });

    // 7. Delete Logic (Delegated)
    $(document).on('click', '.delete-schedule', function() {
        let id = $(this).data('id');
        if(confirm('Are you sure? This will delete the full route path.')) {
            $.ajax({
                url: "{{ url('admin/schedules') }}/" + id,
                method: "DELETE",
                headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                success: function(res) { location.reload(); }
            });
        }
    });

    // 8. View Full Path (Timeline Modal logic with Arrival/Departure Filter)
$(document).on('click', '.view-path', function() {
    let id = $(this).data('id');
    
    $.get("{{ url('admin/schedules') }}/" + id, function(data) {
        let body = $('#pathTimelineBody');
        body.empty();
        
        let timeline = '<div class="list-group list-group-flush">';
        
        data.stoppages.forEach(function(stop, index) {
            let color = 'text-primary';
            let icon = 'bx-radio-circle';
            let timingInfo = '';

            // --- Logic for Starting, Middle and Destination ---
            if (index === 0) {
                // Pehla Stop (Starting Point)
                color = 'text-success';
                icon = 'bx-play-circle';
                timingInfo = `<span class="badge bg-success-light text-success">Departure: ${stop.departure_time}</span>`;
            } 
            else if (index === data.stoppages.length - 1) {
                // Aakhri Stop (Destination)
                color = 'text-danger';
                icon = 'bx-stop-circle';
                timingInfo = `<span class="badge bg-danger-light text-danger">Arrival: ${stop.arrival_time}</span>`;
            } 
            else {
                // Beech ke Stoppages
                color = 'text-primary';
                icon = 'bx-right-arrow-circle';
                timingInfo = `<small class="text-muted">Arr: ${stop.arrival_time} | Dept: ${stop.departure_time}</small>`;
            }

            timeline += `
                <div class="list-group-item d-flex border-0 mb-3 px-0 animate__animated animate__fadeIn">
                    <div class="me-3 fs-3 ${color}"><i class='bx ${icon}'></i></div>
                    <div class="flex-grow-1 border-bottom pb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="fw-bold mb-0 text-dark">${stop.location.city_location}</h6>
                            ${index === 0 ? '<span class="badge bg-soft-success text-success small">START</span>' : (index === data.stoppages.length - 1 ? '<span class="badge bg-soft-danger text-danger small">END</span>' : '')}
                        </div>
                        <div class="mt-1">${timingInfo}</div>
                    </div>
                </div>`;
        });

        timeline += '</div>';
        body.append(timeline);
        $('#viewPathModal').modal('show');
    });
});

    // 9. Edit Schedule Logic (Delegated)
    $(document).on('click', '.edit-schedule', function() {
        let id = $(this).data('id');
        $('#stoppageContainer').empty();
        
        $.get("{{ url('admin/schedules') }}/" + id + "/edit", function(data) {
            $('#schedule_id').val(data.schedule.id);
            $('#vehicle_select').val(data.schedule.vehicle_id).trigger('change');
            $('#specific_date').val(data.schedule.specific_date);
            
            // Days check
            if(data.schedule.days_of_week) {
                let daysArr = data.schedule.days_of_week.split(',');
                $('input[name="days[]"]').prop('checked', false);
                daysArr.forEach(d => $(`input[value="${d}"]`).prop('checked', true));
            }

            // Fill Path
            data.stoppages.forEach(function(stop, index) {
                if(index === 0) { // Start
                    $('#startPointRow select').val(stop.location_id).trigger('change');
                    $('#startPointRow input[name="stop_departure[]"]').val(stop.departure_time);
                } else if(index === data.stoppages.length - 1) { // End
                    $('#destinationPointRow select').val(stop.location_id).trigger('change');
                    $('#destinationPointRow input[name="stop_arrival[]"]').val(stop.arrival_time);
                } else { // Intermediate
                    $('#addStoppageBtn').click();
                    let lastRow = $('#stoppageContainer .stoppage-row').last();
                    lastRow.find('select').val(stop.location_id).trigger('change');
                    lastRow.find('input[name="stop_arrival[]"]').val(stop.arrival_time);
                    lastRow.find('input[name="stop_departure[]"]').val(stop.departure_time);
                }
            });

            $('#modalTitle').text('Edit Route Schedule');
            $('#scheduleModal').modal('show');
        });
    });

    // 10. Ajax Save/Update
    $('#scheduleForm').on('submit', function(e) {
        e.preventDefault();
        let id = $('#schedule_id').val();
        let url = id ? "{{ url('admin/schedules') }}/" + id : "{{ route('admin.schedules.store') }}";
        let method = id ? "PUT" : "POST";

        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(res) { alert(res.message); location.reload(); },
            error: function() { alert('Error processing request.'); }
        });
    });

    $('#setDaily').click(function() { $('input[name="days[]"]').prop('checked', true); });
    $('#filter_vendor_type').on('change', function() {
        let type = $(this).val();
        $('#vehicle_select option').hide();
        $(`#vehicle_select option[data-type="${type}"]`).show();
        $('#vehicle_select').val('').trigger('change');
    });
});
</script>
@endpush