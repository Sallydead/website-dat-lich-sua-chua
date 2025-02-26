<?php
session_start();
require_once 'include/function.php';

// Kiểm tra đăng nhập
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Lấy thông tin user
$stmt = $pdo->prepare("SELECT u.*, ui.* FROM users u 
                       LEFT JOIN user_infos ui ON u.id = ui.user_id 
                       WHERE u.id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

$page_title = "Trang cá nhân";
require_once 'include/layout/header.php';
?>

<div class="row">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <?php if($user['avatar']): ?>
                    <img src="/assets/img/uploads/avatars/<?php echo $user['avatar']; ?>" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <?php else: ?>
                    <img src="/assets/img/uploads/avatars/default.png" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                <?php endif; ?>
                <h4><?php echo $user['fullname']; ?></h4>
                <p class="text-muted"><?php echo $user['email']; ?></p>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <h3 class="card-title">Thông tin cá nhân</h3>
                <hr>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Họ và tên</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $user['fullname']; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Email</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $user['email']; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Số điện thoại</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $user['phone']; ?>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Địa chỉ</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $user['address']; ?>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Giới tính</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <?php echo $user['gender'] == 'nam' ? 'Nam' : 'Nữ'; ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="card-title mb-0">Yêu cầu sửa chữa của bạn</h3>
                    <a href="dat-yeu-cau.php" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tạo yêu cầu mới
                    </a>
                </div>
                
                <?php
                // Lấy danh sách yêu cầu của user
                $stmt = $pdo->prepare("SELECT * FROM yeu_cau_sua_chua WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
                $stmt->execute([$_SESSION['user_id']]);
                $yeu_cau_list = $stmt->fetchAll();
                ?>
                
                <?php if(!empty($yeu_cau_list)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã đơn</th>
                                    <th>Thời gian tạo</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($yeu_cau_list as $yc): ?>
                                <tr>
                                    <td><?php echo $yc['ma_don']; ?></td>
                                    <td><?php echo date('d/m/Y H:i', strtotime($yc['created_at'])); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo get_status_color($yc['trang_thai']); ?>">
                                            <?php echo get_status_text($yc['trang_thai']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <a href="tra-cuu.php?ma_don=<?php echo $yc['ma_don']; ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if(count($yeu_cau_list) >= 5): ?>
                        <div class="text-center mt-3">
                            <a href="tra-cuu.php" class="btn btn-link">Xem tất cả yêu cầu</a>
                        </div>
                    <?php endif; ?>
                <?php else: ?>
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-tools fa-3x mb-3"></i>
                        <p>Bạn chưa có yêu cầu sửa chữa nào</p>
                        <a href="dat-yeu-cau.php" class="btn btn-primary mt-3">
                            <i class="fas fa-plus me-2"></i>Tạo yêu cầu mới
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once 'include/layout/footer.php'; ?>
