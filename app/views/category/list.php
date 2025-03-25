<?php include 'app/views/shares/header.php'; ?>

<div class="container mt-0">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h1 class="h4 mb-0">
                <i class="fas fa-tags me-2"></i>Quản lý danh mục
            </h1>
            <?php if (SessionHelper::hasPermission('add_category')): ?>
            <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus me-2"></i>Thêm danh mục mới
            </button>
            <?php endif; ?>
        </div>
        
        <div class="card-body py-3">
            <!-- Bảng danh mục -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th scope="col" width="5%">#</th>
                            <th scope="col" width="25%">Tên danh mục</th>
                            <th scope="col" width="50%">Mô tả</th>
                            <?php if (SessionHelper::hasPermission('edit_category') || SessionHelper::hasPermission('delete_category')): ?>
                            <th scope="col" width="20%" class="text-center">Thao tác</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($categories)): ?>
                            <?php foreach ($categories as $index => $category): ?>
                                <tr>
                                    <td><?php echo $index + 1; ?></td>
                                    <td><?php echo htmlspecialchars($category->name); ?></td>
                                    <td><?php echo htmlspecialchars($category->description); ?></td>
                                    <?php if (SessionHelper::hasPermission('edit_category') || SessionHelper::hasPermission('delete_category')): ?>
                                    <td class="text-center">
                                        <?php if (SessionHelper::hasPermission('edit_category')): ?>
                                        <button class="btn btn-warning btn-sm edit-category" 
                                                data-id="<?php echo $category->id; ?>"
                                                data-name="<?php echo htmlspecialchars($category->name); ?>"
                                                data-description="<?php echo htmlspecialchars($category->description); ?>"
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editCategoryModal">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <?php endif; ?>
                                        <?php if (SessionHelper::hasPermission('delete_category')): ?>
                                        <a href="/webbanhang/Category/delete/<?php echo $category->id; ?>" 
                                           class="btn btn-danger btn-sm"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?');">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <?php endif; ?>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo (SessionHelper::hasPermission('edit_category') || SessionHelper::hasPermission('delete_category')) ? '4' : '3'; ?>" class="text-center py-3 text-muted">
                                    <i class="fas fa-folder-open fa-3x mb-3"></i>
                                    <p class="mb-0">Chưa có danh mục nào</p>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?php if (SessionHelper::hasPermission('add_category')): ?>
<!-- Modal thêm danh mục -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/webbanhang/Category/add" method="POST" class="needs-validation" novalidate>
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-plus-circle me-2"></i>Thêm danh mục mới
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" required>
                        <div class="invalid-feedback">Vui lòng nhập tên danh mục</div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-2"></i>Lưu danh mục
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<?php if (SessionHelper::hasPermission('edit_category')): ?>
<!-- Modal sửa danh mục -->
<div class="modal fade" id="editCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/webbanhang/Category/update" method="POST" class="needs-validation" novalidate>
                <input type="hidden" name="id" id="edit_id">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title">
                        <i class="fas fa-edit me-2"></i>Sửa danh mục
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                        <div class="invalid-feedback">Vui lòng nhập tên danh mục</div>
                    </div>
                    <div class="mb-3">
                        <label for="edit_description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="edit_description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-save me-2"></i>Cập nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Custom CSS -->
<style>
.edit-category:hover {
    transform: scale(1.1);
}
.modal-content {
    border: none;
    border-radius: 15px;
}
.modal-header {
    border-top-left-radius: 15px;
    border-top-right-radius: 15px;
}
</style>

<!-- Custom JS -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        });
    });

    // Populate edit modal
    const editButtons = document.querySelectorAll('.edit-category');
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.dataset.id;
            const name = this.dataset.name;
            const description = this.dataset.description;

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_name').value = name;
            document.getElementById('edit_description').value = description;
        });
    });
});
</script>

</div><!-- End of container -->

<?php include 'app/views/shares/footer.php'; ?>