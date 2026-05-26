<?php
require_once '../models/AcademicYear.php';

class AcademicYearController {
    private $db;
    private $academicYear;

    public function __construct($db) {
        $this->db = $db;
        $this->academicYear = new AcademicYear($db);
    }

    public function manage() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['action'])) {
                switch($_POST['action']) {
                    case 'add':
                        $this->academicYear->create($_POST['year_name'], $_POST['start_date'], $_POST['end_date']);
                        $_SESSION['success'] = "Academic year added successfully!";
                        break;
                    case 'set_active':
                        $this->academicYear->setActive($_POST['id']);
                        $_SESSION['success'] = "Academic year set as active!";
                        break;
                    case 'delete':
                        $this->academicYear->delete($_POST['id']);
                        $_SESSION['success'] = "Academic year deleted successfully!";
                        break;
                }
                header("Location: index.php?controller=academic_year&action=manage");
                exit();
            }
        }
        
        $academicYears = $this->academicYear->readAll();
        $activeYear = $this->academicYear->getActiveYear();
        require_once '../views/academic_years/manage.php';
    }
}
?>