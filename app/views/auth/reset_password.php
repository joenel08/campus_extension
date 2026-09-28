<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - ISU Extension Services</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/login_app.css">
</head>
<body>
<div class="container">
    <div class="left-side"><div class="overlay"></div></div>
    <div class="right-side">
        <div class="form-top">
            <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" class="form-logo" alt="ISU Logo">
            <p>Set a new password</p>
        </div>

        <?php if (isset($_SESSION['forgot_error'])): ?>
            <div style="color:red; margin-bottom:15px;"><?= htmlspecialchars($_SESSION['forgot_error']) ?></div>
            <?php unset($_SESSION['forgot_error']); ?>
        <?php endif; ?>

        <form method="POST" action="/reset-password">
            <div class="input-group">
                <input type="password" name="password" placeholder="New Password" required minlength="6">
            </div>
            <div class="input-group">
                <input type="password" name="confirm_password" placeholder="Confirm Password" required minlength="6">
            </div>
            <button type="submit" class="login-btn">
                <i class="fas fa-save"></i> Reset Password
            </button>
        </form>
    </div>
</div>
</body>
</html>