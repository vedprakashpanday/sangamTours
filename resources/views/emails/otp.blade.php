<!DOCTYPE html>
<html>
<head>
    <style>
        .email-container {
            font-family: 'Arial', sans-serif;
            max-width: 600px;
            margin: 0 auto;
            border: 1px solid #e1e1e1;
            border-radius: 10px;
            overflow: hidden;
        }
        .header {
            background-color: #0A2239; /* Aapka Brand Blue */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .body {
            padding: 30px;
            text-align: center;
            line-height: 1.6;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            color: #FF4E00; /* Aapka Brand Orange */
            letter-spacing: 5px;
            margin: 20px 0;
            padding: 10px;
            background: #fdf2f2;
            display: inline-block;
            border-radius: 5px;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 15px;
            font-size: 12px;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h2>Sangam Tour & Travels</h2>
        </div>
        <div class="body">
            <h3>Hello,</h3>
            <p>You have requested to reset your password. Use the following OTP to verify your account. This OTP is valid for 10 minutes only.</p>
            
            <div class="otp-code">
                {{ $otp }}
            </div>

            <p>If you did not request this, please ignore this email or contact support.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Sangam Tour & Travels. All Rights Reserved. <br>
            Abhinandan Market, Leheriasarai, Darbhanga.
        </div>
    </div>
</body>
</html>