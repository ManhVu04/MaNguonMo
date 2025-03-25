<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-0 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Quản lý sản phẩm - Frontend API</h1>
            <button type="button" class="btn btn-success btn-sm rounded-pill" id="btnAddProduct">
                <i class="fas fa-plus me-2"></i>Thêm sản phẩm mới
            </button>
        </div>
        
        <div class="card-body py-3">
            <!-- Form tìm kiếm -->
            <div class="row mb-3">
                <div class="col-md-8 mx-auto">
                    <form id="searchForm" class="search-form">
                        <div class="input-group shadow-lg rounded-pill">
                            <span class="input-group-text bg-white border-0 rounded-pill-start ps-4">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" 
                                   id="searchKeyword" 
                                   class="form-control border-0 rounded-pill-end bg-white py-3"
                                   placeholder="Tìm kiếm sản phẩm theo tên, mô tả...">
                            <button type="submit" 
                                    class="btn btn-primary rounded-pill ms-2 px-4 fw-bold"
                                    aria-label="Tìm kiếm">
                                Tìm
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Hiển thị thông báo -->
            <div id="alertMessage" class="alert d-none mb-4"></div>

            <!-- Spinner loading -->
            <div id="loading" class="text-center my-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Đang tải...</span>
                </div>
                <p class="mt-2 text-muted">Đang tải dữ liệu...</p>
            </div>

            <!-- Danh sách sản phẩm -->
            <div id="productList" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
            
            <!-- Empty state -->
            <div id="emptyState" class="empty-state d-none">
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
        </div>
    </div>
</div>

<!-- Modal thêm/sửa sản phẩm -->
<div class="modal fade" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Thêm sản phẩm mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="productForm">
                    <input type="hidden" id="productId">
                    
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="name" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="name" required>
                                <div class="invalid-feedback">Vui lòng nhập tên sản phẩm</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control" id="description" rows="4"></textarea>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" min="0" step="1000" required>
                                <div class="invalid-feedback">Vui lòng nhập giá hợp lệ</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Danh mục</label>
                                <select class="form-select" id="category_id">
                                    <option value="" selected disabled>Chọn danh mục</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Hình ảnh</label>
                                <input type="file" class="form-control" id="image" accept="image/*">
                                <small class="form-text text-muted">Tối đa 5MB (JPG, PNG)</small>
                            </div>
                            
                            <div id="imagePreviewContainer" class="mb-3 d-none">
                                <img id="imagePreview" src="" alt="Preview" class="img-thumbnail mb-2">
                                <button type="button" class="btn btn-sm btn-danger d-block" id="removeImage">
                                    <i class="fas fa-trash-alt"></i> Xóa ảnh
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="saveProduct">
                    <i class="fas fa-save me-2"></i>Lưu sản phẩm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal xác nhận xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="deleteProductId">
                <p>Bạn có chắc chắn muốn xóa sản phẩm <strong id="deleteProductName"></strong>?</p>
                <p class="text-danger fw-bold small">Lưu ý: Hành động này không thể hoàn tác!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash-alt me-2"></i>Xóa sản phẩm
                </button>
            </div>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<style>
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
    height: 200px;
    object-fit: contain;
    transition: transform 0.3s ease;
    background-color: #f8f9fa;
    padding: 1rem;
}

.hover-card:hover .product-image {
    transform: scale(1.05);
}

.image-placeholder {
    height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    color: #6c757d;
}

