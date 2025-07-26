<?php
require_once __DIR__ . '/../../config/config.php';
class Appointment {
    public static function getTodayConfirmed($therapist_id = '') {
        global $pdo;
        $today = date('Y-m-d');
        $sql = 'SELECT a.appointment_id, a.customer_name, s.service_name, t.therapist_name, a.date, a.time
                FROM appointments a
                JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                JOIN services s ON aps.service_id = s.service_id
                JOIN therapists t ON a.therapist_id = t.therapist_id
                WHERE a.status = "booked" AND a.date = ?';
        $params = [$today];
        if ($therapist_id) {
            $sql .= ' AND a.therapist_id = ?';
            $params[] = $therapist_id;
        }
        $sql .= ' ORDER BY a.time ASC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getYearlyMonthlyCounts($year = null) {
        global $pdo;
        if (!$year) $year = date('Y');
        $sql = 'SELECT MONTH(date) as month, COUNT(*) as count FROM appointments WHERE YEAR(date) = ? AND status != "cancelled" GROUP BY MONTH(date)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$year]);
        $data = array_fill(1, 12, 0);
        foreach ($stmt->fetchAll() as $row) {
            $data[(int)$row['month']] = (int)$row['count'];
        }
        return $data;
    }

    public static function getDailyCount($date = null) {
        global $pdo;
        if (!$date) $date = date('Y-m-d');
        $stmt = $pdo->prepare('SELECT COUNT(*) FROM appointments WHERE date = ? AND status != "cancelled"');
        $stmt->execute([$date]);
        return (int)$stmt->fetchColumn();
    }
    public static function getWeeklyCounts($start = null) {
        global $pdo;
        if (!$start) {
            $monday = date('Y-m-d', strtotime('monday this week'));
        } else {
            $monday = $start;
        }
        $days = [];
        for ($i = 0; $i < 7; $i++) {
            $days[] = date('Y-m-d', strtotime("$monday +$i day"));
        }
        $in = implode(',', array_fill(0, 7, '?'));
        $stmt = $pdo->prepare("SELECT date, COUNT(*) as count FROM appointments WHERE date IN ($in) AND status != 'cancelled' GROUP BY date");
        $stmt->execute($days);
        $data = array_fill_keys($days, 0);
        foreach ($stmt->fetchAll() as $row) {
            $data[$row['date']] = (int)$row['count'];
        }
        return $data;
    }
    public static function getMonthlyCounts($year = null, $month = null) {
        global $pdo;
        if (!$year) $year = date('Y');
        if (!$month) $month = date('m');
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        $dates = [];
        for ($i = 1; $i <= $daysInMonth; $i++) {
            $dates[] = sprintf('%04d-%02d-%02d', $year, $month, $i);
        }
        $in = implode(',', array_fill(0, count($dates), '?'));
        $stmt = $pdo->prepare("SELECT date, COUNT(*) as count FROM appointments WHERE date IN ($in) AND status != 'cancelled' GROUP BY date");
        $stmt->execute($dates);
        $data = array_fill_keys($dates, 0);
        foreach ($stmt->fetchAll() as $row) {
            $data[$row['date']] = (int)$row['count'];
        }
        return $data;
    }

