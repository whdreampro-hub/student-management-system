<?php
class HistoryModel extends Model {

    public function getStudentHistory(int $studentId): array {
        return $this->fetchAll(
            "SELECT h.*, c.class_name, c.level, ay.year_name
             FROM student_class_history h
             JOIN classes c ON c.id = h.class_id
             JOIN academic_years ay ON ay.id = h.academic_year_id
             WHERE h.student_id = ?
             ORDER BY h.start_date DESC, h.id DESC",
            [$studentId]
        );
    }

    public function getActive(int $studentId): array|false {
        return $this->fetchOne(
            "SELECT h.*, c.class_name, c.level, ay.year_name
             FROM student_class_history h
             JOIN classes c ON c.id = h.class_id
             JOIN academic_years ay ON ay.id = h.academic_year_id
             WHERE h.student_id = ? AND h.status = 'active'
             LIMIT 1",
            [$studentId]
        );
    }

    public function addEntry(array $data): int {
        $this->query(
            "INSERT INTO student_class_history
             (student_id, class_id, academic_year_id, status, reason, start_date, end_date, remarks)
             VALUES (?,?,?,?,?,?,?,?)",
            [
                $data['student_id'], $data['class_id'], $data['academic_year_id'],
                $data['status'] ?? 'active', $data['reason'] ?? 'New Admission',
                $data['start_date'], $data['end_date'] ?? null, $data['remarks'] ?? null
            ]
        );
        return (int)$this->lastInsertId();
    }

    public function closeActive(int $studentId, string $newStatus, string $endDate): bool {
        $this->query(
            "UPDATE student_class_history 
             SET status = ?, end_date = ? 
             WHERE student_id = ? AND status = 'active'",
            [$newStatus, $endDate, $studentId]
        );
        return true;
    }

    public function promote(int $studentId, int $newClassId, int $yearId, string $remarks = ''): bool {
        $today = date('Y-m-d');
        $this->closeActive($studentId, 'promoted', $today);
        $this->addEntry([
            'student_id'       => $studentId,
            'class_id'         => $newClassId,
            'academic_year_id' => $yearId,
            'status'           => 'active',
            'reason'           => 'Promotion',
            'start_date'       => $today,
            'remarks'          => $remarks
        ]);
        return true;
    }

    public function transfer(int $studentId, int $newClassId, int $yearId, string $remarks = ''): bool {
        $today = date('Y-m-d');
        $this->closeActive($studentId, 'transferred', $today);
        $this->addEntry([
            'student_id'       => $studentId,
            'class_id'         => $newClassId,
            'academic_year_id' => $yearId,
            'status'           => 'active',
            'reason'           => 'Transfer',
            'start_date'       => $today,
            'remarks'          => $remarks
        ]);
        return true;
    }

    public function repeat(int $studentId, int $classId, int $yearId, string $remarks = ''): bool {
        $today = date('Y-m-d');
        $this->closeActive($studentId, 'repeated', $today);
        $this->addEntry([
            'student_id'       => $studentId,
            'class_id'         => $classId,
            'academic_year_id' => $yearId,
            'status'           => 'active',
            'reason'           => 'Repeat',
            'start_date'       => $today,
            'remarks'          => $remarks
        ]);
        return true;
    }

    public function getRecentTransfers(int $limit = 5): array {
        return $this->fetchAll(
            "SELECT h.*, s.first_name, s.last_name, s.registration_number,
                    c.class_name, c.level, ay.year_name
             FROM student_class_history h
             JOIN students s ON s.id = h.student_id
             JOIN classes c ON c.id = h.class_id
             JOIN academic_years ay ON ay.id = h.academic_year_id
             WHERE h.reason IN ('Transfer','Promotion')
             ORDER BY h.created_at DESC LIMIT ?",
            [$limit]
        );
    }

    public function countByClass(int $yearId): array {
        return $this->fetchAll(
            "SELECT c.class_name, c.level, COUNT(h.id) as cnt
             FROM student_class_history h
             JOIN classes c ON c.id = h.class_id
             WHERE h.academic_year_id = ? AND h.status = 'active'
             GROUP BY h.class_id ORDER BY c.level, c.class_name",
            [$yearId]
        );
    }

    public function countTransfers(int $yearId): int {
        $row = $this->fetchOne(
            "SELECT COUNT(*) as cnt FROM student_class_history
             WHERE academic_year_id = ? AND reason = 'Transfer'",
            [$yearId]
        );
        return $row ? (int)$row['cnt'] : 0;
    }

    public function countPromotions(int $yearId): int {
        $row = $this->fetchOne(
            "SELECT COUNT(*) as cnt FROM student_class_history
             WHERE academic_year_id = ? AND reason = 'Promotion'",
            [$yearId]
        );
        return $row ? (int)$row['cnt'] : 0;
    }
}
