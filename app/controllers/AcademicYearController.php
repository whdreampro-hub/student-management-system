<?php
class AcademicYearController {
    private $pdo;
    private $academicYearModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->academicYearModel = new BaseModel($pdo, 'academic_years');
    }

    public function index() {
        $academic_years = $this->academicYearModel->findAll();
        require_once __DIR__ . '/../views/academic_years/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../views/academic_years/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'year_name' => $_POST['year_name'] ?? '',
                'status' => $_POST['status'] ?? 'inactive',
            ];

            // Ensure only one active year
            if ($data['status'] === 'active') {
                // Set all other years to inactive
                $this->pdo->prepare("UPDATE academic_years SET status = 'inactive'")->execute();
            }

            if ($this->academicYearModel->create($data)) {
                setFlash('success', 'Academic year added successfully!');
                header('Location: /student-management/public/index.php?controller=academic_year&action=index');
                exit;
            } else {
                setFlash('error', 'Failed to add academic year!');
                header('Location: /student-management/public/index.php?controller=academic_year&action=create');
                exit;
            }
        } else {
            header('Location: /student-management/public/index.php?controller=academic_year&action=create');
            exit;
        }
    }

    public function edit($id) {
        $academic_year = $this->academicYearModel->findById($id);
        if (!$academic_year) {
            setFlash('error', 'Academic year not found!');
            header('Location: /student-management/public/index.php?controller=academic_year&action=index');
            exit;
        }
        require_once __DIR__ . '/../views/academic_years/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'year_name' => $_POST['year_name'] ?? '',
                'status' => $_POST['status'] ?? 'inactive',
            ];

            // Ensure only one active year
            if ($data['status'] === 'active') {
                // Set all other years to inactive
                $this->pdo->prepare("UPDATE academic_years SET status = 'inactive' WHERE id != :id")
                    ->execute(['id' => $id]);
            }

            if ($this->academicYearModel->update($id, $data)) {
                setFlash('success', 'Academic year updated successfully!');
                header('Location: /student-management/public/index.php?controller=academic_year&action=index');
                exit;
            } else {
                setFlash('error', 'Failed to update academic year!');
                header('Location: /student-management/public/index.php?controller=academic_year&action=edit&id=' . $id);
                exit;
            }
        } else {
            header('Location: /student-management/public/index.php?controller=academic_year&action=edit&id=' . $id);
            exit;
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Prevent deletion of active academic year
            $academic_year = $this->academicYearModel->findById($id);
            if ($academic_year && $academic_year['status'] === 'active') {
                setFlash('error', 'Cannot delete the active academic year!');
                header('Location: /student-management/public/index.php?controller=academic_year&action=index');
                exit;
            }

            if ($this->academicYearModel->delete($id)) {
                setFlash('success', 'Academic year deleted successfully!');
            } else {
                setFlash('error', 'Failed to delete academic year!');
            }

            header('Location: /student-management/public/index.php?controller=academic_year&action=index');
            exit;
        } else {
            header('Location: /student-management/public/index.php?controller=academic_year&action=index');
            exit;
        }
    }
}