<?php
class DisciplineController {
    private DisciplineModel $model;
    private StudentModel    $studentModel;

    public function __construct() {
        $this->model        = new DisciplineModel();
        $this->studentModel = new StudentModel();
    }

    /** Global discipline overview */
    public function index(): void {
        $records  = $this->model->getRecentRecords(30);
        $critical = $this->model->getCriticalStudents();
        $stats    = $this->model->getStats();
        $pageTitle = 'Discipline Management';
        require_once APP . '/views/discipline/index.php';
    }

    /** Deduct marks from a student */
    public function deduct(): void {
        $studentId   = (int)($_POST['student_id']   ?? 0);
        $marksRemoved= (int)($_POST['marks_removed'] ?? 0);
        $reason      = trim($_POST['reason']        ?? '');
        $removedBy   = trim($_POST['removed_by']    ?? '');
        $incidentDate= trim($_POST['incident_date'] ?? date('Y-m-d'));

        if (!$studentId || $marksRemoved < 1 || empty($reason) || empty($removedBy)) {
            jsonResponse(['success' => false, 'message' => 'All fields are required.']);
            return;
        }

        $result = $this->model->deduct(
            $studentId, $marksRemoved, $reason, $removedBy,
            $_SESSION['admin_id'], $incidentDate
        );

        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'DISCIPLINE_DEDUCT',
            "Removed {$marksRemoved} marks from student #{$studentId}: {$reason}", 'student', $studentId);

        $msg = "Marks updated. Current balance: {$result['marks_after']}/40.";
        if ($result['action']) {
            $msg .= " ⚠️ Action triggered: {$result['action']['label']}";
        }

        jsonResponse(['success' => true, 'message' => $msg, 'result' => $result]);
    }

    /** Restore marks */
    public function restore(): void {
        $studentId  = (int)($_POST['student_id']  ?? 0);
        $marksAdded = (int)($_POST['marks_added'] ?? 0);
        $reason     = trim($_POST['reason']       ?? '');
        $addedBy    = trim($_POST['added_by']     ?? '');
        $date       = trim($_POST['incident_date']?? date('Y-m-d'));

        if (!$studentId || $marksAdded < 1 || empty($reason)) {
            jsonResponse(['success' => false, 'message' => 'All fields are required.']);
            return;
        }
        $this->model->restore($studentId, $marksAdded, $reason, $addedBy, $_SESSION['admin_id'], $date);
        jsonResponse(['success' => true, 'message' => "Marks restored successfully."]);
    }

    /** Get records for one student (AJAX) */
    public function getStudentRecords(): void {
        $studentId = (int)($_GET['student_id'] ?? 0);
        $marks     = $this->model->getMarks($studentId);
        $records   = $this->model->getRecords($studentId);
        $action    = $this->model->getActionForMarks((int)$marks['marks']);
        jsonResponse(['success' => true, 'marks' => $marks, 'records' => $records, 'action' => $action]);
    }

    /** Delete a record */
    public function deleteRecord(): void {
        $id = (int)($_POST['id'] ?? 0);
        if (!$id) { jsonResponse(['success' => false, 'message' => 'Invalid ID.']); return; }
        $this->model->deleteRecord($id);
        jsonResponse(['success' => true, 'message' => 'Record deleted and marks restored.']);
    }
}
