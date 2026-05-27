<?php
class HistoryController {
    private HistoryModel $model;
    private StudentModel $studentModel;
    private ClassModel $classModel;
    private AcademicYearModel $yearModel;

    public function __construct() {
        $this->model        = new HistoryModel();
        $this->studentModel = new StudentModel();
        $this->classModel   = new ClassModel();
        $this->yearModel    = new AcademicYearModel();
    }

    public function index(): void {
        $movements = $this->model->getRecentTransfers(50);
        $classes   = $this->classModel->getAll();
        $years     = $this->yearModel->getAll();
        require_once APP . '/views/history/index.php';
    }

    public function promote(): void {
        $studentId = (int)($_POST['student_id'] ?? 0);
        $classId   = (int)($_POST['class_id'] ?? 0);
        $yearId    = (int)($_POST['academic_year_id'] ?? 0);
        $remarks   = trim($_POST['remarks'] ?? '');

        if (!$studentId || !$classId || !$yearId) {
            jsonResponse(['success' => false, 'message' => 'All fields are required.']);
            return;
        }
        $this->model->promote($studentId, $classId, $yearId, $remarks);
        $student = $this->studentModel->getById($studentId);
        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'PROMOTE_STUDENT',
            "Promoted student {$student['first_name']} {$student['last_name']} to class #$classId",
            'student', $studentId);
        jsonResponse(['success' => true, 'message' => 'Student promoted successfully.']);
    }

    public function transfer(): void {
        $studentId = (int)($_POST['student_id'] ?? 0);
        $classId   = (int)($_POST['class_id'] ?? 0);
        $yearId    = (int)($_POST['academic_year_id'] ?? 0);
        $remarks   = trim($_POST['remarks'] ?? '');

        if (!$studentId || !$classId || !$yearId) {
            jsonResponse(['success' => false, 'message' => 'All fields are required.']);
            return;
        }
        $this->model->transfer($studentId, $classId, $yearId, $remarks);
        $student = $this->studentModel->getById($studentId);
        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'TRANSFER_STUDENT',
            "Transferred student {$student['first_name']} {$student['last_name']} to class #$classId",
            'student', $studentId);
        jsonResponse(['success' => true, 'message' => 'Student transferred successfully.']);
    }

    public function repeat(): void {
        $studentId = (int)($_POST['student_id'] ?? 0);
        $classId   = (int)($_POST['class_id'] ?? 0);
        $yearId    = (int)($_POST['academic_year_id'] ?? 0);
        $remarks   = trim($_POST['remarks'] ?? '');

        if (!$studentId || !$classId || !$yearId) {
            jsonResponse(['success' => false, 'message' => 'All fields are required.']);
            return;
        }
        $this->model->repeat($studentId, $classId, $yearId, $remarks);
        jsonResponse(['success' => true, 'message' => 'Student set to repeat class.']);
    }

    public function getStudentHistory(): void {
        $studentId = (int)($_GET['student_id'] ?? 0);
        if (!$studentId) { jsonResponse(['success' => false, 'message' => 'Invalid ID.']); return; }
        $history = $this->model->getStudentHistory($studentId);
        jsonResponse(['success' => true, 'data' => $history]);
    }
}
