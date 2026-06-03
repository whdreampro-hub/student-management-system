<?php $pageTitle = 'Attendance'; require_once APP . '/views/layouts/header.php'; ?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title"><i class="bi bi-calendar-check me-2 text-success"></i>Attendance</h3>
        <p class="page-subtitle">Record and review daily student attendance</p>
    </div>
</div>

<!-- Class + Date Selector -->
<div class="detail-card mb-4">
    <form method="GET" class="row g-3 align-items-end">
        <input type="hidden" name="page" value="attendance">
        <div class="col-md-4">
            <label class="form-label fw-600">Select Class</label>
            <select name="class_id" id="classSelect" class="form-select" onchange="this.form.submit()">
                <option value="">— Choose a class —</option>
                <?php foreach($classes as $c): ?>
                <option value="<?= $c['id'] ?>" <?= $c['id']==$classId?'selected':'' ?>><?= htmlspecialchars($c['class_name']) ?> (<?= htmlspecialchars($c['level']) ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <label class="form-label fw-600">Date</label>
            <input type="date" name="date" class="form-control" value="<?= htmlspecialchars($date) ?>" onchange="this.form.submit()">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="bi bi-search me-1"></i>Load</button>
        </div>
        <?php if($classId): ?>
        <div class="col-md-3">
            <a href="?page=classes&action=students&class_id=<?= $classId ?>" class="btn btn-outline-secondary w-100">
                <i class="bi bi-people me-1"></i>Class Roster
            </a>
        </div>
        <?php endif; ?>
    </form>
</div>

<?php if($classId && $class && !empty($students)): ?>
<!-- Attendance Sheet -->
<div class="detail-card mb-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-700 mb-0">
            <i class="bi bi-list-check me-2 text-success"></i>
            <?= htmlspecialchars($class['class_name']) ?> — <?= date('l, F j, Y', strtotime($date)) ?>
        </h6>
        <div class="d-flex gap-2">
            <button class="btn btn-sm btn-outline-success" onclick="markAll('present')"><i class="bi bi-check-all me-1"></i>All Present</button>
            <button class="btn btn-sm btn-outline-danger"  onclick="markAll('absent')"><i class="bi bi-x me-1"></i>All Absent</button>
        </div>
    </div>

    <form id="attForm">
        <input type="hidden" name="class_id"  value="<?= $classId ?>">
        <input type="hidden" name="year_id"   value="<?= $yearId ?>">
        <input type="hidden" name="date"      value="<?= htmlspecialchars($date) ?>">

        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>#</th><th>Student</th>
                        <th><span class="badge bg-success">Present</span></th>
                        <th><span class="badge bg-danger">Absent</span></th>
                        <th><span class="badge bg-warning text-dark">Late</span></th>
                        <th><span class="badge bg-info">Excused</span></th>
                        <th>Remarks</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($students as $i=>$s):
                    $curStatus = $s['status'] !== 'not_recorded' ? $s['status'] : 'present';
                ?>
                <tr>
                    <td class="text-muted"><?= $i+1 ?></td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="att-avatar">
                                <?php $ph=!empty($s['photo'])?uploadUrl($s['photo']):null; ?>
                                <?php if($ph): ?><img src="<?= htmlspecialchars($ph) ?>" class="w-100 h-100 rounded-circle object-fit-cover" alt="">
                                <?php else: ?><span><?= strtoupper(substr($s['first_name'],0,1).substr($s['last_name'],0,1)) ?></span><?php endif; ?>
                            </div>
                            <div>
                                <div class="fw-600"><?= htmlspecialchars(ucfirst($s['first_name']).' '.ucfirst($s['last_name'])) ?></div>
                                <div class="text-muted small"><?= htmlspecialchars($s['registration_number']) ?></div>
                            </div>
                        </div>
                    </td>
                    <?php foreach(['present','absent','late','excused'] as $st): ?>
                    <td class="text-center">
                        <div class="form-check d-flex justify-content-center">
                            <input class="form-check-input att-radio" type="radio"
                                name="attendance[<?= $s['student_id'] ?>][status]"
                                value="<?= $st ?>"
                                data-row="<?= $s['student_id'] ?>"
                                <?= $curStatus===$st?'checked':'' ?>>
                        </div>
                    </td>
                    <?php endforeach; ?>
                    <td>
                        <input type="text" class="form-control form-control-sm"
                            name="attendance[<?= $s['student_id'] ?>][remarks]"
                            placeholder="optional"
                            value="<?= htmlspecialchars($s['remarks'] ?? '') ?>">
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-end mt-3">
            <button type="button" class="btn btn-success" onclick="saveAttendance()">
                <i class="bi bi-save me-1"></i>Save Attendance
            </button>
        </div>
    </form>
</div>

<!-- Past Attendance Dates -->
<?php if(!empty($dates)): ?>
<div class="detail-card">
    <h6 class="fw-700 mb-3"><i class="bi bi-calendar3 me-2 text-primary"></i>Previous Records for <?= htmlspecialchars($class['class_name']) ?></h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead><tr><th>Date</th><th>Present</th><th>Absent</th><th>Late</th><th>Total</th><th>Rate</th><th></th></tr></thead>
            <tbody>
            <?php foreach($dates as $d):
                $rate=$d['total_count']>0?round(($d['present_count']/$d['total_count'])*100):0;
                $rc=$rate>=80?'#22c55e':($rate>=60?'#f59e0b':'#ef4444');
            ?>
            <tr>
                <td><?= date('l, M d, Y', strtotime($d['attendance_date'])) ?></td>
                <td><span class="badge bg-success"><?= $d['present_count'] ?></span></td>
                <td><span class="badge bg-danger"><?= $d['absent_count'] ?></span></td>
                <td><span class="badge bg-warning text-dark"><?= $d['late_count'] ?></span></td>
                <td><?= $d['total_count'] ?></td>
                <td><span class="fw-700" style="color:<?= $rc ?>"><?= $rate ?>%</span></td>
                <td>
                    <a href="?page=attendance&class_id=<?= $classId ?>&date=<?= $d['attendance_date'] ?>" class="btn btn-xs btn-outline-primary">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<?php elseif($classId && $class && empty($students)): ?>
<div class="detail-card text-center py-5">
    <i class="bi bi-people display-4 text-muted"></i>
    <p class="mt-3 text-muted">No students enrolled in this class for the active year.</p>
</div>
<?php elseif(!$classId): ?>
<div class="detail-card text-center py-5">
    <i class="bi bi-calendar-check display-4 text-muted"></i>
    <p class="mt-3 text-muted">Select a class above to take or view attendance.</p>
</div>
<?php endif; ?>

<style>
.detail-card{background:var(--card-bg,#1e293b);border-radius:14px;padding:1.5rem;box-shadow:0 2px 16px rgba(0,0,0,.2);}
.fw-700{font-weight:700;}.fw-600{font-weight:600;}
.att-avatar{width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:.85rem;font-weight:700;flex-shrink:0;overflow:hidden;}
.form-check-input:checked[value="present"]{background-color:#22c55e;border-color:#22c55e;}
.form-check-input:checked[value="absent"]{background-color:#ef4444;border-color:#ef4444;}
.form-check-input:checked[value="late"]{background-color:#f59e0b;border-color:#f59e0b;}
.form-check-input:checked[value="excused"]{background-color:#0ea5e9;border-color:#0ea5e9;}
.btn-xs{padding:.2rem .45rem;font-size:.75rem;}
</style>
<script>
function markAll(status) {
    document.querySelectorAll('.att-radio[value="'+status+'"]').forEach(r => r.checked=true);
}
function saveAttendance() {
    const fd = new FormData(document.getElementById('attForm'));
    $.ajax({url:'?page=attendance&action=save', method:'POST', data:fd, processData:false, contentType:false, dataType:'json',
        success: res => {
            if(res.success) showToast('success', res.message);
            else showToast('error', res.message);
        }
    });
}
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
