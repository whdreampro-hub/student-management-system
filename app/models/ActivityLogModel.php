<?php
class ActivityLogModel extends Model {

    public function log(int $adminId, string $action, string $description, string $entity = '', int $entityId = 0): void {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $this->query(
            "INSERT INTO activity_logs (admin_id, action, description, entity_type, entity_id, ip_address)
             VALUES (?,?,?,?,?,?)",
            [$adminId, $action, $description, $entity, $entityId, $ip]
        );
    }

    public function getRecent(int $limit = 20): array {
        return $this->fetchAll(
            "SELECT l.*, a.full_name as admin_name 
             FROM activity_logs l
             JOIN admins a ON a.id = l.admin_id
             ORDER BY l.created_at DESC LIMIT ?",
            [$limit]
        );
    }

    public function getAll(): array {
        return $this->fetchAll(
            "SELECT l.*, a.full_name as admin_name 
             FROM activity_logs l
             JOIN admins a ON a.id = l.admin_id
             ORDER BY l.created_at DESC"
        );
    }
}
