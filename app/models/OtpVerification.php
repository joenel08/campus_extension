<?php
class OtpVerification
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

   public function create($email, $otp)
{
    $stmt = $this->db->prepare("
        INSERT INTO otp_verifications (email, otp_code, expires_at) 
        VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 30 MINUTE))
    ");
    return $stmt->execute([$email, $otp]);
}

    public function getValidOtp($email, $otp)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM otp_verifications 
            WHERE email = ? AND otp_code = ? AND expires_at > NOW() AND is_verified = FALSE
            ORDER BY id DESC LIMIT 1
        ");
        $stmt->execute([$email, $otp]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function markVerified($id)
    {
        $stmt = $this->db->prepare("UPDATE otp_verifications SET is_verified = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deleteExpired($email)
    {
        $stmt = $this->db->prepare("DELETE FROM otp_verifications WHERE email = ? OR expires_at < NOW()");
        return $stmt->execute([$email]);
    }
}