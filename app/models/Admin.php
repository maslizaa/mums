<?php
require_once __DIR__ . '/../../config/config.php';

class Admin {
    public static function register($name, $email, $password) {
        global $pdo;
        // Check if email exists
        $stmt = $pdo->prepare('SELECT admin_id FROM admins WHERE admin_email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return 'Email already registered.';
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('INSERT INTO admins (admin_name, admin_email, admin_password) VALUES (?, ?, ?)');
        if ($stmt->execute([$name, $email, $hash])) {
            return true;
        }
        return 'Registration failed.';
    }

    public static function login($email, $password) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM admins WHERE admin_email = ?');
        $stmt->execute([$email]);
        $admin = $stmt->fetch();
        if ($admin && password_verify($password, $admin['admin_password'])) {
            return $admin;
        }
        return false;
    }

    public static function updateProfile($id, $name, $phone) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE admins SET admin_name = ?, admin_phone_number = ? WHERE admin_id = ?');
        return $stmt->execute([$name, $phone, $id]);
    }
    public static function updatePhoto($id, $photoPath) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE admins SET admin_photo = ? WHERE admin_id = ?');
        return $stmt->execute([$photoPath, $id]);
    }
    public static function updatePassword($id, $newPassword) {
        global $pdo;
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE admins SET admin_password = ? WHERE admin_id = ?');
        return $stmt->execute([$hash, $id]);
    }
} 