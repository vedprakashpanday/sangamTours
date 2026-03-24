<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sangam Tours | Explore the World</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&family=Poppins:wght@400;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        :root {
            --primary: #4e73df;
            --secondary: #1cc88a;
            --dark-glass: rgba(255, 255, 255, 0.1);
            --text-main: #2d3436;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text-main);
            overflow-x: hidden;
        }

        h1,
        h2,
        h3,
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
        }

        /* Transparent Navbar */
        .navbar {
            transition: all 0.4s;
            padding: 15px 0;
        }

        .navbar.scrolled {
            background: #fff;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
            padding: 10px 0;
        }

        .navbar-brand {
            color: var(--primary) !important;
            font-size: 1.5rem;
        }

        .nav-link {
            font-weight: 600;
            color: #fff;
            margin: 0 15px;
            transition: 0.3s;
        }

        .scrolled .nav-link {
            color: #555;
        }

        .nav-link:hover {
            color: var(--primary) !important;
        }

        /* Floating Search Box */
        .search-container {
            background: #fff;
            padding: 25px;
            border-radius: 20px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            margin-top: -60px;
            position: relative;
            z-index: 10;
        }

        /* Footer Style */
        .main-footer {
            background: #1a1a1a;
            color: #fff;
            padding: 60px 0 20px;
        }

        .social-icons a {
            font-size: 24px;
            color: #fff;
            margin-right: 15px;
            opacity: 0.7;
            transition: 0.3s;
        }

        .social-icons a:hover {
            opacity: 1;
            color: var(--primary);
        }
    </style>
    @stack('styles')
</head>

<body>

    <nav class="navbar navbar-expand-lg fixed-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand" href="#"><i class='bx bxs-plane-take-off'></i> SANGAM TOURS</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Tours</a></li>
                    <li class="nav-item"><a class="nav-link" href="#">Bus Service</a></li>
                    <li class="nav-item"><a class="nav-link btn btn-primary px-4 py-2 text-white" href="{{ url('login') }}">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    @yield('user_content')

    <footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h4 class="mb-4 text-white">SANGAM TOURS</h4>
                <p class="text-white-50">Travel is the only thing you buy that makes you richer. Explore Bihar and beyond with us.</p>
                <div class="social-icons mt-4">
                    <a href="#"><i class='bx bxl-facebook-circle'></i></a>
                    <a href="#"><i class='bx bxl-instagram'></i></a>
                    <a href="#"><i class='bx bxl-twitter'></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-4">
                <h6 class="fw-bold text-white">Quick Links</h6>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><a href="#" class="text-white-50">About Us</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50">Contact Us</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50">Terms & Conditions</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4">
                <h6 class="fw-bold text-white">Services</h6>
                <ul class="list-unstyled mt-3">
                    <li class="mb-2"><a href="#" class="text-white-50">Bus Booking</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50">Tour Packages</a></li>
                    <li class="mb-2"><a href="#" class="text-white-50">Flight Tickets</a></li>
                </ul>
            </div>
            <div class="col-md-3 mb-4 text-end">
                <h6 class="fw-bold text-white">Support</h6>
                <p class="text-white-50 small mt-3">
                    <i class='bx bx-envelope'></i> support@sangamtours.com<br>
                    <i class='bx bx-phone'></i> +91 98765 43210
                </p>
            </div>
        </div>
        <hr style="background-color: rgba(255,255,255,0.1)">
        <p class="text-center small text-white-50 mb-0">© 2026 Sangam Tours. Made with <i class='bx bxs-heart text-danger'></i> in Bihar.</p>
    </div>
</footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(window).scroll(function() {
            if ($(this).scrollTop() > 50) {
                $('#mainNav').addClass('scrolled');
            } else {
                $('#mainNav').removeClass('scrolled');
            }
        });
    </script>
    @stack('scripts')
</body>

</html>