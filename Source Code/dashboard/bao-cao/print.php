<?php
session_start();
require_once '../../include/function.php';

// Kiểm tra quyền admin và CSKH
if(!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], [1, 2])) {
    header("Location: ../../login.php");
    exit();
}

// Lấy tham số filter
$month = isset($_GET['month']) ? $_GET['month'] : date('Y-m');
$start_date = date('Y-m-01', strtotime($month));
$end_date = date('Y-m-t', strtotime($month));

// Lấy thống kê (giống như index.php)
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
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Báo cáo thống kê - <?php echo date('m/Y', strtotime($month)); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }
            body {
                font-size: 12pt;
            }
            .no-print {
                display: none !important;
            }
            .page-break {
                page-break-after: always;
            }
            table {
                width: 100% !important;
            }
        }
    </style>
</head>
<body class="bg-white">
    <div class="container-fluid py-4">
        <!-- Nút in -->
        <div class="text-end mb-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                <i class="fas fa-print me-2"></i>In báo cáo
            </button>
        </div>

        <!-- Header -->
        <div class="text-center mb-4">
            <h4>BÁO CÁO THỐNG KÊ</h4>
            <p>Tháng <?php echo date('m/Y', strtotime($month)); ?></p>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="row g-4 mb-4">
            <div class="col-12">
                <table class="table table-bordered">
                    <tr>
                        <th class="text-center" width="20%">Tổng yêu cầu</th>
                        <th class="text-center" width="20%">Hoàn thành</th>
                        <th class="text-center" width="20%">Đang xử lý</th>
                        <th class="text-center" width="20%">Chờ tiếp nhận</th>
                        <th class="text-center" width="20%">Đã hủy</th>
                    </tr>
                    <tr class="text-center">
                        <td class="fw-bold"><?php echo number_format($stats['total_requests'] ?? 0); ?></td>
                        <td class="text-success fw-bold"><?php echo number_format($stats['completed'] ?? 0); ?></td>
                        <td class="text-info fw-bold"><?php echo number_format($stats['processing'] ?? 0); ?></td>
                        <td class="text-warning fw-bold"><?php echo number_format($stats['pending'] ?? 0); ?></td>
                        <td class="text-danger fw-bold"><?php echo number_format($stats['cancelled'] ?? 0); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Doanh thu -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <h5>Tổng doanh thu:</h5>
                                <h3 class="text-success">
                                    <?php echo number_format($stats['total_revenue'] ?? 0, 0, ',', '.'); ?>đ
                                </h3>
                            </div>
                            <div class="col-6 text-end">
                                <h5>Doanh thu trung bình/yêu cầu:</h5>
                                <h3>
                                    <?php echo $stats['completed'] > 0 ? 
                                        number_format(round($stats['total_revenue'] / $stats['completed']), 0, ',', '.') : 0; ?>đ
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thống kê nhân viên -->
        <div class="mb-4">
            <h5 class="mb-3">Thống kê theo nhân viên:</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
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
                            <td><?php echo $staff['fullname'] ?: $staff['username']; ?></td>
                            <td><?php echo $role_names[$staff['role']]; ?></td>
                            <td class="text-center"><?php echo $staff['total_orders']; ?></td>
                            <td class="text-center"><?php echo $staff['completed_orders']; ?></td>
                            <td class="text-center">
                                <?php 
                                echo $staff['total_orders'] > 0 ? 
                                    round(($staff['completed_orders'] / $staff['total_orders']) * 100) : 0;
                                ?>%
                            </td>
                            <td class="text-end">
                                <?php if($staff['revenue'] > 0): ?>
                                <?php echo number_format($staff['revenue'], 0, ',', '.'); ?>đ
                                <?php else: ?>
                                -
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="row mt-5">
            <div class="col-6 text-center">
                <p>Người lập báo cáo</p>
                <p class="mt-5"><?php echo $_SESSION['username']; ?></p>
            </div>
            <div class="col-6 text-center">
                <p>Ngày <?php echo date('d/m/Y'); ?></p>
            </div>
        </div>
    </div>
</body>
</html> 