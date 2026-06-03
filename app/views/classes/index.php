<?php
$pageTitle = 'Classes';
require_once APP . '/views/layouts/header.php';
?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Classes</h3>
        <p class="page-subtitle">Manage all school classes and levels</p>
    </div>
    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#classModal" onclick="openCreate()">
        <i class="bi bi-plus-circle me-2"></i>Add Class
    </button>
</div>

<div class="row g-3 mb-4">
    <?php foreach ($classes as $c): ?>
    <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="class-card">
            <div class="class-card-level"><?= htmlspecialchars($c['level']) ?></div>
            <div class="class-card-name"><?= htmlspecialchars($c['class_name']) ?></div>
            <div class="class-card-count"><?= $c['student_count'] ?> Students</div>
            <?php if ($c['description']): ?>
            <div class="class-card-desc"><?= htmlspecialchars($c['description']) ?></div>
            <?php endif; ?>
            <div class="class-card-actions">
                <a href="?page=classes&action=students&class_id=<?= $c['id'] ?>" class="btn btn-xs btn-outline-primary">
                    <i class="bi bi-people-fill"></i> Students
                </a>
                <a href="?page=attendance&class_id=<?= $c['id'] ?>" class="btn btn-xs btn-outline-success">
                    <i class="bi bi-calendar-check"></i>
                </a>
                <button class="btn btn-xs btn-outline-warning"
                    onclick="openEdit(<?= htmlspecialchars(json_encode($c)) ?>)">
                    <i class="bi bi-pencil"></i>
                </button>
                <button class="btn btn-xs btn-outline-danger"
                    onclick="deleteClass(<?= $c['id'] ?>, '<?= htmlspecialchars($c['class_name'], ENT_QUOTES) ?>')">
                    <i class="bi bi-trash3"></i>
                </button>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
    <?php if (empty($classes)): ?>
    <div class="col-12">
        <div class="empty-state py-5"><i class="bi bi-building"></i><p>No classes yet. <a href="#" onclick="openCreate()">Add the first class</a></p></div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal -->
<div class="modal fade" id="classModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content modal-dark">
        <div class="modal-header">
            <h5 class="modal-title" id="classModalTitle">Add Class</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
            <form id="classForm">
                <input type="hidden" name="id" id="classId">
                <div class="mb-3"><label class="form-label required">Class Name</label>
                    <input type="text" class="form-control" name="class_name" id="className" placeholder="e.g. P1, S3" required></div>
                <div class="mb-3"><label class="form-label required">Level</label>
                    <select class="form-select" name="level" id="classLevel" required>
                        <option value="">Select Level</option>
                        <option value="Primary">Primary</option>
                        <option value="Secondary">Secondary</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="mb-3"><label class="form-label">Description</label>
                    <input type="text" class="form-control" name="description" id="classDesc"></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="saveClassBtn" onclick="saveClass()">Save</button>
        </div>
    </div></div>
</div>

<script>
let editMode = false;
function openCreate() {
    editMode = false;
    document.getElementById('classModalTitle').textContent = 'Add Class';
    document.getElementById('classForm').reset();
    document.getElementById('classId').value = '';
}
function openEdit(c) {
    editMode = true;
    document.getElementById('classModalTitle').textContent = 'Edit Class';
    document.getElementById('classId').value   = c.id;
    document.getElementById('className').value  = c.class_name;
    document.getElementById('classLevel').value = c.level;
    document.getElementById('classDesc').value  = c.description || '';
    new bootstrap.Modal(document.getElementById('classModal')).show();
}
function saveClass() {
    const fd = new FormData(document.getElementById('classForm'));
    const url = editMode ? '?page=classes&action=update' : '?page=classes&action=store';
    $.ajax({ url, method:'POST', data:fd, processData:false, contentType:false, dataType:'json',
        success: res => {
            if (res.success) {
                showToast('success', res.message);
                setTimeout(() => location.reload(), 800);
                bootstrap.Modal.getInstance(document.getElementById('classModal'))?.hide();
            } else showToast('error', res.message);
        }
    });
}
function deleteClass(id, name) {
    Swal.fire({ title:'Delete Class?', text:`Delete class "${name}"? This cannot be undone.`, icon:'warning',
        showCancelButton:true, confirmButtonColor:'#ef4444', confirmButtonText:'Delete' })
    .then(r => {
        if (r.isConfirmed) {
            $.post('?page=classes&action=delete', {id}, res => {
                if (res.success) { showToast('success', res.message); setTimeout(() => location.reload(), 800); }
                else showToast('error', res.message);
            }, 'json');
        }
    });
}
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
