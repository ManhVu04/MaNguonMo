<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Thêm sản phẩm mới</h1>
        </div>
        <div class="card-body">
            <!-- Hiển thị thông báo lỗi -->
            <?php if (!empty($errors)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Lỗi nhập liệu!</strong>
                    <ul class="mb-0">
                        <?php foreach ($errors as $error): ?>
                            <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                        <?php endforeach; ?>
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <!-- Form thêm sản phẩm -->
            <form method="POST" action="/webbanhang/Product/save" enctype="multipart/form-data" 
                  onsubmit="return validateForm();" id="productForm">
                <div class="row g-3">
                    <!-- Tên sản phẩm -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="name" class="form-label fw-bold">Tên sản phẩm <span class="text-danger">*</span></label>
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   class="form-control" 
                                   placeholder="Nhập tên sản phẩm" 
                                   value="<?php echo isset($old['name']) ? htmlspecialchars($old['name']) : ''; ?>"
                                   required>
                            <small class="form-text text-muted">Tên sản phẩm từ 3-100 ký tự</small>
                        </div>
                    </div>

                    <!-- Giá -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="price" class="form-label fw-bold">Giá (VNĐ) <span class="text-danger">*</span></label>
                            <input type="number" 
                                   id="price" 
                                   name="price" 
                                   class="form-control" 
                                   placeholder="Ví dụ: 150000" 
                                   step="1000" 
                                   min="0" 
                                   value="<?php echo isset($old['price']) ? htmlspecialchars($old['price']) : ''; ?>"
                                   required>
                            <small class="form-text text-muted">Giá phải lớn hơn 0</small>
                        </div>
                    </div>

                    <!-- Danh mục -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="category_id" class="form-label fw-bold">Danh mục <span class="text-danger">*</span></label>
                            <select id="category_id" 
                                    name="category_id" 
                                    class="form-select" 
                                    required>
                                <option value="">-- Chọn danh mục --</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>" 
                                            <?php echo (isset($old['category_id']) && $old['category_id'] == $category->id) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($category->name, ENT_QUOTES, 'UTF-8'); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Hình ảnh -->
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="image" class="form-label fw-bold">Hình ảnh sản phẩm</label>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   class="form-control" 
                                   accept="image/*" 
                                   onchange="previewImage(event)">
                            <div id="imagePreview" class="mt-2"></div>
                            <small class="form-text text-muted">Định dạng hỗ trợ: JPG, PNG (tối đa 5MB)</small>
                        </div>
                    </div>

                    <!-- Mô tả -->
                    <div class="col-12">
                        <div class="form-group mb-3">
                            <label for="description" class="form-label fw-bold">Mô tả <span class="text-danger">*</span></label>
                            <textarea id="description" 
                                      name="description" 
                                      class="form-control" 
                                      rows="5" 
                                      placeholder="Nhập mô tả sản phẩm" 
                                      required><?php echo isset($old['description']) ? htmlspecialchars($old['description']) : ''; ?></textarea>
                            <small class="form-text text-muted">Mô tả chi tiết sản phẩm (tối đa 1000 ký tự)</small>
                        </div>
                    </div>
                </div>

                <!-- Nút thao tác -->
                <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-2"></i>Thêm sản phẩm
                    </button>
                    <a href="/webbanhang/Product" class="btn btn-secondary px-4">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Script validation và preview ảnh -->
<script>
function validateForm() {
    let isValid = true;
    const name = document.getElementById('name').value;
    const price = document.getElementById('price').value;
    const description = document.getElementById('description').value;

    // Reset thông báo lỗi trước đó
    document.querySelectorAll('.text-danger').forEach(el => el.remove());

    // Validate tên sản phẩm
    if (name.length < 3 || name.length > 100) {
        isValid = false;
        document.getElementById('name').insertAdjacentHTML('afterend', 
            '<small class="text-danger">Tên sản phẩm phải từ 3-100 ký tự</small>');
    }

    // Validate giá
    if (price <= 0) {
        isValid = false;
        document.getElementById('price').insertAdjacentHTML('afterend', 
            '<small class="text-danger">Giá phải lớn hơn 0</small>');
    }

    // Validate mô tả
    if (description.length > 1000) {
        isValid = false;
        document.getElementById('description').insertAdjacentHTML('afterend', 
            '<small class="text-danger">Mô tả không được vượt quá 1000 ký tự</small>');
    }

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
            img.style.maxWidth = '200px';
            img.className = 'img-thumbnail';
            preview.appendChild(img);
        }
        reader.readAsDataURL(file);
    }
}
</script>

<?php include 'app/views/shares/footer.php'; ?>