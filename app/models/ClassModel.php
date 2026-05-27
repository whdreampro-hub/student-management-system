<?php
class ClassModel extends Model {

    public function getAll(): array {
        return $this->fetchAll("SELECT * FROM classes ORDER BY level, class_name");
    }

    public function getById(int $id): array|false {
        return $this->fetchOne("SELECT * FROM classes WHERE id = ?", [$id]);
    }

    public function create(array $data): bool {
        $this->query("INSERT INTO classes (class_name, level, description) VALUES (?, ?, ?)",
            [$data['class_name'], $data['level'], $data['description'] ?? null]);
        return true;
    }

    public function update(int $id, array $data): bool {
        $this->query("UPDATE classes SET class_name = ?, level = ?, description = ? WHERE id = ?",
            [$data['class_name'], $data['level'], $data['description'] ?? null, $id]);
        return true;
    }

    public function delete(int $id): bool {
        $this->query("DELETE FROM classes WHERE id = ?", [$id]);
        return true;
    }

    public function getStudentCount(int $classId, int $yearId): int {
        $row = $this->fetchOne(
            "SELECT COUNT(*) as cnt FROM student_class_history 
             WHERE class_id = ? AND academic_year_id = ? AND status = 'active'",
            [$classId, $yearId]
        );
        return $row ? (int)$row['cnt'] : 0;
    }

    public function getAllWithCounts(int $yearId): array {
        return $this->fetchAll(
            "SELECT c.*, COUNT(h.id) as student_count 
             FROM classes c 
             LEFT JOIN student_class_history h ON h.class_id = c.id 
                AND h.academic_year_id = ? AND h.status = 'active' 
             GROUP BY c.id ORDER BY c.level, c.class_name",
            [$yearId]
        );
    }
}
