<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Registration Rejected</title>
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
            background: linear-gradient(135deg, #C62828 0%, #B71C1C 100%);
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
            background: linear-gradient(90deg, #E76268 0%, #D6BFBF 50%, #E76268 100%);
        }
        
        .logo-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 20px;
            background-color: rgba(255, 255, 255, 0.15);
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
        
        .rejection-card {
            background: linear-gradient(135deg, #FFEBEE 0%, #ffffff 100%);
            border: 3px solid #C62828;
            border-radius: 16px;
            padding: 30px;
            margin: 30px 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(198, 40, 40, 0.2);
        }
        
        .rejection-icon {
            font-size: 4rem;
            margin-bottom: 15px;
        }
        
        .rejection-title {
            font-size: 1.6rem;
            font-weight: 700;
            color: #C62828;
            margin-bottom: 15px;
        }
        
        .rejection-badge {
            display: inline-block;
            background-color: #C62828;
            color: #F3EBDD;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 0.9rem;
            margin: 15px 0;
        }
        
        .reason-box {
            background: linear-gradient(135deg, #FFF3E0 0%, #ffffff 100%);
            border-left: 4px solid #E65100;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }
        
        .reason-title {
            font-weight: 700;
            color: #E65100;
            margin-bottom: 12px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .reason-text {
            color: #36454f;
            font-size: 0.95rem;
            line-height: 1.7;
            padding: 12px;
            background-color: rgba(255, 255, 255, 0.7);
            border-radius: 8px;
        }
        
        .warning-box {
            background: linear-gradient(135deg, #FFF8E1 0%, #ffffff 100%);
            border: 2px solid #F57C00;
            border-radius: 12px;
            padding: 20px;
            margin: 25px 0;
            box-shadow: 0 2px 8px rgba(245, 124, 0, 0.15);
        }
        
        .warning-title {
            font-weight: 700;
            color: #E65100;
            margin-bottom: 10px;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .warning-text {
            color: #36454f;
            font-size: 0.95rem;
            line-height: 1.7;
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
            
            .rejection-card {
                padding: 25px 20px;
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
                We regret to inform you that your registration request has been rejected.
            </p>
            
            <div class="rejection-card">
                <div class="rejection-icon">✗</div>
                <h2 class="rejection-title">Registration Rejected</h2>
                <span class="rejection-badge">Account Not Approved</span>
            </div>
            
            <div class="reason-box">
                <div class="reason-title">
                    <span>📋</span>
                    <span>Reason for Rejection:</span>
                </div>
                <div class="reason-text">
                    @if($rejectionReason && !empty(trim($rejectionReason)))
                        {{ $rejectionReason }}
                    @else
                        <em style="color: #888;">No specific reason was provided.</em>
                    @endif
                </div>
            </div>
            
            <div class="warning-box">
                <div class="warning-title">
                    <span>⚠️</span>
                    <span>Important Notice</span>
                </div>
                <p class="warning-text">
                    <strong>Your account has been completely deleted from our system.</strong> You will need to create a new account if you wish to register again after addressing the issues mentioned above.
                </p>
            </div>
            
            <div class="info-box">
                <div class="info-box-title">💡 What to do next?</div>
                <p class="info-box-text">
                    We encourage you to review the requirements and ensure all information is correct before submitting a new registration. Please address the issues mentioned in the rejection reason (if provided) before attempting to register again.
                </p>
            </div>
            
            <p class="content-text">
                If you believe this rejection was made in error, or if you have any questions about the rejection, please contact our support team. We're here to help and guide you through the registration process.
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
