<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students | Student Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
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
        .sidebar .nav-link:hover, .sidebar .nav-link.active {
            background: rgba(255,255,255,0.2);
        }
        .sidebar .nav-link i {
            margin-right: 10px;
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .top-bar {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .search-box {
            position: relative;
        }
        .search-box input {
            padding-right: 40px;
        }
        .search-box i {
            position: absolute;
            right: 15px;
            top: 12px;
            color: #999;
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
                    <a class="nav-link active" href="index.php?controller=student&action=index">
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
                </nav>
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content p-4">
                <div class="top-bar p-3 mb-4 d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">Students Management</h4>
                    <a href="index.php?controller=student&action=create" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Student
                    </a>
                </div>
                
                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form method="GET" action="" class="row">
                            <input type="hidden" name="controller" value="student">
                            <input type="hidden" name="action" value="index">
                            <div class="col-md-4">
                                <label>Search</label>
                                <div class="search-box">
                                    <input type="text" name="search" class="form-control" placeholder="Name or Registration Number..." value="<?php echo htmlspecialchars($search); ?>">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label>Filter by Class</label>
                                <select name="class_id" class="form-control">
                                    <option value="">All Classes</option>
                                    <?php while($class = $classes->fetch(PDO::FETCH_ASSOC)): ?>
                                    <option value="<?php echo $class['id']; ?>" <?php echo $class_id == $class['id'] ? 'selected' : ''; ?>>
                                        <?php echo $class['class_name']; ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label>&nbsp;</label>
                                <button type="submit" class="btn btn-primary w-100">Filter</button>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Students Table -->
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Reg Number</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Gender</th>
                                        <th>Current Class</th>
                                        <th>Parent Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                    <tr>
                                        <td><?php echo $row['registration_number']; ?></td>
                                        <td>
                                            <img src="assets/uploads/students/<?php echo $row['photo']; ?>" 
                                                 alt="Photo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                                        </td>
                                        <td><?php echo $row['first_name'] . ' ' . $row['last_name']; ?></td>
                                        <td><?php echo $row['gender']; ?></td>
                                        <td>
                                            <span class="badge bg-info">
                                                <?php echo $row['class_name'] ?? 'Not Assigned'; ?>
                                            </span>
                                        </td>
                                        <td><?php echo $row['parent_phone']; ?></td>
                                        <td>
                                            <a href="index.php?controller=student&action=profile&id=<?php echo $row['id']; ?>" 
                                               class="btn btn-sm btn-info" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="index.php?controller=student&action=edit&id=<?php echo $row['id']; ?>" 
                                               class="btn btn-sm btn-warning" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <button onclick="deleteStudent(<?php echo $row['id']; ?>)" 
                                                    class="btn btn-sm btn-danger" title="Delete">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <?php if($totalPages > 1): ?>
                        <nav>
                            <ul class="pagination justify-content-center">
                                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?controller=student&action=index&page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&class_id=<?php echo $class_id; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                                <?php endfor; ?>
                            </ul>
                        </nav>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function deleteStudent(id) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Create a form to submit delete request
                    var form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'index.php?controller=student&action=delete&id=' + id;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
        
        <?php if(isset($_SESSION['success'])): ?>
        Swal.fire('Success!', '<?php echo $_SESSION['success']; ?>', 'success');
        <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['error'])): ?>
        Swal.fire('Error!', '<?php echo $_SESSION['error']; ?>', 'error');
        <?php unset($_SESSION['error']); ?>
        <?php endif; ?>
    </script>
</body>
</html>