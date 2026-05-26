<?php
class AcademicYear {
    private $conn;
    private $table = "academic_years";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY year_name DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getActiveYear() {
        $query = "SELECT * FROM " . $this->table . " WHERE status = 'active' LIMIT 1";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($year_name, $start_date, $end_date) {
        $query = "INSERT INTO " . $this->table . " (year_name, start_date, end_date) VALUES (:year_name, :start_date, :end_date)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':year_name', $year_name);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':end_date', $end_date);
        return $stmt->execute();
    }

    public function setActive($id) {
        // First set all to inactive
        $query1 = "UPDATE " . $this->table . " SET status = 'inactive'";
        $this->conn->exec($query1);
        
        // Then set selected to active
        $query2 = "UPDATE " . $this->table . " SET status = 'active' WHERE id = :id";
        $stmt = $this->conn->prepare($query2);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $query = "DELETE FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}
?>