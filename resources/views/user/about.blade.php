@extends('layouts.user_master')

@section('user_content')
<style>
    /* =========================================
       🔥 ABOUT US PAGE CUSTOM STYLES 🔥
       ========================================= */
    :root {
        --brand-primary: #0A2239; /* Deep Navy Blue */
        --brand-accent: #FF4E00;  /* Vibrant Orange */
        --brand-light: #f8fafc;   /* Soft Background */
    }

    body {
        background-color: var(--brand-light);
        font-family: 'Inter', sans-serif;
    }

    /* Hero Section */
    .about-hero {
        position: relative;
        height: 60vh;
        min-height: 450px;
        background: url('https://loremflickr.com/1920/1080/travel,landscape?random=1') center center/cover no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        margin-top: -80px; /* Adjust according to your navbar height */
        padding-top: 80px;
    }

    .about-hero::before {
        content: '';
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        background: linear-gradient(to bottom, rgba(10, 34, 57, 0.8), rgba(0, 0, 0, 0.4));
    }

    .about-hero-content {
        position: relative;
        z-index: 2;
        max-width: 800px;
        padding: 0 20px;
    }

    .hero-subtitle {
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        color: var(--brand-accent);
        margin-bottom: 15px;
        display: block;
    }

    .hero-title {
        font-size: 3.5rem;
        font-weight: 900;
        margin-bottom: 20px;
        font-family: 'Poppins', sans-serif;
    }

    /* Intro Section */
    .intro-section {
        padding: 80px 0;
        background: #fff;
    }

    .intro-heading {
        color: var(--brand-primary);
        font-weight: 800;
        font-size: 2.5rem;
        line-height: 1.2;
        margin-bottom: 20px;
    }

    .intro-text {
        font-size: 16px;
        line-height: 1.8;
        color: #4a5568;
        margin-bottom: 20px;
    }

    .ceo-box {
        background: var(--brand-light);
        padding: 20px;
        border-left: 4px solid var(--brand-accent);
        border-radius: 0 8px 8px 0;
        margin-top: 30px;
    }

    /* Stats Section */
    .stats-container {
        background: var(--brand-primary);
        color: white;
        padding: 60px 0;
        margin-top: -40px;
        border-radius: 16px;
        position: relative;
        z-index: 10;
        box-shadow: 0 20px 40px rgba(10, 34, 57, 0.15);
    }

    .stat-box {
        text-align: center;
        padding: 20px;
    }

    .stat-number {
        font-size: 3.5rem;
        font-weight: 900;
        color: var(--brand-accent);
        line-height: 1;
        margin-bottom: 10px;
        font-family: 'Poppins', sans-serif;
    }

    .stat-label {
        font-size: 14px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        color: #cbd5e1;
    }

    /* Why Choose Us */
    .features-section {
        padding: 100px 0;
        background: var(--brand-light);
    }

    .feature-card {
        background: #fff;
        padding: 40px 30px;
        border-radius: 16px;
        text-align: center;
        height: 100%;
        transition: all 0.3s ease;
        border: 1px solid #e2e8f0;
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.05);
        border-color: transparent;
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        background: rgba(255, 78, 0, 0.1);
        color: var(--brand-accent);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        margin: 0 auto 25px;
        transition: 0.3s;
    }

    .feature-card:hover .feature-icon {
        background: var(--brand-accent);
        color: #fff;
    }

    /* Popular Destinations */
    .destinations-section {
        padding: 80px 0;
        background: #fff;
    }

    .dest-card {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        height: 300px;
        cursor: pointer;
    }

    .dest-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .dest-card:hover img {
        transform: scale(1.1);
    }

    .dest-overlay {
        position: absolute;
        bottom: 0; left: 0; width: 100%;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        padding: 30px 20px 20px;
        color: white;
    }

    .dest-title {
        font-weight: 800;
        font-size: 1.2rem;
        margin-bottom: 5px;
    }

    .tag-cloud {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        justify-content: center;
        margin-top: 30px;
    }

    .tag-cloud span {
        background: #f1f5f9;
        color: var(--brand-primary);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid #e2e8f0;
        transition: 0.3s;
    }

    .tag-cloud span:hover {
        background: var(--brand-primary);
        color: white;
    }

    /* Contact Banner */
    .contact-banner {
        background: linear-gradient(135deg, var(--brand-primary), #051424);
        padding: 60px 0;
        text-align: center;
        color: white;
    }

    .contact-phone {
        font-size: 2.5rem;
        font-weight: 900;
        color: var(--brand-accent);
        margin: 20px 0;
    }
</style>

<section class="about-hero">
    <div class="about-hero-content">
        <span class="hero-subtitle">World's Leading Travel Agency</span>
        <h1 class="hero-title">Experience the World with Sangam Tours</h1>
        <p class="fs-5 fw-medium text-light">Over 30,000 packages worldwide. Book travel packages and enjoy your holidays with a distinctive experience.</p>
    </div>
</section>

<div class="container relative">
    <div class="stats-container">
        <div class="row g-4">
            <div class="col-md-3 col-6">
                <div class="stat-box">
                    <div class="stat-number">240+</div>
                    <div class="stat-label">Tour Packages</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box border-start border-secondary">
                    <div class="stat-number">960+</div>
                    <div class="stat-label">Amazing Places</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box border-start border-secondary">
                    <div class="stat-number">400+</div>
                    <div class="stat-label">Special Events</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-box border-start border-secondary">
                    <div class="stat-number">120+</div>
                    <div class="stat-label">Luxury Hotels</div>
                </div>
            </div>
        </div>
    </div>
</div>

<section class="intro-section">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-6">
                <h6 class="text-uppercase fw-bold mb-2" style="color: var(--brand-accent); letter-spacing: 1px;">Hi! Welcome to</h6>
                <h2 class="intro-heading">Sangam Tour & Travels</h2>
                
                <p class="intro-text fw-medium text-dark">
                    Sangam Tour & Travels specializes in corporate travel management. We build the foundation of the company by offering professional and efficient service to our clients.
                </p>
                <p class="intro-text">
                    Travel has always been an exciting undertaking for people all over the world. Be it for the purpose of work or for leisure, traveling has its own fun. The pleasures one can derive from traveling around the world and discovering new places cannot be compared to any other thing. 
                </p>
                <p class="intro-text">
                    We, as a reliable travel agency, offer comprehensive travel solutions to clients in and around Bihar and the rest of India. We do not aim at earning an unimaginable amount of profit. What we aim for is to emerge as a leading agency in the tourism sector by providing effective and transparent tour deals to clients.
                </p>

                <div class="ceo-box d-flex align-items-center gap-3">
                    <img src="https://loremflickr.com/100/100/face,man?random=1" alt="CEO" class="rounded-circle border border-2 border-white shadow-sm" style="width: 60px; height: 60px;">
                    <div>
                        <h6 class="fw-bold mb-1 text-dark">Mr. Manoj Bhaskar</h6>
                        <span class="small text-muted fw-semibold">CEO & Founder (Est. 2010, Darbhanga)</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="row g-3">
                    <div class="col-6">
                        <img src="https://loremflickr.com/400/500/travel,india?random=2" class="img-fluid rounded-4 shadow-lg w-100" style="height: 350px; object-fit: cover; margin-top: 50px;">
                    </div>
                    <div class="col-6">
                        <img src="https://loremflickr.com/400/500/vacation,beach?random=3" class="img-fluid rounded-4 shadow-lg w-100 mb-3" style="height: 250px; object-fit: cover;">
                        <img src="https://loremflickr.com/400/300/hotel,luxury?random=4" class="img-fluid rounded-4 shadow-lg w-100" style="height: 180px; object-fit: cover;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="features-section">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="text-uppercase fw-bold mb-2" style="color: var(--brand-accent); letter-spacing: 1px;">Our Excellence</h6>
            <h2 class="intro-heading">Why Choose Sangam Tours</h2>
        </div>

        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class='bx bx-support'></i></div>
                    <h4 class="fw-bold mb-3" style="color: var(--brand-primary);">24 X 7 Services</h4>
                    <p class="text-muted">The leading traveling company in India offering great offers, lowest airfares, busfares, trainfares, exclusive discounts, and a seamless online booking experience.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class='bx bx-map-alt'></i></div>
                    <h4 class="fw-bold mb-3" style="color: var(--brand-primary);">Customized Packages</h4>
                    <p class="text-muted">One of the fastest-growing tour operators in India. We constantly add value to our products and continue to offer the best, tailor-made experiences to our customers.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon"><i class='bx bx-wallet'></i></div>
                    <h4 class="fw-bold mb-3" style="color: var(--brand-primary);">Best Deals Guaranteed</h4>
                    <p class="text-muted">We fully understand the desires of the traveler. Enjoy features like Instant Discounts, Fare Calendar, MyRewards Program, and MyWallet.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="destinations-section">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="intro-heading">Top Packages & Destinations</h2>
            <p class="text-muted fs-5">Explore the most sought-after locations curated just for you.</p>
        </div>

        <div class="row g-4">
            <div class="col-md-6">
                <div class="dest-card">
                    <img src="https://loremflickr.com/800/600/paris,eiffel?random=5" alt="Paris">
                    <div class="dest-overlay d-flex justify-content-between align-items-end">
                        <div>
                            <h4 class="dest-title">Eiffel Tower, Paris</h4>
                            <span class="small fw-semibold"><i class='bx bxs-star text-warning'></i> 4.9 Superb</span>
                        </div>
                        <button class="btn btn-sm btn-light fw-bold rounded-pill px-3">Book Now</button>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="dest-card">
                    <img src="https://loremflickr.com/800/600/dubai,burj?random=6" alt="Dubai">
                    <div class="dest-overlay d-flex justify-content-between align-items-end">
                        <div>
                            <h4 class="dest-title">Burj Al Arab, Dubai</h4>
                            <span class="small fw-semibold"><i class='bx bxs-star text-warning'></i> 4.8 Excellent</span>
                        </div>
                        <button class="btn btn-sm btn-light fw-bold rounded-pill px-3">Book Now</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="tag-cloud">
            <span>Dubai</span>
            <span>Buckingham Palace</span>
            <span>Bangkok</span>
            <span>Thailand</span>
            <span>Statue of Liberty</span>
            <span>Notre Dame de Paris</span>
            <span>Taj Mahal</span>
            <span>The Louvre</span>
            <span>Gothic Quarter</span>
            <span>Great Wall of China</span>
            <span>Yellowstone</span>
        </div>
    </div>
</section>

<section class="contact-banner">
    <div class="container">
        <h3 class="fw-bold mb-3">Ready to Start Your Journey?</h3>
        <p class="fs-5 text-light opacity-75">For all your travel undertakings, count on us and gather unbelievable travel experiences!</p>
        
        <div class="contact-phone">
            <i class='bx bxs-phone-call align-middle me-2'></i> 
            +91-9835040123 <span class="text-white fw-light fs-3 mx-2">|</span> +91-7282-985-888
        </div>
        
        <div class="mt-4 text-light opacity-75 fw-medium">
            <i class='bx bxs-map me-1'></i> Abhinandan Market, Leheriasarai, Darbhanga-846001 (Bihar) <br>
            <i class='bx bxs-envelope me-1 mt-2'></i> sangamtourtravels@gmail.com
        </div>
    </div>
</section>

@endsection