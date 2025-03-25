<?php
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductApiController
{
    private $productModel;
    private $db;
    
    public function __construct()
    {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }
    
    // Lấy danh sách sản phẩm
    public function index()
    {
        header('Content-Type: application/json');
        $products = $this->productModel->getProducts();
        echo json_encode($products);
    }
    
    // Lấy thông tin sản phẩm theo ID
    public function show($id)
    {
        header('Content-Type: application/json');
        $product = $this->productModel->getProductById($id);
        if ($product) {
            echo json_encode($product);
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'Product not found']);
        }
    }
    
    // Thêm sản phẩm mới
    public function store()
    {
        // Always output valid JSON
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Log full request for debugging
            error_log("RAW REQUEST BODY START");
            error_log(file_get_contents("php://input"));
            error_log("RAW REQUEST BODY END");
            
            // Lấy dữ liệu từ request
            $jsonData = file_get_contents("php://input");
            $data = json_decode($jsonData, true);
            
            // Nếu không parse được JSON, trả về lỗi
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid JSON data: ' . json_last_error_msg(),
                    'raw_data' => substr($jsonData, 0, 100) // Hiển thị 100 ký tự đầu tiên để debug
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            
            // Debug: log dữ liệu nhận được
            error_log("Add product - Received data: " . print_r($data, true));
            
            // Kiểm tra nếu data là null
            if ($data === null) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ: Empty JSON object'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = isset($data['category_id']) && !empty($data['category_id']) ? $data['category_id'] : null;
            
            // Kiểm tra danh mục nếu có
            if ($category_id !== null) {
                $categoryModel = new CategoryModel($this->db);
                $category = $categoryModel->getCategoryById($category_id);
                if (!$category) {
                    http_response_code(400);
                    echo json_encode([
                        'status' => 'error',
                        'message' => 'Danh mục không tồn tại',
                        'errors' => ['category_id' => 'Danh mục với ID ' . $category_id . ' không tồn tại']
                    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    return;
                }
            }
            
            // Xử lý ảnh nếu có trong dữ liệu
            $image = null;
            if (isset($data['image']) && !empty($data['image'])) {
                $image = $this->saveBase64Image($data['image']);
            }
            
            $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
            if (is_array($result)) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $result
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(201);
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Product created successfully'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Database error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            error_log("Add product error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Server error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
    
    // Xử lý lưu ảnh base64
    private function saveBase64Image($base64String) {
        try {
            // Tạo thư mục uploads/products nếu chưa tồn tại
            $uploadDir = 'uploads/products/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0777, true)) {
                    error_log("Failed to create directory: $uploadDir");
                    return null;
                }
            }
            
            // Xử lý chuỗi base64
            if (preg_match('/^data:image\/(\w+);base64,/', $base64String, $matches)) {
                $imageType = $matches[1];
                $base64String = substr($base64String, strpos($base64String, ',') + 1);
                $base64String = str_replace(' ', '+', $base64String);
                $imageData = base64_decode($base64String);
                
                if ($imageData) {
                    $fileName = uniqid() . '.' . $imageType;
                    $filePath = $uploadDir . $fileName;
                    
                    if (file_put_contents($filePath, $imageData)) {
                        error_log("Image saved successfully: $filePath");
                        return $filePath;
                    } else {
                        error_log("Failed to save image to: $filePath");
                    }
                } else {
                    error_log("Failed to decode base64 image data");
                }
            } else {
                error_log("Invalid base64 image format");
            }
        } catch (Exception $e) {
            error_log("Error saving base64 image: " . $e->getMessage());
        }
        
        return null;
    }
    
    // Cập nhật sản phẩm theo ID
    public function update($id)
    {
        // Always output valid JSON
        header('Content-Type: application/json; charset=utf-8');
        
        try {
            // Log full request for debugging
            error_log("RAW REQUEST BODY START");
            error_log(file_get_contents("php://input"));
            error_log("RAW REQUEST BODY END");
            
            // Lấy dữ liệu từ request
            $jsonData = file_get_contents("php://input");
            $data = json_decode($jsonData, true);
            
            // Nếu không parse được JSON, trả về lỗi
            if (json_last_error() !== JSON_ERROR_NONE) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Invalid JSON data: ' . json_last_error_msg(),
                    'raw_data' => substr($jsonData, 0, 100) // Hiển thị 100 ký tự đầu tiên để debug
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            
            // Debug: log dữ liệu nhận được
            error_log("Update product $id - Received data: " . print_r($data, true));
            
            // Kiểm tra nếu data là null
            if ($data === null) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ: Empty JSON object'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            
            $name = $data['name'] ?? '';
            $description = $data['description'] ?? '';
            $price = $data['price'] ?? '';
            $category_id = isset($data['category_id']) && !empty($data['category_id']) ? $data['category_id'] : null;
            
            // Kiểm tra dữ liệu
            $errors = [];
            if (empty($name)) {
                $errors[] = 'Tên sản phẩm không được để trống';
            }
            if (empty($description)) {
                $errors[] = 'Mô tả không được để trống';
            }
            if (!is_numeric($price) || $price < 0) {
                $errors[] = 'Giá sản phẩm không hợp lệ';
            }
            
            // Kiểm tra danh mục nếu có
            if ($category_id !== null) {
                $categoryModel = new CategoryModel($this->db);
                $category = $categoryModel->getCategoryById($category_id);
                if (!$category) {
                    $errors[] = 'Danh mục với ID ' . $category_id . ' không tồn tại';
                }
            }
            
            if (count($errors) > 0) {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $errors
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            
            // Lấy thông tin sản phẩm hiện tại để giữ ảnh cũ nếu không có ảnh mới
            $currentProduct = $this->productModel->getProductById($id);
            if (!$currentProduct) {
                http_response_code(404);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Product not found'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                return;
            }
            
            $image = $currentProduct->image ?? null;
            
            // Xử lý ảnh mới nếu có trong dữ liệu
            if (isset($data['image']) && !empty($data['image'])) {
                $newImage = $this->saveBase64Image($data['image']);
                if ($newImage) {
                    // Xóa ảnh cũ nếu có
                    if ($image && file_exists($image)) {
                        unlink($image);
                    }
                    $image = $newImage;
                }
            }
            
            $result = $this->productModel->updateProduct($id, $name, $description, $price, $category_id, $image);
            
            if (is_array($result)) {
                // Nếu kết quả là mảng, đó là lỗi validation
                http_response_code(400);
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Dữ liệu không hợp lệ',
                    'errors' => $result
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else if ($result) {
                echo json_encode([
                    'status' => 'success', 
                    'message' => 'Product updated successfully'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            } else {
                http_response_code(400);
                echo json_encode([
                    'status' => 'error', 
                    'message' => 'Product update failed'
                ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
            }
        } catch (PDOException $e) {
            error_log("PDO Error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Database error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            error_log("Update product error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode([
                'status' => 'error', 
                'message' => 'Server error: ' . $e->getMessage()
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
    
    // Xóa sản phẩm theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');
        
        try {
            // Debug
            error_log("Deleting product ID: $id");
            
            // Kiểm tra xem sản phẩm có tồn tại không
            $product = $this->productModel->getProductById($id);
            if (!$product) {
                http_response_code(404);
                echo json_encode(['status' => 'error', 'message' => 'Product not found']);
                return;
            }
            
            // Xóa ảnh nếu có
            if ($product->image && file_exists($product->image)) {
                unlink($product->image);
                error_log("Deleted image: {$product->image}");
            }
            
            $result = $this->productModel->deleteProduct($id);
            if ($result) {
                echo json_encode(['status' => 'success', 'message' => 'Product deleted successfully']);
            } else {
                http_response_code(400);
                echo json_encode(['status' => 'error', 'message' => 'Product deletion failed']);
            }
        } catch (Exception $e) {
            error_log("Delete product error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['status' => 'error', 'message' => 'Server error: ' . $e->getMessage()]);
        }
    }
} 