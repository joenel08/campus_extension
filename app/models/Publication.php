<?php
class Publication
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM publications ORDER BY year DESC, id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPublished()
    {
        $stmt = $this->db->query("SELECT * FROM publications WHERE status = 'published' ORDER BY year DESC, id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM publications WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $year, $category, $cover_image, $pdf_file, $description, $status)
    {
        $stmt = $this->db->prepare("
            INSERT INTO publications (title, year, category, cover_image, pdf_file, description, status)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$title, $year, $category, $cover_image, $pdf_file, $description, $status]);
    }

    public function update($id, $title, $year, $category, $cover_image, $pdf_file, $description, $status)
    {
        // If cover_image is provided, update it; else keep old
        if ($cover_image && $pdf_file) {
            $stmt = $this->db->prepare("
                UPDATE publications
                SET title = ?, year = ?, category = ?, cover_image = ?, pdf_file = ?, description = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([$title, $year, $category, $cover_image, $pdf_file, $description, $status, $id]);
        } elseif ($cover_image) {
            $stmt = $this->db->prepare("
                UPDATE publications
                SET title = ?, year = ?, category = ?, cover_image = ?, description = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([$title, $year, $category, $cover_image, $description, $status, $id]);
        } elseif ($pdf_file) {
            $stmt = $this->db->prepare("
                UPDATE publications
                SET title = ?, year = ?, category = ?, pdf_file = ?, description = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([$title, $year, $category, $pdf_file, $description, $status, $id]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE publications
                SET title = ?, year = ?, category = ?, description = ?, status = ?
                WHERE id = ?
            ");
            return $stmt->execute([$title, $year, $category, $description, $status, $id]);
        }
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM publications WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function toggleStatus($id, $status)
    {
        $stmt = $this->db->prepare("UPDATE publications SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }


public function getPublishedGroupedByYear()
{
    $publications = $this->getPublished();
    $grouped = [];
    foreach ($publications as $pub) {
        $year = $pub['year'] ?? 'Undated';
        $grouped[$year][] = $pub;
    }
    krsort($grouped);
    return $grouped;
}
}
