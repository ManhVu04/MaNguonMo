<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-5 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Danh sách sản phẩm</h1>
            <a href="/webbanhang/Product/add" class="btn btn-success btn-sm">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
            </a>
        </div>
        <div class="card-body">
            <!-- Form tìm kiếm -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <form method="GET" action="/webbanhang/Product/list" class="input-group">
                        <input type="text" 
                               name="search" 
                               class="form-control rounded-start" 
                               placeholder="Tìm kiếm theo tên sản phẩm..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button type="submit" class="btn btn-primary rounded-end">
                            <i class="fas fa-search"></i> Tìm
                        </button>
                    </form>
                </div>
            </div>

            <!-- Danh sách sản phẩm dạng lưới -->
            <?php if (empty($products)): ?>
                <div class="text-center text-muted py-5">
                    <i class="fas fa-box-open fa-3x mb-3"></i>
                    <p>Không có sản phẩm nào</p>
                </div>
            <?php else: ?>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                    <?php foreach ($products as $product): ?>
                        <div class="col">
                            <div class="card h-100 shadow-sm border-0 hover-card">
                                <div class="card-img-top text-center p-3 bg-light">
                                    <?php if ($product->image): ?>
                                        <img src="/webbanhang/<?php echo htmlspecialchars($product->image, ENT_QUOTES, 'UTF-8'); ?>" 
                                             alt="Product Image" 
                                             class="img-fluid" 
                                             style="max-height: 150px; object-fit: contain;">
                                    <?php else: ?>
                                        <i class="fas fa-image fa-3x text-muted"></i>
                                        <p class="text-muted mt-2">Chưa có ảnh</p>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <a href="/webbanhang/Product/show/<?php echo $product->id; ?>" 
                                           class="text-decoration-none text-dark">
                                            <?php echo htmlspecialchars($product->name, ENT_QUOTES, 'UTF-8'); ?>
                                        </a>
                                    </h5>
                                    <p class="card-text text-muted description-preview">
                                        <?php echo htmlspecialchars($product->description, ENT_QUOTES, 'UTF-8'); ?>
                                    </p>
                                    <p class="card-text fw-bold text-primary">
                                        <?php echo number_format($product->price, 0, ',', '.'); ?> VNĐ
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">Danh mục: <?php echo htmlspecialchars($product->category_name, ENT_QUOTES, 'UTF-8'); ?></small>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-0">
                                    <div class="btn-group w-100">
                                        <a href="/webbanhang/Product/edit/<?php echo $product->id; ?>" 
                                           class="btn btn-outline-warning btn-sm" 
                                           title="Sửa sản phẩm">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="/webbanhang/Product/addToCart/<?php echo $product->id; ?>" 
                                           class="btn btn-outline-primary btn-sm"
                                           title="Thêm vào giỏ hàng">
                                            <i class="fas fa-shopping-cart"></i>
                                        </a>
                                        <a href="/webbanhang/Product/delete/<?php echo $product->id; ?>" 
                                           class="btn btn-outline-danger btn-sm" 
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?');"
                                           title="Xóa sản phẩm">
                                            <i class="fas fa-trash"></i>
                                        </a>
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
.hover-card {
    transition: all 0.3s ease;
}
.hover-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1) !important;
}
.description-preview {
    max-height: 50px;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
}
.btn-group .btn {
    padding: 0.5rem 1rem;
    border-radius: 0;
    flex: 1;
    transition: all 0.2s;
}

.btn-group .btn:first-child {
    border-top-left-radius: 5px;
    border-bottom-left-radius: 5px;
}

.btn-group .btn:last-child {
    border-top-right-radius: 5px;
    border-bottom-right-radius: 5px;
}

.btn-group .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}
</style>

<?php include 'app/views/shares/footer.php'; ?>