    public static function getAll($start_date = '', $end_date = '', $status_filter = '') {
        global $pdo;
        $sql = 'SELECT a.appointment_id, a.customer_name, a.customer_phone_number, a.date, a.time, a.status, a.therapist_id, s.service_name, t.therapist_name
                FROM appointments a
                LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN services s ON aps.service_id = s.service_id
                LEFT JOIN therapists t ON a.therapist_id = t.therapist_id';
        $params = [];
        $where_conditions = [];
        
        if ($start_date && $end_date) {
            $where_conditions[] = 'a.date BETWEEN ? AND ?';
            $params[] = $start_date;
            $params[] = $end_date;
        }
        
        if ($status_filter) {
            $where_conditions[] = 'a.status = ?';
            $params[] = $status_filter;
        }
        
        if (!empty($where_conditions)) {
            $sql .= ' WHERE ' . implode(' AND ', $where_conditions);
        }
        
        $sql .= ' ORDER BY a.date DESC, a.time DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getAllPaginated($limit, $offset) {
        global $pdo;
        $sql = 'SELECT a.appointment_id, a.customer_name, a.customer_phone_number, a.date, a.time, a.status, a.therapist_id, s.service_name, t.therapist_name
                FROM appointments a
                LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN services s ON aps.service_id = s.service_id
                LEFT JOIN therapists t ON a.therapist_id = t.therapist_id
                ORDER BY a.date DESC, a.time DESC
                LIMIT ? OFFSET ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(2, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getByPhone($phone) {
        global $pdo;
        $searchPhone = trim($phone);
        
        // Normalize phone number to local format
        if (strpos($searchPhone, '+60') === 0) {
            $searchPhone = '0' . substr($searchPhone, 3);
        } else if (strpos($searchPhone, '60') === 0 && strlen($searchPhone) > 10) {
            $searchPhone = '0' . substr($searchPhone, 2);
        }
        
        // Try multiple formats for search
        $sql = 'SELECT a.appointment_id, a.customer_name, s.service_name, a.date, a.time, a.status, a.feedback
                FROM appointments a
                LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN services s ON aps.service_id = s.service_id
                WHERE TRIM(a.customer_phone_number) = ? 
                   OR TRIM(a.customer_phone_number) = ? 
                   OR TRIM(a.customer_phone_number) = ?
                ORDER BY a.date DESC, a.time DESC';
        
        // Generate different formats to search
        $formats = [
            $searchPhone, // Original normalized format
            strpos($searchPhone, '0') === 0 ? '+60' . substr($searchPhone, 1) : $searchPhone, // +60 format
            strpos($searchPhone, '0') === 0 ? '60' . substr($searchPhone, 1) : $searchPhone // 60 format
        ];
        
        $stmt = $pdo->prepare($sql);
        $stmt->execute($formats);
        
        // Debug logging
        $results = $stmt->fetchAll();
        $debug_msg = "Phone search: Original='{$phone}', Normalized='{$searchPhone}', Formats=" . json_encode($formats) . ", Results=" . count($results);
        file_put_contents(__DIR__ . '/../../public/debug.txt', "\n" . date('Y-m-d H:i:s') . " - " . $debug_msg, FILE_APPEND);
        
        return $results;
    }

    public static function getById($appointment_id) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT a.*, s.service_name FROM appointments a
            LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
            LEFT JOIN services s ON aps.service_id = s.service_id
            WHERE a.appointment_id = ?');
        $stmt->execute([$appointment_id]);
        return $stmt->fetch();
    }
    public static function saveFeedback($appointment_id, $feedback, $rating, $anonymous) {
        global $pdo;
        $stmt = $pdo->prepare('INSERT INTO feedback (appointment_id, feedback, rating, anonymous) VALUES (?, ?, ?, ?)');
        $stmt->execute([$appointment_id, $feedback, $rating, $anonymous]);
    }

    public static function generateShortId() {
        global $pdo;
        $stmt = $pdo->query("SELECT appointment_id FROM appointments WHERE appointment_id LIKE 'MS%' ORDER BY appointment_id DESC LIMIT 1");
        $last = $stmt->fetchColumn();
        if ($last) {
            $num = (int)substr($last, 2, 2);
            $num = ($num + 1) % 100; // 2 digit, roll over after 99
        } else {
            $num = 1;
        }
        $newId = 'MS' . str_pad($num, 2, '0', STR_PAD_LEFT);
        // Ensure uniqueness
        $check = $pdo->prepare("SELECT COUNT(*) FROM appointments WHERE appointment_id = ?");
        $check->execute([$newId]);
        if ($check->fetchColumn() > 0) {
            // If exists, try next
            for ($i = 0; $i < 100; $i++) {
                $num = ($num + 1) % 100;
                $newId = 'MS' . str_pad($num, 2, '0', STR_PAD_LEFT);
                $check->execute([$newId]);
                if ($check->fetchColumn() == 0) break;
            }
        }
        return $newId;
    }

    public static function create($customer_name, $customer_email, $customer_phone, $service_id, $therapist_id, $date, $time, $addon_therapist = null, $addon_service = null) {
        global $pdo;
        try {
            $pdo->beginTransaction();
            $status = 'booked';
            $addon_therapist_json = $addon_therapist ? json_encode($addon_therapist) : null;
            $addon_service_json = $addon_service ? json_encode($addon_service) : null;
            $stmt = $pdo->prepare('INSERT INTO appointments (customer_name, customer_email, customer_phone_number, therapist_id, date, time, status, addon_therapist, addon_service) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)');
            $stmt->execute([$customer_name, $customer_email, $customer_phone, $therapist_id, $date, $time, $status, $addon_therapist_json, $addon_service_json]);
            $appointment_id = $pdo->lastInsertId();
            $stmt2 = $pdo->prepare('INSERT INTO appointment_services (appointment_id, service_id) VALUES (?, ?)');
            $stmt2->execute([$appointment_id, $service_id]);
            $pdo->commit();
            return $appointment_id;
        } catch (Exception $e) {
            $pdo->rollBack();
            file_put_contents(__DIR__ . '/../../public/debug.txt', "\nDB ERROR: " . $e->getMessage(), FILE_APPEND);
            return false;
        }
    }

    public static function getBookedTimes($therapist_id, $date) {
        global $pdo;
        $stmt = $pdo->prepare('SELECT a.time, s.service_duration FROM appointments a
            JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
            JOIN services s ON aps.service_id = s.service_id
            WHERE a.therapist_id = ? AND a.date = ? AND a.status IN ("booked", "rescheduled")');
        $stmt->execute([$therapist_id, $date]);
        $results = $stmt->fetchAll();
        $booked = [];
        foreach ($results as $row) {
            $booked[] = [
                'time' => $row['time'],
                'duration' => (int)$row['service_duration']
            ];
        }
        return $booked;
    }

    // Update status to 'completed' for past appointments
    public static function updateStatusCompletedIfPast($therapist_id) {
        global $pdo;
        $sql = "UPDATE appointments SET status = 'completed' WHERE therapist_id = ? AND status != 'completed' AND CONCAT(date, ' ', time) < NOW()";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$therapist_id]);
    }

