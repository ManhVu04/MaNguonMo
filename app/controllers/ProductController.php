<?php
require_once('app/helpers/SessionHelper.php');
require_once('app/config/database.php');
require_once('app/models/ProductModel.php');
require_once('app/models/CategoryModel.php');

class ProductController {
    private $productModel;
    private $db;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->productModel = new ProductModel($this->db);
    }

    public function index() {
        // Nếu có tham số search thì thực hiện tìm kiếm
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            return $this->search();
        }
        
        // Everyone can view products
        $products = $this->productModel->getProducts();
        include 'app/views/product/list.php';
    }

    public function add() {
        // Only admin can add products
        SessionHelper::requirePermission('add_product');
        
        $categories = (new CategoryModel($this->db))->getCategories();
        include_once 'app/views/product/add.php';
    }

    public function edit($id) {
        // Only admin can edit products
        SessionHelper::requirePermission('edit_product');
        
        $product = $this->productModel->getProductById($id);
        $categories = (new CategoryModel($this->db))->getCategories();
        if ($product) {
            include 'app/views/product/edit.php';
        } else {
            header('Location: /webbanhang/Product');
        }
    }

    public function delete($id) {
        // Only admin can delete products
        SessionHelper::requirePermission('delete_product');
        
        if ($this->productModel->deleteProduct($id)) {
            header('Location: /webbanhang/Product');
        }
    }

    public function addToCart($id) {
        // Only logged in users can add to cart
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit;
        }
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            echo "Không tìm thấy sản phẩm.";
            return;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity']++;
        } else {
            $_SESSION['cart'][$id] = [
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'image' => $product->image
            ];
        }
        header('Location: /webbanhang/Product/cart');
    }

    public function checkout() {
        // Only logged in users can checkout
        if (!SessionHelper::isLoggedIn()) {
            header('Location: /webbanhang/account/login');
            exit;
        }
        include 'app/views/product/checkout.php';
    }

    public function show($id) {
        $product = $this->productModel->getProductById($id);
        if ($product) {
            include 'app/views/product/show.php';
        } else {
            echo "Không thấy sản phẩm.";
        }
    }

    public function save() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'] ?? '';
            $description = $_POST['description'] ?? '';
            $price = $_POST['price'] ?? '';
            $category_id = $_POST['category_id'] ?? null;
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = "";
            }
            $result = $this->productModel->addProduct($name, $description, $price,
                $category_id, $image);
            if (is_array($result)) {
                $errors = $result;
                $categories = (new CategoryModel($this->db))->getCategories();
                include 'app/views/product/add.php';
            } else {
                header('Location: /webbanhang/Product');
            }
        }
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $name = $_POST['name'];
            $description = $_POST['description'];
            $price = $_POST['price'];
            $category_id = $_POST['category_id'];
            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $image = $this->uploadImage($_FILES['image']);
            } else {
                $image = $_POST['existing_image'];
            }
            $edit = $this->productModel->updateProduct($id, $name, $description,
                $price, $category_id, $image);
            if ($edit) {
                header('Location: /webbanhang/Product');
            } else {
                echo "Đã xảy ra lỗi khi lưu sản phẩm.";
            }
        }
    }

    private function uploadImage($file) {
        $target_dir = "uploads/";
        // Kiểm tra và tạo thư mục nếu chưa tồn tại
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $target_file = $target_dir . basename($file["name"]);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        // Kiểm tra xem file có phải là hình ảnh không
        $check = getimagesize($file["tmp_name"]);
        if ($check === false) {
            throw new Exception("File không phải là hình ảnh.");
        }
        // Kiểm tra kích thước file (10 MB = 10 * 1024 * 1024 bytes)
        if ($file["size"] > 10 * 1024 * 1024) {
            throw new Exception("Hình ảnh có kích thước quá lớn.");
        }
        // Chỉ cho phép một số định dạng hình ảnh nhất định
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType !=
            "jpeg" && $imageFileType != "gif") {
            throw new Exception("Chỉ cho phép các định dạng JPG, JPEG, PNG và GIF.");
        }
        // Lưu file
        if (!move_uploaded_file($file["tmp_name"], $target_file)) {
            throw new Exception("Có lỗi xảy ra khi tải lên hình ảnh.");
        }
        return $target_file;
    }

    public function cart() {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        include 'app/views/product/cart.php';
    }

    public function processCheckout() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = $_POST['name'];
            $phone = $_POST['phone'];
            $address = $_POST['address'];
            // Kiểm tra giỏ hàng
            if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
                echo "Giỏ hàng trống.";
                return;
            }
            // Bắt đầu giao dịch
            $this->db->beginTransaction();
            try {
                // Lưu thông tin đơn hàng vào bảng orders
                $query = "INSERT INTO orders (name, phone, address) VALUES (:name,
                    :phone, :address)";
                $stmt = $this->db->prepare($query);
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':phone', $phone);
                $stmt->bindParam(':address', $address);
                $stmt->execute();
                $order_id = $this->db->lastInsertId();
                // Lưu chi tiết đơn hàng vào bảng order_details
                $cart = $_SESSION['cart'];
                foreach ($cart as $product_id => $item) {
                    $query = "INSERT INTO order_details (order_id, product_id,
                        quantity, price) VALUES (:order_id, :product_id, :quantity, :price)";
                    $stmt = $this->db->prepare($query);
                    $stmt->bindParam(':order_id', $order_id);
                    $stmt->bindParam(':product_id', $product_id);
                    $stmt->bindParam(':quantity', $item['quantity']);
                    $stmt->bindParam(':price', $item['price']);
                    $stmt->execute();
                }
                // Xóa giỏ hàng sau khi đặt hàng thành công
                unset($_SESSION['cart']);
                // Commit giao dịch
                $this->db->commit();
                // Chuyển hướng đến trang xác nhận đơn hàng
                header('Location: /webbanhang/Product/orderConfirmation');
            } catch (Exception $e) {
                // Rollback giao dịch nếu có lỗi
                $this->db->rollBack();
                echo "Đã xảy ra lỗi khi xử lý đơn hàng: " . $e->getMessage();
            }
        }
    }

    public function orderConfirmation() {
        include 'app/views/product/orderConfirmation.php';
    }

    public function updateCart() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Invalid request method']);
            return;
        }

        $product_id = $_POST['product_id'] ?? null;
        $action = $_POST['action'] ?? null;
        $value = $_POST['value'] ?? null;

        if (!isset($_SESSION['cart'][$product_id])) {
            echo json_encode(['success' => false, 'message' => 'Sản phẩm không tồn tại trong giỏ hàng']);
            return;
        }

        switch ($action) {
            case 'increase':
                $_SESSION['cart'][$product_id]['quantity']++;
                break;
                
            case 'decrease':
                if ($_SESSION['cart'][$product_id]['quantity'] > 1) {
                    $_SESSION['cart'][$product_id]['quantity']--;
                }
                break;
                
            case 'input':
                $newQuantity = (int)$value;
                if ($newQuantity > 0) {
                    $_SESSION['cart'][$product_id]['quantity'] = $newQuantity;
                }
                break;
                
            case 'remove':
                unset($_SESSION['cart'][$product_id]);
                break;
                
            default:
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
                return;
        }

        if (empty($_SESSION['cart'])) {
            unset($_SESSION['cart']);
        }

        echo json_encode(['success' => true]);
    }

    public function listAPI()
    {
        // Hiển thị view list_api - danh sách sản phẩm sử dụng API
        require_once 'app/views/product/list_api.php';
    }

    public function showAPI($id)
    {
        // Hiển thị view chi tiết sản phẩm sử dụng API
        require_once 'app/views/product/show_api.php';
    }

    public function addAPI()
    {
        // Hiển thị view thêm sản phẩm sử dụng API
        require_once 'app/views/product/add_api.php';
    }

    public function editAPI($id)
    {
        // Hiển thị view chỉnh sửa sản phẩm sử dụng API
        require_once 'app/views/product/edit_api.php';
    }

    public function apiFrontend()
    {
        // Hiển thị trang frontend sử dụng jQuery để tương tác với API
        require_once 'app/views/product/api_frontend.php';
    }

    public function search()
    {
        $keyword = $_GET['keyword'] ?? '';
        $searchQuery = "%$keyword%";
        
        try {
            $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                      FROM product p 
                      LEFT JOIN category c ON p.category_id = c.id
                      WHERE p.name LIKE :keyword OR p.description LIKE :keyword";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':keyword', $searchQuery);
            $stmt->execute();
            
            $products = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Sử dụng view list.php để hiển thị kết quả
            include 'app/views/product/list.php';
            
        } catch (PDOException $e) {
            echo "Lỗi tìm kiếm: " . $e->getMessage();
        }
    }

    public function list()
    {
        // Nếu có tham số search thì thực hiện tìm kiếm
        if (isset($_GET['keyword']) && !empty($_GET['keyword'])) {
            return $this->search();
        }
        
        // Lấy danh sách sản phẩm từ model
        try {
            $query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as category_name
                      FROM product p 
                      LEFT JOIN category c ON p.category_id = c.id";
            $stmt = $this->db->prepare($query);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_OBJ);
            
            // Bao gồm file view để hiển thị sản phẩm
            include 'app/views/product/list.php';
        } catch (PDOException $e) {
            echo "Lỗi khi lấy danh sách sản phẩm: " . $e->getMessage();
        }
    }
}
?>