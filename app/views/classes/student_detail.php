<?php require_once APP . '/views/layouts/header.php'; ?>
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="?page=classes">Classes</a></li>
        <?php if($class): ?><li class="breadcrumb-item"><a href="?page=classes&action=students&class_id=<?= $class['id'] ?>"><?= htmlspecialchars($class['class_name']) ?></a></li><?php endif; ?>
        <li class="breadcrumb-item active"><?= htmlspecialchars(ucfirst($student['first_name']).' '.ucfirst($student['last_name'])) ?></li>
    </ol>
</nav>

<?php
$currentMarks = (int)$marks['marks'];
$marksPercent = ($currentMarks/40)*100;
$mColor = $currentMarks>30?'#22c55e':($currentMarks>20?'#f59e0b':($currentMarks>15?'#ef4444':'#7f1d1d'));
$mLabel = $currentMarks>30?'Good Standing':($currentMarks>20?'Warning Zone':($currentMarks>15?'Critical':'Severe Risk'));
$yearId = (int)($_GET['year_id'] ?? (isset($_SESSION['active_year_id']) ? $_SESSION['active_year_id'] : 0));
?>

<div class="row g-4">

  <!-- LEFT: Student Info + Marks -->
  <div class="col-lg-4">
    <!-- Student Card -->
    <div class="detail-card mb-4">
      <div class="text-center mb-4">
        <?php $photo = !empty($student['photo']) ? uploadUrl($student['photo']) : null; ?>
        <div class="detail-avatar mx-auto mb-3">
          <?php if($photo): ?><img src="<?= htmlspecialchars($photo) ?>" alt="" class="w-100 h-100 rounded-circle object-fit-cover">
          <?php else: ?><span><?= strtoupper(substr($student['first_name'],0,1).substr($student['last_name'],0,1)) ?></span><?php endif; ?>
        </div>
        <h5 class="fw-700 mb-0"><?= htmlspecialchars(ucfirst($student['first_name']).' '.ucfirst($student['last_name'])) ?></h5>
        <div class="text-muted small"><?= htmlspecialchars($student['registration_number']) ?></div>
        <?php if($student['class_name']): ?>
        <span class="badge bg-primary mt-1"><?= htmlspecialchars($student['class_name']) ?> — <?= htmlspecialchars($student['level'] ?? '') ?></span>
        <?php endif; ?>
      </div>
      <hr class="border-secondary">
      <div class="info-list">
        <div class="info-row"><i class="bi bi-person-badge"></i><span><?= htmlspecialchars($student['gender']) ?></span></div>
        <div class="info-row"><i class="bi bi-calendar3"></i><span><?= htmlspecialchars($student['date_of_birth']) ?></span></div>
        <?php if($student['parent_name']): ?><div class="info-row"><i class="bi bi-person-heart"></i><span><?= htmlspecialchars($student['parent_name']) ?></span></div><?php endif; ?>
        <?php if($student['parent_phone']): ?><div class="info-row"><i class="bi bi-telephone"></i><span><?= htmlspecialchars($student['parent_phone']) ?></span></div><?php endif; ?>
        <?php if($student['district']): ?><div class="info-row"><i class="bi bi-geo-alt"></i><span><?= htmlspecialchars($student['district']) ?></span></div><?php endif; ?>
      </div>
    </div>

    <!-- Behavior Marks Card -->
    <div class="detail-card marks-card" style="border-top:4px solid <?= $mColor ?>">
      <h6 class="fw-700 mb-3"><i class="bi bi-shield-fill me-2" style="color:<?= $mColor ?>"></i>Behaviour Marks</h6>
      <div class="marks-circle-wrap">
        <svg viewBox="0 0 120 120" class="marks-svg">
          <circle cx="60" cy="60" r="50" fill="none" stroke="#334155" stroke-width="10"/>
          <circle cx="60" cy="60" r="50" fill="none" stroke="<?= $mColor ?>" stroke-width="10"
            stroke-dasharray="<?= round(314*$marksPercent/100) ?> 314"
            stroke-linecap="round" transform="rotate(-90 60 60)"/>
        </svg>
        <div class="marks-circle-text">
          <div class="marks-num" style="color:<?= $mColor ?>"><?= $currentMarks ?></div>
          <div class="marks-max text-muted">/ 40</div>
        </div>
      </div>
      <div class="text-center mt-2">
        <span class="badge" style="background:<?= $mColor ?>;font-size:.85rem;padding:.4rem .9rem"><?= $mLabel ?></span>
      </div>
      <?php if($markAction): ?>
      <div class="alert alert-danger mt-3 mb-0 py-2 text-center small fw-600">
        <i class="bi <?= $markAction['icon'] ?> me-1"></i><?= htmlspecialchars($markAction['label']) ?>
      </div>
      <?php endif; ?>

      <!-- Thresholds reference -->
      <div class="thresholds-ref mt-3">
        <?php foreach(array_reverse($thresholds, true) as $thr => $info): ?>
        <div class="thr-row <?= $currentMarks <= $thr ? 'active-thr' : '' ?>">
          <span class="thr-badge" style="background:<?= $thr<=15?'#7f1d1d':($thr<=20?'#ef4444':($thr<=25?'#f59e0b':($thr<=30?'#f97316':'#22c55e'))) ?>"><?= $thr ?></span>
          <span class="thr-label"><?= htmlspecialchars($info['label']) ?></span>
          <?php if($currentMarks <= $thr && ($thr === min(array_keys(array_filter($thresholds, fn($v)=>$currentMarks<=$thr, ARRAY_FILTER_USE_KEY))))): ?>
          <i class="bi bi-arrow-left text-danger ms-auto"></i>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>

      <!-- Deduct Button -->
      <button class="btn btn-danger w-100 mt-3" data-bs-toggle="modal" data-bs-target="#deductModal">
        <i class="bi bi-dash-circle me-1"></i>Remove Marks
      </button>
    </div>

    <!-- Attendance Summary -->
    <div class="detail-card mt-4">
      <h6 class="fw-700 mb-3"><i class="bi bi-calendar-check me-2 text-success"></i>Attendance Summary</h6>
      <?php $rate = $attSummary['rate']; $attColor = $rate>=80?'#22c55e':($rate>=60?'#f59e0b':'#ef4444'); ?>
      <div class="d-flex justify-content-between align-items-center mb-2">
        <span class="text-muted small">Attendance Rate</span>
        <span class="fw-700" style="color:<?= $attColor ?>;font-size:1.3rem"><?= $rate ?>%</span>
      </div>
      <div class="progress mb-3" style="height:8px;border-radius:4px;">
        <div class="progress-bar" style="width:<?= $rate ?>%;background:<?= $attColor ?>;border-radius:4px;"></div>
      </div>
      <div class="row text-center g-2">
        <div class="col-4"><div class="att-stat-box" style="--c:#22c55e"><div class="att-n"><?= $attSummary['present'] ?></div><div class="att-l">Present</div></div></div>
        <div class="col-4"><div class="att-stat-box" style="--c:#ef4444"><div class="att-n"><?= $attSummary['absent'] ?></div><div class="att-l">Absent</div></div></div>
        <div class="col-4"><div class="att-stat-box" style="--c:#f59e0b"><div class="att-n"><?= $attSummary['late'] ?></div><div class="att-l">Late</div></div></div>
      </div>
    </div>
  </div>

  <!-- RIGHT: Tabs -->
  <div class="col-lg-8">
    <div class="detail-card p-0">
      <ul class="nav nav-tabs px-4 pt-3" id="studentTabs">
        <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabDisc">
          <i class="bi bi-shield-exclamation me-1"></i>Discipline Records</button></li>
        <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabAtt">
          <i class="bi bi-calendar3 me-1"></i>Attendance History</button></li>
      </ul>
      <div class="tab-content p-4">

        <!-- Discipline Tab -->
        <div class="tab-pane fade show active" id="tabDisc">
          <?php if(empty($discRecords)): ?>
          <div class="text-center py-5 text-muted"><i class="bi bi-check-circle display-4 text-success"></i><p class="mt-2">No discipline incidents recorded.</p></div>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead><tr>
                <th>Date</th><th>Marks Removed</th><th>Before / After</th>
                <th>Reason</th><th>Removed By</th><th></th>
              </tr></thead>
              <tbody>
              <?php foreach($discRecords as $r):
                $isPositive = (int)$r['marks_removed'] < 0;
              ?>
              <tr>
                <td><?= date('M d, Y', strtotime($r['incident_date'])) ?></td>
                <td>
                  <?php if($isPositive): ?>
                    <span class="badge bg-success">+<?= abs((int)$r['marks_removed']) ?></span>
                  <?php else: ?>
                    <span class="badge bg-danger">-<?= $r['marks_removed'] ?></span>
                  <?php endif; ?>
                </td>
                <td><span class="badge bg-secondary"><?= $r['marks_before'] ?></span> → <span class="badge" style="background:<?= (int)$r['marks_after']>20?'#22c55e':'#ef4444' ?>"><?= $r['marks_after'] ?></span></td>
                <td><?= htmlspecialchars($r['reason']) ?></td>
                <td><?= htmlspecialchars($r['removed_by']) ?></td>
                <td>
                  <button class="btn btn-xs btn-outline-danger" onclick="deleteDisc(<?= $r['id'] ?>)"><i class="bi bi-trash3"></i></button>
                </td>
              </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>

        <!-- Attendance Tab -->
        <div class="tab-pane fade" id="tabAtt">
          <?php if(empty($attHistory)): ?>
          <div class="text-center py-5 text-muted"><i class="bi bi-calendar-x display-4"></i><p class="mt-2">No attendance records yet.</p></div>
          <?php else: ?>
          <div class="table-responsive">
            <table class="table table-hover align-middle">
              <thead><tr><th>Date</th><th>Class</th><th>Status</th><th>Remarks</th></tr></thead>
              <tbody>
              <?php foreach($attHistory as $a):
                $sc=['present'=>'bg-success','absent'=>'bg-danger','late'=>'bg-warning text-dark','excused'=>'bg-info'][$a['status']] ?? 'bg-secondary';
              ?>
              <tr>
                <td><?= date('M d, Y', strtotime($a['attendance_date'])) ?></td>
                <td><?= htmlspecialchars($a['class_name']) ?></td>
                <td><span class="badge <?= $sc ?>"><?= ucfirst($a['status']) ?></span></td>
                <td><?= htmlspecialchars($a['remarks'] ?? '—') ?></td>
              </tr>
              <?php endforeach; ?>
              </tbody>
            </table>
          </div>
          <?php endif; ?>
        </div>

      </div>
    </div>
  </div>
