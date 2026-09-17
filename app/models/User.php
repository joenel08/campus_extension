<?php
class User
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Get all users with college abbreviation
    public function getAll()
    {
        $stmt = $this->db->query("
            SELECT u.*, c.abbreviation as college_abbr
            FROM users u
            LEFT JOIN colleges c ON u.college_id = c.id
            ORDER BY u.id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function find($id)
{
    $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

    // public function find($id)
    // {
    //     $stmt = $this->db->prepare("
    //         SELECT u.*, c.abbreviation as college_abbr
    //         FROM users u
    //         LEFT JOIN colleges c ON u.college_id = c.id
    //         WHERE u.id = ?
    //     ");
    //     $stmt->execute([$id]);
    //     return $stmt->fetch(PDO::FETCH_ASSOC);
    // }

    public function create($name, $email, $password, $role, $college_id = null, $status = 'pending')
    {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, role, college_id, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$name, $email, $hashed, $role, $college_id, $status]);
    }

    public function update($id, $name, $email, $role, $college_id = null, $status = null, $password = null)
    {
        if ($password) {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("
                UPDATE users
                SET name = ?, email = ?, role = ?, college_id = ?, status = ?, password = ?
                WHERE id = ?
            ");
            return $stmt->execute([$name, $email, $role, $college_id, $status, $hashed, $id]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE users
                SET name = ?, email = ?, role = ?, college_id = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([$name, $email, $role, $college_id, $status, $id]);
        }
    }

    public function approve($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET status = 'approved' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function decline($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET status = 'declined' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function verifyPassword($plain, $hash)
    {
        return password_verify($plain, $hash);
    }
}