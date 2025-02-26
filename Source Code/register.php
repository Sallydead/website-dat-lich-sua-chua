<?php
session_start();
require_once 'include/function.php';

$page_title = "Đăng ký";

if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    
    // Thông tin cá nhân mới
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
    
    // Kiểm tra username đã tồn tại
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);
    if($stmt->fetchColumn() > 0) {
        $error = "Tên đăng nhập đã tồn tại";
    } else {
        try {
            $pdo->beginTransaction();
            
            // Thêm user
            $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
            $stmt->execute([$username, $password, $email]);
            $user_id = $pdo->lastInsertId();
            
            // Thêm thông tin cá nhân
            $stmt = $pdo->prepare("INSERT INTO user_infos (user_id, fullname, phone, address, gender, avatar) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->execute([$user_id, $fullname, $phone, $address, $gender, $avatar]);
            
            $pdo->commit();
            header("Location: login.php");
            exit();
            
        } catch(Exception $e) {
            $pdo->rollBack();
            $error = "Có lỗi xảy ra: " . $e->getMessage();
        }
    }
}

require_once 'include/layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center mb-4">Đăng ký tài khoản</h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Họ và tên</label>
                        <input type="text" class="form-control" name="fullname" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Giới tính</label>
                            <select class="form-select" name="gender" required>
                                <option value="">Chọn giới tính</option>
                                <option value="nam">Nam</option>
                                <option value="nu">Nữ</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Địa chỉ</label>
                        <textarea class="form-control" name="address" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Ảnh đại diện</label>
                        <input type="file" class="form-control" name="avatar" accept="image/*" onchange="previewImage(this);">
                        <img id="preview" class="mt-2 rounded" style="max-width: 150px; display: none;">
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="register" class="btn btn-primary">Đăng ký</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-0">Đã có tài khoản? <a href="login.php">Đăng nhập</a></p>
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

<?php require_once 'include/layout/footer.php'; ?> 