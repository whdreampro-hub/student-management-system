<?php
$currentPage = $_GET['page'] ?? 'dashboard';
$adminName   = $_SESSION['admin_name'] ?? 'Admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $pageTitle ?? 'Dashboard' ?> — EduAdmin SMS</title>
<meta name="description" content="Student Management System Administration Panel">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="/student-management/public/assets/css/style.css" rel="stylesheet">
</head>
<body class="app-body">

<!-- Mobile Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="logo-icon-sm"><i class="bi bi-mortarboard-fill"></i></div>
            <div>
                <span class="logo-title">EduAdmin</span>
                <span class="logo-sub">SMS v1.0</span>
            </div>
        </div>
        <button class="sidebar-close d-lg-none" onclick="closeSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Main</div>
        <a href="?page=dashboard" class="nav-item <?= $currentPage==='dashboard'?'active':'' ?>">
            <i class="bi bi-speedometer2"></i><span>Dashboard</span>
        </a>

        <div class="nav-section-label">Students</div>
        <a href="?page=students" class="nav-item <?= $currentPage==='students'?'active':'' ?>">
            <i class="bi bi-people-fill"></i><span>All Students</span>
        </a>
        <a href="?page=students&action=create" class="nav-item <?= ($currentPage==='students'&&($_GET['action']??'')=='create')?'active':'' ?>">
            <i class="bi bi-person-plus-fill"></i><span>Add Student</span>
        </a>
        <a href="?page=students&action=trash" class="nav-item <?= ($currentPage==='students'&&($_GET['action']??'')=='trash')?'active':'' ?>">
            <i class="bi bi-trash3-fill"></i><span>Trash</span>
        </a>

        <div class="nav-section-label">Academic</div>
        <a href="?page=classes" class="nav-item <?= $currentPage==='classes'?'active':'' ?>">
            <i class="bi bi-building-fill"></i><span>Classes</span>
        </a>
        <a href="?page=academic_years" class="nav-item <?= $currentPage==='academic_years'?'active':'' ?>">
            <i class="bi bi-calendar-range-fill"></i><span>Academic Years</span>
        </a>
        <a href="?page=history" class="nav-item <?= $currentPage==='history'?'active':'' ?>">
            <i class="bi bi-clock-history"></i><span>Movement History</span>
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-badge">
            <div class="admin-avatar"><?= strtoupper(substr($adminName,0,1)) ?></div>
            <div class="admin-info">
                <span class="admin-name"><?= htmlspecialchars($adminName) ?></span>
                <span class="admin-role">Administrator</span>
            </div>
            <a href="?page=logout" class="logout-btn" title="Logout">
                <i class="bi bi-box-arrow-right"></i>
            </a>
        </div>
    </div>
</aside>

<!-- Main Content -->
<div class="main-wrapper">
    <!-- Top Navbar -->
    <header class="topbar">
        <button class="topbar-toggle d-lg-none" onclick="openSidebar()">
            <i class="bi bi-list"></i>
        </button>
        <div class="topbar-title">
            <h2><?= $pageTitle ?? 'Dashboard' ?></h2>
        </div>
        <div class="topbar-actions">
            <span class="topbar-date"><i class="bi bi-calendar3 me-1"></i><?= date('M d, Y') ?></span>
            <a href="?page=logout" class="btn btn-sm btn-outline-danger ms-2">
                <i class="bi bi-box-arrow-right me-1"></i>Logout
            </a>
        </div>
    </header>
    <main class="content-area">
