<?php include 'app/views/shares/header.php'; ?>
<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Danh sách sản phẩm (API)</h1>
            <a href="/webbanhang/Product/add" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-plus me-2"></i>Thêm mới
            </a>
        </div>
        
        <div class="card-body">
            <!-- Danh sách sản phẩm -->
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="product-list">
                <!-- Danh sách sản phẩm sẽ được tải từ API và hiển thị tại đây -->
                <div class="col skeleton-loader">
                    <div class="card h-100 shadow-sm border-0">
                        <div class="skeleton-img"></div>
                        <div class="card-body">
                            <div class="skeleton-title"></div>
                            <div class="skeleton-text"></div>
                            <div class="skeleton-text"></div>
                            <div class="d-flex justify-content-between mt-3">
                                <div class="skeleton-price"></div>
                                <div class="skeleton-badge"></div>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent">
                            <div class="d-flex justify-content-between">
                                <div class="skeleton-button"></div>
                                <div class="skeleton-button"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Card styles */
.hover-card {
    transition: transform 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94), 
                box-shadow 0.3s ease;
    border-radius: 12px;
    overflow: hidden;
    border: 1px solid rgba(0,0,0,0.05);
}

.hover-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
}

.product-image {
    max-height: 200px;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.hover-card:hover .product-image {
    transform: scale(1.05);
}

.text-price {
    background: linear-gradient(135deg, #6c5ce7 0%, #a66efa 100%);
    color: white;
    padding: 0.25rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
}

.btn-hover {
    transition: all 0.2s ease;
}

.btn-hover:hover {
    transform: translateY(-2px);
    background: rgba(0,0,0,0.05);
}

.description-preview {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 0.9rem;
    line-height: 1.4;
}

/* Skeleton loader styles */
.skeleton-loader .card {
    min-height: 350px;
}

.skeleton-img, .skeleton-title, .skeleton-text, .skeleton-price, .skeleton-badge, .skeleton-button {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: loading 1.5s infinite;
}

.skeleton-img {
    height: 200px;
    border-radius: 8px 8px 0 0;
}

.skeleton-title {
    height: 24px;
    margin-bottom: 15px;
    width: 80%;
    border-radius: 4px;
}

.skeleton-text {
    height: 16px;
    margin-bottom: 10px;
    border-radius: 4px;
}

.skeleton-price {
    height: 24px;
    width: 100px;
    border-radius: 20px;
}

.skeleton-badge {
    height: 24px;
    width: 80px;
    border-radius: 20px;
}

.skeleton-button {
    height: 30px;
    width: 30px;
    border-radius: 50%;
}

@keyframes loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
}

.empty-state {
    padding: 3rem;
    text-align: center;
    background: rgba(245, 245, 245, 0.5);
    border-radius: 1rem;
    margin-top: 2rem;
}
</style>

<?php include 'app/views/shares/footer.php'; ?>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const productList = document.getElementById('product-list');
    
    // Fetch sản phẩm từ API
    fetch('/webbanhang/api/product')
    .then(response => {
        if (!response.ok) {
            throw new Error('Lỗi kết nối API');
        }
        return response.json();
    })
    .then(data => {
        // Xóa skeleton loader
        productList.innerHTML = '';
        
        if (data.length === 0) {
            // Hiển thị trạng thái trống nếu không có sản phẩm
            productList.innerHTML = `
                <div class="col-12">
                    <div class="empty-state">
                        <i class="fas fa-box-open fa-4x text-muted mb-3"></i>
                        <h3 class="h5 text-muted mb-3">Không có sản phẩm nào</h3>
                        <p class="text-muted">Hãy thêm sản phẩm mới để bắt đầu.</p>
                    </div>
                </div>
            `;
            return;
        }
        
        // Tạo các card sản phẩm
        data.forEach(product => {
            const productCol = document.createElement('div');
            productCol.className = 'col';
            
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
                        <i class="fas fa-image fa-3x text-muted"></i>
                        <p class="text-muted mt-2 mb-0">Chưa có ảnh</p>
                    </div>
                `;
            }
            
            productCol.innerHTML = `
                <div class="card h-100 shadow-sm border-0 hover-card">
                    <div class="card-img-top text-center p-3">
                        ${imageHtml}
                    </div>
                    
                    <div class="card-body pb-0">
                        <h5 class="card-title">
                            <a href="/webbanhang/Product/show/${product.id}" class="text-decoration-none text-dark hover-primary">
                                ${product.name}
                            </a>
                        </h5>
                        <div class="description-preview text-muted mb-3">
                            ${product.description}
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-price">
                                ${Number(product.price).toLocaleString('vi-VN')} VNĐ
                            </span>
                            <span class="badge bg-secondary">
                                ${product.category_name || 'Chưa phân loại'}
                            </span>
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-0 pt-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="/webbanhang/Product/edit/${product.id}" 
                               class="btn btn-icon btn-hover rounded-circle"
                               data-bs-toggle="tooltip" 
                               title="Chỉnh sửa">
                                <i class="fas fa-pencil-alt text-warning"></i>
                            </a>
                            
                            <div class="d-flex gap-2">
                                <a href="/webbanhang/Product/addToCart/${product.id}" 
                                   class="btn btn-icon btn-hover rounded-circle"
                                   data-bs-toggle="tooltip"
                                   title="Thêm vào giỏ">
                                    <i class="fas fa-cart-plus text-primary"></i>
                                </a>
                                
                                <button 
                                   class="btn btn-icon btn-hover rounded-circle"
                                   data-bs-toggle="tooltip"
                                   title="Xóa sản phẩm"
                                   onclick="deleteProduct(${product.id})">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            productList.appendChild(productCol);
        });
        
        // Khởi tạo tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    })
    .catch(error => {
        console.error('Lỗi khi tải dữ liệu sản phẩm:', error);
        productList.innerHTML = `
            <div class="col-12">
                <div class="alert alert-danger" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Đã xảy ra lỗi khi tải dữ liệu sản phẩm. Vui lòng thử lại sau.
                </div>
            </div>
        `;
    });
});

function deleteProduct(id) {
    if (confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')) {
        fetch(`/webbanhang/api/product/${id}`, {
            method: 'DELETE'
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Lỗi khi xóa sản phẩm');
            }
            return response.json();
        })
        .then(data => {
            if (data.message === 'Product deleted successfully') {
                // Hiển thị thông báo thành công và tải lại trang
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
                            Sản phẩm đã được xóa thành công.
                        </div>
                    </div>
                `;
                document.body.appendChild(toast);
                
                // Tải lại dữ liệu sau 1 giây
                setTimeout(() => {
                    location.reload();
                }, 1000);
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