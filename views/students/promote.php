<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promote Student | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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
        .promote-card {
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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
                    <a class="nav-link" href="index.php?controller=student&action=create">
                        <i class="fas fa-user-plus"></i> Add Student
                    </a>
                    <a class="nav-link" href="index.php?controller=auth&action=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
            
            <div class="col-md-10 main-content p-4">
                <div class="promote-card">
                    <h4 class="mb-4"><i class="fas fa-arrow-up"></i> Promote Student</h4>
                    <div class="alert alert-info">
                        <strong>Current Class:</strong> <?php echo $current['class_name']; ?> |
                        <strong>Academic Year:</strong> <?php echo $current['year_name']; ?>
                    </div>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Promote to Class *</label>
                                <select name="new_class_id" class="form-control" required>
                                    <option value="">Select New Class</option>
                                    <?php while($class = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo $current['class_id'] == $class['id'] ? 'disabled' : ''; ?>>
                                        <?php echo $class['class_name']; ?> - <?php echo $class['level']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Academic Year *</label>
                                <select name="academic_year_id" class="form-control" required>
                                    <option value="">Select Academic Year</option>
                                    <?php while($year = $academicYears->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $year['id']; ?>">
                                        <?php echo $year['year_name']; ?> <?php echo $year['status'] == 'active' ? '(Active)' : ''; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Remarks</label>
                                <textarea name="remarks" class="form-control" rows="3" placeholder="Enter promotion remarks..."></textarea>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-arrow-up"></i> Confirm Promotion
                            </button>
                            <a href="index.php?controller=student&action=profile&id=<?php echo $_GET['id']; ?>" 
                               class="btn btn-secondary btn-lg">
                                <i class="fas fa-times"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>