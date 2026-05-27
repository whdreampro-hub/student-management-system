<?php
$pageTitle = 'Student Profile';
require_once APP . '/views/layouts/header.php';
$statusColors = ['active'=>'success','promoted'=>'info','transferred'=>'warning','completed'=>'primary','repeated'=>'danger'];
?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Student Profile</h3>
        <p class="page-subtitle"><?= htmlspecialchars($student['registration_number']) ?></p>
    </div>
    <div class="page-actions d-flex gap-2 flex-wrap">
        <a href="?page=students" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i>Back</a>
        <a href="?page=students&action=edit&id=<?= $student['id'] ?>" class="btn btn-warning"><i class="bi bi-pencil me-1"></i>Edit</a>
        <button class="btn btn-outline-primary" onclick="window.print()"><i class="bi bi-printer me-1"></i>Print</button>
        <button class="btn btn-outline-success" onclick="openPromoteModal()"><i class="bi bi-arrow-up-circle me-1"></i>Promote</button>
        <button class="btn btn-outline-info" onclick="openTransferModal()"><i class="bi bi-arrow-left-right me-1"></i>Transfer</button>
        <button class="btn btn-outline-danger" onclick="deleteStudent(<?= $student['id'] ?>,'<?= htmlspecialchars($student['first_name'].' '.$student['last_name'],ENT_QUOTES) ?>')"><i class="bi bi-trash3 me-1"></i>Delete</button>
    </div>
</div>

