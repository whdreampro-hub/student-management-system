<?php
$pageTitle = 'Students';
require_once APP . '/views/layouts/header.php';
$activeYearId = $activeYear ? $activeYear['id'] : 0;
?>

<div class="page-header mb-4">
    <div>
        <h3 class="page-title">All Students</h3>
        <p class="page-subtitle">Manage and track all enrolled students</p>
    </div>
    <div class="page-actions">
        <a href="?page=students&action=create" class="btn btn-primary">
            <i class="bi bi-person-plus-fill me-2"></i>Add Student
        </a>
    </div>
</div>

<!-- Filters -->
<div class="card-glass mb-4">
    <div class="card-glass-body">
        <form method="GET" id="filterForm" class="row g-3 align-items-end">
            <input type="hidden" name="page" value="students">
            <div class="col-md-4">
                <label class="form-label">Search</label>
                <div class="input-icon-wrap">
                    <i class="bi bi-search input-icon"></i>
                    <input type="text" class="form-control ps-5" name="search" id="searchInput"
                           placeholder="Name or registration number..."
                           value="<?= htmlspecialchars($filters['search']) ?>">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label">Class</label>
                <select class="form-select" name="class_id" id="classFilter">
                    <option value="">All Classes</option>
                    <?php foreach ($classes as $c): ?>
                    <option value="<?= $c['id'] ?>" <?= $filters['class_id']==$c['id']?'selected':'' ?>>
                        <?= htmlspecialchars($c['class_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Academic Year</label>
                <select class="form-select" name="academic_year_id" id="yearFilter">
                    <option value="">All Years</option>
                    <?php foreach ($years as $y): ?>
                    <option value="<?= $y['id'] ?>" <?= $filters['academic_year_id']==$y['id']?'selected':'' ?>>
                        <?= htmlspecialchars($y['year_name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Gender</label>
                <select class="form-select" name="gender" id="genderFilter">
                    <option value="">All Genders</option>
                    <option value="Male"   <?= $filters['gender']==='Male'?'selected':'' ?>>Male</option>
                    <option value="Female" <?= $filters['gender']==='Female'?'selected':'' ?>>Female</option>
                </select>
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                <a href="?page=students" class="btn btn-outline-secondary">Reset</a>
            </div>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card-glass">
    <div class="card-glass-header d-flex justify-content-between align-items-center">
        <h5><i class="bi bi-people-fill me-2"></i>Students <span class="badge bg-primary ms-2"><?= count($students) ?></span></h5>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-success" onclick="exportTable('excel')">
                <i class="bi bi-file-earmark-excel me-1"></i>Excel
            </button>
            <button class="btn btn-sm btn-outline-danger" onclick="window.print()">
                <i class="bi bi-printer me-1"></i>Print
            </button>
        </div>
    </div>
    <div class="card-glass-body p-0">
        <div class="table-responsive">
            <table id="studentsTable" class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Photo</th>
                        <th>Reg No.</th>
                        <th>Full Name</th>
                        <th>Gender</th>
                        <th>Class</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th>Admitted</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $i => $s): ?>
                <tr>
                    <td><?= $i+1 ?></td>
                    <td>
                        <div class="table-avatar">
                            <?php if (!empty($s['photo'])): ?>
                                <img src="<?= uploadUrl($s['photo']) ?>" alt="">
                            <?php else: ?>
                                <span><?= strtoupper(substr($s['first_name'],0,1).substr($s['last_name'],0,1)) ?></span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><code><?= htmlspecialchars($s['registration_number']) ?></code></td>
                    <td><strong><?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?></strong></td>
                    <td>
                        <span class="badge <?= $s['gender']==='Male'?'badge-male':'badge-female' ?>">
                            <?= $s['gender'] ?>
                        </span>
                    </td>
                    <td><?= htmlspecialchars($s['class_name'] ?? '—') ?></td>
                    <td><?= htmlspecialchars($s['year_name'] ?? '—') ?></td>
                    <td>
                        <?php $status = $s['enrollment_status'] ?? 'not enrolled'; ?>
                        <span class="badge status-<?= $status ?>">
                            <?= ucfirst($status) ?>
                        </span>
                    </td>
                    <td><?= $s['admission_date'] ? date('M d, Y', strtotime($s['admission_date'])) : '—' ?></td>
                    <td>
                        <div class="d-flex gap-1">
                            <a href="?page=students&action=view&id=<?= $s['id'] ?>"
                               class="btn btn-xs btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                            <a href="?page=students&action=edit&id=<?= $s['id'] ?>"
                               class="btn btn-xs btn-outline-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <button class="btn btn-xs btn-outline-danger" title="Delete"
                                    onclick="deleteStudent(<?= $s['id'] ?>, '<?= htmlspecialchars($s['first_name'].' '.$s['last_name'], ENT_QUOTES) ?>')">
                                <i class="bi bi-trash3"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($students)): ?>
                <tr><td colspan="10">
                    <div class="empty-state py-5">
                        <i class="bi bi-people"></i>
                        <p>No students found. <a href="?page=students&action=create">Add the first student</a></p>
                    </div>
                </td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    if ($('#studentsTable tbody tr').length > 1) {
        $('#studentsTable').DataTable({
            pageLength: 25,
            order: [],
            columnDefs: [{ orderable: false, targets: [1,9] }],
            language: { search: '', searchPlaceholder: 'Quick search...' }
        });
    }
});

function deleteStudent(id, name) {
    Swal.fire({
        title: 'Delete Student?',
        html: `<p>Are you sure you want to delete <strong>${name}</strong>?</p><p class="text-muted small">Student will be moved to trash and can be restored later.</p>`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, Delete',
        cancelButtonText: 'Cancel'
    }).then(r => {
        if (r.isConfirmed) {
            $.post('?page=students&action=delete', { id }, function(res) {
                if (res.success) { showToast('success', res.message); setTimeout(() => location.reload(), 1000); }
                else showToast('error', res.message);
            }, 'json');
        }
    });
}

function exportTable(type) {
    if (type === 'excel') {
        let rows = [['#','Reg No','Full Name','Gender','Class','Year','Admitted']];
        $('#studentsTable tbody tr').each(function(i) {
            const cells = $(this).find('td');
            if (cells.length > 3) {
                rows.push([
                    i+1,
                    cells.eq(2).text().trim(),
                    cells.eq(3).text().trim(),
                    cells.eq(4).text().trim(),
                    cells.eq(5).text().trim(),
                    cells.eq(6).text().trim(),
                    cells.eq(8).text().trim(),
                ]);
            }
        });
        let csv = rows.map(r => r.join(',')).join('\n');
        let a = document.createElement('a');
        a.href = 'data:text/csv,' + encodeURIComponent(csv);
        a.download = 'students_' + Date.now() + '.csv';
        a.click();
    }
}
</script>

<?php require_once APP . '/views/layouts/footer.php'; ?>
