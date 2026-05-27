<?php
$pageTitle = 'Trash — Deleted Students';
require_once APP . '/views/layouts/header.php';
?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Trash</h3>
        <p class="page-subtitle">Deleted students — can be restored</p>
    </div>
    <a href="?page=students" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-2"></i>Back to Students</a>
</div>

<div class="card-glass">
    <div class="card-glass-header">
        <h5><i class="bi bi-trash3-fill me-2"></i>Deleted Students <span class="badge bg-danger ms-2"><?= count($students) ?></span></h5>
    </div>
    <div class="card-glass-body p-0">
        <div class="table-responsive">
            <table id="trashTable" class="table table-hover mb-0">
                <thead>
                    <tr><th>#</th><th>Reg No.</th><th>Full Name</th><th>Gender</th><th>Class</th><th>Deleted</th><th>Actions</th></tr>
                </thead>
                <tbody>
                <?php foreach ($students as $i => $s): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><code><?= htmlspecialchars($s['registration_number']) ?></code></td>
                    <td><strong><?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?></strong></td>
                    <td><?= $s['gender'] ?></td>
                    <td><?= htmlspecialchars($s['class_name'] ?? '—') ?></td>
                    <td><?= $s['deleted_at'] ? date('M d, Y H:i', strtotime($s['deleted_at'])) : '—' ?></td>
                    <td>
                        <button class="btn btn-xs btn-outline-success" onclick="restoreStudent(<?= $s['id'] ?>, '<?= htmlspecialchars($s['first_name'].' '.$s['last_name'], ENT_QUOTES) ?>')">
                            <i class="bi bi-arrow-counterclockwise me-1"></i>Restore
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                <tr><td colspan="7"><div class="empty-state py-5"><i class="bi bi-trash3"></i><p>Trash is empty</p></div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    if ($('#trashTable tbody tr').length > 1) {
        $('#trashTable').DataTable({ pageLength: 25, order: [] });
    }
});
function restoreStudent(id, name) {
    Swal.fire({
        title: 'Restore Student?', text: `Restore ${name}?`,
        icon: 'question', showCancelButton: true, confirmButtonColor: '#22c55e',
        confirmButtonText: 'Restore'
    }).then(r => {
        if (r.isConfirmed) {
            $.post('?page=students&action=restore', { id }, res => {
                if (res.success) { showToast('success', res.message); setTimeout(() => location.reload(), 800); }
                else showToast('error', res.message);
            }, 'json');
        }
    });
}
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
