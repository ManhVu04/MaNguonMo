<?php 
require_once 'app/helpers/SessionHelper.php';
include 'app/views/shares/header.php'; 
?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Danh sách sản phẩm</h1>
            <?php if (SessionHelper::hasPermission('add_product')): ?>
            <a href="/webbanhang/Product/add" class="btn btn-success btn-sm rounded-pill">
                <i class="fas fa-plus me-2"></i>Thêm mới
            </a>
            <?php endif; ?>
        </div>
        
        <div class="card-body">
            <!-- Form tìm kiếm nâng cao -->
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <form method="GET" action="/webbanhang/Product/search" class="search-form">
                        <div class="input-group shadow-lg rounded-pill">
                            <span class="input-group-text bg-white border-0 rounded-pill-start ps-4">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   name="keyword" 
                                   class="form-control border-0 rounded-pill-end bg-white py-3"
                                   placeholder="Tìm kiếm sản phẩm theo tên, mô tả..."
                                   value="<?php echo htmlspecialchars($_GET['keyword'] ?? ''); ?>">
                            <button type="submit" 
                                    class="btn btn-primary rounded-pill ms-2 px-4 fw-bold"
                                    aria-label="Tìm kiếm">
                                Tìm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Danh sách sản phẩm -->
            <?php if (empty($products)): ?>
                <!-- Empty state với animation -->
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-box-open fa-4x text-primary mb-4"></i>
                        <div class="sparkles">
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                            <div class="sparkle"></div>
                        </div>
                    </div>
                    <h3 class="h5 text-muted mb-3">Không tìm thấy sản phẩm nào</h3>
                    <p class="text-muted small">Hãy thử từ khóa khác hoặc thêm sản phẩm mới</p>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <!-- Image với skeleton loading -->
                                <div class="card-img-top text-center p-3 position-relative">
                                    <?php if ($product->image): ?>
                                        <img src="/webbanhang/<?php echo htmlspecialchars($product->image); ?>" 
                                             alt="<?php echo htmlspecialchars($product->name); ?>"
                                             class="img-fluid product-image"
                                             loading="lazy">
                                    <?php else: ?>
                                        <div class="image-placeholder">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                            <p class="text-muted mt-2 mb-0">Chưa có ảnh</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="card-body pb-0">
                                    <h5 class="card-title">
                                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" 
                                           class="text-decoration-none text-dark hover-primary">
                                            <?php echo htmlspecialchars($product->name); ?>
                                        </a>
                                    </h5>
                                    <div class="description-preview text-muted mb-3">
                                        <?php echo nl2br(htmlspecialchars(mb_substr($product->description, 0, 100)) . '...') ?>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="text-price">
                                            <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                                        </span>
                                        <span class="badge bg-secondary">
                                            <?php echo htmlspecialchars($product->category_name); ?>
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Footer actions -->
                                <div class="card-footer bg-transparent border-0 pt-0">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <?php if (SessionHelper::hasPermission('edit_product')): ?>
                                        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" 
                                           class="btn btn-icon btn-hover rounded-circle"
                                           data-bs-toggle="tooltip" 
                                           title="Chỉnh sửa">
                                            <i class="fas fa-pencil-alt text-warning"></i>
                                        </a>
                                        <?php endif; ?>
                                        
                                        <div class="d-flex gap-2">
                                            <?php if (SessionHelper::hasPermission('view_cart')): ?>
                                            <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" 
                                               class="btn btn-icon btn-hover rounded-circle"
                                               data-bs-toggle="tooltip"
                                               title="Thêm vào giỏ">
                                                <i class="fas fa-cart-plus text-primary"></i>
                                            </a>
                                            <?php endif; ?>
                                            
                                            <?php if (SessionHelper::hasPermission('delete_product')): ?>
                                            <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                                               class="btn btn-icon btn-hover rounded-circle"
                                               data-bs-toggle="tooltip"
                                               title="Xóa sản phẩm"
                                               onclick="return confirmAction()">
                                                <i class="fas fa-trash-alt text-danger"></i>
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
/* Animation effects */
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
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    font-size: 0.9rem;
    line-height: 1.4;
}

.empty-state {
    position: relative;
    padding: 3rem;
    text-align: center;
    background: rgba(245, 245, 245, 0.5);
    border-radius: 1rem;
    overflow: hidden;
}

.sparkle {
    position: absolute;
    background: rgba(255, 223, 0, 0.6);
    width: 8px;
    height: 8px;
    border-radius: 50%;
    animation: sparkle 2s infinite;
}

@keyframes sparkle {
    0% { opacity: 0; transform: scale(0); }
    50% { opacity: 1; transform: scale(1); }
    100% { opacity: 0; transform: scale(0) translate(50px, -50px); }
}

@media (max-width: 768px) {
    .card-title {
        font-size: 1.1rem;
    }
    
    .search-form .input-group {
        flex-direction: column;
        gap: 0.5rem;
    }
    
    .search-form button {
        width: 100%;
        border-radius: 20px !important;
    }
    
    .text-price {
        font-size: 0.9rem;
    }
}
</style>

<script>
// Khởi tạo tooltip
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
})

// Xác nhận xóa
function confirmAction() {
    return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');
}
</script>

<?php include 'app/views/shares/footer.php'; ?>