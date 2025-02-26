<?php
session_start();
require_once 'include/function.php';

$page_title = "Tra cứu yêu cầu";

// Nếu đã đăng nhập, lấy danh sách yêu cầu của user
if(isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM yeu_cau_sua_chua WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $yeu_cau_list = $stmt->fetchAll();
}

// Nếu có mã đơn, hiển thị chi tiết
if(isset($_GET['ma_don'])) {
    $stmt = $pdo->prepare("SELECT yc.*, u.username as xu_ly_vien 
                          FROM yeu_cau_sua_chua yc 
                          LEFT JOIN users u ON yc.nguoi_xu_ly = u.id 
                          WHERE yc.ma_don = ?");
    $stmt->execute([$_GET['ma_don']]);
    $yeu_cau = $stmt->fetch();
    
    if(!$yeu_cau) {
        $error = "Không tìm thấy yêu cầu với mã đơn này!";
    }
}

require_once 'include/layout/header.php';
?>

<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-center mb-4">Tra cứu yêu cầu sửa chữa</h2>
                    
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <!-- Form tra cứu -->
                    <?php if(!isset($yeu_cau)): ?>
                        <form method="GET" action="" class="mb-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="ma_don" placeholder="Nhập mã đơn của bạn..." required>
                                <button class="btn btn-primary" type="submit">
                                    <i class="fas fa-search me-2"></i>Tra cứu
                                </button>
                            </div>
                        </form>
                    <?php endif; ?>
                    
                    <!-- Hiển thị chi tiết yêu cầu -->
                    <?php if(isset($yeu_cau)): ?>
                        <div class="border rounded p-3 mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h4 class="mb-0">Mã đơn: <?php echo $yeu_cau['id']; ?></h4>
                                <span class="badge bg-<?php echo get_status_color($yeu_cau['trang_thai']); ?>">
                                    <?php echo get_status_text($yeu_cau['trang_thai']); ?>
                                </span>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Họ tên:</strong> <?php echo $yeu_cau['ho_ten']; ?></p>
                                    <p><strong>Số điện thoại:</strong> <?php echo $yeu_cau['so_dien_thoai']; ?></p>
                                    <p><strong>Địa chỉ:</strong> <?php echo $yeu_cau['dia_chi']; ?></p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong>Thời gian tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($yeu_cau['created_at'])); ?></p>
                                    <?php if($yeu_cau['thoi_gian_hen']): ?>
                                        <p><strong>Thời gian hẹn:</strong> <?php echo date('d/m/Y H:i', strtotime($yeu_cau['thoi_gian_hen'])); ?></p>
                                    <?php endif; ?>
                                    <?php if($yeu_cau['xu_ly_vien']): ?>
                                        <p><strong>Người phụ trách:</strong> <?php echo $yeu_cau['xu_ly_vien']; ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <p><strong>Mô tả vấn đề:</strong></p>
                                <p><?php echo nl2br($yeu_cau['mo_ta']); ?></p>
                            </div>
                            
                            <?php if($yeu_cau['ghi_chu']): ?>
                                <div class="mt-3">
                                    <p><strong>Ghi chú:</strong></p>
                                    <p><?php echo nl2br($yeu_cau['ghi_chu']); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <div class="mt-4">
                                <a href="tra-cuu.php" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left me-2"></i>Quay lại
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Danh sách yêu cầu của user đã đăng nhập -->
                    <?php if(isset($_SESSION['user_id']) && !empty($yeu_cau_list)): ?>
                        <h4 class="mb-3">Yêu cầu của bạn</h4>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Mã đơn</th>
                                        <th>Thời gian tạo</th>
                                        <th>Trạng thái</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($yeu_cau_list as $yc): ?>
                                        <tr>
                                            <td><?php echo $yc['id']; ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($yc['created_at'])); ?></td>
                                            <td>
                                                <span class="badge bg-<?php echo get_status_color($yc['trang_thai']); ?>">
                                                    <?php echo get_status_text($yc['trang_thai']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?ma_don=<?php echo $yc['id']; ?>" class="btn btn-sm btn-info">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'include/layout/footer.php'; ?> 