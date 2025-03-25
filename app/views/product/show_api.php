<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white">
            <h1 class="h4 mb-0">Chi tiết sản phẩm (API)</h1>
        </div>
        
        <div class="card-body">
            <!-- Hiển thị skeleton loader khi đang tải dữ liệu -->
            <div id="product-skeleton" class="row">
                <div class="col-md-4 text-center">
                    <div class="skeleton-img mb-3" style="height: 300px; border-radius: 8px;"></div>
                </div>
                <div class="col-md-8">
                    <div class="skeleton-title mb-4"></div>
                    <div class="skeleton-text mb-3"></div>
                    <div class="skeleton-text mb-3"></div>
                    <div class="skeleton-text mb-3"></div>
                    <div class="skeleton-text mb-3"></div>
                    <div class="d-flex gap-3 mt-4">
                        <div class="skeleton-button" style="width: 120px; height: 40px; border-radius: 20px;"></div>
                        <div class="skeleton-button" style="width: 120px; height: 40px; border-radius: 20px;"></div>
                    </div>
                </div>
            </div>
            
            <!-- Chi tiết sản phẩm sẽ được tải từ API và hiển thị tại đây -->
            <div id="product-detail" class="row" style="display: none;">
                <!-- Nội dung chi tiết sản phẩm sẽ được điền vào đây bởi JavaScript -->
            </div>
            
            <!-- Message khi có lỗi -->
            <div id="error-message" class="alert alert-danger" style="display: none;">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Không thể tải thông tin sản phẩm. Vui lòng thử lại sau.
            </div>
        </div>
        
        <div class="card-footer bg-transparent">
            <div class="d-flex justify-content-between">
                <a href="/webbanhang/Product/listAPI" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i>Quay lại danh sách
                </a>
                <div id="action-buttons" style="display: none;">
                    <!-- Các nút hành động sẽ hiển thị ở đây -->
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Skeleton loader styles */
.skeleton-img, .skeleton-title, .skeleton-text, .skeleton-price, .skeleton-button {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

.skeleton-title {
    height: 32px;
    width: 70%;
    border-radius: 4px;
}

.skeleton-text {
    height: 16px;
    width: 100%;
    border-radius: 4px;
}

.skeleton-price {
    height: 30px;
    width: 150px;
    border-radius: 20px;
}

.skeleton-button {
    height: 38px;
    border-radius: 4px;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.product-image {
    max-height: 300px;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.product-price {
    font-size: 1.8rem;
    font-weight: 600;
    color: #6c5ce7;
}

.product-category {
    display: inline-block;
    padding: 0.3rem 1rem;
    background-color: #f1f1f1;
    border-radius: 20px;
    font-size: 0.9rem;
    color: #555;
}

.product-description {
    white-space: pre-line;
    line-height: 1.6;
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Lấy ID sản phẩm từ URL
    const urlParts = window.location.pathname.split('/');
    const productId = urlParts[urlParts.length - 1];
    
    if (!productId || isNaN(productId)) {
        document.getElementById('product-skeleton').style.display = 'none';
        document.getElementById('error-message').style.display = 'block';
        document.getElementById('error-message').innerHTML = `
            <i class="fas fa-exclamation-triangle me-2"></i>
            ID sản phẩm không hợp lệ.
        `;
        return;
    }
    
    // Fetch chi tiết sản phẩm từ API
    fetch(`/webbanhang/api/product/${productId}`)
    .then(response => {
        if (!response.ok) {
            throw new Error('Sản phẩm không tồn tại');
        }
        return response.json();
    })
    .then(product => {
        // Ẩn skeleton và hiển thị chi tiết sản phẩm
        document.getElementById('product-skeleton').style.display = 'none';
        const productDetail = document.getElementById('product-detail');
        productDetail.style.display = 'flex';
        
        // Xử lý hiển thị hình ảnh
        let imageHtml = '';
        if (product.image) {
            imageHtml = `
                <img src="/webbanhang/${product.image}" 
                    alt="${product.name}"
                    class="img-fluid product-image"
                    loading="lazy">
            `;
        } else {
            imageHtml = `
                <div class="image-placeholder">
                    <i class="fas fa-image fa-5x text-muted"></i>
                    <p class="text-muted mt-2">Chưa có ảnh sản phẩm</p>
                </div>
            `;
        }
        
        // Hiển thị nội dung sản phẩm
        productDetail.innerHTML = `
            <div class="col-md-4 text-center mb-4 mb-md-0">
                ${imageHtml}
            </div>
            <div class="col-md-8">
                <h2 class="mb-3">${product.name}</h2>
                
                <div class="d-flex flex-wrap align-items-center mb-4 gap-3">
                    <span class="product-price">${Number(product.price).toLocaleString('vi-VN')} VNĐ</span>
                    <span class="product-category">
                        <i class="fas fa-tag me-1"></i>
                        ${product.category_name || 'Chưa phân loại'}
                    </span>
                </div>
                
                <div class="mb-4">
                    <h5>Mô tả sản phẩm:</h5>
                    <div class="product-description">${product.description}</div>
                </div>
                
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="/webbanhang/Product/addToCart/${product.id}" class="btn btn-primary">
                        <i class="fas fa-cart-plus me-2"></i>Thêm vào giỏ hàng
                    </a>
                    <a href="/webbanhang/Cart/checkout" class="btn btn-success">
                        <i class="fas fa-credit-card me-2"></i>Mua ngay
                    </a>
                </div>
            </div>
        `;
        
        // Hiển thị các nút hành động
        const actionButtons = document.getElementById('action-buttons');
        actionButtons.style.display = 'block';
        actionButtons.innerHTML = `
            <a href="/webbanhang/Product/edit/${product.id}" class="btn btn-warning">
                <i class="fas fa-pencil-alt me-2"></i>Chỉnh sửa
            </a>
            <button class="btn btn-danger ms-2" onclick="deleteProduct(${product.id})">
                <i class="fas fa-trash-alt me-2"></i>Xóa sản phẩm
            </button>
        `;
    })
    .catch(error => {
        console.error('Lỗi khi tải dữ liệu sản phẩm:', error);
        document.getElementById('product-skeleton').style.display = 'none';
        document.getElementById('error-message').style.display = 'block';
    });
});

function deleteProduct(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        fetch(`/webbanhang/api/product/${id}`, {
            method: 'DELETE'
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Product deleted successfully') {
                alert('Đã xóa sản phẩm thành công!');
                window.location.href = '/webbanhang/Product/listAPI';
            } else {
                alert('Xóa sản phẩm thất bại');
            }
        })
        .catch(error => {
            console.error('Lỗi khi xóa sản phẩm:', error);
            alert('Đã xảy ra lỗi khi xóa sản phẩm');
        });
    }
}
</script> 