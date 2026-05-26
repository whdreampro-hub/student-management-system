<?php
class Student {
    private $conn;
    private $table = "students";

    public $id;
    public $registration_number;
    public $first_name;
    public $last_name;
    public $gender;
    public $date_of_birth;
    public $photo;
    public $parent_name;
    public $parent_phone;
    public $guardian_name;
    public $guardian_phone;
    public $address;
    public $village;
    public $sector;
    public $district;
    public $email;
    public $nationality;
    public $admission_date;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table . "
                SET registration_number=:registration_number,
                    first_name=:first_name,
                    last_name=:last_name,
                    gender=:gender,
                    date_of_birth=:date_of_birth,
                    photo=:photo,
                    parent_name=:parent_name,
                    parent_phone=:parent_phone,
                    guardian_name=:guardian_name,
                    guardian_phone=:guardian_phone,
                    address=:address,
                    village=:village,
                    sector=:sector,
                    district=:district,
                    email=:email,
                    nationality=:nationality,
                    admission_date=:admission_date";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':registration_number', $this->registration_number);
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':date_of_birth', $this->date_of_birth);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':parent_name', $this->parent_name);
        $stmt->bindParam(':parent_phone', $this->parent_phone);
        $stmt->bindParam(':guardian_name', $this->guardian_name);
        $stmt->bindParam(':guardian_phone', $this->guardian_phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':village', $this->village);
        $stmt->bindParam(':sector', $this->sector);
        $stmt->bindParam(':district', $this->district);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':nationality', $this->nationality);
        $stmt->bindParam(':admission_date', $this->admission_date);

        if($stmt->execute()) {
            return $this->conn->lastInsertId();
        }
        return false;
    }

    public function readAll($limit = 10, $offset = 0, $search = '', $class_id = null) {
        $query = "SELECT s.*, 
                         sch.class_id, 
                         c.class_name,
                         ay.year_name as academic_year
                  FROM " . $this->table . " s
                  LEFT JOIN student_class_history sch ON s.id = sch.student_id AND sch.status = 'active'
                  LEFT JOIN classes c ON sch.class_id = c.id
                  LEFT JOIN academic_years ay ON sch.academic_year_id = ay.id
                  WHERE s.is_deleted = 0";
        
        if($search) {
            $query .= " AND (s.first_name LIKE :search 
                         OR s.last_name LIKE :search 
                         OR s.registration_number LIKE :search)";
        }
        
        if($class_id) {
            $query .= " AND sch.class_id = :class_id";
        }
        
        $query .= " ORDER BY s.created_at DESC LIMIT :limit OFFSET :offset";
        
        $stmt = $this->conn->prepare($query);
        
        if($search) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        if($class_id) {
            $stmt->bindParam(':class_id', $class_id);
        }
        
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        $stmt->execute();
        return $stmt;
    }

    public function getTotalCount($search = '', $class_id = null) {
        $query = "SELECT COUNT(*) as total FROM " . $this->table . " s
                  LEFT JOIN student_class_history sch ON s.id = sch.student_id AND sch.status = 'active'
                  WHERE s.is_deleted = 0";
        
        if($search) {
            $query .= " AND (s.first_name LIKE :search OR s.last_name LIKE :search OR s.registration_number LIKE :search)";
        }
        
        if($class_id) {
            $query .= " AND sch.class_id = :class_id";
        }
        
        $stmt = $this->conn->prepare($query);
        
        if($search) {
            $searchParam = "%{$search}%";
            $stmt->bindParam(':search', $searchParam);
        }
        
        if($class_id) {
            $stmt->bindParam(':class_id', $class_id);
        }
        
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['total'];
    }

    public function readOne() {
        $query = "SELECT * FROM " . $this->table . " WHERE id = :id AND is_deleted = 0 LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        $stmt->execute();
        
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($row) {
            $this->registration_number = $row['registration_number'];
            $this->first_name = $row['first_name'];
            $this->last_name = $row['last_name'];
            $this->gender = $row['gender'];
            $this->date_of_birth = $row['date_of_birth'];
            $this->photo = $row['photo'];
            $this->parent_name = $row['parent_name'];
            $this->parent_phone = $row['parent_phone'];
            $this->guardian_name = $row['guardian_name'];
            $this->guardian_phone = $row['guardian_phone'];
            $this->address = $row['address'];
            $this->village = $row['village'];
            $this->sector = $row['sector'];
            $this->district = $row['district'];
            $this->email = $row['email'];
            $this->nationality = $row['nationality'];
            $this->admission_date = $row['admission_date'];
            return true;
        }
        return false;
    }

    public function update() {
        $query = "UPDATE " . $this->table . "
                SET first_name = :first_name,
                    last_name = :last_name,
                    gender = :gender,
                    date_of_birth = :date_of_birth,
                    photo = :photo,
                    parent_name = :parent_name,
                    parent_phone = :parent_phone,
                    guardian_name = :guardian_name,
                    guardian_phone = :guardian_phone,
                    address = :address,
                    village = :village,
                    sector = :sector,
                    district = :district,
                    email = :email,
                    nationality = :nationality,
                    admission_date = :admission_date
                WHERE id = :id";

        $stmt = $this->conn->prepare($query);
        
        $stmt->bindParam(':first_name', $this->first_name);
        $stmt->bindParam(':last_name', $this->last_name);
        $stmt->bindParam(':gender', $this->gender);
        $stmt->bindParam(':date_of_birth', $this->date_of_birth);
        $stmt->bindParam(':photo', $this->photo);
        $stmt->bindParam(':parent_name', $this->parent_name);
        $stmt->bindParam(':parent_phone', $this->parent_phone);
        $stmt->bindParam(':guardian_name', $this->guardian_name);
        $stmt->bindParam(':guardian_phone', $this->guardian_phone);
        $stmt->bindParam(':address', $this->address);
        $stmt->bindParam(':village', $this->village);
        $stmt->bindParam(':sector', $this->sector);
        $stmt->bindParam(':district', $this->district);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':nationality', $this->nationality);
        $stmt->bindParam(':admission_date', $this->admission_date);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function softDelete() {
        $query = "UPDATE " . $this->table . " SET is_deleted = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $this->id);
        
        if($stmt->execute()) {
            // Also update student class history status
            $query2 = "UPDATE student_class_history SET status = 'completed' WHERE student_id = :id AND status = 'active'";
            $stmt2 = $this->conn->prepare($query2);
            $stmt2->bindParam(':id', $this->id);
            $stmt2->execute();
            return true;
        }
        return false;
    }

    public function generateRegistrationNumber() {
        $year = date('Y');
        $query = "SELECT COUNT(*) as count FROM " . $this->table . " WHERE registration_number LIKE :pattern";
        $stmt = $this->conn->prepare($query);
        $pattern = $year . '%';
        $stmt->bindParam(':pattern', $pattern);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $count = $row['count'] + 1;
        return $year . str_pad($count, 4, '0', STR_PAD_LEFT);
    }
}
?>