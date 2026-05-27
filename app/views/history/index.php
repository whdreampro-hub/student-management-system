<?php
$pageTitle = 'Movement History';
require_once APP . '/views/layouts/header.php';
?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Student Movement History</h3>
        <p class="page-subtitle">All promotions, transfers, and class changes</p>
    </div>
</div>

<div class="card-glass">
    <div class="card-glass-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-clock-history me-2"></i>All Movements</h5>
        <div class="d-flex gap-2">
            <select class="form-select form-select-sm" id="filterReason" style="width:160px" onchange="filterTable()">
                <option value="">All Reasons</option>
                <option value="New Admission">New Admission</option>
                <option value="Promotion">Promotion</option>
                <option value="Transfer">Transfer</option>
                <option value="Repeat">Repeat</option>
            </select>
            <select class="form-select form-select-sm" id="filterStatus" style="width:160px" onchange="filterTable()">
                <option value="">All Statuses</option>
                <option value="active">Active</option>
                <option value="promoted">Promoted</option>
                <option value="transferred">Transferred</option>
                <option value="completed">Completed</option>
                <option value="repeated">Repeated</option>
            </select>
        </div>
    </div>
    <div class="card-glass-body p-0">
        <div class="table-responsive">
            <table id="historyTable" class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th><th>Student</th><th>Reg No.</th><th>Class</th>
                        <th>Year</th><th>Reason</th><th>Status</th>
                        <th>Start</th><th>End</th><th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($movements as $i => $m): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td>
                        <a href="?page=students&action=view&id=<?= $m['student_id'] ?>" class="text-decoration-none">
                            <strong><?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?></strong>
                        </a>
                    </td>
                    <td><code><?= htmlspecialchars($m['registration_number']) ?></code></td>
                    <td><?= htmlspecialchars($m['class_name']) ?> <small class="text-muted">(<?= $m['level'] ?>)</small></td>
                    <td><?= htmlspecialchars($m['year_name']) ?></td>
                    <td>
                        <span class="badge reason-<?= strtolower(str_replace(' ','-',$m['reason'])) ?>">
                            <?= $m['reason'] ?>
                        </span>
                    </td>
                    <td><span class="badge status-<?= $m['status'] ?>"><?= ucfirst($m['status']) ?></span></td>
                    <td><?= $m['start_date'] ? date('M d, Y', strtotime($m['start_date'])) : '—' ?></td>
                    <td><?= $m['end_date']   ? date('M d, Y', strtotime($m['end_date']))   : '—' ?></td>
                    <td><?= htmlspecialchars($m['remarks'] ?? '—') ?></td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($movements)): ?>
                <tr><td colspan="10">
                    <div class="empty-state py-5"><i class="bi bi-clock-history"></i><p>No movement records yet</p></div>
                </td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
let histDT;
$(document).ready(function() {
    histDT = $('#historyTable').DataTable({
        pageLength: 25, order: [],
        language: { search: '', searchPlaceholder: 'Search...' }
    });
});
function filterTable() {
    const reason = $('#filterReason').val();
    const status = $('#filterStatus').val();
    histDT.columns(5).search(reason).columns(6).search(status).draw();
}
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
