<?php require_once __DIR__ . '/../../../public/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Academic Year - Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar { background-color: #0d6efd; color: white; min-height: 100vh; }
        .sidebar a { color: white; text-decoration: none; }
        .sidebar a:hover { background-color: rgba(255,255,255,0.1); }
        .sidebar .active { background-color: rgba(255,255,255,0.2); }
        .main-content { margin-left: 250px; }
        @media (max-width: 768px) { .sidebar { width:100%; height:auto; position:relative; } .main-content { margin-left:0; } }
        .form-section { border: 1px solid #dee2e6; border-radius: 0.5rem; padding: 1.5rem; margin-bottom: 1.5rem; }
        .form-section-title { border-bottom: 2px solid #0d6efd; padding-bottom: 0.5rem; margin-bottom: 1rem; color: #0d6efd; }
    </style>
</head>
<body>
    <div class="d-flex">
        <nav class="sidebar" style="width:250px; min-width:250px;">
            <div class="p-3">
                <h5 class="mb-4">Student Management</h5>
                <nav class="nav flex-column">
                    <a class="nav-link" href="/student-management/public/index.php?controller=dashboard&action=index"><i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=student&action=index"><i class="bi bi-person-lines-fill me-2"></i> Students</a>
                    <a class="nav-link active" href="/student-management/public/index.php?controller=academic_year&action=index"><i class="bi bi-calendar me-2"></i> Academic Years</a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=class&action=index"><i class="bi bi-megaphone me-2"></i> Classes</a>
                    <a class="nav-link" href="/student-management/public/index.php?controller=login&action=logout"><i class="bi bi-box-arrow-left me-2"></i> Logout</a>
                </nav>
            </div>
        </nav>

        <div class="main-content p-4 w-100">
            <header class="mb-4">
                <h1><i class="bi bi-pencil me-2"></i> Edit Academic Year</h1>
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

            <form action="/student-management/public/index.php?controller=academic_year&action=update&id=<?= $academic_year['id'] ?>" method="POST">
                <div class="form-section">
                    <h3 class="form-section-title"><i class="bi bi-calendar me-2"></i> Academic Year Information</h3>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="year_name" class="form-label">Academic Year Name *</label>
                            <input type="text" class="form-control" id="year_name" name="year_name"
                                   value="<?= htmlspecialchars($academic_year['year_name']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label for="status" class="form-label">Status *</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="active"  <?= $academic_year['status'] === 'active'   ? 'selected' : '' ?>>Active</option>
                                <option value="inactive"<?= $academic_year['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                            </select>
                            <small class="text-muted">Only one academic year can be active at a time.</small>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="/student-management/public/index.php?controller=academic_year&action=index" class="btn btn-outline-secondary me-md-2">
                        <i class="bi bi-x-lg me-2"></i> Cancel
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save me-2"></i> Update Academic Year
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
