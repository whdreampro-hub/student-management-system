<?php
class DashboardController {
    private $pdo;
    private $studentModel;
    private $historyModel;
    private $classModel;
    private $academicYearModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->studentModel = new StudentModel($pdo);
        $this->historyModel = new BaseModel($pdo, 'student_class_history');
        $this->classModel = new BaseModel($pdo, 'classes');
        $this->academicYearModel = new BaseModel($pdo, 'academic_years');
    }

    public function index() {
        // Get total students
        $totalStudents = $this->studentModel->countStudents();

        // Get students per class
        $studentsPerClass = $this->pdo->prepare("
            SELECT c.class_name, COUNT(*) as student_count
            FROM student_class_history sch
            JOIN classes c ON sch.class_id = c.id
            WHERE sch.status = 'active'
            GROUP BY c.id, c.class_name
            ORDER BY c.class_name
        ");
        $studentsPerClass->execute();
        $studentsPerClassData = $studentsPerClass->fetchAll();

        // Get recent admissions (students admitted in the last 30 days)
        $recentAdmissions = $this->pdo->prepare("
            SELECT s.*, sch.start_date as admission_date
            FROM students s
            JOIN student_class_history sch ON s.id = sch.student_id
            WHERE sch.reason = 'New Admission'
            AND sch.start_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ORDER BY sch.start_date DESC
            LIMIT 5
        ");
        $recentAdmissions->execute();
        $recentAdmissionsData = $recentAdmissions->fetchAll();

        // Get recent transfers (students transferred in the last 30 days)
        $recentTransfers = $this->pdo->prepare("
            SELECT s.*, sch.start_date as transfer_date, c.class_name as new_class
            FROM students s
            JOIN student_class_history sch ON s.id = sch.student_id
            JOIN classes c ON sch.class_id = c.id
            WHERE sch.reason = 'Transfer'
            AND sch.start_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
            ORDER BY sch.start_date DESC
            LIMIT 5
        ");
        $recentTransfers->execute();
        $recentTransfersData = $recentTransfers->fetchAll();

        // Get statistics for charts
        // Gender distribution
        $genderStats = $this->pdo->prepare("
            SELECT gender, COUNT(*) as count
            FROM students
            GROUP BY gender
        ");
        $genderStats->execute();
        $genderData = $genderStats->fetchAll();

        // Admissions per month (last 6 months)
        $monthlyAdmissions = $this->pdo->prepare("
            SELECT DATE_FORMAT(start_date, '%Y-%m') as month, COUNT(*) as count
            FROM student_class_history
            WHERE reason = 'New Admission'
            AND start_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
            GROUP BY month
            ORDER BY month
        ");
        $monthlyAdmissions->execute();
        $monthlyAdmissionsData = $monthlyAdmissions->fetchAll();

        require_once __DIR__ . '/../views/dashboard/index.php';
    }
}