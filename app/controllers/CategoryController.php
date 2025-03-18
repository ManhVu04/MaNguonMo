<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryController {
    private $categoryModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }

    public function index() {
        $categories = $this->categoryModel->getCategories();
        include 'app/views/category/list.php';
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';

            if ($this->categoryModel->addCategory($name, $description)) {
                header('Location: /webbanhang/Category');
            } else {
                echo "Có lỗi xảy ra khi thêm danh mục.";
            }
        }
    }

    public function update() {
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
        if ($this->categoryModel->deleteCategory($id)) {
            header('Location: /webbanhang/Category');
        } else {
            echo "Có lỗi xảy ra khi xóa danh mục.";
        }
    }
}
?>