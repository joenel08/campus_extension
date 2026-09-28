<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - ISU Extension Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/login_app.css">
</head>
<body>
<div class="container">
    <div class="left-side"><div class="overlay"></div></div>
    <div class="right-side">
         <div id="loginSection">
        <div class="form-top">
            <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" class="form-logo" alt="ISU Logo">
            <p>Forgot your password?</p>
        </div>

        <?php if (isset($_SESSION['forgot_error'])): ?>
            <div style="color:red; margin-bottom:15px;"><?= htmlspecialchars($_SESSION['forgot_error']) ?></div>
            <?php unset($_SESSION['forgot_error']); ?>
        <?php endif; ?>

        <p style="color:#666; margin-bottom:15px; font-size:14px;">
            Enter your email and we'll send you an OTP to reset your password.
        </p>

        <form method="POST" action="/forgot-password">
            <div class="input-group">
                <input type="email" name="email" placeholder="Email address" required>
            </div>
            <button type="submit" class="login-btn">
                <i class="fas fa-paper-plane"></i> Send OTP
            </button>
        </form>

        <div class="bottom-link">
            <a href="/login" style="color:#00a651; font-weight:bold;">← Back to Login</a>
        </div>
    </div>
    </div>
</div>
</body>
</html>