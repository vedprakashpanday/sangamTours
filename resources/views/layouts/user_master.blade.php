<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="session-id" content="{{ session()->getId() ?? '' }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Sangam Tours | Explore the World</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%234e73df%22/><text x=%2250%%22 y=%2252%%22 dominant-baseline=%22central%22 text-anchor=%22middle%22 font-family=%22Arial, sans-serif%22 font-weight=%22bold%22 font-size=%2260%22 fill=%22white%22>ST</text></svg>">

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        :root {
            --brand-blue: #0A2239;
            --brand-accent: #FF4E00;
            --mmt-blue: #008cff;
            --text-dark: #000000;
            /* Pure Black for visibility */
            --text-light: #6c757d;
        }

        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
            background-color: #f2f2f2;
            padding-top: 80px;
            /* Navbar ke liye gap */
        }

        h1,
        h2,
        h3,
        h4,
        h5,
        h6,
        .navbar-brand {
            font-family: 'Poppins', sans-serif;
        }

        /* =========================================
       🔥 NAVBAR CORE STYLES 🔥
       ========================================= */
        .navbar {
            background-color: #ffffff !important;
            box-sizing: border-box;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 12px 0;
            transition: all 0.3s ease;
            z-index: 1030 !important;
            /* Standard Bootstrap Fixed-top */
        }

        .navbar-brand img {
            width: 140px;
        }

        /* 🔥 Desktop View Fix: Force links to be visible black */
        .navbar .navbar-nav .nav-link {
            font-family: 'Inter', sans-serif;
            font-weight: 700 !important;
            color: #000000 !important;
            opacity: 1 !important;
            visibility: visible !important;
            font-size: 16px;
            margin: 0 10px;
            padding: 10px 15px !important;
            transition: all 0.3s ease;
        }

        .navbar .navbar-nav .nav-link:hover {
            color: var(--brand-accent) !important;
        }

        /* Login Button Styling */
        .btn-login-nav {
            background: linear-gradient(135deg, var(--brand-accent), #e64600) !important;
            color: #ffffff !important;
            border-radius: 50px;
            padding: 10px 25px !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(255, 78, 0, 0.3);
            margin-left: 15px;
            border: none;
        }

        .btn-login-nav:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(255, 78, 0, 0.4);
        }

        /* Dropdown visibility logic */
        .dropdown-menu {
            display: none;
            position: absolute;
            z-index: 1050 !important;
            border: none;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1) !important;
            border-radius: 12px;
            margin-top: 10px;
        }

        .dropdown-menu.show {
            display: block !important;
        }

        /* Toggler Styling */
        .navbar-toggler {
            border: 2px solid #000;
            padding: 5px 8px;
            background: #fff;
            border-radius: 8px;
        }

        /* =========================================
       🔥 MOBILE RESPONSIVE (MAX 991px) 🔥
       ========================================= */
        @media (max-width: 991px) {
            .navbar-collapse {
                position: absolute !important;
                top: 100%;
                left: 0;
                width: 100%;
                background-color: #ffffff !important;
                z-index: 10000 !important;
                padding: 20px 10px;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
                border-top: 1px solid #eee;
                border-radius: 0 0 15px 15px;
            }

            .navbar-nav .nav-link {
                border-bottom: 1px solid #f0f0f0;
                width: 100%;
                text-align: center;
                padding: 12px 0 !important;
                margin: 5px 0 !important;
            }

            .dropdown-menu {
                position: static !important;
                float: none;
                width: 100%;
                box-shadow: none !important;
                background: #f8f9fa !important;
                text-align: center;
            }

            .btn-login-nav {
                margin-top: 15px;
                width: 80%;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
        }

        /* =========================================
       🔥 FOOTER & AUTH MODAL 🔥
       ========================================= */
        .main-footer {
            background: #000000;
            color: #ffffff;
            padding: 70px 0 30px;
            margin-top: 60px;
        }

        .footer-logo {
            font-size: 24px;
            font-weight: 900;
            color: #fff;
            margin-bottom: 20px;
        }

        .footer-heading {
            font-size: 16px;
            font-weight: 700;
            text-transform: uppercase;
            color: #ffffff;
            margin-bottom: 25px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
        }

        .footer-links li {
            margin-bottom: 15px;
        }

        .footer-links a {
            color: #999999;
            text-decoration: none;
            transition: 0.3s;
        }

        .footer-links a:hover {
            color: var(--mmt-blue);
            padding-left: 5px;
        }

        .auth-modal-content {
            border-radius: 20px;
            overflow: hidden;
            border: none;
        }

        .auth-left-bg {
            background: linear-gradient(135deg, rgba(10, 34, 57, 0.9), rgba(255, 78, 0, 0.8)), url('https://loremflickr.com/600/800/travel') center/cover;
            color: white;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            text-align: center;
        }

        .auth-nav-tabs {
            border-bottom: none;
            gap: 15px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .auth-nav-tabs .nav-link {
            color: var(--text-light);
            font-weight: 700;
            border: none;
            background: transparent;
            font-size: 18px;
            position: relative;
        }

        .auth-nav-tabs .nav-link.active {
            color: var(--brand-blue);
        }

        .auth-nav-tabs .nav-link.active::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 30px;
            height: 3px;
            background: var(--brand-accent);
        }
    </style>
    @stack('styles')
</head>

<body data-has-errors="{{ $errors->any() ? 'true' : 'false' }}">

    <nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm" style="z-index: 99999 !important;">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">
                <img src="{{ asset('uploads/company/6.png') }}" alt="Sangam Tours" style="height: 45px; width: auto; max-width: 180px;">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class='bx bx-menu text-dark' style="font-size: 35px;"></i>
            </button>

            <div class="collapse navbar-collapse bg-white" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center text-center py-3 py-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3 text-dark" href="{{ route('home') }}">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3" href="#">Tours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3" href="#">Bus Service</a>
                    </li>
                    <li class="nav-item mt-3 mt-lg-0 ms-lg-3">
                        @auth
                        <div class="dropdown">
                            <button class="btn btn-login-nav dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                Hi, {{ Auth::user()->name }}
                            </button>
                            <ul class="dropdown-menu border-0 shadow">
                                <li><a class="dropdown-item" href="#">My Bookings</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">Logout</button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                        @else
                        <button class="btn btn-login-nav" data-bs-toggle="modal" data-bs-target="#authModal">Login / Signup</button>
                        @endauth
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div style="padding-top: 80px;">
        @yield('user_content')
    </div>

    <footer class="main-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="footer-logo"><img src="{{ asset('uploads/company/6.png') }}" alt="Sangam Tours" style="height: 45px; width: auto; max-width: 180px;"></div>
                    <p style="color: #999; font-size: 14px; line-height: 1.7;">Travel is the only thing you buy that makes you richer. We provide the most reliable Buses, Hotels, and Holiday Packages across Bihar and India.</p>
                    <div class="social-icons">
                        <a href="#"><i class='bx bxl-facebook'></i></a>
                        <a href="#"><i class='bx bxl-instagram'></i></a>
                        <a href="#"><i class='bx bxl-twitter'></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="footer-heading">Company</h6>
                    <ul class="footer-links">
                        {{-- Assuming you mapped 'about' route --}}
                        <li><a href="{{ route('about') ?? '#' }}">About Us</a></li>
                        <li><a href="#">Careers</a></li>
                        <li><a href="#">Terms & Conditions</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-heading">Explore Services</h6>
                    <ul class="footer-links">
                        <li><a href="#">Book Bus Tickets</a></li>
                        <li><a href="#">Holiday Packages</a></li>
                        <li><a href="#">Hotel Bookings</a></li>
                        <li><a href="#">Car Rentals</a></li>
                    </ul>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-heading">Need Help?</h6>
                    <div class="footer-contact">
                        <p class="mb-2"><i class='bx bx-envelope'></i> sangamtourtravels@gmail.com</p>
                        <p class="mb-2"><i class='bx bx-phone'></i> +91-9835040123 / +91-7282985888</p>
                        <p class="mb-0"><i class='bx bx-map'></i> Abhinandan Market, Leheriasarai, Darbhanga-846001</p>
                    </div>
                </div>
            </div>

            {{-- 🔥 DYNAMIC FOOTER FIX APPLIED HERE 🔥 --}}
            <div class="border-top py-3 mt-5 text-center">
                <p class="copyright mb-0" style="color: #777; font-size: 14px;">
                    &copy; {{ date('Y') }} <span class="fw-bold text-white">Sangam Tour & Travels</span>. All Rights Reserved.
                    <br class="d-block d-md-none">
                    <span class="d-none d-md-inline mx-2">|</span>
                    Designed & Developed By:
                    <a href="https://www.infoerasoftware.com/" target="_blank" class="text-decoration-none fw-bold" style="color: #FF4E00;">
                        Info Era Software Services Pvt. Ltd.
                    </a>
                </p>
            </div>
        </div>
    </footer>
    <input type="hidden" id="error_trigger" value="{{ $errors->any() ? '1' : '0' }}">
    <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content auth-modal-content shadow-lg">
                <div class="row g-0">
                    <div class="col-md-5 auth-left-bg d-none d-md-flex">
                        <img src="{{ asset('uploads/company/6.png') }}" alt="Logo" class="mb-4 mx-auto" style="height: 50px; filter: brightness(0) invert(1);">
                        <h3 class="fw-bold mb-3">Unlock Premium Travel Deals!</h3>
                        <p class="small opacity-75">Join Sangam Tours to manage your bookings, get exclusive member discounts, and explore the world hassle-free.</p>
                    </div>

                    <div class="col-md-7 p-4 p-md-5 bg-white position-relative">
                        <button type="button" class="btn-close position-absolute top-0 end-0 m-4" data-bs-dismiss="modal"></button>

                        <ul class="nav nav-pills auth-nav-tabs" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-content" type="button">Login</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="register-tab" data-bs-toggle="pill" data-bs-target="#register-content" type="button">Sign Up</button>
                            </li>
                        </ul>

                        <div class="tab-content">
                           <div class="tab-pane fade show active" id="login-content">
    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        {{-- Email Field --}}
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Email Address</label>
            <input type="email" name="email" class="form-control auth-form-control" required placeholder="Enter your email">
        </div>

        {{-- Password Field --}}
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Password</label>
            <input type="password" name="password" class="form-control auth-form-control" required placeholder="Enter password">
        </div>

        {{-- 🔥 Remember Me & Forgot Password (Side by Side) 🔥 --}}
        <div class="mb-4 d-flex justify-content-between align-items-center">
            <div class="form-check mb-0">
                <input type="checkbox" class="form-check-input" id="remember_me" name="remember" style="cursor: pointer;">
                <label class="form-check-label small text-muted" for="remember_me" style="cursor: pointer; user-select: none;">
                    Remember me
                </label>
            </div>
            
            <a href="javascript:void(0)" onclick="showForgotForm()" class="small text-decoration-none fw-bold" style="color: var(--brand-accent);">
                Forgot Password?
            </a>
        </div>

        <button type="submit" class="btn w-100 auth-btn text-white" style="background: var(--brand-blue);">
            Login to Account
        </button>
    </form>
</div>

                            <div class="tab-pane fade" id="register-content">
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Full Name</label>
                                        <input type="text" name="name" class="form-control auth-form-control" required placeholder="e.g. Rahul Sharma">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Email Address</label>
                                        <input type="email" name="email" class="form-control auth-form-control" required placeholder="Enter valid email">
                                    </div>
                                    <div class="row g-2 mb-4">
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Password</label>
                                            <input type="password" name="password" class="form-control auth-form-control" required placeholder="Password">
                                        </div>
                                        <div class="col-6">
                                            <label class="form-label small fw-bold text-muted">Confirm</label>
                                            <input type="password" name="password_confirmation" class="form-control auth-form-control" required placeholder="Confirm">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn w-100 auth-btn text-white" style="background: var(--brand-accent);">Create Account</button>
                                </form>
                            </div>

                           <div id="forgot-password-content" style="display: none;">
    <div class="text-center mb-4">
        <div class="feature-icon mx-auto mb-3" style="width: 60px; height: 60px; background: rgba(255, 78, 0, 0.1); color: var(--brand-accent); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px;">
            <i class='bx bx-lock-open-alt'></i>
        </div>
        <h4 class="fw-bold text-dark">Trouble Logging In?</h4>
        <p class="text-muted small">Enter your email and we'll send you an OTP to get back into your account.</p>
    </div>

    <div id="email-step">
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Registered Email</label>
            <input type="email" id="forgot-email" class="form-control auth-form-control" placeholder="example@gmail.com">
        </div>
        <button onclick="sendOtpJS()" class="btn w-100 auth-btn text-white" style="background: var(--brand-blue);">Send Reset OTP</button>
    </div>

    <div id="otp-step" style="display: none;">
        <div class="mb-3">
            <label class="form-label small fw-bold text-muted">Verify OTP</label>
            <input type="text" id="forgot-otp" class="form-control auth-form-control" placeholder="Enter 6-digit code">
        </div>
        <button onclick="verifyOtpJS()" class="btn w-100 auth-btn text-white" style="background: var(--brand-accent);">Verify & Proceed</button>
    </div>

    <div class="mt-4 text-center border-top pt-3">
        <a href="javascript:void(0)" onclick="backToLogin()" class="text-decoration-none fw-bold small" style="color: var(--brand-blue);">
            <i class='bx bx-arrow-back align-middle'></i> Back to Login
        </a>
    </div>
</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @push('scripts')
    <script>
        $(document).ready(function() {
            // Hidden input se value uthao
            let errorStatus = $('#error_trigger').val();

            // Agar value '1' hai (matlab errors hain), toh modal dikhao
            if (errorStatus === '1') {
                $('#authModal').modal('show');
            }
        });

        // ==========================================
        // 🔥 AI CHATBOT LOGIC (Keeping your existing logic untouched)
        // ==========================================
        function toggleChat() {
            const chatWindow = document.getElementById('aiChatWindow');
            const toggleBtnIcon = document.querySelector('#aiChatToggleBtn i');

            if (chatWindow.style.display === 'none' || chatWindow.style.display === '') {
                chatWindow.style.display = 'flex';
                toggleBtnIcon.classList.remove('bx-message-rounded-dots');
                toggleBtnIcon.classList.add('bx-x');
            } else {
                chatWindow.style.display = 'none';
                toggleBtnIcon.classList.remove('bx-x');
                toggleBtnIcon.classList.add('bx-message-rounded-dots');
            }
        }

        function handleEnter(e) {
            if (e.key === 'Enter') {
                sendRealMessage();
            }
        }

        const sessionId = document.querySelector('meta[name="session-id"]')?.getAttribute('content');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        const chatBody = document.getElementById('chatBody');

        let echoAttempts = 0;
        let echoInterval = setInterval(function() {
            if (window.Echo) {
                clearInterval(echoInterval);
                console.log("🔥 Laravel Echo Connected Successfully!");

                window.Echo.channel(`chat.${sessionId}`)
                    .listen('.ai.replied', (e) => {
                        removeTypingIndicator();
                        appendMessage(e.message, 'ai-message');
                    });
            }
            echoAttempts++;
            if (echoAttempts > 20) {
                clearInterval(echoInterval);
            }
        }, 500);

        // 🔥 Modal band hone par state reset karne ka logic
    $('#authModal').on('hidden.bs.modal', function () {
        backToLogin(); // Modal band hote hi login par switch kar do
    });




        function sendRealMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (message === "") return;

            appendMessage(message, 'user-message');
            input.value = '';
            showTypingIndicator();

            fetch('/chat/send', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: JSON.stringify({
                        message: message
                    })
                })
                .then(response => response.json())
                .catch(error => {
                    console.error('Error:', error);
                    removeTypingIndicator();
                    appendMessage("Sorry! Server Doesn't Connected", 'ai-message');
                });
        }

        function appendMessage(text, senderClass) {
            let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
            if (chatBody) {
                chatBody.innerHTML += `<div class="chat-message ${senderClass}"><div class="msg-bubble">${formattedText}</div></div>`;
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        }

        function showTypingIndicator() {
            if (chatBody) {
                chatBody.innerHTML += `<div class="chat-message ai-message" id="typingIndicator"><div class="msg-bubble"><i>AI is typing...</i> <i class='bx bx-loader-alt bx-spin'></i></div></div>`;
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if (indicator) {
                indicator.remove();
            }
        }

        function showForgotForm() {
    $('#login-content').hide();
    $('.auth-nav-tabs').hide();
    $('#forgot-password-content').fadeIn();
}

function sendOtpJS() {
    let email = $('#forgot-email').val();
    
    $.post("{{ route('password.sendOtp') }}", {
        email: email, 
        _token: "{{ csrf_token() }}"
    }, function(res) {
        if(res.status === 'success') {
            $('#email-step').hide();
            $('#otp-step').fadeIn();
            alert(res.message);
        }
    }).fail(function(err) {
        // 🔥 Asli error message console mein dekhein (F12 daba kar)
        console.error(err.responseJSON); 
        
        if (err.status === 422) {
            alert(err.responseJSON.errors.email[0]); // Validation error (Email doesn't exist)
        } else {
            alert("Server Error: Check if SMTP or Mail class is correct."); // SMTP ya code error
        }
    });
}

function verifyOtpJS() {
    let otp = $('#forgot-otp').val();
    let email = $('#forgot-email').val();
    $.post("{{ route('password.verifyOtp') }}", {otp: otp, email: email, _token: "{{ csrf_token() }}"}, function(res) {
        if(res.status === 'success') {
            window.location.href = res.redirect;
        } else {
            alert(res.message);
        }
    });
}

function backToLogin() {
    // 1. Forgot password wala section hide karo
    $('#forgot-password-content').hide();
    
    // 2. OTP steps ko reset karo taaki agli baar email se shuru ho
    $('#otp-step').hide();
    $('#email-step').show();
    
    // 3. Login form aur Tabs ko wapas dikhao
    $('#login-content').fadeIn();
    $('.auth-nav-tabs').fadeIn();
    
    // 4. Input fields saaf kar do
    $('#forgot-email').val('');
    $('#forgot-otp').val('');
}
    </script>
    @endpush
    @stack('scripts')
</body>

</html>