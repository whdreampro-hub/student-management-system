<?php require_once __DIR__ . '/../../../public/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Student - Student Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="/student-management/public/assets/css/app.css">
    <style>
        /* page-specific */
        .photo-preview {
            width: 150px;
            height: 150px;
            object-fit: cover;
            border-radius: 50%;
            border: 3px solid var(--primary);
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="p-3">
                <h5 class="mb-4">Student Management</h5>
                <nav class="nav flex-column">
                    <a class="nav-link" href="/student-management/public/index.php?controller=dashboard&action=index">
                        <i class="bi bi-speedometer2 me-2"></i> Dashboard
                    </a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=student&action=index">
                        <i class="bi bi-person-lines-fill me-2"></i> Students
                    </a>
                    <a class="nav-link active" href="/student-management/public/index.php?controller=student&action=create">
                        <i class="bi bi-plus-lg me-2"></i> Add New Student
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
                <h1><i class="bi bi-plus-lg me-2"></i> Add New Student</h1>
            </header>

            <?php if (getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars(getFlash('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (getFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars(getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <form action="/student-management/public/index.php?controller=student&action=store" method="POST" enctype="multipart/form-data">
                <!-- Personal Information -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-person me-2"></i> Personal Information</h3>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="registration_number" class="form-label">Registration Number *</label>
                            <input type="text" class="form-control" id="registration_number" name="registration_number" required>
                        </div>
                        <div class="col-md-4">
                            <label for="first_name" class="form-label">First Name *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="last_name" class="form-label">Last Name *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name" required>
                        </div>
                        <div class="col-md-4">
                            <label for="gender" class="form-label">Gender *</label>
                            <select class="form-select" id="gender" name="gender" required>
                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label for="date_of_birth" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth">
                        </div>
                        <div class="col-md-4">
                            <label for="admission_date" class="form-label">Admission Date</label>
                            <input type="date" class="form-control" id="admission_date" name="admission_date" value="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-telephone me-2"></i> Contact Information</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="parent_name" class="form-label">Parent/Guardian Name</label>
                            <input type="text" class="form-control" id="parent_name" name="parent_name">
                        </div>
                        <div class="col-md-6">
                            <label for="parent_phone" class="form-label">Parent/Guardian Phone</label>
                            <input type="tel" class="form-control" id="parent_phone" name="parent_phone">
                        </div>
                        <div class="col-md-6">
                            <label for="guardian_name" class="form-label">Guardian Name (if different)</label>
                            <input type="text" class="form-control" id="guardian_name" name="guardian_name">
                        </div>
                        <div class="col-md-6">
                            <label for="guardian_phone" class="form-label">Guardian Phone</label>
                            <input type="tel" class="form-control" id="guardian_phone" name="guardian_phone">
                        </div>
                        <div class="col-md-6">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email">
                        </div>
                        <div class="col-md-6">
                            <label for="nationality" class="form-label">Nationality</label>
                            <input type="text" class="form-control" id="nationality" name="nationality">
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-geo-alt me-2"></i> Address Information</h3>
                    <div class="row g-3">
                        <div class="col-12">
                            <label for="address" class="form-label">Address</label>
                            <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                        </div>
                        <div class="col-md-3">
                            <label for="village" class="form-label">Village</label>
                            <input type="text" class="form-control" id="village" name="village">
                        </div>
                        <div class="col-md-3">
                            <label for="sector" class="form-label">Sector</label>
                            <input type="text" class="form-control" id="sector" name="sector">
                        </div>
                        <div class="col-md-3">
                            <label for="district" class="form-label">District</label>
                            <input type="text" class="form-control" id="district" name="district">
                        </div>
                    </div>
                </div>

                <!-- Academic Information -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-book me-2"></i> Academic Information</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="class_id" class="form-label">Class *</label>
                            <select class="form-select" id="class_id" name="class_id" required>
                                <option value="">Select Class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['class_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="academic_year_id" class="form-label">Academic Year *</label>
                            <select class="form-select" id="academic_year_id" name="academic_year_id" required>
                                <option value="">Select Academic Year</option>
                                <?php foreach ($academic_years as $year): ?>
                                    <option value="<?= $year['id'] ?>" <?= $year['status'] === 'active' ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['year_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload -->
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-image me-2"></i> Student Photo</h3>
                    <div class="text-center">
                        <div id="photoPreviewContainer">
                            <img src="https://via.placeholder.com/150x150?text=No+Photo" alt="Student Photo Preview" class="photo-preview" id="photoPreview">
                        </div>
                        <div class="mb-3">
                            <label for="photo" class="form-label">Upload Student Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                        </div>
                        <small class="text-muted">Allowed formats: JPG, JPEG, PNG, GIF (Max 5MB)</small>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/student-management/public/index.php?controller=student&action=index" class="btn btn-outline-secondary me-md-2">
                        <i class="bi bi-x-lg me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Save Student
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Photo preview functionality
        document.getElementById('photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('photoPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
            } else {
                document.getElementById('photoPreview').src = 'https://via.placeholder.com/150x150?text=No+Photo';
            }
        });
    </script>
</body>
</html>