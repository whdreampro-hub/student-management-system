<?php
class StudentModel extends Model {

    public function generateRegNumber(): string {
        $year = date('Y');
        $row  = $this->fetchOne("SELECT COUNT(*) as cnt FROM students WHERE YEAR(created_at) = ?", [$year]);
        $seq  = ($row ? (int)$row['cnt'] : 0) + 1;
        return 'STU-' . $year . '-' . str_pad($seq, 4, '0', STR_PAD_LEFT);
    }

    public function create(array $data): int {
        $this->query(
            "INSERT INTO students 
             (registration_number, first_name, last_name, gender, date_of_birth, photo,
              parent_name, parent_phone, guardian_name, guardian_phone,
              address, village, sector, district, email, nationality, admission_date)
             VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)",
            [
                $data['registration_number'], $data['first_name'], $data['last_name'],
                $data['gender'], $data['date_of_birth'], $data['photo'] ?? null,
                $data['parent_name'] ?? null, $data['parent_phone'] ?? null,
                $data['guardian_name'] ?? null, $data['guardian_phone'] ?? null,
                $data['address'] ?? null, $data['village'] ?? null,
                $data['sector'] ?? null, $data['district'] ?? null,
                $data['email'] ?? null, $data['nationality'] ?? 'Rwandan',
                $data['admission_date']
            ]
        );
        return (int)$this->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $photoSql = !empty($data['photo']) ? ', photo = ?' : '';
        $params = [
            $data['first_name'], $data['last_name'], $data['gender'],
            $data['date_of_birth'], $data['parent_name'] ?? null, $data['parent_phone'] ?? null,
            $data['guardian_name'] ?? null, $data['guardian_phone'] ?? null,
            $data['address'] ?? null, $data['village'] ?? null,
            $data['sector'] ?? null, $data['district'] ?? null,
            $data['email'] ?? null, $data['nationality'] ?? 'Rwandan',
        ];
        if (!empty($data['photo'])) $params[] = $data['photo'];
        $params[] = $id;
        $this->query(
            "UPDATE students SET first_name=?, last_name=?, gender=?, date_of_birth=?,
             parent_name=?, parent_phone=?, guardian_name=?, guardian_phone=?,
             address=?, village=?, sector=?, district=?, email=?, nationality=?
             $photoSql WHERE id = ? AND deleted_at IS NULL",
            $params
        );
        return true;
    }

    public function softDelete(int $id): bool {
        $this->query("UPDATE students SET deleted_at = NOW() WHERE id = ?", [$id]);
        return true;
    }

    public function restore(int $id): bool {
        $this->query("UPDATE students SET deleted_at = NULL WHERE id = ?", [$id]);
        return true;
    }

    public function getById(int $id): array|false {
        return $this->fetchOne(
            "SELECT s.*,
                h.class_id, h.academic_year_id, h.status as enrollment_status,
                c.class_name, c.level,
                ay.year_name
             FROM students s
             LEFT JOIN student_class_history h ON h.student_id = s.id AND h.status = 'active'
             LEFT JOIN classes c ON c.id = h.class_id
             LEFT JOIN academic_years ay ON ay.id = h.academic_year_id
             WHERE s.id = ?",
            [$id]
        );
    }

    public function getAll(array $filters = []): array {
        $where  = ["s.deleted_at IS NULL"];
        $params = [];

        if (!empty($filters['search'])) {
            $s = '%' . $filters['search'] . '%';
            $where[] = "(s.first_name LIKE ? OR s.last_name LIKE ? OR s.registration_number LIKE ?)";
            $params  = array_merge($params, [$s, $s, $s]);
        }
        if (!empty($filters['class_id'])) {
            $where[]  = "h.class_id = ?";
            $params[] = $filters['class_id'];
        }
        if (!empty($filters['academic_year_id'])) {
            $where[]  = "h.academic_year_id = ?";
            $params[] = $filters['academic_year_id'];
        }
        if (!empty($filters['gender'])) {
            $where[]  = "s.gender = ?";
            $params[] = $filters['gender'];
        }

        $whereStr = implode(' AND ', $where);
        return $this->fetchAll(
            "SELECT s.*, c.class_name, c.level, ay.year_name,
                    h.status as enrollment_status, h.id as history_id
             FROM students s
             LEFT JOIN student_class_history h ON h.student_id = s.id AND h.status = 'active'
             LEFT JOIN classes c ON c.id = h.class_id
             LEFT JOIN academic_years ay ON ay.id = h.academic_year_id
             WHERE $whereStr
             ORDER BY s.created_at DESC",
            $params
        );
    }

    public function getTrashed(): array {
        return $this->fetchAll(
            "SELECT s.*, c.class_name FROM students s
             LEFT JOIN student_class_history h ON h.student_id = s.id AND h.status = 'active'
             LEFT JOIN classes c ON c.id = h.class_id
             WHERE s.deleted_at IS NOT NULL ORDER BY s.deleted_at DESC"
        );
    }

    public function countAll(): int {
        $row = $this->fetchOne("SELECT COUNT(*) as cnt FROM students WHERE deleted_at IS NULL");
        return $row ? (int)$row['cnt'] : 0;
    }

    public function countByGender(): array {
        return $this->fetchAll(
            "SELECT gender, COUNT(*) as cnt FROM students WHERE deleted_at IS NULL GROUP BY gender"
        );
    }

    public function recentAdmissions(int $limit = 5): array {
        return $this->fetchAll(
            "SELECT s.*, c.class_name FROM students s
             LEFT JOIN student_class_history h ON h.student_id = s.id AND h.status = 'active'
             LEFT JOIN classes c ON c.id = h.class_id
             WHERE s.deleted_at IS NULL ORDER BY s.admission_date DESC LIMIT ?",
            [$limit]
        );
    }

    public function registrationExists(string $reg, int $excludeId = 0): bool {
        $row = $this->fetchOne(
            "SELECT id FROM students WHERE registration_number = ? AND id != ?",
            [$reg, $excludeId]
        );
        return (bool)$row;
    }
}
