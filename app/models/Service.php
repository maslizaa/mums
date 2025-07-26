<?php
require_once __DIR__ . '/../../config/config.php';

class Service {
    public static function delete($id) {
        global $pdo;
        var_dump($pdo); // Tambah ini
        $stmt = $pdo->prepare('DELETE FROM services WHERE service_id = ?');
        $stmt->execute([$id]);
    }

    public static function getAll() {
        global $pdo;
        $stmt = $pdo->query('SELECT service_id, service_name, service_duration, service_price, service_description FROM services WHERE is_active = 1');
        return $stmt->fetchAll();
    }

    public static function getAllWithInactive() {
        global $pdo;
        $stmt = $pdo->query('SELECT service_id, service_name, service_duration, service_price, service_description, is_active FROM services');
        return $stmt->fetchAll();
    }

    public static function update($id, $name, $description, $duration, $price, $status) {
        global $pdo;
        $stmt = $pdo->prepare('UPDATE services SET service_name = ?, service_description = ?, service_duration = ?, service_price = ?, is_active = ? WHERE service_id = ?');
        return $stmt->execute([$name, $description, $duration, $price, $status, $id]);
    }

    public static function getById($id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT * FROM services WHERE service_id = ?');
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public static function add($name, $description, $duration, $price, $status) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO services (service_name, service_description, service_duration, service_price, is_active) VALUES (?, ?, ?, ?, ?)');
        return $stmt->execute([$name, $description, $duration, $price, $status]);
    }

    public static function getAllActive() {
        global $pdo;
        $stmt = $pdo->query('SELECT service_id, service_name, service_duration, service_price, service_description FROM services WHERE is_active = 1');
        return $stmt->fetchAll();
    }
}
