<?php
class SessionHelper {
    // Start session if not already started
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Check if user is logged in
    public static function isLoggedIn() {
        self::start();
        return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
    }

    // Check if user is admin
    public static function isAdmin() {
        self::start();
        return self::isLoggedIn() && isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Get user role, default is 'guest'
    public static function getRole() {
        self::start();
        return $_SESSION['role'] ?? 'guest';
    }

    // Check if user has permission for an action
    public static function hasPermission($action) {
        $role = self::getRole();
        
        // Define permissions for each role
        $permissions = [
            'admin' => [
                'view_products', 'add_product', 'edit_product', 'delete_product',
                'view_categories', 'add_category', 'edit_category', 'delete_category',
                'view_orders', 'manage_orders',
                'view_cart', 'checkout'
            ],
            'user' => [
                'view_products', 'view_categories',
                'view_cart', 'checkout'
            ],
            'guest' => [
                'view_products', 'view_categories'
            ]
        ];

        return in_array($action, $permissions[$role] ?? []);
    }

    // Require specific permission or redirect
    public static function requirePermission($action) {
        if (!self::hasPermission($action)) {
            header('Location: /webbanhang/account/login');
            exit;
        }
    }
}
?>