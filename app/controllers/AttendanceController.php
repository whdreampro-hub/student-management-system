<?php
class AttendanceController {
    private AttendanceModel  $model;
    private ClassModel       $classModel;
    private AcademicYearModel $yearModel;

    public function __construct() {
        $this->model      = new AttendanceModel();
        $this->classModel = new ClassModel();
        $this->yearModel  = new AcademicYearModel();
    }

    /** Show attendance sheet for a class */
    public function index(): void {
        $classId   = (int)($_GET['class_id'] ?? 0);
        $activeYear = $this->yearModel->getActive();
        $yearId    = $activeYear ? (int)$activeYear['id'] : 0;
        $date      = $_GET['date'] ?? date('Y-m-d');

        $class    = $classId ? $this->classModel->getById($classId) : null;
        $classes  = $this->classModel->getAll();
        $students = [];
        $dates    = [];

        if ($classId && $yearId) {
            $students = $this->model->getClassAttendanceOnDate($classId, $yearId, $date);
            $dates    = $this->model->getAttendanceDates($classId, $yearId);
        }

        $pageTitle = 'Attendance';
        require_once APP . '/views/attendance/index.php';
    }

    /** Save attendance for a class on a date */
    public function save(): void {
        $classId  = (int)($_POST['class_id']  ?? 0);
        $yearId   = (int)($_POST['year_id']   ?? 0);
        $date     = trim($_POST['date']        ?? '');
        $records  = $_POST['attendance']       ?? [];

        if (!$classId || !$yearId || !$date || empty($records)) {
            jsonResponse(['success' => false, 'message' => 'Missing attendance data.']);
            return;
        }

        $this->model->saveClassAttendance($classId, $yearId, $date, $_SESSION['admin_id'], $records);

        $log = new ActivityLogModel();
        $log->log($_SESSION['admin_id'], 'RECORD_ATTENDANCE',
            "Recorded attendance for class #{$classId} on {$date}", 'class', $classId);

        jsonResponse(['success' => true, 'message' => 'Attendance saved successfully.']);
    }

    /** AJAX: get attendance data for a class/date */
    public function getSheet(): void {
        $classId = (int)($_GET['class_id'] ?? 0);
        $yearId  = (int)($_GET['year_id']  ?? 0);
        $date    = $_GET['date'] ?? date('Y-m-d');
        $data    = $this->model->getClassAttendanceOnDate($classId, $yearId, $date);
        jsonResponse(['success' => true, 'data' => $data]);
    }
}
