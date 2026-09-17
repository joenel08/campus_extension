<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify OTP - ISU Extension Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
      <link rel="stylesheet" href="/css/login_app.css">
    <link rel="icon" type="image/x-icon" href="/images/isu_logo.png">
</head>
<body>
    <div class="container">
        <div class="left-side">
            <div class="overlay">
                <img src="/images/isu_logo.png" class="top-logo" alt="ISU Logo">
            </div>
        </div>
        <div class="right-side">
            <div id="loginSection">
            <div class="form-top">
                <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" class="form-logo" alt="ISU Logo">
                <p>Verify your email</p>
            </div>
            <?php if (isset($_SESSION['verify_error'])): ?>
                <div style="color:red; margin-bottom:15px;"> <?= htmlspecialchars($_SESSION['verify_error']) ?> </div>
                <?php unset($_SESSION['verify_error']); ?>
            <?php endif; ?>
        

            <?php if (isset($_SESSION['verify_success'])): ?>
    <div style="color:green; background:#e6f7ea; padding:12px; border-radius:6px; margin-bottom:15px; border-left:4px solid #16a34a;">
        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['verify_success']) ?>
    </div>
    <script>
        setTimeout(() => { window.location.href = '/login'; }, 2500);
    </script>
    <?php unset($_SESSION['verify_success']); ?>
<?php endif; ?>
            <form action="/verify-otp" method="POST">
                <div class="input-group">
                    <input type="text" name="otp" placeholder="Enter OTP (6-digit)" required pattern="[0-9]{6}">
                </div>
                <button type="submit" class="login-btn"><i class="fas fa-check-circle"></i> Verify OTP</button>
            </form>
            <div class="bottom-link">
                Didn't receive OTP? <a href="/register" style="color:#00a651; font-weight:bold;">Resend</a>
            </div>
        </div>
        </div>
    </div>
</body>
</html>