</div>

<!-- Deduct Marks Modal -->
<div class="modal fade" id="deductModal" tabindex="-1">
  <div class="modal-dialog"><div class="modal-content modal-dark">
    <div class="modal-header border-secondary">
      <h5 class="modal-title"><i class="bi bi-dash-circle-fill text-danger me-2"></i>Remove Behaviour Marks</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
      <form id="deductForm">
        <input type="hidden" name="student_id" value="<?= $student['id'] ?>">
        <div class="mb-3">
          <label class="form-label required">Marks to Remove</label>
          <input type="number" name="marks_removed" class="form-control" min="1" max="<?= $currentMarks ?>" required>
          <div class="form-text">Current balance: <?= $currentMarks ?>/40</div>
        </div>
        <div class="mb-3">
          <label class="form-label required">Reason / Incident Description</label>
          <textarea name="reason" class="form-control" rows="3" required placeholder="Describe the behaviour incident..."></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label required">Removed By (Principal / Teacher Name)</label>
          <input type="text" name="removed_by" class="form-control" required placeholder="Full name of authority">
        </div>
        <div class="mb-3">
          <label class="form-label required">Incident Date</label>
          <input type="date" name="incident_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>
      </form>
    </div>
    <div class="modal-footer border-secondary">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      <button class="btn btn-danger" onclick="submitDeduct()"><i class="bi bi-dash-circle me-1"></i>Remove Marks</button>
    </div>
  </div></div>
