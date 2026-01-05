<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Verification Pending</title>
    <!-- Favicon -->
    <link rel="icon" type="image/jpeg" href="{{ asset('favicon.jpg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ asset('icons/thefavicon.jpg') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #36454f;
            color: #193948;
            line-height: 1.6;
            padding: 20px;
        }
        
        .email-wrapper {
            max-width: 600px;
            margin: 0 auto;
            background-color: #F3EBDD;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }
        
        .email-header {
            background: linear-gradient(135deg, #193948 0%, #36454f 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        
        .email-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #4FADC0 0%, #D6BFBF 50%, #4FADC0 100%);
        }
        
        .logo-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px;
        }
        
        .logo-text {
            font-family: 'Pacifico', cursive;
            font-size: 2.5rem;
            color: #F3EBDD;
            text-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
            margin: 0;
        }
        
        .email-content {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 1.3rem;
            font-weight: 600;
            color: #193948;
            margin-bottom: 20px;
        }
        
        .content-text {
            font-size: 1rem;
            color: #36454f;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        
        .status-card {
            background: linear-gradient(135deg, #F3EBDD 0%, #ffffff 100%);
            border: 3px solid #193948;
            border-radius: 16px;
            padding: 25px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(25, 57, 72, 0.1);
        }
        
        .status-icon {
            font-size: 3rem;
            margin-bottom: 15px;
        }
        
        .status-title {
            font-size: 1.4rem;
            font-weight: 700;
            color: #193948;
            margin-bottom: 10px;
        }
        
        .status-description {
            font-size: 0.95rem;
            color: #36454f;
            line-height: 1.6;
        }
        
        .info-box {
            background-color: #ffffff;
            border-left: 4px solid #4FADC0;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .info-box-title {
            font-weight: 600;
            color: #193948;
            margin-bottom: 10px;
            font-size: 1.1rem;
        }
        
        .info-box-text {
            color: #36454f;
            font-size: 0.95rem;
            line-height: 1.7;
        }
        
        .email-footer {
            background-color: #193948;
            padding: 30px;
            text-align: center;
            color: #F3EBDD;
        }
        
        .footer-text {
            font-size: 0.9rem;
            margin-bottom: 10px;
            opacity: 0.9;
        }
        
        .footer-signature {
            font-family: 'Pacifico', cursive;
            font-size: 1.2rem;
            color: #D6BFBF;
            margin-top: 15px;
        }
        
        .footer-note {
            font-size: 0.8rem;
            opacity: 0.7;
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid rgba(243, 235, 221, 0.2);
        }
        
        @media only screen and (max-width: 600px) {
            .email-content {
                padding: 30px 20px;
            }
            
            .logo-text {
                font-size: 2rem;
            }
            
            .status-card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-header">
            <div class="logo-icon">
                <svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 2L2 7L12 12L22 7L12 2Z" fill="#F3EBDD" stroke="#F3EBDD" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 17L12 22L22 17" stroke="#D6BFBF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M2 12L12 17L22 12" stroke="#D6BFBF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </div>
            <h1 class="logo-text">ArtRights</h1>
        </div>
        
        <div class="email-content">
            <p class="greeting">Hello {{ $user->name }},</p>
            
            <p class="content-text">
                Thank you for registering on the ArtRights platform! We have received your registration request and your account is currently pending verification.
            </p>
            
            <div class="status-card">
                <div class="status-icon">⏳</div>
                <h2 class="status-title">Verification in Progress</h2>
                <p class="status-description">
                    We will review your account and get back to you via email once we have made a decision regarding approval or rejection of your account.
                </p>
            </div>
            
            <div class="info-box">
                <div class="info-box-title">📋 What happens next?</div>
                <p class="info-box-text">
                    Our team will carefully review your registration information and documents. This process may take a few business days. Please be patient while we process your request.
                </p>
            </div>
            
            <p class="content-text">
                If you have any questions or need assistance, please don't hesitate to contact our support team. We're here to help!
            </p>
        </div>
        
        <div class="email-footer">
            <p class="footer-text">Best regards,</p>
            <p class="footer-signature">The ArtRights Team</p>
            <p class="footer-note">This is an automated message. Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
