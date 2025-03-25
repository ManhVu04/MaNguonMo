<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');
require_once('app/helpers/SessionHelper.php');

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index() {
        // Tất cả người dùng đều có thể xem danh mục
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add() {
        // Chỉ admin mới có thể thêm danh mục
        SessionHelper::requirePermission('add_category');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if ($this->categoryModel->addCategory($name, $description)) {
                header('Location: /webbanhang/Category');
            } else {
                echo "Có lỗi xảy ra khi thêm danh mục.";
            }
        } else {
            // Hiển thị form thêm danh mục
            include 'app/views/category/add.php';
        }
    }

    public function edit($id) {
        // Chỉ admin mới có thể sửa danh mục
        SessionHelper::requirePermission('edit_category');

        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            include 'app/views/category/edit.php';
        } else {
            header('Location: /webbanhang/Category');
        }
    }

    public function update() {
        // Chỉ admin mới có thể cập nhật danh mục
        SessionHelper::requirePermission('edit_category');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if ($this->categoryModel->updateCategory($id, $name, $description)) {
                header('Location: /webbanhang/Category');
            } else {
                echo "Có lỗi xảy ra khi cập nhật danh mục.";
            }
        }
    }

    public function delete($id) {
        // Chỉ admin mới có thể xóa danh mục
        SessionHelper::requirePermission('delete_category');

        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /webbanhang/Category');
        } else {
            echo "Có lỗi xảy ra khi xóa danh mục.";
        }
    }
}
?>