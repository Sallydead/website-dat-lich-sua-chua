<?php
session_start();
require_once '../../include/function.php';

// Kiểm tra quyền admin và CSKH
if(!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], [1, 2])) {
    header("Location: ../../login.php");
    exit();
}

$page_title = "Báo cáo thống kê";

// Định nghĩa role names và colors
$role_names = [
    1 => 'Admin',
    2 => 'CSKH',
    3 => 'Kỹ thuật viên'
];

$role_colors = [
    1 => 'danger',   // Admin - đỏ
    2 => 'info',     // CSKH - xanh dương
    3 => 'success'   // KTV - xanh lá
];

// Lấy tham số filter
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$start_date = date('Y-m-01', strtotime($month));
$end_date = date('Y-m-t', strtotime($month));

// Thống kê tổng quan theo trạng thái
$stmt = $pdo->prepare("
    SELECT 
        COALESCE(COUNT(*), 0) as total_requests,
        COALESCE(SUM(CASE WHEN trang_thai = 0 THEN 1 ELSE 0 END), 0) as pending,
        COALESCE(SUM(CASE WHEN trang_thai IN (1,2) THEN 1 ELSE 0 END), 0) as processing,
        COALESCE(SUM(CASE WHEN trang_thai = 3 THEN 1 ELSE 0 END), 0) as completed,
        COALESCE(SUM(CASE WHEN trang_thai = 4 THEN 1 ELSE 0 END), 0) as cancelled,
        COALESCE(SUM(CASE WHEN trang_thai = 3 THEN chi_phi ELSE 0 END), 0) as total_revenue
    FROM yeu_cau_sua_chua
    WHERE DATE(created_at) BETWEEN ? AND ?
");
$stmt->execute([$start_date, $end_date]);
$stats = $stmt->fetch();

// Thống kê theo nhân viên
$staff_stmt = $pdo->prepare("
    SELECT 
        u.id,
        u.username,
        u.role,
        ui.fullname,
        COUNT(yc.id) as total_orders,
        SUM(CASE WHEN yc.trang_thai = 3 THEN 1 ELSE 0 END) as completed_orders,
        SUM(CASE WHEN yc.trang_thai = 3 THEN yc.chi_phi ELSE 0 END) as revenue
    FROM users u
    LEFT JOIN user_infos ui ON u.id = ui.user_id
    LEFT JOIN yeu_cau_sua_chua yc ON u.id = yc.nguoi_xu_ly 
        AND DATE(yc.created_at) BETWEEN ? AND ?
    WHERE u.role IN (1,2,3)
    GROUP BY u.id, u.username, u.role, ui.fullname
    ORDER BY total_orders DESC
");
$staff_stmt->execute([$start_date, $end_date]);
$staff_stats = $staff_stmt->fetchAll();

// Biểu đồ theo ngày
$daily_stmt = $pdo->prepare("
    SELECT 
        DATE(created_at) as date,
        COUNT(*) as total,
        SUM(CASE WHEN trang_thai = 3 THEN 1 ELSE 0 END) as completed,
        SUM(CASE WHEN trang_thai = 3 THEN chi_phi ELSE 0 END) as revenue
    FROM yeu_cau_sua_chua
    WHERE DATE(created_at) BETWEEN ? AND ?
    GROUP BY DATE(created_at)
    ORDER BY date ASC
");
$daily_stmt->execute([$start_date, $end_date]);
$daily_stats = $daily_stmt->fetchAll();

require_once '../../include/layout/dashboard_header.php';
?>

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Báo cáo thống kê tháng <?php echo date('m/Y', strtotime($month)); ?></h4>
        
        <div class="d-flex gap-2">
            <!-- Filter tháng -->
            <form method="GET" class="d-flex gap-2">
                <input type="month" class="form-control" name="month" 
                       value="<?php echo $month; ?>" onchange="this.form.submit()">
            </form>
            
            <!-- Nút in -->
            <a href="print.php?month=<?php echo $month; ?>" class="btn btn-primary" target="_blank">
                <i class="fas fa-print me-2"></i>In báo cáo
            </a>
            
        </div>
    </div>

    <!-- Thống kê tổng quan -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-clipboard-list fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-uppercase mb-1">Tổng yêu cầu</h6>
                            <h3 class="mb-0"><?php echo number_format($stats['total_requests'] ?? 0); ?></h3>
                        </div>
                    </div>
                    <div class="progress bg-white bg-opacity-25" style="height:4px">
                        <div class="progress-bar bg-white" style="width:100%"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-check-circle fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-uppercase mb-1">Hoàn thành</h6>
                            <h3 class="mb-0"><?php echo number_format($stats['completed'] ?? 0); ?></h3>
                        </div>
                    </div>
                    <?php $completion_rate = $stats['total_requests'] > 0 ? 
                        round(($stats['completed'] / $stats['total_requests']) * 100) : 0; ?>
                    <div class="progress bg-white bg-opacity-25" style="height:4px">
                        <div class="progress-bar bg-white" style="width:<?php echo $completion_rate; ?>%"></div>
                    </div>
                    <div class="text-white-50 small mt-2">
                        Tỷ lệ hoàn thành: <?php echo $completion_rate; ?>%
                    </div>
                </div>
            </div>
        </div>

        <!-- Card đang xử lý -->
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-uppercase mb-1">Đang xử lý</h6>
                            <h3 class="mb-0"><?php echo number_format($stats['processing'] ?? 0); ?></h3>
                        </div>
                    </div>
                    <?php $processing_rate = $stats['total_requests'] > 0 ? 
                        round(($stats['processing'] / $stats['total_requests']) * 100) : 0; ?>
                    <div class="progress bg-white bg-opacity-25" style="height:4px">
                        <div class="progress-bar bg-white" style="width:<?php echo $processing_rate; ?>%"></div>
                    </div>
                    <div class="text-white-50 small mt-2">
                        Tỷ lệ: <?php echo $processing_rate; ?>%
                    </div>
                </div>
            </div>
        </div>

        <!-- Card doanh thu -->
        <div class="col-md-3">
            <div class="card bg-warning text-white h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-25 p-3 rounded">
                            <i class="fas fa-money-bill-wave fa-2x"></i>
                        </div>
                        <div class="ms-3">
                            <h6 class="text-uppercase mb-1">Doanh thu</h6>
                            <h3 class="mb-0"><?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', '.'); ?>đ</h3>
                        </div>
                    </div>
                    <div class="progress bg-white bg-opacity-25" style="height:4px">
                        <div class="progress-bar bg-white" style="width:100%"></div>
                    </div>
                    <div class="text-white-50 small mt-2">
                        TB: <?php echo $stats['completed'] > 0 ? 
                            number_format(round($stats['total_revenue'] / $stats['completed']), 0, ',', '.') : 0; ?>đ/yêu cầu
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Biểu đồ -->
    <div class="row g-4 mb-4">
        <div class="col-md-8">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Biểu đồ thống kê theo ngày</h5>
                </div>
                <div class="card-body">
                    <canvas id="statsChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100">
                <div class="card-header">
                    <h5 class="mb-0">Phân bố trạng thái</h5>
                </div>
                <div class="card-body">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bảng thống kê nhân viên -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Thống kê theo nhân viên</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nhân viên</th>
                            <th>Chức vụ</th>
                            <th class="text-center">Tổng yêu cầu</th>
                            <th class="text-center">Hoàn thành</th>
                            <th class="text-center">Tỷ lệ</th>
                            <th class="text-end">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($staff_stats as $staff): ?>
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-circle fa-2x text-secondary me-2"></i>
                                    <div>
                                        <div class="fw-bold">
                                            <?php echo $staff['fullname'] ?: $staff['username']; ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-<?php echo $role_colors[$staff['role']]; ?>">
                                    <?php echo $role_names[$staff['role']]; ?>
                                </span>
                            </td>
                            <td class="text-center"><?php echo $staff['total_orders']; ?></td>
                            <td class="text-center"><?php echo $staff['completed_orders']; ?></td>
                            <td class="text-center">
                                <?php 
                                $rate = $staff['total_orders'] > 0 ? 
                                    round(($staff['completed_orders'] / $staff['total_orders']) * 100) : 0;
                                ?>
                                <div class="d-flex align-items-center">
                                    <div class="progress flex-grow-1 me-2" style="height:4px">
                                        <div class="progress-bar bg-success" style="width:<?php echo $rate; ?>%"></div>
                                    </div>
                                    <span class="text-muted small"><?php echo $rate; ?>%</span>
                                </div>
                            </td>
                            <td class="text-end">
                                <?php if($staff['revenue'] > 0): ?>
                                <span class="text-success">
                                    <?php echo number_format($staff['revenue'], 0, ',', '.'); ?>đ
                                </span>
                                <?php else: ?>
                                <span class="text-muted">-</span>
                                <?php endif; ?>
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
// Dữ liệu cho biểu đồ theo ngày
const dailyData = <?php echo json_encode($daily_stats); ?>;
const dates = dailyData.map(item => item.date);
const totals = dailyData.map(item => item.total);
const completed = dailyData.map(item => item.completed);
const revenues = dailyData.map(item => item.revenue);

// Biểu đồ đường
new Chart(document.getElementById('statsChart'), {
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
        }
    }
});

// Biểu đồ tròn
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: ['Chờ tiếp nhận', 'Đang xử lý', 'Hoàn thành', 'Đã hủy'],
        datasets: [{
            data: [
                <?php echo $stats['pending']; ?>,
                <?php echo $stats['processing']; ?>,
                <?php echo $stats['completed']; ?>,
                <?php echo $stats['cancelled']; ?>
            ],
            backgroundColor: ['#6c757d', '#3498db', '#2ecc71', '#e74c3c']
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
</script>

<?php require_once '../../include/layout/dashboard_footer.php'; ?> 