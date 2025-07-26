<?php
require_once __DIR__ . '/../models/Admin.php';
require_once __DIR__ . '/../models/Therapist.php';
session_start();

class UserController {
    public function register() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['fullname'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (!$name || !$email || !$password) {
                $error = 'All fields are required.';
            } else if (str_ends_with($email, '@admin.mums.com')) {
                $result = Admin::register($name, $email, $password);
                if ($result === true) {
                    header('Location: /public/index.php?page=login&registered=1');
                    exit;
                } else {
                    $error = $result;
                }
            } else if (str_ends_with($email, '@staff.mums.com')) {
                $result = Therapist::register($name, $email, $password);
                if ($result === true) {
                    header('Location: /public/index.php?page=login&registered=1');
                    exit;
                } else {
                    $error = $result;
                }
            } else {
                $error = 'Wrong email';
            }
        }
        include '../app/views/user/register.php';
    }
    public function login() {
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            if (!$email || !$password) {
                $error = 'Email and password required.';
            } else if (str_ends_with($email, '@admin.mums.com')) {
                $admin = Admin::login($email, $password);
                if ($admin) {
                    $_SESSION['user'] = $admin;
                    $_SESSION['role'] = 'admin';
                    header('Location: /public/index.php?page=admin_dashboard');
                    exit;
                } else {
                    $error = 'Invalid email or password.';
                }
            } else if (str_ends_with($email, '@staff.mums.com')) {
                $therapist = Therapist::login($email, $password);
                if ($therapist) {
                    $_SESSION['user'] = $therapist;
                    $_SESSION['role'] = 'therapist';
                    header('Location: /public/index.php?page=therapist_dashboard');
                    exit;
                } else {
                    // Check if email exists and is inactive
                    global $pdo;
                    $stmt = $pdo->prepare('SELECT status, is_approved FROM therapists WHERE therapist_email = ?');
                    $stmt->execute([$email]);
                    $row = $stmt->fetch();
                    if ($row && isset($row['is_approved']) && $row['is_approved'] == 0) {
                        $error = 'Your account is not approved yet. Please contact admin.';
                    } else if ($row && isset($row['status']) && $row['status'] == 0) {
                        $error = 'Your account is inactive. Please contact admin.';
                    } else {
                        $error = 'Invalid email or password.';
                    }
                }
            } else {
                $error = 'Wrong email';
            }
        }
        include '../app/views/user/login.php';
    }
} 