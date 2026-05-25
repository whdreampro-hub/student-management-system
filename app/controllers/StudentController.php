<?php
class StudentController {
    private $pdo;
    private $studentModel;
    private $classModel;
    private $academicYearModel;
    private $historyModel;

    private function getActiveHistoryRecord(int $studentId) {
        $stmt = $this->pdo->prepare(
            "SELECT sch.*
             FROM student_class_history sch
             WHERE sch.student_id = :student_id
               AND sch.status = 'active'
             ORDER BY sch.start_date DESC, sch.id DESC
             LIMIT 1"
        );
        $stmt->execute(['student_id' => $studentId]);
        return $stmt->fetch();
    }

    private function closeActiveHistoryAndCreateNew(
        int $studentId,
        int $newClassId,
        int $academicYearId,
        string $newStatus,
        string $reason,
        string $remarks = ''
    ) {
        $this->pdo->beginTransaction();

        try {
            // Invariant check: ensure at most 1 active row exists
            $countStmt = $this->pdo->prepare(
                "SELECT COUNT(*) AS cnt
                 FROM student_class_history
                 WHERE student_id = :student_id AND status = 'active'"
            );
            $countStmt->execute(['student_id' => $studentId]);
            $cnt = (int)($countStmt->fetch()['cnt'] ?? 0);
            if ($cnt !== 1) {
                throw new RuntimeException("Invariant violation: expected exactly 1 active history row, found {$cnt}.");
            }

            $currentRecord = $this->getActiveHistoryRecord($studentId);
            if (!$currentRecord) {
                throw new RuntimeException('No active class record found for student.');
            }

            // Close current record
            $updateStmt = $this->pdo->prepare(
                "UPDATE student_class_history
                 SET status = :status,
                     end_date = :end_date,
                     remarks = :remarks,
                     updated_at = CURRENT_TIMESTAMP
                 WHERE id = :id"
            );
            $updateStmt->execute([
                'status' => $newStatus,
                'end_date' => date('Y-m-d'),
                'remarks' => $remarks,
                'id' => $currentRecord['id'],
            ]);

            // Create new active record
            $insertStmt = $this->pdo->prepare(
                "INSERT INTO student_class_history
                    (student_id, class_id, academic_year_id, status, reason, start_date, end_date, remarks, created_at, updated_at)
                 VALUES
                    (:student_id, :class_id, :academic_year_id, 'active', :reason, :start_date, NULL, :remarks, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
            );
            $insertStmt->execute([
                'student_id' => $studentId,
                'class_id' => $newClassId,
                'academic_year_id' => $academicYearId,
                'reason' => $reason,
                'start_date' => date('Y-m-d'),
                'remarks' => $remarks,
            ]);

            $this->pdo->commit();
        } catch (Throwable $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }


    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->studentModel = new StudentModel($pdo);
        // We'll create these models later, but for now we'll use BaseModel
        $this->classModel = new BaseModel($pdo, 'classes');
        $this->academicYearModel = new BaseModel($pdo, 'academic_years');
        $this->historyModel = new BaseModel($pdo, 'student_class_history');
    }

    public function index() {
        // Handle search and filter
        $search = $_GET['search'] ?? '';
        $class_id = $_GET['class_id'] ?? '';
        $academic_year_id = $_GET['academic_year_id'] ?? '';

        // Get students with filters
        if (!empty($search)) {
            $students = $this->studentModel->searchStudents($search);
        } elseif (!empty($class_id) && !empty($academic_year_id)) {
            // We need to get students by class and academic year via history
            // This is more complex, so we'll do a custom query
            $stmt = $this->pdo->prepare("
                SELECT s.* 
                FROM students s
                JOIN student_class_history sch ON s.id = sch.student_id
                WHERE sch.class_id = :class_id 
                AND sch.academic_year_id = :academic_year_id
                AND sch.status = 'active'
            ");
            $stmt->execute([
                'class_id' => $class_id,
                'academic_year_id' => $academic_year_id
            ]);
            $students = $stmt->fetchAll();
        } else {
            // Get all students with pagination (for simplicity, we'll get all without pagination in this example)
            $students = $this->studentModel->findAll();
        }

        // Get classes and academic years for filters
        $classes = $this->classModel->findAll();
        $academic_years = $this->academicYearModel->findAll();

        // Load view
        require_once __DIR__ . '/../views/students/index.php';
    }

    public function create() {
        // Get classes and academic years for the form
        $classes = $this->classModel->findAll();
        $academic_years = $this->academicYearModel->findAll();

        require_once __DIR__ . '/../views/students/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input
            $data = [
                'registration_number' => $_POST['registration_number'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'date_of_birth' => $_POST['date_of_birth'] ?? null,
                'photo' => '', // We'll handle file upload separately
                'parent_name' => $_POST['parent_name'] ?? '',
                'parent_phone' => $_POST['parent_phone'] ?? '',
                'guardian_name' => $_POST['guardian_name'] ?? '',
                'guardian_phone' => $_POST['guardian_phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'village' => $_POST['village'] ?? '',
                'sector' => $_POST['sector'] ?? '',
                'district' => $_POST['district'] ?? '',
                'email' => $_POST['email'] ?? '',
                'nationality' => $_POST['nationality'] ?? '',
                'admission_date' => $_POST['admission_date'] ?? null,
            ];

            // Check if registration number already exists
            $existing = $this->studentModel->findByRegistrationNumber($data['registration_number']);
            if ($existing) {
                setFlash('error', 'Registration number already exists!');
                header('Location: /student-management/public/index.php?controller=student&action=create');
                exit;
            }

            // Handle photo upload
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/../../public/assets/images/';
                $fileName = basename($_FILES['photo']['name']);
                $targetFilePath = $uploadDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                // Allow certain file formats
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($fileType), $allowedTypes)) {
                    // Move the file
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                        $data['photo'] = 'assets/images/' . $fileName;
                    } else {
                        setFlash('error', 'Sorry, there was an error uploading your file.');
                        header('Location: /student-management/public/index.php?controller=student&action=create');
                        exit;
                    }
                } else {
                    setFlash('error', 'Only JPG, JPEG, PNG & GIF files are allowed.');
                    header('Location: /student-management/public/index.php?controller=student&action=create');
                    exit;
                }
            }

            // Create student
            if ($this->studentModel->create($data)) {
                $studentId = $this->pdo->lastInsertId();

                // Create initial history record for the student
                // We need to know the current active academic year and a default class (let's assume P1 for new students)
                $activeYear = $this->academicYearModel->findBy(['status' => 'active']);
                $activeYearId = $activeYear[0]['id'] ?? 1; // fallback to 1 if not found

                // Get the first class (P1) as default for new students
                $defaultClass = $this->classModel->findBy(['class_name' => 'P1']);
                $defaultClassId = $defaultClass[0]['id'] ?? 1; // fallback to 1 if not found

                $historyData = [
                    'student_id' => $studentId,
                    'class_id' => $defaultClassId,
                    'academic_year_id' => $activeYearId,
                    'status' => 'active',
                    'reason' => 'New Admission',
                    'start_date' => date('Y-m-d'),
                    'end_date' => null,
                    'remarks' => 'Initial admission'
                ];

                $this->historyModel->create($historyData);

                setFlash('success', 'Student added successfully!');
                header('Location: /student-management/public/index.php?controller=student&action=index');
                exit;
            } else {
                setFlash('error', 'Failed to add student!');
                header('Location: /student-management/public/index.php?controller=student&action=create');
                exit;
            }
        } else {
            header('Location: /student-management/public/index.php?controller=student&action=create');
            exit;
        }
    }

    public function edit($id) {
        // Get student by id
        $student = $this->studentModel->findById($id);
        if (!$student) {
            setFlash('error', 'Student not found!');
            header('Location: /student-management/public/index.php?controller=student&action=index');
            exit;
        }

        // Get classes and academic years for the form
        $classes = $this->classModel->findAll();
        $academic_years = $this->academicYearModel->findAll();

        require_once __DIR__ . '/../views/students/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validate and sanitize input
            $data = [
                'registration_number' => $_POST['registration_number'] ?? '',
                'first_name' => $_POST['first_name'] ?? '',
                'last_name' => $_POST['last_name'] ?? '',
                'gender' => $_POST['gender'] ?? '',
                'date_of_birth' => $_POST['date_of_birth'] ?? null,
                // Note: photo is handled separately
                'parent_name' => $_POST['parent_name'] ?? '',
                'parent_phone' => $_POST['parent_phone'] ?? '',
                'guardian_name' => $_POST['guardian_name'] ?? '',
                'guardian_phone' => $_POST['guardian_phone'] ?? '',
                'address' => $_POST['address'] ?? '',
                'village' => $_POST['village'] ?? '',
                'sector' => $_POST['sector'] ?? '',
                'district' => $_POST['district'] ?? '',
                'email' => $_POST['email'] ?? '',
                'nationality' => $_POST['nationality'] ?? '',
                'admission_date' => $_POST['admission_date'] ?? null,
            ];

            // Check if registration number already exists for another student
            $existing = $this->studentModel->findByRegistrationNumber($data['registration_number']);
            if ($existing && $existing['id'] != $id) {
                setFlash('error', 'Registration number already exists!');
                header('Location: /student-management/public/index.php?controller=student&action=edit&id=' . $id);
                exit;
            }

            // Handle photo upload if a new photo is provided
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
                // Delete old photo if exists
                $oldStudent = $this->studentModel->findById($id);
                if ($oldStudent && $oldStudent['photo']) {
                    $oldPhotoPath = __DIR__ . '/../../public/' . $oldStudent['photo'];
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }

                $uploadDir = __DIR__ . '/../../public/assets/images/';
                $fileName = basename($_FILES['photo']['name']);
                $targetFilePath = $uploadDir . $fileName;
                $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

                // Allow certain file formats
                $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
                if (in_array(strtolower($fileType), $allowedTypes)) {
                    // Move the file
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFilePath)) {
                        $data['photo'] = 'assets/images/' . $fileName;
                    } else {
                        setFlash('error', 'Sorry, there was an error uploading your file.');
                        header('Location: /student-management/public/index.php?controller=student&action=edit&id=' . $id);
                        exit;
                    }
                } else {
                    setFlash('error', 'Only JPG, JPEG, PNG & GIF files are allowed.');
                    header('Location: /student-management/public/index.php?controller=student&action=edit&id=' . $id);
                    exit;
                }
            } else {
                // Keep the existing photo
                $existingStudent = $this->studentModel->findById($id);
                $data['photo'] = $existingStudent['photo'];
            }

            // Update student
            if ($this->studentModel->update($id, $data)) {
                setFlash('success', 'Student updated successfully!');
                header('Location: /student-management/public/index.php?controller=student&action=index');
                exit;
            } else {
                setFlash('error', 'Failed to update student!');
                header('Location: /student-management/public/index.php?controller=student&action=edit&id=' . $id);
                exit;
            }
        } else {
            header('Location: /student-management/public/index.php?controller=student&action=edit&id=' . $id);
            exit;
        }
    }

    public function delete($id) {
        // For safety, we'll use a POST request for deletion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // First, get the student to check if exists
            $student = $this->studentModel->findById($id);
            if (!$student) {
                setFlash('error', 'Student not found!');
                header('Location: /student-management/public/index.php?controller=student&action=index');
                exit;
            }

            // Delete student (this will cascade delete history due to foreign key)
            if ($this->studentModel->delete($id)) {
                setFlash('success', 'Student deleted successfully!');
            } else {
                setFlash('error', 'Failed to delete student!');
            }

            header('Location: /student-management/public/index.php?controller=student&action=index');
            exit;
        } else {
            // If not POST, redirect to index (or show error)
            header('Location: /student-management/public/index.php?controller=student&action=index');
            exit;
        }
    }

    public function view($id) {
        $student = $this->studentModel->findById($id);
        if (!$student) {
            setFlash('error', 'Student not found!');
            header('Location: /student-management/public/index.php?controller=student&action=index');
            exit;
        }

        // Get student's current class and academic year from history
        $currentHistory = $this->pdo->prepare("
            SELECT sch.*, c.class_name, ay.year_name 
            FROM student_class_history sch
            JOIN classes c ON sch.class_id = c.id
            JOIN academic_years ay ON sch.academic_year_id = ay.id
            WHERE sch.student_id = :student_id
            AND sch.status = 'active'
        ");
        $currentHistory->execute(['student_id' => $id]);
        $currentHistoryRecord = $currentHistory->fetch();

        // Get full history
        $history = $this->pdo->prepare("
            SELECT sch.*, c.class_name, ay.year_name 
            FROM student_class_history sch
            JOIN classes c ON sch.class_id = c.id
            JOIN academic_years ay ON sch.academic_year_id = ay.id
            WHERE sch.student_id = :student_id
            ORDER BY sch.start_date DESC
        ");
        $history->execute(['student_id' => $id]);
        $historyRecords = $history->fetchAll();

        require_once __DIR__ . '/../views/students/view.php';
    }

    // Method to promote a student
    public function promote($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_class_id = (int)($_POST['new_class_id'] ?? 0);
            $reason = $_POST['reason'] ?? 'Promotion';
            $remarks = $_POST['remarks'] ?? '';


            if (empty($new_class_id)) {
                setFlash('error', 'Please select a class to promote to!');
                header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
                exit;
            }

            // Get student
            $student = $this->studentModel->findById($id);
            if (!$student) {
                setFlash('error', 'Student not found!');
                header('Location: /student-management/public/index.php?controller=student&action=index');
                exit;
            }

            // Get current active academic year
            $activeYear = $this->academicYearModel->findBy(['status' => 'active']);
            $activeYearId = $activeYear[0]['id'] ?? null;
            if (!$activeYearId) {
                setFlash('error', 'No active academic year found!');
                header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
                exit;
            }

            // Get current active history record for the student
            $currentHistory = $this->pdo->prepare("
                SELECT * FROM student_class_history 
                WHERE student_id = :student_id AND status = 'active'
            ");
            $currentHistory->execute(['student_id' => $id]);
            $currentRecord = $currentHistory->fetch();

            if (!$currentRecord) {
                setFlash('error', 'No active class record found for student!');
                header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
                exit;
            }

            try {
                $this->closeActiveHistoryAndCreateNew(
                    (int)$id,
                    (int)$new_class_id,
                    (int)$activeYearId,
                    'promoted',
                    $reason,
                    $remarks
                );
            } catch (Throwable $e) {
                setFlash('error', $e->getMessage());
                header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
                exit;
            }

            setFlash('success', 'Student promoted successfully!');

            header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
            exit;
        } else {
            // If not POST, redirect to view
            header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
            exit;
        }
    }

    // Method to transfer a student
    public function transfer($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $new_class_id = $_POST['new_class_id'] ?? '';
            $new_academic_year_id = $_POST['new_academic_year_id'] ?? '';
            $reason = $_POST['reason'] ?? 'Transfer';
            $remarks = $_POST['remarks'] ?? '';

            if (empty($new_class_id) || empty($new_academic_year_id)) {
                setFlash('error', 'Please select both class and academic year!');
                header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
                exit;
            }

            // Get student
            $student = $this->studentModel->findById($id);
            if (!$student) {
                setFlash('error', 'Student not found!');
                header('Location: /student-management/public/index.php?controller=student&action=index');
                exit;
            }

            try {
                $this->closeActiveHistoryAndCreateNew(
                    (int)$id,
                    (int)$new_class_id,
                    (int)$new_academic_year_id,
                    'transferred',
                    $reason,
                    $remarks
                );
            } catch (Throwable $e) {
                setFlash('error', $e->getMessage());
                header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
                exit;
            }

            setFlash('success', 'Student transferred successfully!');

            header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
            exit;
        } else {
            // If not POST, redirect to view
            header('Location: /student-management/public/index.php?controller=student&action=view&id=' . $id);
            exit;
        }
    }
}