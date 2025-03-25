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
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        
        // Xử lý ảnh nếu có trong dữ liệu
        $image = null;
        if (isset($data['image']) && !empty($data['image'])) {
            $image = $this->saveBase64Image($data['image']);
        }
        
        $result = $this->productModel->addProduct($name, $description, $price, $category_id, $image);
        if (is_array($result)) {
            http_response_code(400);
            echo json_encode(['errors' => $result]);
        } else {
            http_response_code(201);
            echo json_encode(['message' => 'Product created successfully']);
        }
    }
    
    // Xử lý lưu ảnh base64
    private function saveBase64Image($base64String) {
        // Tạo thư mục uploads/products nếu chưa tồn tại
        $uploadDir = 'uploads/products/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
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
                    return $filePath;
                }
            }
        }
        
        return null;
    }
    
    // Cập nhật sản phẩm theo ID
    public function update($id)
    {
        header('Content-Type: application/json');
        $data = json_decode(file_get_contents("php://input"), true);
        $name = $data['name'] ?? '';
        $description = $data['description'] ?? '';
        $price = $data['price'] ?? '';
        $category_id = $data['category_id'] ?? null;
        
        // Lấy thông tin sản phẩm hiện tại để giữ ảnh cũ nếu không có ảnh mới
        $currentProduct = $this->productModel->getProductById($id);
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
        if ($result) {
            echo json_encode(['message' => 'Product updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product update failed']);
        }
    }
    
    // Xóa sản phẩm theo ID
    public function destroy($id)
    {
        header('Content-Type: application/json');
        
        // Lấy thông tin sản phẩm để xóa ảnh (nếu có)
        $product = $this->productModel->getProductById($id);
        if ($product && $product->image && file_exists($product->image)) {
            unlink($product->image);
        }
        
        $result = $this->productModel->deleteProduct($id);
        if ($result) {
            echo json_encode(['message' => 'Product deleted successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['message' => 'Product deletion failed']);
        }
    }
} 