<?php
class StudentController {
    private StudentModel $studentModel;
    private HistoryModel $historyModel;
    private ClassModel $classModel;
    private AcademicYearModel $yearModel;

    public function __construct() {
        $this->studentModel = new StudentModel();
        $this->historyModel = new HistoryModel();
        $this->classModel   = new ClassModel();
        $this->yearModel    = new AcademicYearModel();
    }

    public function index(): void {
        $filters = [
            'search'           => $_GET['search'] ?? '',
            'class_id'         => (int)($_GET['class_id'] ?? 0),
            'academic_year_id' => (int)($_GET['academic_year_id'] ?? 0),
            'gender'           => $_GET['gender'] ?? '',
        ];
        $students   = $this->studentModel->getAll($filters);
        $classes    = $this->classModel->getAll();
        $years      = $this->yearModel->getAll();
        $activeYear = $this->yearModel->getActive();
        require_once APP . '/views/students/index.php';
    }

    public function create(): void {
        $classes    = $this->classModel->getAll();
        $years      = $this->yearModel->getAll();
        $activeYear = $this->yearModel->getActive();
        require_once APP . '/views/students/create.php';
    }

    public function store(): void {
        $data = $this->sanitizeInput($_POST);

        // Validate required fields
        $required = ['first_name', 'last_name', 'gender', 'date_of_birth', 'admission_date'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                jsonResponse(['success' => false, 'message' => ucfirst(str_replace('_', ' ', $field)) . ' is required.']);
                return;
            }
        }

        // Handle photo upload
        $data['photo'] = $this->handlePhotoUpload();
        $data['registration_number'] = $this->studentModel->generateRegNumber();

        $studentId = $this->studentModel->create($data);

        // Enroll in class if provided
        $classId = (int)($_POST['class_id'] ?? 0);
        $yearId  = (int)($_POST['academic_year_id'] ?? 0);
        if ($classId && $yearId) {
            $this->historyModel->addEntry([
                'student_id'       => $studentId,
                'class_id'         => $classId,
                'academic_year_id' => $yearId,
                'status'           => 'active',
                'reason'           => 'New Admission',
                'start_date'       => $data['admission_date'],
                'remarks'          => $_POST['remarks'] ?? ''
            ]);
        }

        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'CREATE_STUDENT',
            "Registered student: {$data['first_name']} {$data['last_name']} ({$data['registration_number']})",
            'student', $studentId);

        jsonResponse(['success' => true, 'message' => 'Student registered successfully.', 'id' => $studentId]);
    }

    public function edit(): void {
        $id = (int)($_GET['id'] ?? 0);
        $student    = $this->studentModel->getById($id);
        if (!$student) { redirect('?page=students'); return; }
        $classes    = $this->classModel->getAll();
        $years      = $this->yearModel->getAll();
        $activeYear = $this->yearModel->getActive();
        require_once APP . '/views/students/edit.php';
    }

    public function update(): void {
        $id   = (int)($_POST['id'] ?? 0);
        $data = $this->sanitizeInput($_POST);

        $existing = $this->studentModel->getById($id);
        if (!$existing) { jsonResponse(['success' => false, 'message' => 'Student not found.']); return; }

        $photo = $this->handlePhotoUpload();
        if ($photo) $data['photo'] = $photo;

        $this->studentModel->update($id, $data);

        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'UPDATE_STUDENT',
            "Updated student: {$data['first_name']} {$data['last_name']}", 'student', $id);

        jsonResponse(['success' => true, 'message' => 'Student updated successfully.']);
    }

    public function view(): void {
        $id      = (int)($_GET['id'] ?? 0);
        $student = $this->studentModel->getById($id);
        if (!$student) { redirect('?page=students'); return; }
        $history = $this->historyModel->getStudentHistory($id);
        require_once APP . '/views/students/view.php';
    }

    public function delete(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { jsonResponse(['success' => false, 'message' => 'Invalid ID.']); return; }
        $student = $this->studentModel->getById($id);
        $this->studentModel->softDelete($id);
        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'DELETE_STUDENT',
            "Deleted student: {$student['first_name']} {$student['last_name']}", 'student', $id);
        jsonResponse(['success' => true, 'message' => 'Student deleted successfully.']);
    }

    public function restore(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { jsonResponse(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->studentModel->restore($id);
        jsonResponse(['success' => true, 'message' => 'Student restored successfully.']);
    }

    public function trash(): void {
        $students = $this->studentModel->getTrashed();
        require_once APP . '/views/students/trash.php';
    }

    private function sanitizeInput(array $post): array {
        $fields = ['first_name','last_name','gender','date_of_birth','parent_name','parent_phone',
                   'guardian_name','guardian_phone','address','village','sector','district',
                   'email','nationality','admission_date'];
        $data = [];
        foreach ($fields as $f) {
            $data[$f] = htmlspecialchars(trim($post[$f] ?? ''), ENT_QUOTES, 'UTF-8');
        }
        return $data;
    }

    private function handlePhotoUpload(): string {
        if (!isset($_FILES['photo']) || $_FILES['photo']['error'] !== UPLOAD_ERR_OK) return '';
        $allowed   = ['image/jpeg','image/png','image/jpg','image/webp'];
        $mimeType  = mime_content_type($_FILES['photo']['tmp_name']);
        if (!in_array($mimeType, $allowed)) return '';
        $ext      = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
        $fileName = 'photo_' . uniqid() . '.' . $ext;
        $dest     = UPLOADS . '/' . $fileName;
        move_uploaded_file($_FILES['photo']['tmp_name'], $dest);
        return $fileName;
    }
}
