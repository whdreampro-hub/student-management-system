<?php require_once __DIR__ . '/../../../public/init.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Student Management System</title>
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
        .action-buttons .btn {
            margin-right: 0.5rem;
        }
        .photo-thumbnail {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 50%;
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
                    <a class="nav-link active" href="/student-management/public/index.php?controller=student&action=index">
                        <i class="bi bi-person-lines-fill me-2"></i> Students
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
                <h1><i class="bi bi-person-lines-fill me-2"></i> Students Management</h1>
                <a href="/student-management/public/index.php?controller=student&action=create" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i> Add New Student
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

            <!-- Search and Filter -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Search by name or registration number" id="searchInput" value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
                        <button class="btn btn-outline-secondary" type="button" id="searchBtn"><i class="bi bi-search"></i></button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="classFilter">
                        <option value="">All Classes</option>
                        <?php foreach ($classes as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= (!empty($_GET['class_id']) && $_GET['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['class_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="academicYearFilter">
                        <option value="">All Years</option>
                        <?php foreach ($academic_years as $year): ?>
                            <option value="<?= $year['id'] ?>" <?= (!empty($_GET['academic_year_id']) && $_GET['academic_year_id'] == $year['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($year['year_name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 d-grid">
                    <button class="btn btn-outline-primary" id="filterBtn">Filter</button>
                </div>
            </div>

            <!-- Students Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle" id="studentsTable">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Registration Number</th>
                            <th>Name</th>
                            <th>Gender</th>
                            <th>Date of Birth</th>
                            <th>Current Class</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($students)): ?>
                            <?php foreach ($students as $student): ?>
                                <?php
                                // Get current class and academic year for this student
                                $currentClass = '-';
                                $currentHistory = $pdo->prepare("
                                    SELECT c.class_name 
                                    FROM student_class_history sch
                                    JOIN classes c ON sch.class_id = c.id
                                    WHERE sch.student_id = :student_id
                                    AND sch.status = 'active'
                                ");
                                $currentHistory->execute(['student_id' => $student['id']]);
                                $currentRecord = $currentHistory->fetch();
                                if ($currentRecord) {
                                    $currentClass = $currentRecord['class_name'];
                                }
                                ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($student['photo'])): ?>
                                            <img src="/student-management/public/<?= htmlspecialchars($student['photo']) ?>" alt="Student Photo" class="photo-thumbnail">
                                        <?php else: ?>
                                            <div class="photo-thumbnail bg-secondary text-white d-flex align-items-center justify-content-center">
                                                <i class="bi bi-person"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= htmlspecialchars($student['registration_number']) ?></td>
                                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                    <td><?= htmlspecialchars($student['gender']) ?></td>
                                    <td><?= $student['date_of_birth'] ? date('M d, Y', strtotime($student['date_of_birth'])) : '-' ?></td>
                                    <td><?= htmlspecialchars($currentClass) ?></td>
                                    <td class="action-buttons">
                                        <a href="/student-management/public/index.php?controller=student&action=view&id=<?= $student['id'] ?>" class="btn btn-sm btn-outline-info">
                                            <i class="bi bi-eye"></i> View
                                        </a>
                                        <a href="/student-management/public/index.php?controller=student&action=edit&id=<?= $student['id'] ?>" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="/student-management/public/index.php?controller=student&action=delete&id=<?= $student['id'] ?>" method="POST" class="d-inline">
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.');">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">No students found.</td>
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
            $('#studentsTable').DataTable({
                responsive: true,
                pageLength: 25,
                language: {
                    search: "",
                    searchPlaceholder: "Search..."
                }
            });
        });

        // Handle search button
        document.getElementById('searchBtn').addEventListener('click', function() {
            const searchTerm = document.getElementById('searchInput').value.trim();
            window.location.href = '/student-management/public/index.php?controller=student&action=index&search=' + encodeURIComponent(searchTerm);
        });

        // Handle filter button
        document.getElementById('filterBtn').addEventListener('click', function() {
            const classId = document.getElementById('classFilter').value;
            const yearId = document.getElementById('academicYearFilter').value;
            let url = '/student-management/public/index.php?controller=student&action=index';
            const params = [];
            if (classId) params.push('class_id=' + classId);
            if (yearId) params.push('academic_year_id=' + yearId);
            if (params.length > 0) {
                url += '?' + params.join('&');
            }
            window.location.href = url;
        });

        // Allow pressing Enter in search input
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                document.getElementById('searchBtn').click();
            }
        });
    </script>
</body>
</html>