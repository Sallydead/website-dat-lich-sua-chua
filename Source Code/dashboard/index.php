<?php
session_start();
require_once '../include/function.php';

// Kiểm tra đăng nhập và quyền admin
if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 1) {
    header("Location: ../login.php");
    exit();
}

$page_title = "Tổng quan";

// Thống kê số lượng người dùng theo role
$stmt = $pdo->query("SELECT role, COUNT(*) as total FROM users GROUP BY role");
$user_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Thống kê khách hàng mới trong 7 ngày qua
$stmt = $pdo->query("SELECT DATE(created_at) as date, COUNT(*) as total 
                     FROM users 
                     WHERE created_at >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND role = 0
                     GROUP BY DATE(created_at)
                     ORDER BY date DESC");
$new_users = $stmt->fetchAll();

// Thống kê giới tính
$stmt = $pdo->query("SELECT gender, COUNT(*) as total FROM user_infos WHERE user_id IN (SELECT id FROM users WHERE role = 0) GROUP BY gender");
$gender_stats = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

// Người dùng mới nhất
$stmt = $pdo->query("SELECT u.*, ui.fullname, ui.phone 
                     FROM users u 
                     LEFT JOIN user_infos ui ON u.id = ui.user_id 
                     WHERE u.role = 0
                     ORDER BY u.created_at DESC 
                     LIMIT 5");
$latest_users = $stmt->fetchAll();

require_once '../include/layout/dashboard_header.php';
?>

<div class="container-fluid">
    <!-- Thống kê tổng quan -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Tổng số người dùng</h6>
                            <h3 class="mb-0"><?php echo array_sum($user_stats); ?></h3>
                        </div>
                        <i class="fas fa-users fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Khách hàng</h6>
                            <h3 class="mb-0"><?php echo isset($user_stats[0]) ? $user_stats[0] : 0; ?></h3>
                        </div>
                        <i class="fas fa-user fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Nhân viên CSKH</h6>
                            <h3 class="mb-0"><?php echo isset($user_stats[2]) ? $user_stats[2] : 0; ?></h3>
                        </div>
                        <i class="fas fa-headset fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title">Kỹ thuật viên</h6>
                            <h3 class="mb-0"><?php echo isset($user_stats[3]) ? $user_stats[3] : 0; ?></h3>
                        </div>
                        <i class="fas fa-wrench fa-2x opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Biểu đồ người dùng mới -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Khách hàng mới trong 7 ngày qua</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($new_users)): ?>
                        <canvas id="newUsersChart"></canvas>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-chart-line fa-3x mb-3"></i>
                            <p>Chưa có khách hàng mới trong 7 ngày qua</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- Biểu đồ giới tính -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Thống kê giới tính</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($gender_stats)): ?>
                        <canvas id="genderChart"></canvas>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-users fa-3x mb-3"></i>
                            <p>Chưa có dữ liệu thống kê giới tính</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Danh sách người dùng mới nhất -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Người dùng mới nhất</h5>
                </div>
                <div class="card-body">
                    <?php if(!empty($latest_users)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Tên đăng nhập</th>
                                        <th>Họ tên</th>
                                        <th>Email</th>
                                        <th>Số điện thoại</th>
                                        <th>Ngày tạo</th>
                                        <th>Thao tác</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($latest_users as $user): ?>
                                    <tr>
                                        <td><?php echo $user['id']; ?></td>
                                        <td><?php echo $user['username']; ?></td>
                                        <td><?php echo $user['fullname'] ?: '<span class="text-muted">Chưa cập nhật</span>'; ?></td>
                                        <td><?php echo $user['email']; ?></td>
                                        <td><?php echo $user['phone'] ?: '<span class="text-muted">Chưa cập nhật</span>'; ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($user['created_at'])); ?></td>
                                        <td>
                                            <a href="users/view.php?id=<?php echo $user['id']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="text-center text-muted py-5">
                            <i class="fas fa-user-plus fa-3x mb-3"></i>
                            <p>Chưa có người dùng nào</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Chỉ khởi tạo biểu đồ nếu có dữ liệu
<?php if(!empty($new_users)): ?>
// Biểu đồ người dùng mới
const newUsersData = {
    labels: <?php echo json_encode(array_column($new_users, 'date')); ?>,
    datasets: [{
        label: 'Người dùng mới',
        data: <?php echo json_encode(array_column($new_users, 'total')); ?>,
        backgroundColor: 'rgba(54, 162, 235, 0.2)',
        borderColor: 'rgba(54, 162, 235, 1)',
        borderWidth: 1
    }]
};

new Chart(document.getElementById('newUsersChart'), {
    type: 'line',
    data: newUsersData,
    options: {
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
<?php endif; ?>

<?php if(!empty($gender_stats)): ?>
// Biểu đồ giới tính
const genderData = {
    labels: ['Nam', 'Nữ'],
    datasets: [{
        data: [
            <?php echo isset($gender_stats['nam']) ? $gender_stats['nam'] : 0; ?>,
            <?php echo isset($gender_stats['nu']) ? $gender_stats['nu'] : 0; ?>
        ],
        backgroundColor: [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)'
        ]
    }]
};

new Chart(document.getElementById('genderChart'), {
    type: 'doughnut',
    data: genderData,
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});
<?php endif; ?>
</script>

<?php require_once '../include/layout/dashboard_footer.php'; ?> 