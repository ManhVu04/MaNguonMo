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
    
    .btn-signup {
        background: linear-gradient(to right, #1cc88a, #169a6f);
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
    
    .btn-signup:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(28, 200, 138, 0.4);
    }
    
    .btn-signup::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(to right, #169a6f, #1cc88a);
        z-index: -2;
    }
    
    .btn-signup::before {
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
    
    .btn-signup:hover::before {
        width: 100%;
    }
    
    .login-link {
        color: #4e73df;
        font-weight: 600;
        transition: all 0.3s;
    }
    
    .login-link:hover {
        color: #224abe;
        text-decoration: underline;
    }
</style>

<section class="login-container d-flex justify-content-center align-items-center py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-8">
                <div class="auth-card">
                    <!-- Header with animation -->
                    <div class="auth-header text-center text-white">
                        <h2 class="fw-bold mb-0">Đăng ký tài khoản</h2>
                        <p class="mb-0">Vui lòng điền thông tin để tạo tài khoản mới</p>
                    </div>
                    
                    <!-- Registration Form -->
                    <div class="card-body p-4 p-md-5">
                        <?php if (isset($errors)): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Đã xảy ra lỗi:</strong>
                                <ul class="mb-0 mt-2">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?php echo $err; ?></li>
                                    <?php endforeach; ?>
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>
                        
                        <form action="/webbanhang/account/save" method="post" class="needs-validation" novalidate>
                            <div class="row g-3">
                                <!-- Username -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập" required>
                                        <label for="username"><i class="fas fa-user me-2"></i>Tên đăng nhập</label>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập tên đăng nhập
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Full Name -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="text" class="form-control" id="fullname" name="fullname" placeholder="Họ và tên" required>
                                        <label for="fullname"><i class="fas fa-address-card me-2"></i>Họ và tên</label>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập họ và tên
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Password -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
                                        <label for="password"><i class="fas fa-lock me-2"></i>Mật khẩu</label>
                                        <div class="invalid-feedback">
                                            Vui lòng nhập mật khẩu
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Confirm Password -->
                                <div class="col-md-6 mb-3">
                                    <div class="form-floating">
                                        <input type="password" class="form-control" id="confirmpassword" name="confirmpassword" placeholder="Xác nhận mật khẩu" required>
                                        <label for="confirmpassword"><i class="fas fa-key me-2"></i>Xác nhận mật khẩu</label>
                                        <div class="invalid-feedback">
                                            Vui lòng xác nhận mật khẩu
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Terms and Conditions -->
                            <div class="form-check mb-4 mt-2">
                                <input class="form-check-input" type="checkbox" id="terms" required>
                                <label class="form-check-label" for="terms">
                                    Tôi đồng ý với <a href="#" class="login-link">điều khoản và điều kiện</a>
                                </label>
                                <div class="invalid-feedback">
                                    Bạn phải đồng ý với điều khoản trước khi đăng ký
                                </div>
                            </div>
                            
                            <!-- Submit Button -->
                            <div class="text-center">
                                <button type="submit" class="btn btn-signup btn-lg px-5 py-3 mb-3 w-100">
                                    <i class="fas fa-user-plus me-2"></i>Đăng ký
                                </button>
                            </div>
                        </form>
                        
                        <!-- Login Link -->
                        <div class="text-center mt-3">
                            <p class="mb-0">Đã có tài khoản? <a href="/webbanhang/account/login" class="login-link">Đăng nhập ngay</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    // Form validation
    (function () {
        'use strict'
        
        // Fetch all forms we want to apply validation to
        var forms = document.querySelectorAll('.needs-validation')
        
        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    
                    form.classList.add('was-validated')
                }, false)
            })
            
        // Password confirmation validation
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmpassword');
        
        function validatePassword() {
            if (password.value != confirmPassword.value) {
                confirmPassword.setCustomValidity('Mật khẩu không khớp');
            } else {
                confirmPassword.setCustomValidity('');
            }
        }
        
        password.onchange = validatePassword;
        confirmPassword.onkeyup = validatePassword;
    })()
</script>
<?php include 'app/views/shares/footer.php'; ?>
