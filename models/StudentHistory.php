<?php
class StudentHistory {
    private $conn;
    
    public function __construct($db) {
        $this->conn = $db;
    }
    
    public function addHistory($student_id, $class_id, $academic_year_id, $reason, $remarks = null) {
        $query = "INSERT INTO student_class_history 
                  SET student_id = :student_id,
                      class_id = :class_id,
                      academic_year_id = :academic_year_id,
                      reason = :reason,
                      status = 'active',
                      start_date = :start_date,
                      remarks = :remarks";
        
        $stmt = $this->conn->prepare($query);
        
        $start_date = date('Y-m-d');
        
        $stmt->bindParam(':student_id', $student_id);
        $stmt->bindParam(':class_id', $class_id);
        $stmt->bindParam(':academic_year_id', $academic_year_id);
        $stmt->bindParam(':reason', $reason);
        $stmt->bindParam(':start_date', $start_date);
        $stmt->bindParam(':remarks', $remarks);
        
        return $stmt->execute();
    }
    
    public function closeCurrentHistory($student_id, $new_status, $end_date = null) {
        $query = "UPDATE student_class_history 
                  SET status = :status, end_date = :end_date 
                  WHERE student_id = :student_id AND status = 'active'";
        
        $stmt = $this->conn->prepare($query);
        
        if(!$end_date) {
            $end_date = date('Y-m-d');
        }
        
        $stmt->bindParam(':status', $new_status);
        $stmt->bindParam(':end_date', $end_date);
        $stmt->bindParam(':student_id', $student_id);
        
        return $stmt->execute();
    }
    
    public function getStudentHistory($student_id) {
        $query = "SELECT sch.*, c.class_name, ay.year_name 
                  FROM student_class_history sch
                  JOIN classes c ON sch.class_id = c.id
                  JOIN academic_years ay ON sch.academic_year_id = ay.id
                  WHERE sch.student_id = :student_id
                  ORDER BY sch.start_date DESC";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt;
    }
    
    public function getCurrentClass($student_id) {
        $query = "SELECT c.id, c.class_name, ay.id as academic_year_id, ay.year_name
                  FROM student_class_history sch
                  JOIN classes c ON sch.class_id = c.id
                  JOIN academic_years ay ON sch.academic_year_id = ay.id
                  WHERE sch.student_id = :student_id AND sch.status = 'active'
                  LIMIT 1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':student_id', $student_id);
        $stmt->execute();
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function promoteStudent($student_id, $new_class_id, $academic_year_id) {
        // Close current history with 'promoted' status
        if($this->closeCurrentHistory($student_id, 'promoted')) {
            // Add new history entry
            return $this->addHistory($student_id, $new_class_id, $academic_year_id, 'Promotion', 'Student promoted to next class');
        }
        return false;
    }
    
    public function transferStudent($student_id, $new_class_id, $academic_year_id, $remarks = null) {
        // Close current history with 'transferred' status
        if($this->closeCurrentHistory($student_id, 'transferred')) {
            // Add new history entry
            return $this->addHistory($student_id, $new_class_id, $academic_year_id, 'Transfer', $remarks);
        }
        return false;
    }
    
    public function getClassStatistics($class_id = null) {
        $query = "SELECT c.class_name, COUNT(sch.student_id) as student_count
                  FROM classes c
                  LEFT JOIN student_class_history sch ON c.id = sch.class_id AND sch.status = 'active'
                  GROUP BY c.id";
        
        if($class_id) {
            $query .= " HAVING c.id = :class_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if($class_id) {
            $stmt->bindParam(':class_id', $class_id);
        }
        
        $stmt->execute();
        return $stmt;
    }
}
?>