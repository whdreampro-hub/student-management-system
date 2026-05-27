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
