<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student | Student Management System</title>
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
        .form-section {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
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
                    <a class="nav-link" href="index.php?controller=auth&action=logout">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </nav>
            </div>
            
            <div class="col-md-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Edit Student Information</h4>
                    <a href="index.php?controller=student&action=profile&id=<?php echo $this->student->id; ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Back to Profile
                    </a>
                </div>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="form-section">
                        <h5>Personal Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>First Name *</label>
                                <input type="text" name="first_name" class="form-control" value="<?php echo $this->student->first_name; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Last Name *</label>
                                <input type="text" name="last_name" class="form-control" value="<?php echo $this->student->last_name; ?>" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Gender</label>
                                <select name="gender" class="form-control">
                                    <option value="Male" <?php echo $this->student->gender == 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $this->student->gender == 'Female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo $this->student->gender == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="<?php echo $this->student->date_of_birth; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Photo</label>
                                <input type="file" name="photo" class="form-control" accept="image/*">
                                <small>Current: <?php echo $this->student->photo; ?></small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h5>Parent/Guardian Information</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label>Parent Name</label>
                                <input type="text" name="parent_name" class="form-control" value="<?php echo $this->student->parent_name; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Parent Phone</label>
                                <input type="text" name="parent_phone" class="form-control" value="<?php echo $this->student->parent_phone; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Guardian Name</label>
                                <input type="text" name="guardian_name" class="form-control" value="<?php echo $this->student->guardian_name; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Guardian Phone</label>
                                <input type="text" name="guardian_phone" class="form-control" value="<?php echo $this->student->guardian_phone; ?>">
                            </div>
                            <div class="col-md-12 mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="<?php echo $this->student->email; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-section">
                        <h5>Address Information</h5>
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label>Address</label>
                                <textarea name="address" class="form-control" rows="2"><?php echo $this->student->address; ?></textarea>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Village</label>
                                <input type="text" name="village" class="form-control" value="<?php echo $this->student->village; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>Sector</label>
                                <input type="text" name="sector" class="form-control" value="<?php echo $this->student->sector; ?>">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label>District</label>
                                <input type="text" name="district" class="form-control" value="<?php echo $this->student->district; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Nationality</label>
                                <input type="text" name="nationality" class="form-control" value="<?php echo $this->student->nationality; ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label>Admission Date</label>
                                <input type="date" name="admission_date" class="form-control" value="<?php echo $this->student->admission_date; ?>">
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary btn-lg">Update Student</button>
                        <a href="index.php?controller=student&action=profile&id=<?php echo $this->student->id; ?>" class="btn btn-secondary btn-lg">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>