<?php $pageTitle = 'Discipline Management'; require_once APP . '/views/layouts/header.php'; ?>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title"><i class="bi bi-shield-exclamation me-2 text-warning"></i>Discipline Management</h3>
        <p class="page-subtitle">Monitor student behaviour marks and incident records</p>
    </div>
</div>

<!-- Stats Row -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-box" style="--accent:#22c55e">
            <div class="stat-icon"><i class="bi bi-emoji-smile-fill"></i></div>
            <div class="stat-val"><?= $stats['good'] ?></div>
            <div class="stat-lbl">Good Standing (&gt;30)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="--accent:#f59e0b">
            <div class="stat-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
            <div class="stat-val"><?= $stats['warning'] ?></div>
            <div class="stat-lbl">Warning Zone (21-30)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="--accent:#ef4444">
            <div class="stat-icon"><i class="bi bi-x-octagon-fill"></i></div>
            <div class="stat-val"><?= $stats['critical'] ?></div>
            <div class="stat-lbl">Critical (&le;20)</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-box" style="--accent:#6366f1">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-val"><?= $stats['total'] ?></div>
            <div class="stat-lbl">Total Students</div>
        </div>
    </div>
</div>

<!-- Marks Scale Reference -->
<div class="detail-card mb-4">
    <h6 class="fw-700 mb-3"><i class="bi bi-info-circle me-2 text-info"></i>Behaviour Marks Scale</h6>
    <div class="d-flex flex-wrap gap-2">
        <span class="badge rounded-pill" style="background:#22c55e;padding:.5rem 1rem;font-size:.85rem">40 — Start (Perfect)</span>
        <span class="badge rounded-pill" style="background:#84cc16;padding:.5rem 1rem;font-size:.85rem">31-39 — Good</span>
        <span class="badge rounded-pill" style="background:#f59e0b;padding:.5rem 1rem;font-size:.85rem">≤30 — Counseling Session</span>
        <span class="badge rounded-pill" style="background:#f97316;padding:.5rem 1rem;font-size:.85rem">≤25 — Parent Notification</span>
        <span class="badge rounded-pill" style="background:#ef4444;padding:.5rem 1rem;font-size:.85rem">≤20 — Weekend Detention</span>
        <span class="badge rounded-pill" style="background:#991b1b;padding:.5rem 1rem;font-size:.85rem">≤15 — Transfer to Another School</span>
        <span class="badge rounded-pill" style="background:#1a0000;border:1px solid #7f1d1d;padding:.5rem 1rem;font-size:.85rem">0 — Permanent Expulsion</span>
    </div>
</div>

<!-- Critical Students -->
<?php if (!empty($critical)): ?>
<div class="detail-card mb-4" style="border-left:4px solid #ef4444">
    <h6 class="fw-700 mb-3 text-danger"><i class="bi bi-exclamation-octagon-fill me-2"></i>Students Requiring Immediate Action (≤20 marks)</h6>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead><tr><th>Student</th><th>Class</th><th>Marks</th><th>Status</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach($critical as $c):
                $m=(int)$c['marks'];
                $mc=$m<=15?'#7f1d1d':($m<=20?'#ef4444':'#f59e0b');
                $ml=$m<=0?'Expulsion Risk':($m<=15?'Transfer':($m<=20?'Weekend Detention':'Warning'));
            ?>
            <tr>
                <td>
                    <div class="fw-600"><?= htmlspecialchars(ucfirst($c['first_name']).' '.ucfirst($c['last_name'])) ?></div>
                    <div class="text-muted small"><?= htmlspecialchars($c['registration_number']) ?></div>
                </td>
                <td><?= htmlspecialchars($c['class_name'] ?? '—') ?></td>
                <td><span class="fw-700" style="color:<?= $mc ?>;font-size:1.1rem"><?= $m ?>/40</span></td>
                <td><span class="badge" style="background:<?= $mc ?>"><?= $ml ?></span></td>
                <td>
                    <a href="?page=classes&action=student_detail&student_id=<?= $c['student_id'] ?><?= $c['class_id'] ? '&class_id='.$c['class_id'] : '' ?>" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-person-lines-fill"></i> View
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!-- Recent Incidents -->
<div class="detail-card">
    <h6 class="fw-700 mb-3"><i class="bi bi-clock-history me-2 text-primary"></i>Recent Discipline Incidents</h6>
    <?php if(empty($records)): ?>
    <div class="text-center py-4 text-muted"><i class="bi bi-check-circle display-4 text-success"></i><p class="mt-2">No discipline incidents recorded yet.</p></div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle" id="discTable">
            <thead><tr><th>Date</th><th>Student</th><th>Class</th><th>Marks</th><th>Before→After</th><th>Reason</th><th>By</th><th>Action</th></tr></thead>
            <tbody>
            <?php foreach($records as $r):
                $isPos=(int)$r['marks_removed']<0;
                $ma=(int)$r['marks_after'];
                $mc=$ma>20?'#22c55e':($ma>15?'#ef4444':'#7f1d1d');
                $disc=null;
                foreach(DisciplineModel::THRESHOLDS as $t=>$inf){if($ma<=$t){$disc=$inf;break;}}
            ?>
            <tr>
                <td><?= date('M d, Y',strtotime($r['incident_date'])) ?></td>
                <td>
                    <a href="?page=classes&action=student_detail&student_id=<?= $r['student_id'] ?>" class="fw-600 text-decoration-none">
                        <?= htmlspecialchars(ucfirst($r['first_name']).' '.ucfirst($r['last_name'])) ?>
                    </a>
                    <div class="text-muted small"><?= htmlspecialchars($r['registration_number']) ?></div>
                </td>
                <td><?= htmlspecialchars($r['class_name'] ?? '—') ?></td>
                <td><?= $isPos ? '<span class="badge bg-success">+'.abs((int)$r['marks_removed']).'</span>' : '<span class="badge bg-danger">-'.$r['marks_removed'].'</span>' ?></td>
                <td><span class="badge bg-secondary"><?= $r['marks_before'] ?></span> → <span class="badge" style="background:<?= $mc ?>"><?= $r['marks_after'] ?></span></td>
                <td style="max-width:200px;white-space:normal"><?= htmlspecialchars($r['reason']) ?></td>
                <td><?= htmlspecialchars($r['removed_by']) ?></td>
                <td><?php if($disc): ?><span class="badge" style="background:<?= $ma<=15?'#7f1d1d':($ma<=20?'#ef4444':'#f59e0b') ?>;font-size:.72rem"><?= $disc['label'] ?></span><?php endif; ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>
</div>

<style>
.stat-box{background:var(--card-bg,#1e293b);border-radius:12px;padding:1.2rem;text-align:center;border-top:3px solid var(--accent);box-shadow:0 2px 12px rgba(0,0,0,.15);}
.stat-icon{font-size:1.6rem;color:var(--accent);margin-bottom:.4rem;}
.stat-val{font-size:2rem;font-weight:800;color:var(--accent);}
.stat-lbl{font-size:.78rem;color:#94a3b8;margin-top:.2rem;}
.detail-card{background:var(--card-bg,#1e293b);border-radius:14px;padding:1.5rem;box-shadow:0 2px 16px rgba(0,0,0,.2);}
.fw-700{font-weight:700;}.fw-600{font-weight:600;}
</style>
<script>
$(document).ready(function(){$('#discTable').DataTable({order:[[0,'desc']],pageLength:15});});
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
