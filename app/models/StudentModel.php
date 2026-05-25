<?php
class StudentModel extends BaseModel {
    public function __construct($pdo) {
        parent::__construct($pdo, 'students');
    }

    // Find student by registration number
    public function findByRegistrationNumber($registration_number) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE registration_number = :registration_number");
        $stmt->execute(['registration_number' => $registration_number]);
        return $stmt->fetch();
    }

    // Search students by name or registration number
    public function searchStudents($search_term) {
        $stmt = $this->pdo->prepare("SELECT * FROM students WHERE first_name LIKE :search OR last_name LIKE :search OR registration_number LIKE :search");
        $search_term = "%{$search_term}%";
        $stmt->execute(['search' => $search_term]);
        return $stmt->fetchAll();
    }

    // Get students with pagination
    public function getStudentsPaginated($limit, $offset) {
        $stmt = $this->pdo->prepare("SELECT * FROM students LIMIT :limit OFFSET :offset");
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Count total students
    public function countStudents() {
        return $this->countAll();
    }
}