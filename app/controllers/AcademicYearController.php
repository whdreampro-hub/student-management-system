<?php
class AcademicYearController {
    private AcademicYearModel $model;

    public function __construct() {
        $this->model = new AcademicYearModel();
    }

    public function index(): void {
        $years = $this->model->getAll();
        require_once APP . '/views/academic_years/index.php';
    }

    public function store(): void {
        $yearName = trim($_POST['year_name'] ?? '');
        if (empty($yearName)) {
            jsonResponse(['success' => false, 'message' => 'Year name is required.']);
            return;
        }
        $this->model->create(['year_name' => $yearName, 'status' => 'inactive']);
        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'CREATE_YEAR', "Created academic year: $yearName", 'academic_year');
        jsonResponse(['success' => true, 'message' => 'Academic year created.']);
    }

    public function update(): void {
        $id       = (int)($_POST['id'] ?? 0);
        $yearName = trim($_POST['year_name'] ?? '');
        $status   = $_POST['status'] ?? 'inactive';
        if (!$id || empty($yearName)) {
            jsonResponse(['success' => false, 'message' => 'Invalid data.']);
            return;
        }
        $this->model->update($id, ['year_name' => $yearName, 'status' => $status]);
        jsonResponse(['success' => true, 'message' => 'Academic year updated.']);
    }

    public function setActive(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { jsonResponse(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->setActive($id);
        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'SET_ACTIVE_YEAR', "Set academic year #$id as active", 'academic_year', $id);
        jsonResponse(['success' => true, 'message' => 'Academic year set as active.']);
    }

    public function delete(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { jsonResponse(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->delete($id);
        jsonResponse(['success' => true, 'message' => 'Academic year deleted.']);
    }

    public function getAll(): void {
        jsonResponse(['success' => true, 'data' => $this->model->getAll()]);
    }
}
