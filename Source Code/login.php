<?php
session_start();
require_once 'include/function.php';

$page_title = "Đăng nhập";

if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        
        if($user['role'] == 1) {
            header("Location: dashboard/index.php");
        } else {
            header("Location: index.php");
        }
        exit();
    } else {
        $error = "Tên đăng nhập hoặc mật khẩu không đúng";
    }
}

require_once 'include/layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h2 class="text-center mb-4">Đăng nhập</h2>
                
                <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control" name="username" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input type="password" class="form-control" name="password" required>
                    </div>
                    
                    <div class="d-grid">
                        <button type="submit" name="login" class="btn btn-primary">Đăng nhập</button>
                    </div>
                </form>
                
                <div class="text-center mt-3">
                    <p class="mb-0">Chưa có tài khoản? <a href="register.php">Đăng ký</a></p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'include/layout/footer.php'; ?>
