<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-0 mb-5">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">Quản lý danh mục - Frontend API</h1>
            <button type="button" class="btn btn-success btn-sm rounded-pill" id="btnAddCategory">
                <i class="fas fa-plus me-2"></i>Thêm danh mục mới
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
                                   placeholder="Tìm kiếm danh mục theo tên...">
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

            <!-- Danh sách danh mục -->
            <div id="categoryList" class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4"></div>
            
            <!-- Empty state -->
            <div id="emptyState" class="empty-state d-none">
                <div class="empty-state-icon">
                    <i class="fas fa-folder-open fa-4x text-primary mb-4"></i>
                    <div class="sparkles">
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                        <div class="sparkle"></div>
                    </div>
                </div>
                <h3 class="h5 text-muted mb-3">Không tìm thấy danh mục nào</h3>
                <p class="text-muted small">Hãy thử từ khóa khác hoặc thêm danh mục mới</p>
            </div>
        </div>
    </div>
</div>

<!-- Modal thêm/sửa danh mục -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalTitle">Thêm danh mục mới</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="categoryForm">
                    <input type="hidden" id="categoryId">
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" required>
                        <div class="invalid-feedback">Vui lòng nhập tên danh mục</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" rows="4"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" id="saveCategory">
                    <i class="fas fa-save me-2"></i>Lưu danh mục
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
                <input type="hidden" id="deleteCategoryId">
                <p>Bạn có chắc chắn muốn xóa danh mục <strong id="deleteCategoryName"></strong>?</p>
                <p class="text-danger fw-bold small">Lưu ý: Xóa danh mục sẽ ảnh hưởng đến các sản phẩm thuộc danh mục này!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-danger" id="confirmDelete">
                    <i class="fas fa-trash-alt me-2"></i>Xóa danh mục
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

.category-icon {
    height: 100px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #f8f9fa;
    font-size: 2.5rem;
    color: #0d6efd;
}

