<?php $pageTitle = 'Class: ' . htmlspecialchars($class['class_name']) . ' — Students'; require_once APP . '/views/layouts/header.php'; ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="?page=classes">Classes</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($class['class_name']) ?> Students</li>
    </ol>
</nav>
<div class="page-header mb-4">
    <div>
        <h3 class="page-title"><i class="bi bi-people-fill me-2 text-primary"></i><?= htmlspecialchars($class['class_name']) ?> — <?= htmlspecialchars($class['level']) ?></h3>
        <p class="page-subtitle"><?= count($students) ?> student(s) enrolled</p>
    </div>
    <div class="d-flex gap-2">
        <a href="?page=attendance&class_id=<?= $class['id'] ?>" class="btn btn-success"><i class="bi bi-calendar-check me-1"></i>Attendance</a>
        <a href="?page=discipline" class="btn btn-warning text-dark"><i class="bi bi-shield-exclamation me-1"></i>Discipline</a>
    </div>
</div>
<?php if (empty($students)): ?>
<div class="text-center py-5"><i class="bi bi-people display-3 text-muted"></i><p class="mt-3 text-muted">No students enrolled in this class.</p></div>
<?php else: ?>
<div class="row g-3">
<?php foreach ($students as $s):
    $marks=$s['marks']; $marksColor=$marks>30?'#22c55e':($marks>20?'#f59e0b':($marks>15?'#ef4444':'#7f1d1d'));
    $marksLabel=$marks>30?'Good':($marks>20?'Warning':($marks>15?'Critical':'Severe'));
    $attColor=$s['att_rate']>=80?'#22c55e':($s['att_rate']>=60?'#f59e0b':'#ef4444');
    $photo=!empty($s['photo'])?uploadUrl($s['photo']):null;
?>
<div class="col-sm-6 col-md-4 col-xl-3">
<div class="student-class-card" style="border-top:3px solid <?= $marksColor ?>">
    <div class="d-flex align-items-center gap-3 mb-3">
        <div class="samd">
            <?php if($photo): ?><img src="<?= htmlspecialchars($photo) ?>" alt="" class="rounded-circle w-100 h-100 object-fit-cover">
            <?php else: ?><span><?= strtoupper(substr($s['first_name'],0,1).substr($s['last_name'],0,1)) ?></span><?php endif; ?>
        </div>
        <div class="flex-fill overflow-hidden">
            <div class="fw-600 text-truncate"><?= htmlspecialchars(ucfirst($s['first_name']).' '.ucfirst($s['last_name'])) ?></div>
            <div class="text-muted small"><?= htmlspecialchars($s['registration_number']) ?></div>
            <span class="badge rounded-pill text-white" style="font-size:10px;background:<?= $s['gender']==='Male'?'#0ea5e9':'#ec4899' ?>"><?= $s['gender'] ?></span>
        </div>
    </div>
    <div class="mb-2">
        <div class="d-flex justify-content-between small mb-1">
            <span class="text-muted">Behaviour</span>
            <span class="fw-600" style="color:<?= $marksColor ?>"><?= $marks ?>/40 — <?= $marksLabel ?></span>
        </div>
        <div class="progress" style="height:6px;border-radius:3px;"><div class="progress-bar" style="width:<?= ($marks/40)*100 ?>%;background:<?= $marksColor ?>;border-radius:3px;"></div></div>
        <?php if($s['mark_action']): ?><div class="text-danger small mt-1 fw-500"><i class="bi bi-exclamation-triangle-fill me-1"></i><?= htmlspecialchars($s['mark_action']['label']) ?></div><?php endif; ?>
    </div>
    <div class="mb-3">
        <div class="d-flex justify-content-between small mb-1">
            <span class="text-muted">Attendance</span>
            <span class="fw-600" style="color:<?= $attColor ?>"><?= $s['att_rate'] ?>%<?= $s['att_absent']>0?' ('.$s['att_absent'].' absent)':'' ?></span>
        </div>
        <div class="progress" style="height:6px;border-radius:3px;"><div class="progress-bar" style="width:<?= $s['att_rate'] ?>%;background:<?= $attColor ?>;border-radius:3px;"></div></div>
    </div>
    <a href="?page=classes&action=student_detail&student_id=<?= $s['id'] ?>&class_id=<?= $class['id'] ?>" class="btn btn-sm btn-outline-primary w-100"><i class="bi bi-person-lines-fill me-1"></i>Full Profile</a>
</div>
</div>
<?php endforeach; ?>
</div>
<?php endif; ?>
<style>
.student-class-card{background:var(--card-bg,#1e293b);border-radius:12px;padding:1.2rem;box-shadow:0 2px 12px rgba(0,0,0,.15);transition:transform .18s,box-shadow .18s;height:100%;}
.student-class-card:hover{transform:translateY(-3px);box-shadow:0 6px 24px rgba(0,0,0,.25);}
.samd{width:52px;height:52px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.1rem;font-weight:700;flex-shrink:0;overflow:hidden;}
.fw-600{font-weight:600;}.fw-500{font-weight:500;}
</style>
<?php require_once APP . '/views/layouts/footer.php'; ?>
