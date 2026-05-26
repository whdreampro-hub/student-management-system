<div class="container-fluid">
    <div class="row">
        <div class="col-md-2 p-0 sidebar">
            <div class="text-center text-white py-4">
                <i class="fas fa-graduation-cap fa-2x"></i>
                <h4 class="mt-2">SMS</h4>
                <small>School Management</small>
            </div>
            <nav class="nav flex-column">
                <a class="nav-link <?php echo $active_page == 'dashboard' ? 'active' : ''; ?>" href="index.php?controller=dashboard&action=index">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a class="nav-link <?php echo $active_page == 'students' ? 'active' : ''; ?>" href="index.php?controller=student&action=index">
                    <i class="fas fa-users"></i> Students
                </a>
                <a class="nav-link <?php echo $active_page == 'add-student' ? 'active' : ''; ?>" href="index.php?controller=student&action=create">
                    <i class="fas fa-user-plus"></i> Add Student
                </a>
                <a class="nav-link <?php echo $active_page == 'classes' ? 'active' : ''; ?>" href="index.php?controller=class&action=manage">
                    <i class="fas fa-book"></i> Classes
                </a>
                <a class="nav-link <?php echo $active_page == 'academic-years' ? 'active' : ''; ?>" href="index.php?controller=academic_year&action=manage">
                    <i class="fas fa-calendar-alt"></i> Academic Years
                </a>
                <a class="nav-link <?php echo $active_page == 'reports' ? 'active' : ''; ?>" href="index.php?controller=report&action=index">
                    <i class="fas fa-chart-line"></i> Reports
                </a>
                <hr class="bg-light">
                <a class="nav-link" href="index.php?controller=auth&action=logout">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </nav>
        </div>
        <div class="col-md-10 main-content p-4">