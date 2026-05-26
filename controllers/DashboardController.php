<?php
require_once '../models/Student.php';
require_once '../models/StudentHistory.php';

class DashboardController {
    private $db;
    private $student;
    private $history;

    public function __construct($db) {
        $this->db = $db;
        $this->student = new Student($db);
        $this->history = new StudentHistory($db);
    }

    public function index() {
        // Get statistics
        $total_students = $this->student->getTotalCount();
        $class_stats = $this->history->getClassStatistics();
        $recent_students = $this->student->readAll(5, 0);
        
        // Get gender statistics
        $male_count = $this->getGenderCount('Male');
        $female_count = $this->getGenderCount('Female');
        
        require_once '../views/dashboard/index.php';
    }
    
    private function getGenderCount($gender) {
        $query = "SELECT COUNT(*) as count FROM students WHERE gender = :gender AND is_deleted = 0";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':gender', $gender);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
}
?>