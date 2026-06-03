<?php
class DisciplineModel extends Model {

    // Thresholds and their actions
    const THRESHOLDS = [
        30 => ['label' => 'Counseling Session',    'color' => 'warning',  'icon' => 'bi-chat-heart-fill'],
        25 => ['label' => 'Parent Notification',   'color' => 'orange',   'icon' => 'bi-telephone-fill'],
        20 => ['label' => 'Weekend Detention',      'color' => 'danger',   'icon' => 'bi-moon-stars-fill'],
        15 => ['label' => 'Transfer to Another School','color'=>'dark',    'icon' => 'bi-arrow-right-circle-fill'],
        0  => ['label' => 'Permanent Expulsion',   'color' => 'black',    'icon' => 'bi-x-octagon-fill'],
    ];

    /** Get or create behavior marks record for a student */
    public function getMarks(int $studentId): array {
        $row = $this->fetchOne(
            "SELECT * FROM student_behavior_marks WHERE student_id = ?", [$studentId]
        );
        if (!$row) {
            $this->query(
                "INSERT INTO student_behavior_marks (student_id, marks) VALUES (?, 40)",
                [$studentId]
            );
            $row = $this->fetchOne(
                "SELECT * FROM student_behavior_marks WHERE student_id = ?", [$studentId]
            );
        }
        return $row;
    }

    /** Deduct marks and log the incident */
    public function deduct(int $studentId, int $marksRemoved, string $reason, string $removedBy, int $adminId, string $incidentDate): array {
        $marksRow   = $this->getMarks($studentId);
        $marksBefore = (int)$marksRow['marks'];
        $marksAfter  = max(0, $marksBefore - $marksRemoved);

        // Update current marks
        $this->query(
            "UPDATE student_behavior_marks SET marks = ? WHERE student_id = ?",
            [$marksAfter, $studentId]
        );

        // Log the record
        $this->query(
            "INSERT INTO discipline_records (student_id, marks_removed, marks_before, marks_after, reason, removed_by, admin_id, incident_date)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$studentId, $marksRemoved, $marksBefore, $marksAfter, $reason, $removedBy, $adminId, $incidentDate]
        );

        // Determine triggered action
        $action = $this->getActionForMarks($marksAfter);

        return [
            'marks_before' => $marksBefore,
            'marks_after'  => $marksAfter,
            'action'       => $action,
        ];
    }

    /** Restore marks (positive correction) */
    public function restore(int $studentId, int $marksAdded, string $reason, string $addedBy, int $adminId, string $incidentDate): void {
        $marksRow    = $this->getMarks($studentId);
        $marksBefore = (int)$marksRow['marks'];
        $marksAfter  = min(40, $marksBefore + $marksAdded);

        $this->query(
            "UPDATE student_behavior_marks SET marks = ? WHERE student_id = ?",
            [$marksAfter, $studentId]
        );

        $this->query(
            "INSERT INTO discipline_records (student_id, marks_removed, marks_before, marks_after, reason, removed_by, admin_id, incident_date)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
            [$studentId, -$marksAdded, $marksBefore, $marksAfter, $reason, $addedBy, $adminId, $incidentDate]
        );
    }

    /** Get all records for a student */
    public function getRecords(int $studentId): array {
        return $this->fetchAll(
            "SELECT dr.*, a.full_name as admin_full_name
             FROM discipline_records dr
             LEFT JOIN admins a ON a.id = dr.admin_id
             WHERE dr.student_id = ?
             ORDER BY dr.incident_date DESC, dr.created_at DESC",
            [$studentId]
        );
    }

    /** Get recent discipline records across all students */
    public function getRecentRecords(int $limit = 20): array {
        return $this->fetchAll(
            "SELECT dr.*, s.first_name, s.last_name, s.registration_number,
                    c.class_name, a.full_name as admin_full_name
             FROM discipline_records dr
             JOIN students s ON s.id = dr.student_id
             LEFT JOIN student_class_history sch ON sch.student_id = dr.student_id AND sch.status = 'active'
             LEFT JOIN classes c ON c.id = sch.class_id
             LEFT JOIN admins a ON a.id = dr.admin_id
             ORDER BY dr.created_at DESC LIMIT ?",
            [$limit]
        );
    }

    /** Get students with critical marks (≤ 20) */
    public function getCriticalStudents(): array {
        return $this->fetchAll(
            "SELECT sbm.*, s.first_name, s.last_name, s.registration_number,
                    c.class_name, c.id as class_id
             FROM student_behavior_marks sbm
             JOIN students s ON s.id = sbm.student_id AND s.deleted_at IS NULL
             LEFT JOIN student_class_history sch ON sch.student_id = sbm.student_id AND sch.status = 'active'
             LEFT JOIN classes c ON c.id = sch.class_id
             WHERE sbm.marks <= 20
             ORDER BY sbm.marks ASC"
        );
    }

    /** Determine what action should be triggered based on marks */
    public function getActionForMarks(int $marks): ?array {
        foreach (self::THRESHOLDS as $threshold => $info) {
            if ($marks <= $threshold) {
                return array_merge($info, ['threshold' => $threshold]);
            }
        }
        return null;
    }

    /** Get discipline stats for dashboard */
    public function getStats(): array {
        $total      = $this->fetchOne("SELECT COUNT(*) as c FROM students WHERE deleted_at IS NULL")['c'] ?? 0;
        $critical   = $this->fetchOne("SELECT COUNT(*) as c FROM student_behavior_marks WHERE marks <= 20")['c'] ?? 0;
        $warning    = $this->fetchOne("SELECT COUNT(*) as c FROM student_behavior_marks WHERE marks > 20 AND marks <= 30")['c'] ?? 0;
        $good       = $this->fetchOne("SELECT COUNT(*) as c FROM student_behavior_marks WHERE marks > 30")['c'] ?? 0;
        return compact('total','critical','warning','good');
    }

    /** Delete a discipline record */
    public function deleteRecord(int $id): void {
        // Revert marks change first
        $record = $this->fetchOne("SELECT * FROM discipline_records WHERE id = ?", [$id]);
        if ($record) {
            $this->query(
                "UPDATE student_behavior_marks SET marks = ? WHERE student_id = ?",
                [$record['marks_before'], $record['student_id']]
            );
            $this->query("DELETE FROM discipline_records WHERE id = ?", [$id]);
        }
    }
}
