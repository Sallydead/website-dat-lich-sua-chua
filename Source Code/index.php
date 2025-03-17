<?php
session_start();
require_once 'include/function.php';

// Kiểm tra đăng nhập
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$page_title = "Trang chủ";

require_once 'include/layout/header.php';
?>

</div></div>
<!-- Hero Section -->
<div class="hero bg-primary text-white py-5" style="margin-top: -40px;">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">Vũ Chí Linh Computer</h1>
                <p class="lead mb-4">Chuyên cung cấp các dịch vụ sửa chữa, nâng cấp và bảo trì máy tính, laptop tại Bắc Giang</p>
                <div class="d-flex gap-3">
                    <a href="dat-yeu-cau.php" class="btn btn-light btn-lg">
                        <i class="fas fa-tools me-2"></i>Đặt yêu cầu sửa chữa
                    </a>
                    <a href="tra-cuu.php" class="btn btn-outline-light btn-lg">
                        <i class="fas fa-search me-2"></i>Tra cứu yêu cầu
                    </a>
                </div>
            </div>
            <div class="col-md-6">
                <img src="/assets/media/banner1.png" class="img-fluid">
            </div>
        </div>
    </div>
</div>
<div class="main-content">
    <div class="container-fluid px-4">

<?php if(in_array($_SESSION['role'], [1,2])): // Chỉ admin và CSKH xem thống kê ?>
<!-- Dịch vụ -->
<section class="services py-5">
    <div class="container-fluid px-4">
        <h2 class="text-center mb-5">Thống kê dịch vụ</h2>
        <div class="row g-4">
            <!-- ... các card thống kê ... -->
        </div>
    </div>
</section>

<!-- Cam kết -->
<section class="features bg-light py-5">
    <div class="container-fluid px-4">
        <h2 class="text-center mb-5">Thống kê chi tiết</h2>
        <div class="row g-4">
            <!-- ... các thống kê chi tiết ... -->
        </div>
    </div>
</section>
<?php endif; ?>

<?php if($_SESSION['role'] == 3): // KTV chỉ xem phân công ?>
<!-- Phân công công việc -->
<section class="py-5">
    <div class="container-fluid px-4">
        <h2 class="text-center mb-5">Công việc được phân công</h2>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <!-- Hiển thị danh sách công việc -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Liên hệ -->
<section class="contact py-5">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h2 class="mb-4">Liên hệ với chúng tôi</h2>
                <p class="mb-4">Hãy để lại thông tin, chúng tôi sẽ liên hệ với bạn sớm nhất</p>
                <form>
                    <div class="mb-3">
                        <input type="text" class="form-control" placeholder="Họ và tên">
                    </div>
                    <div class="mb-3">
                        <input type="tel" class="form-control" placeholder="Số điện thoại">
                    </div>
                    <div class="mb-3">
                        <textarea class="form-control" rows="3" placeholder="Nội dung"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Gửi tin nhắn</button>
                </form>
            </div>
            <div class="col-md-6">
                <div class="ratio ratio-16x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3371.9072569828018!2d105.81959267471234!3d20.94934999050235!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135add7a98d2c1d%3A0xe60529400caa5c57!2zVHLGsOG7nW5nIENhbyDEkOG6s25nIEPDtG5nIE5naOG7hyBCw6FjaCBLaG9hIEjDoCBO4buZaSBD4bufIFPhu58gVGhhbmggVHLDrA!5e1!3m2!1svi!2s!4v1740591062734!5m2!1svi!2s" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require_once 'include/layout/footer.php'; ?> 