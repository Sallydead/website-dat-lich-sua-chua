<?php
session_start();
require_once 'include/function.php';

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

        <!-- Dịch vụ -->
        <section class="services py-5">
            <h2 class="text-center mb-5 reveal">Dịch vụ của chúng tôi</h2>
            <div class="row g-4">
                <div class="col-md-4 reveal" style="transition-delay: 0.1s;">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-laptop fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title">Sửa chữa laptop</h5>
                            <p class="card-text text-muted">Dịch vụ sửa chữa, thay thế linh kiện laptop chuyên nghiệp</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 reveal" style="transition-delay: 0.3s;">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-desktop fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title">Sửa chữa máy tính</h5>
                            <p class="card-text text-muted">Sửa chữa, nâng cấp và bảo trì máy tính để bàn tận nơi</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 reveal" style="transition-delay: 0.5s;">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-4">
                                <i class="fas fa-microchip fa-3x text-info"></i>
                            </div>
                            <h5 class="card-title">Thay thế linh kiện</h5>
                            <p class="card-text text-muted">Cung cấp và thay thế linh kiện chính hãng, bảo hành dài hạn</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Liên hệ -->
        <section class="contact py-5 bg-light">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <h2 class="mb-4">Liên hệ với chúng tôi</h2>
                        <div class="mb-4">
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-map-marker-alt fa-fw text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Địa chỉ</h5>
                                    <p class="mb-0">Số 298 Đường Lê Thanh Nghị, Thành phố Bắc Giang</p>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-phone fa-fw text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Hotline</h5>
                                    <p class="mb-0">0123456789</p>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-envelope fa-fw text-primary"></i>
                                </div>
                                <div class="ms-3">
                                    <h5 class="mb-1">Email</h5>
                                    <p class="mb-0">luvtinno123@gmail.com</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="ratio ratio-4x3">
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3371.9072569828018!2d105.81959267471234!3d20.94934999050235!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135add7a98d2c1d%3A0xe60529400caa5c57!2zVHLGsOG7nW5nIENhbyDEkOG6s25nIEPDtG5nIE5naOG7hyBCw6FjaCBLaG9hIEjDoCBO4buZaSBD4bufIFPhu58gVGhhbmggVHLDrA!5e1!3m2!1svi!2s!4v1740591062734!5m2!1svi!2s" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

<!-- Script cho hiệu ứng scroll reveal -->
<script>
window.addEventListener('scroll', reveal);
window.addEventListener('load', reveal);

function reveal() {
    var reveals = document.querySelectorAll('.reveal');
    
    for(var i = 0; i < reveals.length; i++) {
        var windowHeight = window.innerHeight;
        var revealTop = reveals[i].getBoundingClientRect().top;
        var revealPoint = 150;
        
        if(revealTop < windowHeight - revealPoint) {
            reveals[i].classList.add('active');
        }
    }
}
</script>

<?php require_once 'include/layout/footer.php'; ?> 