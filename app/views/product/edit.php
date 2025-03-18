<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-lg border-0 animate__animated animate__fadeIn">
        <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Sửa thông tin sản phẩm</h1>
            <a href="/webbanhang/product" class="btn btn-outline-light btn-sm">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
        <div class="card-body p-4">
            <!-- Thông báo lỗi -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show animate__animated animate__shakeX" role="alert">
                    <strong><i class="fas fa-exclamation-triangle me-2"></i>Lỗi nhập liệu!</strong>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Form sửa sản phẩm -->
            <form method="POST" action="/webbanhang/Product/update" enctype="multipart/form-data" 
                  onsubmit="return validateForm();" id="productForm" class="needs-validation" novalidate>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($product->id, ENT_QUOTES, 'UTF-8'); ?>">
                <input type="hidden" name="existing_image" value="<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>">

                <div class="row g-4">
                    <!-- Tên sản phẩm -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control shadow-sm" 
                                   placeholder="Tên sản phẩm" 
                                   value="<?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>
                            <label for="name">Tên sản phẩm <span class="text-danger">*</span></label>
                            <div class="invalid-feedback">Tên sản phẩm từ 3-100 ký tự.</div>
                        </div>
                    </div>

                    <!-- Giá -->
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   class="form-control shadow-sm" 
                                   placeholder="Giá sản phẩm" 
                                   step="1000" 
                                   min="0" 
                                   value="<?php echo htmlspecialchars($product->price, ENT_QUOTES, 'UTF-8'); ?>"
                                   required>
                            <label for="price">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <div class="invalid-feedback">Giá phải lớn hơn 0.</div>
                        </div>
                    </div>

                    <!-- Danh mục -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="category_id" class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                            <select id="category_id" 
                                    name="category_id" 
                                    class="form-select shadow-sm" 
                                    required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>" 
                                            <?php echo $category->id == $product->category_id ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <div class="invalid-feedback">Vui lòng chọn danh mục.</div>
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="image" class="form-label fw-bold">Hình ảnh sản phẩm</label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   class="form-control shadow-sm" 
                                   accept="image/*" 
                                   onchange="previewImage(event)">
                            <div id="imagePreview" class="mt-3 position-relative">
                                <?php if ($product->image): ?>
                                    <img src="/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                         alt="Product Image" 
                                         class="img-fluid rounded shadow-sm" 
                                         style="max-height: 200px; transition: all 0.3s;">
                                    <button type="button" 
                                            class="btn btn-danger btn-sm position-absolute top-0 end-0" 
                                            onclick="removeImage()"
                                            title="Xóa ảnh">
                                        <i class="fas fa-times"></i>
                                    </button>
                                <?php endif; ?>
                            </div>
                            <small class="form-text text-muted">Định dạng: JPG, PNG (tối đa 5MB).</small>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-control shadow-sm" 
                                      rows="5" 
                                      placeholder="Nhập mô tả sản phẩm" 
                                      required><?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?></textarea>
                            <div class="invalid-feedback">Mô tả không được vượt quá 1000 ký tự.</div>
                            <small class="form-text text-muted">Mô tả chi tiết sản phẩm.</small>
                        </div>
                    </div>
                </div>

                <!-- Nút thao tác -->
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary btn-lg shadow-sm">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
                    </button>
                    <a href="/webbanhang/Product" class="btn btn-outline-secondary btn-lg shadow-sm">
                        <i class="fas fa-times me-2"></i>Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS tùy chỉnh -->
<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #00c4ff);
}
.card {
    border-radius: 15px;
    overflow: hidden;
}
.form-control, .form-select {
    border-radius: 10px;
    transition: all 0.3s;
}
.form-control:focus, .form-select:focus {
    box-shadow: 0 0 10px rgba(0, 123, 255, 0.5);
    border-color: #007bff;
}
.btn {
    border-radius: 10px;
    transition: transform 0.2s;
}
.btn:hover {
    transform: translateY(-2px);
}
.img-fluid:hover {
    transform: scale(1.05);
}
</style>

<!-- Script validation, preview ảnh và xóa ảnh -->
<script>
function validateForm() {
    let form = document.getElementById('productForm');
    let isValid = form.checkValidity();

    if (!isValid) {
        form.classList.add('was-validated');
        return false;
    }

    const name = document.getElementById('name').value;
    const price = document.getElementById('price').value;
    const description = document.getElementById('description').value;

    if (name.length < 3 || name.length > 100) isValid = false;
    if (price <= 0) isValid = false;
    if (description.length > 1000) isValid = false;

    return isValid;
}

function previewImage(event) {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const img = document.createElement('img');
            img.src = e.target.result;
            img.className = 'img-fluid rounded shadow-sm';
            img.style.maxHeight = '200px';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}

function removeImage() {
    const preview = document.getElementById('imagePreview');
    preview.innerHTML = '';
    document.getElementById('image').value = '';
    document.querySelector('input[name="existing_image"]').value = '';
}
</script>

<?php include 'app/views/shares/footer.php'; ?>