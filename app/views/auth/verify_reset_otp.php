<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Verify OTP - ISU Extension Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/login_app.css">
</head>

<body>
    <div class="container">
        <div class="left-side">
            <div class="overlay"></div>
        </div>
        <div class="right-side">
            <div id="loginSection">
                <div class="form-top">
                    <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" class="form-logo" alt="ISU Logo">
                    <p>Enter the OTP sent to your email</p>
                </div>

                <?php if (isset($_SESSION['forgot_success'])): ?>
                    <div style="color:green; background:#e6f7ea; padding:12px; border-radius:6px; margin-bottom:15px; border-left:4px solid #16a34a;">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['forgot_success']) ?>
                    </div>
                    <?php unset($_SESSION['forgot_success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['forgot_error'])): ?>
                    <div style="color:red; margin-bottom:15px;"><?= htmlspecialchars($_SESSION['forgot_error']) ?></div>
                    <?php unset($_SESSION['forgot_error']); ?>
                <?php endif; ?>

                <form method="POST" action="/verify-reset-otp">
                    <div class="input-group">
                        <input type="text" name="otp" placeholder="Enter OTP (6-digit)" required pattern="[0-9]{6}">
                    </div>
                    <button type="submit" class="login-btn">
                        <i class="fas fa-check-circle"></i> Verify OTP
                    </button>
                </form>

                <div class="bottom-link">
                    <a href="/forgot-password" style="color:#00a651; font-weight:bold;">Resend OTP</a>
                </div>
            </div>
        </div>
    </div>
</body>

</html>