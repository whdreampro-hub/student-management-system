<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile | Student Management System</title>
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
            background: rgba(255, 255, 255, 0.2);
        }

        .sidebar .nav-link i {
            margin-right: 10px;
        }

        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }

        .profile-header {
            background: white;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .profile-photo {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 5px solid #667eea;
        }

        .info-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .info-card h6 {
            color: #667eea;
            margin-bottom: 20px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 10px;
        }

        .timeline {
            position: relative;
            padding-left: 30px;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 20px;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -20px;
            top: 0;
            width: 2px;
            height: 100%;
            background: #667eea;
        }

        .timeline-item::after {
            content: '';
            position: absolute;
            left: -24px;
            top: 5px;
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #667eea;
        }

        .badge-status {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
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
                    <a href="index.php?controller=student&action=history&id=<?php echo $this->student->id; ?>"
                        class="btn btn-info">
                        <i class="fas fa-history"></i> View Full History
                    </a>
                </nav>
            </div>

            <!-- Main Content -->
            <div class="col-md-10 main-content p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4>Student Profile</h4>
                    <div>
                        <a href="index.php?controller=student&action=edit&id=<?php echo $this->student->id; ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="index.php?controller=student&action=promote&id=<?php echo $this->student->id; ?>" class="btn btn-success">
                            <i class="fas fa-arrow-up"></i> Promote
                        </a>
                        <a href="index.php?controller=student&action=transfer&id=<?php echo $this->student->id; ?>" class="btn btn-info">
                            <i class="fas fa-exchange-alt"></i> Transfer
                        </a>
                        <a href="index.php?controller=student&action=index" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back
                        </a>
                    </div>
                </div>

                <!-- Profile Header -->
                <div class="profile-header">
                    <div class="row">
                        <div class="col-md-3 text-center">
                            <img src="assets/uploads/students/<?php echo $this->student->photo; ?>" class="profile-photo" alt="Student Photo">
                            <h5 class="mt-3"><?php echo $this->student->first_name . ' ' . $this->student->last_name; ?></h5>
                            <p class="text-muted"><?php echo $this->student->registration_number; ?></p>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-4">
                                    <p><strong><i class="fas fa-venus-mars"></i> Gender:</strong> <?php echo $this->student->gender; ?></p>
                                    <p><strong><i class="fas fa-calendar"></i> Date of Birth:</strong> <?php echo $this->student->date_of_birth; ?></p>
                                    <p><strong><i class="fas fa-calendar-alt"></i> Admission:</strong> <?php echo $this->student->admission_date; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong><i class="fas fa-users"></i> Parent:</strong> <?php echo $this->student->parent_name; ?></p>
                                    <p><strong><i class="fas fa-phone"></i> Parent Phone:</strong> <?php echo $this->student->parent_phone; ?></p>
                                    <p><strong><i class="fas fa-user-friends"></i> Guardian:</strong> <?php echo $this->student->guardian_name ?? 'N/A'; ?></p>
                                </div>
                                <div class="col-md-4">
                                    <p><strong><i class="fas fa-envelope"></i> Email:</strong> <?php echo $this->student->email ?? 'N/A'; ?></p>
                                    <p><strong><i class="fas fa-map-marker-alt"></i> Location:</strong> <?php echo $this->student->village . ', ' . $this->student->sector; ?></p>
                                    <p><strong><i class="fas fa-chalkboard"></i> Current Class:</strong>
                                        <span class="badge bg-primary"><?php echo $current_class['class_name'] ?? 'Not Assigned'; ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <!-- Academic History Timeline -->
                        <div class="info-card">
                            <h6><i class="fas fa-history"></i> Academic History Timeline</h6>
                            <div class="timeline">
                                <?php while ($history = $history_stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <div class="timeline-item">
                                        <strong><?php echo $history['class_name']; ?></strong>
                                        <br>
                                        <small class="text-muted">
                                            <i class="fas fa-calendar"></i> <?php echo $history['start_date']; ?>
                                            <?php if ($history['end_date']): ?>
                                                - <?php echo $history['end_date']; ?>
                                            <?php endif; ?>
                                        </small>
                                        <br>
                                        <span class="badge-status bg-<?php
                                                                        echo $history['status'] == 'active' ? 'success' : ($history['status'] == 'promoted' ? 'info' : ($history['status'] == 'transferred' ? 'warning' : 'secondary'));
                                                                        ?> text-white">
                                            <?php echo ucfirst($history['status']); ?>
                                        </span>
                                        <small class="text-muted"> | <?php echo $history['reason']; ?></small>
                                        <br>
                                        <small><strong>Academic Year:</strong> <?php echo $history['year_name']; ?></small>
                                        <?php if ($history['remarks']): ?>
                                            <br>
                                            <small><strong>Remarks:</strong> <?php echo $history['remarks']; ?></small>
                                        <?php endif; ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Address Information -->
                        <div class="info-card">
                            <h6><i class="fas fa-address-card"></i> Complete Address</h6>
                            <p><strong>Address:</strong> <?php echo $this->student->address ?? 'N/A'; ?></p>
                            <p><strong>Village:</strong> <?php echo $this->student->village ?? 'N/A'; ?></p>
                            <p><strong>Sector:</strong> <?php echo $this->student->sector ?? 'N/A'; ?></p>
                            <p><strong>District:</strong> <?php echo $this->student->district ?? 'N/A'; ?></p>
                            <p><strong>Nationality:</strong> <?php echo $this->student->nationality; ?></p>
                        </div>

                        <!-- Quick Actions -->
                        <div class="info-card">
                            <h6><i class="fas fa-cogs"></i> Quick Actions</h6>
                            <div class="d-grid gap-2">
                                <button onclick="printProfile()" class="btn btn-outline-primary">
                                    <i class="fas fa-print"></i> Print Profile
                                </button>
                                <button onclick="window.location.href='index.php?controller=student&action=promote&id=<?php echo $this->student->id; ?>'"
                                    class="btn btn-outline-success">
                                    <i class="fas fa-arrow-up"></i> Promote Student
                                </button>
                                <button onclick="window.location.href='index.php?controller=student&action=transfer&id=<?php echo $this->student->id; ?>'"
                                    class="btn btn-outline-info">
                                    <i class="fas fa-exchange-alt"></i> Transfer Student
                                </button>
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
        function printProfile() {
            window.print();
        }
    </script>
</body>

</html>