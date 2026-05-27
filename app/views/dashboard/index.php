<?php
$pageTitle = 'Dashboard';
require_once APP . '/views/layouts/header.php';

$maleCount   = 0; $femaleCount = 0;
foreach ($genderStats as $g) {
    if ($g['gender'] === 'Male')   $maleCount   = (int)$g['cnt'];
    if ($g['gender'] === 'Female') $femaleCount = (int)$g['cnt'];
}

$classLabels = array_column($classCounts, 'class_name');
$classCntArr = array_column($classCounts, 'cnt');
?>

<div class="page-header mb-4">
    <div>
        <h3 class="page-title">Welcome back, <?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?> 👋</h3>
        <p class="page-subtitle">Here's what's happening in your school today.</p>
    </div>
    <div class="page-actions">
        <?php if ($activeYear): ?>
        <span class="badge badge-active-year">
            <i class="bi bi-calendar-check me-1"></i><?= htmlspecialchars($activeYear['year_name']) ?>
        </span>
        <?php endif; ?>
    </div>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--blue">
            <div class="stat-icon"><i class="bi bi-people-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value"><?= number_format($totalStudents) ?></div>
                <div class="stat-label">Total Students</div>
            </div>
            <div class="stat-trend"><i class="bi bi-graph-up-arrow"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--purple">
            <div class="stat-icon"><i class="bi bi-building-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value"><?= count($classCounts) ?></div>
                <div class="stat-label">Active Classes</div>
            </div>
            <div class="stat-trend"><i class="bi bi-layers-fill"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--green">
            <div class="stat-icon"><i class="bi bi-arrow-up-circle-fill"></i></div>
            <div class="stat-body">
                <div class="stat-value"><?= number_format($totalPromotions) ?></div>
                <div class="stat-label">Promotions This Year</div>
            </div>
            <div class="stat-trend"><i class="bi bi-trophy-fill"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card stat-card--orange">
            <div class="stat-icon"><i class="bi bi-arrow-left-right"></i></div>
            <div class="stat-body">
                <div class="stat-value"><?= number_format($totalTransfers) ?></div>
                <div class="stat-label">Transfers This Year</div>
            </div>
            <div class="stat-trend"><i class="bi bi-shuffle"></i></div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card-glass">
            <div class="card-glass-header">
                <h5><i class="bi bi-bar-chart-fill me-2"></i>Students per Class</h5>
            </div>
            <div class="card-glass-body">
                <canvas id="classChart" height="80"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card-glass">
            <div class="card-glass-header">
                <h5><i class="bi bi-pie-chart-fill me-2"></i>Gender Distribution</h5>
            </div>
            <div class="card-glass-body d-flex flex-column align-items-center justify-content-center">
                <canvas id="genderChart" height="160"></canvas>
                <div class="gender-legend mt-3">
                    <span class="legend-dot male"></span> Male: <strong><?= $maleCount ?></strong>
                    &nbsp;&nbsp;
                    <span class="legend-dot female"></span> Female: <strong><?= $femaleCount ?></strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activity Row -->
<div class="row g-4">
    <div class="col-lg-6">
        <div class="card-glass">
            <div class="card-glass-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-person-plus-fill me-2"></i>Recent Admissions</h5>
                <a href="?page=students" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-glass-body p-0">
                <div class="student-list">
                <?php foreach ($recentAdmissions as $s): ?>
                <a href="?page=students&action=view&id=<?= $s['id'] ?>" class="student-list-item">
                    <div class="student-avatar-sm">
                        <?php if (!empty($s['photo'])): ?>
                            <img src="<?= uploadUrl($s['photo']) ?>" alt="">
                        <?php else: ?>
                            <span><?= strtoupper(substr($s['first_name'],0,1).substr($s['last_name'],0,1)) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="student-info">
                        <strong><?= htmlspecialchars($s['first_name'].' '.$s['last_name']) ?></strong>
                        <small><?= htmlspecialchars($s['registration_number']) ?> &bull; <?= htmlspecialchars($s['class_name'] ?? 'No class') ?></small>
                    </div>
                    <div class="student-date"><?= date('M d', strtotime($s['admission_date'])) ?></div>
                </a>
                <?php endforeach; ?>
                <?php if (empty($recentAdmissions)): ?>
                    <div class="empty-state py-4"><i class="bi bi-inbox"></i><p>No recent admissions</p></div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card-glass">
            <div class="card-glass-header d-flex justify-content-between align-items-center">
                <h5><i class="bi bi-clock-history me-2"></i>Recent Movements</h5>
                <a href="?page=history" class="btn btn-sm btn-outline-primary">View All</a>
            </div>
            <div class="card-glass-body p-0">
                <div class="student-list">
                <?php foreach ($recentMovements as $m): ?>
                <a href="?page=students&action=view&id=<?= $m['student_id'] ?>" class="student-list-item">
                    <div class="movement-icon <?= strtolower($m['reason']) ?>">
                        <i class="bi <?= $m['reason']==='Transfer'?'bi-arrow-left-right':'bi-arrow-up-circle-fill' ?>"></i>
                    </div>
                    <div class="student-info">
                        <strong><?= htmlspecialchars($m['first_name'].' '.$m['last_name']) ?></strong>
                        <small><?= $m['reason'] ?> → <?= htmlspecialchars($m['class_name']) ?> &bull; <?= htmlspecialchars($m['year_name']) ?></small>
                    </div>
                    <div class="student-date"><?= timeAgo($m['created_at']) ?></div>
                </a>
                <?php endforeach; ?>
                <?php if (empty($recentMovements)): ?>
                    <div class="empty-state py-4"><i class="bi bi-inbox"></i><p>No recent movements</p></div>
                <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Class Chart
const classCtx = document.getElementById('classChart').getContext('2d');
new Chart(classCtx, {
    type: 'bar',
    data: {
        labels: <?= json_encode($classLabels) ?>,
        datasets: [{
            label: 'Students',
            data: <?= json_encode($classCntArr) ?>,
            backgroundColor: 'rgba(99,102,241,0.7)',
            borderColor: 'rgba(99,102,241,1)',
            borderWidth: 2,
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#9ca3af' } },
            x: { grid: { display: false }, ticks: { color: '#9ca3af' } }
        }
    }
});

// Gender Chart
const genderCtx = document.getElementById('genderChart').getContext('2d');
new Chart(genderCtx, {
    type: 'doughnut',
    data: {
        labels: ['Male','Female'],
        datasets: [{
            data: [<?= $maleCount ?>, <?= $femaleCount ?>],
            backgroundColor: ['rgba(99,102,241,0.8)','rgba(236,72,153,0.8)'],
            borderColor: ['rgba(99,102,241,1)','rgba(236,72,153,1)'],
            borderWidth: 2,
            hoverOffset: 4
        }]
    },
    options: {
        responsive: true,
        cutout: '70%',
        plugins: { legend: { display: false } }
    }
});
</script>

<?php require_once APP . '/views/layouts/footer.php'; ?>
