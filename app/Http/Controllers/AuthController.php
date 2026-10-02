<?php
require_once __DIR__ . '/../../models/College.php';
require_once __DIR__ . '/../../models/User.php';
require_once __DIR__ . '/../../models/OtpVerification.php';
require_once __DIR__ . '/../../models/PasswordReset.php';
require_once __DIR__ . '/../../../vendor/autoload.php'; 


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class AuthController
{
    private $userModel;
    private $otpModel;
    private $colleges;
    private $db;

    public function __construct()
    {
        //   echo "AuthController constructed<br>";
        $config = require __DIR__ . '/../../../config/database.php';
        $config['dbname'] = 'extension_db'; // force correct db
        $dsn = "mysql:host={$config['host']};dbname={$config['dbname']};charset={$config['charset']}";
        $pdo = new PDO($dsn, $config['username'], $config['password']);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db = $pdo;
        $this->userModel = new User($pdo);
        $this->colleges = new College($pdo); // <-- This is the problem: property name is $colleges, not $collegeModel
        $this->otpModel = new OtpVerification($pdo);
    }
    public function showLoginForm()
    {
        $activeSection = 'login';
        require __DIR__ . '/../../views/auth/login.php';
    }

    public function login()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: /login');
        exit;
    }

    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $role     = trim($_POST['role'] ?? '');

    if (!$email || !$password || !$role) {
        $_SESSION['login_error'] = 'Email, password, and role are required.';
        header('Location: /login');
        exit;
    }

    try {
        // Find user by email AND role
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ? AND role = ? LIMIT 1");
        $stmt->execute([$email, $role]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
            $_SESSION['login_error'] = 'Invalid email, password, or role.';
            header('Location: /login');
            exit;
        }

        // Set session
        $_SESSION['user_id']      = $user['id'];
        $_SESSION['user_name']    = $user['name'];
        $_SESSION['user_role']    = $user['role'];
        $_SESSION['user_email']   = $user['email'];
        $_SESSION['college_id']   = $user['college_id'];

        // Fetch college abbreviation
        if ($user['college_id']) {
            $stmt = $this->db->prepare("SELECT abbreviation FROM colleges WHERE id = ?");
            $stmt->execute([$user['college_id']]);
            $_SESSION['college_abbr'] = $stmt->fetchColumn() ?: '';
        } else {
            $_SESSION['college_abbr'] = '';
        }

        // Load current academic year
        $stmt = $this->db->query("SELECT * FROM academic_years WHERE is_current = TRUE LIMIT 1");
        $currentYear = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['academic_year_id']    = $currentYear['id'] ?? null;
        $_SESSION['academic_year_label'] = $currentYear['year_label'] ?? 'N/A';

        // Redirect based on role
        // staff redirects to admin dashboard
        if ($user['role'] === 'staff' || $user['role'] === 'admin') {
            header('Location: /admin/dashboard');
        } elseif ($user['role'] === 'extensionist') {
            header('Location: /extensionist/dashboard');
        } elseif ($user['role'] === 'evaluator') {
            header('Location: /evaluator/dashboard');
        }
        exit;
    } catch (PDOException $e) {
        $_SESSION['login_error'] = 'Database error. Please try again.';
        header('Location: /login');
        exit;
    }
}
    // public function login()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         header('Location: /login');
    //         exit;
    //     }

    //     $email = trim($_POST['email'] ?? '');
    //     $password = trim($_POST['password'] ?? '');

    //     try {
    //         $user = $this->userModel->findByEmail($email);
    //         if (!$user || !$this->userModel->verifyPassword($password, $user['password'])) {
    //             $_SESSION['login_error'] = 'Invalid email or password.';
    //             header('Location: /login');
    //             exit;
    //         }

    //         // Load current academic year
    //         $stmt = $this->db->query("SELECT * FROM academic_years WHERE is_current = TRUE LIMIT 1");
    //         $currentYear = $stmt->fetch(PDO::FETCH_ASSOC);
    //         if ($currentYear) {
    //             $_SESSION['academic_year_id'] = $currentYear['id'];
    //             $_SESSION['academic_year_label'] = $currentYear['year_label'];
    //         } else {
    //             $_SESSION['academic_year_id'] = null;
    //             $_SESSION['academic_year_label'] = 'No academic year set';
    //         }
    //         // Set session
    //         $_SESSION['user_id'] = $user['id'];
    //         $_SESSION['user_name'] = $user['name'];
    //         $_SESSION['user_role'] = $user['role'];
    //         $_SESSION['college_id'] = $user['college_id'];

    //         // Fetch college abbreviation using the stored PDO connection
    //         if ($user['college_id']) {
    //             $stmt = $this->db->prepare("SELECT abbreviation FROM colleges WHERE id = ?");
    //             $stmt->execute([$user['college_id']]);
    //             $college = $stmt->fetch(PDO::FETCH_ASSOC);
    //             $_SESSION['college_abbr'] = $college['abbreviation'] ?? '';
    //         } else {
    //             $_SESSION['college_abbr'] = '';
    //         }

    //         header("Location: /{$user['role']}/dashboard");
    //         exit;
    //     } catch (PDOException $e) {
    //         $_SESSION['login_error'] = 'Database error. Please try again.';
    //         header('Location: /login');
    //         exit;
    //     }
    // }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }
    public function showRegisterForm()
    {
        // Attempt to fetch colleges
        try {
            $stmt = $this->db->query("SELECT * FROM colleges ORDER BY id DESC");
            $colleges = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $colleges = [];
        }


        $activeSection = 'register'; // not used but keep for consistency
        require __DIR__ . '/../../views/auth/register.php';
    }
    // Process registration (step 1: send OTP)
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'extensionist';
        $college_id = $_POST['college_id'] ?? null;

        // Basic validation
        if (!$name || !$email || !$password || !$role) {
            $_SESSION['register_error'] = 'All fields are required.';
            header('Location: /register');
            exit;
        }

        // Check if email already registered
        $existing = $this->userModel->findByEmail($email);
        if ($existing) {
            $_SESSION['register_error'] = 'Email already registered. Please login.';
            header('Location: /register');
            exit;
        }

        // Generate OTP (6-digit numeric)
        $otp = sprintf("%06d", mt_rand(1, 999999));
        // $expiresAt = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        // Store OTP in database
        $this->otpModel->deleteExpired($email); // clean old entries
        $this->otpModel->create($email, $otp);

        // Store registration data in session
        $_SESSION['temp_registration'] = [
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'college_id' => $college_id,
        ];

        // Send OTP email
        $sent = $this->sendOtpEmail($email, $otp);

        if ($sent) {
            $_SESSION['register_success'] = 'OTP sent to your email. Please verify.';
            header('Location: /verify-otp');
            exit;
        } else {
            $_SESSION['register_error'] = 'Failed to send OTP. Please try again.';
            header('Location: /register');
            exit;
        }
    }

    // Show OTP verification form
    public function showVerifyOtp()
    {
        if (!isset($_SESSION['temp_registration']) && !isset($_SESSION['verify_success'])) {
            header('Location: /register');
            exit;
        }
        require __DIR__ . '/../../views/auth/verify_otp.php';
    }
    // Verify OTP and create account
    public function verifyOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /verify-otp');
            exit;
        }

        $otp = trim($_POST['otp'] ?? '');
        if (!$otp) {
            $_SESSION['verify_error'] = 'Please enter the OTP.';
            header('Location: /verify-otp');
            exit;
        }

        // Get temp registration data
        $temp = $_SESSION['temp_registration'] ?? null;
        if (!$temp) {
            $_SESSION['verify_error'] = 'Registration session expired. Please register again.';
            header('Location: /register');
            exit;
        }

        $email = $temp['email'];

        // Verify OTP
        $validOtp = $this->otpModel->getValidOtp($email, $otp);
        if (!$validOtp) {
            $_SESSION['verify_error'] = 'Invalid or expired OTP. Please try again.';
            header('Location: /verify-otp');
            exit;
        }

        // Mark OTP as verified
        $this->otpModel->markVerified($validOtp['id']);

        // Create user account
        $hashedPassword = password_hash($temp['password'], PASSWORD_DEFAULT);
        $created = $this->userModel->create(
            $temp['name'],
            $temp['email'],
            $temp['password'],
            $temp['role'],
            $temp['college_id'] ?? null,
            'approved' // auto-approve after OTP verification
        );

        if ($created) {
            unset($_SESSION['temp_registration']);

            // === NOTIFY ADMINS ===
            require_once __DIR__ . '/../../models/Notification.php';
            $notifModel = new Notification($this->db);
            $adminIds = $notifModel->getAdmins();
            $notifModel->createBulk(
                $adminIds,
                'account_registered',
                'New Account Registration',
                $temp['name'] . ' registered as ' . $temp['role'] . '.',
                '/admin/accounts'
            );
            $_SESSION['login_success'] = 'Account verified successfully! Please login with your credentials.';
            header('Location: /login');
            exit;
        } else {
            $_SESSION['verify_error'] = 'Failed to create account. Please contact support.';
            header('Location: /verify-otp');
            exit;
        }
    }

    // Helper: Send OTP email using PHPMailer
    private function sendOtpEmail($email, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            // Server settings (configure according to your mail server)
            // For local development, you can use a test mail service like Mailtrap
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';  // e.g., smtp.gmail.com
            $mail->SMTPAuth   = true;
            $mail->Username   = 'anywherefind2@gmail.com';
            $mail->Password   = 'bken kxvu pcsf zhuo';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('noreply@isu.edu.ph', 'ISU Extension Services');
            $mail->addAddress($email);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'OTP for Registration - ISU Extension Services';
            $mail->Body    = "
                <h3>Welcome to ISU Extension Services</h3>
                <p>Your OTP for registration is:</p>
                <h2 style='color:#0b7a33; font-size:32px;'>{$otp}</h2>
                <p>This OTP is valid for 30 minutes.</p>
                <p>If you did not request this, please ignore this email.</p>
            ";
            $mail->AltBody = "Your OTP for registration is: {$otp}. Valid for 30 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $mail->ErrorInfo);
            return false;
        }
    }


    // ==================== FORGOT PASSWORD ====================

    public function showForgotForm()
    {
        require __DIR__ . '/../../views/auth/forgot_password.php';
    }

    public function sendResetOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /forgot-password');
            exit;
        }

        $email = trim($_POST['email'] ?? '');

        if (!$email) {
            $_SESSION['forgot_error'] = 'Please enter your email address.';
            header('Location: /forgot-password');
            exit;
        }

        // Check if user exists
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            $_SESSION['forgot_error'] = 'No account found with that email.';
            header('Location: /forgot-password');
            exit;
        }

        // Generate OTP
        $otp = sprintf("%06d", mt_rand(1, 999999));

        require_once __DIR__ . '/../../models/PasswordReset.php';
        $resetModel = new PasswordReset($this->db);
        $resetModel->create($email, $otp);

        // Store email in session for next step
        $_SESSION['reset_email'] = $email;

        // Send email
        $sent = $this->sendPasswordResetEmail($email, $otp);

        if ($sent) {
            $_SESSION['forgot_success'] = 'OTP sent to your email.';
            header('Location: /verify-reset-otp');
            exit;
        } else {
            $_SESSION['forgot_error'] = 'Failed to send OTP. Try again.';
            header('Location: /forgot-password');
            exit;
        }
    }

    public function showVerifyResetOtp()
    {
        if (!isset($_SESSION['reset_email'])) {
            header('Location: /forgot-password');
            exit;
        }
        require __DIR__ . '/../../views/auth/verify_reset_otp.php';
    }

    public function verifyResetOtp()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /verify-reset-otp');
            exit;
        }

        $otp = trim($_POST['otp'] ?? '');
        $email = $_SESSION['reset_email'] ?? null;

        if (!$otp || !$email) {
            $_SESSION['forgot_error'] = 'Invalid session. Please start over.';
            header('Location: /forgot-password');
            exit;
        }

        require_once __DIR__ . '/../../models/PasswordReset.php';
        $resetModel = new PasswordReset($this->db);
        $valid = $resetModel->getValidOtp($email, $otp);

        if (!$valid) {
            $_SESSION['forgot_error'] = 'Invalid or expired OTP.';
            header('Location: /verify-reset-otp');
            exit;
        }

        $resetModel->markVerified($valid['id']);
        $_SESSION['reset_verified'] = true;

        header('Location: /reset-password');
        exit;
    }

    public function showResetForm()
    {
        if (!isset($_SESSION['reset_email']) || !isset($_SESSION['reset_verified'])) {
            header('Location: /forgot-password');
            exit;
        }
        require __DIR__ . '/../../views/auth/reset_password.php';
    }

    public function resetPassword()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /reset-password');
            exit;
        }

        $email = $_SESSION['reset_email'] ?? null;
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['confirm_password'] ?? '';

        if (!$email || !isset($_SESSION['reset_verified'])) {
            $_SESSION['forgot_error'] = 'Session expired. Please start over.';
            header('Location: /forgot-password');
            exit;
        }

        if (strlen($password) < 6) {
            $_SESSION['forgot_error'] = 'Password must be at least 6 characters.';
            header('Location: /reset-password');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['forgot_error'] = 'Passwords do not match.';
            header('Location: /reset-password');
            exit;
        }

        // Update password
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$hashed, $email]);

        // Clean up
        require_once __DIR__ . '/../../models/PasswordReset.php';
        $resetModel = new PasswordReset($this->db);
        $resetModel->deleteByEmail($email);

        unset($_SESSION['reset_email']);
        unset($_SESSION['reset_verified']);

        $_SESSION['login_success'] = 'Password reset successfully! Please login.';
        header('Location: /login');
        exit;
    }

    // Helper: Send password reset email
    private function sendPasswordResetEmail($email, $otp)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'anywherefind2@gmail.com';
            $mail->Password   = 'bken kxvu pcsf zhuo';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port       = 587;

            $mail->setFrom('noreply@isu.edu.ph', 'ISU Extension Services');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Password Reset OTP - ISU Extension Services';
            $mail->Body    = "
            <h3>Password Reset Request</h3>
            <p>Your OTP for password reset is:</p>
            <h2 style='color:#0b7a33; font-size:32px;'>{$otp}</h2>
            <p>This OTP is valid for 30 minutes.</p>
            <p>If you did not request this, ignore this email.</p>
        ";
            $mail->AltBody = "Your password reset OTP is: {$otp}. Valid for 30 minutes.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email sending failed: " . $mail->ErrorInfo);
            return false;
        }
    }
}
