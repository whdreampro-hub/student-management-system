
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .dashboard-card {
            border-radius: 15px;
            transition: transform 0.3s;
            cursor: pointer;
        }
        .dashboard-card:hover {
            transform: translateY(-5px);
        }
        .stat-icon {
            font-size: 3rem;
            opacity: 0.7;
        }
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: white;
            padding: 12px 20px;
            margin: 5px 0;
            border-radius: 10px;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .top-bar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 p-0 sidebar">
                <div class="text-center text-white py-4">
                    <h4>SMS</h4>
                    <small>School Management</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link active" href="index.php?controller=dashboard&action=index">
                        <i class="fas fa-dashboard"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?controller=student&action=index">
                        <i class="fas fa-users"></i> Students
                    </a>
                    <a class="nav-link" href="index.php?controller=student&action=create">
                        <i class="fas fa-user-plus"></i> Add Student
                    </a>
                    <a class="nav-link" href="index.php?controller=class&action=manage">
                        <i class="fas fa-book"></i> Classes
                    </a>
                    <a class="nav-link" href="index.php?controller=academic_year&action=manage">
                        <i class="fas fa-calendar"></i> Academic Years
                    </a>
                    <a class="nav-link" href="index.php?controller=report&action=index">
                        <i class="fas fa-chart-line"></i> Reports
                    </a>
                    <a class="nav-link" href="index.php?controller=auth&action=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content p-4">
                <div class="top-bar p-3 mb-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Dashboard</h4>
                    <div>
                        <span class="me-3">Welcome, Admin</span>
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                
                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-3">
                        <div class="card dashboard-card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total Students</h6>
                                        <h2 class="mb-0"><?php echo $total_students; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card dashboard-card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Active Classes</h6>
                                        <h2 class="mb-0"><?php echo $class_stats->rowCount(); ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-book"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card dashboard-card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Male Students</h6>
                                        <h2 class="mb-0"><?php echo $male_count; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-mars"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="card dashboard-card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Female Students</h6>
                                        <h2 class="mb-0"><?php echo $female_count; ?></h2>
                                    </div>
                                    <div class="stat-icon">
                                        <i class="fas fa-venus"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Charts Row -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Students per Class</h6>
                            </div>
                            <div class="card-body">
                                <canvas id="classChart"></canvas>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h6>Recent Admissions</h6>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm">
                                        <thead>
                                            <tr><th>Reg No</th><th>Name</th><th>Date</th></tr>
                                        </thead>
                                        <tbody>
                                            <?php while($student = $recent_students->fetch(PDO::FETCH_ASSOC)): ?>
                                            <tr>
                                                <td><?php echo $student['registration_number']; ?></td>
                                                <td><?php echo $student['first_name'] . ' ' . $student['last_name']; ?></td>
                                                <td><?php echo $student['admission_date']; ?></td>
                                            </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Class Chart
        const ctx = document.getElementById('classChart').getContext('2d');
        const classChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: [<?php while($stat = $class_stats->fetch(PDO::FETCH_ASSOC)) { echo "'" . $stat['class_name'] . "',"; } ?>],
                datasets: [{
                    label: 'Number of Students',
                    data: [<?php $class_stats_data = $this->history->getClassStatistics(); while($stat = $class_stats_data->fetch(PDO::FETCH_ASSOC)) { echo $stat['student_count'] . ","; } ?>],
                    backgroundColor: 'rgba(102, 126, 234, 0.5)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>