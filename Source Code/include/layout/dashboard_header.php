<?php
// Hàm kiểm tra URL hiện tại
function isCurrentUrl($path) {
    return strpos($_SERVER['REQUEST_URI'], $path) !== false;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - MinhGiangPC.Com' : 'Dashboard - MinhGiangPC.Com'; ?></title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" href="/assets/media/favicon.ico">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link href="/assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <!-- Top Navbar -->
    <nav class="top-navbar d-flex justify-content-center align-items-center">
        <div class="container-fluid px-3">
            <div class="d-flex justify-content-center align-items-center h-100">
                <!-- Toggle Sidebar Button -->
                <button class="btn btn-link text-dark d-lg-none me-2" id="sidebarToggle" type="button">
                    <i class="fas fa-bars"></i>
                </button>

                <!-- Logo -->
                <a href="/" class="navbar-brand d-flex align-items-center">
                    <img src="/assets/media/logo.png" alt="Logo" height="20px" class="ms-2">
                </a>

                <!-- Spacer -->
                <div class="flex-grow-1"></div>

                <!-- User Menu -->
                <div class="dropdown">
                    <button type="button" class="btn btn-link text-dark dropdown-toggle d-flex align-items-center border-0" data-bs-toggle="dropdown">
                        <i class="fas fa-user-circle fa-lg me-2"></i>
                        <span class="d-none d-sm-inline"><?php echo $_SESSION['username']; ?></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item py-2" href="/">
                                <i class="fas fa-home fa-fw me-2"></i>Trang chủ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="#">
                                <i class="fas fa-user fa-fw me-2"></i>Thông tin cá nhân
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item py-2" href="#">
                                <i class="fas fa-key fa-fw me-2"></i>Đổi mật khẩu
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item py-2 text-danger" href="/logout.php" 
                               onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                                <i class="fas fa-sign-out-alt fa-fw me-2"></i>Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Sidebar Backdrop -->
    <div class="sidebar-backdrop"></div>

    <!-- Sidebar -->
    <nav class="sidebar">
        <div class="py-3">
            <!-- Dashboard -->
            <a href="/dashboard" class="nav-link <?php echo isCurrentUrl('/dashboard/index.php') ? 'active' : ''; ?>">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>

            <!-- Quản lý người dùng -->
            <?php if($_SESSION['role'] == 1): ?>
            <a href="/dashboard/users" class="nav-link <?php echo isCurrentUrl('/dashboard/users') ? 'active' : ''; ?>">
                <i class="fas fa-users"></i>
                <span>Quản lý người dùng</span>
            </a>
            <?php endif; ?>

            <!-- Quản lý yêu cầu -->
            <?php if(in_array($_SESSION['role'], [1, 2, 3])): ?>
            <a href="/dashboard/quan-ly-yeu-cau" class="nav-link <?php echo isCurrentUrl('/dashboard/quan-ly-yeu-cau') ? 'active' : ''; ?>">
                <i class="fas fa-tools"></i>
                <span>Quản lý yêu cầu</span>
            </a>
            <?php endif; ?>

            <!-- Báo cáo -->
            <?php if(in_array($_SESSION['role'], [1, 2])): ?>
            <a href="/dashboard/bao-cao" class="nav-link <?php echo isCurrentUrl('/dashboard/bao-cao') ? 'active' : ''; ?>">
                <i class="fas fa-chart-bar"></i>
                <span>Báo cáo thống kê</span>
            </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content"> 