<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Thêm sản phẩm mới (API)</h1>
        </div>
        
        <div class="card-body">
            <form id="add-product-form">
                <div class="row g-3">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Giá (VNĐ)</label>
                        <input type="number" id="price" name="price" class="form-control" min="0" step="1000" required>
                        <div class="invalid-feedback" id="price-error"></div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="" disabled selected>Đang tải danh mục...</option>
                        </select>
                        <div class="invalid-feedback" id="category-error"></div>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="image" class="form-label">Hình ảnh sản phẩm:</label>
                        <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                        <small class="form-text text-muted">Định dạng: JPG, PNG (tối đa 5MB).</small>
                        
                        <div id="image-preview" class="mt-3 text-center" style="display: none;">
                            <img id="preview-img" src="" alt="Product Image" class="img-thumbnail" style="max-height: 200px;">
                            <button type="button" class="btn btn-sm btn-danger mt-2" onclick="removeImage()">
                                <i class="fas fa-trash-alt me-2"></i>Xóa ảnh
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="/webbanhang/Product/listAPI" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Thêm sản phẩm
                    </button>
                </div>
            </form>
            
            <!-- Thông báo lỗi -->
            <div id="error-message" class="alert alert-danger mt-3" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <span id="error-text"></span>
            </div>
        </div>
    </div>
</div>

<?php include 'app/views/shares/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const form = document.getElementById('add-product-form');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    
    // Tải danh mục từ API
    fetch('/webbanhang/api/category')
    .then(response => {
        if (!response.ok) {
            throw new Error('Không thể tải danh mục');
        }
        return response.json();
    })
    .then(data => {
        const categorySelect = document.getElementById('category_id');
        categorySelect.innerHTML = '<option value="" disabled selected>-- Chọn danh mục --</option>';
        
        if (data.length === 0) {
            categorySelect.innerHTML += '<option value="" disabled>Không có danh mục nào</option>';
            return;
        }
        
        data.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
    })
    .catch(error => {
        console.error('Lỗi khi tải danh mục:', error);
        document.getElementById('category_id').innerHTML = '<option value="" disabled selected>Lỗi khi tải danh mục</option>';
    });
    
    // Xử lý submit form
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Reset thông báo lỗi
        errorMessage.style.display = 'none';
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        // Kiểm tra dữ liệu cơ bản phía client
        let isValid = true;
        
        if (!document.getElementById('name').value.trim()) {
            document.getElementById('name').classList.add('is-invalid');
            document.getElementById('name-error').textContent = 'Vui lòng nhập tên sản phẩm';
            isValid = false;
        }
        
        if (!document.getElementById('description').value.trim()) {
            document.getElementById('description').classList.add('is-invalid');
            document.getElementById('description-error').textContent = 'Vui lòng nhập mô tả sản phẩm';
            isValid = false;
        }
        
        const price = document.getElementById('price').value;
        if (!price || parseFloat(price) <= 0) {
            document.getElementById('price').classList.add('is-invalid');
            document.getElementById('price-error').textContent = 'Vui lòng nhập giá hợp lệ';
            isValid = false;
        }
        
        if (!document.getElementById('category_id').value) {
            document.getElementById('category_id').classList.add('is-invalid');
            document.getElementById('category-error').textContent = 'Vui lòng chọn danh mục';
            isValid = false;
        }
        
        if (!isValid) return;
        
        // Thu thập dữ liệu form
        const formData = new FormData(this);
        const jsonData = {};
        
        // Chuyển FormData thành JSON
        formData.forEach((value, key) => {
            // Bỏ qua file image trong JSON
            if (key !== 'image') {
                jsonData[key] = value;
            }
        });
        
        // Xử lý trường hợp có file ảnh
        const imageFile = document.getElementById('image').files[0];
        if (imageFile) {
            // Trong API thực tế, bạn sẽ cần upload file trước và nhận đường dẫn trả về
            // Đây chỉ là giả lập, ta giả định ảnh được lưu ở đường dẫn này
            jsonData.image = 'uploads/products/' + imageFile.name;
        }
        
        // Gửi dữ liệu đến API
        fetch('/webbanhang/api/product', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Product created successfully') {
                // Tạo và hiển thị thông báo thành công
                const toast = document.createElement('div');
                toast.className = 'position-fixed bottom-0 end-0 p-3';
                toast.style.zIndex = '5';
                toast.innerHTML = `
                    <div class="toast show" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-header bg-success text-white">
                            <i class="fas fa-check-circle me-2"></i>
                            <strong class="me-auto">Thành công</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                        </div>
                        <div class="toast-body">
                            Sản phẩm đã được thêm thành công.
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Chuyển hướng sau 1 giây
                setTimeout(() => {
                    location.href = '/webbanhang/Product/listAPI';
                }, 1000);
            } else if (data.errors) {
                // Hiển thị lỗi validation từ server
                Object.keys(data.errors).forEach(field => {
                    const element = document.getElementById(field);
                    if (element) {
                        element.classList.add('is-invalid');
                        const errorElement = document.getElementById(`${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field];
                        }
                    }
                });
                
                errorMessage.style.display = 'block';
                errorText.textContent = 'Vui lòng kiểm tra lại thông tin sản phẩm';
            } else {
                // Hiển thị lỗi chung
                errorMessage.style.display = 'block';
                errorText.textContent = data.message || 'Đã xảy ra lỗi khi thêm sản phẩm';
            }
        })
        .catch(error => {
            console.error('Lỗi khi gửi dữ liệu:', error);
            errorMessage.style.display = 'block';
            errorText.textContent = 'Lỗi kết nối đến máy chủ. Vui lòng thử lại sau.';
        });
    });

    // Thêm hàm previewImage và removeImage
    function previewImage(event) {
        const file = event.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    }

    function removeImage() {
        document.getElementById('image').value = '';
        document.getElementById('image-preview').style.display = 'none';
    }
});
</script> 