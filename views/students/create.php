<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .form-section h5 {
            color: #667eea;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
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
                    <a class="nav-link" href="index.php?controller=dashboard&action=index">
                        <i class="fas fa-dashboard"></i> Dashboard
                    </a>
                    <a class="nav-link" href="index.php?controller=student&action=index">
                        <i class="fas fa-users"></i> Students
                    </a>
                    <a class="nav-link active" href="index.php?controller=student&action=create">
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
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Register New Student</h4>
                    <a href="index.php?controller=student&action=index" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to List
                    </a>
                </div>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-section">
                        <h5><i class="fas fa-user-graduate"></i> Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>First Name *</label>
                                <input type="text" name="first_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Gender *</label>
                                <select name="gender" class="form-control" required>
                                    <option value="">Select Gender</option>
                                    <option value="Male">Male</option>
                                    <option value="Female">Female</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Date of Birth *</label>
                                <input type="date" name="date_of_birth" class="form-control" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Nationality</label>
                                <input type="text" name="nationality" class="form-control" value="Rwandan">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Student Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h5><i class="fas fa-chalkboard"></i> Academic Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Initial Class *</label>
                                <select name="class_id" class="form-control" required>
                                    <option value="">Select Class</option>
                                    <?php while($class = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $class['id']; ?>">
                                        <?php echo $class['class_name']; ?> - <?php echo $class['level']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Admission Date *</label>
                                <input type="date" name="admission_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Additional Remarks</label>
                                <textarea name="remarks" class="form-control" rows="2"></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h5><i class="fas fa-users"></i> Parent/Guardian Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Parent Name *</label>
                                <input type="text" name="parent_name" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Parent Phone *</label>
                                <input type="text" name="parent_phone" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Guardian Name</label>
                                <input type="text" name="guardian_name" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Guardian Phone</label>
                                <input type="text" name="guardian_phone" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h5><i class="fas fa-map-marker-alt"></i> Address Information</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Village</label>
                                <input type="text" name="village" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Sector</label>
                                <input type="text" name="sector" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>District</label>
                                <input type="text" name="district" class="form-control">
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save"></i> Register Student
                        </button>
                        <a href="index.php?controller=student&action=index" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>