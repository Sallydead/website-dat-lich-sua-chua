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

        <!-- Quy trình làm việc -->
        <section class="process py-5 bg-light">
            <h2 class="text-center mb-5 reveal">Quy trình làm việc</h2>
            <div class="row">
                <div class="col-lg-10 mx-auto">
                    <div class="timeline position-relative">
                        <div class="timeline-item left reveal">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="timeline-icon bg-primary text-white">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h5 class="mb-3">Tiếp nhận yêu cầu</h5>
                                    <p class="text-muted mb-0">Khách hàng gửi yêu cầu sửa chữa trực tuyến hoặc gọi điện. Chúng tôi sẽ tiếp nhận và phản hồi trong vòng 30 phút.</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item right reveal">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="timeline-icon bg-success text-white">
                                        <i class="fas fa-search"></i>
                                    </div>
                                    <h5 class="mb-3">Kiểm tra và báo giá</h5>
                                    <p class="text-muted mb-0">Kỹ thuật viên sẽ kiểm tra thiết bị và đưa ra báo giá chi tiết. Khách hàng có thể theo dõi tiến độ trực tuyến.</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item left reveal">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="timeline-icon bg-info text-white">
                                        <i class="fas fa-tools"></i>
                                    </div>
                                    <h5 class="mb-3">Sửa chữa</h5>
                                    <p class="text-muted mb-0">Tiến hành sửa chữa theo quy trình chuyên nghiệp, sử dụng linh kiện chính hãng và công cụ hiện đại.</p>
                                </div>
                            </div>
                        </div>
                        <div class="timeline-item right reveal">
                            <div class="card border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="timeline-icon bg-warning text-white">
                                        <i class="fas fa-check-circle"></i>
                                    </div>
                                    <h5 class="mb-3">Bàn giao và bảo hành</h5>
                                    <p class="text-muted mb-0">Bàn giao thiết bị đã sửa chữa và cung cấp chế độ bảo hành. Hỗ trợ kỹ thuật sau bán hàng 24/7.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Đội ngũ kỹ thuật -->
        <section class="team py-5">
            <h2 class="text-center mb-5 reveal">Đội ngũ kỹ thuật</h2>
            <div class="row g-4">
                <?php
                // Lấy 2 KTV ngẫu nhiên
                $stmt_ktv = $pdo->prepare("
                    SELECT u.id, u.username, u.role, ui.fullname, ui.avatar
                    FROM users u
                    LEFT JOIN user_infos ui ON u.id = ui.user_id
                    WHERE u.role = 3
                    ORDER BY RAND()
                    LIMIT 2
                ");
                $stmt_ktv->execute();
                $ktv_list = $stmt_ktv->fetchAll();
                
                // Lấy 2 CSKH ngẫu nhiên
                $stmt_cskh = $pdo->prepare("
                    SELECT u.id, u.username, u.role, ui.fullname, ui.avatar
                    FROM users u
                    LEFT JOIN user_infos ui ON u.id = ui.user_id
                    WHERE u.role = 2
                    ORDER BY RAND()
                    LIMIT 2
                ");
                $stmt_cskh->execute();
                $cskh_list = $stmt_cskh->fetchAll();
                
                // Gộp 2 mảng
                $staff_list = array_merge($ktv_list, $cskh_list);
                // Xáo trộn mảng
                shuffle($staff_list);
                
                foreach($staff_list as $index => $staff):
                    $delay = ($index * 0.2) . "s";
                    $name = $staff['fullname'] ?: $staff['username'];
                    $role_text = ($staff['role'] == 3) ? "Kỹ thuật viên" : "Chuyên viên CSKH";
                    $avatar = $staff['avatar'] ? "/assets/media/uploads/avatars/{$staff['avatar']}" : "/assets/media/uploads/avatars/default.jpg";
                ?>
                <div class="col-md-3 reveal" style="transition-delay: <?php echo $delay; ?>">
                    <div class="card team-card border-0 shadow-sm h-100">
                        <div class="team-img-container">
                            <img src="<?php echo $avatar; ?>" class="card-img-top" alt="<?php echo $name; ?>" style="height: 250px; object-fit: cover;">
                            <div class="team-overlay">
                                <div class="team-social">
                                            <i class="fas fa-star" style="color: yellow;"></i>
                                            <i class="fas fa-star" style="color: yellow;"></i>
                                            <i class="fas fa-star" style="color: yellow;"></i>
                                            <i class="fas fa-star" style="color: yellow;"></i>
                                            <i class="fas fa-star" style="color: yellow;"></i>
                                </div>
                            </div>
                        </div>
                        <div class="card-body text-center">
                            <h5 class="card-title mb-1"><?php echo $name; ?></h5>
                            <p class="text-muted"><?php echo $role_text; ?></p>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Đánh giá khách hàng -->
        <section class="testimonials py-5 bg-light">
            <h2 class="text-center mb-5 reveal">Khách hàng nói gì về chúng tôi</h2>
            <div class="row">
                <div class="col-lg-8 mx-auto">
                    <div id="testimonialCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-5">
                                        <img src="/assets/media/uploads/avatars/mark-zuckerberg.jpg" class="rounded-circle mb-4" width="80" height="80" alt="Khách hàng" style="width: 80px;height: 80px;object-fit: cover;border-radius: 50%;">
                                        <p class="lead mb-4">"Dịch vụ sửa chữa laptop của Vũ Chí Linh Computer thực sự xuất sắc. Kỹ thuật viên rất chuyên nghiệp và thân thiện. Máy tính của tôi hoạt động tốt như mới."</p>
                                        <h5 class="mb-1">Mark Zuckerberg</h5>
                                        <p class="text-muted">CEO Facebook</p>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-5">
                                        <img src="/assets/media/uploads/avatars/kim-jong-un.jpg" class="rounded-circle mb-4" width="80" height="80" alt="Khách hàng" style="width: 80px;height: 80px;object-fit: cover;border-radius: 50%;">
                                        <p class="lead mb-4">"Tôi đã sử dụng dịch vụ của Vũ Chí Linh Computer nhiều lần và luôn hài lòng. Giá cả hợp lý, chất lượng tốt và đặc biệt là dịch vụ khách hàng rất chu đáo."</p>
                                        <h5 class="mb-1">Mr.Ủn</h5>
                                        <p class="text-muted">Nhân viên văn phòng</p>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star-half-alt"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="carousel-item">
                                <div class="card border-0 shadow-sm">
                                    <div class="card-body text-center p-5">
                                        <img src="/assets/media/uploads/avatars/elon-musk.jpg" class="rounded-circle mb-4" width="80" height="80" alt="Khách hàng" style="width: 80px;height: 80px;object-fit: cover;border-radius: 50%;">
                                        <p class="lead mb-4">"Laptop của tôi bị hỏng nặng, nhiều nơi từ chối sửa nhưng Vũ Chí Linh Computer đã khắc phục thành công. Tôi rất ấn tượng với kỹ năng chuyên môn của họ."</p>
                                        <h5 class="mb-1">Elon Musk</h5>
                                        <p class="text-muted">CEO Tesla</p>
                                        <div class="text-warning">
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#testimonialCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                        <div class="carousel-indicators position-relative mt-4">
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="0" class="active bg-primary" aria-current="true"></button>
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="1" class="bg-primary"></button>
                            <button type="button" data-bs-target="#testimonialCarousel" data-bs-slide-to="2" class="bg-primary"></button>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Liên hệ -->
        <section class="contact py-5">
            <div class="container-fluid px-4">
                <div class="row align-items-center">
                    <div class="col-md-6 reveal">
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
                    <div class="col-md-6 reveal">
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