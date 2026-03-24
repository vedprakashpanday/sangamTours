<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | SangamTours Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <style>
        :root {
            --primary-color: #4e73df;
            --bg-gradient: linear-gradient(135deg, #f8f9fc 0%, #e2e8f0 100%);
        }

        body {
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
        }

        .auth-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05), 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .auth-header {
            background: var(--primary-color);
            padding: 30px;
            text-align: center;
            color: white;
        }

        .auth-header i {
            font-size: 3rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }

        .auth-body {
            padding: 40px 35px;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #a0aec0;
        }

        .form-control {
            border-left: none;
            padding: 12px;
            border-color: #e2e8f0;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: #e2e8f0;
            background-color: #fdfdfd;
        }

        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 12px;
            border-radius: 10px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            margin-top: 10px;
        }

        .btn-primary:hover {
            background-color: #3b5bdb;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        }

        .alert {
            border-radius: 12px;
            padding: 15px;
            font-size: 0.85rem;
        }

        .back-to-login {
            color: #718096;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.9rem;
            transition: color 0.2s;
        }

        .back-to-login:hover {
            color: var(--primary-color);
        }

        /* Loading Spinner Animation */
        .btn-loading {
            position: relative;
            color: transparent !important;
        }
        .btn-loading::after {
            content: "";
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-top: -10px;
            margin-left: -10px;
            border: 3px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 1s ease-in-out infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <i class='bx bxs-lock-open-alt'></i>
        <h4 class="fw-bold mb-0">Forgot Password?</h4>
        <p class="mb-0 small opacity-75">No worries, we'll send you reset instructions.</p>
    </div>

    <div class="auth-body">
        @if (session('status'))
            <div class="alert alert-success border-0 shadow-sm mb-4 d-flex align-items-start">
                <i class='bx bxs-check-circle fs-4 me-2'></i>
                <div>
                    <strong>Link Sent!</strong><br>
                    Please check your Gmail inbox for the recovery link.
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4">
                <div class="d-flex align-items-center mb-1">
                    <i class='bx bxs-error-circle fs-5 me-2'></i>
                    <strong>Action Required</strong>
                </div>
                <ul class="mb-0 ps-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.password.email') }}" method="POST" id="resetForm">
            @csrf
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email" required autofocus>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary w-100" id="submitBtn">
                Send Reset Link
            </button>
            
            <div class="text-center mt-4">
                <a href="{{ route('admin.login') }}" class="back-to-login">
                    <i class='bx bx-left-arrow-alt align-middle fs-5'></i> Back to Login
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    // Submit hone par button par loading spinner dikhane ke liye
    document.getElementById('resetForm').onsubmit = function() {
        document.getElementById('submitBtn').classList.add('btn-loading');
    };
</script>

</body>
</html>