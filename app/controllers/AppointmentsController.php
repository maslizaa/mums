<?php
class AppointmentsController {
    public function history() {
        $appointments = [];
        if (isset($_GET['phone']) && $_GET['phone']) {
            $phone = trim($_GET['phone']);
            // Normalize phone number to local format
            if (strpos($phone, '+60') === 0) {
                $phone = '0' . substr($phone, 3);
            } else if (strpos($phone, '60') === 0) {
                $phone = '0' . substr($phone, 2);
            }
            require_once '../app/models/Appointment.php';
            $appointments = Appointment::getByPhone($phone);
        }
        include '../app/views/layouts/header.php';
        include '../app/views/appointments/history.php';
        include '../app/views/layouts/footer.php';
    }
    public function feedback() {
        require_once '../app/models/Appointment.php';
        $appointment = null;
        if (isset($_GET['appointment_id'])) {
            $appointment = Appointment::getById($_GET['appointment_id']);
        }
        $successMessage = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $appointment && isset($_POST['feedback'], $_POST['rating'])) {
            $is_anon = isset($_POST['anonymous']) ? 1 : 0;
            require_once '../app/models/Feedback.php';
            Feedback::save($appointment['appointment_id'], $_POST['feedback'], $_POST['rating'], $is_anon);
            header('Location: /public/index.php?page=feedback&appointment_id=' . urlencode($appointment['appointment_id']) . '&view=1&success=1');
            exit;
        }
        include '../app/views/layouts/header.php';
        include '../app/views/appointments/feedback.php';
        include '../app/views/layouts/footer.php';
    }
    public function booking_form() {
        session_start();
        require_once '../app/models/Service.php';
        require_once '../app/models/Therapist.php';
        require_once '../app/models/Appointment.php';
        $services = Service::getAll();
        $therapists = Therapist::getAllActive();
        $success = false;
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['full_name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $phone = trim($_POST['phone'] ?? '');
            // Normalize phone number to local format
            if (strpos($phone, '+60') === 0) {
                $phone = '0' . substr($phone, 3);
            } else if (strpos($phone, '60') === 0) {
                $phone = '0' . substr($phone, 2);
            }
            $service_id = $_POST['service'] ?? '';
            $therapist_id = $_POST['therapist'] ?? '';
            $date = $_POST['date'] ?? '';
            $time = $_POST['time'] ?? '';
            $addon_therapist = isset($_POST['addon_therapist']) ? $_POST['addon_therapist'] : null;
            $addon_service = isset($_POST['addon_service']) ? $_POST['addon_service'] : null;
            // Randomly assign therapist if 'any' is selected
            if ($therapist_id === 'any') {
                $approved_therapists = Therapist::getAllApproved();
                require_once '../app/models/Service.php';
                $service = Service::getById($service_id);
                $service_duration = isset($service['service_duration']) ? (int)$service['service_duration'] : 0;
                $available_therapists = [];
                foreach ($approved_therapists as $therapist) {
                    $booked_times = Appointment::getBookedTimes($therapist['therapist_id'], $date);
                    $is_available = true;
                    $requested_start = strtotime($time);
                    $requested_end = $requested_start + ($service_duration * 60);
                    foreach ($booked_times as $bt) {
                        $bt_start = strtotime($bt['time']);
                        $bt_end = $bt_start + ($bt['duration'] * 60);
                        // Check for overlap
                        if ($requested_start < $bt_end && $requested_end > $bt_start) {
                            $is_available = false;
                            break;
                        }
                    }
                    if ($is_available) {
                        $available_therapists[] = $therapist['therapist_id'];
                    }
                }
                if (!empty($available_therapists)) {
                    $therapist_id = $available_therapists[array_rand($available_therapists)];
                } else {
                    $error = 'No therapist available for the selected time.';
                }
            }
            // Validate phone number is numeric
            if (!preg_match('/^0\d{9,10}$/', $phone)) {
                $error = 'Nombor telefon mesti bermula dengan 0 dan mengandungi 10 atau 11 digit.';
            } else if (!$name || !$email || !$phone || !$service_id || !$therapist_id || !$date || !$time) {
                $error = 'Sila lengkapkan semua maklumat.';
            } else {
                $appointment_id = Appointment::create($name, $email, $phone, $service_id, $therapist_id, $date, $time, $addon_therapist, $addon_service);
                if ($appointment_id) {
                    $_SESSION['last_appointment'] = [
                        'name' => $name,
                        'email' => $email,
                        'phone' => $phone,
                        'service' => $service_id,
                        'therapist' => $therapist_id,
                        'date' => $date,
                        'time' => $time
                    ];
                    header('Location: /public/index.php?page=booking&success=1');
                    exit;
                } else {
                    $error = 'Appointment failed. Please try again.';
                }
            }
        }
        if (isset($_GET['success']) && $_GET['success'] == 1) {
            $success = true;
        }
        include '../app/views/layouts/header.php';
        include '../app/views/booking/booking_form.php';
        include '../app/views/layouts/footer.php';
    }
    public function availability() {
        header('Content-Type: application/json');
        $therapist_id = $_GET['therapist_id'] ?? null;
        $date = $_GET['date'] ?? null;
        if (!$therapist_id || !$date) {
            echo json_encode(['error' => 'Missing parameters']);
            exit;
        }
        require_once '../app/models/Appointment.php';
        $booked = Appointment::getBookedTimes($therapist_id, $date);
        echo json_encode(['booked' => $booked]);
        exit;
    }
    public function all_feedback() {
        require_once '../app/models/Feedback.php';
        $feedbacks = Feedback::getAllWithDetails();
        include '../app/views/all_feedback.php';
    }
} 