<?php
session_start();
require_once '../../include/function.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../login.php");
    exit();
}

$page_title = "Chỉnh sửa người dùng";

// Lấy thông tin user
if(!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT u.*, ui.* FROM users u 
                       LEFT JOIN user_infos ui ON u.id = ui.user_id 
                       WHERE u.id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if(!$user) {
    $_SESSION['error'] = "Không tìm thấy người dùng!";
    header("Location: index.php");
    exit();
}

if(isset($_POST['update'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $gender = $_POST['gender'];
    
    try {
        $pdo->beginTransaction();
        
        // Kiểm tra username đã tồn tại (nếu thay đổi username)
        if($username != $user['username']) {
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? AND id != ?");
            $stmt->execute([$username, $user_id]);
            if($stmt->fetchColumn() > 0) {
                throw new Exception("Tên đăng nhập đã tồn tại");
            }
        }
        
        // Cập nhật mật khẩu nếu có nhập mới
        if(!empty($_POST['password'])) {
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET username = ?, password = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $password, $email, $role, $user_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = ?, email = ?, role = ? WHERE id = ?");
            $stmt->execute([$username, $email, $role, $user_id]);
        }
        
        // Xử lý upload avatar mới
        $avatar = $user['avatar']; // Giữ nguyên avatar cũ nếu không upload mới
        if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/img/uploads/avatars/";
            if(!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            $file_extension = strtolower(pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION));
            $file_name = generateAvatarFilename($user_id, $file_extension);
            $target_file = $target_dir . $file_name;
            
            if(move_uploaded_file($_FILES['avatar']['tmp_name'], $target_file)) {
                // Xóa avatar cũ nếu có
                if($avatar && file_exists($_SERVER['DOCUMENT_ROOT'] . "/assets/img/uploads/avatars/" . $avatar)) {
                    unlink($_SERVER['DOCUMENT_ROOT'] . "/assets/img/uploads/avatars/" . $avatar);
                }
                $avatar = $file_name; // Chỉ lưu tên file
            }
        }
        
        // Cập nhật thông tin cá nhân
        $stmt = $pdo->prepare("UPDATE user_infos SET fullname = ?, phone = ?, address = ?, gender = ?, avatar = ? WHERE user_id = ?");
        $stmt->execute([$fullname, $phone, $address, $gender, $avatar, $user_id]);
        
        $pdo->commit();
        $_SESSION['success'] = "Cập nhật thông tin thành công!";
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
                    <h3 class="card-title">Chỉnh sửa người dùng</h3>
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
                                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Mật khẩu mới (để trống nếu không đổi)</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Quyền</label>
                                    <select name="role" class="form-select" required <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                        <option value="0" <?php echo $user['role'] == 0 ? 'selected' : ''; ?>>Khách hàng</option>
                                        <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                        <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>CSKH</option>
                                        <option value="3" <?php echo $user['role'] == 3 ? 'selected' : ''; ?>>Kỹ thuật viên</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <h4>Thông tin cá nhân</h4>
                                <hr>
                                
                                <div class="mb-3">
                                    <label class="form-label">Họ và tên</label>
                                    <input type="text" class="form-control" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Số điện thoại</label>
                                    <input type="tel" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Địa chỉ</label>
                                    <textarea class="form-control" name="address" rows="3" required><?php echo htmlspecialchars($user['address']); ?></textarea>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Giới tính</label>
                                    <select name="gender" class="form-select" required>
                                        <option value="">Chọn giới tính</option>
                                        <option value="nam" <?php echo $user['gender'] == 'nam' ? 'selected' : ''; ?>>Nam</option>
                                        <option value="nu" <?php echo $user['gender'] == 'nu' ? 'selected' : ''; ?>>Nữ</option>
                                    </select>
                                </div>
                                
                                <div class="mb-3">
                                    <label class="form-label">Ảnh đại diện</label>
                                    <?php if($user['avatar']): ?>
                                        <div class="mb-2">
                                            <img src="/assets/img/uploads/avatars/<?php echo $user['avatar']; ?>" class="rounded" style="max-width: 150px;">
                                        </div>
                                    <?php endif; ?>
                                    <input type="file" class="form-control" name="avatar" accept="image/*" onchange="previewImage(this);">
                                    <img id="preview" class="mt-2 rounded" style="max-width: 150px; display: none;">
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-end mt-3">
                            <button type="submit" name="update" class="btn btn-primary">
                                <i class="fas fa-save"></i> Cập nhật
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