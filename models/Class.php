<?php
class ClassModel {
    private $conn;
    private $table = "classes";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function readAll() {
        $query = "SELECT * FROM " . $this->table . " ORDER BY level, class_name";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function readOne($id) {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($class_name, $level, $capacity) {
        $query = "INSERT INTO " . $this->table . " (class_name, level, capacity) VALUES (:class_name, :level, :capacity)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':class_name', $class_name);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':capacity', $capacity);
        return $stmt->execute();
    }

    public function update($id, $class_name, $level, $capacity) {
        $query = "UPDATE " . $this->table . " SET class_name = :class_name, level = :level, capacity = :capacity WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':class_name', $class_name);
        $stmt->bindParam(':level', $level);
        $stmt->bindParam(':capacity', $capacity);
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