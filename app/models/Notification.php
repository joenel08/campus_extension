<?php
class Notification
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Create a single notification
    public function create($user_id, $type, $title, $message, $link = null)
    {
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, title, message, link)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $type, $title, $message, $link]);
    }

    // Create notifications for multiple users
    public function createBulk($user_ids, $type, $title, $message, $link = null)
    {
        if (empty($user_ids)) return false;
        $stmt = $this->db->prepare("
            INSERT INTO notifications (user_id, type, title, message, link)
            VALUES (?, ?, ?, ?, ?)
        ");
        foreach ($user_ids as $uid) {
            $stmt->execute([$uid, $type, $title, $message, $link]);
        }
        return true;
    }

    // Get latest notifications for a user
    public function getByUser($user_id, $limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM notifications 
            WHERE user_id = ? 
            ORDER BY created_at DESC 
            LIMIT " . intval($limit)
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Count unread
    public function countUnread($user_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
        $stmt->execute([$user_id]);
        return $stmt->fetchColumn();
    }

    // Mark single as read
    public function markRead($id)
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Mark all as read
    public function markAllRead($user_id)
    {
        $stmt = $this->db->prepare("UPDATE notifications SET is_read = TRUE WHERE user_id = ?");
        return $stmt->execute([$user_id]);
    }

    // Helper: get all extensionists in a given college
    public function getUsersByRoleAndCollege($role, $college_id = null)
    {
        if ($college_id) {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE role = ? AND college_id = ? AND status = 'approved'");
            $stmt->execute([$role, $college_id]);
        } else {
            $stmt = $this->db->prepare("SELECT id FROM users WHERE role = ? AND status = 'approved'");
            $stmt->execute([$role]);
        }
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // Helper: get all admins
    public function getAdmins()
    {
        $stmt = $this->db->query("SELECT id FROM users WHERE role = 'admin'");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}