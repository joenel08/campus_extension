<?php
class Template
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM templates ORDER BY display_order ASC, id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActive()
    {
        $stmt = $this->db->query("SELECT * FROM templates WHERE status = 'active' ORDER BY display_order ASC, id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM templates WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $template_code, $description, $attached_file, $file_size, $file_type, $display_order = 0, $status = 'active')
    {
        $stmt = $this->db->prepare("
            INSERT INTO templates 
            (title, template_code, description, attached_file, file_size, file_type, display_order, status)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $title, $template_code, $description, $attached_file,
            $file_size, $file_type, $display_order, $status
        ]);
    }

    public function update($id, $title, $template_code, $description, $attached_file, $file_size, $file_type, $display_order, $status)
    {
        if ($attached_file) {
            $stmt = $this->db->prepare("
                UPDATE templates
                SET title = ?, template_code = ?, description = ?, 
                    attached_file = ?, file_size = ?, file_type = ?, 
                    display_order = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $title, $template_code, $description, $attached_file,
                $file_size, $file_type, $display_order, $status, $id
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE templates
                SET title = ?, template_code = ?, description = ?, 
                    display_order = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([
                $title, $template_code, $description,
                $display_order, $status, $id
            ]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM templates WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE templates SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}