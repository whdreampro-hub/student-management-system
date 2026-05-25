<?php require_once __DIR__ . '/../../../public/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classes - Student Management System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- DataTables -->
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .sidebar {
            background-color: #0d6efd;
            color: white;
            min-height: 100vh;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
        }
        .sidebar a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar .active {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .main-content {
            margin-left: 250px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
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
                    <a class="nav-link" href="/student-management/public/index.php?controller=academic_year&action=index">
                        <i class="bi bi-calendar me-2"></i> Academic Years
                    </a>
                    <a class="nav-link active" href="/student-management/public/index.php?controller=class&action=index">
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
                <h1><i class="bi bi-megaphone me-2"></i> Classes Management</h1>
                <a href="/student-management/public/index.php?controller=class&action=create" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i> Add New Class
                </a>
            </header>

            <?php if (getFlash('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars(getFlash('success')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>
            <?php if (getFlash('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars(getFlash('error')) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Classes Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="classesTable">
                    <thead>
                        <tr>
                            <th>Class Name</th>
                            <th>Level</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($classes)): ?>
                            <?php foreach ($classes as $class): ?>
                                <tr>
                                    <td><?= htmlspecialchars($class['class_name']) ?></td>
                                    <td><?= htmlspecialchars($class['level']) ?></td>
                                    <td><?= $class['created_at'] ? date('M d, Y', strtotime($class['created_at'])) : '-' ?></td>
                                    <td>
                                        <a href="/student-management/public/index.php?controller=class&action=edit&id=<?= $class['id'] ?>" class="btn btn-sm btn-outline-primary me-1">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="/student-management/public/index.php?controller=class&action=delete&id=<?= $class['id'] ?>" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this class?');">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center py-4">No classes found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#classesTable').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                }
            });
        });
    </script>
</body>
</html>