<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Chỉnh sửa sản phẩm (API)</h1>
        </div>
        
        <div class="card-body">
            <!-- Skeleton loader khi đang tải dữ liệu -->
            <div id="form-skeleton">
                <div class="skeleton-title mb-4"></div>
                <div class="skeleton-text mb-3"></div>
                <div class="skeleton-text mb-3"></div>
                <div class="skeleton-text mb-3"></div>
                <div class="skeleton-text mb-3"></div>
                <div class="d-flex justify-content-between mt-4">
                    <div class="skeleton-button" style="width: 100px; height: 38px;"></div>
                    <div class="skeleton-button" style="width: 100px; height: 38px;"></div>
                </div>
            </div>
            
            <!-- Form chỉnh sửa sản phẩm -->
            <form id="edit-product-form" style="display: none;">
                <input type="hidden" id="product-id" name="id">
                
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
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="/webbanhang/Product/listAPI" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu thay đổi
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

<style>
/* Skeleton loader styles */
.skeleton-title, .skeleton-text, .skeleton-button {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
    border-radius: 4px;
}

.skeleton-title {
    height: 32px;
    width: 60%;
}

.skeleton-text {
    height: 38px;
    width: 100%;
}

.skeleton-button {
    border-radius: 4px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Lấy ID sản phẩm từ URL
    const urlParts = window.location.pathname.split('/');
    const productId = urlParts[urlParts.length - 1];
    
    const formSkeleton = document.getElementById('form-skeleton');
    const form = document.getElementById('edit-product-form');
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    
    if (!productId || isNaN(productId)) {
        formSkeleton.style.display = 'none';
        errorMessage.style.display = 'block';
        errorText.textContent = 'ID sản phẩm không hợp lệ';
        return;
    }
    
    // Khởi tạo các biến cho dữ liệu
    let categories = [];
    let product = null;
    
    // Tải dữ liệu danh mục
    const fetchCategories = fetch('/webbanhang/api/category')
        .then(response => {
            if (!response.ok) throw new Error('Không thể tải danh mục');
            return response.json();
        })
        .then(data => { categories = data; });
    
    // Tải chi tiết sản phẩm
    const fetchProduct = fetch(`/webbanhang/api/product/${productId}`)
        .then(response => {
            if (!response.ok) throw new Error('Không thể tải thông tin sản phẩm');
            return response.json();
        })
        .then(data => { product = data; });
    
    // Đợi tải tất cả dữ liệu
    Promise.all([fetchCategories, fetchProduct])
        .then(() => {
            // Điền thông tin danh mục vào select
            const categorySelect = document.getElementById('category_id');
            categorySelect.innerHTML = '<option value="" disabled>-- Chọn danh mục --</option>';
            
            categories.forEach(category => {
                const option = document.createElement('option');
                option.value = category.id;
                option.textContent = category.name;
                categorySelect.appendChild(option);
            });
            
            // Điền thông tin sản phẩm vào form
            document.getElementById('product-id').value = product.id;
            document.getElementById('name').value = product.name;
            document.getElementById('description').value = product.description;
            document.getElementById('price').value = product.price;
            document.getElementById('category_id').value = product.category_id;
            
            // Hiển thị form, ẩn skeleton
            formSkeleton.style.display = 'none';
            form.style.display = 'block';
        })
        .catch(error => {
            console.error('Lỗi khi tải dữ liệu:', error);
            formSkeleton.style.display = 'none';
            errorMessage.style.display = 'block';
            errorText.textContent = 'Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.';
        });
    
    // Xử lý submit form
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Reset thông báo lỗi
        errorMessage.style.display = 'none';
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        
        // Thu thập dữ liệu form
        const formData = {
            name: document.getElementById('name').value.trim(),
            description: document.getElementById('description').value.trim(),
            price: document.getElementById('price').value,
            category_id: document.getElementById('category_id').value
        };
        
        // Kiểm tra dữ liệu cơ bản phía client
        let isValid = true;
        
        if (!formData.name) {
            document.getElementById('name').classList.add('is-invalid');
            document.getElementById('name-error').textContent = 'Vui lòng nhập tên sản phẩm';
            isValid = false;
        }
        
        if (!formData.description) {
            document.getElementById('description').classList.add('is-invalid');
            document.getElementById('description-error').textContent = 'Vui lòng nhập mô tả sản phẩm';
            isValid = false;
        }
        
        if (!formData.price || formData.price <= 0) {
            document.getElementById('price').classList.add('is-invalid');
            document.getElementById('price-error').textContent = 'Vui lòng nhập giá hợp lệ';
            isValid = false;
        }
        
        if (!formData.category_id) {
            document.getElementById('category_id').classList.add('is-invalid');
            document.getElementById('category-error').textContent = 'Vui lòng chọn danh mục';
            isValid = false;
        }
        
        if (!isValid) return;
        
        // Gửi dữ liệu đến API
        fetch(`/webbanhang/api/product/${productId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Product updated successfully') {
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
                            Sản phẩm đã được cập nhật thành công.
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Chuyển hướng sau 1 giây
                setTimeout(() => {
                    location.href = '/webbanhang/Product/listAPI';
                }, 1000);
            } else {
                // Hiển thị lỗi chung
                errorMessage.style.display = 'block';
                errorText.textContent = data.message || 'Đã xảy ra lỗi khi cập nhật sản phẩm';
            }
        })
        .catch(error => {
            console.error('Lỗi khi gửi dữ liệu:', error);
            errorMessage.style.display = 'block';
            errorText.textContent = 'Lỗi kết nối đến máy chủ. Vui lòng thử lại sau.';
        });
    });
});
</script> 