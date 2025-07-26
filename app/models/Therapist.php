<?php
require_once __DIR__ . '/../../config/config.php';

class Therapist {
    public static function register($name, $email, $password, $autoApprove = false) {
        global $pdo;
        // Check if email exists
        $stmt = $pdo->prepare('SELECT therapist_id FROM therapists WHERE therapist_email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            return 'Email already registered.';
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $is_approved = $autoApprove ? 1 : 0;
        $status = $autoApprove ? 1 : 0;
        $stmt = $pdo->prepare('INSERT INTO therapists (therapist_name, therapist_email, therapist_password, is_approved, status) VALUES (?, ?, ?, ?, ?)');
        if ($stmt->execute([$name, $email, $hash, $is_approved, $status])) {
            return true;
        }
        return 'Registration failed.';
    }

    public static function login($email, $password) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM therapists WHERE therapist_email = ?');
        $stmt->execute([$email]);
        $therapist = $stmt->fetch();
        if ($therapist && password_verify($password, $therapist['therapist_password']) && (!isset($therapist['status']) || $therapist['status'] == 1)) {
            return $therapist;
        }
        return false;
    }

    public static function updatePhoto($id, $photoPath) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE therapists SET therapist_photo = ? WHERE therapist_id = ?');
        $stmt->execute([$photoPath, $id]);
    }

    public static function updateProfile($id, $name, $phone) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE therapists SET therapist_name = ?, therapist_phone_number = ? WHERE therapist_id = ?');
        return $stmt->execute([$name, $phone, $id]);
    }
    public static function updatePassword($id, $newPassword) {
        global $pdo;
        $hash = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE therapists SET therapist_password = ? WHERE therapist_id = ?');
        return $stmt->execute([$hash, $id]);
    }

    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT therapist_id, therapist_name, therapist_email, therapist_phone_number, therapist_photo, therapist_strength, is_approved, status FROM therapists');
        return $stmt->fetchAll();
    }
    public static function getAllActive() {
        global $pdo;
        $stmt = $pdo->query('SELECT therapist_id, therapist_name, therapist_email, therapist_phone_number, therapist_photo, therapist_strength FROM therapists WHERE is_approved = 1 AND status = 1');
        return $stmt->fetchAll();
    }
    public static function getAllApproved() {
        global $pdo;
        $stmt = $pdo->query('SELECT therapist_id, therapist_name, therapist_email, therapist_phone_number, therapist_photo, therapist_strength FROM therapists WHERE is_approved = 1');
        return $stmt->fetchAll();
    }

    public static function delete($id) {
        global $pdo;
        $today = date('Y-m-d');
        // Check for future appointments
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM appointments WHERE therapist_id = ? AND date >= ?');
        $stmt->execute([$id, $today]);
        $count = $stmt->fetchColumn();
        if ($count > 0) {
            throw new \Exception('Therapist has future appointments.');
        }
        $stmt = $pdo->prepare('DELETE FROM therapists WHERE therapist_id = ?');
        return $stmt->execute([$id]);
    }

    public static function countUnapproved() {
        global $pdo;
        $stmt = $pdo->query('SELECT COUNT(*) FROM therapists WHERE is_approved = 0');
        return $stmt->fetchColumn();
    }
} 