.description-preview {
    display: -webkit-box;
    -webkit-line-clamp: 3;
    line-clamp: 3;
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
</style>

<script>
$(document).ready(function() {
    // URL API cơ bản
    const baseApiUrl = '/webbanhang/api/category';
    let categoryList = [];
    
    // Khởi tạo các modal
    const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    
    // Tải danh sách danh mục khi trang được tải
    loadCategories();
    
    // Event handlers
    $('#btnAddCategory').click(function() {
        resetCategoryForm();
        $('#modalTitle').text('Thêm danh mục mới');
        categoryModal.show();
    });
    
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        const keyword = $('#searchKeyword').val().trim();
        // Hiện tại API không hỗ trợ tìm kiếm, nên chúng ta sẽ lọc ở client
        filterCategories(keyword);
    });
    
    $('#categoryForm').submit(function(e) {
        e.preventDefault();
        saveCategory();
    });
    
    $('#saveCategory').click(function() {
        $('#categoryForm').submit();
    });
    
    $('#confirmDelete').click(function() {
        const categoryId = $('#deleteCategoryId').val();
        if (categoryId) {
            deleteCategory(categoryId);
        }
    });
    
    // Tải danh sách danh mục
    function loadCategories() {
        $('#loading').removeClass('d-none');
        $('#categoryList').addClass('d-none');
        $('#emptyState').addClass('d-none');
        
        $.ajax({
            url: baseApiUrl,
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                $('#loading').addClass('d-none');
                
                if (Array.isArray(data) && data.length > 0) {
                    categoryList = data;
                    renderCategories(data);
                    $('#categoryList').removeClass('d-none');
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
    
    // Lọc danh mục theo từ khóa
    function filterCategories(keyword) {
        if (!keyword) {
            renderCategories(categoryList);
            return;
        }
        
        const filteredCategories = categoryList.filter(category => 
            category.name.toLowerCase().includes(keyword.toLowerCase()) || 
            (category.description && category.description.toLowerCase().includes(keyword.toLowerCase()))
        );
        
        if (filteredCategories.length > 0) {
            renderCategories(filteredCategories);
            $('#categoryList').removeClass('d-none');
            $('#emptyState').addClass('d-none');
        } else {
            $('#categoryList').addClass('d-none');
            $('#emptyState').removeClass('d-none');
        }
    }
    
    // Hiển thị danh sách danh mục
    function renderCategories(categories) {
        let html = '';
        
        $.each(categories, function(index, category) {
            html += `
                <div class="col">
                    <div class="card h-100 shadow-sm border-0 hover-card">
                        <div class="category-icon">
                            <i class="fas fa-folder"></i>
                        </div>
                        
                        <div class="card-body">
                            <h5 class="card-title">${category.name}</h5>
                            <div class="description-preview text-muted mb-3">
                                ${category.description || 'Không có mô tả'}
                            </div>
                        </div>
                        
                        <div class="card-footer bg-transparent border-0">
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn-action btn-edit" 
                                        onclick="editCategory(${category.id})" 
                                        data-bs-toggle="tooltip"
                                        title="Sửa">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn-action btn-view" 
                                            onclick="viewCategoryProducts(${category.id})"
                                            data-bs-toggle="tooltip" 
                                            title="Xem sản phẩm">
                                        <i class="fas fa-list"></i>
                                    </button>
                                    <button type="button" class="btn-action btn-delete"
                                            onclick="showDeleteConfirm(${category.id}, '${category.name}')"
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
        
        $('#categoryList').html(html);
        
        // Khởi tạo tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
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
    function resetCategoryForm() {
        $('#categoryId').val('');
        $('#name').val('');
        $('#description').val('');
        $('#categoryForm .is-invalid').removeClass('is-invalid');
    }
    
    // Lưu danh mục (thêm mới hoặc cập nhật)
    function saveCategory() {
        // Kiểm tra form hợp lệ
        const form = document.getElementById('categoryForm');
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }
        
        // Thu thập dữ liệu
        const categoryId = $('#categoryId').val();
        const categoryData = {
            name: $('#name').val(),
            description: $('#description').val()
        };
        
        // Xác định phương thức và URL
        const method = categoryId ? 'PUT' : 'POST';
        const url = categoryId ? `${baseApiUrl}/${categoryId}` : baseApiUrl;
        
        // Gửi yêu cầu API
        $.ajax({
            url: url,
            type: method,
            data: JSON.stringify(categoryData),
            contentType: 'application/json',
            dataType: 'json',
            success: function(response) {
                categoryModal.hide();
                showAlert('success', categoryId ? 'Cập nhật danh mục thành công' : 'Thêm danh mục mới thành công');
                loadCategories();
            },
            error: function(xhr, status, error) {
                console.error('Response Text:', xhr.responseText);
                let errorMessage = 'Lỗi khi lưu danh mục';
                
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
    
    // Lấy thông tin danh mục để chỉnh sửa
    window.editCategory = function(categoryId) {
        const category = categoryList.find(c => c.id == categoryId);
        if (category) {
            resetCategoryForm();
            
            $('#categoryId').val(category.id);
            $('#name').val(category.name);
            $('#description').val(category.description);
            
            $('#modalTitle').text('Chỉnh sửa danh mục');
            categoryModal.show();
        }
    };
    
    // Hiển thị modal xác nhận xóa
    window.showDeleteConfirm = function(categoryId, categoryName) {
        $('#deleteCategoryId').val(categoryId);
        $('#deleteCategoryName').text(categoryName);
        deleteModal.show();
    };
    
    // Xóa danh mục
    function deleteCategory(categoryId) {
        $.ajax({
            url: `${baseApiUrl}/${categoryId}`,
            type: 'DELETE',
            dataType: 'json',
            success: function(response) {
                deleteModal.hide();
                showAlert('success', 'Đã xóa danh mục thành công');
                loadCategories();
            },
            error: function(xhr, status, error) {
                deleteModal.hide();
                console.error('Response Text:', xhr.responseText);
                let errorMessage = 'Lỗi khi xóa danh mục';
                
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
    
    // Xem sản phẩm thuộc danh mục
    window.viewCategoryProducts = function(categoryId) {
        window.location.href = `/webbanhang/Product?category=${categoryId}`;
    };
});
</script>

<?php include 'app/views/shares/footer.php'; ?> 