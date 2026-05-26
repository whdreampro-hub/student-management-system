<?php require_once __DIR__ . '/../../../public/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Student - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { background-color: #0d6efd; color: white; min-height: 100vh; width: 250px; min-width: 250px; }
        .sidebar a { color: white; text-decoration: none; }
        .sidebar a:hover { background-color: rgba(255,255,255,0.1); }
        .sidebar .active { background-color: rgba(255,255,255,0.2); }
        .main-content { margin-left: 250px; }
        @media (max-width: 768px) { .sidebar { width:100%; min-width:100%; height:auto; position:relative; } .main-content { margin-left:0; } }
        .student-photo { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 4px solid #0d6efd; }
        .info-card { border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.5rem; }
        .info-card-title { border-bottom: 2px solid #0d6efd; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #0d6efd; font-size: 1.1rem; font-weight: 600; }
        .info-label { font-weight: 600; color: #6c757d; font-size: 0.85rem; text-transform: uppercase; }
        .info-value { font-size: 1rem; color: #212529; }
        .badge-active { background-color: #d4edda; color: #155724; padding: 0.3em 0.8em; border-radius: 1rem; font-size: 0.8em; }
        .badge-inactive { background-color: #f8d7da; color: #721c24; padding: 0.3em 0.8em; border-radius: 1rem; font-size: 0.8em; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="p-3">
                <h5 class="mb-4">Student Management</h5>
                <nav class="nav flex-column">
                    <a class="nav-link" href="/student-management/public/index.php?controller=dashboard&action=index"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link active" href="/student-management/public/index.php?controller=student&action=index"><i class="bi bi-person-lines-fill me-2"></i> Students</a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=academic_year&action=index"><i class="bi bi-calendar me-2"></i> Academic Years</a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=class&action=index"><i class="bi bi-megaphone me-2"></i> Classes</a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=login&action=logout"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
                </nav>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="main-content p-4 w-100">
            <header class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h1><i class="bi bi-person-badge me-2"></i> Student Profile</h1>
                <div class="d-flex gap-2 flex-wrap">
                    <a href="/student-management/public/index.php?controller=student&action=edit&id=<?= $student['id'] ?>" class="btn btn-primary">
                        <i class="bi bi-pencil me-1"></i> Edit
                    </a>
                    <a href="/student-management/public/index.php?controller=student&action=index" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-1"></i> Back to List
                    </a>
                </div>
            </header>

            <?php if (getFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars(getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars(getFlash('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <!-- Left: Photo + Quick Info -->
                <div class="col-md-3 mb-4 text-center">
                    <?php if (!empty($student['photo'])): ?>
                        <img src="/student-management/public/<?= htmlspecialchars($student['photo']) ?>" alt="Student Photo" class="student-photo mb-3">
                    <?php else: ?>
                        <div class="student-photo bg-secondary text-white d-inline-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-person" style="font-size:3rem;"></i>
                        </div>
                    <?php endif; ?>
                    <h4><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h4>
                    <p class="text-muted mb-1"><strong>Reg #:</strong> <?= htmlspecialchars($student['registration_number']) ?></p>
                    <?php if ($currentHistoryRecord): ?>
                        <p class="mb-1"><span class="badge-active"><?= htmlspecialchars($currentHistoryRecord['class_name']) ?></span></p>
                        <p class="text-muted small"><?= htmlspecialchars($currentHistoryRecord['year_name']) ?></p>
                    <?php else: ?>
                        <p><span class="badge-inactive">No Active Class</span></p>
                    <?php endif; ?>
                </div>

                <!-- Right: Details -->
                <div class="col-md-9">
                    <!-- Personal Information -->
                    <div class="info-card">
                        <div class="info-card-title"><i class="bi bi-person me-2"></i>Personal Information</div>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="info-label">First Name</div>
                                <div class="info-value"><?= htmlspecialchars($student['first_name']) ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Last Name</div>
                                <div class="info-value"><?= htmlspecialchars($student['last_name']) ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Gender</div>
                                <div class="info-value"><?= htmlspecialchars($student['gender'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Date of Birth</div>
                                <div class="info-value"><?= $student['date_of_birth'] ? date('M d, Y', strtotime($student['date_of_birth'])) : '-' ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Nationality</div>
                                <div class="info-value"><?= htmlspecialchars($student['nationality'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Admission Date</div>
                                <div class="info-value"><?= $student['admission_date'] ? date('M d, Y', strtotime($student['admission_date'])) : '-' ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="info-card">
                        <div class="info-card-title"><i class="bi bi-telephone me-2"></i>Contact Information</div>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="info-label">Parent Name</div>
                                <div class="info-value"><?= htmlspecialchars($student['parent_name'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Parent Phone</div>
                                <div class="info-value"><?= htmlspecialchars($student['parent_phone'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Guardian Name</div>
                                <div class="info-value"><?= htmlspecialchars($student['guardian_name'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Guardian Phone</div>
                                <div class="info-value"><?= htmlspecialchars($student['guardian_phone'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Email</div>
                                <div class="info-value"><?= htmlspecialchars($student['email'] ?: '-') ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="info-card">
                        <div class="info-card-title"><i class="bi bi-geo-alt me-2"></i>Address</div>
                        <div class="row g-3">
                            <div class="col-12">
                                <div class="info-label">Address</div>
                                <div class="info-value"><?= htmlspecialchars($student['address'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Village</div>
                                <div class="info-value"><?= htmlspecialchars($student['village'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">Sector</div>
                                <div class="info-value"><?= htmlspecialchars($student['sector'] ?: '-') ?></div>
                            </div>
                            <div class="col-md-4">
                                <div class="info-label">District</div>
                                <div class="info-value"><?= htmlspecialchars($student['district'] ?: '-') ?></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Promote / Transfer Actions -->
            <?php if ($currentHistoryRecord): ?>
            <div class="row g-3 mb-4">
                <!-- Promote -->
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-card-title"><i class="bi bi-arrow-up-circle me-2"></i>Promote Student</div>
                        <form action="/student-management/public/index.php?controller=student&action=promote&id=<?= $student['id'] ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label">Promote to Class</label>
                                <select class="form-select" name="new_class_id" required>
                                    <option value="">Select Class</option>
                                    <?php
                                    $allClassesStmt = $pdo->query("SELECT * FROM classes ORDER BY class_name");
                                    foreach ($allClassesStmt->fetchAll() as $cls):
                                    ?>
                                        <option value="<?= $cls['id'] ?>"><?= htmlspecialchars($cls['class_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Remarks</label>
                                <input type="text" class="form-control" name="remarks" placeholder="Optional remarks">
                            </div>
                            <input type="hidden" name="reason" value="Promotion">
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Promote this student?')">
                                <i class="bi bi-arrow-up-circle me-1"></i> Promote
                            </button>
                        </form>
                    </div>
                </div>
                <!-- Transfer -->
                <div class="col-md-6">
                    <div class="info-card">
                        <div class="info-card-title"><i class="bi bi-arrow-left-right me-2"></i>Transfer Student</div>
                        <form action="/student-management/public/index.php?controller=student&action=transfer&id=<?= $student['id'] ?>" method="POST">
                            <div class="mb-3">
                                <label class="form-label">New Class</label>
                                <select class="form-select" name="new_class_id" required>
                                    <option value="">Select Class</option>
                                    <?php
                                    $allClassesStmt2 = $pdo->query("SELECT * FROM classes ORDER BY class_name");
                                    foreach ($allClassesStmt2->fetchAll() as $cls):
                                    ?>
                                        <option value="<?= $cls['id'] ?>"><?= htmlspecialchars($cls['class_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Academic Year</label>
                                <select class="form-select" name="new_academic_year_id" required>
                                    <option value="">Select Year</option>
                                    <?php
                                    $allYearsStmt = $pdo->query("SELECT * FROM academic_years ORDER BY year_name DESC");
                                    foreach ($allYearsStmt->fetchAll() as $yr):
                                    ?>
                                        <option value="<?= $yr['id'] ?>" <?= $yr['status']==='active' ? 'selected' : '' ?>><?= htmlspecialchars($yr['year_name']) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <input type="hidden" name="reason" value="Transfer">
                            <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Transfer this student?')">
                                <i class="bi bi-arrow-left-right me-1"></i> Transfer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Class History -->
            <div class="info-card">
                <div class="info-card-title"><i class="bi bi-clock-history me-2"></i>Class History</div>
                <?php if (!empty($historyRecords)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Class</th>
                                    <th>Academic Year</th>
                                    <th>Status</th>
                                    <th>Reason</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($historyRecords as $record): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($record['class_name']) ?></td>
                                        <td><?= htmlspecialchars($record['year_name']) ?></td>
                                        <td>
                                            <span class="<?= $record['status'] === 'active' ? 'badge-active' : 'badge-inactive' ?>">
                                                <?= ucfirst(htmlspecialchars($record['status'])) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($record['reason'] ?: '-') ?></td>
                                        <td><?= $record['start_date'] ? date('M d, Y', strtotime($record['start_date'])) : '-' ?></td>
                                        <td><?= $record['end_date']   ? date('M d, Y', strtotime($record['end_date']))   : '-' ?></td>
                                        <td><?= htmlspecialchars($record['remarks'] ?: '-') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-muted text-center py-3">No class history found for this student.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
