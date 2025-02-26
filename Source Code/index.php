<?php
session_start();
require_once 'include/function.php';

$page_title = "Trang chủ";
require_once 'include/layout/header.php';
?>

<!-- Hero Section -->
<div class="hero bg-primary text-white py-5">
    <div class="container-fluid px-4">
        <div class="row align-items-center">
            <div class="col-md-6">
                <h1 class="display-4 fw-bold mb-4">MinhGiangPC.Com</h1>
                <p class="lead mb-4">Chuyên cung cấp các dịch vụ sửa chữa, nâng cấp và bảo trì máy tính, laptop tại Cao Bằng</p>
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
                <img src="https://blogger.googleusercontent.com/img/b/R29vZ2xl/AVvXsEjMhMb1wEQaJ7xNhzXz-9O1Ec-ir7jw-ComE1iLOszHSU_8eWya0QvDEKOmBLMgrIi3LtG7b-48gVJhCKkshSKFxlhDngRF5L3lGl2xvBnK5eUuGfqYZQ4oovvsKfcFqOGv_dfNAoB9ic8lcipetd4cRqO5kwd67qlwWgmzThTFFTQEUGX0AGTEZ1n2/s1600/home_slider1.jpg" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<!-- Dịch vụ -->
<section class="services py-5">
    <div class="container-fluid px-4">
        <h2 class="text-center mb-5">Dịch vụ của chúng tôi</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-laptop fa-3x text-primary mb-3"></i>
                        <h4>Sửa chữa laptop</h4>
                        <p>Dịch vụ sửa chữa laptop chuyên nghiệp, uy tín với đội ngũ kỹ thuật viên giàu kinh nghiệm</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-desktop fa-3x text-primary mb-3"></i>
                        <h4>Sửa chữa máy tính</h4>
                        <p>Sửa chữa, bảo trì và nâng cấp máy tính để bàn với linh kiện chính hãng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-network-wired fa-3x text-primary mb-3"></i>
                        <h4>Lắp đặt mạng</h4>
                        <p>Thi công, lắp đặt hệ thống mạng LAN, WiFi cho văn phòng và hộ gia đình</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Cam kết -->
<section class="features bg-light py-5">
    <div class="container-fluid px-4">
        <h2 class="text-center mb-5">Cam kết của chúng tôi</h2>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-clock fa-2x text-primary mb-3"></i>
                    <h5>Phục vụ 24/7</h5>
                    <p>Sẵn sàng hỗ trợ mọi lúc mọi nơi</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-tools fa-2x text-primary mb-3"></i>
                    <h5>Kỹ thuật chuyên nghiệp</h5>
                    <p>Đội ngũ kỹ thuật viên giàu kinh nghiệm</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-shield-alt fa-2x text-primary mb-3"></i>
                    <h5>Bảo hành uy tín</h5>
                    <p>Cam kết bảo hành dài hạn cho dịch vụ</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="text-center">
                    <i class="fas fa-hand-holding-usd fa-2x text-primary mb-3"></i>
                    <h5>Giá cả hợp lý</h5>
                    <p>Chi phí cạnh tranh, minh bạch</p>
                </div>
            </div>
        </div>
    </div>
</section>

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