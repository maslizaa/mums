<?php
require_once __DIR__ . '/../../config/config.php';

class Feedback {
    public static function save($appointment_id, $comment, $rating, $is_visible) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO feedback (appointment_id, comment, rating, is_visible) VALUES (?, ?, ?, ?)');
        $stmt->execute([$appointment_id, $comment, $rating, $is_visible]);
    }

    public static function hasFeedback($appointment_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM feedback WHERE appointment_id = ?');
        $stmt->execute([$appointment_id]);
        return $stmt->fetchColumn() > 0;
    }

    public static function getByAppointmentId($appointment_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM feedback WHERE appointment_id = ? LIMIT 1');
        $stmt->execute([$appointment_id]);
        return $stmt->fetch();
    }

    public static function getAllByServiceId($service_id) {
        global $pdo;
        $stmt = $pdo->prepare('
            SELECT f.*, a.customer_name, a.date, a.time
            FROM feedback f
            JOIN appointments a ON f.appointment_id = a.appointment_id
            JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
            WHERE aps.service_id = ?
            ORDER BY f.created_at DESC
        ');
        $stmt->execute([$service_id]);
        return $stmt->fetchAll();
    }

    public static function getAllByAppointmentId($appointment_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM feedback WHERE appointment_id = ? ORDER BY created_at DESC');
        $stmt->execute([$appointment_id]);
        return $stmt->fetchAll();
    }

    public static function getAllWithDetails() {
        global $pdo;
        $stmt = $pdo->prepare('
            SELECT f.*, a.customer_name, a.date, a.time, s.service_name
            FROM feedback f
            JOIN appointments a ON f.appointment_id = a.appointment_id
            JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
            JOIN services s ON aps.service_id = s.service_id
            ORDER BY f.created_at DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllFeedback() {
        global $pdo;
        $stmt = $pdo->prepare('
            SELECT f.*, a.customer_name, s.service_name, a.date, a.time
            FROM feedback f
            JOIN appointments a ON f.appointment_id = a.appointment_id
            JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
            JOIN services s ON aps.service_id = s.service_id
            ORDER BY f.created_at DESC
        ');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function deleteById($feedback_id) {
        global $pdo;
        $stmt = $pdo->prepare('DELETE FROM feedback WHERE feedback_id = ?');
        return $stmt->execute([$feedback_id]);
    }
} 