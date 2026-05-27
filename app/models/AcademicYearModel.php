<?php
class AcademicYearModel extends Model {

    public function getAll(): array {
        return $this->fetchAll("SELECT * FROM academic_years ORDER BY year_name DESC");
    }

    public function getActive(): array|false {
        return $this->fetchOne("SELECT * FROM academic_years WHERE status = 'active' LIMIT 1");
    }

    public function getById(int $id): array|false {
        return $this->fetchOne("SELECT * FROM academic_years WHERE id = ?", [$id]);
    }

    public function create(array $data): bool {
        $this->query("INSERT INTO academic_years (year_name, status) VALUES (?, ?)",
            [$data['year_name'], $data['status'] ?? 'inactive']);
        return true;
    }

    public function update(int $id, array $data): bool {
        $this->query("UPDATE academic_years SET year_name = ?, status = ? WHERE id = ?",
            [$data['year_name'], $data['status'], $id]);
        return true;
    }

    public function setActive(int $id): bool {
        $this->query("UPDATE academic_years SET status = 'inactive'");
        $this->query("UPDATE academic_years SET status = 'active' WHERE id = ?", [$id]);
        return true;
    }

    public function delete(int $id): bool {
        $this->query("DELETE FROM academic_years WHERE id = ?", [$id]);
        return true;
    }
}
