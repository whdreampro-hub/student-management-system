<?php require_once __DIR__ . '/../../../public/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Student Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/student-management/public/assets/css/app.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="p-3">
                <h5 class="mb-4">Student Management</h5>
                <nav class="nav flex-column">
                    <a class="nav-link <?= basename($_SERVER['SCRIPT_NAME']) === 'index.php' && isset($_GET['controller']) && $_GET['controller'] === 'dashboard' ? 'active' : '' ?>" href="/student-management/public/index.php?controller=dashboard&action=index">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a class="nav-link <?= isset($_GET['controller']) && $_GET['controller'] === 'student' ? 'active' : '' ?>" href="/student-management/public/index.php?controller=student&action=index">
                        <i class="bi bi-person-lines-fill me-2"></i> Students
                    </a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=academic_year&action=index">
                        <i class="bi bi-calendar me-2"></i> Academic Years
                    </a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=class&action=index">
                        <i class="bi bi-megaphone me-2"></i> Classes
                    </a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=login&action=logout">
                        <i class="bi bi-box-arrow-left me-2"></i> Logout
                    </a>
                </nav>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content p-4">
            <header class="mb-4">
                <h1><i class="bi bi-speedometer2 me-2"></i> Dashboard</h1>
            </header>

            <!-- Stats Cards -->
            <div class="row g-4 mb-4">
                <div class="col-md-3">
                    <div class="card stats-card stats-card-primary h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">Total Students</h5>
                            <h2 class="display-5"><?= $totalStudents ?? 0 ?></h2>
                        </div>
                    </div>
                </div>
                <?php if (!empty($studentsPerClassData)): ?>
                    <?php foreach ($studentsPerClassData as $index => $classData): ?>
                        <div class="col-md-3">
                            <div class="card stats-card stats-card-<?= ['success', 'info', 'warning', 'danger'][$index % 4] ?> h-100">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title"><?= htmlspecialchars($classData['class_name']) ?> Students</h5>
                                    <h2 class="display-5"><?= $classData['student_count'] ?? 0 ?></h2>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Charts -->
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Gender Distribution</h5>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="genderChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Monthly Admissions (Last 6 Months)</h5>
                        </div>
                        <div class="card-body p-0">
                            <canvas id="admissionsChart" height="150"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="row g-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Admissions</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php if (!empty($recentAdmissionsData)): ?>
                                    <?php foreach ($recentAdmissionsData as $admission): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($admission['first_name'] . ' ' . $admission['last_name']) ?></h6>
                                                <small><?= date('M d, Y', strtotime($admission['admission_date'])) ?></small>
                                            </div>
                                            <p class="mb-1"><?= htmlspecialchars($admission['registration_number']) ?></p>
                                            <small class="text-muted">New Admission</small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="list-group-item text-center py-3">No recent admissions</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="card-title mb-0">Recent Transfers</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="list-group list-group-flush">
                                <?php if (!empty($recentTransfersData)): ?>
                                    <?php foreach ($recentTransfersData as $transfer): ?>
                                        <div class="list-group-item">
                                            <div class="d-flex w-100 justify-content-between">
                                                <h6 class="mb-1"><?= htmlspecialchars($transfer['first_name'] . ' ' . $transfer['last_name']) ?></h6>
                                                <small><?= date('M d, Y', strtotime($transfer['transfer_date'])) ?></small>
                                            </div>
                                            <p class="mb-1"><?= htmlspecialchars($transfer['registration_number']) ?></p>
                                            <small class="text-muted">Transferred to <?= htmlspecialchars($transfer['new_class']) ?></small>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="list-group-item text-center py-3">No recent transfers</div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <!-- Chart.js -->
    <script>
        // Gender Chart
        const genderChartEl = document.getElementById('genderChart');
        if (genderChartEl) {
            const genderCtx = genderChartEl.getContext('2d');
            new Chart(genderCtx, {
                type: 'pie',
                data: {
                    labels: <?= json_encode(array_column($genderData ?? [], 'gender')) ?>,
                    datasets: [{
                        data: <?= json_encode(array_column($genderData ?? [], 'count')) ?>,
                        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#dc3545', '#0dcaf0', '#6f42c1']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        // Admissions Chart
        const admissionsChartEl = document.getElementById('admissionsChart');
        if (admissionsChartEl) {
            const admissionsCtx = admissionsChartEl.getContext('2d');
            new Chart(admissionsCtx, {
                type: 'line',
                data: {
                    labels: <?= json_encode(array_column($monthlyAdmissionsData ?? [], 'month')) ?>,
                    datasets: [{
                        label: 'New Admissions',
                        data: <?= json_encode(array_column($monthlyAdmissionsData ?? [], 'count')) ?>,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: { y: { beginAtZero: true } }
                }
            });
        }
    </script>
</body>
</html>

