<?php
class ProductModel
{
private $conn;
private $table_name = "product";
public function __construct($db)
{
$this->conn = $db;
}
public function getProducts()
{
$query = "SELECT p.id, p.name, p.description, p.price, p.image, c.name as
category_name
FROM " . $this->table_name . " p
LEFT JOIN category c ON p.category_id = c.id";
$stmt = $this->conn->prepare($query);
$stmt->execute();
$result = $stmt->fetchAll(PDO::FETCH_OBJ);
return $result;
}
public function getProductById($id)
{
$query = "SELECT p.*, c.name as category_name
FROM " . $this->table_name . " p
LEFT JOIN category c ON p.category_id = c.id
WHERE p.id = :id";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':id', $id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_OBJ);
return $result;
}
public function addProduct($name, $description, $price, $category_id, $image = null)
{
$errors = [];
if (empty($name)) {
$errors['name'] = 'Tên sản phẩm không được để trống';
}
if (empty($description)) {
$errors['description'] = 'Mô tả không được để trống';
}
if (!is_numeric($price) || $price < 0) {
$errors['price'] = 'Giá sản phẩm không hợp lệ';
}
if (count($errors) > 0) {
return $errors;
}

try {
$query = "INSERT INTO " . $this->table_name . " (name, description, price, category_id, image) VALUES (:name, :description, :price, :category_id, :image)";
$stmt = $this->conn->prepare($query);

$name = htmlspecialchars(strip_tags($name));
$description = htmlspecialchars(strip_tags($description));
$price = htmlspecialchars(strip_tags($price));

// Nếu category_id là chuỗi rỗng hoặc null, set nó thành NULL trong database
if (empty($category_id)) {
$category_id = null;
} else {
$category_id = htmlspecialchars(strip_tags($category_id));
}

$image = $image ? htmlspecialchars(strip_tags($image)) : null;

$stmt->bindParam(':name', $name);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':price', $price);
$stmt->bindParam(':category_id', $category_id);
$stmt->bindParam(':image', $image);

// Debug log
error_log("Adding product with: name=$name, price=$price, cat_id=" . ($category_id ?? 'NULL') . ", image=" . ($image ? 'yes' : 'no'));

if ($stmt->execute()) {
return true;
}

$errorInfo = $stmt->errorInfo();
error_log("Product add failed: " . ($errorInfo[2] ?? 'Unknown error'));
return false;
} catch (PDOException $e) {
error_log("PDO Error in addProduct: " . $e->getMessage());
throw $e;
} catch (Exception $e) {
error_log("General Error in addProduct: " . $e->getMessage());
throw $e;
}
}
public function updateProduct(
$id,
$name,
$description,
$price,
$category_id,
$image = null
) {
try {
// Kiểm tra dữ liệu
$errors = [];
if (empty($name)) {
$errors['name'] = 'Tên sản phẩm không được để trống';
}
if (empty($description)) {
$errors['description'] = 'Mô tả không được để trống';
}
if (!is_numeric($price) || $price < 0) {
$errors['price'] = 'Giá sản phẩm không hợp lệ';
}
if (count($errors) > 0) {
return $errors;
}

// Debug thông tin
error_log("Updating product ID: $id");
error_log("Name: $name, Price: $price, Category ID: " . ($category_id ?? 'NULL'));

$query = "UPDATE " . $this->table_name . " SET name=:name, description=:description, price=:price, category_id=:category_id, image=:image WHERE id=:id";
$stmt = $this->conn->prepare($query);

$name = htmlspecialchars(strip_tags($name));
$description = htmlspecialchars(strip_tags($description));
$price = htmlspecialchars(strip_tags($price));

// Nếu category_id là chuỗi rỗng hoặc null, set nó thành NULL trong database
if (empty($category_id)) {
$category_id = null;
} else {
$category_id = htmlspecialchars(strip_tags($category_id));
}

$image = $image ? htmlspecialchars(strip_tags($image)) : null;

$stmt->bindParam(':id', $id);
$stmt->bindParam(':name', $name);
$stmt->bindParam(':description', $description);
$stmt->bindParam(':price', $price);
$stmt->bindParam(':category_id', $category_id);
$stmt->bindParam(':image', $image);

// Debug SQL
error_log("SQL Query: " . $query);
error_log("Params: id=$id, name=$name, price=$price, cat_id=" . ($category_id ?? 'NULL') . ", image=" . ($image ? 'yes' : 'no'));

$result = $stmt->execute();

if ($result) {
error_log("Product updated successfully");
return true;
} else {
$errorInfo = $stmt->errorInfo();
error_log("Product update failed: " . ($errorInfo[2] ?? 'Unknown error'));
return false;
}
} catch (PDOException $e) {
error_log("PDO Error: " . $e->getMessage());
throw $e;
} catch (Exception $e) {
error_log("General Error: " . $e->getMessage());
throw $e;
}
}
public function deleteProduct($id)
{
$query = "DELETE FROM " . $this->table_name . " WHERE id=:id";
$stmt = $this->conn->prepare($query);
$stmt->bindParam(':id', $id);
if ($stmt->execute()) {
return true;
}
return false;
}
}