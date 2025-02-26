<?php
session_start();
require_once '../../include/function.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../login.php");
    exit();
}

$page_title = "Thêm người dùng mới";

if(isset($_POST['create'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    $role = $_POST['role'];
    
    // Thông tin cá nhân
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    
    // Xử lý upload avatar
    $avatar = '';
    if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/media/uploads/avatars/";
        if(!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        
        $file_extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
        $file_name = generateAvatarFilename($user_id, $file_extension);
        $target_file = $target_dir . $file_name;
        
        if(move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
            $avatar = $file_name; // Chỉ lưu tên file
        }
    }
    
    try {
        $pdo->beginTransaction();
        
        // Kiểm tra username đã tồn tại
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if($stmt->fetchColumn() > 0) {
            throw new Exception("Tên đăng nhập đã tồn tại");
        }
        
        // Thêm user
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email, role) VALUES (?, ?, ?, ?)");
        $stmt->execute([$username, $password, $email, $role]);
        $user_id = $pdo->lastInsertId();
        
        // Thêm thông tin cá nhân
        $stmt = $pdo->prepare("INSERT INTO user_infos (user_id, fullname, phone, address, gender, avatar) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $fullname, $phone, $address, $gender, $avatar]);
        
        $pdo->commit();
        $_SESSION['success'] = "Thêm người dùng thành công!";
        header("Location: index.php");
        exit();
        
    } catch(Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}

require_once '../../include/layout/dashboard_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Thêm người dùng mới</h3>
                    <a href="index.php" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Quay lại
                    </a>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Thông tin tài khoản</h4>
                                <hr>
                                
                                <div class="mb-3">
                                    <label class="form-label">Tên đăng nhập</label>
                                    <input type="text" class="form-control" name="username" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu</label>
                                    <input type="password" class="form-control" name="password" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Quyền</label>
                                    <select name="role" class="form-select" required>
                                        <option value="0">Khách hàng</option>
                                        <option value="1">Admin</option>
                                        <option value="2">CSKH</option>
                                        <option value="3">Kỹ thuật viên</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Thông tin cá nhân</h4>
                                <hr>
                                
                                <div class="mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" name="fullname" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="phone" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" name="address" rows="3" required></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="nam">Nam</option>
                                        <option value="nu">Nữ</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <input type="file" class="form-control" name="avatar" accept="image/*" onchange="previewImage(this);">
                                    <img id="preview" class="mt-2 rounded" style="max-width: 150px; display: none;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-3">
                            <button type="submit" name="create" class="btn btn-primary">
                                <i class="fas fa-save"></i> Lưu
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    var preview = document.getElementById('preview');
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once '../../include/layout/dashboard_footer.php'; ?>