<div class="row g-4">
    <!-- Profile Card -->
    <div class="col-lg-4">
        <div class="card-glass text-center">
            <div class="card-glass-body">
                <div class="profile-photo-wrap mx-auto mb-3">
                    <?php if (!empty($student['photo'])): ?>
                        <img src="<?= uploadUrl($student['photo']) ?>" alt="" class="profile-photo">
                    <?php else: ?>
                        <div class="profile-photo-placeholder">
                            <?= strtoupper(substr($student['first_name'],0,1).substr($student['last_name'],0,1)) ?>
                        </div>
                    <?php endif; ?>
                </div>
                <h4 class="mb-1"><?= htmlspecialchars($student['first_name'].' '.$student['last_name']) ?></h4>
                <p class="text-muted mb-3"><code><?= htmlspecialchars($student['registration_number']) ?></code></p>
                <div class="d-flex justify-content-center gap-2 mb-3">
                    <span class="badge <?= $student['gender']==='Male'?'badge-male':'badge-female' ?>"><?= $student['gender'] ?></span>
                    <?php if ($student['class_name']): ?>
                    <span class="badge bg-primary"><?= htmlspecialchars($student['class_name']) ?></span>
                    <?php endif; ?>
                    <span class="badge status-<?= $student['enrollment_status'] ?? 'not-enrolled' ?>">
                        <?= ucfirst($student['enrollment_status'] ?? 'Not Enrolled') ?>
                    </span>
                </div>
                <div class="info-grid text-start">
                    <div class="info-row"><span class="info-label">DOB</span><span><?= date('M d, Y', strtotime($student['date_of_birth'])) ?></span></div>
                    <div class="info-row"><span class="info-label">Nationality</span><span><?= htmlspecialchars($student['nationality'] ?? '—') ?></span></div>
                    <div class="info-row"><span class="info-label">Admitted</span><span><?= date('M d, Y', strtotime($student['admission_date'])) ?></span></div>
                    <?php if ($student['email']): ?>
                    <div class="info-row"><span class="info-label">Email</span><span><?= htmlspecialchars($student['email']) ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card-glass mt-4">
            <div class="card-glass-header"><h5><i class="bi bi-geo-alt me-2"></i>Location</h5></div>
            <div class="card-glass-body">
                <div class="info-grid">
                    <div class="info-row"><span class="info-label">Address</span><span><?= htmlspecialchars($student['address'] ?? '—') ?></span></div>
                    <div class="info-row"><span class="info-label">Village</span><span><?= htmlspecialchars($student['village'] ?? '—') ?></span></div>
                    <div class="info-row"><span class="info-label">Sector</span><span><?= htmlspecialchars($student['sector'] ?? '—') ?></span></div>
                    <div class="info-row"><span class="info-label">District</span><span><?= htmlspecialchars($student['district'] ?? '—') ?></span></div>
                </div>
            </div>
        </div>

        <div class="card-glass mt-4">
            <div class="card-glass-header"><h5><i class="bi bi-people me-2"></i>Parent / Guardian</h5></div>
            <div class="card-glass-body">
                <div class="info-grid">
                    <div class="info-row"><span class="info-label">Parent</span><span><?= htmlspecialchars($student['parent_name'] ?? '—') ?></span></div>
                    <div class="info-row"><span class="info-label">Parent Tel</span><span><?= htmlspecialchars($student['parent_phone'] ?? '—') ?></span></div>
                    <div class="info-row"><span class="info-label">Guardian</span><span><?= htmlspecialchars($student['guardian_name'] ?? '—') ?></span></div>
                    <div class="info-row"><span class="info-label">Guardian Tel</span><span><?= htmlspecialchars($student['guardian_phone'] ?? '—') ?></span></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Academic History -->
    <div class="col-lg-8">
        <div class="card-glass">
            <div class="card-glass-header"><h5><i class="bi bi-clock-history me-2"></i>Academic History Timeline</h5></div>
            <div class="card-glass-body">
                <?php if (empty($history)): ?>
                <div class="empty-state py-4"><i class="bi bi-calendar-x"></i><p>No academic history yet</p></div>
                <?php else: ?>
                <div class="timeline">
                    <?php foreach ($history as $h): ?>
                    <div class="timeline-item">
                        <div class="timeline-dot status-dot-<?= $h['status'] ?>"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                                <div>
                                    <strong><?= htmlspecialchars($h['class_name']) ?></strong>
                                    <span class="text-muted ms-2"><?= htmlspecialchars($h['level']) ?></span>
                                </div>
                                <div class="d-flex gap-2 align-items-center">
                                    <span class="badge status-<?= $h['status'] ?>"><?= ucfirst($h['status']) ?></span>
                                    <span class="badge bg-secondary"><?= htmlspecialchars($h['year_name']) ?></span>
                                </div>
                            </div>
                            <div class="text-muted small mt-1">
                                <i class="bi bi-tag me-1"></i><?= $h['reason'] ?>
                                &nbsp;|&nbsp;
                                <i class="bi bi-calendar me-1"></i><?= date('M d, Y', strtotime($h['start_date'])) ?>
                                <?php if ($h['end_date']): ?> → <?= date('M d, Y', strtotime($h['end_date'])) ?><?php endif; ?>
                            </div>
                            <?php if ($h['remarks']): ?>
                            <div class="timeline-remarks"><?= htmlspecialchars($h['remarks']) ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Promote Modal -->
<div class="modal fade" id="promoteModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content modal-dark">
        <div class="modal-header"><h5 class="modal-title"><i class="bi bi-arrow-up-circle me-2"></i>Promote Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <form id="promoteForm">
                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                <div class="mb-3"><label class="form-label">Promote to Class</label>
                    <select class="form-select" name="class_id" id="promoteClass" required></select></div>
                <div class="mb-3"><label class="form-label">Academic Year</label>
                    <select class="form-select" name="academic_year_id" id="promoteYear" required></select></div>
                <div class="mb-3"><label class="form-label">Remarks</label>
                    <textarea class="form-control" name="remarks" rows="2"></textarea></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-success" onclick="submitAction('promote')"><i class="bi bi-arrow-up-circle me-1"></i>Promote</button>
        </div>
    </div></div>
</div>

