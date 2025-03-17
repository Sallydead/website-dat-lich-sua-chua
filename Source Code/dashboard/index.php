<?php
session_start();
require_once '../include/function.php';

// Kiểm tra đăng nhập
if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$page_title = "Dashboard";

// phân công
// Lấy danh sách yêu cầu được phân công
    $where = "";
    $params = [];
    
        $where = "WHERE yc.nguoi_xu_ly = ? AND MONTH(yc.created_at) = MONTH(CURRENT_DATE()) AND YEAR(yc.created_at) = YEAR(CURRENT_DATE())";
        $params = [$_SESSION['user_id']];


    $stmt = $pdo->prepare("
        SELECT 
            yc.*,
            u.username as nguoi_xu_ly_username,
            ui.fullname as nguoi_xu_ly_name
        FROM yeu_cau_sua_chua yc
        LEFT JOIN users u ON yc.nguoi_xu_ly = u.id
        LEFT JOIN user_infos ui ON u.id = ui.user_id
        $where
        ORDER BY 
            CASE 
                WHEN yc.thoi_gian_hen IS NOT NULL THEN yc.thoi_gian_hen
                ELSE yc.created_at 
            END ASC
    ");
    $stmt->execute($params);
    $assignments = $stmt->fetchAll();

    
if(in_array($_SESSION['role'], [1,2])) { // Admin, CSKH
// Lấy thống kê tổng quan
$stats = [
    'total' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua WHERE trang_thai = 0")->fetchColumn(),
    'processing' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua WHERE trang_thai IN (1,2)")->fetchColumn(),
    'completed' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua WHERE trang_thai = 3")->fetchColumn(),
    'cancelled' => $pdo->query("SELECT COUNT(*) FROM yeu_cau_sua_chua WHERE trang_thai = 4")->fetchColumn(),
    'revenue' => $pdo->query("
        SELECT COALESCE(SUM(chi_phi), 0) as total 
        FROM yeu_cau_sua_chua 
        WHERE trang_thai = 3 
            AND MONTH(created_at) = MONTH(CURRENT_DATE())
            AND YEAR(created_at) = YEAR(CURRENT_DATE())
    ")->fetchColumn()
];

// Lấy thống kê theo ngày (7 ngày gần nhất)
$daily_stats = $pdo->query("
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as total,
        SUM(CASE WHEN trang_thai = 3 THEN 1 ELSE 0 END) as completed
    FROM yeu_cau_sua_chua 
    WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
    GROUP BY DATE(created_at)
    ORDER BY date ASC
")->fetchAll();

// Lấy top 5 nhân viên có nhiều đơn nhất trong tháng
$top_staff = $pdo->query("
    SELECT 
        u.id,
        u.username,
        u.role,
        ui.fullname,
        COUNT(*) as total_orders,
        SUM(CASE WHEN yc.trang_thai = 3 THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN yc.trang_thai = 3 THEN yc.chi_phi ELSE 0 END) as total_revenue
    FROM yeu_cau_sua_chua yc
    JOIN users u ON yc.nguoi_xu_ly = u.id
    LEFT JOIN user_infos ui ON u.id = ui.user_id
    WHERE u.role IN (1,2,3)
        AND MONTH(yc.created_at) = MONTH(CURRENT_DATE())
        AND YEAR(yc.created_at) = YEAR(CURRENT_DATE())
    GROUP BY u.id, u.username, u.role, ui.fullname
    ORDER BY total_orders DESC
    LIMIT 5
")->fetchAll();
}
// Mảng màu và tên cho từng role
$role_colors = [
    1 => 'danger',   // Admin - đỏ
    2 => 'info',     // CSKH - xanh dương
    3 => 'success'   // KTV - xanh lá
];

$role_names = [
    1 => 'Admin',
    2 => 'CSKH',
    3 => 'Kỹ thuật viên'
];

if(in_array($_SESSION['role'], [1,2])) { // Admin, CSKH
// Lấy các yêu cầu mới nhất
$latest_requests = $pdo->query("
    SELECT 
        yc.*,
        u.username as nguoi_xu_ly_username,
        ui.fullname as nguoi_xu_ly_name
    FROM yeu_cau_sua_chua yc
    LEFT JOIN users u ON yc.nguoi_xu_ly = u.id
    LEFT JOIN user_infos ui ON u.id = ui.user_id
    ORDER BY yc.created_at DESC
    LIMIT 5
")->fetchAll();
}
require_once '../include/layout/dashboard_header.php';
?>

<div class="container-fluid">


<?php if(in_array($_SESSION['role'], [1,2])): ?>
    <!-- Thống kê dạng card -->
    <div class="row g-3 mb-4">
        <!-- Người dùng -->
        <div class="col-sm-6 col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1"><?php echo $stats['total']; ?></h3>
                        <div class="text-white-50 small text-uppercase">Người dùng</div>
                    </div>
                </div>
            </div>
        </div>
        

        <!-- Ghi chú -->
        <div class="col-sm-6 col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1"><?php echo $stats['pending']; ?></h3>
                        <div class="text-white-50 small text-uppercase">Chờ tiếp nhận</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Hình ảnh -->
        <div class="col-sm-6 col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-images fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1"><?php echo $stats['processing']; ?></h3>
                        <div class="text-white-50 small text-uppercase">Đang xử lý</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- File -->
        <div class="col-sm-6 col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body d-flex align-items-center">
                    <div class="flex-shrink-0 me-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-file-alt fa-2x"></i>
                        </div>
                    </div>
                    <div>
                        <h3 class="fw-bold mb-1"><?php echo $stats['completed']; ?></h3>
                        <div class="text-white-50 small text-uppercase">Hoàn thành</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">
                    <?php if($_SESSION['role'] == 3): ?>
                        <i class="fas fa-clipboard-list me-2"></i>Công việc được phân công
                    <?php else: ?>
                        <i class="fas fa-tasks me-2"></i>Yêu cầu trong tháng
                    <?php endif; ?>
                </h5>
            </div>
            <div class="card-body">
                <?php if(empty($assignments)): ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-check-circle fa-3x mb-3"></i>
                    <p class="mb-0">Không có yêu cầu nào</p>
                </div>
                <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Thời gian hẹn</th>
                                <th>Địa chỉ</th>
                                <th>Trạng thái</th>
                                <?php if($_SESSION['role'] != 3): ?>
                                <th>Người xử lý</th>
                                <?php endif; ?>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($assignments as $yc): ?>
                            <tr>
                                <td class="fw-bold"><?php echo $yc['ma_don']; ?></td>
                                <td>
                                    <div class="fw-bold"><?php echo $yc['ho_ten']; ?></div>
                                    <div class="small text-muted"><?php echo $yc['so_dien_thoai']; ?></div>
                                </td>
                                <td>
                                    <?php if($yc['thoi_gian_hen']): ?>
                                    <div class="text-danger fw-bold">
                                        <?php echo date('d/m/Y H:i', strtotime($yc['thoi_gian_hen'])); ?>
                                    </div>
                                    <?php else: ?>
                                    <span class="text-muted">Không có hẹn</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo $yc['dia_chi']; ?></td>
                                <td>
                                    <span class="badge bg-<?php echo get_status_color($yc['trang_thai']); ?>">
                                        <?php echo get_status_text($yc['trang_thai']); ?>
                                    </span>
                                </td>
                                <?php if($_SESSION['role'] != 3): ?>
                                <td>
                                    <?php if($yc['nguoi_xu_ly']): ?>
                                        <?php echo $yc['nguoi_xu_ly_name'] ?: $yc['nguoi_xu_ly_username']; ?>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa phân công</span>
                                    <?php endif; ?>
                                </td>
                                <?php endif; ?>
                                <td>
                                    <a href="/dashboard/quan-ly-yeu-cau?search=<?php echo $yc['ma_don']; ?>" 
                                       class="btn btn-sm btn-info">
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

<?php if(in_array($_SESSION['role'], [1,2])): ?>
    <!-- Biểu đồ và Top nhân viên -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0">Thống kê yêu cầu</h6>
                    <select class="form-select form-select-sm w-auto">
                        <option>7 ngày qua</option>
                        <option>30 ngày qua</option>
                    </select>
                </div>
                <div class="card-body">
                    <canvas id="requestsChart"></canvas>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header">
                    <h6 class="mb-0">Top nhân viên tháng <?php echo date('m/Y'); ?></h6>
                </div>
                <div class="card-body p-0">
                    <?php if(empty($top_staff)): ?>
                    <div class="text-center text-muted p-4">
                        <i class="fas fa-chart-bar fa-2x mb-3"></i>
                        <p class="mb-0">Chưa có dữ liệu thống kê</p>
                    </div>
                    <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach($top_staff as $staff): ?>
                        <div class="list-group-item">
                            <div class="d-flex align-items-center">
                                <div class="flex-shrink-0 me-3 position-relative">
                                    <i class="fas fa-user-circle fa-2x text-secondary"></i>
                                    <span class="position-absolute bottom-0 end-0 badge rounded-pill bg-<?php echo $role_colors[$staff['role']]; ?>" style="font-size: 0.6rem;">
                                        <?php echo substr($role_names[$staff['role']], 0, 1); ?>
                                    </span>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-1"><?php echo $staff['fullname'] ?: $staff['username']; ?></h6>
                                    <div class="small">
                                        <span class="text-muted me-3">
                                            <i class="fas fa-check-circle me-1"></i>
                                            <?php echo $staff['completed_orders']; ?>/<?php echo $staff['total_orders']; ?>
                                        </span>
                                        <?php if($staff['total_revenue'] > 0): ?>
                                        <span class="text-success">
                                            <i class="fas fa-money-bill-wave me-1"></i>
                                            <?php echo number_format($staff['total_revenue'], 0, ',', '.'); ?>đ
                                        </span>
                                        <?php endif; ?>
                                    </div>
                                    <?php 
                                    $completion_rate = $staff['total_orders'] > 0 ? 
                                        round(($staff['completed_orders'] / $staff['total_orders']) * 100) : 0;
                                    ?>
                                    <div class="progress mt-2" style="height: 4px;">
                                        <div class="progress-bar bg-<?php echo $role_colors[$staff['role']]; ?>" 
                                             style="width: <?php echo $completion_rate; ?>%">
                                        </div>
                                    </div>
                                </div>
                                <div class="flex-shrink-0 ms-3">
                                    <span class="badge bg-<?php echo $role_colors[$staff['role']]; ?>">
                                        <?php echo $completion_rate; ?>%
                                    </span>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Yêu cầu mới nhất -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h6 class="m-0 font-weight-bold">Yêu cầu mới nhất</h6>
                <a href="/dashboard/quan-ly-yeu-cau" class="btn btn-primary btn-sm">
                    <i class="fas fa-list me-1"></i>Xem tất cả
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Khách hàng</th>
                            <th>Thời gian</th>
                            <th>Trạng thái</th>
                            <th>Người xử lý</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $status_class = [
                            0 => 'secondary',
                            1 => 'info',
                            2 => 'primary',
                            3 => 'success',
                            4 => 'danger'
                        ];
                        $status_text = [
                            0 => 'Chờ tiếp nhận',
                            1 => 'Đã tiếp nhận',
                            2 => 'Đang xử lý',
                            3 => 'Đã hoàn thành',
                            4 => 'Đã hủy'
                        ];
                        foreach($latest_requests as $yc): 
                        ?>
                        <tr>
                            <td class="fw-bold"><?php echo $yc['ma_don']; ?></td>
                            <td>
                                <div class="fw-bold"><?php echo $yc['ho_ten']; ?></div>
                                <div class="small text-muted"><?php echo $yc['so_dien_thoai']; ?></div>
                            </td>
                            <td>
                                <div class="small">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo date('d/m/Y H:i', strtotime($yc['created_at'])); ?>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $status_class[$yc['trang_thai']]; ?>">
                                    <?php echo $status_text[$yc['trang_thai']]; ?>
                                </span>
                            </td>
                            <td>
                                <?php if($yc['nguoi_xu_ly']): ?>
                                    <?php echo $yc['nguoi_xu_ly_name'] ?: $yc['nguoi_xu_ly_username']; ?>
                                <?php else: ?>
                                    <span class="text-muted">Đang chờ</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="/dashboard/quan-ly-yeu-cau?search=<?php echo $yc['ma_don']; ?>" 
                                   class="btn btn-sm btn-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Dữ liệu cho biểu đồ
const dailyData = <?php echo json_encode($daily_stats); ?>;
const dates = dailyData.map(item => item.date);
const totals = dailyData.map(item => item.total);
const completed = dailyData.map(item => item.completed);

// Vẽ biểu đồ
const ctx = document.getElementById('requestsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: dates,
        datasets: [{
            label: 'Tổng yêu cầu',
            data: totals,
            borderColor: '#2c3e50',
            backgroundColor: 'rgba(44, 62, 80, 0.1)',
            fill: true
        }, {
            label: 'Hoàn thành',
            data: completed,
            borderColor: '#27ae60',
            backgroundColor: 'rgba(39, 174, 96, 0.1)',
            fill: true
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    stepSize: 1
                }
            }
        }
    }
});
</script>
<?php endif; ?>

<?php require_once '../include/layout/dashboard_footer.php'; ?> 