.text-price {
    background: linear-gradient(135deg, #6c5ce7 0%, #a66efa 100%);
    color: white;
    padding: 0.25rem 1rem;
    border-radius: 20px;
    font-weight: 600;
    display: inline-block;
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

.btn-action {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
    background-color: #f8f9fa;
    color: #212529;
    border: none;
}

.btn-action:hover {
    transform: translateY(-3px);
}

.btn-edit:hover {
    background-color: #fff3cd;
    color: #ffc107;
}

.btn-delete:hover {
    background-color: #f8d7da;
    color: #dc3545;
}

.btn-view:hover {
    background-color: #d1e7ff;
    color: #0d6efd;
}

.btn-cart:hover {
    background-color: #d1e7dd;
    color: #198754;
}

#imagePreview {
    max-height: 150px;
    object-fit: contain;
}
</style>

<script>
$(document).ready(function() {
    // URL API cơ bản
    const baseApiUrl = '/webbanhang/api/product';
    let productList = [];
    let base64Image = null;
    
    // Khởi tạo các modal
    const productModal = new bootstrap.Modal(document.getElementById('productModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    // Tải danh sách sản phẩm khi trang được tải
    loadProducts();
    
    // Tải danh mục cho dropdown
    loadCategories();
    
    // Event handlers
    $('#btnAddProduct').click(function() {
        resetProductForm();
        $('#modalTitle').text('Thêm sản phẩm mới');
        productModal.show();
    });
    
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        const keyword = $('#searchKeyword').val().trim();
        loadProducts(keyword);
    });
    
    $('#productForm').submit(function(e) {
        e.preventDefault();
        saveProduct();
    });
    
    $('#saveProduct').click(function() {
        $('#productForm').submit();
    });
    
    $('#confirmDelete').click(function() {
        const productId = $('#deleteProductId').val();
        if (productId) {
            deleteProduct(productId);
        }
    });
    
    // Xử lý xem trước ảnh
    $('#image').change(function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                base64Image = e.target.result;
                $('#imagePreview').attr('src', e.target.result);
                $('#imagePreviewContainer').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            base64Image = null;
            $('#imagePreviewContainer').addClass('d-none');
        }
    });
    
    $('#removeImage').click(function() {
        $('#image').val('');
        base64Image = null;
        $('#imagePreviewContainer').addClass('d-none');
    });
    
    // Tải danh sách sản phẩm
    function loadProducts(keyword = '') {
        $('#loading').removeClass('d-none');
        $('#productList').addClass('d-none');
        $('#emptyState').addClass('d-none');
        
        let url = baseApiUrl;
        if (keyword) {
            url = `/webbanhang/Product/search?keyword=${encodeURIComponent(keyword)}`;
        }
        
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#loading').addClass('d-none');
                
                if (Array.isArray(data) && data.length > 0) {
                    renderProducts(data);
                    $('#productList').removeClass('d-none');
                } else {
                    $('#emptyState').removeClass('d-none');
                }
            },
            error: function(xhr, status, error) {
                $('#loading').addClass('d-none');
                showAlert('danger', 'Lỗi khi tải dữ liệu: ' + error);
            }
        });
    }
    
    // Hiển thị danh sách sản phẩm
    function renderProducts(products) {
        productList = products;
        let html = '';
        
        $.each(products, function(index, product) {
            html += `
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 hover-card">
                        <!-- Ảnh sản phẩm -->
                        ${product.image ? 
                            `<img src="/webbanhang/${product.image}" alt="${product.name}" class="product-image">` : 
                            `<div class="image-placeholder">
                                <i class="fas fa-image fa-3x mb-2"></i>
                                <p class="text-muted">Chưa có ảnh</p>
                             </div>`
                        }
                        
                        <div class="card-body">
                            <h5 class="card-title">${product.name}</h5>
                            <div class="description-preview text-muted mb-3">
                                ${product.description || 'Không có mô tả'}
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-price">
                                    ${new Intl.NumberFormat('vi-VN').format(product.price)} VNĐ
                                </span>
                                <span class="badge bg-secondary">
                                    ${product.category_name || 'Chưa phân loại'}
                                </span>
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn-action btn-edit" 
                                        onclick="editProduct(${product.id})" 
                                        data-bs-toggle="tooltip"
                                        title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn-action btn-view" 
                                            onclick="viewProduct(${product.id})"
                                            data-bs-toggle="tooltip" 
                                            title="Xem chi tiết">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button type="button" class="btn-action btn-cart"
                                            onclick="addToCart(${product.id})"
                                            data-bs-toggle="tooltip" 
                                            title="Thêm vào giỏ">
                                        <i class="fas fa-cart-plus"></i>
                                    </button>
                                    <button type="button" class="btn-action btn-delete"
                                            onclick="showDeleteConfirm(${product.id}, '${product.name}')"
                                            data-bs-toggle="tooltip" 
                                            title="Xóa">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        $('#productList').html(html);
        
        // Khởi tạo tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    }
    
    // Tải danh sách danh mục
    function loadCategories() {
        $.ajax({
            url: '/webbanhang/api/category',
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                if (Array.isArray(data) && data.length > 0) {
                    let options = '<option value="" selected disabled>Chọn danh mục</option>';
                    $.each(data, function(index, category) {
                        options += `<option value="${category.id}">${category.name}</option>`;
                    });
                    $('#category_id').html(options);
                }
            },
            error: function(xhr, status, error) {
                console.error('Lỗi khi tải danh mục:', error);
            }
        });
    }
    
    // Hiển thị thông báo
    function showAlert(type, message) {
        const alertDiv = $('#alertMessage');
        alertDiv.removeClass('d-none alert-success alert-danger alert-warning')
                .addClass(`alert-${type}`)
                .html(message)
                .show();
        
        // Tự động ẩn sau 5 giây
        setTimeout(function() {
            alertDiv.fadeOut('slow', function() {
                alertDiv.addClass('d-none');
            });
        }, 5000);
    }
    
    // Reset form
    function resetProductForm() {
        $('#productId').val('');
        $('#name').val('');
        $('#description').val('');
        $('#price').val('');
        $('#category_id').val('');
        $('#image').val('');
        base64Image = null;
        $('#imagePreviewContainer').addClass('d-none');
        $('#productForm .is-invalid').removeClass('is-invalid');
    }
    
    // Lưu sản phẩm (thêm mới hoặc cập nhật)
    function saveProduct() {
        // Xóa trạng thái lỗi cũ
        $('.is-invalid').removeClass('is-invalid');
        $('.invalid-feedback').hide();
        
        // Kiểm tra form hợp lệ
        const form = document.getElementById('productForm');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        
        // Thu thập dữ liệu
        const productId = $('#productId').val();
        const productData = {
            name: $('#name').val().trim(),
            description: $('#description').val().trim(),
            price: $('#price').val().trim()
        };
        
        // Kiểm tra danh mục - chỉ gửi category_id nếu có giá trị hợp lệ
        const categoryId = $('#category_id').val();
        if (categoryId && categoryId !== "") {
            productData.category_id = categoryId;
        }
        
        // Log dữ liệu sản phẩm để debug
        console.log('Sending product data:', JSON.stringify(productData));
        
        // Thêm ảnh nếu có
        if (base64Image) {
            productData.image = base64Image;
            console.log('Including image data (length):', base64Image.length);
        }
        
        // Xác định phương thức và URL
        const method = productId ? 'PUT' : 'POST';
        const url = productId ? `${baseApiUrl}/${productId}` : baseApiUrl;
        console.log('Request method:', method);
        console.log('Request URL:', url);
        
        // Hiển thị loading
        const saveBtn = $('#saveProduct');
        const originalBtnText = saveBtn.html();
        saveBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Đang lưu...');
        saveBtn.prop('disabled', true);
        
        // Disable tất cả input trong form
        $('#productForm input, #productForm textarea, #productForm select').prop('disabled', true);
        
        // Gửi yêu cầu API
        $.ajax({
            url: url,
            type: method,
            data: JSON.stringify(productData),
            contentType: 'application/json',
            dataType: 'json',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            timeout: 10000, // 10 giây timeout
            beforeSend: function(xhr) {
                xhr.setRequestHeader('Content-Type', 'application/json');
            },
            success: function(response) {
                // Khôi phục nút lưu và form inputs
                saveBtn.html(originalBtnText);
                saveBtn.prop('disabled', false);
                $('#productForm input, #productForm textarea, #productForm select').prop('disabled', false);
                
                console.log('Success response:', response);
                productModal.hide();
                showAlert('success', productId ? 'Cập nhật sản phẩm thành công' : 'Thêm sản phẩm mới thành công');
                loadProducts();
            },
            error: function(xhr, status, error) {
                // Khôi phục nút lưu và form inputs
                saveBtn.html(originalBtnText);
                saveBtn.prop('disabled', false);
                $('#productForm input, #productForm textarea, #productForm select').prop('disabled', false);
                
                console.error('Error status:', status);
                console.error('Error:', error);
                console.error('Response Text:', xhr.responseText);
                console.error('Status Code:', xhr.status);
                console.error('Status Text:', xhr.statusText);
                
                let errorMessage = 'Lỗi khi lưu sản phẩm';
                let detailErrors = [];
                let fieldErrors = {};
                
                // Cố gắng phân tích phản hồi JSON
                try {
                    if (xhr.responseJSON) {
                        const response = xhr.responseJSON;
                        
                        if (response.message) {
                            errorMessage = response.message;
                        }
                        
                        if (response.errors) {
                            if (Array.isArray(response.errors)) {
                                detailErrors = response.errors;
                            } else if (typeof response.errors === 'object') {
                                // Trường hợp errors là object với key là tên trường
                                fieldErrors = response.errors;
                                Object.keys(response.errors).forEach(key => {
                                    detailErrors.push(response.errors[key]);
                                    
                                    // Highlight field with error
                                    const field = $('#' + key);
                                    if (field.length) {
                                        field.addClass('is-invalid');
                                        field.siblings('.invalid-feedback').text(response.errors[key]).show();
                                    }
                                });
                            }
                        }
                    } else if (xhr.responseText) {
                        try {
                            const errorObj = JSON.parse(xhr.responseText);
                            if (errorObj.message) {
                                errorMessage = errorObj.message;
                            }
                            
                            if (errorObj.errors) {
                                if (Array.isArray(errorObj.errors)) {
                                    detailErrors = errorObj.errors;
                                } else if (typeof errorObj.errors === 'object') {
                                    fieldErrors = errorObj.errors;
                                    Object.keys(errorObj.errors).forEach(key => {
                                        detailErrors.push(errorObj.errors[key]);
                                        
                                        // Highlight field with error
                                        const field = $('#' + key);
                                        if (field.length) {
                                            field.addClass('is-invalid');
                                            field.siblings('.invalid-feedback').text(errorObj.errors[key]).show();
                                        }
                                    });
                                }
                            }
                        } catch (e) {
                            // Nếu responseText không phải JSON
                            console.error('Error parsing responseText:', e);
                            errorMessage = 'Lỗi không xác định: ' + error;
                        }
                    }
                } catch (e) {
                    console.error('Error handling response:', e);
                    errorMessage = 'Lỗi xử lý phản hồi: ' + error;
                }
                
                // Hiển thị thông báo lỗi
                let alertMessage = errorMessage;
                if (detailErrors.length > 0) {
                    alertMessage += '<ul class="mt-2 mb-0">';
                    detailErrors.forEach(err => {
                        alertMessage += `<li>${err}</li>`;
                    });
                    alertMessage += '</ul>';
                }
                
                showAlert('danger', alertMessage);
            }
        });
    }
    
    // Lấy thông tin sản phẩm để chỉnh sửa
    window.editProduct = function(productId) {
        const product = productList.find(p => p.id == productId);
        if (product) {
            resetProductForm();
            
            $('#productId').val(product.id);
            $('#name').val(product.name);
            $('#description').val(product.description);
            $('#price').val(product.price);
            $('#category_id').val(product.category_id);
            
            // Nếu có ảnh
            if (product.image) {
                $('#imagePreview').attr('src', `/webbanhang/${product.image}`);
                $('#imagePreviewContainer').removeClass('d-none');
            }
            
            $('#modalTitle').text('Chỉnh sửa sản phẩm');
            productModal.show();
        }
    };
    
    // Hiển thị modal xác nhận xóa
    window.showDeleteConfirm = function(productId, productName) {
        $('#deleteProductId').val(productId);
        $('#deleteProductName').text(productName);
        deleteModal.show();
    };
    
    // Xóa sản phẩm
    function deleteProduct(productId) {
        $.ajax({
            url: `${baseApiUrl}/${productId}`,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                deleteModal.hide();
                showAlert('success', 'Đã xóa sản phẩm thành công');
                loadProducts();
            },
            error: function(xhr, status, error) {
                deleteModal.hide();
                console.error('Response Text:', xhr.responseText);
                let errorMessage = 'Lỗi khi xóa sản phẩm';
                
                // Cố gắng phân tích phản hồi JSON
                try {
                    if (xhr.responseJSON) {
                        errorMessage += ': ' + (xhr.responseJSON.message || 'Không xác định');
                    } else if (xhr.responseText) {
                        const errorObj = JSON.parse(xhr.responseText);
                        errorMessage += ': ' + (errorObj.message || 'Không xác định');
                    }
                } catch (e) {
                    errorMessage += ': ' + error;
                    console.error('Error parsing response:', e);
                }
                
                showAlert('danger', errorMessage);
            }
        });
    }
    
    // Xem chi tiết sản phẩm
    window.viewProduct = function(productId) {
        window.location.href = `/webbanhang/Product/show/${productId}`;
    };
    
    // Thêm vào giỏ hàng
    window.addToCart = function(productId) {
        $.ajax({
            url: `/webbanhang/Product/addToCart/${productId}`,
            type: 'GET',
            success: function() {
                showAlert('success', 'Đã thêm sản phẩm vào giỏ hàng');
            },
            error: function() {
                showAlert('danger', 'Lỗi khi thêm vào giỏ hàng');
            }
        });
    };
});
</script>

<?php include 'app/views/shares/footer.php'; ?> 