<!-- Transfer Modal -->
<div class="modal fade" id="transferModal" tabindex="-1">
    <div class="modal-dialog"><div class="modal-content modal-dark">
        <div class="modal-header"><h5 class="modal-title"><i class="bi bi-arrow-left-right me-2"></i>Transfer Student</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
        <div class="modal-body">
            <form id="transferForm">
                <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
                <div class="mb-3"><label class="form-label">Transfer to Class</label>
                    <select class="form-select" name="class_id" id="transferClass" required></select></div>
                <div class="mb-3"><label class="form-label">Academic Year</label>
                    <select class="form-select" name="academic_year_id" id="transferYear" required></select></div>
                <div class="mb-3"><label class="form-label">Reason / Remarks</label>
                    <textarea class="form-control" name="remarks" rows="2" placeholder="Reason for transfer..."></textarea></div>
            </form>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-info" onclick="submitAction('transfer')"><i class="bi bi-arrow-left-right me-1"></i>Transfer</button>
        </div>
    </div></div>
</div>

<script>
let classesData = [], yearsData = [];

function populateSelects() {
    $.get('?page=classes&action=all', r => {
        classesData = r.data;
        ['promoteClass','transferClass'].forEach(id => {
            const sel = document.getElementById(id);
            if (!sel) return;
            sel.innerHTML = '<option value="">Select Class</option>' +
                r.data.map(c => `<option value="${c.id}">${c.class_name} (${c.level})</option>`).join('');
        });
    }, 'json');

    $.get('?page=academic_years&action=all', r => {
        yearsData = r;
    }).always(() => {
        fetch('?page=academic_years').then(() => {});
    });
}

$.getJSON('?page=academic_years', function(){}).always(function(){
    // Load years manually
    fetch('?page=academic_years&action=all')
        .then(r => r.json()).then(r => {
            if (!r || !r.data) return;
            ['promoteYear','transferYear'].forEach(id => {
                const sel = document.getElementById(id);
                if (!sel) return;
                sel.innerHTML = '<option value="">Select Year</option>' +
                    r.data.map(y => `<option value="${y.id}" ${y.status==='active'?'selected':''}>${y.year_name}</option>`).join('');
            });
        });
});

function openPromoteModal() { populateSelects(); new bootstrap.Modal(document.getElementById('promoteModal')).show(); }
function openTransferModal() { populateSelects(); new bootstrap.Modal(document.getElementById('transferModal')).show(); }

function submitAction(type) {
    const form = document.getElementById(type + 'Form');
    const fd = new FormData(form);
    $.ajax({
        url: `?page=history&action=${type}`, method: 'POST', data: fd,
        processData: false, contentType: false, dataType: 'json',
        success: function(res) {
            if (res.success) {
                Swal.fire({ icon:'success', title:'Done!', text:res.message, timer:1500, showConfirmButton:false })
                    .then(() => location.reload());
            } else showToast('error', res.message);
        }
    });
}

function deleteStudent(id, name) {
    Swal.fire({
        title:'Delete Student?', text:`Delete ${name}? They will be moved to trash.`,
        icon:'warning', showCancelButton:true, confirmButtonColor:'#ef4444',
        confirmButtonText:'Delete'
    }).then(r => {
        if (r.isConfirmed) {
            $.post('?page=students&action=delete', {id}, res => {
                if (res.success) window.location.href = '?page=students';
            }, 'json');
        }
    });
}

// Preload selects
document.addEventListener('DOMContentLoaded', function() {
    // Load classes into modal selects on page load
    $.getJSON('?page=classes&action=all', r => {
        ['promoteClass','transferClass'].forEach(id => {
            const sel = document.getElementById(id);
            if (!sel) return;
            sel.innerHTML = '<option value="">Select Class</option>' +
                r.data.map(c => `<option value="${c.id}">${c.class_name} (${c.level})</option>`).join('');
        });
    });
});
</script>

<?php require_once APP . '/views/layouts/footer.php'; ?>
