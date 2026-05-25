<?php require_once __DIR__ . '/../../../public/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { background-color: #0d6efd; color: white; min-height: 100vh; width: 250px; min-width: 250px; }
        .sidebar a { color: white; text-decoration: none; }
        .sidebar a:hover { background-color: rgba(255,255,255,0.1); }
        .sidebar .active { background-color: rgba(255,255,255,0.2); }
        .main-content { margin-left: 250px; }
        @media (max-width: 768px) { .sidebar { width:100%; min-width:100%; height:auto; position:relative; } .main-content { margin-left:0; } }
        .form-section { border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.5rem; }
        .form-section-title { border-bottom: 2px solid #0d6efd; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #0d6efd; }
        .photo-preview { width: 150px; height: 150px; object-fit: cover; border-radius: 50%; border: 3px solid #0d6efd; margin-bottom: 1rem; }
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
            <header class="mb-4">
                <h1><i class="bi bi-pencil-square me-2"></i> Edit Student</h1>
            </header>

            <?php if (getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= htmlspecialchars(getFlash('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (getFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= htmlspecialchars(getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form action="/student-management/public/index.php?controller=student&action=update&id=<?= $student['id'] ?>" method="POST" enctype="multipart/form-data">

                <!-- Personal Information -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-person me-2"></i> Personal Information</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="registration_number" class="form-label">Registration Number *</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number"
                                   value="<?= htmlspecialchars($student['registration_number']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="<?= htmlspecialchars($student['first_name']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="<?= htmlspecialchars($student['last_name']) ?>" required>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male"   <?= $student['gender'] === 'Male'   ? 'selected' : '' ?>>Male</option>
                                <option value="Female" <?= $student['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
                                <option value="Other"  <?= $student['gender'] === 'Other'  ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth"
                                   value="<?= htmlspecialchars($student['date_of_birth'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="admission_date" class="form-label">Admission Date</label>
                            <input type="date" class="form-control" id="admission_date" name="admission_date"
                                   value="<?= htmlspecialchars($student['admission_date'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="nationality" name="nationality"
                                   value="<?= htmlspecialchars($student['nationality'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email"
                                   value="<?= htmlspecialchars($student['email'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-telephone me-2"></i> Contact Information</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="parent_name" class="form-label">Parent Name</label>
                            <input type="text" class="form-control" id="parent_name" name="parent_name"
                                   value="<?= htmlspecialchars($student['parent_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="parent_phone" class="form-label">Parent Phone</label>
                            <input type="tel" class="form-control" id="parent_phone" name="parent_phone"
                                   value="<?= htmlspecialchars($student['parent_phone'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="guardian_name" class="form-label">Guardian Name</label>
                            <input type="text" class="form-control" id="guardian_name" name="guardian_name"
                                   value="<?= htmlspecialchars($student['guardian_name'] ?? '') ?>">
                        </div>
                        <div class="col-md-6">
                            <label for="guardian_phone" class="form-label">Guardian Phone</label>
                            <input type="tel" class="form-control" id="guardian_phone" name="guardian_phone"
                                   value="<?= htmlspecialchars($student['guardian_phone'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-geo-alt me-2"></i> Address Information</h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="2"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                        </div>
                        <div class="col-md-4">
                            <label for="village" class="form-label">Village</label>
                            <input type="text" class="form-control" id="village" name="village"
                                   value="<?= htmlspecialchars($student['village'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="sector" class="form-label">Sector</label>
                            <input type="text" class="form-control" id="sector" name="sector"
                                   value="<?= htmlspecialchars($student['sector'] ?? '') ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="district" class="form-label">District</label>
                            <input type="text" class="form-control" id="district" name="district"
                                   value="<?= htmlspecialchars($student['district'] ?? '') ?>">
                        </div>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-image me-2"></i> Student Photo</h3>
                    <div class="text-center">
                        <?php if (!empty($student['photo'])): ?>
                            <img src="/student-management/public/<?= htmlspecialchars($student['photo']) ?>"
                                 alt="Current Photo" class="photo-preview" id="photoPreview">
                        <?php else: ?>
                            <img src="https://via.placeholder.com/150x150?text=No+Photo"
                                 alt="No Photo" class="photo-preview" id="photoPreview">
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Upload New Photo (optional)</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        </div>
                        <small class="text-muted">Allowed formats: JPG, JPEG, PNG, GIF. Leave empty to keep current photo.</small>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/student-management/public/index.php?controller=student&action=index" class="btn btn-outline-secondary me-md-2">
                        <i class="bi bi-x-lg me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Update Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
</body>
</html>