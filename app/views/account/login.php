<?php include 'app/views/shares/header.php'; ?>

<style>
    .login-container {
        min-height: 85vh;
        background: linear-gradient(135deg, rgba(78, 115, 223, 0.9) 0%, rgba(34, 74, 190, 0.8) 100%);
    }
    
    .auth-card {
        border: none;
        border-radius: 1.5rem;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1), 0 5px 15px rgba(0, 0, 0, 0.07);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
        background: white;
    }
    
    .auth-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15), 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    
    .auth-header {
        background-color: #4e73df;
        padding: 2rem 0;
        position: relative;
        overflow: hidden;
    }
    
    .auth-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.2) 0%, rgba(255,255,255,0) 80%);
        animation: pulse 15s infinite;
    }
    
    @keyframes pulse {
        0% { transform: scale(1); opacity: 0.3; }
        50% { transform: scale(1.05); opacity: 0.5; }
        100% { transform: scale(1); opacity: 0.3; }
    }
    
    .form-floating > .form-control:focus ~ label,
    .form-floating > .form-control:not(:placeholder-shown) ~ label {
        color: #4e73df;
        opacity: 0.8;
    }
    
    .form-control:focus {
        border-color: #4e73df;
        box-shadow: 0 0 0 0.25rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-login {
        background: linear-gradient(to right, #4e73df, #224abe);
        border: none;
        color: white;
        border-radius: 50px;
        padding: 0.75rem 2.5rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(34, 74, 190, 0.4);
    }
    
    .btn-login::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, #224abe, #4e73df);
        z-index: -2;
    }
    
    .btn-login::before {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.1);
        transition: all 0.3s;
        z-index: -1;
    }
    
    .btn-login:hover::before {
        width: 100%;
    }
    
    .social-btn {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 10px;
        transition: all 0.3s;
        background-color: #f0f2f5;
        color: #4e73df;
    }
    
    .social-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);
    }
    
    .social-btn.facebook:hover {
        background-color: #3b5998;
        color: white;
    }
    
    .social-btn.twitter:hover {
        background-color: #1da1f2;
        color: white;
    }
    
    .social-btn.google:hover {
        background-color: #db4437;
        color: white;
    }
    
    .separator {
        display: flex;
        align-items: center;
        text-align: center;
        color: #ccc;
        margin: 1.5rem 0;
    }
    
    .separator::before,
    .separator::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #eee;
    }
    
    .separator::before {
        margin-right: .5rem;
    }
    
    .separator::after {
        margin-left: .5rem;
    }
    
    .signup-link {
        color: #4e73df;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .signup-link:hover {
        color: #224abe;
        text-decoration: underline;
    }
    
    .forgot-link {
        color: #6c757d;
        transition: all 0.3s;
    }
    
    .forgot-link:hover {
        color: #4e73df;
    }
</style>

<section class="login-container d-flex justify-content-center align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="auth-card">
                    <!-- Header with animation -->
                    <div class="auth-header text-center text-white">
                        <h2 class="fw-bold mb-0">Đăng nhập</h2>
                        <p class="mb-0">Vui lòng đăng nhập để truy cập hệ thống</p>
                    </div>
                    
                    <!-- Login Form -->
                    <div class="card-body p-4 p-md-5">
                        <?php if(isset($error)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo $error; ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="/webbanhang/account/checklogin" method="post">
                            <!-- Username input with floating label -->
                            <div class="form-floating mb-4">
                                <input type="text" id="username" name="username" class="form-control" placeholder="Tên đăng nhập" required/>
                                <label for="username"><i class="fas fa-user me-2"></i>Tên đăng nhập</label>
                            </div>
                            
                            <!-- Password input with floating label -->
                            <div class="form-floating mb-4">
                                <input type="password" id="password" name="password" class="form-control" placeholder="Mật khẩu" required/>
                                <label for="password"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
                            </div>
                            
                            <!-- Remember me & Forgot password -->
                            <div class="d-flex justify-content-between mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" />
                                    <label class="form-check-label" for="rememberMe">
                                        Ghi nhớ đăng nhập
                                    </label>
                                </div>
                                <a href="#!" class="forgot-link">Quên mật khẩu?</a>
                            </div>
                            
                            <!-- Login button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-login btn-lg px-5 py-3 mb-3 w-100">
                                    <i class="fas fa-sign-in-alt me-2"></i>Đăng nhập
                                </button>
                            </div>
                        </form>
                        
                        <!-- Separator -->
                        <div class="separator">hoặc đăng nhập với</div>
                        
                        <!-- Social login -->
                        <div class="d-flex justify-content-center mb-4">
                            <a href="#!" class="social-btn facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#!" class="social-btn google">
                                <i class="fab fa-google"></i>
                            </a>
                            <a href="#!" class="social-btn twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                        </div>
                        
                        <!-- Sign up link -->
                        <div class="text-center">
                            <p class="mb-0">Chưa có tài khoản? <a href="/webbanhang/account/register" class="signup-link">Đăng ký ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php include 'app/views/shares/footer.php'; ?>
