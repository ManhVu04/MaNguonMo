<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Sửa sản phẩm</h1>
        </div>
        
        <div class="card-body">
            <form id="edit-product-form">
                <input type="hidden" id="id" name="id">
                <input type="hidden" id="existing_image" name="existing_image">
                
                <div class="row g-3">
                    <div class="col-md-12 mb-3">
                        <label for="name" class="form-label">Tên sản phẩm:</label>
                        <input type="text" id="name" name="name" class="form-control" required>
                    </div>
                    
                    <div class="col-md-12 mb-3">
                        <label for="description" class="form-label">Mô tả:</label>
                        <textarea id="description" name="description" class="form-control" rows="4" required></textarea>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="price" class="form-label">Giá:</label>
                        <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="category_id" class="form-label">Danh mục:</label>
                        <select id="category_id" name="category_id" class="form-select" required>
                            <option value="" disabled selected>Đang tải danh mục...</option>
                        </select>
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
                    <a href="/webbanhang/Product/list" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách sản phẩm
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

<?php include 'app/views/shares/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const productId = <?= $product->id ?>;
    const errorMessage = document.getElementById('error-message');
    const errorText = document.getElementById('error-text');
    
    // Tải thông tin sản phẩm từ API
    fetch(`/webbanhang/api/product/${productId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Không thể tải thông tin sản phẩm');
        }
        return response.json();
    })
    .then(data => {
        document.getElementById('id').value = data.id;
        document.getElementById('name').value = data.name;
        document.getElementById('description').value = data.description;
        document.getElementById('price').value = data.price;
        document.getElementById('category_id').value = data.category_id;
        
        // Hiển thị hình ảnh nếu có
        if (data.image) {
            document.getElementById('existing_image').value = data.image;
            document.getElementById('preview-img').src = '/webbanhang/' + data.image;
            document.getElementById('image-preview').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Lỗi khi tải thông tin sản phẩm:', error);
        errorMessage.style.display = 'block';
        errorText.textContent = 'Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.';
    });
    
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
        categorySelect.innerHTML = '<option value="" disabled>-- Chọn danh mục --</option>';
        
        data.forEach(category => {
            const option = document.createElement('option');
            option.value = category.id;
            option.textContent = category.name;
            categorySelect.appendChild(option);
        });
        
        // Chọn danh mục đã được thiết lập sau khi danh sách đã được tải
        const savedCategoryId = document.getElementById('category_id').value;
        if (savedCategoryId) {
            categorySelect.value = savedCategoryId;
        }
    })
    .catch(error => {
        console.error('Lỗi khi tải danh mục:', error);
        document.getElementById('category_id').innerHTML = '<option value="" disabled selected>Lỗi khi tải danh mục</option>';
    });
    
    // Xử lý submit form
    document.getElementById('edit-product-form').addEventListener('submit', function(event) {
        event.preventDefault();
        
        // Reset thông báo lỗi
        errorMessage.style.display = 'none';
        
        // Kiểm tra dữ liệu cơ bản phía client
        let isValid = true;
        
        const name = document.getElementById('name').value.trim();
        const description = document.getElementById('description').value.trim();
        const price = document.getElementById('price').value;
        const category_id = document.getElementById('category_id').value;
        
        if (!name) {
            document.getElementById('name').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!description) {
            document.getElementById('description').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!price || parseFloat(price) <= 0) {
            document.getElementById('price').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!category_id) {
            document.getElementById('category_id').classList.add('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            errorMessage.style.display = 'block';
            errorText.textContent = 'Vui lòng điền đầy đủ thông tin sản phẩm';
            return;
        }
        
        // Thu thập dữ liệu form
        const jsonData = {
            id: document.getElementById('id').value,
            name: name,
            description: description,
            price: price,
            category_id: category_id
        };
        
        // Xử lý trường hợp có file ảnh mới
        const imageFile = document.getElementById('image').files[0];
        if (imageFile) {
            // Trong API thực tế, bạn sẽ cần upload file trước và nhận đường dẫn trả về
            // Đây chỉ là giả lập, ta giả định ảnh được lưu ở đường dẫn này
            jsonData.image = 'uploads/products/' + imageFile.name;
        } else if (document.getElementById('existing_image').value) {
            // Sử dụng ảnh cũ nếu không có ảnh mới
            jsonData.image = document.getElementById('existing_image').value;
        }
        
        // Gửi dữ liệu đến API
        fetch(`/webbanhang/api/product/${jsonData.id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(jsonData)
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
                    location.href = '/webbanhang/Product';
                }, 1000);
            } else {
                // Hiển thị lỗi
                errorMessage.style.display = 'block';
                errorText.textContent = data.message || 'Cập nhật sản phẩm thất bại';
            }
        })
        .catch(error => {
            console.error('Lỗi khi cập nhật sản phẩm:', error);
            errorMessage.style.display = 'block';
            errorText.textContent = 'Lỗi kết nối đến máy chủ. Vui lòng thử lại sau.';
        });
    });
});

// Xem trước hình ảnh được chọn
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

// Xóa hình ảnh đã chọn
function removeImage() {
    document.getElementById('image').value = '';
    document.getElementById('existing_image').value = '';
    document.getElementById('image-preview').style.display = 'none';
}
</script>