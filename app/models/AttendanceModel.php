<?php
class AttendanceModel extends Model {

    /** Save or update attendance for a list of students on a date */
    public function saveClassAttendance(int $classId, int $yearId, string $date, int $adminId, array $records): void {
        foreach ($records as $studentId => $data) {
            $status  = $data['status']  ?? 'present';
            $remarks = $data['remarks'] ?? null;

            // Upsert
            $this->query(
                "INSERT INTO attendance (student_id, class_id, academic_year_id, attendance_date, status, remarks, recorded_by)
                 VALUES (?, ?, ?, ?, ?, ?, ?)
                 ON DUPLICATE KEY UPDATE status = VALUES(status), remarks = VALUES(remarks), recorded_by = VALUES(recorded_by)",
                [$studentId, $classId, $yearId, $date, $status, $remarks, $adminId]
            );
        }
    }

    /** Get attendance sheet for a class on a date */
    public function getClassAttendanceOnDate(int $classId, int $yearId, string $date): array {
        return $this->fetchAll(
            "SELECT s.id as student_id, s.first_name, s.last_name, s.registration_number, s.photo,
                    COALESCE(a.status, 'not_recorded') as status,
                    a.remarks, a.id as attendance_id
             FROM student_class_history sch
             JOIN students s ON s.id = sch.student_id AND s.deleted_at IS NULL
             LEFT JOIN attendance a ON a.student_id = s.id AND a.attendance_date = ? AND a.class_id = ?
             WHERE sch.class_id = ? AND sch.academic_year_id = ? AND sch.status = 'active'
             ORDER BY s.last_name, s.first_name",
            [$date, $classId, $classId, $yearId]
        );
    }

    /** Get all dates that have attendance recorded for a class */
    public function getAttendanceDates(int $classId, int $yearId): array {
        return $this->fetchAll(
            "SELECT DISTINCT attendance_date, 
                    SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present_count,
                    SUM(CASE WHEN status='absent'  THEN 1 ELSE 0 END) as absent_count,
                    SUM(CASE WHEN status='late'    THEN 1 ELSE 0 END) as late_count,
                    COUNT(*) as total_count
             FROM attendance
             WHERE class_id = ? AND academic_year_id = ?
             GROUP BY attendance_date
             ORDER BY attendance_date DESC",
            [$classId, $yearId]
        );
    }

    /** Get a student's attendance summary for a class/year */
    public function getStudentSummary(int $studentId, int $yearId): array {
        $row = $this->fetchOne(
            "SELECT 
                COUNT(*) as total_days,
                SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status='absent'  THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status='late'    THEN 1 ELSE 0 END) as late,
                SUM(CASE WHEN status='excused' THEN 1 ELSE 0 END) as excused
             FROM attendance
             WHERE student_id = ? AND academic_year_id = ?",
            [$studentId, $yearId]
        );
        if (!$row || !$row['total_days']) return ['total_days'=>0,'present'=>0,'absent'=>0,'late'=>0,'excused'=>0,'rate'=>0];
        $row['rate'] = $row['total_days'] > 0 ? round(($row['present'] / $row['total_days']) * 100) : 0;
        return $row;
    }

    /** Get a student's full attendance history */
    public function getStudentHistory(int $studentId, int $yearId): array {
        return $this->fetchAll(
            "SELECT a.*, c.class_name
             FROM attendance a
             JOIN classes c ON c.id = a.class_id
             WHERE a.student_id = ? AND a.academic_year_id = ?
             ORDER BY a.attendance_date DESC",
            [$studentId, $yearId]
        );
    }

    /** Stats for dashboard */
    public function getTodayStats(): array {
        $today = date('Y-m-d');
        $row = $this->fetchOne(
            "SELECT 
                SUM(CASE WHEN status='present' THEN 1 ELSE 0 END) as present,
                SUM(CASE WHEN status='absent'  THEN 1 ELSE 0 END) as absent,
                SUM(CASE WHEN status='late'    THEN 1 ELSE 0 END) as late,
                COUNT(*) as total
             FROM attendance WHERE attendance_date = ?", [$today]
        );
        return $row ?? ['present'=>0,'absent'=>0,'late'=>0,'total'=>0];
    }
}
