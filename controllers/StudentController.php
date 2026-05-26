<?php
require_once '../models/Student.php';
require_once '../models/StudentHistory.php';
require_once '../models/Class.php';
require_once '../models/AcademicYear.php';

class StudentController {
    private $db;
    private $student;
    private $history;
    private $class;
    private $academicYear;
    
    public function __construct($db) {
        $this->db = $db;
        $this->student = new Student($db);
        $this->history = new StudentHistory($db);
        $this->class = new ClassModel($db);
        $this->academicYear = new AcademicYear($db);
    }
    
    public function index() {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $limit = 10;
        $offset = ($page - 1) * $limit;
        $search = isset($_GET['search']) ? $_GET['search'] : '';
        $class_id = isset($_GET['class_id']) ? $_GET['class_id'] : null;
        
        $stmt = $this->student->readAll($limit, $offset, $search, $class_id);
        $total = $this->student->getTotalCount($search, $class_id);
        $totalPages = ceil($total / $limit);
        
        $classes = $this->class->readAll();
        
        require_once '../views/students/index.php';
    }
    
    public function create() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->student->registration_number = $this->student->generateRegistrationNumber();
            $this->student->first_name = $_POST['first_name'];
            $this->student->last_name = $_POST['last_name'];
            $this->student->gender = $_POST['gender'];
            $this->student->date_of_birth = $_POST['date_of_birth'];
            $this->student->parent_name = $_POST['parent_name'];
            $this->student->parent_phone = $_POST['parent_phone'];
            $this->student->guardian_name = $_POST['guardian_name'];
            $this->student->guardian_phone = $_POST['guardian_phone'];
            $this->student->address = $_POST['address'];
            $this->student->village = $_POST['village'];
            $this->student->sector = $_POST['sector'];
            $this->student->district = $_POST['district'];
            $this->student->email = $_POST['email'];
            $this->student->nationality = $_POST['nationality'];
            $this->student->admission_date = $_POST['admission_date'];
            $this->student->photo = 'default.png';
            
            // Handle photo upload
            if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['photo']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if(in_array($ext, $allowed)) {
                    $new_filename = uniqid() . '.' . $ext;
                    $upload_path = UPLOAD_PATH . $new_filename;
                    
                    if(move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                        $this->student->photo = $new_filename;
                    }
                }
            }
            
            $student_id = $this->student->create();
            
            if($student_id) {
                // Add to class history
                $active_year = $this->academicYear->getActiveYear();
                $this->history->addHistory($student_id, $_POST['class_id'], $active_year['id'], 'New Admission', $_POST['remarks']);
                
                $_SESSION['success'] = "Student registered successfully! Registration Number: " . $this->student->registration_number;
                header("Location: index.php?controller=student&action=index");
                exit();
            } else {
                $_SESSION['error'] = "Failed to register student.";
            }
        }
        
        $classes = $this->class->readAll();
        $academicYears = $this->academicYear->readAll();
        require_once '../views/students/create.php';
    }
    
    public function edit($id) {
        $this->student->id = $id;
        $this->student->readOne();
        
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->student->first_name = $_POST['first_name'];
            $this->student->last_name = $_POST['last_name'];
            $this->student->gender = $_POST['gender'];
            $this->student->date_of_birth = $_POST['date_of_birth'];
            $this->student->parent_name = $_POST['parent_name'];
            $this->student->parent_phone = $_POST['parent_phone'];
            $this->student->guardian_name = $_POST['guardian_name'];
            $this->student->guardian_phone = $_POST['guardian_phone'];
            $this->student->address = $_POST['address'];
            $this->student->village = $_POST['village'];
            $this->student->sector = $_POST['sector'];
            $this->student->district = $_POST['district'];
            $this->student->email = $_POST['email'];
            $this->student->nationality = $_POST['nationality'];
            $this->student->admission_date = $_POST['admission_date'];
            
            // Handle photo upload
            if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['photo']['name'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));
                
                if(in_array($ext, $allowed)) {
                    $new_filename = uniqid() . '.' . $ext;
                    $upload_path = UPLOAD_PATH . $new_filename;
                    
                    if(move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                        // Delete old photo if not default
                        if($this->student->photo != 'default.png' && file_exists(UPLOAD_PATH . $this->student->photo)) {
                            unlink(UPLOAD_PATH . $this->student->photo);
                        }
                        $this->student->photo = $new_filename;
                    }
                }
            }
            
            if($this->student->update()) {
                $_SESSION['success'] = "Student information updated successfully!";
                header("Location: index.php?controller=student&action=profile&id=" . $id);
                exit();
            } else {
                $_SESSION['error'] = "Failed to update student information.";
            }
        }
        
        $classes = $this->class->readAll();
        require_once '../views/students/edit.php';
    }

    public function history($id) {
    $this->student->id = $id;
    $this->student->readOne();
    
    $history_stmt = $this->history->getStudentHistory($id);
    $student = [
        'id' => $this->student->id,
        'first_name' => $this->student->first_name,
        'last_name' => $this->student->last_name
    ];
    
    require_once '../views/students/history.php';
}
    
    public function delete($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $this->student->id = $id;
            
            if($this->student->softDelete()) {
                $_SESSION['success'] = "Student deleted successfully!";
            } else {
                $_SESSION['error'] = "Failed to delete student.";
            }
            
            header("Location: index.php?controller=student&action=index");
            exit();
        }
    }
    
    public function profile($id) {
        $this->student->id = $id;
        $this->student->readOne();
        
        $current_class = $this->history->getCurrentClass($id);
        $history_stmt = $this->history->getStudentHistory($id);
        
        require_once '../views/students/profile.php';
    }
    
    public function promote($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_class_id = $_POST['new_class_id'];
            $academic_year_id = $_POST['academic_year_id'];
            
            if($this->history->promoteStudent($id, $new_class_id, $academic_year_id)) {
                $_SESSION['success'] = "Student promoted successfully!";
            } else {
                $_SESSION['error'] = "Failed to promote student.";
            }
            
            header("Location: index.php?controller=student&action=profile&id=" . $id);
            exit();
        }
        
        $current = $this->history->getCurrentClass($id);
        $classes = $this->class->readAll();
        $academicYears = $this->academicYear->readAll();
        
        require_once '../views/students/promote.php';
    }
    
    public function transfer($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $new_class_id = $_POST['new_class_id'];
            $academic_year_id = $_POST['academic_year_id'];
            $remarks = $_POST['remarks'];
            
            if($this->history->transferStudent($id, $new_class_id, $academic_year_id, $remarks)) {
                $_SESSION['success'] = "Student transferred successfully!";
            } else {
                $_SESSION['error'] = "Failed to transfer student.";
            }
            
            header("Location: index.php?controller=student&action=profile&id=" . $id);
            exit();
        }
        
        $current = $this->history->getCurrentClass($id);
        $classes = $this->class->readAll();
        $academicYears = $this->academicYear->readAll();
        
        require_once '../views/students/transfer.php';
    }
    
    public function search() {
        $search_term = $_GET['term'];
        $stmt = $this->student->readAll(100, 0, $search_term);
        $students = [];
        
        while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $students[] = $row;
        }
        
        header('Content-Type: application/json');
        echo json_encode($students);
    }
}


?>