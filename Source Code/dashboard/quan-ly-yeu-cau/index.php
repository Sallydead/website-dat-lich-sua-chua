<?php
session_start();
require_once '../../include/function.php';

// Kiểm tra đăng nhập và quyền truy cập
if(!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], [1, 2, 3])) {
    header("Location: ../../login.php");
    exit();
}

$page_title = "Quản lý yêu cầu sửa chữa";

// Xử lý cập nhật trạng thái và phân công
if(isset($_POST['update_status'])) {
    $yeu_cau_id = $_POST['yeu_cau_id'];
    $trang_thai = $_POST['trang_thai'];
    $ghi_chu = $_POST['ghi_chu'];
    $chi_phi = isset($_POST['chi_phi']) ? $_POST['chi_phi'] : null;
    
    // Chỉ admin và CSKH được phân công
    if($_SESSION['role'] <= 2 && isset($_POST['nguoi_xu_ly'])) {
        $nguoi_xu_ly = $_POST['nguoi_xu_ly'];
    } else {
        // Nếu là KTV thì tự động gán người xử lý là chính mình
        $nguoi_xu_ly = $_SESSION['user_id'];
    }
    
    try {
        // Cập nhật thông tin yêu cầu
        $sql = "UPDATE yeu_cau_sua_chua SET 
                trang_thai = ?, 
                ghi_chu = ?, 
                nguoi_xu_ly = ?,
                chi_phi = ?
                WHERE id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$trang_thai, $ghi_chu, $nguoi_xu_ly, $chi_phi, $yeu_cau_id]);
        
        $_SESSION['success'] = "Đã cập nhật yêu cầu thành công!";
    } catch(Exception $e) {
        $_SESSION['error'] = "Có lỗi xảy ra: " . $e->getMessage();
    }
    
    header("Location: index.php");
    exit();
}

