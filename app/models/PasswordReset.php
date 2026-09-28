<?php
class PasswordReset
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function create($email, $otp)
    {
        // Delete previous requests for this email
        $stmt = $this->db->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$email]);

        // Insert new with 30-minute expiry using MySQL NOW()
        $stmt = $this->db->prepare("
            INSERT INTO password_resets (email, otp_code, expires_at)
            VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))
        ");
        return $stmt->execute([$email, $otp]);
    }

    public function getValidOtp($email, $otp)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM password_resets
            WHERE email = ? AND otp_code = ? 
              AND expires_at > NOW() AND is_verified = FALSE
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$email, $otp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markVerified($id)
    {
        $stmt = $this->db->prepare("UPDATE password_resets SET is_verified = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteByEmail($email)
    {
        $stmt = $this->db->prepare("DELETE FROM password_resets WHERE email = ?");
        return $stmt->execute([$email]);
    }
}