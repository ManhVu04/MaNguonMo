<?php
require_once('app/config/database.php');
require_once('app/models/CategoryModel.php');

class CategoryApiController
{
    private $categoryModel;
    private $db;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->categoryModel = new CategoryModel($this->db);
    }
    
    // Lấy danh sách danh mục
    public function index()
    {
        header('Content-Type: application/json');
        $categories = $this->categoryModel->getCategories();
        echo json_encode($categories);
    }
    
    // Lấy thông tin danh mục theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        // Giả sử chúng ta có phương thức getCategoryById trong model
        // Nếu không có, bạn có thể thêm phương thức này vào CategoryModel
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Category not found']);
        }
    }
    
    // Thêm danh mục mới
    public function store()
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Kiểm tra lỗi JSON
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid JSON: ' . json_last_error_msg()]);
            return;
        }
        
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        
        $result = $this->categoryModel->addCategory($name, $description);
        
        if($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Category creation failed']);
        }
    }
    
    // Cập nhật danh mục theo ID
    public function update($id)
    {
        header('Content-Type: application/json');
        
        // Lấy dữ liệu từ request
        $data = json_decode(file_get_contents("php://input"), true);
        
        // Nếu không parse được JSON, trả về lỗi
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid JSON data: ' . json_last_error_msg()]);
            return;
        }
        
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        
        try {
            $result = $this->categoryModel->updateCategory($id, $name, $description);
            
            if($result) {
                echo json_encode(['message' => 'Category updated successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Category update failed']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
        }
    }
    
    // Xóa danh mục theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');
        
        try {
            $result = $this->categoryModel->deleteCategory($id);
            
            if($result) {
                echo json_encode(['message' => 'Category deleted successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['message' => 'Category deletion failed']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Server error: ' . $e->getMessage()]);
        }
    }
} 