// Lấy danh sách người có thể xử lý (admin, CSKH, KTV)
$stmt = $pdo->prepare("SELECT u.id, u.username, u.role, ui.fullname 
                       FROM users u 
                       LEFT JOIN user_infos ui ON u.id = ui.user_id 
                       WHERE u.role IN (1,2,3)  # Lấy admin, CSKH và KTV
                       ORDER BY u.role ASC");  # Sắp xếp theo role để nhóm các nhóm người dùng
$stmt->execute();
$nhan_vien_list = $stmt->fetchAll();

// Mảng tên các role để hiển thị
$role_names = [
    1 => 'Admin',
    2 => 'CSKH', 
    3 => 'Kỹ thuật viên'
];

// Xử lý tìm kiếm và lọc
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status_filter = isset($_GET['status']) ? $_GET['status'] : '';
$date_from = isset($_GET['date_from']) ? $_GET['date_from'] : '';
$date_to = isset($_GET['date_to']) ? $_GET['date_to'] : '';

// Phân trang
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

$where = "WHERE 1=1";
$params = [];

// Nếu là KTV thì chỉ xem yêu cầu được phân công cho mình
if($_SESSION['role'] == 3) {
    $where .= " AND yc.nguoi_xu_ly = ?";
    $params[] = $_SESSION['user_id'];
}

if($search) {
    $where .= " AND (yc.ma_don LIKE ? OR yc.ho_ten LIKE ? OR yc.so_dien_thoai LIKE ?)";
    $params = array_merge($params, ["%$search%", "%$search%", "%$search%"]);
}

if($status_filter !== '') {
    $where .= " AND yc.trang_thai = ?";
    $params[] = $status_filter;
}

if($date_from) {
    $where .= " AND DATE(yc.created_at) >= ?";
    $params[] = $date_from;
}

if($date_to) {
    $where .= " AND DATE(yc.created_at) <= ?";
    $params[] = $date_to;
}

// Lấy tổng số yêu cầu
$stmt = $pdo->prepare("
    SELECT COUNT(*) 
    FROM yeu_cau_sua_chua yc
    $where
");
$stmt->execute($params);
$total_records = $stmt->fetchColumn();
$total_pages = ceil($total_records / $limit);

// Lấy danh sách yêu cầu
$stmt = $pdo->prepare("
    SELECT 
        yc.*,
        u.username as nguoi_xu_ly_username,
        ui.fullname as nguoi_xu_ly_name,
        ui.avatar as nguoi_xu_ly_avatar
    FROM yeu_cau_sua_chua yc
    LEFT JOIN users u ON yc.nguoi_xu_ly = u.id
    LEFT JOIN user_infos ui ON u.id = ui.user_id
    $where
    ORDER BY yc.created_at DESC
    LIMIT $limit OFFSET $offset
");
$stmt->execute($params);
$yeu_cau_list = $stmt->fetchAll();

// Cập nhật mảng trạng thái theo readme
$status_class = [
    0 => 'secondary',  // Chờ tiếp nhận
    1 => 'info',       // Đã tiếp nhận
    2 => 'primary',    // Đang xử lý
    3 => 'success',    // Đã hoàn thành
    4 => 'danger'      // Đã hủy
];

$status_text = [
    0 => 'Chờ tiếp nhận',
    1 => 'Đã tiếp nhận',
    2 => 'Đang xử lý',
    3 => 'Đã hoàn thành',
    4 => 'Đã hủy'
];

if(in_array($_SESSION['role'], [1,2])){
// thống kê
$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua WHERE trang_thai = 0")->fetchColumn(),
    'processing' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua WHERE trang_thai IN (1,2)")->fetchColumn(),
    'completed' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua WHERE trang_thai = 3")->fetchColumn()
];
}

require_once '../../include/layout/dashboard_header.php';
?>

<div class="container-fluid">
    <?php if(in_array($_SESSION['role'], [1,2])): ?>
    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75">Tổng yêu cầu</div>
                            <div class="display-6 fw-bold"><?php echo $stats['total']; ?></div>
                        </div>
                        <i class="fas fa-tools fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75">Chờ tiếp nhận</div>
                            <div class="display-6 fw-bold"><?php echo $stats['pending']; ?></div>
                        </div>
                        <i class="fas fa-clock fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75">Đang xử lý</div>
                            <div class="display-6 fw-bold"><?php echo $stats['processing']; ?></div>
                        </div>
                        <i class="fas fa-cog fa-2x text-white-50 fa-spin"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="me-3">
                            <div class="text-white-75">Đã hoàn thành</div>
                            <div class="display-6 fw-bold"><?php echo $stats['completed']; ?></div>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <div class="card">
        <div class="card-header bg-white py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="m-0 font-weight-bold text-primary">Danh sách yêu cầu sửa chữa</h5>
                </div>
                <div class="col text-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="collapse"
                        data-bs-target="#searchForm">
                        <i class="fas fa-search me-1"></i> Tìm kiếm
                    </button>
                </div>
            </div>
        </div>

        <div class="card-body">
            <?php if(isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $_SESSION['success']; unset($_SESSION['success']); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <!-- Form tìm kiếm (collapse) -->
            <div class="collapse mb-4" id="searchForm">
                <div class="card card-body border-light bg-light">
                    <form method="GET" action="">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    <input type="text" class="form-control" name="search"
                                        placeholder="Tìm theo mã đơn, tên, SĐT..."
                                        value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <select name="status" class="form-select">
                                    <option value="">Tất cả trạng thái</option>
                                    <?php foreach($status_text as $key => $text): ?>
                                    <option value="<?php echo $key; ?>"
                                        <?php echo $status_filter === (string)$key ? 'selected' : ''; ?>>
                                        <?php echo $text; ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" class="form-control" name="date_from"
                                        value="<?php echo $date_from; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    <input type="date" class="form-control" name="date_to"
                                        value="<?php echo $date_to; ?>">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Tìm kiếm
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn</th>
                            <th>Thông tin khách</th>
                            <th>Thời gian</th>
                            <th style="width: 120px;">Trạng thái</th>
                            <th>Người xử lý</th>
                            <th style="width: 100px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if(empty($yeu_cau_list)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="fas fa-check-circle fa-3x mb-3"></i>
                                <p class="mb-0">Không có yêu cầu nào</p>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach($yeu_cau_list as $yc): ?>
                        <tr>
                            <td class="fw-bold"><?php echo $yc['ma_don']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo $yc['ho_ten']; ?></div>
                                <div class="small text-primary"><?php echo $yc['so_dien_thoai']; ?></div>
                                <div class="small text-muted"><?php echo $yc['dia_chi']; ?></div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($yc['created_at'])); ?>
                                </div>
                                <?php if($yc['thoi_gian_hen']): ?>
                                <div class="small text-danger">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    Hẹn: <?php echo date('d/m/Y H:i', strtotime($yc['thoi_gian_hen'])); ?>
                                </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $status_class[$yc['trang_thai']]; ?> d-block p-2">
                                    <?php echo $status_text[$yc['trang_thai']]; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($yc['nguoi_xu_ly']): ?>
                                <div class="d-flex align-items-center">
                                    <div class="me-2">
                                        <?php if($yc['nguoi_xu_ly_avatar']): ?>
                                        <img src="/assets/media/uploads/avatars/<?php echo $yc['nguoi_xu_ly_avatar']; ?>"
                                            class="rounded-circle" width="30" height="30" style="object-fit: cover;">
                                        <?php else: ?>
                                        <img src="/assets/media/uploads/avatars/default.jpg" class="rounded-circle"
                                            width="30" height="30" style="object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <div>
                                        <div class="fw-bold">
                                            <?php echo $yc['nguoi_xu_ly_name'] ?: $yc['nguoi_xu_ly_username']; ?></div>
                                    </div>
                                </div>
                                <?php else: ?>
                                <span class="text-muted">
                                    <i class="fas fa-user-clock me-1"></i> Đang chờ
                                </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal"
                                        data-bs-target="#viewModal<?php echo $yc['id']; ?>" title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#updateModal<?php echo $yc['id']; ?>" title="Cập nhật">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($total_pages > 1): ?>
            <nav aria-label="Page navigation" class="mt-4">
                <ul class="pagination justify-content-center">
                    <!-- Nút Previous -->
                    <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                        <a class="page-link"
                            href="?page=<?php echo $page-1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <?php
                    // Hiển thị tối đa 5 trang
                    $start_page = max(1, $page - 2);
                    $end_page = min($total_pages, $start_page + 4);
                    
                    // Hiển thị nút trang đầu nếu cần
                    if($start_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?page=1&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>">1</a>
                    </li>
                    <?php if($start_page > 2): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    <?php endif;
                    endif;

                    // Hiển thị các trang chính
                    for($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                        <a class="page-link"
                            href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>">
                            <?php echo $i; ?>
                        </a>
                    </li>
                    <?php endfor;

                    // Hiển thị nút trang cuối nếu cần
                    if($end_page < $total_pages): 
                        if($end_page < $total_pages - 1): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link"
                            href="?page=<?php echo $total_pages; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>">
                            <?php echo $total_pages; ?>
                        </a>
                    </li>
                    <?php endif; ?>

                    <!-- Nút Next -->
                    <li class="page-item <?php echo $page >= $total_pages ? 'disabled' : ''; ?>">
                        <a class="page-link"
                            href="?page=<?php echo $page+1; ?>&search=<?php echo urlencode($search); ?>&status=<?php echo $status_filter; ?>&date_from=<?php echo $date_from; ?>&date_to=<?php echo $date_to; ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>

                <!-- Hiển thị thông tin phân trang -->
                <div class="text-center text-muted mt-2">
                    <small>
                        Hiển thị <?php echo $offset + 1; ?> - <?php echo min($offset + $limit, $total_records); ?>
                        trong tổng số <?php echo $total_records; ?> yêu cầu
                    </small>
                </div>
            </nav>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Modal xem chi tiết -->
<?php foreach($yeu_cau_list as $yc): ?>
<div class="modal fade" id="viewModal<?php echo $yc['id']; ?>" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle me-2"></i>
                    Chi tiết yêu cầu #<?php echo $yc['ma_don']; ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Thông tin khách hàng</h6>
                            </div>
                            <div class="card-body">
                                <p><i class="fas fa-user-circle me-2"></i><strong>Họ tên:</strong>
                                    <?php echo $yc['ho_ten']; ?></p>
                                <p><i class="fas fa-phone me-2"></i><strong>Số điện thoại:</strong>
                                    <?php echo $yc['so_dien_thoai']; ?></p>
                                <p><i class="fas fa-map-marker-alt me-2"></i><strong>Địa chỉ:</strong>
                                    <?php echo $yc['dia_chi']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card h-100">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Thông tin yêu cầu</h6>
                            </div>
                            <div class="card-body">
                                <p>
                                    <i class="fas fa-clock me-2"></i>
                                    <strong>Thời gian tạo:</strong>
                                    <?php echo date('d/m/Y H:i', strtotime($yc['created_at'])); ?>
                                </p>
                                <p>
                                    <i class="fas fa-calendar-alt me-2"></i>
                                    <strong>Thời gian hẹn:</strong>
                                    <?php echo $yc['thoi_gian_hen'] ? date('d/m/Y H:i', strtotime($yc['thoi_gian_hen'])) : '<span class="text-muted">Không có</span>'; ?>
                                </p>
                                <p>
                                    <i class="fas fa-tag me-2"></i>
                                    <strong>Trạng thái:</strong>
                                    <span class="badge bg-<?php echo $status_class[$yc['trang_thai']]; ?>">
                                        <?php echo $status_text[$yc['trang_thai']]; ?>
                                    </span>
                                </p>
                                <?php if($yc['chi_phi']): ?>
                                <p>
                                    <i class="fas fa-money-bill me-2"></i>
                                    <strong>Chi phí:</strong>
                                    <span class="text-danger fw-bold">
                                        <?php echo number_format($yc['chi_phi'], 0, ',', '.'); ?> VNĐ
                                    </span>
                                </p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-clipboard me-2"></i>Mô tả vấn đề</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo nl2br($yc['mo_ta']); ?></p>
                    </div>
                </div>

                <?php if($yc['media']): ?>
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-images me-2"></i>Hình ảnh/Video đính kèm</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php 
                            $media_files = json_decode($yc['media'], true);
                            foreach($media_files as $file):
                                $file_ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                $is_image = in_array($file_ext, ['jpg', 'jpeg', 'png', 'gif']);
                            ?>
                            <div class="col-md-3">
                                <div class="position-relative">
                                    <?php if($is_image): ?>
                                    <a href="/assets/media/uploads/yeu-cau-sua-chua/<?php echo $file; ?>"
                                        target="_blank">
                                        <img src="/assets/media/uploads/yeu-cau-sua-chua/<?php echo $file; ?>"
                                            class="img-thumbnail w-100" style="height: 150px; object-fit: cover;">
                                    </a>
                                    <?php else: ?>
                                    <video class="img-thumbnail w-100" controls
                                        style="height: 150px; object-fit: cover;">
                                        <source src="/assets/media/uploads/yeu-cau-sua-chua/<?php echo $file; ?>"
                                            type="video/<?php echo $file_ext; ?>">
                                    </video>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <?php if($yc['ghi_chu']): ?>
                <div class="card mt-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Ghi chú</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-0"><?php echo nl2br($yc['ghi_chu']); ?></p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Đóng
                </button>
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" data-bs-toggle="modal"
                    data-bs-target="#updateModal<?php echo $yc['id']; ?>">
                    <i class="fas fa-edit me-2"></i>Cập nhật
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal cập nhật -->
<div class="modal fade" id="updateModal<?php echo $yc['id']; ?>" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>
                        Cập nhật yêu cầu #<?php echo $yc['ma_don']; ?>
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="yeu_cau_id" value="<?php echo $yc['id']; ?>">

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-tag me-2"></i>Trạng thái
                        </label>
                        <select name="trang_thai" class="form-select" required>
                            <?php foreach($status_text as $key => $text): ?>
                            <option value="<?php echo $key; ?>"
                                <?php echo $yc['trang_thai'] == $key ? 'selected' : ''; ?>>
                                <?php echo $text; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user-cog me-2"></i>Người xử lý
                        </label>
                        <select name="nguoi_xu_ly" class="form-select"
                            <?php echo $_SESSION['role'] > 2 ? 'disabled' : ''; ?>>
                            <option value="">-- Chọn người xử lý --</option>
                            <?php 
                            $current_role = 0;
                            foreach($nhan_vien_list as $nv): 
                                if($current_role != $nv['role']){
                                    if($current_role != 0) echo '</optgroup>';
                                    echo '<optgroup label="' . $role_names[$nv['role']] . '">';
                                    $current_role = $nv['role'];
                                }
                            ?>
                            <option value="<?php echo $nv['id']; ?>"
                                <?php echo $yc['nguoi_xu_ly'] == $nv['id'] ? 'selected' : ''; ?>>
                                <?php echo $nv['fullname'] ?: $nv['username']; ?>
                            </option>
                            <?php 
                            endforeach;
                            if($current_role != 0) echo '</optgroup>';
                            ?>
                        </select>
                        <?php if($_SESSION['role'] > 2): ?>
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Chỉ Admin và CSKH có quyền phân công người xử lý
                        </small>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-sticky-note me-2"></i>Ghi chú
                        </label>
                        <textarea name="ghi_chu" class="form-control" rows="3"
                            placeholder="Nhập ghi chú nội bộ..."><?php echo $yc['ghi_chu']; ?></textarea>
                    </div>

                    <?php if($yc['trang_thai'] == 3): // Nếu đã hoàn thành ?>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-money-bill me-2"></i>Chi phí sửa chữa
                        </label>
                        <div class="input-group">
                            <span class="input-group-text">VNĐ</span>
                            <input type="number" class="form-control" name="chi_phi"
                                value="<?php echo $yc['chi_phi']; ?>" min="0">
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Đóng
                    </button>
                    <button type="submit" name="update_status" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endforeach; ?>

<?php require_once '../../include/layout/dashboard_footer.php'; ?>