</div>

<style>
.detail-card{background:var(--card-bg,#1e293b);border-radius:14px;padding:1.5rem;box-shadow:0 2px 16px rgba(0,0,0,.2);}
.detail-avatar{width:80px;height:80px;border-radius:50%;background:linear-gradient(135deg,#6366f1,#8b5cf6);display:flex;align-items:center;justify-content:center;color:#fff;font-size:1.8rem;font-weight:700;overflow:hidden;}
.fw-700{font-weight:700;}
.info-list{display:flex;flex-direction:column;gap:.5rem;}
.info-row{display:flex;align-items:center;gap:.6rem;font-size:.9rem;color:var(--text-muted,#94a3b8);}
.info-row i{color:#6366f1;width:18px;}
.marks-circle-wrap{position:relative;width:140px;height:140px;margin:0 auto;}
.marks-svg{width:140px;height:140px;}
.marks-circle-text{position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);text-align:center;}
.marks-num{font-size:2.2rem;font-weight:800;line-height:1;}
.marks-max{font-size:.8rem;}
.marks-card{}
.thresholds-ref{display:flex;flex-direction:column;gap:.4rem;}
.thr-row{display:flex;align-items:center;gap:.5rem;font-size:.8rem;padding:.3rem .5rem;border-radius:6px;background:rgba(255,255,255,.04);}
.thr-row.active-thr{background:rgba(239,68,68,.12);border-left:3px solid #ef4444;}
.thr-badge{min-width:28px;text-align:center;padding:.15rem .4rem;border-radius:4px;color:#fff;font-weight:700;font-size:.75rem;}
.thr-label{flex:1;}
.att-stat-box{background:rgba(255,255,255,.05);border-radius:8px;padding:.6rem;border-top:3px solid var(--c);}
.att-n{font-size:1.4rem;font-weight:800;color:var(--c);}
.att-l{font-size:.7rem;color:#94a3b8;text-transform:uppercase;letter-spacing:.05em;}
.btn-xs{padding:.2rem .45rem;font-size:.75rem;}
.nav-tabs .nav-link{color:#94a3b8;border:none;border-bottom:2px solid transparent;padding:.6rem 1.2rem;}
.nav-tabs .nav-link.active{color:#6366f1;border-bottom:2px solid #6366f1;background:transparent;}
</style>

<script>
function submitDeduct() {
    const fd = new FormData(document.getElementById('deductForm'));
    $.ajax({url:'?page=discipline&action=deduct', method:'POST', data:fd, processData:false, contentType:false, dataType:'json',
        success: res => {
            if (res.success) { showToast('success', res.message); setTimeout(() => location.reload(), 1200); bootstrap.Modal.getInstance(document.getElementById('deductModal'))?.hide(); }
            else showToast('error', res.message);
        }
    });
}
function deleteDisc(id) {
    Swal.fire({title:'Delete Record?',text:'This will restore the marks and remove this record.',icon:'warning',showCancelButton:true,confirmButtonColor:'#ef4444',confirmButtonText:'Delete'})
    .then(r => { if(r.isConfirmed) $.post('?page=discipline&action=delete_record',{id}, res => { if(res.success){showToast('success',res.message);setTimeout(()=>location.reload(),800);}else showToast('error',res.message); },'json'); });
}
</script>
<?php require_once APP . '/views/layouts/footer.php'; ?>
