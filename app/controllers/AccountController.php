<?php
class AccountModel {
private $conn;
private $table_name = "account";
public function __construct($db) {
$this->conn = $db;
}
public function getAccountByUsername($username) {
    $query = "SELECT * FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
    $stmt = $this->conn->prepare($query);
    $stmt->bindParam(":username", $username);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_OBJ);
    }
    public function save($username, $fullName, $password, $role = 'user') {
    if ($this->getAccountByUsername($username)) {
    return false;
    }
    $query = "INSERT INTO " . $this->table_name . " SET username=:username, fullname=:fullname, password=:password, role=:role";
    $stmt = $this->conn->prepare($query);
    $username = htmlspecialchars(strip_tags($username));
    $fullName = htmlspecialchars(strip_tags($fullName));
    $password = password_hash($password, PASSWORD_BCRYPT);
    $role = htmlspecialchars(strip_tags($role));
    $stmt->bindParam(":username", $username);
    $stmt->bindParam(":fullname", $fullName);
    $stmt->bindParam(":password", $password);
    $stmt->bindParam(":role", $role);
    return $stmt->execute();
    }
    }
    ?>
    <?php
    require_once('app/config/database.php');
    require_once('app/models/AccountModel.php');
    class AccountController {
    private $accountModel;
    private $db;
    public function __construct() {
    $this->db = (new Database())->getConnection();
    $this->accountModel = new AccountModel($this->db);
    }
    public function register() {    
    include_once 'app/views/account/register.php';
    }
    public function login() {
    include_once 'app/views/account/login.php';
    }
    public function save() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $fullName = $_POST['fullname'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirmpassword'] ?? '';
    $role = $_POST['role'] ?? 'user';
    $errors = [];
    if (empty($username)) $errors['username'] = "Vui lòng nhập username!";
    if (empty($fullName)) $errors['fullname'] = "Vui lòng nhập fullname!";
    if (empty($password)) $errors['password'] = "Vui lòng nhập password!";
    if ($password != $confirmPassword) $errors['confirmPass'] = "Mật khẩu và xác nhận chưa khớp!";
    if (!in_array($role, ['admin', 'user'])) $role = 'user';
    if ($this->accountModel->getAccountByUsername($username)) {
    $errors['account'] = "Tài khoản này đã được đăng ký!";
    }
    if (count($errors) > 0) {
    include_once 'app/views/account/register.php';
    } else {
    $result = $this->accountModel->save($username, $fullName, $password,
    $role);
    if ($result) {
    header('Location: /webbanhang/account/login');
    exit;
    }
    }
    }
    }
    public function logout() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        $_SESSION = array();
        
        session_destroy();
        
        header('Location: /webbanhang/Product');
        exit;
    }
    public function checkLogin() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $account = $this->accountModel->getAccountByUsername($username);
    if ($account && password_verify($password, $account->password)) {
    if (session_status() == PHP_SESSION_NONE) {
    session_start();
    }
    $_SESSION['loggedin'] = true;
    $_SESSION['username'] = $account->username;
    $_SESSION['role'] = $account->role;
    $_SESSION['fullname'] = $account->fullname;
    header('Location: /webbanhang/Product');
    exit;
    } else {
    $error = $account ? "Mật khẩu không đúng!" : "Không tìm thấy tài khoản!";
    include_once 'app/views/account/login.php';
    exit;
    }
    }
    }
    }
    ?>