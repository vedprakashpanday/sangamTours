{{-- resources/views/partials/ride_cards.blade.php --}}

@forelse($rides as $ride)
    @php
        $mainImg = $ride->images->where('image_type', 'main')->first();
        $imagePath = ($mainImg && file_exists(public_path('uploads/vehicles/'.$mainImg->filename))) 
                     ? asset('uploads/vehicles/'.$mainImg->filename) 
                     : asset('no-image.png');

        $hasAc = $ride->amenities->contains(function($val) {
            return str_contains(strtolower($val->name), 'ac') || str_contains(strtolower($val->name), 'air condition');
        });
    @endphp

    <div class="col-lg-3 col-md-6">
        <div class="car-card shadow-sm h-100 d-flex flex-column">
            
            <div class="car-img-wrapper" style="height: 180px;">
                <img src="{{ $imagePath }}" alt="{{ $ride->model_name ?? 'Vehicle' }}" style="width: 100%; height: 100%; object-fit: cover;">
            </div>
            
            <div class="card-body p-4 d-flex flex-column flex-grow-1">
                
                <h5 class="fw-bold mb-1" style="color: #0A2239;">{{ $ride->model_name ?? 'Standard Model' }}</h5>
                
                <p class="text-muted small mb-3">
                    <span class="fw-semibold text-dark">{{ $ride->seat_type ?? $ride->type }}</span> • {{ $ride->vendor->name ?? 'Sangam Travels' }}
                </p>
                
                <div class="car-features mb-4 d-flex gap-3 text-muted small fw-medium">
                    <span><i class='bx bx-user fs-5 align-middle'></i> {{ $ride->total_seats }} Seats</span>
                    @if($ride->luggage_allowed)
                        <span><i class='bx bx-briefcase fs-5 align-middle'></i> {{ $ride->luggage_allowed }}</span> 
                    @endif
                    @if($hasAc)
                        <span><i class='bx bx-wind fs-5 align-middle'></i> A/C</span>
                    @endif
                </div>
                
                <div class="d-flex justify-content-between align-items-center mt-auto border-top pt-3">
                    <div>
                        <span class="d-block small text-muted" style="line-height: 1;">Starting at</span>
                        <span class="fw-bold fs-5" style="color: #0A2239;">₹{{ number_format($ride->charges_per_km) }}<span class="small text-muted fs-6 fw-normal">/km</span></span>
                    </div>
                    <a href="#" class="btn px-4 py-2 fw-bold" style="background: #FF4E00; color: #fff; border-radius: 8px;">Book</a>
                </div>
                
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <i class='bx bx-info-circle text-muted mb-3' style="font-size: 50px;"></i>
        <h5 class="text-muted">{{ $emptyMsg ?? 'No vehicles available.' }}</h5>
        <p class="text-muted small">Please check back later as we update our fleet.</p>
    </div>
@endforelse