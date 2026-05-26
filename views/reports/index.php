<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
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
        .sidebar .nav-link:hover {
            background: rgba(255,255,255,0.2);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .report-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            cursor: pointer;
            transition: transform 0.3s;
        }
        .report-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 p-0 sidebar">
                <div class="text-center text-white py-4">
                    <h4>SMS</h4>
                    <small>School Management</small>
                </div>
                <nav class="nav flex-column">
                    <a class="nav-link" href="index.php?controller=dashboard&action=index">
                        <i class="fas fa-dashboard"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?controller=student&action=index">
                        <i class="fas fa-users"></i> Students
                    </a>
                    <a class="nav-link active" href="index.php?controller=report&action=index">
                        <i class="fas fa-chart-line"></i> Reports
                    </a>
                    <a class="nav-link" href="index.php?controller=auth&action=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
            
            <div class="col-md-10 main-content p-4">
                <h4 class="mb-4"><i class="fas fa-chart-line"></i> Reports & Analytics</h4>
                
                <div class="row">
                    <!-- Class Statistics -->
                    <div class="col-md-6 mb-4">
                        <div class="report-card">
                            <h6><i class="fas fa-chart-bar"></i> Students per Class</h6>
                            <canvas id="classChart" height="200"></canvas>
                        </div>
                    </div>
                    
                    <!-- Gender Distribution -->
                    <div class="col-md-6 mb-4">
                        <div class="report-card">
                            <h6><i class="fas fa-chart-pie"></i> Gender Distribution</h6>
                            <canvas id="genderChart" height="200"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Export Reports -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="report-card">
                            <h6><i class="fas fa-download"></i> Export Reports</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <label>Filter by Class</label>
                                    <select id="exportClass" class="form-control">
                                        <option value="">All Classes</option>
                                        <?php while($class = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?php echo $class['id']; ?>"><?php echo $class['class_name']; ?></option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button onclick="exportReport('excel')" class="btn btn-success form-control">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </button>
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;</label>
                                    <button onclick="exportReport('pdf')" class="btn btn-danger form-control">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </button>
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
        const classCtx = document.getElementById('classChart').getContext('2d');
        const classData = {
            labels: [<?php $class_stats = $this->history->getClassStatistics(); while($stat = $class_stats->fetch(PDO::FETCH_ASSOC)) { echo "'" . $stat['class_name'] . "',"; } ?>],
            datasets: [{
                label: 'Number of Students',
                data: [<?php $class_stats = $this->history->getClassStatistics(); while($stat = $class_stats->fetch(PDO::FETCH_ASSOC)) { echo $stat['student_count'] . ","; } ?>],
                backgroundColor: 'rgba(102, 126, 234, 0.5)',
                borderColor: 'rgba(102, 126, 234, 1)',
                borderWidth: 1
            }]
        };
        new Chart(classCtx, {
            type: 'bar',
            data: classData,
            options: { responsive: true, maintainAspectRatio: true }
        });
        
        // Gender Chart
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        new Chart(genderCtx, {
            type: 'pie',
            data: {
                labels: ['Male', 'Female', 'Other'],
                datasets: [{
                    data: [<?php echo $male_count ?? 0; ?>, <?php echo $female_count ?? 0; ?>, 0],
                    backgroundColor: ['#36a2eb', '#ff6384', '#ffce56']
                }]
            },
            options: { responsive: true, maintainAspectRatio: true }
        });
        
        function exportReport(format) {
            const classId = document.getElementById('exportClass').value;
            window.location.href = `index.php?controller=report&action=export&format=${format}&class_id=${classId}`;
        }
    </script>
</body>
</html>