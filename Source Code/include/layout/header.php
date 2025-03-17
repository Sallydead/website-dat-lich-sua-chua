<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title . ' - Vũ Chí Linh Computer' : 'Vũ Chí Linh Computer'; ?></title>
    <link rel="shortcut icon" href="/assets/media/favicon.ico" type="image/x-icon">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/assets/css/style.css" rel="stylesheet">
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="/">
                <img src="/assets/media/logo.png" alt="Vũ Chí Linh Computer" width="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <?php if(isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">
                            <i class="fas fa-home"></i> Trang chủ
                        </a>
                    </li>

                    <li class="nav-item">
                        <?php if($_SESSION['role'] == 1): ?>
                        <a class="nav-link" href="/dashboard/index.php">
                            <i class="fas fa-tachometer-alt"></i> Quản trị
                        </a>
                        <?php elseif($_SESSION['role'] == 2): ?>
                        <a class="nav-link" href="/dashboard/index.php">
                            <i class="fas fa-tachometer-alt"></i> Quản lý
                        </a>
                        <?php elseif($_SESSION['role'] == 3): ?>
                        <a class="nav-link" href="/dashboard/index.php">
                            <i class="fas fa-tachometer-alt"></i> Xem phân công
                        </a>
                        <?php endif; ?>
                    </li>
                    
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown"
                            role="button" data-bs-toggle="dropdown">
                            <?php 
                                $stmt = $pdo->prepare("SELECT avatar FROM user_infos WHERE user_id = ?");
                                $stmt->execute([$_SESSION['user_id']]);
                                $avatar = $stmt->fetchColumn();
                                if($avatar): ?>
                            <img src="/assets/media/uploads/avatars/<?php echo $avatar; ?>" class="rounded-circle me-2"
                                width="30" height="30" style="object-fit: cover;width: 30px;height: 30px;">
                            <?php else: ?>
                            <img src="/assets/media/uploads/avatars/default.jpg" class="rounded-circle me-2" width="30"
                                height="30" style="object-fit: cover;width: 30px;height: 30px;">
                            <?php endif; ?>
                            <?php echo $_SESSION['username']; ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="/profile.php">
                                <i class="fas fa-user"></i> Trang cá nhân
                            </a>
                            <a class="dropdown-item" href="/tra-cuu.php">
                                <i class="fas fa-search"></i> Tra cứu yêu cầu
                            </a>
                            <a class="dropdown-item" href="/logout.php">
                                <i class="fas fa-sign-out-alt"></i> Đăng xuất
                            </a>
                        </div>
                    </li>
                    <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/login.php">
                            <i class="fas fa-sign-in-alt"></i> Đăng nhập
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/register.php">
                            <i class="fas fa-user-plus"></i> Đăng ký
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main content -->
    <div class="main-content">
        <div class="container-fluid px-4">