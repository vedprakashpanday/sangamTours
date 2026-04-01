<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="session-id" content="{{ session()->getId() }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>Sangam Tours | Explore the World</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Poppins:wght@400;600;800;900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        :root {
            --brand-blue: #0A2239;
            --brand-accent: #FF4E00;
            --mmt-blue: #008cff;
            --text-dark: #333333;
            --text-light: #6c757d;
        }

        body { font-family: 'Inter', sans-serif; overflow-x: hidden; background-color: #f2f2f2; }
        h1, h2, h3, h4, h5, h6, .navbar-brand { font-family: 'Poppins', sans-serif; }

        /* =========================================
           🔥 THE ULTIMATE Z-INDEX & VISIBILITY FIX 🔥
           ========================================= */
        .navbar {
            background-color: #ffffff !important;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            padding: 12px 0;
            transition: all 0.3s ease;
            z-index: 9999 !important; /* Pushes entire navbar to the front */
        }

        .navbar-brand img { width: 140px; }

       .navbar-nav .nav-link {
            font-family: 'Inter', sans-serif;
            font-weight: 700 !important;
            color: #000000 !important; /* Strict Black Text */
            opacity: 1 !important; /* 🔥 YE WALA FIX: Force text to be 100% visible */
            font-size: 16px;
            margin: 0 10px;
            padding: 10px 15px !important;
            transition: all 0.3s ease;
        }

        .navbar-nav .nav-link:hover { 
            color: #FF4E00 !important; 
            opacity: 1 !important;
        }

        /* Modern Login Button */
        .btn-login-nav {
            background: linear-gradient(135deg, var(--brand-accent), #e64600) !important;
            color: #ffffff !important;
            border-radius: 50px;
            padding: 10px 25px !important;
            font-weight: 700 !important;
            box-shadow: 0 4px 15px rgba(255, 78, 0, 0.3);
            margin-left: 15px;
        }
        .btn-login-nav:hover { transform: translateY(-2px); box-shadow: 0 6px 20px rgba(255, 78, 0, 0.4); }

        /* Burger Menu Icon Fix */
        .navbar-toggler { 
            border: 2px solid #000; 
            padding: 5px 8px; 
            background: #fff;
            border-radius: 8px;
        }
        .navbar-toggler-icon { filter: brightness(0); } /* Forces icon to be dark */
        .navbar-toggler i { font-size: 28px; color: #000; }

        /* 🔥 MOBILE MENU Z-INDEX FIX (Cleaned Duplicate) 🔥 */
        @media (max-width: 991px) {
            .navbar-collapse {
                position: absolute !important; /* Forces it to drop down from navbar */
                top: 100%; /* Exactly below the navbar */
                left: 0;
                width: 100%;
                background-color: #ffffff !important;
                z-index: 10000 !important; /* Forces menu over the Hero Image */
                padding: 20px 10px;
                box-shadow: 0 15px 30px rgba(0,0,0,0.2);
                border-top: 1px solid #eee;
                border-radius: 0 0 15px 15px;
            }
            .navbar-nav .nav-link { 
                border-bottom: 1px solid #f0f0f0; 
                width: 100%; 
                text-align: center; 
                padding: 12px 0 !important;
                margin: 5px 0;
                display: block;
            }
            .navbar-nav .nav-item:last-child .nav-link { border-bottom: none; }
            .btn-login-nav { margin-top: 15px; width: 80%; display: block; margin-left: auto; margin-right: auto; }
        }

        /* =========================================
           🔥 FOOTER (PREMIUM DARK MMT STYLE) 🔥
           ========================================= */
        .main-footer {
            background: #000000;
            color: #ffffff;
            padding: 70px 0 30px;
            margin-top: 60px;
        }

        .footer-logo { font-size: 24px; font-weight: 900; letter-spacing: 1px; margin-bottom: 20px; color: #fff;}
        .footer-logo span { color: var(--mmt-blue); }

        .footer-heading {
            font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;
            margin-bottom: 25px; color: #ffffff;
        }

        .footer-links { list-style: none; padding: 0; margin: 0; }
        .footer-links li { margin-bottom: 15px; }
        .footer-links a {
            color: #999999; text-decoration: none; font-size: 14px; font-weight: 500; transition: 0.3s;
        }
        .footer-links a:hover { color: var(--mmt-blue); padding-left: 5px; }

        .social-icons { display: flex; gap: 15px; margin-top: 20px; }
        .social-icons a {
            width: 40px; height: 40px; border-radius: 50%; background: #222;
            display: flex; align-items: center; justify-content: center;
            color: #fff; font-size: 20px; transition: 0.3s; text-decoration: none;
        }
        .social-icons a:hover { background: var(--mmt-blue); transform: translateY(-3px); }

        .footer-contact { font-size: 14px; color: #999; line-height: 1.8; }
        .footer-contact i { color: var(--mmt-blue); margin-right: 8px; font-size: 18px; vertical-align: middle;}

        .footer-bottom { border-top: 1px solid #333; padding-top: 25px; margin-top: 40px; }
        .copyright { color: #777; font-size: 13px; text-align: center; margin: 0; }

        /* Mobile Footer Adjustments */
        @media (max-width: 768px) {
            .main-footer { padding: 50px 0 20px; text-align: left; }
            .social-icons { justify-content: flex-start; }
            .footer-heading { margin-top: 30px; margin-bottom: 15px; }
        }




    </style>
    @stack('styles')
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top bg-white shadow-sm" style="z-index: 99999 !important;">
        <div class="container">
            <a class="navbar-brand" href="#"> 
                <img src="{{ asset('uploads/company/6.png') }}" alt="Sangam Tours" style="height: 45px; width: auto; max-width: 180px;">
            </a>

            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class='bx bx-menu text-dark' style="font-size: 35px;"></i>
            </button>

            <div class="collapse navbar-collapse bg-white" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center text-center py-3 py-lg-0">
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3" href="#" style="color: #000000 !important; opacity: 1 !important; font-size: 16px;">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3" href="#" style="color: #000000 !important; opacity: 1 !important; font-size: 16px;">Tours</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link fw-bold px-3" href="#" style="color: #000000 !important; opacity: 1 !important; font-size: 16px;">Bus Service</a>
                    </li>
                    <li class="nav-item mt-3 mt-lg-0 ms-lg-3">
                        <a class="btn text-white fw-bold px-4 py-2" href="{{ url('login') }}" style="background-color: #FF4E00; border-radius: 50px; opacity: 1 !important;">Login / Signup</a>
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
                    <div class="footer-logo">SANGAM <span>TOURS</span></div>
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
                        <li><a href="#">About Us</a></li>
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
                        <p class="mb-2"><i class='bx bx-envelope'></i> support@sangamtours.com</p>
                        <p class="mb-2"><i class='bx bx-phone'></i> +91 98765 43210</p>
                        <p class="mb-0"><i class='bx bx-map'></i> Boring Road, Patna, Bihar, India 800001</p>
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="copyright">© 2026 Sangam Tours. All rights reserved. Crafted with <i class='bx bxs-heart text-danger'></i> in Bihar.</p>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @push('scripts')
    <script>
        $(document).ready(function() {
            setTimeout(function() { $('#welcomeModal').modal('show'); }, 600);
        });

        // ==========================================
        // 🔥 AI CHATBOT LOGIC
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
            if(e.key === 'Enter') { sendRealMessage(); }
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
            if (echoAttempts > 20) { clearInterval(echoInterval); }
        }, 500);

        function sendRealMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if(message === "") return;

            appendMessage(message, 'user-message');
            input.value = '';
            showTypingIndicator();

            fetch('/chat/send', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ message: message })
            })
            .then(response => response.json())
            .catch(error => {
                console.error('Error:', error);
                removeTypingIndicator();
                appendMessage("Bhai, server connect nahi ho pa raha.", 'ai-message');
            });
        }

        function appendMessage(text, senderClass) {
            let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<b>$1</b>');
            if(chatBody) {
                chatBody.innerHTML += `<div class="chat-message ${senderClass}"><div class="msg-bubble">${formattedText}</div></div>`;
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        }

        function showTypingIndicator() {
            if(chatBody) {
                chatBody.innerHTML += `<div class="chat-message ai-message" id="typingIndicator"><div class="msg-bubble"><i>AI is typing...</i> <i class='bx bx-loader-alt bx-spin'></i></div></div>`;
                chatBody.scrollTop = chatBody.scrollHeight;
            }
        }

        function removeTypingIndicator() {
            const indicator = document.getElementById('typingIndicator');
            if(indicator) { indicator.remove(); }
        }
    </script>
    @endpush
    @stack('scripts')
</body>
</html>