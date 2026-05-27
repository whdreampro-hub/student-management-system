<?php
class DashboardController {
    private StudentModel $studentModel;
    private HistoryModel $historyModel;
    private ClassModel $classModel;
    private AcademicYearModel $yearModel;
    private ActivityLogModel $logModel;

    public function __construct() {
        $this->studentModel = new StudentModel();
        $this->historyModel = new HistoryModel();
        $this->classModel   = new ClassModel();
        $this->yearModel    = new AcademicYearModel();
        $this->logModel     = new ActivityLogModel();
    }

    public function index(): void {
        $activeYear       = $this->yearModel->getActive();
        $yearId           = $activeYear ? $activeYear['id'] : 0;

        $totalStudents    = $this->studentModel->countAll();
        $genderStats      = $this->studentModel->countByGender();
        $classCounts      = $this->historyModel->countByClass($yearId);
        $recentAdmissions = $this->studentModel->recentAdmissions(6);
        $recentMovements  = $this->historyModel->getRecentTransfers(6);
        $totalTransfers   = $this->historyModel->countTransfers($yearId);
        $totalPromotions  = $this->historyModel->countPromotions($yearId);
        $recentLogs       = $this->logModel->getRecent(8);
        $allYears         = $this->yearModel->getAll();

        require_once APP . '/views/dashboard/index.php';
    }
}
