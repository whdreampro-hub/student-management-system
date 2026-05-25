<?php
class ClassController {
    private $pdo;
    private $classModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->classModel = new BaseModel($pdo, 'classes');
    }

    public function index() {
        $classes = $this->classModel->findAll();
        require_once __DIR__ . '/../views/classes/index.php';
    }

    public function create() {
        require_once __DIR__ . '/../views/classes/create.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'class_name' => $_POST['class_name'] ?? '',
                'level' => $_POST['level'] ?? '',
            ];

            if ($this->classModel->create($data)) {
                setFlash('success', 'Class added successfully!');
                header('Location: /student-management/public/index.php?controller=class&action=index');
                exit;
            } else {
                setFlash('error', 'Failed to add class!');
                header('Location: /student-management/public/index.php?controller=class&action=create');
                exit;
            }
        } else {
            header('Location: /student-management/public/index.php?controller=class&action=create');
            exit;
        }
    }

    public function edit($id) {
        $class = $this->classModel->findById($id);
        if (!$class) {
            setFlash('error', 'Class not found!');
            header('Location: /student-management/public/index.php?controller=class&action=index');
            exit;
        }
        require_once __DIR__ . '/../views/classes/edit.php';
    }

    public function update($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'class_name' => $_POST['class_name'] ?? '',
                'level' => $_POST['level'] ?? '',
            ];

            if ($this->classModel->update($id, $data)) {
                setFlash('success', 'Class updated successfully!');
                header('Location: /student-management/public/index.php?controller=class&action=index');
                exit;
            } else {
                setFlash('error', 'Failed to update class!');
                header('Location: /student-management/public/index.php?controller=class&action=edit&id=' . $id);
                exit;
            }
        } else {
            header('Location: /student-management/public/index.php?controller=class&action=edit&id=' . $id);
            exit;
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($this->classModel->delete($id)) {
                setFlash('success', 'Class deleted successfully!');
            } else {
                setFlash('error', 'Failed to delete class!');
            }

            header('Location: /student-management/public/index.php?controller=class&action=index');
            exit;
        } else {
            header('Location: /student-management/public/index.php?controller=class&action=index');
            exit;
        }
    }
}