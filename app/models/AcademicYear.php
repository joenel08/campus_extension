<?php
class AcademicYear
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM academic_years ORDER BY start_date DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM academic_years WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCurrent()
    {
        $stmt = $this->db->query("SELECT * FROM academic_years WHERE is_current = TRUE LIMIT 1");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($year_label, $start_date, $end_date)
    {
        $stmt = $this->db->prepare("INSERT INTO academic_years (year_label, start_date, end_date) VALUES (?, ?, ?)");
        return $stmt->execute([$year_label, $start_date, $end_date]);
    }

    public function update($id, $year_label, $start_date, $end_date)
    {
        $stmt = $this->db->prepare("UPDATE academic_years SET year_label = ?, start_date = ?, end_date = ? WHERE id = ?");
        return $stmt->execute([$year_label, $start_date, $end_date, $id]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM academic_years WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function setCurrent($id)
    {
        // Unset all
        $this->db->exec("UPDATE academic_years SET is_current = FALSE");
        // Set selected
        $stmt = $this->db->prepare("UPDATE academic_years SET is_current = TRUE WHERE id = ?");
        return $stmt->execute([$id]);
    }
}