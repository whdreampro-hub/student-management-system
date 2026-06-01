<?php
$pageTitle = 'Academic Years';
require_once APP . '/views/layouts/header.php';
?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Academic Years</h3>
        <p class="page-subtitle">Manage academic years and set the active one</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#yearModal" onclick="openCreate()">
        <i class="bi bi-plus-circle me-2"></i>Add Year
    </button>
</div>

<div class="card-glass">
    <div class="card-glass-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead><tr><th>#</th><th>Year Name</th><th>Status</th><th>Created</th><th>Actions</th></tr></thead>
                <tbody>
                <?php foreach ($years as $i => $y): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td><strong><?= htmlspecialchars($y['year_name']) ?></strong></td>
                    <td>
                        <?php if ($y['status'] === 'active'): ?>
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i>Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><?= $y['created_at'] ? date('M d, Y', strtotime($y['created_at'])) : '—' ?></td>
                    <td>
                        <div class="d-flex gap-2">
                            <?php if ($y['status'] !== 'active'): ?>
                            <button class="btn btn-xs btn-outline-success"
                                onclick="setActive(<?= $y['id'] ?>, '<?= htmlspecialchars($y['year_name'], ENT_QUOTES) ?>')">
                                <i class="bi bi-check-circle me-1"></i>Set Active
                            </button>
                            <?php endif; ?>
                            <button class="btn btn-xs btn-outline-warning"
                                onclick="openEdit(<?= htmlspecialchars(json_encode($y)) ?>)">
                                <i class="bi bi-pencil"></i>
                            </button>
                            <?php if ($y['status'] !== 'active'): ?>
                            <button class="btn btn-xs btn-outline-danger"
                                onclick="deleteYear(<?= $y['id'] ?>, '<?= htmlspecialchars($y['year_name'], ENT_QUOTES) ?>')">
                                <i class="bi bi-trash3"></i>
                            </button>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($years)): ?>
                <tr><td colspan="5"><div class="empty-state py-4"><i class="bi bi-calendar-x"></i><p>No academic years yet</p></div></td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="yearModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content modal-dark">
        <div class="modal-header">
            <h5 class="modal-title" id="yearModalTitle">Add Academic Year</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="yearForm">
                <input type="hidden" name="id" id="yearId">
                <div class="mb-3">
                    <label class="form-label required">Year Name</label>
                    <input type="text" class="form-control" name="year_name" id="yearName"
                           placeholder="e.g. 2025-2026" required>
                </div>
                <div class="mb-3" id="statusRow" style="display:none">
                    <label class="form-label">Status</label>
                    <select class="form-select" name="status" id="yearStatus">
                        <option value="inactive">Inactive</option>
                        <option value="active">Active</option>
                    </select>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" onclick="saveYear()">Save</button>
        </div>
    </div></div>
</div>

<script>
let yearEditMode = false;
function openCreate() {
    yearEditMode = false;
    document.getElementById('yearModalTitle').textContent = 'Add Academic Year';
    document.getElementById('yearForm').reset();
    document.getElementById('yearId').value = '';
    document.getElementById('statusRow').style.display = 'none';
}
function openEdit(y) {
    yearEditMode = true;
    document.getElementById('yearModalTitle').textContent = 'Edit Academic Year';
    document.getElementById('yearId').value    = y.id;
    document.getElementById('yearName').value  = y.year_name;
    document.getElementById('yearStatus').value = y.status;
    document.getElementById('statusRow').style.display = 'block';
    new bootstrap.Modal(document.getElementById('yearModal')).show();
}
function saveYear() {
    const fd  = new FormData(document.getElementById('yearForm'));
    const url = yearEditMode ? '?page=academic_years&action=update' : '?page=academic_years&action=store';
    $.ajax({ url, method:'POST', data:fd, processData:false, contentType:false, dataType:'json',
        success: res => {
            if (res.success) {
                showToast('success', res.message);
                setTimeout(() => location.reload(), 800);
                bootstrap.Modal.getInstance(document.getElementById('yearModal'))?.hide();
            } else showToast('error', res.message);
        }
    });
}
function setActive(id, name) {
    Swal.fire({ title:'Set as Active?', text:`Set "${name}" as the active academic year?`, icon:'question',
        showCancelButton:true, confirmButtonColor:'#22c55e', confirmButtonText:'Set Active' })
    .then(r => {
        if (r.isConfirmed) {
            $.post('?page=academic_years&action=set_active', {id}, res => {
                if (res.success) { showToast('success', res.message); setTimeout(() => location.reload(), 800); }
                else showToast('error', res.message);
            }, 'json');
        }
    });
}
function deleteYear(id, name) {
    Swal.fire({ title:'Delete Year?', text:`Delete "${name}"?`, icon:'warning',
        showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete' })
    .then(r => {
        if (r.isConfirmed) {
            $.post('?page=academic_years&action=delete', {id}, res => {
                if (res.success) { showToast('success', res.message); setTimeout(() => location.reload(), 800); }
                else showToast('error', res.message);
            }, 'json');
        }
    });
}
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
