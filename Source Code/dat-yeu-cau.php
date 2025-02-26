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
    $media_files = array(); // Mảng lưu tên các file media
    
    try {
        // Xử lý upload files
        if(!empty($_FILES['media']['name'][0])) {
            $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/assets/media/uploads/yeu-cau-sua-chua/";
            if(!file_exists($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Lấy prefix cho tên file
            $prefix = isset($_SESSION['username']) ? $_SESSION['username'] : 'khach';
            
            // Xử lý từng file
            foreach($_FILES['media']['tmp_name'] as $key => $tmp_name) {
                if($_FILES['media']['error'][$key] == 0) {
                    $file_extension = strtolower(pathinfo($_FILES['media']['name'][$key], PATHINFO_EXTENSION));
                    
                    // Kiểm tra định dạng file
                    $allowed_extensions = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov', 'avi');
                    if(!in_array($file_extension, $allowed_extensions)) {
                        throw new Exception("Định dạng file không được hỗ trợ");
                    }
                    
                    // Kiểm tra kích thước file (20MB)
                    if($_FILES['media']['size'][$key] > 20 * 1024 * 1024) {
                        throw new Exception("File không được lớn hơn 20MB");
                    }
                    
                    // Tạo tên file mới
                    $file_name = $prefix . '-' . date('YmdHis') . '-' . $key . '.' . $file_extension;
                    $target_file = $target_dir . $file_name;
                    
                    // Upload file
                    if(move_uploaded_file($tmp_name, $target_file)) {
                        $media_files[] = $file_name;
                    }
                }
            }
        }
        
        // Chuyển mảng tên file thành chuỗi JSON
        $media_json = !empty($media_files) ? json_encode($media_files) : null;
        
        $stmt = $pdo->prepare("INSERT INTO yeu_cau_sua_chua (user_id, ho_ten, so_dien_thoai, dia_chi, mo_ta, thoi_gian_hen, media) 
                               VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $ho_ten, $so_dien_thoai, $dia_chi, $mo_ta, $thoi_gian_hen, $media_json]);
        
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
                    
                    <form method="POST" action="" enctype="multipart/form-data">
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
                            <label class="form-label">Thời gian hẹn sửa chữa (không bắt buộc)</label>
                            <input type="datetime-local" class="form-control" name="thoi_gian_hen" min="<?php echo date('Y-m-d\TH:i'); ?>">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Hình ảnh hoặc video (không bắt buộc)</label>
                            <input type="file" class="form-control" name="media[]" accept="image/*,video/*" multiple onchange="previewMedia(this);">
                            <small class="text-muted">Có thể chọn nhiều file. Chỉ chấp nhận ảnh và video, tối đa 20MB/file</small>
                            <div id="mediaPreview" class="row g-2 mt-2"></div>
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

<script>
function previewMedia(input) {
    var preview = document.getElementById('mediaPreview');
    preview.innerHTML = '';
    
    if (input.files && input.files.length > 0) {
        for(var i = 0; i < input.files.length; i++) {
            var file = input.files[i];
            var reader = new FileReader();
            
            reader.onload = (function(file) {
                return function(e) {
                    var div = document.createElement('div');
                    div.className = 'col-md-3';
                    
                    if(file.type.startsWith('image/')) {
                        div.innerHTML = `
                            <img src="${e.target.result}" class="img-thumbnail" style="height: 150px; object-fit: cover;">
                        `;
                    } else if(file.type.startsWith('video/')) {
                        div.innerHTML = `
                            <video class="img-thumbnail" style="height: 150px; object-fit: cover;">
                                <source src="${e.target.result}" type="${file.type}">
                            </video>
                        `;
                    }
                    
                    preview.appendChild(div);
                };
            })(file);
            
            reader.readAsDataURL(file);
        }
    }
}
</script>

<?php require_once 'include/layout/footer.php'; ?> 