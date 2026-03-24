<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Sangam Tours Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    
    <style>
        :root {
            --primary-color: #4e73df; /* Modern Blue */
            --bg-gradient: linear-gradient(135deg, #f8f9fc 0%, #e2e8f0 100%);
        }

        body {
            background: var(--bg-gradient);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Inter', sans-serif;
            margin: 0;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 20px;
            border: none;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.05), 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .login-header {
            background: var(--primary-color);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }

        .login-header i {
            font-size: 3.5rem;
            margin-bottom: 15px;
            display: block;
        }

        .login-header h3 {
            font-weight: 700;
            letter-spacing: -0.5px;
            margin-bottom: 5px;
        }

        .login-body {
            padding: 40px 35px;
        }

        .form-label {
            font-weight: 600;
            color: #4a5568;
            font-size: 0.9rem;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border-right: none;
            color: #a0aec0;
            padding-left: 15px;
        }

        .form-control {
            border-left: none;
            padding: 12px;
            border-color: #e2e8f0;
            font-size: 0.95rem;
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
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #3b5bdb;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.3);
        }

        .alert {
            border-radius: 12px;
            font-size: 0.85rem;
            border: none;
            background-color: #fff5f5;
            color: #c53030;
            display: flex;
            align-items: center;
        }

        .forgot-link {
            font-size: 0.85rem;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
        }

        .forgot-link:hover {
            text-decoration: underline;
        }

        /* Checkbox custom style */
        .form-check-input:checked {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
    </style>
</head>
<body>

<div class="login-card">
    <div class="login-header">
        <i class='bx bxs-plane-take-off'></i>
        <h3>Sangam Tours</h3>
        <p class="mb-0 opacity-75 small">Admin Control Panel</p>
    </div>

    <div class="login-body">
        @if($errors->any())
            <div class="alert mb-4 shadow-sm">
                <i class='bx bxs-error-circle fs-5 me-2'></i>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        <form action="{{ url('admin/login') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <label class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-envelope'></i></span>
                    <input type="email" name="email" class="form-control" placeholder="admin@sangamtours.com" required autofocus>
                </div>
            </div>

            <div class="mb-3">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <label class="form-label mb-0">Password</label>
                    <a href="{{ route('admin.password.request') }}" class="forgot-link">Forgot?</a>
                </div>
                <div class="input-group">
                    <span class="input-group-text"><i class='bx bx-lock-alt'></i></span>
                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                </div>
            </div>

            <div class="mb-4 form-check">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label small text-muted" for="remember">Keep me logged in</label>
            </div>

            <button type="submit" class="btn btn-primary w-100 mb-3">
                Sign In <i class='bx bx-log-in-circle align-middle ms-1'></i>
            </button>
        </form>
    </div>
</div>

</body>
</html>