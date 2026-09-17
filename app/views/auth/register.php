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
        <div class="left-side">
            <div class="overlay">
                <img src="/images/isu_logo.png" class="top-logo" alt="ISU Logo">
            </div>
        </div>
        <div class="right-side">
           <div id="loginSection">
             <div class="form-top">
                <img src="/images/ChatGPT Image May 10, 2026, 11_17_02 AM.png" class="form-logo" alt="ISU Logo">
                <p>Create your account</p>
            </div>
            <?php if (isset($_SESSION['register_error'])): ?>
                <div style="color:red; margin-bottom:15px;"> <?= htmlspecialchars($_SESSION['register_error']) ?> </div>
                <?php unset($_SESSION['register_error']); ?>
            <?php endif; ?>
            <?php if (isset($_SESSION['register_success'])): ?>
                <div style="color:green; margin-bottom:15px;"> <?= htmlspecialchars($_SESSION['register_success']) ?> </div>
                <?php unset($_SESSION['register_success']); ?>
            <?php endif; ?>
            <form action="/register" method="POST">
                <div class="input-group">
                    <input type="text" name="name" placeholder="Full Name" required>
                </div>
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email address" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>
                <div class="input-group">
                    <select name="role" required>
                        <option value="extensionist">Extensionist</option>
                        <option value="evaluator">Evaluator</option>
                    </select>
                </div>
                <div class="input-group">
                    <select name="college_id">
                        <option value="">Select College</option>
                        <?php if (!empty($colleges)): ?>
                            <?php foreach ($colleges as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['abbreviation']) ?></option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Fallback hardcoded colleges -->
                            <option value="1">CDC</option>
                            <option value="2">CCSICT</option>
                            <option value="3">CEAT</option>
                            <option value="4">CED</option>
                            <option value="5">CBM</option>
                            <option value="6">CAS</option>
                            <option value="7">CJE</option>
                        <?php endif; ?>
                    </select>
                </div>
                <button type="submit" class="login-btn"><i class="fas fa-envelope"></i> Register & Get OTP</button>
            </form>
            <div class="bottom-link">
                Already have an account? <a href="/login" style="color:#00a651; font-weight:bold;">Login</a>
            </div>
           </div>
        </div>
    </div>
</body>
</html>