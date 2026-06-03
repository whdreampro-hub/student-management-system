<?php
class ClassController {
    private ClassModel $model;

    public function __construct() {
        $this->model = new ClassModel();
    }

    public function index(): void {
        $yearModel  = new AcademicYearModel();
        $activeYear = $yearModel->getActive();
        $yearId     = $activeYear ? $activeYear['id'] : 0;
        $classes    = $this->model->getAllWithCounts($yearId);
        require_once APP . '/views/classes/index.php';
    }

    /** Show all students enrolled in a specific class */
    public function students(): void {
        $classId    = (int)($_GET['class_id'] ?? 0);
        $yearModel  = new AcademicYearModel();
        $activeYear = $yearModel->getActive();
        $yearId     = $activeYear ? (int)$activeYear['id'] : 0;

        if (!$classId) { redirect('?page=classes'); return; }

        $class   = $this->model->getById($classId);
        if (!$class) { redirect('?page=classes'); return; }

        $studentModel = new StudentModel();
        $discModel    = new DisciplineModel();
        $attModel     = new AttendanceModel();

        $students = $studentModel->getAll(['class_id' => $classId, 'academic_year_id' => $yearId]);

        // Attach marks & today's attendance to each student
        $today = date('Y-m-d');
        foreach ($students as &$s) {
            $marks          = $discModel->getMarks((int)$s['id']);
            $s['marks']     = (int)$marks['marks'];
            $s['mark_action'] = $discModel->getActionForMarks($s['marks']);
            $attSummary     = $attModel->getStudentSummary((int)$s['id'], $yearId);
            $s['att_rate']  = $attSummary['rate'];
            $s['att_absent']= $attSummary['absent'];
        }
        unset($s);

        $pageTitle = 'Class: ' . $class['class_name'] . ' Students';
        require_once APP . '/views/classes/students.php';
    }

    /** Show full detail of one student within a class context */
    public function studentDetail(): void {
        $studentId  = (int)($_GET['student_id'] ?? 0);
        $classId    = (int)($_GET['class_id']   ?? 0);
        $yearModel  = new AcademicYearModel();
        $activeYear = $yearModel->getActive();
        $yearId     = $activeYear ? (int)$activeYear['id'] : 0;

        if (!$studentId) { redirect('?page=classes'); return; }

        $studentModel = new StudentModel();
        $discModel    = new DisciplineModel();
        $attModel     = new AttendanceModel();

        $student    = $studentModel->getById($studentId);
        $class      = $classId ? $this->model->getById($classId) : null;
        $marks      = $discModel->getMarks($studentId);
        $discRecords= $discModel->getRecords($studentId);
        $markAction = $discModel->getActionForMarks((int)$marks['marks']);
        $attSummary = $attModel->getStudentSummary($studentId, $yearId);
        $attHistory = $attModel->getStudentHistory($studentId, $yearId);
        $thresholds = DisciplineModel::THRESHOLDS;

        $pageTitle = $student ? ucfirst($student['first_name']) . ' ' . ucfirst($student['last_name']) . ' — Profile' : 'Student Detail';
        require_once APP . '/views/classes/student_detail.php';
    }

    public function store(): void {
        $data = [
            'class_name'  => trim($_POST['class_name'] ?? ''),
            'level'       => trim($_POST['level'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ];
        if (empty($data['class_name']) || empty($data['level'])) {
            jsonResponse(['success' => false, 'message' => 'Class name and level are required.']);
            return;
        }
        $this->model->create($data);
        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'CREATE_CLASS', "Created class: {$data['class_name']}", 'class');
        jsonResponse(['success' => true, 'message' => 'Class created successfully.']);
    }

    public function update(): void {
        $id   = (int)($_POST['id'] ?? 0);
        $data = [
            'class_name'  => trim($_POST['class_name'] ?? ''),
            'level'       => trim($_POST['level'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
        ];
        if (!$id || empty($data['class_name'])) {
            jsonResponse(['success' => false, 'message' => 'Invalid data.']);
            return;
        }
        $this->model->update($id, $data);
        jsonResponse(['success' => true, 'message' => 'Class updated successfully.']);
    }

    public function delete(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { jsonResponse(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->delete($id);
        jsonResponse(['success' => true, 'message' => 'Class deleted.']);
    }

    public function getAll(): void {
        jsonResponse(['success' => true, 'data' => $this->model->getAll()]);
    }
}