    // Get appointments by therapist and status (if status is not 'all')
    public static function getByTherapistAndStatus($therapist_id, $status = 'all') {
        global $pdo;
        $params = [$therapist_id];
        $sql = 'SELECT a.appointment_id, a.customer_name, a.date, a.time, a.status, s.service_name FROM appointments a LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id LEFT JOIN services s ON aps.service_id = s.service_id WHERE a.therapist_id = ?';
        if ($status && $status !== 'all') {
            if ($status === 'today') {
                $sql .= ' AND a.date = ?';
                $params[] = date('Y-m-d');
            } else {
                $sql .= ' AND a.status = ?';
                $params[] = $status;
            }
        }
        $sql .= ' ORDER BY a.date DESC, a.time DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Sync completed appointments to sales table
    public static function syncCompletedToSales() {
        global $pdo;
        $sql = "SELECT a.appointment_id, aps.service_id, s.service_price
                FROM appointments a
                JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                JOIN services s ON aps.service_id = s.service_id
                WHERE a.status = 'completed'
                AND NOT EXISTS (SELECT 1 FROM sales WHERE sales.appointment_id = a.appointment_id)";
        $stmt = $pdo->query($sql);
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
            $insert = $pdo->prepare('INSERT INTO sales (appointment_id, amount) VALUES (?, ?)');
            $insert->execute([$row['appointment_id'], $row['service_price']]);
        }
    }

    public static function getByDate($date) {
        global $pdo;
        $sql = 'SELECT a.appointment_id, a.customer_name, a.customer_phone_number, a.date, a.time, a.status, a.therapist_id, s.service_name, t.therapist_name
                FROM appointments a
                LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN services s ON aps.service_id = s.service_id
                LEFT JOIN therapists t ON a.therapist_id = t.therapist_id
                WHERE a.date = ?
                ORDER BY a.time DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$date]);
        return $stmt->fetchAll();
    }
    public static function getByDatePaginated($date, $limit, $offset) {
        global $pdo;
        $sql = 'SELECT a.appointment_id, a.customer_name, a.customer_phone_number, a.date, a.time, a.status, a.therapist_id, s.service_name, t.therapist_name
                FROM appointments a
                LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN services s ON aps.service_id = s.service_id
                LEFT JOIN therapists t ON a.therapist_id = t.therapist_id
                WHERE a.date = ?
                ORDER BY a.time DESC
                LIMIT ? OFFSET ?';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(1, $date);
        $stmt->bindValue(2, (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(3, (int)$offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public static function getAllDates() {
        global $pdo;
        $sql = 'SELECT DISTINCT date FROM appointments ORDER BY date DESC';
        $stmt = $pdo->query($sql);
        $dates = $stmt->fetchAll(PDO::FETCH_COLUMN);
        return $dates;
    }

    public static function getByMonthYearStatus($month, $year, $status_filter = '') {
        global $pdo;
        $sql = 'SELECT a.appointment_id, a.customer_name, a.customer_phone_number, a.date, a.time, a.status, a.therapist_id, s.service_name, t.therapist_name
                FROM appointments a
                LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN services s ON aps.service_id = s.service_id
                LEFT JOIN therapists t ON a.therapist_id = t.therapist_id
                WHERE MONTH(a.date) = ? AND YEAR(a.date) = ?';
        $params = [$month, $year];
        if ($status_filter) {
            $sql .= ' AND a.status = ?';
            $params[] = $status_filter;
        }
        $sql .= ' ORDER BY a.date DESC, a.time DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public static function getByMonthYearStatusTherapist($month, $year, $status_filter = '', $therapist_filter = '') {
        global $pdo;
        $sql = 'SELECT a.appointment_id, a.customer_name, a.customer_phone_number, a.date, a.time, a.status, a.therapist_id, s.service_name, t.therapist_name
                FROM appointments a
                LEFT JOIN appointment_services aps ON a.appointment_id = aps.appointment_id
                LEFT JOIN services s ON aps.service_id = s.service_id
                LEFT JOIN therapists t ON a.therapist_id = t.therapist_id
                WHERE MONTH(a.date) = ? AND YEAR(a.date) = ?';
        $params = [$month, $year];
        
        if ($status_filter) {
            $sql .= ' AND a.status = ?';
            $params[] = $status_filter;
        }
        
        if ($therapist_filter) {
            $sql .= ' AND a.therapist_id = ?';
            $params[] = $therapist_filter;
        }
        
        $sql .= ' ORDER BY a.date DESC, a.time DESC';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
} 