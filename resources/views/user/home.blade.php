@extends('layouts.user_master')

@section('user_content')
<style>
    :root {
        --mmt-blue: #008cff;
        --mmt-dark: #4a4a4a;
        --mmt-light-grey: #e7e7e7;
        --mmt-bg: #f2f2f2;
        --brand-accent: #ff4e00;
    }

    body { background-color: var(--mmt-bg); font-family: 'Inter', sans-serif; }

    /* =========================================
       🔥 NAVBAR OVERRIDES (DARK/LIGHT FIXES) 🔥
       ========================================= */
    .navbar .nav-link { color: #ffffff !important; font-weight: 600; padding: 8px 18px !important; border-radius: 25px; transition: all 0.3s ease; }
    .navbar .nav-link:hover { background: #ffffff !important; color: var(--mmt-blue) !important; box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
    .navbar.scrolled .nav-link { color: var(--mmt-blue) !important; }
    .navbar.scrolled .nav-link:hover { background: var(--mmt-blue) !important; color: #ffffff !important; }
    
    .navbar-toggler { background-color: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.3); backdrop-filter: blur(5px); padding: 8px 12px; border-radius: 8px; }
    .navbar-toggler-icon { filter: brightness(0) invert(1); }
    .navbar.scrolled .navbar-toggler { border: 1px solid var(--mmt-blue); }
    .navbar.scrolled .navbar-toggler-icon { filter: brightness(0); }

    @media (max-width: 991px) {
        .navbar-collapse { background: #fff; border-radius: 16px; padding: 15px; margin-top: 15px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); position: absolute; width: 100%; left: 0; top: 100%; border: 1px solid #eee; }
        .navbar-collapse .nav-link { color: var(--mmt-blue) !important; border-radius: 12px; padding: 12px 20px !important; margin-bottom: 5px; }
        .navbar-collapse .nav-link:hover { background: rgba(0, 140, 255, 0.05) !important; color: var(--mmt-blue) !important; box-shadow: none; }
        .main-footer .text-end { text-align: left !important; margin-top: 15px;}
    }

    /* --- 1. HERO SECTION (MMT STYLE DARK BACKGROUND) --- */
    .hero-mmt {
        position: relative;
        height: 100vh;
        min-height: 700px;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        padding-top: 50px;
    }

    .hero-bg {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: url('https://loremflickr.com/1920/1080/city,night') no-repeat center center;
        background-size: cover; z-index: -2;
    }
    
    .hero-overlay {
        position: absolute; top: 0; left: 0; width: 100%; height: 100%;
        background: rgba(0,0,0,0.4); z-index: -1;
    }

    /* --- 2. EXACT MMT WIDGET CONTAINER --- */
    .mmt-widget-container {
        position: relative;
        width: 95%;
        max-width: 1200px;
        background: #ffffff;
        border-radius: 10px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        z-index: 100;
        margin-bottom: -50px; /* Overlaps below */
    }

    /* --- 3. MMT TABS (ALL 12 TABS) --- */
    .mmt-tabs-wrapper {
        background: #ffffff;
        border-radius: 10px 10px 0 0;
        padding: 5px 15px 0;
        box-shadow: 0 4px 10px rgba(0,0,0,0.03); /* Subtle separation */
        display: flex;
        overflow-x: auto;
        scrollbar-width: none;
    }
    .mmt-tabs-wrapper::-webkit-scrollbar { display: none; }

    .mmt-tab {
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        padding: 12px 18px; color: #4a4a4a; cursor: pointer; transition: 0.2s;
        border-bottom: 3px solid transparent; min-width: 85px; text-align: center; opacity: 0.8;
    }
    .mmt-tab i { font-size: 26px; color: #888; margin-bottom: 5px; transition: 0.2s; }
    .mmt-tab span { font-size: 13px; font-weight: 600; white-space: nowrap; }
    
    .mmt-tab:hover { opacity: 1; color: var(--mmt-blue); }
    .mmt-tab:hover i { color: var(--mmt-blue); }
    
    .mmt-tab.active { border-bottom-color: var(--mmt-blue); color: var(--mmt-blue); opacity: 1; }
    .mmt-tab.active i { color: var(--mmt-blue); }

    /* --- 4. FORM BODY & RADIOS --- */
    .mmt-widget-body { padding: 30px 20px 45px 20px; }

    .mmt-radios { display: flex; gap: 20px; margin-bottom: 20px; margin-left: 10px; }
    .mmt-radio-item { display: flex; align-items: center; gap: 5px; font-size: 14px; font-weight: 600; color: #4a4a4a; cursor: pointer; }
    .mmt-radio-item input[type="radio"] { accent-color: var(--mmt-blue); width: 16px; height: 16px; cursor: pointer; }

    /* --- 5. THE MAIN INPUT GRID (EXACT MMT STYLE BORDERS) --- */
    .mmt-input-grid {
        display: flex;
        border: 1px solid var(--mmt-light-grey);
        border-radius: 10px;
        position: relative;
    }

    .mmt-input-box {
        padding: 15px 20px;
        border-right: 1px solid var(--mmt-light-grey);
        cursor: pointer;
        transition: 0.2s;
    }
    .mmt-input-box:hover { background-color: #eaf5ff; }
    .mmt-input-box:last-child { border-right: none; }
    
    /* Grid Flex Sizing */
    .box-large { flex: 2; }
    .box-medium { flex: 1.5; }

    /* Input Typography */
    .mmt-label { display: block; font-size: 14px; font-weight: 600; color: #4a4a4a; margin-bottom: 5px; }
    .mmt-value-input {
        border: none; background: transparent; width: 100%; outline: none; padding: 0;
        font-size: 32px; font-weight: 900; color: #000; line-height: 1.1; margin-bottom: 5px; cursor: pointer;
    }
    .mmt-sub-label { display: block; font-size: 13px; font-weight: 500; color: #777; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

    /* The Swap Icon */
    .swap-icon {
        position: absolute;
        left: 31%; /* Adjust based on flex */
        top: 50%;
        transform: translate(-50%, -50%);
        background: #fff;
        border: 1px solid var(--mmt-light-grey);
        border-radius: 50%;
        width: 36px; height: 36px;
        display: flex; align-items: center; justify-content: center;
        color: var(--mmt-blue); font-size: 20px; font-weight: bold;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        z-index: 5; cursor: pointer;
    }

    /* --- 6. SPECIAL FARES SECTION --- */
    .special-fares { margin-top: 20px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
    .fare-title { font-size: 12px; font-weight: 800; color: #000; }
    .fare-badge { padding: 5px 10px; border: 1px solid var(--mmt-light-grey); border-radius: 5px; font-size: 11px; font-weight: 600; color: #4a4a4a; cursor: pointer; background: #f9f9f9; }
    .fare-badge.active { background: #eaf5ff; border-color: var(--mmt-blue); color: var(--mmt-blue); }

    /* --- 7. THE BIG SEARCH BUTTON --- */
    .mmt-search-btn-wrapper { position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%); width: 100%; text-align: center;}
    .mmt-search-btn {
        background: linear-gradient(90deg, #53b2fe, #065af3);
        color: white; border: none; border-radius: 50px;
        padding: 12px 65px; font-size: 24px; font-weight: 800;
        text-transform: uppercase; cursor: pointer;
        box-shadow: 0 4px 10px rgba(0, 140, 255, 0.4); transition: 0.3s;
    }
    .mmt-search-btn:hover { box-shadow: 0 6px 15px rgba(0, 140, 255, 0.6); transform: scale(1.02); }

    /* Explore More dropdown */
    .explore-more { position: absolute; bottom: -60px; left: 50%; transform: translateX(-50%); color: white; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 5px;}

    /* --- MOBILE RESPONSIVENESS --- */
    @media (max-width: 991px) {
        .mmt-input-grid { flex-direction: column; }
        .mmt-input-box { border-right: none; border-bottom: 1px solid var(--mmt-light-grey); }
        .swap-icon { left: auto; right: 20px; top: 15%; transform: translateY(-50%); }
        .mmt-radios { flex-wrap: wrap; }
        .hero-mmt { height: auto; padding: 100px 10px 100px 10px; min-height: 100vh;}
        .mmt-widget-container { margin-bottom: 0; }
        .explore-more { display: none; }
    }

    /* --- OTHER SECTIONS --- */
    .section-title { font-family: 'Poppins', sans-serif; font-weight: 900; font-size: clamp(1.8rem, 4vw, 2.2rem); color: #000; margin-bottom: 5px; }
    .dest-card-pro { height: 320px; border-radius: 12px; overflow: hidden; position: relative; box-shadow: 0 4px 15px rgba(0,0,0,0.1); cursor: pointer; }
    .dest-card-pro img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s ease; }
    .dest-card-pro:hover img { transform: scale(1.05); }
    .dest-content { position: absolute; bottom: 0; width: 100%; padding: 20px; background: linear-gradient(transparent, rgba(0,0,0,0.9)); color: white; text-align: left; }
    .dest-content h3 { font-family: 'Poppins', sans-serif; font-size: 1.4rem; margin-bottom: 2px;}
</style>

<div class="modal fade" id="welcomeModal" data-bs-backdrop="static" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
            <div class="row g-0">
                <div class="col-md-5 d-none d-md-block" style="background: url('https://loremflickr.com/500/800/vacation') center; background-size: cover;"></div>
                <div class="col-md-7 p-5 bg-white position-relative">
                    <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal"></button>
                    <h6 class="text-uppercase fw-bold mb-2 text-primary">Sangam Tours</h6>
                    <h2 class="fw-bold mb-2" style="font-size: 2.2rem; color: #000;">Get <span class="text-primary">₹500 OFF</span></h2>
                    <p class="text-muted mb-4 small">Sign in to unlock exclusive app-only deals and manage your bookings.</p>
                    <form>
                        <input type="text" class="form-control py-3 mb-3 bg-light border-0" placeholder="Full Name" required style="border-radius: 8px; font-weight: 600;">
                        <input type="number" class="form-control py-3 mb-4 bg-light border-0" placeholder="Mobile Number" required style="border-radius: 8px; font-weight: 600;">
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold text-white" style="border-radius: 8px; font-size: 1.1rem;">CLAIM REWARD</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="hero-mmt">
    <div class="hero-bg"></div>
    <div class="hero-overlay"></div>

    <div class="mmt-widget-container">
        
        <div class="mmt-tabs-wrapper" id="mmtTabs">
            <div class="mmt-tab active" data-type="flight"><i class='bx bxs-plane-alt'></i> <span>Flights</span></div>
            <div class="mmt-tab" data-type="hotel"><i class='bx bxs-building-house'></i> <span>Hotels</span></div>
            <div class="mmt-tab" data-type="homestay"><i class='bx bx-home-heart'></i> <span>Villas & Homestays</span></div>
            <div class="mmt-tab" data-type="tour"><i class='bx bxs-briefcase-alt-2'></i> <span>Holiday Packages</span></div>
            <div class="mmt-tab" data-type="train"><i class='bx bxs-train'></i> <span>Trains</span></div>
            <div class="mmt-tab" data-type="bus"><i class='bx bxs-bus'></i> <span>Buses</span></div>
            <div class="mmt-tab" data-type="car"><i class='bx bxs-car'></i> <span>Cabs</span></div>
            <div class="mmt-tab" data-type="attraction"><i class='bx bxs-map-alt'></i> <span>Tours & Attractions</span></div>
            <div class="mmt-tab" data-type="visa"><i class='bx bx-id-card'></i> <span>Visa</span></div>
            <div class="mmt-tab" data-type="cruise"><i class='bx bx-water'></i> <span>Cruise</span></div>
            <div class="mmt-tab" data-type="forex"><i class='bx bx-credit-card'></i> <span>Forex Card</span></div>
            <div class="mmt-tab" data-type="insurance"><i class='bx bx-shield-quarter'></i> <span>Travel Insurance</span></div>
        </div>

        <div class="mmt-widget-body">
            <form action="{{ route('bus.search') }}" method="GET">
                
                <div class="mmt-radios">
                    <label class="mmt-radio-item"><input type="radio" name="trip_type" checked> One Way</label>
                    <label class="mmt-radio-item"><input type="radio" name="trip_type"> Round Trip</label>
                    <label class="mmt-radio-item"><input type="radio" name="trip_type"> Multi City</label>
                </div>

                <div id="dynamicMmtInputs">
                    <div class="mmt-input-grid">
                        
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">From</span>
                            <input type="text" class="mmt-value-input" value="Delhi" name="from">
                            <span class="mmt-sub-label">DEL, Delhi Airport India</span>
                        </div>

                        <div class="swap-icon"><i class='bx bx-transfer-alt'></i></div>

                        <div class="mmt-input-box box-large" style="padding-left: 40px;">
                            <span class="mmt-label">To</span>
                            <input type="text" class="mmt-value-input" value="Bengaluru" name="to">
                            <span class="mmt-sub-label">BLR, Bengaluru International Airport...</span>
                        </div>

                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Departure <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="25 Mar'26" readonly>
                            <span class="mmt-sub-label">Wednesday</span>
                        </div>

                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Return <i class='bx bx-chevron-down text-primary'></i></span>
                            <span class="mmt-sub-label" style="margin-top: 10px;">Tap to add a return date for bigger discounts</span>
                        </div>

                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Travellers & Class <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="1 Traveller" readonly>
                            <span class="mmt-sub-label">Economy/Premium Economy</span>
                        </div>
                    </div>
                </div>

                <div class="special-fares d-none d-md-flex">
                    <span class="fare-title">SPECIAL FARES</span>
                    <span class="fare-badge active">Regular<br><small class="fw-normal">Regular fares</small></span>
                    <span class="fare-badge">Student<br><small class="fw-normal">Extra discounts</small></span>
                    <span class="fare-badge">Senior Citizen<br><small class="fw-normal">Up to ₹600 off</small></span>
                    <span class="fare-badge">Armed Forces<br><small class="fw-normal">Up to ₹600 off</small></span>
                </div>

                <div class="mmt-search-btn-wrapper">
                    <button type="submit" class="mmt-search-btn">SEARCH</button>
                </div>
            </form>
        </div>

        <div class="explore-more">
            <i class='bx bx-chevron-down fs-4'></i> Explore More <i class='bx bx-chevron-down fs-4'></i>
        </div>
    </div>
</section>

<section class="container" style="padding-top: 60px; padding-bottom: 60px;">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="section-title">Offers</h2>
            <div class="d-flex gap-4 mt-2" style="font-size: 14px; font-weight: 600; color: #4a4a4a;">
                <span class="text-primary border-bottom border-primary border-2 pb-1">All Offers</span>
                <span>Bank Offers</span>
                <span>Flights</span>
                <span>Hotels</span>
                <span>Holidays</span>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card border border-light shadow-sm rounded-3">
                <div class="row g-0">
                    <div class="col-4">
                        <img src="https://loremflickr.com/400/400/beach,vacation" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                    </div>
                    <div class="col-8 p-3">
                        <p class="small text-muted mb-1 text-end">T&C's APPLY</p>
                        <h6 class="fw-bold mb-2">Up to 40% OFF* on Flights, Stays & More.</h6>
                        <p class="small text-muted border-bottom pb-2">Pause your routine & get travel-ready.</p>
                        <div class="text-end"><a href="#" class="fw-bold text-primary text-decoration-none small">BOOK NOW</a></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border border-light shadow-sm rounded-3">
                <div class="row g-0">
                    <div class="col-4">
                        <img src="https://loremflickr.com/400/400/card,payment" class="img-fluid rounded-start h-100" style="object-fit: cover;">
                    </div>
                    <div class="col-8 p-3">
                        <p class="small text-muted mb-1 text-end">T&C's APPLY</p>
                        <h6 class="fw-bold mb-2">NEW: Zero Markup Currency Forex Card</h6>
                        <p class="small text-muted border-bottom pb-2">Zero cross-currency fees | FREE ATM withdrawals</p>
                        <div class="text-end"><a href="#" class="fw-bold text-primary text-decoration-none small">EXPLORE NOW</a></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    $(document).ready(function() {
        setTimeout(function() { $('#welcomeModal').modal('show'); }, 600);

        // MMT Tab Switching
        $('.mmt-tab').on('click', function() {
            $('.mmt-tab').removeClass('active');
            $(this).addClass('active');
            let type = $(this).data('type');
            
            let html = '';
            
            if(type === 'flight' || type === 'bus' || type === 'train' || type === 'car') {
                html = `
                    <div class="mmt-input-grid">
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">From</span>
                            <input type="text" class="mmt-value-input" value="Patna">
                            <span class="mmt-sub-label">Bihar, India</span>
                        </div>
                        <div class="swap-icon"><i class='bx bx-transfer-alt'></i></div>
                        <div class="mmt-input-box box-large" style="padding-left: 40px;">
                            <span class="mmt-label">To</span>
                            <input type="text" class="mmt-value-input" placeholder="Destination">
                            <span class="mmt-sub-label">Enter arrival city</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Departure <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="{{ date('d M Y') }}" readonly>
                            <span class="mmt-sub-label">{{ date('l') }}</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Travellers <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="1 Adult" readonly>
                        </div>
                    </div>`;
            } else if(type === 'hotel' || type === 'homestay') {
                html = `
                    <div class="mmt-input-grid">
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">City, Property Name Or Location</span>
                            <input type="text" class="mmt-value-input" placeholder="Where to?">
                            <span class="mmt-sub-label">India or International</span>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Check-In <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="{{ date('d M Y') }}" readonly>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Check-Out <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="{{ date('d M Y', strtotime('+1 day')) }}" readonly>
                        </div>
                        <div class="mmt-input-box box-medium">
                            <span class="mmt-label">Rooms & Guests <i class='bx bx-chevron-down text-primary'></i></span>
                            <input type="text" class="mmt-value-input" value="1 Room, 2 Adults" readonly>
                        </div>
                    </div>`;
            } else {
                 html = `
                    <div class="mmt-input-grid">
                        <div class="mmt-input-box box-large">
                            <span class="mmt-label">Search</span>
                            <input type="text" class="mmt-value-input" placeholder="Search Services...">
                        </div>
                    </div>`;
            }
           

            
            $('#dynamicMmtInputs').fadeOut(100, function() {
                $(this).html(html).fadeIn(200);
            });
        });
    });
</script>
@endpush