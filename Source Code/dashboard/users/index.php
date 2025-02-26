<?php
session_start();
require_once '../../include/function.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../../login.php");
    exit();
}

$page_title = "Quản lý người dùng";

// Xử lý thay đổi trạng thái người dùng
if(isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'];
    $new_role = $_POST['role'];
    
    $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
    $stmt->execute([$new_role, $user_id]);
    
    $_SESSION['success'] = "Đã cập nhật quyền thành công!";
    header("Location: index.php");
    exit();
}

// Xử lý xóa người dùng
if(isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    
    try {
        $pdo->beginTransaction();
        
        // Xóa thông tin cá nhân
        $stmt = $pdo->prepare("DELETE FROM user_infos WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        // Xóa tài khoản
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        
        $pdo->commit();
        $_SESSION['success'] = "Đã xóa người dùng thành công!";
    } catch(Exception $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
    }
    
    header("Location: index.php");
    exit();
}

// Tìm kiếm
$search = isset($_GET['search']) ? $_GET['search'] : '';
$role_filter = isset($_GET['role']) ? $_GET['role'] : '';

// Lấy danh sách người dùng với phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Xây dựng câu query tìm kiếm
$where = "WHERE u.role > 0"; // Mặc định chỉ lấy nhân viên
$params = [];

if($search) {
    $where .= " AND (u.username LIKE ? OR ui.fullname LIKE ? OR u.email LIKE ? OR ui.phone LIKE ?)";
    $search_param = "%$search%";
    $params = array_merge($params, [$search_param, $search_param, $search_param, $search_param]);
}

if($role_filter !== '') {
    $where = "WHERE u.role = ?"; // Bỏ điều kiện role > 0 nếu có filter
    $params = [$role_filter];
}

// Đếm tổng số bản ghi
$stmt = $pdo->prepare("SELECT COUNT(*) FROM users u LEFT JOIN user_infos ui ON u.id = ui.user_id $where");
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Query lấy danh sách
$sql = "SELECT u.*, ui.fullname, ui.phone, ui.address, ui.gender, ui.avatar 
        FROM users u 
        LEFT JOIN user_infos ui ON u.id = ui.user_id 
        $where 
        ORDER BY u.id DESC 
        LIMIT $limit OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$users = $stmt->fetchAll();

require_once '../../include/layout/dashboard_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Danh sách người dùng</h3>
                    <a href="create.php" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Thêm mới
                    </a>
                </div>
                <div class="card-body">
                    <?php if(isset($_SESSION['success'])): ?>
                        <div class="alert alert-success">
                            <?php 
                            echo $_SESSION['success'];
                            unset($_SESSION['success']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if(isset($_SESSION['error'])): ?>
                        <div class="alert alert-danger">
                            <?php 
                            echo $_SESSION['error'];
                            unset($_SESSION['error']);
                            ?>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Form tìm kiếm -->
                    <form method="GET" action="" class="mb-4">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <input type="text" class="form-control" name="search" placeholder="Tìm kiếm..." value="<?php echo htmlspecialchars($search); ?>">
                                    <button class="btn btn-outline-secondary" type="submit">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <select name="role" class="form-select" onchange="this.form.submit()">
                                    <option value="">Tất cả quyền</option>
                                    <option value="0" <?php echo $role_filter === '0' ? 'selected' : ''; ?>>Khách hàng</option>
                                    <option value="1" <?php echo $role_filter === '1' ? 'selected' : ''; ?>>Admin</option>
                                    <option value="2" <?php echo $role_filter === '2' ? 'selected' : ''; ?>>CSKH</option>
                                    <option value="3" <?php echo $role_filter === '3' ? 'selected' : ''; ?>>Kỹ thuật viên</option>
                                </select>
                            </div>
                        </div>
                    </form>
                    
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên đăng nhập</th>
                                    <th>Họ tên</th>
                                    <th>Email</th>
                                    <th>Số điện thoại</th>
                                    <th>Quyền</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user): ?>
                                <tr>
                                    <td><?php echo $user['id']; ?></td>
                                    <td><?php echo $user['username']; ?></td>
                                    <td><?php echo $user['fullname']; ?></td>
                                    <td><?php echo $user['email']; ?></td>
                                    <td><?php echo $user['phone']; ?></td>
                                    <td>
                                        <form method="POST" action="" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <select name="role" class="form-select form-select-sm" onchange="this.form.submit()" <?php echo $user['id'] == $_SESSION['user_id'] ? 'disabled' : ''; ?>>
                                                <option value="0" <?php echo $user['role'] == 0 ? 'selected' : ''; ?>>Khách hàng</option>
                                                <option value="1" <?php echo $user['role'] == 1 ? 'selected' : ''; ?>>Admin</option>
                                                <option value="2" <?php echo $user['role'] == 2 ? 'selected' : ''; ?>>CSKH</option>
                                                <option value="3" <?php echo $user['role'] == 3 ? 'selected' : ''; ?>>Kỹ thuật viên</option>
                                            </select>
                                            <input type="hidden" name="update_role">
                                        </form>
                                    </td>
                                    <td>
                                        <a href="view.php?id=<?php echo $user['id']; ?>" class="btn btn-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="edit.php?id=<?php echo $user['id']; ?>" class="btn btn-warning btn-sm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if($user['id'] != $_SESSION['user_id']): ?>
                                            <form method="POST" action="" class="d-inline" onsubmit="return confirm('Bạn có chắc chắn muốn xóa người dùng này?');">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <button type="submit" name="delete_user" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($total_pages > 1): ?>
                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination justify-content-center">
                            <?php for($i = 1; $i <= $total_pages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&role=<?php echo $role_filter; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php endfor; ?>
                        </ul>
                    </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../../include/layout/dashboard_footer.php'; ?>
