<?php
require_once '../models/Class.php';

class ClassController {
    private $db;
    private $class;

    public function __construct($db) {
        $this->db = $db;
        $this->class = new ClassModel($db);
    }

    public function manage() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            if(isset($_POST['action'])) {
                switch($_POST['action']) {
                    case 'add':
                        $this->class->create($_POST['class_name'], $_POST['level'], $_POST['capacity']);
                        $_SESSION['success'] = "Class added successfully!";
                        break;
                    case 'edit':
                        $this->class->update($_POST['id'], $_POST['class_name'], $_POST['level'], $_POST['capacity']);
                        $_SESSION['success'] = "Class updated successfully!";
                        break;
                    case 'delete':
                        $this->class->delete($_POST['id']);
                        $_SESSION['success'] = "Class deleted successfully!";
                        break;
                }
                header("Location: index.php?controller=class&action=manage");
                exit();
            }
        }
        
        $classes = $this->class->readAll();
        require_once '../views/classes/manage.php';
    }
}
?>