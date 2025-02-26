<?php
session_start();
require_once 'include/function.php';

$page_title = "Đặt yêu cầu sửa chữa";

// Nếu đã đăng nhập thì lấy thông tin user
if(isset($_SESSION['user_id'])) {
    $stmt = $pdo->prepare("SELECT u.*, ui.* FROM users u LEFT JOIN user_infos ui ON u.id = ui.user_id WHERE u.id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
}

if(isset($_POST['submit'])) {
    $ho_ten = $_POST['ho_ten'];
    $so_dien_thoai = $_POST['so_dien_thoai'];
    $dia_chi = $_POST['dia_chi'];
    $mo_ta = $_POST['mo_ta'];
    $thoi_gian_hen = !empty($_POST['thoi_gian_hen']) ? $_POST['thoi_gian_hen'] : null;
    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    
    try {
        $stmt = $pdo->prepare("INSERT INTO yeu_cau_sua_chua (user_id, ho_ten, so_dien_thoai, dia_chi, mo_ta, thoi_gian_hen) 
                               VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $ho_ten, $so_dien_thoai, $dia_chi, $mo_ta, $thoi_gian_hen]);
        
        // Lấy mã đơn vừa tạo
        $ma_don = $pdo->query("SELECT ma_don FROM yeu_cau_sua_chua WHERE id = LAST_INSERT_ID()")->fetchColumn();
        
        $_SESSION['success'] = "Đặt yêu cầu thành công! Mã đơn của bạn là: " . $ma_don;
        header("Location: tra-cuu.php?ma_don=" . $ma_don);
        exit();
        
    } catch(Exception $e) {
        $error = "Có lỗi xảy ra: " . $e->getMessage();
    }
}

require_once 'include/layout/header.php';
?>

<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="text-center mb-4">Đặt yêu cầu sửa chữa</h2>
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Họ và tên</label>
                                <input type="text" class="form-control" name="ho_ten" value="<?php echo isset($user) ? $user['fullname'] : ''; ?>" required>
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" name="so_dien_thoai" value="<?php echo isset($user) ? $user['phone'] : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Địa chỉ</label>
                            <textarea class="form-control" name="dia_chi" rows="2" required><?php echo isset($user) ? $user['address'] : ''; ?></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Mô tả vấn đề cần sửa</label>
                            <textarea class="form-control" name="mo_ta" rows="4" required placeholder="Vui lòng mô tả chi tiết vấn đề bạn gặp phải..."></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Thời gian hẹn (không bắt buộc)</label>
                            <input type="datetime-local" class="form-control" name="thoi_gian_hen" min="<?php echo date('Y-m-d\TH:i'); ?>">
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" name="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Gửi yêu cầu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'include/layout/footer.php'; ?> 