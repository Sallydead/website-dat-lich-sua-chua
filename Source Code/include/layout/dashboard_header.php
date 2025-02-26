<?php
// Hàm kiểm tra URL hiện tại
function isCurrentUrl($path) {
    $current_url = $_SERVER['REQUEST_URI'];
    return strpos($current_url, $path) !== false;
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - MinhGiangPC.Com' : 'Dashboard - MinhGiangPC.Com'; ?></title>
    <link rel="shortcut icon" href="/assets/img/favicon.ico" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/dashboard.css" rel="stylesheet">
</head>
<body>
    <!-- Top Navbar -->
    <nav class="top-navbar">
        <div class="d-flex justify-content-between align-items-center h-100">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark me-3 d-flex" id="sidebarToggle">
                    <i class="fas fa-bars"></i>
                </button>
                <img src="/assets/img/logo.png" alt="MinhGiangPC.Com" width="200">
            </div>
            <div class="d-flex align-items-center">
                <div class="dropdown">
                    <a class="btn btn-link text-dark dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-circle"></i>
                        <?php echo $_SESSION['username']; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="/">
                                <i class="fas fa-home me-2"></i>Trang chủ
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-user me-2"></i>Thông tin cá nhân
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <i class="fas fa-key me-2"></i>Đổi mật khẩu
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item text-danger" href="/logout.php" onclick="return confirm('Bạn có chắc chắn muốn đăng xuất?')">
                                <i class="fas fa-sign-out-alt me-2"></i>Đăng xuất
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>

    <!-- Backdrop for mobile -->
    <div class="sidebar-backdrop"></div>

    <!-- Sidebar -->
    <div class="sidebar">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo isCurrentUrl('/dashboard/index.php') ? 'active' : ''; ?>" href="/dashboard/index.php">
                    <i class="fas fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo isCurrentUrl('/dashboard/users') ? 'active' : ''; ?>" href="/dashboard/users/index.php">
                    <i class="fas fa-users"></i>
                    <span>Quản lý người dùng</span>
                </a>
            </li>
        </ul>
    </div>
            
    <!-- Main content -->
    <div class="main-content"> 