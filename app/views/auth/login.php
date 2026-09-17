<?php
$activeSection = $activeSection ?? 'login';
$colleges = $colleges ?? [];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="/css/login_app.css">
    <link rel="icon" type="image/x-icon" href="/images/isu_logo.png">
</head>

<body>

    <div class="container">
        <!-- Left Side -->
        <div class="left-side">
            <div class="overlay">
                <img src="/images/isu_logo.png" class="top-logo" alt="ISU Logo">
            </div>
        </div>

        <!-- Right Side -->
        <div class="right-side">
            <!-- Login Section -->
            <div id="loginSection" style="<?= $activeSection === 'login' ? 'display:block;' : 'display:none;' ?>">
                <div class="form-top">
                    <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" class="form-logo" alt="ISU Logo">
                    <p>Sign in with Email address</p>
                </div>

                <?php if (isset($_SESSION['login_error'])): ?>
                    <div style="color:red; margin-bottom:15px;"> <?= htmlspecialchars($_SESSION['login_error']) ?> </div>
                    <?php unset($_SESSION['login_error']); ?>
                <?php endif; ?>

                <!-- ADD THIS: -->
                <?php if (isset($_SESSION['login_success'])): ?>
                    <div style="color:green; background:#e6f7ea; padding:12px; border-radius:6px; margin-bottom:15px; border-left:4px solid #16a34a;">
                        <i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['login_success']) ?>
                    </div>
                    <?php unset($_SESSION['login_success']); ?>
                <?php endif; ?>

                <form action="/login" method="POST">
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email address" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" id="password" placeholder="Password" required>
                    </div>
                    <div class="show-pass">
                        <input type="checkbox" onclick="togglePassword()">
                        <label>Show Password</label>
                    </div>
                    <button type="submit" class="login-btn">
                        <i class="fas fa-key"></i> Sign-in Account
                    </button>
                </form>

                <div class="bottom-link">
                    No account?
                    <a href="/register" style="color:#00a651; font-weight:bold;">Register</a>
                </div>
            </div>



            <!-- OTP Verification Section -->
            <div id="otpSection" style="<?= $activeSection === 'otp' ? 'display:block;' : 'display:none;' ?>">
                <div class="form-top">
                    <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" class="form-logo" alt="ISU Logo">
                    <p>Verify your email</p>
                </div>
                <?php if (isset($_SESSION['verify_error'])): ?>
                    <div style="color:red; margin-bottom:15px;"> <?= htmlspecialchars($_SESSION['verify_error']) ?> </div>
                    <?php unset($_SESSION['verify_error']); ?>
                <?php endif; ?>
                <?php if (isset($_SESSION['register_success'])): ?>
                    <div style="color:green; margin-bottom:15px;"> <?= htmlspecialchars($_SESSION['register_success']) ?> </div>
                    <?php unset($_SESSION['register_success']); ?>
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

    <script>
        function togglePassword() {
            const pass = document.getElementById('password');
            pass.type = pass.type === 'password' ? 'text' : 'password';
        }

        function showRegister() {
            document.getElementById('loginSection').style.display = 'none';
            document.getElementById('otpSection').style.display = 'none';
            document.getElementById('registerSection').style.display = 'block';
        }

        function showLogin() {
            document.getElementById('registerSection').style.display = 'none';
            document.getElementById('otpSection').style.display = 'none';
            document.getElementById('loginSection').style.display = 'block';
        }

        function showOtp() {
            document.getElementById('loginSection').style.display = 'none';
            document.getElementById('registerSection').style.display = 'none';
            document.getElementById('otpSection').style.display = 'block';
        }

        // Automatically show the correct section based on PHP variable (already set)
        // No extra action needed because we used inline style.
    </script>
</body>

</html>